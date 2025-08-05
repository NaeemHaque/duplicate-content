<?php

if (!defined('ABSPATH')) {
    exit;
}

// Get counts safely with error handling
$post_count = wp_count_posts('post');
$post_count = isset($post_count->publish) ? absint($post_count->publish) : 0;

$page_count = wp_count_posts('page');
$page_count = isset($page_count->publish) ? absint($page_count->publish) : 0;

$category_count = wp_count_terms('category');
$category_count = is_wp_error($category_count) ? 0 : absint($category_count);

$tag_count = wp_count_terms('post_tag');
$tag_count = is_wp_error($tag_count) ? 0 : absint($tag_count);
?>

<?php require_once plugin_dir_path(dirname(__FILE__)) . 'Views/navbar.php'; ?>

<div class="wrap wpdc-content">
    <p class="wpdc-welcome"><?php esc_html_e('Dashboard', 'duplicate-content'); ?></p>

    <div class="wpdc-dashboard-stats">
        <div class="wpdc-stat-card">
            <p class="wpdc-stat-number"><?php echo esc_html($post_count); ?></p>
            <p class="wpdc-stat-label"><?php esc_html_e('Posts', 'duplicate-content'); ?></p>
        </div>

        <div class="wpdc-stat-card">
            <p class="wpdc-stat-number"><?php echo esc_html($page_count); ?></p>
            <p class="wpdc-stat-label"><?php esc_html_e('Pages', 'duplicate-content'); ?></p>
        </div>

        <div class="wpdc-stat-card">
            <p class="wpdc-stat-number"><?php echo esc_html($category_count); ?></p>
            <p class="wpdc-stat-label"><?php esc_html_e('Categories', 'duplicate-content'); ?></p>
        </div>

        <div class="wpdc-stat-card">
            <p class="wpdc-stat-number"><?php echo esc_html($tag_count); ?></p>
            <p class="wpdc-stat-label"><?php esc_html_e('Tags', 'duplicate-content'); ?></p>
        </div>
    </div>

    <div class="wpdc-quick-actions">
        <h2><?php esc_html_e('Quick Actions', 'duplicate-content'); ?></h2>
        <p><?php esc_html_e('Use the duplicate buttons in your posts, pages, and taxonomy lists to quickly duplicate content.', 'duplicate-content'); ?></p>

        <div class="wpdc-action-buttons">
            <a href="<?php echo esc_url(admin_url('edit.php')); ?>" class="wpdc-primary-button">
                <?php esc_html_e('Manage Posts', 'duplicate-content'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('edit.php?post_type=page')); ?>" class="wpdc-primary-button">
                <?php esc_html_e('Manage Pages', 'duplicate-content'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy=category')); ?>" class="wpdc-primary-button">
                <?php esc_html_e('Manage Categories', 'duplicate-content'); ?>
            </a>
            <a href="<?php echo esc_url(admin_url('edit-tags.php?taxonomy=post_tag')); ?>" class="wpdc-primary-button">
                <?php esc_html_e('Manage Tags', 'duplicate-content'); ?>
            </a>
        </div>
    </div>

    <div class="wpdc-how-to">
        <h2><?php esc_html_e('How to Use', 'duplicate-content'); ?></h2>
        <ol>
            <li><?php esc_html_e('Navigate to any post, page, or taxonomy list in your WordPress admin.', 'duplicate-content'); ?></li>
            <li><?php esc_html_e('Look for the "Duplicate" link in the row actions.', 'duplicate-content'); ?></li>
            <li><?php esc_html_e('Click the duplicate link to create a copy of the item.', 'duplicate-content'); ?></li>
            <li><?php esc_html_e('The duplicated item will be created with your configured settings.', 'duplicate-content'); ?></li>
        </ol>
        <p class="wpdc-view-help">
            <a href="<?php echo esc_url(admin_url('admin.php?page=duplicate-content-help')); ?>" class="wpdc-secondary-button">
                <?php esc_html_e('View Help Documentation', 'duplicate-content'); ?>
            </a>
        </p>
    </div>
</div>
