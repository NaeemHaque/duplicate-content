<?php

namespace WPDuplicate\Controllers;

use WPDuplicate\Services\DuplicationService;
use WPDuplicate\Models\Settings;

class AdminController
{
    private $plugin_name;
    private $version;
    private $duplicationService;
    private $settings;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name        = $plugin_name;
        $this->version            = $version;
        $this->duplicationService = new DuplicationService();
        $this->settings           = new Settings();
    }

    public function enqueueStyles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/css/admin.css',
            array(),
            $this->version,
            'all'
        );
    }

    public function enqueueScripts()
    {
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/js/admin.js',
            array('jquery'),
            $this->version,
            false
        );
    }

    public function addAdminMenu()
    {
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

    public function registerSettings()
    {
        $this->settings->register();
    }

    public function registerCustomHooks()
    {
        $custom_post_types = get_post_types(array('_builtin' => false));

        foreach ($custom_post_types as $post_type) {
            add_filter($post_type . '_row_actions', [$this, 'addDuplicatePostAction'], 10, 2);
        }

        $custom_taxonomies = get_taxonomies(array('_builtin' => false));

        foreach ($custom_taxonomies as $taxonomy) {
            add_filter($taxonomy . '_row_actions', [$this, 'addDuplicateTaxonomyAction'], 10, 2);
        }
    }

    public function addDuplicatePostAction($actions, $post)
    {
        if ($this->canUserDuplicate() && $this->isDuplicatablePostType($post->post_type)) {
            $actions['duplicate'] = sprintf(
                '<a href="%s" class="wpdc-duplicate-action" data-post-id="%d" data-post-type="%s">%s</a>',
                wp_nonce_url(
                    admin_url('admin-ajax.php?action=wp_duplicate_post&post_id=' . $post->ID),
                    'wp_duplicate_post_' . $post->ID
                ),
                $post->ID,
                $post->post_type,
                __('Duplicate', 'wp-duplicate')
            );
        }

        return $actions;
    }

    public function addDuplicateTaxonomyAction($actions, $term)
    {
        if ($this->canUserDuplicate()) {
            $actions['duplicate'] = sprintf(
                '<a href="%s" class="wpdc-duplicate-action" data-term-id="%d" data-taxonomy="%s">%s</a>',
                wp_nonce_url(
                    admin_url(
                        'admin-ajax.php?action=wp_duplicate_taxonomy&term_id=' . $term->term_id . '&taxonomy=' . $term->taxonomy
                    ),
                    'wp_duplicate_taxonomy_' . $term->term_id
                ),
                $term->term_id,
                $term->taxonomy,
                __('Duplicate', 'wp-duplicate')
            );
        }

        return $actions;
    }

    public function duplicatePostAjax()
    {
        if ( ! wp_verify_nonce($_GET['_wpnonce'], 'wp_duplicate_post_' . $_GET['post_id'])) {
            wp_die(__('Security check failed.', 'wp-duplicate'));
        }

        if ( ! $this->canUserDuplicate()) {
            wp_die(__('You do not have permission to duplicate content.', 'wp-duplicate'));
        }

        $post_id = intval($_GET['post_id']);
        $post    = get_post($post_id);

        if ( ! $post) {
            wp_die(__('Post not found.', 'wp-duplicate'));
        }

        $duplication_key = 'wp_duplicate_processing_' . $post_id;
        if (get_transient($duplication_key)) {
            wp_die(__('This post is already being duplicated. Please wait.', 'wp-duplicate'));
        }

        set_transient($duplication_key, true, 10);

        $unique_id = uniqid('dup_' . $post_id . '_', true);
        set_transient('wp_duplicate_unique_' . $post_id, $unique_id, 10);

        $duplicate_id = $this->duplicationService->duplicatePost($post_id);

        if (get_transient('wp_duplicate_unique_' . $post_id) === $unique_id) {
            delete_transient($duplication_key);
            delete_transient('wp_duplicate_unique_' . $post_id);
        }

        if ($duplicate_id) {
            $redirect_url = admin_url('post.php?post=' . $duplicate_id . '&action=edit');
            wp_redirect($redirect_url);
            exit;
        } else {
            wp_die(__('Failed to duplicate post.', 'wp-duplicate'));
        }
    }

    public function duplicateTaxonomyAjax()
    {
        if ( ! wp_verify_nonce($_GET['_wpnonce'], 'wp_duplicate_taxonomy_' . $_GET['term_id'])) {
            wp_die(__('Security check failed.', 'wp-duplicate'));
        }

        if ( ! $this->canUserDuplicate()) {
            wp_die(__('You do not have permission to duplicate content.', 'wp-duplicate'));
        }

        $term_id  = intval($_GET['term_id']);
        $taxonomy = sanitize_text_field($_GET['taxonomy']);
        $term     = get_term($term_id, $taxonomy);

        if ( ! $term) {
            wp_die(__('Term not found.', 'wp-duplicate'));
        }

        $duplication_key = 'wp_duplicate_processing_term_' . $term_id;
        if (get_transient($duplication_key)) {
            wp_die(__('This term is already being duplicated. Please wait.', 'wp-duplicate'));
        }

        set_transient($duplication_key, true, 10);

        $unique_id = uniqid('dup_term_' . $term_id . '_', true);
        set_transient('wp_duplicate_unique_term_' . $term_id, $unique_id, 10);

        $duplicate_id = $this->duplicationService->duplicateTaxonomy($term_id, $taxonomy);

        if (get_transient('wp_duplicate_unique_term_' . $term_id) === $unique_id) {
            delete_transient($duplication_key);
            delete_transient('wp_duplicate_unique_term_' . $term_id);
        }

        if ($duplicate_id) {
            $redirect_url = admin_url('edit-tags.php?taxonomy=' . $taxonomy . '&message=duplicated');
            wp_redirect($redirect_url);
            exit;
        } else {
            wp_die(__('Failed to duplicate term.', 'wp-duplicate'));
        }
    }

    private function canUserDuplicate()
    {
        $permissions = $this->settings->get('wp_duplicate_permissions', 'administrator');

        switch ($permissions) {
            case 'administrator':
                return current_user_can('manage_options');
            case 'editor':
                return current_user_can('edit_others_posts');
            case 'author':
                return current_user_can('edit_posts');
            default:
                return current_user_can('manage_options');
        }
    }

    private function isDuplicatablePostType($post_type)
    {
        $duplicatable_types = array('post', 'page');
        $custom_post_types  = get_post_types(array('_builtin' => false));
        $duplicatable_types = array_merge($duplicatable_types, $custom_post_types);

        return in_array($post_type, $duplicatable_types);
    }

    public function displayDashboard()
    {
        require_once plugin_dir_path(dirname(dirname(__FILE__))) . 'src/Views/dashboard.php';
    }

    public function displaySettings()
    {
        require_once plugin_dir_path(dirname(dirname(__FILE__))) . 'src/Views/settings.php';
    }

    public function displayHelp()
    {
        require_once plugin_dir_path(dirname(dirname(__FILE__))) . 'src/Views/help.php';
    }
}
