<?php

if ( ! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete options
delete_option('wp_duplicate_options');

global $wpdb;

$wpdb->query(
    $wpdb->prepare("
        DELETE FROM {$wpdb->options} 
        WHERE option_name LIKE %s 
        OR option_name LIKE %s
    ",
        '_transient_wpdc_%',
        '_transient_timeout_wpdc_%'
    )
);
