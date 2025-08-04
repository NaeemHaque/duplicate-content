<?php

namespace WPDuplicate\Models;

if ( ! defined('ABSPATH')) {
    exit;
}

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
            'wp_duplicate_copy_suffix',
            __('Title Suffix', 'wp-duplicate'),
            [$this, 'suffixFieldCallback'],
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
            'wp_duplicate_content_types',
            __('Content Types', 'wp-duplicate'),
            [$this, 'contentTypesFieldCallback'],
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
        $sanitized_input = [];

        if (isset($input['wp_duplicate_copy_status'])) {
            $status                                      = sanitize_text_field($input['wp_duplicate_copy_status']);
            $allowed_statuses                            = ['draft', 'pending', 'private', 'publish', 'same'];
            $sanitized_input['wp_duplicate_copy_status'] = in_array(
                $status,
                $allowed_statuses,
                true
            ) ? $status : 'draft';
        }

        if (isset($input['wp_duplicate_copy_title'])) {
            $sanitized_input['wp_duplicate_copy_title'] = sanitize_text_field($input['wp_duplicate_copy_title']);
        }

        if (isset($input['wp_duplicate_copy_suffix'])) {
            $sanitized_input['wp_duplicate_copy_suffix'] = sanitize_text_field($input['wp_duplicate_copy_suffix']);
        }

        // Copy options settings
        $sanitized_input['wp_duplicate_copy_meta']        = isset($input['wp_duplicate_copy_meta']) ? '1' : '';
        $sanitized_input['wp_duplicate_copy_taxonomies']  = isset($input['wp_duplicate_copy_taxonomies']) ? '1' : '';
        $sanitized_input['wp_duplicate_copy_comments']    = isset($input['wp_duplicate_copy_comments']) ? '1' : '';
        $sanitized_input['wp_duplicate_copy_attachments'] = isset($input['wp_duplicate_copy_attachments']) ? '1' : '';

        // Content types settings
        $sanitized_input['wp_duplicate_enable_posts']             = isset($input['wp_duplicate_enable_posts']) ? '1' : '';
        $sanitized_input['wp_duplicate_enable_pages']             = isset($input['wp_duplicate_enable_pages']) ? '1' : '';
        $sanitized_input['wp_duplicate_enable_categories']        = isset($input['wp_duplicate_enable_categories']) ? '1' : '';
        $sanitized_input['wp_duplicate_enable_tags']              = isset($input['wp_duplicate_enable_tags']) ? '1' : '';
        $sanitized_input['wp_duplicate_enable_custom_post_types'] = isset($input['wp_duplicate_enable_custom_post_types']) ? '1' : '';
        $sanitized_input['wp_duplicate_enable_custom_taxonomies'] = isset($input['wp_duplicate_enable_custom_taxonomies']) ? '1' : '';

        if (isset($input['wp_duplicate_permissions']) && is_array($input['wp_duplicate_permissions'])) {
            $valid_roles           = array_keys(wp_roles()->get_names());
            $sanitized_permissions = [];

            foreach ($input['wp_duplicate_permissions'] as $role) {
                $role = sanitize_text_field($role);
                if (in_array($role, $valid_roles, true)) {
                    $sanitized_permissions[] = $role;
                }
            }

            $sanitized_input['wp_duplicate_permissions'] = ! empty($sanitized_permissions) ? $sanitized_permissions : ['administrator'];
        } else {
            $sanitized_input['wp_duplicate_permissions'] = ['administrator'];
        }

        return $sanitized_input;
    }

    public function generalSectionCallback()
    {
        echo '<p>' . esc_html__('Configure how WP Duplicate behaves when duplicating content.', 'wp-duplicate') . '</p>';
    }

    public function statusFieldCallback()
    {
        $options        = get_option($this->option_name, []);
        $current_status = isset($options['wp_duplicate_copy_status']) ? $options['wp_duplicate_copy_status'] : 'draft';
        ?>
        <select
            name="<?php echo esc_attr($this->option_name); ?>[wp_duplicate_copy_status]"
            class="wpdc-select"
            id="wp_duplicate_copy_status"
        >
            <option value="draft" <?php selected($current_status, 'draft'); ?>>
                <?php esc_html_e('Draft', 'wp-duplicate'); ?></option>
            <option value="pending" <?php selected($current_status, 'pending'); ?>>
                <?php esc_html_e('Pending Review', 'wp-duplicate'); ?>
            </option>
            <option value="private" <?php selected($current_status, 'private'); ?>>
                <?php esc_html_e('Private', 'wp-duplicate'); ?></option>
            <option value="publish"
                <?php selected($current_status, 'publish'); ?>>
                <?php esc_html_e('Publish', 'wp-duplicate'); ?>
            </option>
            <option value="same" <?php selected($current_status, 'same'); ?>>
                <?php esc_html_e('Same as original', 'wp-duplicate'); ?>
            </option>
        </select>
        <p class="description">
            <?php esc_html_e('Choose the default status for duplicated posts and pages.', 'wp-duplicate'); ?></p>
        <?php
    }

    public function titleFieldCallback()
    {
        $options       = get_option($this->option_name, []);
        $current_title = isset($options['wp_duplicate_copy_title']) ? $options['wp_duplicate_copy_title'] : 'Copy of ';
        ?>
        <input type="text"
               name="<?php echo esc_attr($this->option_name); ?>[wp_duplicate_copy_title]"
               id="wp_duplicate_copy_title"
               class="wpdc-input regular-text"
               value="<?php echo esc_attr($current_title); ?>"
        />
        <p class="description">
            <?php esc_html_e('Text to add before the original title. A space will be automatically added.', 'wp-duplicate'); ?>
        </p>
        <?php
    }

    public function suffixFieldCallback()
    {
        $options        = get_option($this->option_name, []);
        $current_suffix = isset($options['wp_duplicate_copy_suffix']) ? $options['wp_duplicate_copy_suffix'] : '';
        ?>
        <input type="text"
               name="<?php echo esc_attr($this->option_name); ?>[wp_duplicate_copy_suffix]"
               id="wp_duplicate_copy_suffix"
               class="wpdc-input regular-text"
               value="<?php echo esc_attr($current_suffix); ?>"
        />
        <p class="description">
            <?php esc_html_e('Text to add after the original title. A space will be automatically added.', 'wp-duplicate'); ?>
        </p>
        <?php
    }

    public function copyOptionsFieldCallback()
    {
        $options = get_option($this->option_name, []);
        ?>
        <fieldset>
            <legend class="screen-reader-text">
                <?php esc_html_e('Copy Options', 'wp-duplicate'); ?>
            </legend>

            <label for="wp_duplicate_copy_meta">
                <input type="checkbox"
                       name="<?php echo esc_attr($this->option_name); ?>[wp_duplicate_copy_meta]"
                       id="wp_duplicate_copy_meta"
                       value="1"
                    <?php checked(isset($options['wp_duplicate_copy_meta']) ? $options['wp_duplicate_copy_meta'] : '1', '1'); ?>
                />
                <?php esc_html_e('Copy custom fields and meta data', 'wp-duplicate'); ?>
            </label><br/>

            <label for="wp_duplicate_copy_taxonomies">
                <input type="checkbox"
                       name="<?php echo esc_attr($this->option_name); ?>[wp_duplicate_copy_taxonomies]"
                       id="wp_duplicate_copy_taxonomies"
                       value="1"
                    <?php checked(isset($options['wp_duplicate_copy_taxonomies']) ? $options['wp_duplicate_copy_taxonomies'] : '1', '1'); ?>
                />
                <?php esc_html_e('Copy categories, tags, and other taxonomies', 'wp-duplicate'); ?>
            </label><br/>

            <label for="wp_duplicate_copy_comments">
                <input type="checkbox"
                       name="<?php echo esc_attr($this->option_name); ?>[wp_duplicate_copy_comments]"
                       id="wp_duplicate_copy_comments"
                       value="1"
                    <?php checked(isset($options['wp_duplicate_copy_comments']) ? $options['wp_duplicate_copy_comments'] : '', '1'); ?>
                />
                <?php esc_html_e('Copy comments (if applicable)', 'wp-duplicate'); ?>
            </label><br/>

            <label for="wp_duplicate_copy_attachments">
                <input type="checkbox"
                       name="<?php echo esc_attr($this->option_name); ?>[wp_duplicate_copy_attachments]"
                       id="wp_duplicate_copy_attachments"
                       value="1"
                    <?php checked(isset($options['wp_duplicate_copy_attachments']) ? $options['wp_duplicate_copy_attachments'] : '', '1'); ?>
                />
                <?php esc_html_e('Copy attached media files', 'wp-duplicate'); ?>
            </label>
        </fieldset>
        <?php
    }

    public function contentTypesFieldCallback()
    {
        $options = get_option($this->option_name, []);
        ?>
        <div class="wpdc-settings-grid">
            <div class="wpdc-settings-section">
                <h4><?php esc_html_e('Post Types', 'wp-duplicate'); ?></h4>
                <fieldset>
                    <label for="wp_duplicate_enable_posts">
                        <input type="checkbox"
                               name="<?php
                               echo esc_attr($this->option_name); ?>[wp_duplicate_enable_posts]"
                               id="wp_duplicate_enable_posts"
                               value="1"
                            <?php checked(isset($options['wp_duplicate_enable_posts']) ? $options['wp_duplicate_enable_posts'] : '1', '1'); ?>
                        />
                        <?php esc_html_e('Enable for Posts', 'wp-duplicate'); ?>
                    </label>
                    <br/>

                    <label for="wp_duplicate_enable_pages">
                        <input type="checkbox"
                               name="<?php echo esc_attr($this->option_name); ?>[wp_duplicate_enable_pages]"
                               id="wp_duplicate_enable_pages"
                               value="1"
                            <?php checked(isset($options['wp_duplicate_enable_pages']) ? $options['wp_duplicate_enable_pages'] : '1', '1'); ?>
                        />
                        <?php esc_html_e('Enable for Pages', 'wp-duplicate'); ?>
                    </label>

                    <br/>

                    <label for="wp_duplicate_enable_custom_post_types">
                        <input type="checkbox"
                               name="<?php echo esc_attr($this->option_name); ?>[wp_duplicate_enable_custom_post_types]"
                               id="wp_duplicate_enable_custom_post_types"
                               value="1"
                            <?php checked(isset($options['wp_duplicate_enable_custom_post_types']) ? $options['wp_duplicate_enable_custom_post_types'] : '1', '1'); ?>
                        />
                        <?php esc_html_e('Enable for Custom Post Types', 'wp-duplicate'); ?>
                    </label>
                </fieldset>
            </div>

            <div class="wpdc-settings-section">
                <h4><?php esc_html_e('Taxonomies', 'wp-duplicate'); ?></h4>
                <fieldset>
                    <label for="wp_duplicate_enable_categories">
                        <input type="checkbox"
                               name="<?php echo esc_attr($this->option_name); ?>[wp_duplicate_enable_categories]"
                               id="wp_duplicate_enable_categories"
                               value="1"
                            <?php checked(isset($options['wp_duplicate_enable_categories']) ? $options['wp_duplicate_enable_categories'] : '1', '1'); ?>
                        />
                        <?php esc_html_e('Enable for Categories', 'wp-duplicate'); ?>
                    </label>

                    <br/>

                    <label for="wp_duplicate_enable_tags">
                        <input type="checkbox"
                               name="<?php echo esc_attr($this->option_name); ?>[wp_duplicate_enable_tags]"
                               id="wp_duplicate_enable_tags"
                               value="1"
                            <?php checked(isset($options['wp_duplicate_enable_tags']) ? $options['wp_duplicate_enable_tags'] : '1', '1'); ?>
                        />
                        <?php esc_html_e('Enable for Tags', 'wp-duplicate'); ?>
                    </label>

                    <br/>

                    <label for="wp_duplicate_enable_custom_taxonomies">
                        <input type="checkbox"
                               name="<?php echo esc_attr($this->option_name); ?>[wp_duplicate_enable_custom_taxonomies]"
                               id="wp_duplicate_enable_custom_taxonomies"
                               value="1"
                            <?php checked(isset($options['wp_duplicate_enable_custom_taxonomies']) ? $options['wp_duplicate_enable_custom_taxonomies'] : '1', '1'); ?>
                        />
                        <?php esc_html_e('Enable for Custom Taxonomies', 'wp-duplicate'); ?>
                    </label>
                </fieldset>
            </div>
        </div>
        <p class="description"><?php
            esc_html_e(
                'Select which content types should have duplicate functionality enabled. Unchecked items will not show duplicate buttons.', 'wp-duplicate');
            ?>
        </p>
        <?php
    }

    public function permissionsFieldCallback()
    {
        $options = get_option($this->option_name, []);

        // Handle backward compatibility - convert old single value to array
        $current_permissions = isset($options['wp_duplicate_permissions']) ? $options['wp_duplicate_permissions'] : ['administrator'];
        if ( ! is_array($current_permissions)) {
            $current_permissions = [$current_permissions];
        }

        $wp_roles = wp_roles();
        $roles    = $wp_roles->get_names();
        ?>
        <fieldset>
            <legend class="screen-reader-text">
                <?php esc_html_e('User Permissions', 'wp-duplicate'); ?>
            </legend>
            <p class="description">
                <?php esc_html_e(
                    'Select which user roles can duplicate content. Users with any of the selected roles will have access to duplicate functionality.', 'wp-duplicate');
                ?>
            </p>
            <?php
            foreach ($roles as $role_key => $role_name) {
                $checked = in_array($role_key, $current_permissions, true) ? 'checked' : ''; ?>
                <label for="wp_duplicate_role_<?php
                echo esc_attr($role_key); ?>">
                    <input type="checkbox"
                           name="<?php echo esc_attr($this->option_name); ?>[wp_duplicate_permissions][]"
                           id="wp_duplicate_role_<?php echo esc_attr($role_key); ?>"
                           value="<?php echo esc_attr($role_key); ?>"
                        <?php echo $checked; ?>
                    />
                    <?php
                    echo esc_html($role_name); ?>
                </label><br/>
                <?php
            }
            ?>
        </fieldset>
        <?php
    }

    public function get($key, $default = '')
    {
        $options = get_option($this->option_name, []);

        return isset($options[$key]) ? $options[$key] : $default;
    }

    public function set($key, $value)
    {
        $options       = get_option($this->option_name, []);
        $options[$key] = $value;

        return update_option($this->option_name, $options);
    }
} 
