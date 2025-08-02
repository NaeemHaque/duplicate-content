<?php

namespace WPDuplicate\Helpers;

class Activator
{
    public static function activate()
    {
        // Set default options if they don't exist
        if ( ! get_option('wp_duplicate_options')) {
            $default_options = array(
                'wp_duplicate_copy_status'      => 'draft',
                'wp_duplicate_copy_title'       => 'Copy of ',
                'wp_duplicate_copy_meta'        => '1',
                'wp_duplicate_copy_taxonomies'  => '1',
                'wp_duplicate_copy_comments'    => '',
                'wp_duplicate_copy_attachments' => '',
                'wp_duplicate_permissions'      => 'administrator'
            );
            add_option('wp_duplicate_options', $default_options);
        }
    }
} 
