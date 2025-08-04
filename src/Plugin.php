<?php

namespace WPDuplicate;

class Plugin
{
    private static $instance = null;
    private $version = '1.0.0';
    private $plugin_name = 'wp-duplicate';

    private function __construct()
    {
        // Constructor is empty - initialization happens in run()
    }

    private function init()
    {
        $this->loadDependencies();
        $this->defineAdminHooks();
    }

    private function loadDependencies()
    {
        require_once plugin_dir_path(dirname(__FILE__)) . 'src/Controllers/AdminController.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'src/Services/DuplicationService.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'src/Models/Settings.php';
    }

    private function defineAdminHooks()
    {
        $adminController = new Controllers\AdminController($this->plugin_name, $this->version);

        // Enqueue scripts and styles
        add_action('admin_enqueue_scripts', [$adminController, 'enqueueStyles']);
        add_action('admin_enqueue_scripts', [$adminController, 'enqueueScripts']);

        // Admin menu
        add_action('admin_menu', [$adminController, 'addAdminMenu']);

        // Row actions
        add_filter('post_row_actions', [$adminController, 'addDuplicatePostAction'], 10, 2);
        add_filter('page_row_actions', [$adminController, 'addDuplicatePostAction'], 10, 2);
        add_filter('tag_row_actions', [$adminController, 'addDuplicateTaxonomyAction'], 10, 2);
        add_filter('category_row_actions', [$adminController, 'addDuplicateTaxonomyAction'], 10, 2);

        // AJAX handlers
        add_action('wp_ajax_wp_duplicate_post', [$adminController, 'duplicatePostAjax']);
        add_action('wp_ajax_wp_duplicate_taxonomy', [$adminController, 'duplicateTaxonomyAjax']);

        // Custom hooks
        add_action('init', [$adminController, 'registerCustomHooks']);
        add_action('admin_init', [$adminController, 'registerSettings']);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function run()
    {
        $this->init();
    }
}
