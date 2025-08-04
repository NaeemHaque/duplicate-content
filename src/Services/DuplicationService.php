<?php

namespace WPDuplicate\Services;

use WPDuplicate\Models\Settings;

if ( ! defined('ABSPATH')) {
    exit;
}

class DuplicationService
{
    private $settings;

    public function __construct()
    {
        $this->settings = new Settings();
    }

    public function duplicatePost($post_id)
    {
        $post_id = absint($post_id);
        if (empty($post_id)) {
            error_log('WP Duplicate: Invalid post ID provided');

            return false;
        }

        $post = get_post($post_id);
        if ( ! $post || is_wp_error($post)) {
            error_log('WP Duplicate: Post not found for ID ' . $post_id);

            return false;
        }

        // Check if user can edit this post type
        if ( ! current_user_can('edit_posts') || ! current_user_can('edit_post', $post_id)) {
            error_log('WP Duplicate: User lacks permission to duplicate post ID ' . $post_id);

            return false;
        }

        $default_status   = $this->settings->get('wp_duplicate_copy_status', 'draft');
        $title_prefix     = $this->settings->get('wp_duplicate_copy_title', 'Copy of ');
        $title_suffix     = $this->settings->get('wp_duplicate_copy_suffix', '');
        $copy_meta        = $this->settings->get('wp_duplicate_copy_meta', '1');
        $copy_taxonomies  = $this->settings->get('wp_duplicate_copy_taxonomies', '1');
        $copy_comments    = $this->settings->get('wp_duplicate_copy_comments', '');
        $copy_attachments = $this->settings->get('wp_duplicate_copy_attachments', '');

        // Handle status based on settings
        $post_status     = $this->validatePostStatus($default_status, $post->post_status);
        $formatted_title = $this->formatDuplicateTitle($title_prefix, $post->post_title, $title_suffix);

        $new_post_data = [
            'post_title'     => wp_strip_all_tags($formatted_title),
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_status'    => $post_status,
            'post_type'      => $post->post_type,
            'post_author'    => get_current_user_id(),
            'post_parent'    => absint($post->post_parent),
            'menu_order'     => absint($post->menu_order),
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
        ];

        $new_post_id = wp_insert_post($new_post_data, true);

        if (is_wp_error($new_post_id) || ! $new_post_id) {
            error_log(
                'WP Duplicate: Failed to duplicate post ID ' . $post_id . ' - ' . $new_post_id->get_error_message()
            );

            return false;
        }

        if ($copy_meta === '1') {
            $this->copyPostMeta($post_id, $new_post_id);
        }

        if ($copy_taxonomies === '1') {
            $this->copyPostTaxonomies($post_id, $new_post_id, $post->post_type);
        }

        $this->copyFeaturedImage($post_id, $new_post_id);

        if ($copy_comments === '1') {
            $this->copyComments($post_id, $new_post_id);
        }

        if ($copy_attachments === '1') {
            $this->copyAttachments($post_id, $new_post_id);
        }

        // Hook for developers
        do_action('wp_duplicate_post_created', $new_post_id, $post_id);

        return $new_post_id;
    }

    public function duplicateTaxonomy($term_id, $taxonomy)
    {
        $term_id = absint($term_id);

        if (empty($term_id)) {
            error_log('WP Duplicate: Invalid term ID provided');

            return false;
        }

        if ( ! taxonomy_exists($taxonomy)) {
            error_log('WP Duplicate: Invalid taxonomy ' . $taxonomy);

            return false;
        }

        $term = get_term($term_id, $taxonomy);

        if ( ! $term || is_wp_error($term)) {
            error_log('WP Duplicate: Term not found for ID ' . $term_id . ' in taxonomy ' . $taxonomy);

            return false;
        }

        if ( ! current_user_can('manage_categories')) {
            error_log('WP Duplicate: User lacks permission to duplicate taxonomy terms');

            return false;
        }

        $title_prefix    = $this->settings->get('wp_duplicate_copy_title', 'Copy of ');
        $title_suffix    = $this->settings->get('wp_duplicate_copy_suffix', '');
        $formatted_title = $this->formatDuplicateTitle($title_prefix, $term->name, $title_suffix);

        $base_slug   = sanitize_title($term->slug . '-copy');
        $unique_slug = $this->generateUniqueSlug($base_slug, $taxonomy);

        $new_term_data = [
            'description' => $term->description,
            'slug'        => $unique_slug,
        ];

        // Handle parent relationship
        if ( ! empty($term->parent)) {
            $parent_term = get_term($term->parent, $taxonomy);
            if ($parent_term && ! is_wp_error($parent_term)) {
                $new_term_data['parent'] = $term->parent;
            }
        }

        $new_term = wp_insert_term(wp_strip_all_tags($formatted_title), $taxonomy, $new_term_data);

        if (is_wp_error($new_term)) {
            error_log(
                'WP Duplicate: Failed to duplicate term ID ' . $term_id . ' in taxonomy ' . $taxonomy . ' - ' . $new_term->get_error_message(
                )
            );

            return false;
        }

        if (isset($new_term['term_id'])) {
            $this->copyTermMeta($term_id, $new_term['term_id']);

            do_action('wp_duplicate_term_created', $new_term['term_id'], $term_id, $taxonomy);

            return $new_term['term_id'];
        }

        return false;
    }

