<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!current_user_can('manage_options')) {
    wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'duplicate-content'));
}
?>

<?php require_once plugin_dir_path(dirname(__FILE__)) . 'Views/navbar.php'; ?>

<div class="wrap wpdc-content">
    <p class="wpdc-welcome"><?php esc_html_e('Settings', 'duplicate-content'); ?></p>

    <?php
    // Check for any settings errors
    $settings_errors = get_settings_errors('duplicate_content_options');
    if (!empty($settings_errors)) {
        foreach ($settings_errors as $error) {
            $error_class = $error['type'] === 'error' ? 'notice-error' : 'notice-warning';
            ?>
            <div class="notice <?php echo esc_attr($error_class); ?> is-dismissible">
                <p><?php echo esc_html($error['message']); ?></p>
            </div>
            <?php
        }
    }
    ?>

    <div class="wpdc-settings-container">
        <form method="post" action="options.php" id="wpdc-settings-form">
            <?php
            settings_fields('duplicate_content_options');
            do_settings_sections('duplicate_content_settings');
            ?>

            <div class="wpdc-settings-actions">
                <button type="submit" class="wpdc-primary-button">
                    <span class="dashicons dashicons-saved"></span>
                    <?php esc_html_e('Save Settings', 'duplicate-content'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

