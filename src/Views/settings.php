<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'wp-duplicate'));
}
?>

<?php require_once plugin_dir_path(dirname(__FILE__)) . 'Views/navbar.php'; ?>

<div class="wrap wpdc-content">
    <p class="wpdc-welcome"><?php esc_html_e('Settings', 'wp-duplicate'); ?></p>

    <?php
    if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true') {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e('Settings saved successfully!', 'wp-duplicate'); ?></p>
        </div>
        <?php
    }

    // Check for any settings errors
    $settings_errors = get_settings_errors('wp_duplicate_options');
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
            settings_fields('wp_duplicate_options');
            do_settings_sections('wp_duplicate_settings');
            ?>

            <div class="wpdc-settings-actions">
                <button type="submit" class="wpdc-primary-button">
                    <span class="dashicons dashicons-saved"></span>
                    <?php esc_html_e('Save Settings', 'wp-duplicate'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

