<?php

namespace WPDuplicate\Models;

class Settings
{

    private $option_name = 'wp_duplicate_options';

    public function register()
    {
        register_setting(
            'wp_duplicate_options',
            $this->option_name,
            [$this, 'sanitizeSettings']
        );

        add_settings_section(
            'wp_duplicate_general_section',
            __('General Settings', 'wp-duplicate'),
            [$this, 'generalSectionCallback'],
            'wp_duplicate_settings'
        );

        add_settings_field(
            'wp_duplicate_copy_status',
            __('Default Status', 'wp-duplicate'),
            [$this, 'statusFieldCallback'],
            'wp_duplicate_settings',
            'wp_duplicate_general_section'
        );

        add_settings_field(
            'wp_duplicate_copy_title',
            __('Title Prefix', 'wp-duplicate'),
            [$this, 'titleFieldCallback'],
            'wp_duplicate_settings',
            'wp_duplicate_general_section'
        );

        add_settings_field(
            'wp_duplicate_copy_options',
            __('Copy Options', 'wp-duplicate'),
            [$this, 'copyOptionsFieldCallback'],
            'wp_duplicate_settings',
            'wp_duplicate_general_section'
        );

        add_settings_field(
            'wp_duplicate_permissions',
            __('User Permissions', 'wp-duplicate'),
            [$this, 'permissionsFieldCallback'],
            'wp_duplicate_settings',
            'wp_duplicate_general_section'
        );
    }

    public function sanitizeSettings($input)
    {
        $sanitized_input = array();

        if (isset($input['wp_duplicate_copy_status'])) {
            $sanitized_input['wp_duplicate_copy_status'] = sanitize_text_field(
                $input['wp_duplicate_copy_status']
            );
        }

        if (isset($input['wp_duplicate_copy_title'])) {
            $sanitized_input['wp_duplicate_copy_title'] = sanitize_text_field(
                $input['wp_duplicate_copy_title']
            );
        }

        $sanitized_input['wp_duplicate_copy_meta']        = isset($input['wp_duplicate_copy_meta']) ? '1' : '';
        $sanitized_input['wp_duplicate_copy_taxonomies']  = isset($input['wp_duplicate_copy_taxonomies']) ? '1' : '';
        $sanitized_input['wp_duplicate_copy_comments']    = isset($input['wp_duplicate_copy_comments']) ? '1' : '';
        $sanitized_input['wp_duplicate_copy_attachments'] = isset($input['wp_duplicate_copy_attachments']) ? '1' : '';

        if (isset($input['wp_duplicate_permissions'])) {
            $sanitized_input['wp_duplicate_permissions'] = sanitize_text_field(
                $input['wp_duplicate_permissions']
            );
        }

        return $sanitized_input;
    }

    public function generalSectionCallback()
    {
        echo '<p>' . __(
                'Configure how WP Duplicate behaves when duplicating content.',
                'wp-duplicate'
            ) . '</p>';
    }

    public function statusFieldCallback()
    {
        $options        = get_option($this->option_name, array());
        $current_status = isset($options['wp_duplicate_copy_status']) ? $options['wp_duplicate_copy_status'] : 'draft';
        ?>
        <select name="<?php
        echo $this->option_name; ?>[wp_duplicate_copy_status]" id="wp_duplicate_copy_status">
            <option value="draft" <?php
            selected(
                $current_status,
                'draft'
            ); ?>><?php
                _e('Draft', 'wp-duplicate'); ?></option>
            <option value="pending" <?php
            selected(
                $current_status,
                'pending'
            ); ?>><?php
                _e('Pending Review', 'wp-duplicate'); ?></option>
            <option value="private" <?php
            selected(
                $current_status,
                'private'
            ); ?>><?php
                _e('Private', 'wp-duplicate'); ?></option>
        </select>
        <p class="description"><?php
            _e(
                'Choose the default status for duplicated posts and pages.',
                'wp-duplicate'
            ); ?></p>
        <?php
    }

    public function titleFieldCallback()
    {
        $options       = get_option($this->option_name, array());
        $current_title = isset($options['wp_duplicate_copy_title']) ? $options['wp_duplicate_copy_title'] : 'Copy of ';
        ?>
        <input type="text" name="<?php
        echo $this->option_name; ?>[wp_duplicate_copy_title]" id="wp_duplicate_copy_title" class="regular-text"
               value="<?php
               echo esc_attr(
                   $current_title
               ); ?>"/>
        <p class="description"><?php
            _e('Text to add before the original title.', 'wp-duplicate'); ?></p>
        <?php
    }

