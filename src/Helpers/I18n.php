<?php

namespace WPDuplicate\Helpers;

if ( ! defined('ABSPATH')) {
    exit;
}

class I18n
{
    public function loadPluginTextdomain()
    {
        load_plugin_textdomain(
            'wp-duplicate',
            false,
            dirname(dirname(dirname(plugin_basename(__FILE__)))) . '/languages/'
        );
    }
}
