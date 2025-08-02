<?php

namespace WPDuplicate;

class Plugin
{
    private static $instance = null;
    private $version = '1.0.0';
    private $plugin_name = 'wp-duplicate';
    private function __construct() {
        // Constructor is empty - initialization happens in run()
    }

    private function init() {
        $this->loadDependencies();
        $this->setLocale();
        $this->defineAdminHooks();
    }

    private function loadDependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'src/Helpers/Loader.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'src/Helpers/I18n.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'src/Controllers/AdminController.php';
    }

    private function setLocale() {
        $i18n = new Helpers\I18n();
        add_action('plugins_loaded', [$i18n, 'loadPluginTextdomain']);
    }

    private function defineAdminHooks() {
        $adminController = new Controllers\AdminController($this->plugin_name, $this->version);

        // Enqueue scripts and styles
        add_action('admin_enqueue_scripts', [$adminController, 'enqueueStyles']);
        add_action('admin_enqueue_scripts', [$adminController, 'enqueueScripts']);

        // Admin menu
        add_action('admin_menu', [$adminController, 'addAdminMenu']);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function run() {
        $this->init();
    }
}
