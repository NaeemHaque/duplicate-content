<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1 class="wpdc-welcome"><?php _e('WP Duplicate Dashboard', 'wp-duplicate'); ?></h1>

    <div class="wpdc-dashboard-stats">
        <div class="wpdc-stat-card">
            <p class="wpdc-stat-number"><?php echo wp_count_posts('post')->publish; ?></p>
            <h3><?php _e('Posts', 'wp-duplicate'); ?></h3>
        </div>

        <div class="wpdc-stat-card">
            <p class="wpdc-stat-number"><?php echo wp_count_posts('page')->publish; ?></p>
            <h3><?php _e('Pages', 'wp-duplicate'); ?></h3>
        </div>

        <div class="wpdc-stat-card">
            <p class="wpdc-stat-number"><?php echo wp_count_terms('category'); ?></p>
            <h3><?php _e('Categories', 'wp-duplicate'); ?></h3>
        </div>

        <div class="wpdc-stat-card">
            <p class="wpdc-stat-number"><?php echo wp_count_terms('post_tag'); ?></p>
            <h3><?php _e('Tags', 'wp-duplicate'); ?></h3>
        </div>
    </div>

    <div class="wpdc-quick-actions">
        <h2><?php _e('Quick Actions', 'wp-duplicate'); ?></h2>
        <p><?php _e('Use the duplicate buttons in your posts, pages, and taxonomy lists to quickly duplicate content.', 'wp-duplicate'); ?></p>

        <div class="wpdc-action-buttons">
            <a href="<?php echo admin_url('edit.php'); ?>" class="button button-primary">
                <?php _e('Manage Posts', 'wp-duplicate'); ?>
            </a>
            <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" class="button button-primary">
                <?php _e('Manage Pages', 'wp-duplicate'); ?>
            </a>
            <a href="<?php echo admin_url('edit-tags.php?taxonomy=category'); ?>" class="button button-primary">
                <?php _e('Manage Categories', 'wp-duplicate'); ?>
            </a>
            <a href="<?php echo admin_url('edit-tags.php?taxonomy=post_tag'); ?>" class="button button-primary">
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
    </div>

    <div class="wpdc-help-link">
        <p><a href="<?php echo admin_url('admin.php?page=wp-duplicate-help'); ?>" class="button button-secondary">
                <?php _e('View Help Documentation', 'wp-duplicate'); ?>
            </a></p>
    </div>
</div>
