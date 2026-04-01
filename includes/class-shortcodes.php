<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_Shortcodes {
    private static function get_allowed_field_keys() {
        $base = array(
            'business_type', 'company_name', 'street', 'city', 'state', 'postal_code', 'country',
            'phone_primary', 'phone_secondary', 'email_primary', 'email_secondary', 'website',
            'price_range', 'hours_display', 'rating_value', 'rating_count', 'gbp_last_sync',
        );

        $type_keys = array();
        foreach (RW_BS_Type_Definitions::get_groups() as $fields) {
            $type_keys = array_merge($type_keys, array_keys($fields));
        }

        return array_values(array_unique(array_merge($base, $type_keys)));
    }

    public static function init() {
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
        add_shortcode('rw_business_card', array(__CLASS__, 'business_card'));
        add_shortcode('rw_business_hours', array(__CLASS__, 'business_hours'));
        add_shortcode('rw_business_field', array(__CLASS__, 'business_field'));
        add_shortcode('rw_business_reviews', array(__CLASS__, 'business_reviews'));
    }

    public static function enqueue_assets() {
        wp_enqueue_style('rw-bs-shortcodes', RW_BS_URL . 'public/css/shortcode-display.css', array(), RW_BS_VERSION);
    }

    public static function business_card($atts = array()) {
        $settings = rw_bs_get_settings();

        $html = '<div class="rw-bs-card">';
        $html .= '<h3>' . esc_html($settings['company_name']) . '</h3>';
        $html .= '<p>' . esc_html(rw_bs_get_full_address()) . '</p>';

        if (!empty($settings['phone_primary'])) {
            $html .= '<p><strong>Phone:</strong> ' . esc_html($settings['phone_primary']) . '</p>';
        }

        if (!empty($settings['phone_secondary'])) {
            $html .= '<p><strong>Secondary Phone:</strong> ' . esc_html($settings['phone_secondary']) . '</p>';
        }

        if (!empty($settings['email_primary'])) {
            $html .= '<p><strong>Email:</strong> ' . esc_html($settings['email_primary']) . '</p>';
        }

        if (!empty($settings['email_secondary'])) {
            $html .= '<p><strong>Secondary Email:</strong> ' . esc_html($settings['email_secondary']) . '</p>';
        }

        $html .= '</div>';

        return $html;
    }

    public static function business_hours($atts = array()) {
        $settings = rw_bs_get_settings();
        $display = trim((string) $settings['hours_display']);

        if ($display === '') {
            $display = RW_BS_Hours_Handler::to_display_hours($settings['hours']);
        }

        return '<pre class="rw-bs-hours">' . esc_html($display) . '</pre>';
    }

    public static function business_field($atts = array()) {
        $atts = shortcode_atts(array('field' => ''), $atts, 'rw_business_field');
        $field = sanitize_key($atts['field']);

        if ($field === '') {
            return '';
        }

        if (!in_array($field, self::get_allowed_field_keys(), true)) {
            return '';
        }

        $settings = rw_bs_get_settings();

        if (array_key_exists($field, $settings)) {
            return esc_html(is_scalar($settings[$field]) ? (string) $settings[$field] : '');
        }

        $type_fields = isset($settings['type_fields']) && is_array($settings['type_fields']) ? $settings['type_fields'] : array();
        if (array_key_exists($field, $type_fields)) {
            return esc_html(is_scalar($type_fields[$field]) ? (string) $type_fields[$field] : '');
        }

        return '';
    }

    public static function business_reviews($atts = array()) {
        $atts = shortcode_atts(array('count' => 5), $atts, 'rw_business_reviews');
        $count = max(1, min(20, intval($atts['count'])));

        $reviews = get_transient('rw_bs_gbp_reviews_cache');
        if (!is_array($reviews) || empty($reviews)) {
            return '';
        }

        $reviews = array_slice($reviews, 0, $count);

        $html = '<div class="rw-bs-reviews">';
        foreach ($reviews as $review) {
            $html .= '<article class="rw-bs-review">';
            $html .= '<h4>' . esc_html($review['reviewer']) . '</h4>';
            $html .= '<p><strong>Rating:</strong> ' . esc_html($review['rating']) . '/5</p>';
            $html .= '<p>' . esc_html($review['comment']) . '</p>';
            $html .= '</article>';
        }
        $html .= '</div>';

        return $html;
    }
}
