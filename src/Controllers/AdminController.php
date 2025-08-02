<?php

namespace WPDuplicate\Controllers;

class AdminController
{
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueueStyles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/css/admin.css', array(), $this->version, 'all');
    }

    public function enqueueScripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/js/admin.js', array('jquery'), $this->version, false);
    }

    public function addAdminMenu() {
        add_menu_page(
            __('WP Duplicate', 'wp-duplicate'),
            __('WP Duplicate', 'wp-duplicate'),
            'manage_options',
            'wp-duplicate',
            [$this, 'displayDashboard'],
            'dashicons-admin-page',
            30
        );

        add_submenu_page(
            'wp-duplicate',
            __('Dashboard', 'wp-duplicate'),
            __('Dashboard', 'wp-duplicate'),
            'manage_options',
            'wp-duplicate',
            [$this, 'displayDashboard']
        );

        add_submenu_page(
            'wp-duplicate',
            __('Settings', 'wp-duplicate'),
            __('Settings', 'wp-duplicate'),
            'manage_options',
            'wp-duplicate-settings',
            [$this, 'displaySettings']
        );

        add_submenu_page(
            'wp-duplicate',
            __('Help', 'wp-duplicate'),
            __('Help', 'wp-duplicate'),
            'manage_options',
            'wp-duplicate-help',
            [$this, 'displayHelp']
        );
    }

    public function displayDashboard() {
        require_once plugin_dir_path(dirname(dirname(__FILE__))) . 'src/Views/dashboard.php';
    }

    public function displaySettings() {
        require_once plugin_dir_path(dirname(dirname(__FILE__))) . 'src/Views/settings.php';
    }

    public function displayHelp() {
        require_once plugin_dir_path(dirname(dirname(__FILE__))) . 'src/Views/help.php';
    }
}
