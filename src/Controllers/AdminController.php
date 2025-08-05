<?php

namespace DuplicateContent\Controllers;

use DuplicateContent\Services\DuplicationService;
use DuplicateContent\Models\Settings;

// Prevent direct access
if ( ! defined('ABSPATH')) {
    exit;
}

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
            [],
            $this->version,
            'all'
        );
    }

    public function enqueueScripts()
    {
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/js/admin.js',
            ['jquery'],
            $this->version,
            false
        );
    }

    public function addAdminMenu()
    {
        add_menu_page(
            esc_html__('Duplicate Content', 'duplicate-content'),
            esc_html__('Duplicate Content', 'duplicate-content'),
            'manage_options',
            'duplicate-content',
            [$this, 'displayDashboard'],
            plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/images/icon.svg',
            30
        );

        add_submenu_page(
            'duplicate-content',
                            esc_html__('Dashboard', 'duplicate-content'),
                esc_html__('Dashboard', 'duplicate-content'),
            'manage_options',
            'duplicate-content',
            [$this, 'displayDashboard']
        );

        add_submenu_page(
            'duplicate-content',
                            esc_html__('Settings', 'duplicate-content'),
                esc_html__('Settings', 'duplicate-content'),
            'manage_options',
            'duplicate-content-settings',
            [$this, 'displaySettings']
        );

        add_submenu_page(
            'duplicate-content',
                            esc_html__('Help', 'duplicate-content'),
                esc_html__('Help', 'duplicate-content'),
            'manage_options',
            'duplicate-content-help',
            [$this, 'displayHelp']
        );
    }

    public function registerSettings()
    {
        $this->settings->register();
    }

    public function registerCustomHooks()
    {
        $custom_post_types = get_post_types(['_builtin' => false]);

        foreach ($custom_post_types as $post_type) {
            add_filter($post_type . '_row_actions', [$this, 'addDuplicatePostAction'], 10, 2);
        }

        $custom_taxonomies = get_taxonomies(['_builtin' => false]);

        foreach ($custom_taxonomies as $taxonomy) {
            add_filter($taxonomy . '_row_actions', [$this, 'addDuplicateTaxonomyAction'], 10, 2);
        }
    }

    public function addDuplicatePostAction($actions, $post)
    {
        if ($this->canUserDuplicate() &&
            $this->isDuplicatablePostType($post->post_type) &&
            $this->isContentTypeEnabled($post->post_type))
        {
            $actions['duplicate'] = sprintf(
                '<a href="%s" class="wpdc-duplicate-action" data-post-id="%d" data-post-type="%s">%s</a>',
                wp_nonce_url(
                    admin_url('admin-ajax.php?action=duplicate_content_post&post_id=' . $post->ID),
                    'duplicate_content_post_' . $post->ID
                ),
                $post->ID,
                esc_attr($post->post_type),
                esc_html__('Duplicate', 'duplicate-content')
            );
        }

        return $actions;
    }

    public function addDuplicateTaxonomyAction($actions, $term)
    {
        if ($this->canUserDuplicate() && $this->isTaxonomyEnabled($term->taxonomy)) {
            $actions['duplicate'] = sprintf(
                '<a href="%s" class="wpdc-duplicate-action" data-term-id="%d" data-taxonomy="%s">%s</a>',
                wp_nonce_url(
                    admin_url(
                        'admin-ajax.php?action=duplicate_content_taxonomy&term_id=' . $term->term_id . '&taxonomy=' . $term->taxonomy
                    ),
                    'duplicate_content_taxonomy_' . $term->term_id
                ),
                $term->term_id,
                esc_attr($term->taxonomy),
                esc_html__('Duplicate', 'duplicate-content')
            );
        }

        return $actions;
    }

    public function duplicatePostAjax()
    {
        // Validate and sanitize input
        $post_id = isset($_GET['post_id']) ? absint($_GET['post_id']) : 0;
        $nonce   = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';

        if (empty($post_id) || empty($nonce)) {
            wp_die(esc_html__('Invalid request parameters.', 'duplicate-content'), 400);
        }

        if ( ! wp_verify_nonce($nonce, 'duplicate_content_post_' . $post_id)) {
            wp_die(esc_html__('Security check failed.', 'duplicate-content'), 403);
        }

        if ( ! $this->canUserDuplicate()) {
            wp_die(esc_html__('You do not have permission to duplicate content.', 'duplicate-content'), 403);
        }

        $post = get_post($post_id);

        if ( ! $post || ! current_user_can('edit_post', $post_id)) {
            wp_die(esc_html__('Post not found or you do not have permission to edit it.', 'duplicate-content'), 404);
        }

        // Check for concurrent duplication
        $duplication_key = 'wpdc_proc_' . $post_id;
        if (get_transient($duplication_key)) {
            wp_die(esc_html__('This post is already being duplicated. Please wait.', 'duplicate-content'), 409);
        }

        set_transient($duplication_key, true, 30);

        $unique_id = uniqid('dup_' . $post_id . '_', true);
        set_transient('wpdc_unique_' . $post_id, $unique_id, 30);

        try {
            $duplicate_id = $this->duplicationService->duplicatePost($post_id);

            // Clean up only if we're the same process
            if (get_transient('wpdc_unique_' . $post_id) === $unique_id) {
                delete_transient($duplication_key);
                delete_transient('wpdc_unique_' . $post_id);
            }

            if ($duplicate_id && is_numeric($duplicate_id)) {
                $redirect_url = admin_url('post.php?post=' . $duplicate_id . '&action=edit');
                wp_redirect($redirect_url);
                exit;
            } else {
                wp_die(esc_html__('Failed to duplicate post. Please try again.', 'duplicate-content'), 500);
            }
        } catch (Exception $e) {
            delete_transient($duplication_key);
            delete_transient('wpdc_unique_' . $post_id);
            wp_die(esc_html__('An error occurred while duplicating the post.', 'duplicate-content'), 500);
        }
    }

    public function duplicateTaxonomyAjax()
    {
        $term_id  = isset($_GET['term_id']) ? absint($_GET['term_id']) : 0;
        $taxonomy = isset($_GET['taxonomy']) ? sanitize_text_field(wp_unslash($_GET['taxonomy'])) : '';
        $nonce    = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';

        if (empty($term_id) || empty($taxonomy) || empty($nonce)) {
            wp_die(esc_html__('Invalid request parameters.', 'duplicate-content'), 400);
        }

        if ( ! wp_verify_nonce($nonce, 'duplicate_content_taxonomy_' . $term_id)) {
            wp_die(esc_html__('Security check failed.', 'duplicate-content'), 403);
        }

        if ( ! $this->canUserDuplicate()) {
            wp_die(esc_html__('You do not have permission to duplicate content.', 'duplicate-content'), 403);
        }

        if ( ! taxonomy_exists($taxonomy)) {
            wp_die(esc_html__('Invalid taxonomy.', 'duplicate-content'), 400);
        }

        $term = get_term($term_id, $taxonomy);

        if ( ! $term || is_wp_error($term)) {
            wp_die(esc_html__('Term not found.', 'duplicate-content'), 404);
        }

        if ( ! current_user_can('manage_categories')) {
            wp_die(esc_html__('You do not have permission to manage taxonomies.', 'duplicate-content'), 403);
        }

        // Check for concurrent duplication
        $duplication_key = 'wpdc_proc_term_' . $term_id;
        if (get_transient($duplication_key)) {
            wp_die(esc_html__('This term is already being duplicated. Please wait.', 'duplicate-content'), 409);
        }

        set_transient($duplication_key, true, 10);

        $unique_id = uniqid('dup_term_' . $term_id . '_', true);
        set_transient('wpdc_unique_term_' . $term_id, $unique_id, 30);

        try {
            $duplicate_id = $this->duplicationService->duplicateTaxonomy($term_id, $taxonomy);

            // Clean up only if we're the same process
            if (get_transient('wpdc_unique_term_' . $term_id) === $unique_id) {
                delete_transient($duplication_key);
                delete_transient('wpdc_unique_term_' . $term_id);
            }

            if ($duplicate_id && is_numeric($duplicate_id)) {
                $redirect_url = admin_url('edit-tags.php?taxonomy=' . $taxonomy . '&message=duplicated');
                wp_redirect($redirect_url);
                exit;
            } else {
                wp_die(esc_html__('Failed to duplicate term. Please try again.', 'duplicate-content'), 500);
            }
        } catch (Exception $e) {
            delete_transient($duplication_key);
            delete_transient('wpdc_unique_term_' . $term_id);
            wp_die(esc_html__('An error occurred while duplicating the term.', 'duplicate-content'), 500);
        }
    }


    private function canUserDuplicate()
    {
        if ( ! current_user_can('edit_posts')) {
            return false;
        }

        $permissions = $this->settings->get('duplicate_content_permissions', ['administrator']);

        // Handle backward compatibility - convert old single value to array
        if ( ! is_array($permissions)) {
            $permissions = [$permissions];
        }

        if (empty($permissions)) {
            return current_user_can('manage_options');
        }

        $current_user = wp_get_current_user();
        $user_roles   = $current_user->roles;

        foreach ($permissions as $role) {
            if (in_array($role, $user_roles, true)) {
                return true;
            }
        }

        return false;
    }

    private function isDuplicatablePostType($post_type)
    {
        $duplicatable_types = ['post', 'page'];
        $custom_post_types  = get_post_types(['_builtin' => false]);
        $duplicatable_types = array_merge($duplicatable_types, $custom_post_types);

        return in_array($post_type, $duplicatable_types, true);
    }

    private function isContentTypeEnabled($content_type)
    {
        $options = get_option('duplicate_content_options', []);

        switch ($content_type) {
            case 'post':
                return isset($options['duplicate_content_enable_posts']) ? $options['duplicate_content_enable_posts'] === '1' : true;
            case 'page':
                return isset($options['duplicate_content_enable_pages']) ? $options['duplicate_content_enable_pages'] === '1' : true;
            default:
                return isset($options['duplicate_content_enable_custom_post_types']) ? $options['duplicate_content_enable_custom_post_types'] === '1' : true;
        }
    }

    private function isTaxonomyEnabled($taxonomy)
    {
        $options = get_option('duplicate_content_options', []);

        switch ($taxonomy) {
            case 'category':
                return isset($options['duplicate_content_enable_categories']) ? $options['duplicate_content_enable_categories'] === '1' : true;
            case 'post_tag':
                return isset($options['duplicate_content_enable_tags']) ? $options['duplicate_content_enable_tags'] === '1' : true;
            default:
                return isset($options['duplicate_content_enable_custom_taxonomies']) ? $options['duplicate_content_enable_custom_taxonomies'] === '1' : true;
        }
    }

    public function displayDashboard()
    {
        $dashboard_file = plugin_dir_path(dirname(dirname(__FILE__))) . 'src/Views/dashboard.php';
        if (file_exists($dashboard_file)) {
            require_once $dashboard_file;
        }
    }

    public function displaySettings()
    {
        $settings_file = plugin_dir_path(dirname(dirname(__FILE__))) . 'src/Views/settings.php';
        if (file_exists($settings_file)) {
            require_once $settings_file;
        }
    }

    public function displayHelp()
    {
        $help_file = plugin_dir_path(dirname(dirname(__FILE__))) . 'src/Views/help.php';
        if (file_exists($help_file)) {
            require_once $help_file;
        }
    }
}
