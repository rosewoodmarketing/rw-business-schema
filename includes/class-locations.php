<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_Locations {
    const CPT = 'rw_bs_location';

    public static function init() {
        add_action('init', array(__CLASS__, 'register_cpt'));
        add_action('add_meta_boxes', array(__CLASS__, 'add_metabox'));
        add_action('save_post', array(__CLASS__, 'save_metabox'), 10, 2);
    }

    public static function register_cpt() {
        if (empty(rw_bs_get_field('multi_location'))) {
            return;
        }

        $locations_are_public = self::locations_are_public();

        register_post_type(self::CPT, array(
            'labels' => array(
                'name' => 'Business Locations',
                'singular_name' => 'Location',
                'add_new' => 'Add Location',
                'add_new_item' => 'Add New Location',
                'edit_item' => 'Edit Location',
                'all_items' => 'All Locations',
            ),
            'public' => $locations_are_public,
            'publicly_queryable' => $locations_are_public,
            'show_ui' => true,
            'show_in_menu' => 'rw-business-schema',
            'show_in_rest' => $locations_are_public,
            'supports' => array('title'),
            'has_archive' => false,
            'rewrite' => $locations_are_public
                ? array(
                    'slug' => self::get_public_slug(),
                    'with_front' => false,
                )
                : false,
            'query_var' => $locations_are_public,
            'capability_type' => 'post',
            'map_meta_cap' => true,
        ));
    }

    public static function locations_are_public() {
        return !empty(rw_bs_get_field('multi_location')) && !empty(rw_bs_get_field('location_public'));
    }

    public static function get_public_slug() {
        $slug = sanitize_title((string) rw_bs_get_field('location_slug', 'business-location'));

        return $slug !== '' ? $slug : 'business-location';
    }

    public static function add_metabox() {
        if (!post_type_exists(self::CPT)) {
            return;
        }

        add_meta_box(
            'rw_bs_location_details',
            'Location Details',
            array(__CLASS__, 'render_metabox'),
            self::CPT,
            'normal',
            'high'
        );
    }

    public static function render_metabox($post) {
        wp_nonce_field('rw_bs_save_location_' . $post->ID, 'rw_bs_location_nonce');
        $types = RW_BS_Type_Definitions::get_types();
        $groups = RW_BS_Type_Definitions::get_groups();
        $meta = self::get_location_settings($post->ID);
        $target = self::get_location_target($post->ID);
        $type_fields = is_array($meta['type_fields']) ? $meta['type_fields'] : array();

        include RW_BS_PATH . 'admin/templates/metabox-location.php';
    }

    public static function save_metabox($post_id, $post) {
        if (!isset($_POST['rw_bs_location_nonce'])) {
            return;
        }

        $nonce = sanitize_text_field(wp_unslash($_POST['rw_bs_location_nonce']));
        if (!wp_verify_nonce($nonce, 'rw_bs_save_location_' . $post_id)) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if ($post->post_type !== self::CPT || !current_user_can('edit_post', $post_id)) {
            return;
        }

        $input = isset($_POST['rw_bs_loc']) && is_array($_POST['rw_bs_loc']) ? wp_unslash($_POST['rw_bs_loc']) : array();

        $text_fields = array(
            'company_name', 'street', 'city', 'state', 'postal_code', 'country',
            'phone_primary', 'phone_secondary', 'price_range', 'hours_display',
            'rating_value', 'rating_count',
        );

        foreach ($text_fields as $field) {
            update_post_meta($post_id, 'rw_bs_' . $field, sanitize_text_field($input[$field] ?? ''));
        }

        update_post_meta($post_id, 'rw_bs_hours_display', wp_kses_post($input['hours_display'] ?? ''));

        update_post_meta($post_id, 'rw_bs_email_primary', sanitize_email($input['email_primary'] ?? ''));
        update_post_meta($post_id, 'rw_bs_email_secondary', sanitize_email($input['email_secondary'] ?? ''));
        update_post_meta($post_id, 'rw_bs_website', esc_url_raw($input['website'] ?? ''));
        update_post_meta($post_id, 'rw_bs_schema_target_page_id', absint($input['schema_target_page_id'] ?? 0));
        update_post_meta($post_id, 'rw_bs_schema_target_path', sanitize_text_field($input['schema_target_path'] ?? ''));

        $type = in_array(($input['business_type'] ?? 'LocalBusiness'), RW_BS_Type_Definitions::get_type_keys(), true)
            ? sanitize_text_field($input['business_type'])
            : 'LocalBusiness';
        update_post_meta($post_id, 'rw_bs_business_type', $type);

        $hours = rw_bs_default_hours();
        if (!empty($input['hours']) && is_array($input['hours'])) {
            $sanitized = array();
            foreach ($input['hours'] as $row) {
                $sanitized[] = array(
                    'day' => sanitize_text_field($row['day'] ?? ''),
                    'closed' => !empty($row['closed']),
                    'open' => sanitize_text_field($row['open'] ?? ''),
                    'close' => sanitize_text_field($row['close'] ?? ''),
                );
            }
            $hours = $sanitized;
        }
        update_post_meta($post_id, 'rw_bs_hours', $hours);

        $type_fields = array();
        if (!empty($input['type_fields']) && is_array($input['type_fields'])) {
            foreach ((array) $input['type_fields'] as $key => $value) {
                if (is_array($value)) {
                    continue;
                }
                $key = sanitize_key($key);
                if (in_array($key, array('petsAllowed', 'acceptsReservations'), true)) {
                    $type_fields[$key] = !empty($value);
                } else {
                    $type_fields[$key] = sanitize_text_field($value);
                }
            }
        }
        update_post_meta($post_id, 'rw_bs_type_fields', $type_fields);
    }

    public static function get_location_settings($post_id) {
        $base = rw_bs_get_settings();

        $scalar = array(
            'company_name', 'street', 'city', 'state', 'postal_code', 'country',
            'phone_primary', 'phone_secondary', 'email_primary', 'email_secondary',
            'website', 'price_range', 'hours_display', 'rating_value', 'rating_count', 'business_type',
        );

        foreach ($scalar as $field) {
            $val = get_post_meta($post_id, 'rw_bs_' . $field, true);
            if ($val !== '') {
                $base[$field] = $val;
            }
        }

        $hours = get_post_meta($post_id, 'rw_bs_hours', true);
        if (is_array($hours)) {
            $base['hours'] = $hours;
        }

        $type_fields = get_post_meta($post_id, 'rw_bs_type_fields', true);
        if (is_array($type_fields)) {
            $base['type_fields'] = $type_fields;
        }

        return $base;
    }

    public static function get_all_locations() {
        return get_posts(array(
            'post_type' => self::CPT,
            'post_status' => 'publish',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ));
    }

    public static function get_location_choices() {
        $choices = array();

        foreach (self::get_all_locations() as $location) {
            $choices[$location->ID] = $location->post_title !== ''
                ? $location->post_title
                : 'Location #' . $location->ID;
        }

        return $choices;
    }

    public static function get_location_target($post_id) {
        return array(
            'page_id' => absint(get_post_meta($post_id, 'rw_bs_schema_target_page_id', true)),
            'path' => sanitize_text_field((string) get_post_meta($post_id, 'rw_bs_schema_target_path', true)),
        );
    }

    public static function get_context_settings($location_id = 0) {
        $location_id = absint($location_id);

        if ($location_id > 0) {
            return self::get_location_settings($location_id);
        }

        $location_id = self::get_location_id_for_current_request();
        if ($location_id > 0) {
            return self::get_location_settings($location_id);
        }

        return rw_bs_get_settings();
    }

    public static function get_location_id_for_current_request() {
        if (is_admin() || empty(rw_bs_get_field('multi_location'))) {
            return 0;
        }

        if (is_singular(self::CPT)) {
            return absint(get_queried_object_id());
        }

        $page_id = absint(get_queried_object_id());
        $path = self::get_current_request_path();

        return self::find_location_id_by_target($page_id, $path);
    }

    public static function find_location_id_by_target($page_id = 0, $path = '') {
        $page_id = absint($page_id);
        $path = self::normalize_path($path);

        foreach (self::get_all_locations() as $location) {
            $target = self::get_location_target($location->ID);
            $target_page_id = absint($target['page_id'] ?? 0);
            $target_path = self::normalize_path($target['path'] ?? '');

            if ($page_id > 0 && $target_page_id === $page_id) {
                return (int) $location->ID;
            }

            if ($path !== '' && $target_path !== '' && $target_path === $path) {
                return (int) $location->ID;
            }
        }

        return 0;
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
}
