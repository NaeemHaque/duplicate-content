<?php

namespace DuplicateContent\Models;

if ( ! defined('ABSPATH')) {
    exit;
}

class Settings
{
    private $option_name = 'duplicate_content_options';

    public function register()
    {
        register_setting(
            'duplicate_content_options',
            $this->option_name,
            [$this, 'sanitizeSettings']
        );

        add_settings_section(
            'duplicate_content_general_section',
            esc_html__('General Settings', 'duplicate-content'),
            [$this, 'generalSectionCallback'],
            'duplicate_content_settings'
        );

        add_settings_field(
            'duplicate_content_copy_status',
            esc_html__('Default Status', 'duplicate-content'),
            [$this, 'statusFieldCallback'],
            'duplicate_content_settings',
            'duplicate_content_general_section'
        );

        add_settings_field(
            'duplicate_content_copy_title',
            esc_html__('Title Prefix', 'duplicate-content'),
            [$this, 'titleFieldCallback'],
            'duplicate_content_settings',
            'duplicate_content_general_section'
        );

        add_settings_field(
            'duplicate_content_copy_suffix',
            esc_html__('Title Suffix', 'duplicate-content'),
            [$this, 'suffixFieldCallback'],
            'duplicate_content_settings',
            'duplicate_content_general_section'
        );

        add_settings_field(
            'duplicate_content_copy_options',
            esc_html__('Copy Options', 'duplicate-content'),
            [$this, 'copyOptionsFieldCallback'],
            'duplicate_content_settings',
            'duplicate_content_general_section'
        );

        add_settings_field(
            'duplicate_content_content_types',
            esc_html__('Content Types', 'duplicate-content'),
            [$this, 'contentTypesFieldCallback'],
            'duplicate_content_settings',
            'duplicate_content_general_section'
        );

        add_settings_field(
            'duplicate_content_permissions',
            esc_html__('User Permissions', 'duplicate-content'),
            [$this, 'permissionsFieldCallback'],
            'duplicate_content_settings',
            'duplicate_content_general_section'
        );
    }

    public function sanitizeSettings($input)
    {
        $sanitized_input = [];

        if (isset($input['duplicate_content_copy_status'])) {
            $status                                      = sanitize_text_field($input['duplicate_content_copy_status']);
            $allowed_statuses                            = ['draft', 'pending', 'private', 'publish', 'same'];
            $sanitized_input['duplicate_content_copy_status'] = in_array(
                $status,
                $allowed_statuses,
                true
            ) ? $status : 'draft';
        }

        if (isset($input['duplicate_content_copy_title'])) {
            $sanitized_input['duplicate_content_copy_title'] = sanitize_text_field($input['duplicate_content_copy_title']);
        }

        if (isset($input['duplicate_content_copy_suffix'])) {
            $sanitized_input['duplicate_content_copy_suffix'] = sanitize_text_field($input['duplicate_content_copy_suffix']);
        }

        // Copy options settings
        $sanitized_input['duplicate_content_copy_meta']        = isset($input['duplicate_content_copy_meta']) ? '1' : '';
        $sanitized_input['duplicate_content_copy_taxonomies']  = isset($input['duplicate_content_copy_taxonomies']) ? '1' : '';
        $sanitized_input['duplicate_content_copy_comments']    = isset($input['duplicate_content_copy_comments']) ? '1' : '';
        $sanitized_input['duplicate_content_copy_attachments'] = isset($input['duplicate_content_copy_attachments']) ? '1' : '';

        // Content types settings
        $sanitized_input['duplicate_content_enable_posts']             = isset($input['duplicate_content_enable_posts']) ? '1' : '';
        $sanitized_input['duplicate_content_enable_pages']             = isset($input['duplicate_content_enable_pages']) ? '1' : '';
        $sanitized_input['duplicate_content_enable_categories']        = isset($input['duplicate_content_enable_categories']) ? '1' : '';
        $sanitized_input['duplicate_content_enable_tags']              = isset($input['duplicate_content_enable_tags']) ? '1' : '';
        $sanitized_input['duplicate_content_enable_custom_post_types'] = isset($input['duplicate_content_enable_custom_post_types']) ? '1' : '';
        $sanitized_input['duplicate_content_enable_custom_taxonomies'] = isset($input['duplicate_content_enable_custom_taxonomies']) ? '1' : '';

        if (isset($input['duplicate_content_permissions']) && is_array($input['duplicate_content_permissions'])) {
            $valid_roles           = array_keys(wp_roles()->get_names());
            $sanitized_permissions = [];

            foreach ($input['duplicate_content_permissions'] as $role) {
                $role = sanitize_text_field($role);
                if (in_array($role, $valid_roles, true)) {
                    $sanitized_permissions[] = $role;
                }
            }

            $sanitized_input['duplicate_content_permissions'] = ! empty($sanitized_permissions) ? $sanitized_permissions : ['administrator'];
        } else {
            $sanitized_input['duplicate_content_permissions'] = ['administrator'];
        }

        return $sanitized_input;
    }

    public function generalSectionCallback()
    {
        echo '<p>' . esc_html__('Configure how Duplicate Content behaves when duplicating content.', 'duplicate-content') . '</p>';
    }

