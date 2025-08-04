<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php require_once plugin_dir_path(dirname(__FILE__)) . 'Views/navbar.php'; ?>

<div class="wrap wpdc-content">
    <p class="wpdc-welcome"><?php esc_html_e('Help', 'wp-duplicate'); ?></p>

    <div class="wpdc-help-content">
        <div class="wpdc-help-section">
            <h2><?php esc_html_e('Getting Started', 'wp-duplicate'); ?></h2>
            <p><?php esc_html_e('WP Duplicate allows you to quickly duplicate posts, pages, categories, tags, and other content types with a single click.', 'wp-duplicate'); ?></p>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('Supported Content Types', 'wp-duplicate'); ?></h2>
            <ul class="wpdc-help-list" role="list">
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Posts and Pages', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Custom Post Types', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Categories and Tags', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Custom Taxonomies', 'wp-duplicate'); ?>
                </li>
            </ul>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('What Gets Duplicated', 'wp-duplicate'); ?></h2>
            <ul class="wpdc-help-list" role="list">
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Title (with prefix and suffix - spaces automatically added)', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Content and excerpt', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Custom fields and meta data (optional)', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Categories, tags, and taxonomies (optional)', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Featured image', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Comments (optional)', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Attached media files (optional)', 'wp-duplicate'); ?>
                </li>
            </ul>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('How to Use WP Duplicate', 'wp-duplicate'); ?></h2>
            <ol class="wpdc-help-steps">
                <li><?php esc_html_e('Navigate to any post, page, or taxonomy list in your WordPress admin.', 'wp-duplicate'); ?></li>
                <li><?php esc_html_e('Look for the "Duplicate" link in the row actions (hover over the item title).', 'wp-duplicate'); ?></li>
                <li><?php esc_html_e('Click the "Duplicate" link to create a copy of the item.', 'wp-duplicate'); ?></li>
                <li><?php esc_html_e('The duplicated item will be created with your configured settings and you\'ll be redirected to edit it.', 'wp-duplicate'); ?></li>
            </ol>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('Settings Configuration', 'wp-duplicate'); ?></h2>
            <p><?php esc_html_e('Configure your duplication preferences in the Settings page:', 'wp-duplicate'); ?></p>
            <ul class="wpdc-help-list" role="list">
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Default status for duplicated content', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Title prefix and suffix for duplicated items', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Copy options (meta, taxonomies, comments, attachments)', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Content types to enable duplication for', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('User role permissions for duplication (multiple roles can be selected)', 'wp-duplicate'); ?>
                </li>
            </ul>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('Troubleshooting', 'wp-duplicate'); ?></h2>
            <p><?php esc_html_e('If you encounter issues:', 'wp-duplicate'); ?></p>
            <ul class="wpdc-help-list" role="list">
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Check your user role permissions in the Settings page', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Ensure the content type is enabled in Settings', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Verify you have the necessary permissions to edit the content type', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Check if other plugins might be interfering', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('Ensure your WordPress installation meets the minimum requirements', 'wp-duplicate'); ?>
                </li>
            </ul>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('System Requirements', 'wp-duplicate'); ?></h2>
            <ul class="wpdc-help-list" role="list">
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('WordPress 5.0 or higher', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('PHP 7.4 or higher', 'wp-duplicate'); ?>
                </li>
                <li>
                    <span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
                    <?php esc_html_e('MySQL 5.6 or higher', 'wp-duplicate'); ?>
                </li>
            </ul>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('Support', 'wp-duplicate'); ?></h2>
            <p><?php esc_html_e('For additional support, feature requests, or bug reports, please visit the WordPress.org plugin support forum.', 'wp-duplicate'); ?></p>

            <div class="wpdc-support-info">
                <p><strong><?php esc_html_e('Note:', 'wp-duplicate'); ?></strong> <?php esc_html_e('This plugin is provided free of charge. Support is provided on a best-effort basis through the WordPress.org community forums.', 'wp-duplicate'); ?></p>
            </div>
        </div>

        <div class="wpdc-help-section">
            <h2><?php esc_html_e('Changelog', 'wp-duplicate'); ?></h2>

            <div class="wpdc-changelog">
                <h3><?php esc_html_e('Version 1.0.0', 'wp-duplicate'); ?></h3>
                <ul class="wpdc-changelog-list" role="list">
                    <li><?php esc_html_e('Initial release', 'wp-duplicate'); ?></li>
                    <li><?php esc_html_e('Support for posts, pages, and taxonomies', 'wp-duplicate'); ?></li>
                    <li><?php esc_html_e('Support for custom post types and taxonomies', 'wp-duplicate'); ?></li>
                    <li><?php esc_html_e('Configurable duplication options', 'wp-duplicate'); ?></li>
                    <li><?php esc_html_e('User permission controls', 'wp-duplicate'); ?></li>
                    <li><?php esc_html_e('Comprehensive settings panel', 'wp-duplicate'); ?></li>
                    <li><?php esc_html_e('Security features and proper sanitization', 'wp-duplicate'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
