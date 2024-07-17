<?php

class Przystanek_Database {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'create_tables' ) );
    }

    public static function install() {
        self::create_tables();
        self::insert_test_data(); // Dodajemy testowe dane po instalacji
    }

    public static function uninstall() {
        // Kod do usuwania tabel, jeśli chcesz
    }

    public static function create_tables() {
        global $wpdb;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $charset_collate = $wpdb->get_charset_collate();

        // Tabela rodzaje wsparcia
        $table_name = $wpdb->prefix . 'przystanek_rodzaje_wsparcia';
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nazwa varchar(255) NOT NULL,
            opis text NOT NULL,
            czas_trwania int NOT NULL,
            cena decimal(10, 2) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta( $sql );

        // Tabela zespół
        $table_name = $wpdb->prefix . 'przystanek_zespol';
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            imie varchar(255) NOT NULL,
            nazwisko varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            telefon varchar(20) NOT NULL,
            dostepnosc text NOT NULL,  // JSON string containing availability
            stawka decimal(10, 2) NOT NULL,
            special_dates text NOT NULL,  // JSON string containing special dates
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta( $sql );

        // Tabela gabinety
        $table_name = $wpdb->prefix . 'przystanek_gabinety';
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nazwa varchar(255) NOT NULL,
            opis text NOT NULL,
            dostepnosc text NOT NULL,  // JSON string containing availability
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta( $sql );

        // Tabela rezerwacje
        $table_name = $wpdb->prefix . 'przystanek_rezerwacje';
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nazwa varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            telefon varchar(20) NOT NULL,
            service_id mediumint(9) NOT NULL,
            appointment_date datetime NOT NULL,
            status varchar(20) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta( $sql );

        // Inne tabele (np. klienci)
        // Dodaj odpowiednie zapytania SQL
    }

    public static function insert_test_data() {
        global $wpdb;

        // Przykładowe dane rodzajów wsparcia
        $wpdb->insert(
            $wpdb->prefix . 'przystanek_rodzaje_wsparcia',
            array(
                'nazwa' => 'Podstawowa sesja z psychologiem',
                'opis' => '45 minutowa sesja indywidualna z psychologiem.',
                'czas_trwania' => 45,
                'cena' => 100.00
            )
        );

        $wpdb->insert(
            $wpdb->prefix . 'przystanek_rodzaje_wsparcia',
            array(
                'nazwa' => 'Półkolonie z psychologiem',
                'opis' => '5 dniowe półkolonie z psychologiem, 6 godzin dziennie.',
                'czas_trwania' => 5 * 6 * 60, // 5 dni po 6 godzin (w minutach)
                'cena' => 500.00
            )
        );

        // Przykładowe dane zespołu
        $wpdb->insert(
            $wpdb->prefix . 'przystanek_zespol',
            array(
                'imie' => 'Agnieszka',
                'nazwisko' => 'Kontek',
                'email' => 'agnieszka@example.com',
                'telefon' => '123456789',
                'dostepnosc' => json_encode(array('poniedziałek' => array('start' => '09:00', 'end' => '17:00'), 'wtorek' => array('start' => '09:00', 'end' => '17:00'))),
                'stawka' => 150.00,
                'special_dates' => json_encode(array('2024-08-15', '2024-12-25'))
            )
        );

        $wpdb->insert(
            $wpdb->prefix . 'przystanek_zespol',
            array(
                'imie' => 'Maria',
                'nazwisko' => 'Dunat Ostrowska',
                'email' => 'maria@example.com',
                'telefon' => '987654321',
                'dostepnosc' => json_encode(array('środa' => array('start' => '10:00', 'end' => '18:00'), 'czwartek' => array('start' => '10:00', 'end' => '18:00'))),
                'stawka' => 200.00,
                'special_dates' => json_encode(array('2024-11-01', '2024-12-24'))
            )
        );

        // Przykładowe dane gabinetów
        $wpdb->insert(
            $wpdb->prefix . 'przystanek_gabinety',
            array(
                'nazwa' => 'Hamakowy',
                'opis' => 'Terapie dzieci i młodzieży, konsultacje psychologiczne indywidualne.',
                'dostepnosc' => json_encode(array('poniedziałek' => '09:00-17:00', 'wtorek' => '09:00-17:00'))
            )
        );

        $wpdb->insert(
            $wpdb->prefix . 'przystanek_gabinety',
            array(
                'nazwa' => 'Kanapowy',
                'opis' => 'Mediacje, terapie rodzinne, terapie par, terapie patchworkowe.',
                'dostepnosc' => json_encode(array('środa' => '10:00-18:00', 'czwartek' => '10:00-18:00'))
            )
        );
    }
}
