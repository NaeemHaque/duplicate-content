<?php

namespace WPDuplicate\Helpers;

class Deactivator
{
    public static function deactivate()
    {
        delete_option( 'wp_duplicate_options' );
        // Clear any processing transients
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_wp_duplicate_processing_%'");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_wp_duplicate_unique_%'");
    }
}
