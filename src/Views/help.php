<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php require_once plugin_dir_path(dirname(__FILE__)) . 'Views/navbar.php'; ?>

<div class="wrap wpdc-content">
    <p class="wpdc-welcome"><?php esc_html_e('Help', 'duplicate-content'); ?></p>

    <div class="wpdc-help-content">
        <div class="wpdc-help-section">
            <h2><?php esc_html_e('Getting Started', 'duplicate-content'); ?></h2>
            <p><?php esc_html_e('Duplicate Content allows you to quickly duplicate posts, pages, categories, tags, and other content types with a single click.', 'duplicate-content'); ?></p>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('Supported Content Types', 'duplicate-content'); ?></h2>
            <ul class="wpdc-help-list" role="list">
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Posts and Pages', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Custom Post Types', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Categories and Tags', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Custom Taxonomies', 'duplicate-content'); ?>
                </li>
            </ul>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('What Gets Duplicated', 'duplicate-content'); ?></h2>
            <ul class="wpdc-help-list" role="list">
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Title (with prefix and suffix - spaces automatically added)', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Content and excerpt', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Custom fields and meta data (optional)', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Categories, tags, and taxonomies (optional)', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Featured image', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Comments (optional)', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Attached media files (optional)', 'duplicate-content'); ?>
                </li>
            </ul>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('How to Use Duplicate Content', 'duplicate-content'); ?></h2>
            <ol class="wpdc-help-steps">
                <li><?php esc_html_e('Navigate to any post, page, or taxonomy list in your WordPress admin.', 'duplicate-content'); ?></li>
                <li><?php esc_html_e('Look for the "Duplicate" link in the row actions (hover over the item title).', 'duplicate-content'); ?></li>
                <li><?php esc_html_e('Click the "Duplicate" link to create a copy of the item.', 'duplicate-content'); ?></li>
                <li><?php esc_html_e('The duplicated item will be created with your configured settings and you\'ll be redirected to edit it.', 'duplicate-content'); ?></li>
            </ol>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('Settings Configuration', 'duplicate-content'); ?></h2>
            <p><?php esc_html_e('Configure your duplication preferences in the Settings page:', 'duplicate-content'); ?></p>
            <ul class="wpdc-help-list" role="list">
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Default status for duplicated content', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Title prefix and suffix for duplicated items', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Copy options (meta, taxonomies, comments, attachments)', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Content types to enable duplication for', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('User role permissions for duplication (multiple roles can be selected)', 'duplicate-content'); ?>
                </li>
            </ul>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('Troubleshooting', 'duplicate-content'); ?></h2>
            <p><?php esc_html_e('If you encounter issues:', 'duplicate-content'); ?></p>
            <ul class="wpdc-help-list" role="list">
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Check your user role permissions in the Settings page', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Ensure the content type is enabled in Settings', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Verify you have the necessary permissions to edit the content type', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Check if other plugins might be interfering', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Ensure your WordPress installation meets the minimum requirements', 'duplicate-content'); ?>
                </li>
            </ul>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('System Requirements', 'duplicate-content'); ?></h2>
            <ul class="wpdc-help-list" role="list">
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('WordPress 5.0 or higher', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('PHP 7.4 or higher', 'duplicate-content'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('MySQL 5.6 or higher', 'duplicate-content'); ?>
                </li>
            </ul>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('Support', 'duplicate-content'); ?></h2>
            <p><?php esc_html_e('For additional support, feature requests, or bug reports, please visit the WordPress.org plugin support forum.', 'duplicate-content'); ?></p>

            <div class="wpdc-support-info">
                <p><strong><?php esc_html_e('Note:', 'duplicate-content'); ?></strong> <?php esc_html_e('This plugin is provided free of charge. Support is provided on a best-effort basis through the WordPress.org community forums.', 'duplicate-content'); ?></p>
            </div>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('Changelog', 'duplicate-content'); ?></h2>

            <div class="wpdc-changelog">
                <h3><?php esc_html_e('Version 1.0.0', 'duplicate-content'); ?></h3>
                <ul class="wpdc-changelog-list" role="list">
                    <li><?php esc_html_e('Initial release', 'duplicate-content'); ?></li>
                    <li><?php esc_html_e('Support for posts, pages, and taxonomies', 'duplicate-content'); ?></li>
                    <li><?php esc_html_e('Support for custom post types and taxonomies', 'duplicate-content'); ?></li>
                    <li><?php esc_html_e('Configurable duplication options', 'duplicate-content'); ?></li>
                    <li><?php esc_html_e('User permission controls', 'duplicate-content'); ?></li>
                    <li><?php esc_html_e('Comprehensive settings panel', 'duplicate-content'); ?></li>
                    <li><?php esc_html_e('Security features and proper sanitization', 'duplicate-content'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
