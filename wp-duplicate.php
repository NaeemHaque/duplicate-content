<?php

/**
 * Plugin Name: WP Duplicate - Duplicate any content
 * Plugin URI: https://github.com/naeemhaque/wp-duplicate
 * Description: Duplicate your posts, pages, categories, tags, CPTs, and other taxonomies with just a single click.
 * Version: 1.0.0
 * Author: Naeem Haque
 * Author URI: https://profiles.wordpress.org/naeemhaque/
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-duplicate
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

define('WP_DUPLICATE_VERSION', '1.0.0');

// Autoloader for namespaces
spl_autoload_register(function ($class) {
    $prefix = 'WPDuplicate\\';
    $base_dir = plugin_dir_path(__FILE__) . 'src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Security: Validate file path to prevent directory traversal
    $real_file = realpath($file);
    $real_base = realpath($base_dir);
    
    if ($real_file && $real_base && strpos($real_file, $real_base) === 0 && file_exists($file)) {
        require $file;
    }
});

function activate_wp_duplicate() {
    require_once plugin_dir_path(__FILE__) . 'src/Helpers/Activator.php';
    WPDuplicate\Helpers\Activator::activate();
}

function deactivate_wp_duplicate() {
    require_once plugin_dir_path(__FILE__) . 'src/Helpers/Deactivator.php';
    WPDuplicate\Helpers\Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wp_duplicate');
register_deactivation_hook(__FILE__, 'deactivate_wp_duplicate');

// Initialize the plugin
function run_wp_duplicate() {
    $plugin = WPDuplicate\Plugin::getInstance();
    $plugin->run();
}

run_wp_duplicate();
