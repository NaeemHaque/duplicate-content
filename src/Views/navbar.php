<?php
if (!defined('ABSPATH')) {
    exit;
}

$current_page = $_GET['page'] ?? 'wp-duplicate';
?>
<div class="wpdc-navbar">
    <div class="wpdc-navbar-container">
        <div class="wpdc-navbar-brand">
            <img src="<?php echo plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/images/icon.png'; ?>" alt="WP Duplicate" class="wpdc-navbar-logo" style="width: 40px">
            <span class="wpdc-navbar-title"><?php _e('WP Duplicate', 'wp-duplicate'); ?></span>
        </div>
        
        <div class="wpdc-navbar-menu">
            <a href="<?php echo admin_url('admin.php?page=wp-duplicate'); ?>" class="wpdc-navbar-link <?php echo $current_page === 'wp-duplicate' ? 'active' : ''; ?>">
                <span class="dashicons dashicons-dashboard"></span>
                <?php _e('Dashboard', 'wp-duplicate'); ?>
            </a>
            <a href="<?php echo admin_url('admin.php?page=wp-duplicate-settings'); ?>" class="wpdc-navbar-link <?php echo $current_page === 'wp-duplicate-settings' ? 'active' : ''; ?>">
                <span class="dashicons dashicons-admin-generic"></span>
                <?php _e('Settings', 'wp-duplicate'); ?>
            </a>
            <a href="<?php echo admin_url('admin.php?page=wp-duplicate-help'); ?>" class="wpdc-navbar-link <?php echo $current_page === 'wp-duplicate-help' ? 'active' : ''; ?>">
                <span class="dashicons dashicons-editor-help"></span>
                <?php _e('Help', 'wp-duplicate'); ?>
            </a>
        </div>
    </div>
</div> 
