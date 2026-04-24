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

/**
 * Returns the list of country presets available in the admin dropdown.
 * Keys and values are both the full country name (stored as the country field value).
 *
 * @return array<string, string>
 */
function rw_bs_get_country_presets() {
    return array(
        'United States'  => 'United States',
        'Canada'         => 'Canada',
        'Mexico'         => 'Mexico',
        'United Kingdom' => 'United Kingdom',
        'Germany'        => 'Germany',
        'France'         => 'France',
        'Spain'          => 'Spain',
        'Italy'          => 'Italy',
        'Netherlands'    => 'Netherlands',
    );
}

/**
 * Returns a map of country name => international dialing prefix (e.g. '+1').
 *
 * @return array<string, string>
 */
function rw_bs_get_dialing_codes() {
    return array(
        'United States'  => '+1',
        'Canada'         => '+1',
        'Mexico'         => '+52',
        'United Kingdom' => '+44',
        'Germany'        => '+49',
        'France'         => '+33',
        'Spain'          => '+34',
        'Italy'          => '+39',
        'Netherlands'    => '+31',
    );
}

/**
 * Returns a phone number with its international dialing prefix applied.
 * If the phone already starts with '+', it is returned as-is.
 * If the country has no known code, the raw phone value is returned.
 *
 * @param string $phone   The raw phone number as entered by the user.
 * @param string $country The country name (must match a key in rw_bs_get_dialing_codes()).
 * @return string
 */
function rw_bs_get_phone_international( $phone, $country = '' ) {
    $phone   = trim( (string) $phone );
    $country = trim( (string) $country );

    if ( $phone === '' ) {
        return '';
    }

    // Already has an international prefix — respect it as-is.
    if ( strpos( $phone, '+' ) === 0 ) {
        return $phone;
    }

    $codes = rw_bs_get_dialing_codes();
    $code  = isset( $codes[ $country ] ) ? $codes[ $country ] : '';

    if ( $code === '' ) {
        return $phone;
    }

    $digits = preg_replace( '/\D/', '', $phone );

    // US/Canada share +1. Strip a leading '1' if the user typed a full 11-digit NANP number
    // (e.g. 14155551234) to avoid producing +114155551234.
    if ( $code === '+1' && strlen( $digits ) === 11 && $digits[0] === '1' ) {
        $digits = substr( $digits, 1 );
    }

    return $code . $digits;
}

/**
 * Returns a tel: URI string suitable for use in href attributes and Elementor dynamic link controls.
 * Returns an empty string when the phone is empty.
 *
 * @param string $phone   The raw phone number.
 * @param string $country The country name.
 * @return string  e.g. "tel:+14155551234"
 */
function rw_bs_get_phone_tel_link( $phone, $country = '' ) {
    $international = rw_bs_get_phone_international( $phone, $country );

    if ( $international === '' ) {
        return '';
    }

    // tel: URIs allow digits and a leading '+' only — strip spaces, dashes, parentheses, etc.
    $clean = preg_replace( '/[^\d+]/', '', $international );

    return 'tel:' . $clean;
}
