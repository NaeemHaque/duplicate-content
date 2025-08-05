<?php

namespace DuplicateContent\Helpers;

if ( ! defined('ABSPATH')) {
    exit;
}

class Activator
{
    public static function activate()
    {
        // Set default options if they don't exist
        if ( ! get_option('duplicate_content_options')) {
            $default_options = [
                'duplicate_content_copy_status'      => 'draft',
                'duplicate_content_copy_title'       => 'Copy of ',
                'duplicate_content_copy_suffix'      => '',
                'duplicate_content_copy_meta'        => '1',
                'duplicate_content_copy_taxonomies'  => '1',
                'duplicate_content_copy_comments'    => '',
                'duplicate_content_copy_attachments' => '',
                'duplicate_content_permissions'      => ['administrator']
            ];
            add_option('duplicate_content_options', $default_options);
        }
    }
} 
