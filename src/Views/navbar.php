<?php
if (!defined('ABSPATH')) {
    exit;
}

// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading admin page parameter for navigation display only
$current_page = isset($_GET['page']) ? sanitize_text_field(wp_unslash($_GET['page'])) : 'duplicate-content';

$allowed_pages = [
    'duplicate-content',
    'duplicate-content-settings',
    'duplicate-content-help'
];

if (!in_array($current_page, $allowed_pages, true)) {
    $current_page = 'duplicate-content';
}

$plugin_url = plugin_dir_url(dirname(dirname(__FILE__)));
$icon_url = $plugin_url . 'assets/images/icon.png';
?>

<div class="wpdc-navbar">
    <div class="wpdc-navbar-container">
        <div class="wpdc-navbar-brand">
            <img src="<?php echo esc_url($icon_url); ?>"
                 alt="<?php esc_attr_e('Duplicate Content', 'duplicate-content'); ?>"
                 class="wpdc-navbar-logo"
                 style="width: 40px; height: 40px;">
            <span class="wpdc-navbar-title"><?php esc_html_e('Duplicate Content', 'duplicate-content'); ?></span>
        </div>

        <nav class="wpdc-navbar-menu" role="navigation" aria-label="<?php esc_attr_e('Plugin navigation', 'duplicate-content'); ?>">
            <a href="<?php echo esc_url(admin_url('admin.php?page=duplicate-content')); ?>"
               class="wpdc-navbar-link <?php echo $current_page === 'duplicate-content' ? 'active' : ''; ?>"
                <?php echo $current_page === 'duplicate-content' ? 'aria-current="page"' : ''; ?>>
                <span class="dashicons dashicons-dashboard" aria-hidden="true"></span>
                <?php esc_html_e('Dashboard', 'duplicate-content'); ?>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=duplicate-content-settings')); ?>"
               class="wpdc-navbar-link <?php echo $current_page === 'duplicate-content-settings' ? 'active' : ''; ?>"
                <?php echo $current_page === 'duplicate-content-settings' ? 'aria-current="page"' : ''; ?>>
                <span class="dashicons dashicons-admin-generic" aria-hidden="true"></span>
                <?php esc_html_e('Settings', 'duplicate-content'); ?>
            </a>

            <a href="<?php echo esc_url(admin_url('admin.php?page=duplicate-content-help')); ?>"
               class="wpdc-navbar-link <?php echo $current_page === 'duplicate-content-help' ? 'active' : ''; ?>"
                <?php echo $current_page === 'duplicate-content-help' ? 'aria-current="page"' : ''; ?>>
                <span class="dashicons dashicons-editor-help" aria-hidden="true"></span>
                <?php esc_html_e('Help', 'duplicate-content'); ?>
            </a>
        </nav>
    </div>
</div>
