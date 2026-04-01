<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_GBP_Sync {
    public static function init() {
        add_action('admin_post_rw_bs_gbp_sync', array(__CLASS__, 'sync_now'));
        add_action('rw_bs_gbp_daily_sync', array(__CLASS__, 'sync_scheduled'));
    }

    public static function activate_schedule() {
        if (!wp_next_scheduled('rw_bs_gbp_daily_sync')) {
            wp_schedule_event(time() + 300, 'daily', 'rw_bs_gbp_daily_sync');
        }
    }

    public static function deactivate_schedule() {
        $timestamp = wp_next_scheduled('rw_bs_gbp_daily_sync');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'rw_bs_gbp_daily_sync');
        }
    }

    public static function sync_now() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('rw_bs_gbp_sync');
        self::perform_sync();

        wp_safe_redirect(admin_url('admin.php?page=rw-business-schema&tab=google&rw_bs_notice=synced'));
        exit;
    }

    public static function sync_scheduled() {
        self::perform_sync();
    }

    public static function perform_sync() {
        $access_token = RW_BS_GBP_OAuth::refresh_access_token();
        if (!$access_token) {
            return false;
        }

        $settings = rw_bs_get_settings();
        $location = trim((string) $settings['gbp_location']);
        if ($location === '') {
            return false;
        }

        $url = 'https://mybusiness.googleapis.com/v4/' . ltrim($location, '/') . '/reviews?pageSize=20&orderBy=updateTime%20desc';
        $response = wp_remote_get($url, array(
            'timeout' => 30,
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
                'Accept' => 'application/json',
            ),
        ));

        if (is_wp_error($response)) {
            return false;
        }

        $body = json_decode((string) wp_remote_retrieve_body($response), true);
        if (!is_array($body)) {
            return false;
        }

        $settings['rating_value'] = isset($body['averageRating']) ? (string) $body['averageRating'] : $settings['rating_value'];
        $settings['rating_count'] = isset($body['totalReviewCount']) ? (string) intval($body['totalReviewCount']) : $settings['rating_count'];
        $settings['gbp_last_sync'] = gmdate('c');

        rw_bs_update_settings($settings);

        $reviews = array();
        if (!empty($body['reviews']) && is_array($body['reviews'])) {
            foreach ($body['reviews'] as $review) {
                $star = isset($review['starRating']) ? sanitize_text_field($review['starRating']) : 'THREE';
                $map = array('ONE' => 1, 'TWO' => 2, 'THREE' => 3, 'FOUR' => 4, 'FIVE' => 5);

                $reviews[] = array(
                    'reviewer' => sanitize_text_field($review['reviewer']['displayName'] ?? 'Google User'),
                    'rating' => $map[$star] ?? 3,
                    'comment' => sanitize_textarea_field($review['comment'] ?? ''),
                    'updated' => sanitize_text_field($review['updateTime'] ?? ''),
                );
            }
        }

        set_transient('rw_bs_gbp_reviews_cache', $reviews, DAY_IN_SECONDS * 30);
        return true;
    }
}
