<?php

namespace WPDuplicate\Services;

use WPDuplicate\Models\Settings;

class DuplicationService
{

    private $settings;

    public function __construct()
    {
        $this->settings = new Settings();
    }

    public function duplicatePost($post_id)
    {
        $post = get_post($post_id);

        if ( ! $post) {
            return false;
        }

        $default_status   = $this->settings->get('wp_duplicate_copy_status', 'draft');
        $title_prefix     = $this->settings->get('wp_duplicate_copy_title', 'Copy of ');
        $copy_meta        = $this->settings->get('wp_duplicate_copy_meta', '1');
        $copy_taxonomies  = $this->settings->get('wp_duplicate_copy_taxonomies', '1');
        $copy_comments    = $this->settings->get('wp_duplicate_copy_comments', '');
        $copy_attachments = $this->settings->get('wp_duplicate_copy_attachments', '');

        $new_post_data = array(
            'post_title'     => $title_prefix . $post->post_title,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_status'    => $default_status,
            'post_type'      => $post->post_type,
            'post_author'    => get_current_user_id(),
            'post_parent'    => $post->post_parent,
            'menu_order'     => $post->menu_order,
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
        );

        $new_post_id = wp_insert_post($new_post_data);

        if ( ! $new_post_id) {
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

        return $new_post_id;
    }

    public function duplicateTaxonomy($term_id, $taxonomy)
    {
        $term = get_term($term_id, $taxonomy);

        if ( ! $term) {
            return false;
        }

        $title_prefix = $this->settings->get('wp_duplicate_copy_title', 'Copy of ');

        $new_term_data = array(
            'description' => $term->description,
            'slug'        => $term->slug . '-copy',
        );

        $new_term = wp_insert_term($title_prefix . $term->name, $taxonomy, $new_term_data);

        if (is_wp_error($new_term)) {
            return false;
        }

        $this->copyTermMeta($term_id, $new_term['term_id']);

        return $new_term['term_id'];
    }

    private function copyPostMeta($original_id, $new_id)
    {
        $meta_keys = get_post_custom_keys($original_id);
        if ($meta_keys) {
            foreach ($meta_keys as $meta_key) {
                $meta_values = get_post_meta($original_id, $meta_key, false);
                foreach ($meta_values as $meta_value) {
                    add_post_meta($new_id, $meta_key, $meta_value);
                }
            }
        }
    }

    private function copyPostTaxonomies($original_id, $new_id, $post_type)
    {
        $taxonomies = get_object_taxonomies($post_type);
        foreach ($taxonomies as $taxonomy) {
            $terms = wp_get_object_terms($original_id, $taxonomy, array('fields' => 'ids'));
            wp_set_object_terms($new_id, $terms, $taxonomy);
        }
    }

    private function copyFeaturedImage($original_id, $new_id)
    {
        $thumbnail_id = get_post_thumbnail_id($original_id);
        if ($thumbnail_id) {
            set_post_thumbnail($new_id, $thumbnail_id);
        }
    }

    private function copyComments($original_id, $new_id)
    {
        $comments = get_comments(array('post_id' => $original_id));
        foreach ($comments as $comment) {
            $comment_data = array(
                'comment_post_ID'      => $new_id,
                'comment_author'       => $comment->comment_author,
                'comment_author_email' => $comment->comment_author_email,
                'comment_author_url'   => $comment->comment_author_url,
                'comment_content'      => $comment->comment_content,
                'comment_type'         => $comment->comment_type,
                'comment_parent'       => $comment->comment_parent,
                'user_id'              => $comment->user_id,
                'comment_date'         => $comment->comment_date,
                'comment_approved'     => $comment->comment_approved,
            );
            wp_insert_comment($comment_data);
        }
    }

    private function copyAttachments($original_id, $new_id)
    {
        $copy_attachments = $this->settings->get('wp_duplicate_copy_attachments', '');
        
        if ($copy_attachments !== '1') {
            return;
        }

        $attachments = get_attached_media('', $original_id);
        foreach ($attachments as $attachment) {
            $attachment_data   = array(
                'post_title'   => $attachment->post_title,
                'post_content' => $attachment->post_content,
                'post_excerpt' => $attachment->post_excerpt,
                'post_status'  => 'inherit',
                'post_type'    => 'attachment',
                'post_parent'  => $new_id,
            );
            $new_attachment_id = wp_insert_post($attachment_data);

            $attachment_meta_keys = get_post_custom_keys($attachment->ID);
            if ($attachment_meta_keys) {
                foreach ($attachment_meta_keys as $meta_key) {
                    $meta_values = get_post_meta($attachment->ID, $meta_key, false);
                    foreach ($meta_values as $meta_value) {
                        add_post_meta($new_attachment_id, $meta_key, $meta_value);
                    }
                }
            }
        }
    }

    private function copyTermMeta($original_id, $new_id)
    {
        $term_meta = get_term_meta($original_id);
        foreach ($term_meta as $meta_key => $meta_values) {
            foreach ($meta_values as $meta_value) {
                add_term_meta($new_id, $meta_key, $meta_value);
            }
        }
    }
} 
