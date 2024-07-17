<?php

class Przystanek_Notifications {

    public static function init() {
        add_action( 'wp_insert_comment', array( __CLASS__, 'send_sms_notification' ), 10, 2 );
    }

    public static function send_sms_notification( $comment_id, $comment_object ) {
        // Kod do wysyłania powiadomień SMS
        $phone = get_comment_meta( $comment_id, 'phone', true );
        $message = 'Twoja rezerwacja została utworzona.';

        // Wstaw swój kod do wysyłania SMS, np. przy użyciu zewnętrznej bramki SMS
        // Example:
        // $api_key = 'YOUR_API_KEY';
        // $api_url = 'https://api.smsprovider.com/send';
        // $response = wp_remote_post( $api_url, array(
        //     'body' => array(
        //         'key' => $api_key,
        //         'to' => $phone,
        //         'message' => $message,
        //     ),
        // ));
    }
}
