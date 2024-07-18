<?php

class PNW_Activator {

    public static function activate() {
        $user_management = new PNW_User_Management('przystanek-na-wsparcie', PNW_VERSION);
        $user_management->register_user_roles();

        self::create_tables();

        flush_rewrite_rules();
    }

    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix . 'pnw_reservation_meta';

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            reservation_id mediumint(9) NOT NULL,
            meta_key varchar(255) NOT NULL,
            meta_value longtext NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
