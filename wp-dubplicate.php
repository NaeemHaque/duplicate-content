<?php

/**
 * Plugin Name: WP Duplicate
 * Plugin URI: https://github.com/NaeemHaque/wp-duplicate.git
 * Description: Duplicate your posts, pages, categories, tags, CPTs, and other taxonomies with just a single click.
 * Version: 1.0.0
 * Author: Golam Sarwer
 * Author URI: https://www.linkedin.com/in/golam-sarwer-8626101a3/
 * Donate link: https://buymeacoffee.com/naeemhaque
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wp-duplicate
 * Domain Path: /languages
 */

if (!defined('WPINC')) {
    die;
}

define('WP_DUPLICATE_VERSION', '1.0.0');

function run_wp_duplicate()
{
    return "Welcome to WP Duplicate";
}

run_wp_duplicate();
