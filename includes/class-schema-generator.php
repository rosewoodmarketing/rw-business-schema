<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_Schema_Generator {
    public static function init() {
        add_action('wp_head', array(__CLASS__, 'output_schema'), 1);
        add_filter('wpseo_schema_organization', array(__CLASS__, 'merge_into_yoast'), 20);
    }

    /**
     * Adds the business details to Yoast's site-wide Organization node so search engines
     * see one business entity on every page instead of two unlinked ones.
     */
    public static function merge_into_yoast($data) {
        $settings = rw_bs_get_settings();
        if (!rw_bs_use_yoast_merge($settings) || !is_array($data)) {
            return $data;
        }

        $schema = self::build_schema($settings);
        if (empty($schema['name'])) {
            return $data;
        }

        $type = $schema['@type'];
        unset($schema['@context'], $schema['@type'], $schema['@id']);

        $data['@type'] = array_values(array_unique(array_merge((array) ($data['@type'] ?? 'Organization'), array($type))));

        // Keep Yoast's logo object; merge social profiles from both sources.
        unset($schema['logo']);
        if (!empty($data['sameAs']) && !empty($schema['sameAs'])) {
            $merged = array();
            foreach (array_merge($schema['sameAs'], (array) $data['sameAs']) as $url) {
                $merged[strtolower(untrailingslashit($url))] ??= $url;
            }
            $schema['sameAs'] = array_values($merged);
        }

        return array_merge($data, $schema);
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
            'telephone' => rw_bs_get_phone_international(sanitize_text_field($settings['phone_primary']), sanitize_text_field($settings['country'])),
            'email' => sanitize_email($settings['email_primary']),
            'url' => esc_url($settings['website']),
            'priceRange' => sanitize_text_field($settings['price_range']),
        );

        if (!empty($settings['description'])) {
            $schema['description'] = sanitize_textarea_field($settings['description']);
        }

        if (!empty($settings['logo_url'])) {
            $schema['image'] = esc_url_raw($settings['logo_url']);
            $schema['logo'] = esc_url_raw($settings['logo_url']);
        }

        if (is_numeric($settings['latitude'] ?? '') && is_numeric($settings['longitude'] ?? '')) {
            $schema['geo'] = array(
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $settings['latitude'],
                'longitude' => (float) $settings['longitude'],
            );
        }

        if (!empty($settings['map_url'])) {
            $schema['hasMap'] = esc_url_raw($settings['map_url']);
        }

        $same_as = array_values(array_filter(array_map('esc_url_raw', rw_bs_lines($settings['same_as'] ?? ''))));
        if (!empty($same_as)) {
            $schema['sameAs'] = $same_as;
        }

        $area_served = self::build_area_served($settings['area_served'] ?? '');
        if (!empty($area_served)) {
            $schema['areaServed'] = $area_served;
        }

        $hours_spec = RW_BS_Hours_Handler::to_opening_hours_specification($settings['hours']);
        if (!empty($hours_spec)) {
            $schema['openingHoursSpecification'] = $hours_spec;
        }

        // Google ignores self-reported ratings on a business's own schema and expects the reviews to be visible,
        // so this is opt-in.
        if (!empty($settings['schema_include_rating']) && $settings['rating_value'] !== '' && $settings['rating_count'] !== '') {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => (string) floatval($settings['rating_value']),
                'reviewCount' => (string) intval($settings['rating_count']),
            );
        }

        $types = RW_BS_Type_Definitions::get_flat_types();
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

    /**
     * One place per line. "City, State" becomes a City inside a State; anything else is kept as plain text.
     */
    private static function build_area_served($value) {
        $areas = array();

        foreach (rw_bs_lines($value) as $line) {
            $line = sanitize_text_field($line);
            $parts = array_map('trim', explode(',', $line, 2));

            if (count($parts) === 2 && $parts[0] !== '' && $parts[1] !== '') {
                $areas[] = array(
                    '@type' => 'City',
                    'name' => $parts[0],
                    'containedInPlace' => array('@type' => 'State', 'name' => $parts[1]),
                );
            } else {
                $areas[] = $line;
            }
        }

        return $areas;
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

    /**
     * Gives a location block its own @id and, when Yoast is active, links it to the site's Organization.
     */
    private static function with_location_ids($schema, $url) {
        $schema = array('@context' => 'https://schema.org', '@id' => trailingslashit((string) $url) . '#localbusiness') + $schema;

        if (defined('WPSEO_VERSION')) {
            $schema['parentOrganization'] = array('@id' => home_url('/#organization'));
        }

        return $schema;
    }

    public static function output_schema() {
        if (is_admin()) {
            return;
        }

        $settings = rw_bs_get_settings();
        $is_multi = !empty($settings['multi_location']);

        if ($is_multi && class_exists('RW_BS_Locations')) {
            if (is_singular(RW_BS_Locations::CPT)) {
                $location_id = absint(get_queried_object_id());
                if ($location_id > 0) {
                    $loc_schema = self::with_location_ids(self::build_schema(RW_BS_Locations::get_location_settings($location_id)), get_permalink($location_id));
                    if (!empty($loc_schema['name'])) {
                        echo '<script type="application/ld+json">' . wp_json_encode($loc_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
                    }
                }

                return;
            }

            $locations = RW_BS_Locations::get_all_locations();
            foreach ($locations as $loc) {
                $target = RW_BS_Locations::get_location_target($loc->ID);
                if (!self::should_output_for_target($target['page_id'] ?? 0, $target['path'] ?? '')) {
                    continue;
                }

                $loc_schema = self::with_location_ids(self::build_schema(RW_BS_Locations::get_location_settings($loc->ID)), get_permalink($loc->ID));
                if (!empty($loc_schema['name'])) {
                    echo '<script type="application/ld+json">' . wp_json_encode($loc_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
                }
            }

            return;
        }

        // Yoast mode: the details are already in Yoast's Organization on every page.
        if (rw_bs_use_yoast_merge($settings)) {
            return;
        }

        if (!self::should_output_for_target($settings['schema_target_page_id'] ?? 0, $settings['schema_target_path'] ?? '')) {
            return;
        }

        $schema = array('@context' => 'https://schema.org', '@id' => home_url('/#localbusiness')) + self::build_schema($settings);
        if (!empty($schema['name'])) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
        }
    }
}
