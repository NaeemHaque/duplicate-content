<?php

namespace WPDuplicate\Helpers;

if ( ! defined('ABSPATH')) {
    exit;
}

class Deactivator
{
    public static function deactivate()
    {
        // Note: Consider keeping user settings on deactivation
        // delete_option('wp_duplicate_options');

        self::cleanupTransients();
    }

    private static function cleanupTransients()
    {
        global $wpdb;

        // Use prepared statements for security
        $wpdb->query(
            $wpdb->prepare(
                "
            DELETE FROM {$wpdb->options} 
            WHERE option_name LIKE %s 
            OR option_name LIKE %s
            OR option_name LIKE %s
            OR option_name LIKE %s
        ",
                '_transient_wpdc_proc_%',
                '_transient_timeout_wpdc_proc_%',
                '_transient_wpdc_unique_%',
                '_transient_timeout_wpdc_unique_%'
            )
        );
    }
}
