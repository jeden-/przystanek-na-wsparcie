<?php

class Przystanek_Na_Wsparcie {

    protected $loader;
    protected $plugin_name;
    protected $version;
    protected $user_management;
    protected $reservations;

    public function __construct() {
        $this->plugin_name = 'przystanek-na-wsparcie';
        $this->version = PNW_VERSION;
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

        $this->user_management = new PNW_User_Management($this->get_plugin_name(), $this->get_version());
        $this->reservations = new PNW_Reservations($this->get_plugin_name(), $this->get_version());
        $this->notifications = new PNW_Notifications($this->get_plugin_name(), $this->get_version());
        $this->notifications = new PNW_Notifications($this->get_plugin_name(), $this->get_version());
        $this->notifications_settings = new PNW_Notifications_Settings();
        $this->calendar = new PNW_Calendar($this->get_plugin_name(), $this->get_version());
    }

    private function load_dependencies() {
        require_once PNW_PLUGIN_DIR . 'modules/user-management/class-user-management.php';
        require_once PNW_PLUGIN_DIR . 'modules/reservations/class-reservations.php';
        require_once PNW_PLUGIN_DIR . 'modules/notifications/class-notifications.php';
        require_once PNW_PLUGIN_DIR . 'modules/notifications/class-notifications.php';
        require_once PNW_PLUGIN_DIR . 'modules/notifications/class-notifications-settings.php';
        require_once PNW_PLUGIN_DIR . 'modules/notifications/interface-sms-gateway.php';
        require_once PNW_PLUGIN_DIR . 'modules/notifications/class-example-sms-gateway.php';
        require_once PNW_PLUGIN_DIR . 'modules/notifications/class-notifications.php';
        require_once PNW_PLUGIN_DIR . 'modules/notifications/class-notifications-settings.php';
        require_once PNW_PLUGIN_DIR . 'modules/calendar/class-calendar.php';
        // Load other dependencies here
    }

    private function set_locale() {
        // Set up internationalization
    }

    private function define_admin_hooks() {
        // Define admin-specific hooks
    }

    private function define_public_hooks() {
        // Define public-facing hooks
    }

    public function run() {
        // Run the loader to execute all of the hooks with WordPress
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_version() {
        return $this->version;
    }
}
