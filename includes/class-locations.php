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

        register_post_type(self::CPT, array(
            'labels' => array(
                'name' => 'Business Locations',
                'singular_name' => 'Location',
                'add_new' => 'Add Location',
                'add_new_item' => 'Add New Location',
                'edit_item' => 'Edit Location',
                'all_items' => 'All Locations',
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'rw-business-schema',
            'supports' => array('title'),
            'rewrite' => false,
            'capability_type' => 'post',
            'map_meta_cap' => true,
        ));
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

        update_post_meta($post_id, 'rw_bs_email_primary', sanitize_email($input['email_primary'] ?? ''));
        update_post_meta($post_id, 'rw_bs_email_secondary', sanitize_email($input['email_secondary'] ?? ''));
        update_post_meta($post_id, 'rw_bs_website', esc_url_raw($input['website'] ?? ''));

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
}
