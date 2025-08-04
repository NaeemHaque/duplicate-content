<?php

if ( ! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option('wp_duplicate_options');

global $wpdb;

$query = $wpdb->prepare(
    "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s OR option_name LIKE %s OR option_name LIKE %s",
    '_transient_wpdc_proc_%',
    '_transient_timeout_wpdc_proc_%',
    '_transient_wpdc_unique_%',
    '_transient_timeout_wpdc_unique_%'
);
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.NotPrepared
$wpdb->query($query);
