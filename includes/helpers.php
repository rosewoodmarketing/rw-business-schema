<?php

if (!defined('ABSPATH')) {
    exit;
}

function rw_bs_get_settings() {
    $defaults = array(
        'business_type' => 'LocalBusiness',
        'company_name' => '',
        'street' => '',
        'city' => '',
        'state' => '',
        'postal_code' => '',
        'country' => '',
        'phone_primary' => '',
        'phone_secondary' => '',
        'email_primary' => '',
        'email_secondary' => '',
        'website' => '',
        'price_range' => '',
        'hours_display' => '',
        'hours' => rw_bs_default_hours(),
        'type_fields' => array(),
        'rating_value' => '',
        'rating_count' => '',
        'multi_location' => false,
        'location_public' => false,
        'location_slug' => 'business-location',
        'schema_target_page_id' => 0,
        'schema_target_path' => '',
        'github_token' => '',
        'gbp_client_id' => '',
        'gbp_client_secret' => '',
        'gbp_location' => '',
        'gbp_last_sync' => '',
    );

    return wp_parse_args(get_option(RW_BS_OPTION_KEY, array()), $defaults);
}

function rw_bs_update_settings($settings) {
    update_option(RW_BS_OPTION_KEY, $settings, false);
}

function rw_bs_default_hours() {
    $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
    $hours = array();

    foreach ($days as $day) {
        $hours[] = array(
            'day' => $day,
            'closed' => false,
            'open' => '09:00',
            'close' => '17:00',
        );
    }

    return $hours;
}

function rw_bs_get_field($key, $default = '') {
    $settings = rw_bs_get_settings();

    if ($key === 'type_fields') {
        return is_array($settings['type_fields']) ? $settings['type_fields'] : array();
    }

    return isset($settings[$key]) ? $settings[$key] : $default;
}

function rw_bs_get_full_address() {
    return rw_bs_get_full_address_from_settings(rw_bs_get_settings());
}

function rw_bs_get_full_address_from_settings($settings) {
    $parts = array_filter(array(
        is_array($settings) ? ($settings['street'] ?? '') : '',
        is_array($settings) ? ($settings['city'] ?? '') : '',
        is_array($settings) ? ($settings['state'] ?? '') : '',
        is_array($settings) ? ($settings['postal_code'] ?? '') : '',
        is_array($settings) ? ($settings['country'] ?? '') : '',
    ));

    return implode(', ', $parts);
}

function rw_bs_encrypt_value($plaintext) {
    $plaintext = (string) $plaintext;
    if ($plaintext === '' || !function_exists('openssl_encrypt')) {
        return $plaintext;
    }

    $key_material = wp_salt('auth') . AUTH_KEY;
    $key = hash('sha256', $key_material, true);
    $cipher = 'AES-256-CBC';
    $iv_length = openssl_cipher_iv_length($cipher);
    $iv = random_bytes($iv_length);
    $encrypted = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);

    if ($encrypted === false) {
        return $plaintext;
    }

    return 'enc:' . base64_encode($iv . $encrypted);
}

function rw_bs_decrypt_value($ciphertext) {
    $ciphertext = (string) $ciphertext;
    if ($ciphertext === '' || strpos($ciphertext, 'enc:') !== 0 || !function_exists('openssl_decrypt')) {
        return $ciphertext;
    }

    $payload = base64_decode(substr($ciphertext, 4), true);
    if ($payload === false) {
        return '';
    }

    $key_material = wp_salt('auth') . AUTH_KEY;
    $key = hash('sha256', $key_material, true);
    $cipher = 'AES-256-CBC';
    $iv_length = openssl_cipher_iv_length($cipher);
    $iv = substr($payload, 0, $iv_length);
    $encrypted = substr($payload, $iv_length);

    if ($iv === '' || $encrypted === '') {
        return '';
    }

    $decrypted = openssl_decrypt($encrypted, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    return $decrypted === false ? '' : $decrypted;
}
