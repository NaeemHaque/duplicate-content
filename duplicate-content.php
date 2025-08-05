<?php

/**
 * Plugin Name: Duplicate Content
 * Plugin URI: https://github.com/naeemhaque/duplicate-content
 * Description: Duplicate your posts, pages, categories, tags, CPTs, and other taxonomies with just a single click.
 * Version: 1.0.0
 * Author: Naeem Haque
 * Author URI: https://profiles.wordpress.org/naeemhaque/
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: duplicate-content
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

define('DUPLICATE_CONTENT_VERSION', '1.0.0');

// Autoloader for namespaces
spl_autoload_register(function ($class) {
    $prefix = 'DuplicateContent\\';
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

function activate_duplicate_content() {
    require_once plugin_dir_path(__FILE__) . 'src/Helpers/Activator.php';
    DuplicateContent\Helpers\Activator::activate();
}

function deactivate_duplicate_content() {
    require_once plugin_dir_path(__FILE__) . 'src/Helpers/Deactivator.php';
    DuplicateContent\Helpers\Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_duplicate_content');
register_deactivation_hook(__FILE__, 'deactivate_duplicate_content');

// Initialize the plugin
function run_duplicate_content() {
    $plugin = DuplicateContent\Plugin::getInstance();
    $plugin->run();
}

run_duplicate_content();