    public function copyOptionsFieldCallback()
    {
        $options = get_option($this->option_name, array());
        ?>
        <fieldset>
            <legend class="screen-reader-text"><?php
                _e('Copy Options', 'wp-duplicate'); ?></legend>
            <label for="wp_duplicate_copy_meta">
                <input type="checkbox" name="<?php
                echo $this->option_name; ?>[wp_duplicate_copy_meta]" id="wp_duplicate_copy_meta" value="1" <?php
                checked(
                    isset($options['wp_duplicate_copy_meta']) ? $options['wp_duplicate_copy_meta'] : '1',
                    '1'
                ); ?> />
                <?php
                _e('Copy custom fields and meta data', 'wp-duplicate'); ?>
            </label>
            <br/>
            <label for="wp_duplicate_copy_taxonomies">
                <input type="checkbox" name="<?php
                echo $this->option_name; ?>[wp_duplicate_copy_taxonomies]" id="wp_duplicate_copy_taxonomies"
                       value="1" <?php
                checked(
                    isset($options['wp_duplicate_copy_taxonomies']) ? $options['wp_duplicate_copy_taxonomies'] : '1',
                    '1'
                ); ?> />
                <?php
                _e('Copy categories, tags, and other taxonomies', 'wp-duplicate'); ?>
            </label>
            <br/>
            <label for="wp_duplicate_copy_comments">
                <input type="checkbox" name="<?php
                echo $this->option_name; ?>[wp_duplicate_copy_comments]" id="wp_duplicate_copy_comments" value="1" <?php
                checked(
                    isset($options['wp_duplicate_copy_comments']) ? $options['wp_duplicate_copy_comments'] : '',
                    '1'
                ); ?> />
                <?php
                _e('Copy comments (if applicable)', 'wp-duplicate'); ?>
            </label>
            <br/>
            <label for="wp_duplicate_copy_attachments">
                <input type="checkbox" name="<?php
                echo $this->option_name; ?>[wp_duplicate_copy_attachments]" id="wp_duplicate_copy_attachments"
                       value="1" <?php
                checked(
                    isset($options['wp_duplicate_copy_attachments']) ? $options['wp_duplicate_copy_attachments'] : '',
                    '1'
                ); ?> />
                <?php
                _e('Copy attached media files', 'wp-duplicate'); ?>
            </label>
        </fieldset>
        <?php
    }

    public function permissionsFieldCallback()
    {
        $options             = get_option($this->option_name, array());
        $current_permissions = isset($options['wp_duplicate_permissions']) ? $options['wp_duplicate_permissions'] : 'administrator';
        ?>
        <fieldset>
            <legend class="screen-reader-text"><?php
                _e('User Permissions', 'wp-duplicate'); ?></legend>
            <label for="wp_duplicate_admin_only">
                <input type="radio" name="<?php
                echo $this->option_name; ?>[wp_duplicate_permissions]" id="wp_duplicate_admin_only"
                       value="administrator" <?php
                checked(
                    $current_permissions,
                    'administrator'
                ); ?> />
                <?php
                _e('Administrators only', 'wp-duplicate'); ?>
            </label>
            <br/>
            <label for="wp_duplicate_editor_plus">
                <input type="radio" name="<?php
                echo $this->option_name; ?>[wp_duplicate_permissions]" id="wp_duplicate_editor_plus"
                       value="editor" <?php
                checked(
                    $current_permissions,
                    'editor'
                ); ?> />
                <?php
                _e('Editors and Administrators', 'wp-duplicate'); ?>
            </label>
            <br/>
            <label for="wp_duplicate_author_plus">
                <input type="radio" name="<?php
                echo $this->option_name; ?>[wp_duplicate_permissions]" id="wp_duplicate_author_plus"
                       value="author" <?php
                checked(
                    $current_permissions,
                    'author'
                ); ?> />
                <?php
                _e('Authors, Editors, and Administrators', 'wp-duplicate'); ?>
            </label>
        </fieldset>
        <?php
    }

    public function get($key, $default = '')
    {
        $options = get_option($this->option_name, array());

        return isset($options[$key]) ? $options[$key] : $default;
    }

    public function set($key, $value)
    {
        $options       = get_option($this->option_name, array());
        $options[$key] = $value;

        return update_option($this->option_name, $options);
    }
} 
