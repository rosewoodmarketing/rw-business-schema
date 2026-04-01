<?php

if (!defined('ABSPATH')) {
    exit;
}

class RW_BS_GitHub_Updater {
    public static function init() {
        add_filter('pre_set_site_transient_update_plugins', array(__CLASS__, 'check_for_update'));
        add_filter('plugins_api', array(__CLASS__, 'plugin_info'), 20, 3);
        add_action('update_option_' . RW_BS_OPTION_KEY, array(__CLASS__, 'maybe_clear_cache'), 10, 2);
    }

    public static function maybe_clear_cache($old, $new) {
        $old_token = isset($old['github_token']) ? (string) $old['github_token'] : '';
        $new_token = isset($new['github_token']) ? (string) $new['github_token'] : '';
        if ($old_token !== $new_token) {
            delete_transient('rw_bs_github_release');
        }
    }

    public static function get_release_data() {
        $cache_key = 'rw_bs_github_release';
        $cached = get_transient($cache_key);
        if ($cached && is_array($cached)) {
            return $cached;
        }

        $settings = rw_bs_get_settings();
        $token = trim((string) $settings['github_token']);

        $args = array('timeout' => 20, 'headers' => array('Accept' => 'application/vnd.github+json'));
        if ($token !== '') {
            $args['headers']['Authorization'] = 'Bearer ' . $token;
        }

        $url = 'https://api.github.com/repos/' . RW_BS_GITHUB_REPO . '/releases/latest';
        $response = wp_remote_get($url, $args);

        if (is_wp_error($response)) {
            return false;
        }

        $body = json_decode((string) wp_remote_retrieve_body($response), true);
        if (!is_array($body) || empty($body['tag_name'])) {
            return false;
        }

        set_transient($cache_key, $body, HOUR_IN_SECONDS * 6);
        return $body;
    }

    public static function check_for_update($transient) {
        if (empty($transient->checked) || !is_object($transient)) {
            return $transient;
        }

        $release = self::get_release_data();
        if (!$release) {
            return $transient;
        }

        $latest = ltrim((string) $release['tag_name'], 'v');
        $plugin_slug = plugin_basename(RW_BS_FILE);
        $installed_version = isset($transient->checked[$plugin_slug]) ? (string) $transient->checked[$plugin_slug] : RW_BS_VERSION;

        if (version_compare($latest, $installed_version, '<=')) {
            return $transient;
        }

        $package = self::get_release_package_url($release);

        if (!$package) {
            return $transient;
        }

        $transient->response[$plugin_slug] = (object) array(
            'slug' => dirname($plugin_slug),
            'plugin' => $plugin_slug,
            'new_version' => $latest,
            'url' => 'https://github.com/' . RW_BS_GITHUB_REPO,
            'package' => $package,
            'icons' => array(),
            'banners' => array(),
            'tested' => get_bloginfo('version'),
            'requires_php' => '7.4',
        );

        return $transient;
    }

    private static function get_release_package_url($release) {
        $candidate = '';

        if (!empty($release['assets']) && is_array($release['assets'])) {
            foreach ($release['assets'] as $asset) {
                $asset_url = isset($asset['browser_download_url']) ? (string) $asset['browser_download_url'] : '';
                if ($asset_url !== '' && preg_match('/\.zip($|[?&#])/i', $asset_url)) {
                    $candidate = $asset_url;
                    break;
                }
            }
        }

        if ($candidate === '') {
            $candidate = $release['zipball_url'] ?? '';
        }

        // Security: only accept HTTPS downloads from GitHub domains.
        if (!preg_match('#^https://(api\.github\.com|codeload\.github\.com|github\.com)/#i', $candidate)) {
            return '';
        }

        return esc_url_raw($candidate);
    }

    public static function plugin_info($res, $action, $args) {
        if ($action !== 'plugin_information' || empty($args->slug) || $args->slug !== 'rw-business-schema') {
            return $res;
        }

        $release = self::get_release_data();
        if (!$release) {
            return $res;
        }

        $version = ltrim((string) $release['tag_name'], 'v');
        $sections = array(
            'description' => 'Local business schema manager with Elementor tags, shortcodes, GBP sync, and GitHub updates.',
            'changelog' => wp_kses_post(wpautop((string) ($release['body'] ?? 'No changelog provided.'))),
        );

        return (object) array(
            'name' => 'RW Business Schema',
            'slug' => 'rw-business-schema',
            'version' => $version,
            'author' => '<a href="https://github.com/rosewoodmarketing">Rosewood Marketing</a>',
            'homepage' => 'https://github.com/' . RW_BS_GITHUB_REPO,
            'requires' => '6.0',
            'requires_php' => '7.4',
            'sections' => $sections,
            'download_link' => self::get_release_package_url($release),
        );
    }
}
