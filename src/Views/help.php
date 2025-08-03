<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php require_once plugin_dir_path(dirname(__FILE__)) . 'Views/navbar.php'; ?>

<div class="wrap wpdc-content">
    <p class="wpdc-welcome"><?php _e('Dashboard', 'wp-duplicate'); ?></p>

    <div class="wpdc-help-content">
        <div class="wpdc-help-section">
            <h2><?php _e('Getting Started', 'wp-duplicate'); ?></h2>
            <p><?php _e('WP Duplicate allows you to quickly duplicate posts, pages, categories, tags, and other content types with a single click.', 'wp-duplicate'); ?></p>
        </div>

        <div class="wpdc-help-section">
            <h2><?php _e('Supported Content Types', 'wp-duplicate'); ?></h2>
            <ul>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Posts and Pages', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Custom Post Types', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Categories and Tags', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Custom Taxonomies', 'wp-duplicate'); ?>
                </li>
            </ul>
        </div>

        <div class="wpdc-help-section">
            <h2><?php _e('What Gets Duplicated', 'wp-duplicate'); ?></h2>
            <ul>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Title (with prefix and suffix - spaces automatically added)', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Content and excerpt', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Custom fields and meta data (optional)', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Categories, tags, and taxonomies (optional)', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Featured image', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Comments (optional)', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Attached media files (optional)', 'wp-duplicate'); ?>
                </li>
            </ul>
        </div>

        <div class="wpdc-help-section">
            <h2><?php _e('Settings Configuration', 'wp-duplicate'); ?></h2>
            <p><?php _e('Configure your duplication preferences in the Settings page:', 'wp-duplicate'); ?></p>
            <ul>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Default status for duplicated content', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Title prefix and suffix for duplicated items', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Copy options (meta, taxonomies, comments, attachments)', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('User role permissions for duplication (multiple roles can be selected)', 'wp-duplicate'); ?>
                </li>
            </ul>
        </div>

        <div class="wpdc-help-section">
            <h2><?php _e('Troubleshooting', 'wp-duplicate'); ?></h2>
            <p><?php _e('If you encounter issues:', 'wp-duplicate'); ?></p>
            <ul>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Check your user role permissions', 'wp-duplicate'); ?></li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Ensure the content type is supported', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Verify your WordPress installation is up to date', 'wp-duplicate'); ?></li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt"></span>
                    <?php _e('Check for conflicts with other plugins', 'wp-duplicate'); ?>
                </li>
            </ul>
        </div>

        <div class="wpdc-help-section">
            <h2><?php _e('Support', 'wp-duplicate'); ?></h2>
            <p><?php _e('For additional support or feature requests, please visit our documentation or contact support.', 'wp-duplicate'); ?></p>
        </div>

        <div class="wpdc-help-section">
            <h2><?php _e('Changelog', 'wp-duplicate'); ?></h2>
            <h3>Version 1.0.0</h3>
            <ul>
                <li><?php _e('Initial release', 'wp-duplicate'); ?></li>
                <li><?php _e('Support for posts, pages, and taxonomies', 'wp-duplicate'); ?></li>
                <li><?php _e('Configurable duplication options', 'wp-duplicate'); ?></li>
                <li><?php _e('User permission controls', 'wp-duplicate'); ?></li>
            </ul>
        </div>
    </div>
</div>
