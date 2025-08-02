<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1 class="wpdc-settings-title"><?php echo esc_html(get_admin_page_title()); ?></h1>

    <form method="post" action="options.php" id="wpdc-settings-form">
        <?php
        settings_fields('wp_duplicate_options');
        do_settings_sections('wp_duplicate_settings');
        submit_button();
        ?>
    </form>

</div>
