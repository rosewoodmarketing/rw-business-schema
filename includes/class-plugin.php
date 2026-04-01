<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_Plugin {
    public static function init() {
        add_action('plugins_loaded', array(__CLASS__, 'boot'));
        register_activation_hook(RW_BS_FILE, array(__CLASS__, 'activate'));
        register_deactivation_hook(RW_BS_FILE, array(__CLASS__, 'deactivate'));
    }

    public static function boot() {
        RW_BS_Wizard::init();
        RW_BS_Locations::init();
        RW_BS_Admin::init();
        RW_BS_Schema_Generator::init();
        RW_BS_Shortcodes::init();
        RW_BS_Elementor_Integration::init();
        RW_BS_GBP_OAuth::init();
        RW_BS_GBP_Sync::init();
        RW_BS_GitHub_Updater::init();
    }

    public static function activate() {
        if (!get_option(RW_BS_OPTION_KEY)) {
            rw_bs_update_settings(rw_bs_get_settings());
        }

        RW_BS_GBP_Sync::activate_schedule();
        RW_BS_Wizard::set_first_run();
    }

    public static function deactivate() {
        RW_BS_GBP_Sync::deactivate_schedule();
    }
}
