<?php

class PNW_Notifications {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('pnw_reservation_created', array($this, 'send_reservation_confirmation'), 10, 2);
        add_action('pnw_reservation_reminder', array($this, 'send_reservation_reminder'), 10, 2);

        $this->init_sms_gateway();
    }

    private function init_sms_gateway() {
        if (get_option('pnw_sms_enabled')) {
            $api_key = get_option('pnw_sms_api_key');
            $this->sms_gateway = new PNW_Example_SMS_Gateway($api_key);
        }
    }

    public function send_reservation_confirmation($reservation_id, $user_id) {
        $user = get_userdata($user_id);
        $reservation = get_post($reservation_id);

        $to = $user->user_email;
        $subject = get_option('pnw_email_confirmation_subject', 'Potwierdzenie rezerwacji');
        $message = $this->prepare_email_body('pnw_email_confirmation_body', $user, $reservation);

        wp_mail($to, $subject, $message);

        if (get_option('pnw_sms_enabled')) {
            $this->send_sms($user->user_phone, $message);
        }
    }

    public function send_reservation_reminder($reservation_id, $user_id) {
        $user = get_userdata($user_id);
        $reservation = get_post($reservation_id);

        $to = $user->user_email;
        $subject = get_option('pnw_email_reminder_subject', 'Przypomnienie o wizycie');
        $message = $this->prepare_email_body('pnw_email_reminder_body', $user, $reservation);

        wp_mail($to, $subject, $message);

        if (get_option('pnw_sms_enabled')) {
            $this->send_sms($user->user_phone, $message);
        }
    }

    private function prepare_email_body($option_name, $user, $reservation) {
        $template = get_option($option_name, 'Witaj {user_name},

Szczegóły Twojej rezerwacji:
Data: {reservation_date}
Godzina: {reservation_time}

Dziękujemy za skorzystanie z naszych usług.');

        $replacements = array(
            '{user_name}' => $user->display_name,
            '{reservation_date}' => get_post_meta($reservation->ID, '_pnw_date', true),
            '{reservation_time}' => get_post_meta($reservation->ID, '_pnw_time', true)
        );

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    private function send_sms($phone_number, $message) {
        // Tutaj dodaj kod do wysyłania SMS przez wybraną bramkę SMS
        // Na przykład:
        $api_key = get_option('pnw_sms_api_key');
        // Użyj $api_key do autoryzacji i wyślij SMS
        // To jest tylko przykład, musisz dostosować to do konkretnej bramki SMS
    }
}