    public function statusFieldCallback()
    {
        $options        = get_option($this->option_name, []);
        $current_status = isset($options['duplicate_content_copy_status']) ? $options['duplicate_content_copy_status'] : 'draft';
        ?>
        <select
            name="<?php echo esc_attr($this->option_name); ?>[duplicate_content_copy_status]"
            class="wpdc-select"
            id="duplicate_content_copy_status"
        >
            <option value="draft" <?php selected($current_status, 'draft'); ?>>
                <?php esc_html_e('Draft', 'duplicate-content'); ?></option>
            <option value="pending" <?php selected($current_status, 'pending'); ?>>
                <?php esc_html_e('Pending Review', 'duplicate-content'); ?>
            </option>
            <option value="private" <?php selected($current_status, 'private'); ?>>
                <?php esc_html_e('Private', 'duplicate-content'); ?></option>
            <option value="publish"
                <?php selected($current_status, 'publish'); ?>>
                <?php esc_html_e('Publish', 'duplicate-content'); ?>
            </option>
            <option value="same" <?php selected($current_status, 'same'); ?>>
                <?php esc_html_e('Same as original', 'duplicate-content'); ?>
            </option>
        </select>
        <p class="description">
            <?php esc_html_e('Choose the default status for duplicated posts and pages.', 'duplicate-content'); ?></p>
        <?php
    }

    public function titleFieldCallback()
    {
        $options       = get_option($this->option_name, []);
        $current_title = isset($options['duplicate_content_copy_title']) ? $options['duplicate_content_copy_title'] : 'Copy of ';
        ?>
        <input type="text"
               name="<?php echo esc_attr($this->option_name); ?>[duplicate_content_copy_title]"
               id="duplicate_content_copy_title"
               class="wpdc-input regular-text"
               value="<?php echo esc_attr($current_title); ?>"
        />
        <p class="description">
            <?php esc_html_e('Text to add before the original title. A space will be automatically added.', 'duplicate-content'); ?>
        </p>
        <?php
    }

    public function suffixFieldCallback()
    {
        $options        = get_option($this->option_name, []);
        $current_suffix = isset($options['duplicate_content_copy_suffix']) ? $options['duplicate_content_copy_suffix'] : '';
        ?>
        <input type="text"
               name="<?php echo esc_attr($this->option_name); ?>[duplicate_content_copy_suffix]"
               id="duplicate_content_copy_suffix"
               class="wpdc-input regular-text"
               value="<?php echo esc_attr($current_suffix); ?>"
        />
        <p class="description">
            <?php esc_html_e('Text to add after the original title. A space will be automatically added.', 'duplicate-content'); ?>
        </p>
        <?php
    }

