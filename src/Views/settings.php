<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1 class="wpdc-settings-title">
        <span class="dashicons dashicons-admin-generic"></span>
        <?php echo esc_html(get_admin_page_title()); ?>
    </h1>

    <form method="post" action="options.php" id="wpdc-settings-form">
        <?php
        settings_fields('wp_duplicate_options');
        do_settings_sections('wp_duplicate_settings');
        ?>
        
        <div class="wpdc-settings-actions">
            <button type="submit" class="button button-primary wpdc-save-button">
                <span class="dashicons dashicons-saved"></span>
                <?php _e('Save Settings', 'wp-duplicate'); ?>
            </button>
        </div>
    </form>
</div>
