<?php
/**
 * Plugin Name: RW Business Schema
 * Description: Local business schema manager with Elementor dynamic fields, shortcodes, GBP rating sync, and GitHub auto-updates.
 * Version: 1.0.0
 * Author: Rosewood Marketing
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPL-2.0-or-later
 * Text Domain: rw-business-schema
 */

if (!defined('ABSPATH')) {
    exit;
}

define('RW_BS_FILE', __FILE__);
define('RW_BS_PATH', plugin_dir_path(__FILE__));
define('RW_BS_URL', plugin_dir_url(__FILE__));
define('RW_BS_VERSION', '1.0.0');
define('RW_BS_OPTION_KEY', 'rw_bs_settings');
define('RW_BS_GBP_TOKEN_KEY', 'rw_bs_gbp_tokens');
define('RW_BS_GITHUB_REPO', 'rosewoodmarketing/rw-business-schema');

require_once RW_BS_PATH . 'includes/helpers.php';
require_once RW_BS_PATH . 'includes/class-type-definitions.php';
require_once RW_BS_PATH . 'includes/class-wizard.php';
require_once RW_BS_PATH . 'includes/class-locations.php';
require_once RW_BS_PATH . 'includes/class-hours-handler.php';
require_once RW_BS_PATH . 'includes/class-schema-generator.php';
require_once RW_BS_PATH . 'includes/class-shortcodes.php';
require_once RW_BS_PATH . 'includes/class-elementor-field-tag.php';
require_once RW_BS_PATH . 'includes/class-elementor-integration.php';
require_once RW_BS_PATH . 'includes/class-gbp-oauth.php';
require_once RW_BS_PATH . 'includes/class-gbp-sync.php';
require_once RW_BS_PATH . 'includes/class-github-updater.php';
require_once RW_BS_PATH . 'includes/class-admin.php';
require_once RW_BS_PATH . 'includes/class-plugin.php';

RW_BS_Plugin::init();
