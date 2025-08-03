<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php require_once plugin_dir_path(dirname(__FILE__)) . 'Views/navbar.php'; ?>

<div class="wrap wpdc-content">
    <p class="wpdc-welcome"><?php _e('Dashboard', 'wp-duplicate'); ?></p>

    <div class="wpdc-dashboard-stats">
        <div class="wpdc-stat-card">
            <p class="wpdc-stat-number"><?php echo wp_count_posts('post')->publish; ?></p>
            <p class="wpdc-stat-label"><?php _e('Posts', 'wp-duplicate'); ?></p>
        </div>

        <div class="wpdc-stat-card">
            <p class="wpdc-stat-number"><?php echo wp_count_posts('page')->publish; ?></p>
            <p class="wpdc-stat-label"><?php _e('Pages', 'wp-duplicate'); ?></p>
        </div>

        <div class="wpdc-stat-card">
            <p class="wpdc-stat-number"><?php echo wp_count_terms('category'); ?></p>
            <p class="wpdc-stat-label"><?php _e('Categories', 'wp-duplicate'); ?></p>
        </div>

        <div class="wpdc-stat-card">
            <p class="wpdc-stat-number"><?php echo wp_count_terms('post_tag'); ?></p>
            <p class="wpdc-stat-label"><?php _e('Tags', 'wp-duplicate'); ?></>
        </div>
    </div>

    <div class="wpdc-quick-actions">
        <h2><?php _e('Quick Actions', 'wp-duplicate'); ?></h2>
        <p><?php _e('Use the duplicate buttons in your posts, pages, and taxonomy lists to quickly duplicate content.', 'wp-duplicate'); ?></p>

        <div class="wpdc-action-buttons">
            <a href="<?php echo admin_url('edit.php'); ?>" class="wpdc-primary-button">
                <?php _e('Manage Posts', 'wp-duplicate'); ?>
            </a>
            <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" class="wpdc-primary-button">
                <?php _e('Manage Pages', 'wp-duplicate'); ?>
            </a>
            <a href="<?php echo admin_url('edit-tags.php?taxonomy=category'); ?>" class="wpdc-primary-button">
                <?php _e('Manage Categories', 'wp-duplicate'); ?>
            </a>
            <a href="<?php echo admin_url('edit-tags.php?taxonomy=post_tag'); ?>" class="wpdc-primary-button">
                <?php _e('Manage Tags', 'wp-duplicate'); ?>
            </a>
        </div>
    </div>

    <div class="wpdc-how-to">
        <h2><?php _e('How to Use', 'wp-duplicate'); ?></h2>
        <ol>
            <li><?php _e('Navigate to any post, page, or taxonomy list in your WordPress admin.', 'wp-duplicate'); ?></li>
            <li><?php _e('Look for the "Duplicate" link in the row actions.', 'wp-duplicate'); ?></li>
            <li><?php _e('Click the duplicate link to create a copy of the item.', 'wp-duplicate'); ?></li>
            <li><?php _e('The duplicated item will be created with your configured settings.', 'wp-duplicate'); ?></li>
        </ol>
        <p class="wpdc-view-help">
            <a href="<?php
            echo admin_url('admin.php?page=wp-duplicate-help'); ?>" class="wpdc-secondary-button">
                <?php
                _e('View Help Documentation', 'wp-duplicate'); ?>
            </a>
        </p>
    </div>
</div>
