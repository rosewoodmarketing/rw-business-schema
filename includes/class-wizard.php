<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_Wizard {
    public static function init() {
        add_action('admin_init', array(__CLASS__, 'maybe_redirect'));
        add_action('admin_post_rw_bs_dismiss_wizard', array(__CLASS__, 'dismiss'));
    }

    public static function set_first_run() {
        set_transient('rw_bs_show_wizard', 1, DAY_IN_SECONDS);
    }

    public static function maybe_redirect() {
        if (!get_transient('rw_bs_show_wizard')) {
            return;
        }

        if (get_option('rw_bs_wizard_done') || !current_user_can('manage_options') || wp_doing_ajax()) {
            return;
        }

        delete_transient('rw_bs_show_wizard');

        if (!isset($_GET['page']) || $_GET['page'] !== 'rw-business-schema') {
            wp_safe_redirect(admin_url('admin.php?page=rw-business-schema&wizard=1'));
            exit;
        }
    }

    public static function dismiss() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        check_admin_referer('rw_bs_dismiss_wizard');

        update_option('rw_bs_wizard_done', 1, false);

        wp_safe_redirect(admin_url('admin.php?page=rw-business-schema'));
        exit;
    }
}
