<?php

namespace WPDuplicate\Helpers;

class I18n
{
    public function loadPluginTextdomain() {
        load_plugin_textdomain(
            'wp-duplicate',
            false,
            dirname(dirname(dirname(plugin_basename(__FILE__)))) . '/languages/'
        );
    }
}
