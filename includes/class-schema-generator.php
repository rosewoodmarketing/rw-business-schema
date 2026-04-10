<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_Schema_Generator {
    public static function init() {
        add_action('wp_head', array(__CLASS__, 'output_schema'), 1);
    }

    public static function build_schema($settings = null) {
        if ($settings === null) {
            $settings = rw_bs_get_settings();
        }
        $type = in_array($settings['business_type'], RW_BS_Type_Definitions::get_type_keys(), true)
            ? $settings['business_type']
            : 'LocalBusiness';

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => $type,
            'name' => sanitize_text_field($settings['company_name']),
            'address' => array(
                '@type' => 'PostalAddress',
                'streetAddress' => sanitize_text_field($settings['street']),
                'addressLocality' => sanitize_text_field($settings['city']),
                'addressRegion' => sanitize_text_field($settings['state']),
                'postalCode' => sanitize_text_field($settings['postal_code']),
                'addressCountry' => sanitize_text_field($settings['country']),
            ),
            'telephone' => sanitize_text_field($settings['phone_primary']),
            'email' => sanitize_email($settings['email_primary']),
            'url' => esc_url($settings['website']),
            'priceRange' => sanitize_text_field($settings['price_range']),
        );

        $hours_spec = RW_BS_Hours_Handler::to_opening_hours_specification($settings['hours']);
        if (!empty($hours_spec)) {
            $schema['openingHoursSpecification'] = $hours_spec;
        }

        if ($settings['rating_value'] !== '' && $settings['rating_count'] !== '') {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => (string) floatval($settings['rating_value']),
                'reviewCount' => (string) intval($settings['rating_count']),
            );
        }

        $types = RW_BS_Type_Definitions::get_types();
        $type_fields = isset($settings['type_fields']) && is_array($settings['type_fields']) ? $settings['type_fields'] : array();

        if (isset($types[$type]['fields'])) {
            foreach ($types[$type]['fields'] as $field_key) {
                if (!isset($type_fields[$field_key]) || $type_fields[$field_key] === '') {
                    continue;
                }

                $value = $type_fields[$field_key];

                if ($field_key === 'petsAllowed' || $field_key === 'acceptsReservations') {
                    $value = (bool) $value;
                }

                if ($field_key === 'starRating') {
                    $value = array(
                        '@type' => 'Rating',
                        'ratingValue' => (string) floatval($value),
                    );
                }

                $schema[$field_key] = $value;
            }
        }

        return array_filter($schema, array(__CLASS__, 'remove_empty'));
    }

    public static function remove_empty($value) {
        if (is_array($value)) {
            return !empty(array_filter($value, array(__CLASS__, 'remove_empty')));
        }

        return $value !== null && $value !== '';
    }

    private static function normalize_path($path) {
        $path = trim((string) $path);
        if ($path === '') {
            return '';
        }

        if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
            $parsed_path = wp_parse_url($path, PHP_URL_PATH);
            $path = is_string($parsed_path) ? $parsed_path : '';
        }

        if ($path === '') {
            return '';
        }

        $path = '/' . ltrim($path, '/');
        $path = preg_replace('/\/+/', '/', $path);

        if ($path !== '/') {
            $path = untrailingslashit($path) . '/';
        }

        return strtolower($path);
    }

    private static function get_current_request_path() {
        $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '/';
        $path = wp_parse_url($request_uri, PHP_URL_PATH);

        if (!is_string($path) || $path === '') {
            $path = '/';
        }

        $site_path = wp_parse_url(home_url('/'), PHP_URL_PATH);
        if (is_string($site_path) && $site_path !== '' && $site_path !== '/') {
            $site_path = rtrim($site_path, '/');
            if (strpos($path, $site_path) === 0) {
                $path = substr($path, strlen($site_path));
                if ($path === false || $path === '') {
                    $path = '/';
                }
            }
        }

        return self::normalize_path($path);
    }

    private static function should_output_for_target($page_id, $path) {
        $page_id = absint($page_id);
        $path = self::normalize_path($path);

        if ($page_id > 0 && is_page($page_id)) {
            return true;
        }

        if ($path !== '' && self::get_current_request_path() === $path) {
            return true;
        }

        return false;
    }

    public static function output_schema() {
        if (is_admin()) {
            return;
        }

        $settings = rw_bs_get_settings();
        $is_multi = !empty($settings['multi_location']);

        if ($is_multi && class_exists('RW_BS_Locations')) {
            $locations = RW_BS_Locations::get_all_locations();
            foreach ($locations as $loc) {
                $target = RW_BS_Locations::get_location_target($loc->ID);
                if (!self::should_output_for_target($target['page_id'] ?? 0, $target['path'] ?? '')) {
                    continue;
                }

                $loc_schema = self::build_schema(RW_BS_Locations::get_location_settings($loc->ID));
                if (!empty($loc_schema['name'])) {
                    echo '<script type="application/ld+json">' . wp_json_encode($loc_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
                }
            }

            return;
        }

        if (!self::should_output_for_target($settings['schema_target_page_id'] ?? 0, $settings['schema_target_path'] ?? '')) {
            return;
        }

        $schema = self::build_schema($settings);
        if (!empty($schema['name'])) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
        }
    }
}
