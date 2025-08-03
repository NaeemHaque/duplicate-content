<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php require_once plugin_dir_path(dirname(__FILE__)) . 'Views/navbar.php'; ?>

<div class="wrap wpdc-content">
    <p class="wpdc-welcome"><?php _e('Settings', 'wp-duplicate'); ?></p>


    <div class="wpdc-settings-container">
        <form method="post" action="options.php" id="wpdc-settings-form">
            <?php
            settings_fields('wp_duplicate_options');
            do_settings_sections('wp_duplicate_settings');
            ?>

            <div class="wpdc-settings-actions">
                <button type="submit" class="wpdc-primary-button">
                    <span class="dashicons dashicons-saved"></span>
                    <?php _e('Save Settings', 'wp-duplicate'); ?>
                </button>
            </div>
        </form>
    </div>
</div>
