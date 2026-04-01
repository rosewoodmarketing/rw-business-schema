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

    public static function output_schema() {
        if (is_admin()) {
            return;
        }

        $schema = self::build_schema();
        if (!empty($schema['name'])) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
        }

        if (!empty(rw_bs_get_field('multi_location')) && class_exists('RW_BS_Locations')) {
            $locations = RW_BS_Locations::get_all_locations();
            foreach ($locations as $loc) {
                $loc_schema = self::build_schema(RW_BS_Locations::get_location_settings($loc->ID));
                if (!empty($loc_schema['name'])) {
                    echo '<script type="application/ld+json">' . wp_json_encode($loc_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
                }
            }
        }
    }
}
