<?php

class PNW_Notifications_Settings {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_settings_page() {
        add_submenu_page(
            'edit.php?post_type=pnw_reservation',
            'Ustawienia powiadomień',
            'Ustawienia powiadomień',
            'manage_options',
            'pnw-notifications-settings',
            array($this, 'render_settings_page')
        );
    }

    public function register_settings() {
        register_setting('pnw_notifications_settings', 'pnw_email_confirmation_subject');
        register_setting('pnw_notifications_settings', 'pnw_email_confirmation_body');
        register_setting('pnw_notifications_settings', 'pnw_email_reminder_subject');
        register_setting('pnw_notifications_settings', 'pnw_email_reminder_body');
        register_setting('pnw_notifications_settings', 'pnw_sms_enabled');
        register_setting('pnw_notifications_settings', 'pnw_sms_api_key');
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Ustawienia powiadomień</h1>
            <form method="post" action="options.php">
                <?php settings_fields('pnw_notifications_settings'); ?>
                <?php do_settings_sections('pnw_notifications_settings'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Temat e-maila potwierdzającego</th>
                        <td><input type="text" name="pnw_email_confirmation_subject" value="<?php echo esc_attr(get_option('pnw_email_confirmation_subject')); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Treść e-maila potwierdzającego</th>
                        <td><textarea name="pnw_email_confirmation_body" rows="5" cols="50"><?php echo esc_textarea(get_option('pnw_email_confirmation_body')); ?></textarea></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Temat e-maila przypominającego</th>
                        <td><input type="text" name="pnw_email_reminder_subject" value="<?php echo esc_attr(get_option('pnw_email_reminder_subject')); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Treść e-maila przypominającego</th>
                        <td><textarea name="pnw_email_reminder_body" rows="5" cols="50"><?php echo esc_textarea(get_option('pnw_email_reminder_body')); ?></textarea></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Włącz powiadomienia SMS</th>
                        <td><input type="checkbox" name="pnw_sms_enabled" value="1" <?php checked(1, get_option('pnw_sms_enabled'), true); ?> /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Klucz API do bramki SMS</th>
                        <td><input type="text" name="pnw_sms_api_key" value="<?php echo esc_attr(get_option('pnw_sms_api_key')); ?>" /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