    private function validatePostStatus($setting_status, $original_status)
    {
        $valid_statuses = get_post_stati();

        if ($setting_status === 'same') {
            return in_array($original_status, array_keys($valid_statuses), true) ? $original_status : 'draft';
        }

        return in_array($setting_status, array_keys($valid_statuses), true) ? $setting_status : 'draft';
    }

    private function generateUniqueSlug($base_slug, $taxonomy, $counter = 1)
    {
        $slug = $counter === 1 ? $base_slug : $base_slug . '-' . $counter;

        $existing = get_term_by('slug', $slug, $taxonomy);
        if ($existing) {
            return $this->generateUniqueSlug($base_slug, $taxonomy, $counter + 1);
        }

        return $slug;
    }

    private function copyPostMeta($original_id, $new_id)
    {
        $original_id = absint($original_id);
        $new_id      = absint($new_id);

        if (empty($original_id) || empty($new_id)) {
            return false;
        }

        $meta_keys = get_post_custom_keys($original_id);
        if ( ! $meta_keys || ! is_array($meta_keys)) {
            return false;
        }

        $protected_keys = $this->getProtectedMetaKeys();

        foreach ($meta_keys as $meta_key) {
            // Skip protected and system meta keys
            if (in_array($meta_key, $protected_keys, true) || $this->isProtectedMetaKey($meta_key)) {
                continue;
            }

            $meta_values = get_post_meta($original_id, $meta_key, false);
            if (is_array($meta_values)) {
                foreach ($meta_values as $meta_value) {
                    add_post_meta($new_id, $meta_key, $meta_value);
                }
            }
        }

        return true;
    }

    private function copyPostTaxonomies($original_id, $new_id, $post_type)
    {
        $original_id = absint($original_id);
        $new_id      = absint($new_id);

        if (empty($original_id) || empty($new_id)) {
            return false;
        }

        $taxonomies = get_object_taxonomies($post_type);
        if ( ! is_array($taxonomies)) {
            return false;
        }

        foreach ($taxonomies as $taxonomy) {
            if ( ! taxonomy_exists($taxonomy)) {
                continue;
            }

            $terms = wp_get_object_terms($original_id, $taxonomy, ['fields' => 'ids']);
            if ( ! is_wp_error($terms) && is_array($terms)) {
                $result = wp_set_object_terms($new_id, $terms, $taxonomy);
                if (is_wp_error($result)) {
                    error_log(
                        'WP Duplicate: Failed to copy taxonomy ' . $taxonomy . ' - ' . $result->get_error_message()
                    );
                }
            }
        }

        return true;
    }

    private function copyFeaturedImage($original_id, $new_id)
    {
        $original_id = absint($original_id);
        $new_id      = absint($new_id);

        if (empty($original_id) || empty($new_id)) {
            return false;
        }

        $thumbnail_id = get_post_thumbnail_id($original_id);
        if ($thumbnail_id && wp_attachment_is_image($thumbnail_id)) {
            set_post_thumbnail($new_id, $thumbnail_id);

            return true;
        }

        return false;
    }

    private function copyComments($original_id, $new_id)
    {
        $original_id = absint($original_id);
        $new_id      = absint($new_id);

        if (empty($original_id) || empty($new_id)) {
            return false;
        }

        if ( ! current_user_can('moderate_comments')) {
            return false;
        }

        $comments = get_comments(['post_id' => $original_id, 'status' => 'approve']);

        if ( ! is_array($comments)) {
            return false;
        }

        foreach ($comments as $comment) {
            if ( ! is_object($comment)) {
                continue;
            }

            $comment_data = [
                'comment_post_ID'      => $new_id,
                'comment_author'       => sanitize_text_field($comment->comment_author),
                'comment_author_email' => sanitize_email($comment->comment_author_email),
                'comment_author_url'   => esc_url_raw($comment->comment_author_url),
                'comment_content'      => wp_kses_post($comment->comment_content),
                'comment_type'         => sanitize_text_field($comment->comment_type),
                'comment_parent'       => absint($comment->comment_parent),
                'user_id'              => absint($comment->user_id),
                'comment_date'         => $comment->comment_date,
                'comment_approved'     => 'approve' === $comment->comment_approved ? 1 : 0,
            ];

            $result = wp_insert_comment($comment_data);
            if ( ! $result) {
                error_log('WP Duplicate: Failed to copy comment from post ' . $original_id);
            }
        }

        return true;
    }

