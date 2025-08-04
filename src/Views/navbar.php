<?php
if (!defined('ABSPATH')) {
    exit;
}

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading admin page parameter for navigation display only
$current_page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : 'wp-duplicate';

$allowed_pages = [
    'wp-duplicate',
    'wp-duplicate-settings',
    'wp-duplicate-help'
];

if (!in_array($current_page, $allowed_pages, true)) {
    $current_page = 'wp-duplicate';
}

$plugin_url = plugin_dir_url(dirname(dirname(__FILE__)));
$icon_url = $plugin_url . 'assets/images/icon.png';
?>

<div class="wpdc-navbar">
    <div class="wpdc-navbar-container">
        <div class="wpdc-navbar-brand">
            <img src="<?php echo esc_url($icon_url); ?>"
                 alt="<?php esc_attr_e('WP Duplicate', 'wp-duplicate'); ?>"
                 class="wpdc-navbar-logo"
                 style="width: 40px; height: 40px;">
            <span class="wpdc-navbar-title"><?php esc_html_e('WP Duplicate', 'wp-duplicate'); ?></span>
        </div>

        <nav class="wpdc-navbar-menu" role="navigation" aria-label="<?php esc_attr_e('Plugin navigation', 'wp-duplicate'); ?>">
            <a href="<?php echo esc_url(admin_url('admin.php?page=wp-duplicate')); ?>"
               class="wpdc-navbar-link <?php echo $current_page === 'wp-duplicate' ? 'active' : ''; ?>"
                <?php echo $current_page === 'wp-duplicate' ? 'aria-current="page"' : ''; ?>>
                <span class="dashicons dashicons-dashboard" aria-hidden="true"></span>
                <?php esc_html_e('Dashboard', 'wp-duplicate'); ?>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=wp-duplicate-settings')); ?>"
               class="wpdc-navbar-link <?php echo $current_page === 'wp-duplicate-settings' ? 'active' : ''; ?>"
                <?php echo $current_page === 'wp-duplicate-settings' ? 'aria-current="page"' : ''; ?>>
                <span class="dashicons dashicons-admin-generic" aria-hidden="true"></span>
                <?php esc_html_e('Settings', 'wp-duplicate'); ?>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=wp-duplicate-help')); ?>"
               class="wpdc-navbar-link <?php echo $current_page === 'wp-duplicate-help' ? 'active' : ''; ?>"
                <?php echo $current_page === 'wp-duplicate-help' ? 'aria-current="page"' : ''; ?>>
                <span class="dashicons dashicons-editor-help" aria-hidden="true"></span>
                <?php esc_html_e('Help', 'wp-duplicate'); ?>
            </a>
        </nav>
    </div>
</div>
