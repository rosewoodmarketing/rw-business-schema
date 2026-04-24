<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_Elementor_Integration {
    public static function init() {
        add_action('elementor/dynamic_tags/register', array(__CLASS__, 'register_dynamic_tags'));
    }

    public static function register_dynamic_tags($dynamic_tags_manager) {
        if (file_exists(RW_BS_PATH . 'includes/class-elementor-field-tag.php')) {
            require_once RW_BS_PATH . 'includes/class-elementor-field-tag.php';
        }

        if (
            !class_exists('Elementor\\Core\\DynamicTags\\Tag') ||
            !class_exists('Elementor\\Controls_Manager') ||
            !class_exists('RW_BS_Elementor_Field_Tag')
        ) {
            return;
        }

        $dynamic_tags_manager->register(new RW_BS_Elementor_Field_Tag());
    }
}