    public function copyOptionsFieldCallback()
    {
        $options = get_option($this->option_name, []);
        ?>
        <fieldset>
            <legend class="screen-reader-text">
                <?php esc_html_e('Copy Options', 'duplicate-content'); ?>
            </legend>

            <label for="duplicate_content_copy_meta">
                <input type="checkbox"
                       name="<?php echo esc_attr($this->option_name); ?>[duplicate_content_copy_meta]"
                       id="duplicate_content_copy_meta"
                       value="1"
                    <?php checked(isset($options['duplicate_content_copy_meta']) ? $options['duplicate_content_copy_meta'] : '1', '1'); ?>
                />
                <?php esc_html_e('Copy custom fields and meta data', 'duplicate-content'); ?>
            </label><br/>

            <label for="duplicate_content_copy_taxonomies">
                <input type="checkbox"
                       name="<?php echo esc_attr($this->option_name); ?>[duplicate_content_copy_taxonomies]"
                       id="duplicate_content_copy_taxonomies"
                       value="1"
                    <?php checked(isset($options['duplicate_content_copy_taxonomies']) ? $options['duplicate_content_copy_taxonomies'] : '1', '1'); ?>
                />
                <?php esc_html_e('Copy categories, tags, and other taxonomies', 'duplicate-content'); ?>
            </label><br/>

            <label for="duplicate_content_copy_comments">
                <input type="checkbox"
                       name="<?php echo esc_attr($this->option_name); ?>[duplicate_content_copy_comments]"
                       id="duplicate_content_copy_comments"
                       value="1"
                    <?php checked(isset($options['duplicate_content_copy_comments']) ? $options['duplicate_content_copy_comments'] : '', '1'); ?>
                />
                <?php esc_html_e('Copy comments (if applicable)', 'duplicate-content'); ?>
            </label><br/>

            <label for="duplicate_content_copy_attachments">
                <input type="checkbox"
                       name="<?php echo esc_attr($this->option_name); ?>[duplicate_content_copy_attachments]"
                       id="duplicate_content_copy_attachments"
                       value="1"
                    <?php checked(isset($options['duplicate_content_copy_attachments']) ? $options['duplicate_content_copy_attachments'] : '', '1'); ?>
                />
                <?php esc_html_e('Copy attached media files', 'duplicate-content'); ?>
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
                <h4><?php esc_html_e('Post Types', 'duplicate-content'); ?></h4>
                <fieldset>
                    <label for="duplicate_content_enable_posts">
                        <input type="checkbox"
                               name="<?php
                               echo esc_attr($this->option_name); ?>[duplicate_content_enable_posts]"
                               id="duplicate_content_enable_posts"
                               value="1"
                            <?php checked(isset($options['duplicate_content_enable_posts']) ? $options['duplicate_content_enable_posts'] : '1', '1'); ?>
                        />
                        <?php esc_html_e('Enable for Posts', 'duplicate-content'); ?>
                    </label>
                    <br/>

                    <label for="duplicate_content_enable_pages">
                        <input type="checkbox"
                               name="<?php echo esc_attr($this->option_name); ?>[duplicate_content_enable_pages]"
                               id="duplicate_content_enable_pages"
                               value="1"
                            <?php checked(isset($options['duplicate_content_enable_pages']) ? $options['duplicate_content_enable_pages'] : '1', '1'); ?>
                        />
                        <?php esc_html_e('Enable for Pages', 'duplicate-content'); ?>
                    </label>

                    <br/>

                    <label for="duplicate_content_enable_custom_post_types">
                        <input type="checkbox"
                               name="<?php echo esc_attr($this->option_name); ?>[duplicate_content_enable_custom_post_types]"
                               id="duplicate_content_enable_custom_post_types"
                               value="1"
                            <?php checked(isset($options['duplicate_content_enable_custom_post_types']) ? $options['duplicate_content_enable_custom_post_types'] : '1', '1'); ?>
                        />
                        <?php esc_html_e('Enable for Custom Post Types', 'duplicate-content'); ?>
                    </label>
                </fieldset>
            </div>

            <div class="wpdc-settings-section">
                <h4><?php esc_html_e('Taxonomies', 'duplicate-content'); ?></h4>
                <fieldset>
                    <label for="duplicate_content_enable_categories">
                        <input type="checkbox"
                               name="<?php echo esc_attr($this->option_name); ?>[duplicate_content_enable_categories]"
                               id="duplicate_content_enable_categories"
                               value="1"
                            <?php checked(isset($options['duplicate_content_enable_categories']) ? $options['duplicate_content_enable_categories'] : '1', '1'); ?>
                        />
                        <?php esc_html_e('Enable for Categories', 'duplicate-content'); ?>
                    </label>

                    <br/>

                    <label for="duplicate_content_enable_tags">
                        <input type="checkbox"
                               name="<?php echo esc_attr($this->option_name); ?>[duplicate_content_enable_tags]"
                               id="duplicate_content_enable_tags"
                               value="1"
                            <?php checked(isset($options['duplicate_content_enable_tags']) ? $options['duplicate_content_enable_tags'] : '1', '1'); ?>
                        />
                        <?php esc_html_e('Enable for Tags', 'duplicate-content'); ?>
                    </label>

                    <br/>

                    <label for="duplicate_content_enable_custom_taxonomies">
                        <input type="checkbox"
                               name="<?php echo esc_attr($this->option_name); ?>[duplicate_content_enable_custom_taxonomies]"
                               id="duplicate_content_enable_custom_taxonomies"
                               value="1"
                            <?php checked(isset($options['duplicate_content_enable_custom_taxonomies']) ? $options['duplicate_content_enable_custom_taxonomies'] : '1', '1'); ?>
                        />
                        <?php esc_html_e('Enable for Custom Taxonomies', 'duplicate-content'); ?>
                    </label>
                </fieldset>
            </div>
        </div>
        <p class="description"><?php
            esc_html_e(
                'Select which content types should have duplicate functionality enabled. Unchecked items will not show duplicate buttons.', 'duplicate-content');
            ?>
        </p>
        <?php
    }

    public function permissionsFieldCallback()
    {
        $options = get_option($this->option_name, []);

        // Handle backward compatibility - convert old single value to array
        $current_permissions = isset($options['duplicate_content_permissions']) ? $options['duplicate_content_permissions'] : ['administrator'];
        if ( ! is_array($current_permissions)) {
            $current_permissions = [$current_permissions];
        }

        $wp_roles = wp_roles();
        $roles    = $wp_roles->get_names();
        ?>
        <fieldset>
            <legend class="screen-reader-text">
                <?php esc_html_e('User Permissions', 'duplicate-content'); ?>
            </legend>
            <p class="description">
                <?php esc_html_e(
                    'Select which user roles can duplicate content. Users with any of the selected roles will have access to duplicate functionality.', 'duplicate-content');
                ?>
            </p>
            <?php
            foreach ($roles as $role_key => $role_name) {
                $checked = in_array($role_key, $current_permissions, true) ? 'checked' : ''; ?>
                <label for="duplicate_content_role_<?php
                echo esc_attr($role_key); ?>">
                    <input type="checkbox"
                           name="<?php echo esc_attr($this->option_name); ?>[duplicate_content_permissions][]"
                           id="duplicate_content_role_<?php echo esc_attr($role_key); ?>"
                           value="<?php echo esc_attr($role_key); ?>"
                        <?php echo esc_attr($checked); ?>
                    />
                    <?php echo esc_html($role_name); ?>
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
