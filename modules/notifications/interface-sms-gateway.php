<?php

interface PNW_SMS_Gateway_Interface {
    public function send_sms($phone_number, $message);
}
