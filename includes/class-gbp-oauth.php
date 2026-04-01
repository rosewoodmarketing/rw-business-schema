<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_GBP_OAuth {
    public static function init() {
        add_action('admin_post_rw_bs_gbp_connect', array(__CLASS__, 'start_oauth'));
        add_action('admin_post_rw_bs_gbp_callback', array(__CLASS__, 'handle_callback'));
        add_action('admin_post_rw_bs_gbp_disconnect', array(__CLASS__, 'disconnect'));
    }

    public static function get_redirect_uri() {
        return admin_url('admin-post.php?action=rw_bs_gbp_callback');
    }

    public static function start_oauth() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('rw_bs_gbp_connect');

        $settings = rw_bs_get_settings();
        $client_id = trim((string) $settings['gbp_client_id']);

        if ($client_id === '') {
            wp_safe_redirect(admin_url('admin.php?page=rw-business-schema&tab=google&rw_bs_notice=missing_client'));
            exit;
        }

        $state = wp_generate_password(48, false, false);
        set_transient('rw_bs_gbp_state_' . get_current_user_id(), $state, MINUTE_IN_SECONDS * 10);

        $params = array(
            'client_id' => $client_id,
            'redirect_uri' => self::get_redirect_uri(),
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/business.manage',
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $state,
        );

        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);

        wp_safe_redirect($url);
        exit;
    }

    public static function handle_callback() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        $state = isset($_GET['state']) ? sanitize_text_field(wp_unslash($_GET['state'])) : '';
        $saved_state = (string) get_transient('rw_bs_gbp_state_' . get_current_user_id());

        if (!$state || !$saved_state || !hash_equals($saved_state, $state)) {
            wp_safe_redirect(admin_url('admin.php?page=rw-business-schema&tab=google&rw_bs_notice=bad_state'));
            exit;
        }

        delete_transient('rw_bs_gbp_state_' . get_current_user_id());

        $code = isset($_GET['code']) ? sanitize_text_field(wp_unslash($_GET['code'])) : '';
        if ($code === '') {
            wp_safe_redirect(admin_url('admin.php?page=rw-business-schema&tab=google&rw_bs_notice=missing_code'));
            exit;
        }

        $settings = rw_bs_get_settings();
        $client_id = trim((string) $settings['gbp_client_id']);
        $client_secret = trim((string) $settings['gbp_client_secret']);

        $response = wp_remote_post('https://oauth2.googleapis.com/token', array(
            'timeout' => 20,
            'body' => array(
                'code' => $code,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'redirect_uri' => self::get_redirect_uri(),
                'grant_type' => 'authorization_code',
            ),
        ));

        if (is_wp_error($response)) {
            wp_safe_redirect(admin_url('admin.php?page=rw-business-schema&tab=google&rw_bs_notice=token_error'));
            exit;
        }

        $body = json_decode((string) wp_remote_retrieve_body($response), true);
        if (!is_array($body) || empty($body['access_token'])) {
            wp_safe_redirect(admin_url('admin.php?page=rw-business-schema&tab=google&rw_bs_notice=token_missing'));
            exit;
        }

        $tokens = array(
            'access_token' => rw_bs_encrypt_value(sanitize_text_field($body['access_token'])),
            'refresh_token' => isset($body['refresh_token']) ? rw_bs_encrypt_value(sanitize_text_field($body['refresh_token'])) : '',
            'expires_at' => time() + intval($body['expires_in'] ?? 3600),
        );

        update_option(RW_BS_GBP_TOKEN_KEY, $tokens, false);

        wp_safe_redirect(admin_url('admin.php?page=rw-business-schema&tab=google&rw_bs_notice=connected'));
        exit;
    }

    public static function refresh_access_token() {
        $tokens = get_option(RW_BS_GBP_TOKEN_KEY, array());
        $refresh_token = isset($tokens['refresh_token']) ? rw_bs_decrypt_value($tokens['refresh_token']) : '';
        if ($refresh_token === '') {
            return false;
        }

        $access_token = isset($tokens['access_token']) ? rw_bs_decrypt_value($tokens['access_token']) : '';
        if ($access_token !== '' && !empty($tokens['expires_at']) && intval($tokens['expires_at']) > time() + 60) {
            return $access_token;
        }

        $settings = rw_bs_get_settings();
        $client_id = trim((string) $settings['gbp_client_id']);
        $client_secret = trim((string) $settings['gbp_client_secret']);

        if ($client_id === '' || $client_secret === '') {
            return false;
        }

        $response = wp_remote_post('https://oauth2.googleapis.com/token', array(
            'timeout' => 20,
            'body' => array(
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'refresh_token' => $refresh_token,
                'grant_type' => 'refresh_token',
            ),
        ));

        if (is_wp_error($response)) {
            return false;
        }

        $body = json_decode((string) wp_remote_retrieve_body($response), true);
        if (!is_array($body) || empty($body['access_token'])) {
            return false;
        }

        $tokens['access_token'] = rw_bs_encrypt_value(sanitize_text_field($body['access_token']));
        $tokens['expires_at'] = time() + intval($body['expires_in'] ?? 3600);
        update_option(RW_BS_GBP_TOKEN_KEY, $tokens, false);

        return rw_bs_decrypt_value($tokens['access_token']);
    }

    public static function disconnect() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('rw_bs_gbp_disconnect');

        delete_option(RW_BS_GBP_TOKEN_KEY);
        wp_safe_redirect(admin_url('admin.php?page=rw-business-schema&tab=google&rw_bs_notice=disconnected'));
        exit;
    }
}