    private function copyAttachments($original_id, $new_id)
    {
        $original_id = absint($original_id);
        $new_id      = absint($new_id);

        if (empty($original_id) || empty($new_id)) {
            return false;
        }

        if ( ! current_user_can('upload_files')) {
            return false;
        }

        $attachments = get_attached_media('', $original_id);
        if ( ! is_array($attachments)) {
            return false;
        }

        foreach ($attachments as $attachment) {
            if ( ! is_object($attachment) || $attachment->post_type !== 'attachment') {
                continue;
            }

            // Security: Only copy image attachments to prevent potential security issues
            if ( ! wp_attachment_is_image($attachment->ID)) {
                continue;
            }

            $attachment_data = [
                'post_title'   => sanitize_text_field($attachment->post_title),
                'post_content' => wp_kses_post($attachment->post_content),
                'post_excerpt' => sanitize_textarea_field($attachment->post_excerpt),
                'post_status'  => 'inherit',
                'post_type'    => 'attachment',
                'post_parent'  => $new_id,
                'post_author'  => get_current_user_id(),
            ];

            $new_attachment_id = wp_insert_post($attachment_data, true);
            if (is_wp_error($new_attachment_id) || ! $new_attachment_id) {
                error_log('WP Duplicate: Failed to copy attachment ' . $attachment->ID);
                continue;
            }

            // Copy attachment meta (excluding system meta)
            $this->copyAttachmentMeta($attachment->ID, $new_attachment_id);
        }

        return true;
    }

    private function copyAttachmentMeta($original_id, $new_id)
    {
        $meta_keys = get_post_custom_keys($original_id);
        if ( ! is_array($meta_keys)) {
            return false;
        }

        $protected_keys       = $this->getProtectedMetaKeys();
        $attachment_protected = ['_wp_attachment_metadata', '_wp_attached_file'];
        $protected_keys       = array_merge($protected_keys, $attachment_protected);

        foreach ($meta_keys as $meta_key) {
            if (in_array($meta_key, $protected_keys, true) || $this->isProtectedMetaKey($meta_key)) {
                continue;
            }

            $meta_values = get_post_meta($original_id, $meta_key, false);
            if (is_array($meta_values)) {
                foreach ($meta_values as $meta_value) {
                    add_post_meta($new_id, $meta_key, $meta_value);
                }
            }
        }

        return true;
    }

    private function copyTermMeta($original_id, $new_id)
    {
        $original_id = absint($original_id);
        $new_id      = absint($new_id);

        if (empty($original_id) || empty($new_id)) {
            return false;
        }

        $term_meta = get_term_meta($original_id);
        if ( ! is_array($term_meta)) {
            return false;
        }

        foreach ($term_meta as $meta_key => $meta_values) {
            if ($this->isProtectedMetaKey($meta_key)) {
                continue;
            }

            if (is_array($meta_values)) {
                foreach ($meta_values as $meta_value) {
                    add_term_meta($new_id, $meta_key, maybe_unserialize($meta_value));
                }
            }
        }

        return true;
    }

    private function formatDuplicateTitle($prefix, $title, $suffix)
    {
        $prefix = trim(sanitize_text_field($prefix));
        $suffix = trim(sanitize_text_field($suffix));
        $title  = trim(sanitize_text_field($title));

        if (empty($title)) {
            $title = __('Untitled', 'wp-duplicate');
        }

        $formatted_title = $title;

        if ( ! empty($prefix)) {
            $formatted_title = $prefix . ' ' . $formatted_title;
        }

        if ( ! empty($suffix)) {
            $formatted_title = $formatted_title . ' ' . $suffix;
        }

        return $formatted_title;
    }

    private function getProtectedMetaKeys()
    {
        return [
            '_edit_lock',
            '_edit_last',
            '_wp_old_slug',
            '_wp_old_date',
            '_thumbnail_id',
            '_wp_page_template',
            '_wp_attachment_metadata',
            '_wp_attached_file',
        ];
    }

    private function isProtectedMetaKey($meta_key)
    {
        if (strpos($meta_key, '_') === 0) {
            return true;
        }

        // Additional protection patterns
        $protected_patterns = [
            'wp_',
            'wpdb_',
            'session_',
            'transient_',
        ];

        foreach ($protected_patterns as $pattern) {
            if (strpos($meta_key, $pattern) === 0) {
                return true;
            }
        }

        return false;
    }
}
