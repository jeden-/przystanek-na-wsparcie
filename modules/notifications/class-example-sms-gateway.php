<?php

class PNW_Example_SMS_Gateway implements PNW_SMS_Gateway_Interface {
    private $api_key;

    public function __construct($api_key) {
        $this->api_key = $api_key;
    }

    public function send_sms($phone_number, $message) {
        // To jest tylko przykład. W rzeczywistości, tutaj byłby kod do wysyłania SMS przez konkretną bramkę.
        error_log("Wysyłanie SMS do $phone_number: $message");

        // Symulacja wysyłki SMS
        $success = rand(0, 1); // Losowo symulujemy sukces lub porażkę

        if ($success) {
            return true;
        } else {
            throw new Exception("Nie udało się wysłać SMS");
        }
    }
}
