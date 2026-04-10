<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_Admin {
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'register_menu'));
        add_action('admin_menu', array(__CLASS__, 'normalize_menu_order'), 999);
        add_action('admin_init', array(__CLASS__, 'register_setting'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
    }

    public static function register_menu() {
        add_menu_page(
            'RW Business Schema',
            'Business Schema',
            'manage_options',
            'rw-business-schema',
            array(__CLASS__, 'render_page'),
            'dashicons-store',
            56
        );

        // Always register an explicit Settings submenu for reliable parent routing.
        add_submenu_page(
            'rw-business-schema',
            'Settings',
            'Settings',
            'manage_options',
            'rw-business-schema',
            array(__CLASS__, 'render_page')
        );

        add_submenu_page(
            'rw-business-schema',
            'GBP Setup Guide',
            'GBP Setup Guide',
            'manage_options',
            'rw-bs-gbp-help',
            array(__CLASS__, 'render_gbp_help_page')
        );

        add_submenu_page(
            'rw-business-schema',
            'Shortcodes & Dynamic Tags',
            'Shortcodes & Tags',
            'manage_options',
            'rw-bs-shortcodes-help',
            array(__CLASS__, 'render_shortcodes_help_page')
        );
    }

    public static function normalize_menu_order() {
        global $submenu;

        if (empty($submenu['rw-business-schema']) || !is_array($submenu['rw-business-schema'])) {
            return;
        }

        $items = $submenu['rw-business-schema'];
        $normalized = array();
        $settings_item = null;

        foreach ($items as $item) {
            $slug = $item[2] ?? '';

            if ($slug === 'rw-business-schema') {
                if ($settings_item !== null) {
                    continue;
                }

                $item[0] = 'Settings';
                $settings_item = $item;
                continue;
            }

            $normalized[] = $item;
        }

        if ($settings_item === null) {
            $settings_item = array('Settings', 'manage_options', 'rw-business-schema', 'Settings');
        }

        array_unshift($normalized, $settings_item);
        $submenu['rw-business-schema'] = $normalized;
    }

    public static function register_setting() {
        register_setting('rw_bs_group', RW_BS_OPTION_KEY, array(__CLASS__, 'sanitize_settings'));
    }

    public static function sanitize_settings($input) {
        $current = rw_bs_get_settings();
        $output = $current;

        $output['business_type'] = in_array(($input['business_type'] ?? 'LocalBusiness'), RW_BS_Type_Definitions::get_type_keys(), true)
            ? $input['business_type']
            : 'LocalBusiness';

        $text_fields = array(
            'company_name', 'street', 'city', 'state', 'postal_code', 'country',
            'phone_primary', 'phone_secondary', 'price_range', 'hours_display',
            'gbp_location',
        );

        foreach ($text_fields as $field) {
            $output[$field] = sanitize_text_field($input[$field] ?? '');
        }

        $output['email_primary'] = sanitize_email($input['email_primary'] ?? '');
        $output['email_secondary'] = sanitize_email($input['email_secondary'] ?? '');
        $output['website'] = esc_url_raw($input['website'] ?? '');
        $output['gbp_client_id'] = sanitize_text_field($input['gbp_client_id'] ?? '');
        $output['gbp_client_secret'] = sanitize_text_field($input['gbp_client_secret'] ?? '');
        $output['rating_value'] = sanitize_text_field($input['rating_value'] ?? '');
        $output['rating_count'] = sanitize_text_field($input['rating_count'] ?? '');
        $output['multi_location'] = !empty($input['multi_location']);

        $output['hours'] = rw_bs_default_hours();
        if (!empty($input['hours']) && is_array($input['hours'])) {
            $sanitized_hours = array();
            foreach ($input['hours'] as $row) {
                $sanitized_hours[] = array(
                    'day' => sanitize_text_field($row['day'] ?? ''),
                    'closed' => !empty($row['closed']),
                    'open' => sanitize_text_field($row['open'] ?? ''),
                    'close' => sanitize_text_field($row['close'] ?? ''),
                );
            }
            $output['hours'] = $sanitized_hours;
        }

        $output['type_fields'] = array();
        if (!empty($input['type_fields']) && is_array($input['type_fields'])) {
            foreach ($input['type_fields'] as $key => $value) {
                if (is_array($value)) {
                    continue;
                }
                if (in_array($key, array('petsAllowed', 'acceptsReservations'), true)) {
                    $output['type_fields'][$key] = !empty($value);
                } else {
                    $output['type_fields'][$key] = sanitize_text_field($value);
                }
            }
        }

        return $output;
    }

    public static function enqueue_assets($hook) {
        $is_settings = $hook === 'toplevel_page_rw-business-schema';
        $is_help_page = in_array($hook, array('business-schema_page_rw-bs-gbp-help', 'business-schema_page_rw-bs-shortcodes-help'), true);
        $is_location_edit = in_array($hook, array('post.php', 'post-new.php'), true);

        if (!$is_settings && !$is_help_page && !$is_location_edit) {
            return;
        }

        if ($is_location_edit) {
            $screen = get_current_screen();
            if (!$screen || $screen->post_type !== RW_BS_Locations::CPT) {
                return;
            }
        }

        wp_enqueue_style('rw-bs-admin', RW_BS_URL . 'admin/css/admin.css', array(), RW_BS_VERSION);
        wp_enqueue_script('rw-bs-admin', RW_BS_URL . 'admin/js/admin.js', array('jquery'), RW_BS_VERSION, true);

        $types = RW_BS_Type_Definitions::get_types();
        $type_groups = array();
        foreach ($types as $key => $def) {
            if ($def['group'] !== 'none') {
                $type_groups[$key] = $def['group'];
            }
        }

        wp_localize_script('rw-bs-admin', 'rwBsData', array(
            'typeGroups' => $type_groups,
        ));
    }

    public static function render_page() {
        $settings = rw_bs_get_settings();
        $types = RW_BS_Type_Definitions::get_types();
        $groups = RW_BS_Type_Definitions::get_groups();

        include RW_BS_PATH . 'admin/templates/settings-page.php';
    }

    public static function render_gbp_help_page() {
        include RW_BS_PATH . 'admin/templates/help-gbp.php';
    }

    public static function render_shortcodes_help_page() {
        include RW_BS_PATH . 'admin/templates/help-shortcodes.php';
    }
}
