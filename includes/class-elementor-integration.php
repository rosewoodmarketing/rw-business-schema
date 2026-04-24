<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_Elementor_Integration {
    private static $tag_registered = false;

    public static function init() {
        // Elementor has used different registration hooks across versions.
        add_action('elementor/dynamic_tags/register', array(__CLASS__, 'register_dynamic_tags'));
        add_action('elementor/dynamic_tags/register_tags', array(__CLASS__, 'register_dynamic_tags'));
    }

    public static function register_dynamic_tags($dynamic_tags_manager) {
        if (self::$tag_registered) {
            return;
        }

        if (file_exists(RW_BS_PATH . 'includes/class-elementor-field-tag.php')) {
            require_once RW_BS_PATH . 'includes/class-elementor-field-tag.php';
        }

        if (
            !class_exists('RW_BS_Elementor_Field_Tag')
        ) {
            return;
        }

        if (is_object($dynamic_tags_manager) && method_exists($dynamic_tags_manager, 'register_group')) {
            $dynamic_tags_manager->register_group(
                'rw-business-schema',
                array(
                    'title' => 'RW Business Schema',
                )
            );
        }

        $tag = new RW_BS_Elementor_Field_Tag();

        if (is_object($dynamic_tags_manager) && method_exists($dynamic_tags_manager, 'register')) {
            $dynamic_tags_manager->register($tag);
            self::$tag_registered = true;
            return;
        }

        if (is_object($dynamic_tags_manager) && method_exists($dynamic_tags_manager, 'register_tag')) {
            $dynamic_tags_manager->register_tag($tag);
            self::$tag_registered = true;
        }
    }
}
