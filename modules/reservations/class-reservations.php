<?php

class PNW_Reservations {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('init', array($this, 'register_post_type'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_box_data'));
        add_shortcode('pnw_reservation_form', array($this, 'reservation_form_shortcode'));
        add_action('init', array($this, 'handle_reservation_form'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_get_available_times', array($this, 'get_available_times'));
        add_action('wp_ajax_nopriv_get_available_times', array($this, 'get_available_times'));
    }

    public function register_post_type() {
        $args = array(
            'public' => true,
            'label'  => 'Rezerwacje',
            'supports' => array('title', 'author'),
            'capability_type' => 'post',
            'capabilities' => array(
                'create_posts' => true, // Zezwól na tworzenie postów
            ),
            'map_meta_cap' => true,
        );
        register_post_type('pnw_reservation', $args);
    }

    public function add_meta_boxes() {
        add_meta_box(
            'pnw_reservation_details',
            'Szczegóły rezerwacji',
            array($this, 'render_meta_box'),
            'pnw_reservation',
            'normal',
            'high'
        );
    }

    public function render_meta_box($post) {
        wp_nonce_field('pnw_reservation_meta_box', 'pnw_reservation_meta_box_nonce');

        $therapist_id = get_post_meta($post->ID, '_pnw_therapist_id', true);
        $client_id = get_post_meta($post->ID, '_pnw_client_id', true);
        $date = get_post_meta($post->ID, '_pnw_date', true);
        $time = get_post_meta($post->ID, '_pnw_time', true);
        $duration = get_post_meta($post->ID, '_pnw_duration', true);
        $status = get_post_meta($post->ID, '_pnw_status', true);

        // Render form fields here
        echo '<p><label for="pnw_therapist_id">Terapeuta: </label>';
        wp_dropdown_users(array(
            'name' => 'pnw_therapist_id',
            'selected' => $therapist_id,
            'role' => 'pnw_therapist'
        ));
        echo '</p>';

        echo '<p><label for="pnw_client_id">Klient: </label>';
        wp_dropdown_users(array(
            'name' => 'pnw_client_id',
            'selected' => $client_id,
            'role' => 'subscriber'
        ));
        echo '</p>';

        echo '<p><label for="pnw_date">Data: </label>';
        echo '<input type="date" id="pnw_date" name="pnw_date" value="' . esc_attr($date) . '"></p>';

        echo '<p><label for="pnw_time">Czas: </label>';
        echo '<input type="time" id="pnw_time" name="pnw_time" value="' . esc_attr($time) . '"></p>';

        echo '<p><label for="pnw_duration">Czas trwania (minuty): </label>';
        echo '<input type="number" id="pnw_duration" name="pnw_duration" value="' . esc_attr($duration) . '"></p>';

        echo '<p><label for="pnw_status">Status: </label>';
        echo '<select id="pnw_status" name="pnw_status">';
        $statuses = array('oczekująca', 'potwierdzona', 'anulowana', 'zakończona');
        foreach ($statuses as $stat) {
            echo '<option value="' . esc_attr($stat) . '" ' . selected($status, $stat, false) . '>' . esc_html($stat) . '</option>';
        }
        echo '</select></p>';
    }

    public function save_meta_box_data($post_id) {
        if (!isset($_POST['pnw_reservation_meta_box_nonce'])) {
            return;
        }
        if (!wp_verify_nonce($_POST['pnw_reservation_meta_box_nonce'], 'pnw_reservation_meta_box')) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = array(
            '_pnw_therapist_id',
            '_pnw_client_id',
            '_pnw_date',
            '_pnw_time',
            '_pnw_duration',
            '_pnw_status'
        );

        foreach ($fields as $field) {
            if (isset($_POST[ltrim($field, '_')])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[ltrim($field, '_')]));
            }
        }
    }

    public function reservation_form_shortcode() {
        ob_start();

        if (isset($_GET['reservation_status'])) {
            if ($_GET['reservation_status'] === 'success') {
                echo '<p class="pnw-success">Twoja rezerwacja została przyjęta. Wkrótce skontaktujemy się z Tobą w celu potwierdzenia.</p>';
            } elseif ($_GET['reservation_status'] === 'error') {
                echo '<p class="pnw-error">Wystąpił błąd podczas przetwarzania Twojej rezerwacji. Prosimy spróbować ponownie lub skontaktować się z nami bezpośrednio.</p>';
            }
        }

        ?>
        <form id="pnw-reservation-form" method="post">
            <?php wp_nonce_field('pnw_reservation', 'pnw_reservation_nonce'); ?>
            <p>
                <label for="pnw_therapist">Wybierz terapeutę:</label>
                <select name="pnw_therapist" id="pnw_therapist" required>
                    <option value="">Wybierz terapeutę</option>
                    <?php
                    $therapists = get_users(array('role' => 'pnw_therapist'));
                    foreach ($therapists as $therapist) {
                        echo '<option value="' . esc_attr($therapist->ID) . '">' . esc_html($therapist->display_name) . '</option>';
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="pnw_date">Data wizyty:</label>
                <input type="text" name="pnw_date" id="pnw_date" required>
            </p>
            <p>
                <label for="pnw_time">Godzina wizyty:</label>
                <select name="pnw_time" id="pnw_time" required>
                    <option value="">Najpierw wybierz datę</option>
                </select>
            </p>
            <p>
                <label for="pnw_duration">Czas trwania (minuty):</label>
                <input type="number" name="pnw_duration" id="pnw_duration" value="45" required>
            </p>
            <p>
                <label for="pnw_name">Twoje imię i nazwisko:</label>
                <input type="text" name="pnw_name" id="pnw_name" required>
            </p>
            <p>
                <label for="pnw_email">Twój email:</label>
                <input type="email" name="pnw_email" id="pnw_email" required>
            </p>
            <p>
                <label for="pnw_phone">Twój numer telefonu:</label>
                <input type="tel" name="pnw_phone" id="pnw_phone" required>
            </p>
            <p>
                <input type="submit" value="Zarezerwuj wizytę">
            </p>
        </form>
        <?php
        return ob_get_clean();
    }

    public function handle_reservation_form() {
        if (isset($_POST['pnw_reservation_nonce']) && wp_verify_nonce($_POST['pnw_reservation_nonce'], 'pnw_reservation')) {
            $therapist_id = intval($_POST['pnw_therapist']);
            $date = sanitize_text_field($_POST['pnw_date']);
            $time = sanitize_text_field($_POST['pnw_time']);
            $duration = intval($_POST['pnw_duration']);
            $name = sanitize_text_field($_POST['pnw_name']);
            $email = sanitize_email($_POST['pnw_email']);
            $phone = sanitize_text_field($_POST['pnw_phone']);

            // Sprawdź, czy klient już istnieje
            $user = get_user_by('email', $email);
            if (!$user) {
                // Jeśli nie, stwórz nowego użytkownika
                $user_id = wp_create_user($email, wp_generate_password(), $email);
                wp_update_user(array('ID' => $user_id, 'display_name' => $name));
                update_user_meta($user_id, 'phone', $phone);
            } else {
                $user_id = $user->ID;
            }

            // Stwórz rezerwację
            $reservation_data = array(
                'therapist_id' => $therapist_id,
                'client_id' => $user_id,
                'client_name' => $name,
                'date' => $date,
                'time' => $time,
                'duration' => $duration
            );

            $reservation_id = $this->create_reservation($reservation_data);

            if ($reservation_id) {
                // Tutaj możemy dodać kod do wysłania powiadomienia email/SMS
                wp_redirect(add_query_arg('reservation_status', 'success', $_SERVER['REQUEST_URI']));
                exit;
            } else {
                wp_redirect(add_query_arg('reservation_status', 'error', $_SERVER['REQUEST_URI']));
                exit;
            }
        }
    }

        public function create_reservation($data) {
        $post_data = array(
            'post_title'    => 'Rezerwacja - ' . $data['client_name'] . ' - ' . $data['date'],
            'post_status'   => 'publish',
            'post_type'     => 'pnw_reservation',
        );

        $post_id = wp_insert_post($post_data);

        if (!is_wp_error($post_id)) {
            update_post_meta($post_id, '_pnw_therapist_id', $data['therapist_id']);
            update_post_meta($post_id, '_pnw_client_id', $data['client_id']);
            update_post_meta($post_id, '_pnw_date', $data['date']);
            update_post_meta($post_id, '_pnw_time', $data['time']);
            update_post_meta($post_id, '_pnw_duration', $data['duration']);
            update_post_meta($post_id, '_pnw_status', 'oczekująca');

            do_action('pnw_reservation_created', $post_id, $data['client_id']);

            return $post_id;
        }

        return false;
    }

    public function enqueue_scripts() {
        wp_enqueue_style('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
        wp_enqueue_script('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr', array('jquery'), null, true);
        wp_enqueue_script('pnw-reservation', plugin_dir_url(__FILE__) . 'js/reservation.js', array('jquery', 'flatpickr'), $this->version, true);
        wp_localize_script('pnw-reservation', 'pnw_ajax', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    public function get_available_times() {
        $date = sanitize_text_field($_POST['date']);
        $therapist_id = intval($_POST['therapist']);

        // Tutaj dodaj logikę do sprawdzania dostępnych godzin
        // Na razie zwróćmy przykładowe godziny
        $available_times = array('09:00', '10:00', '11:00', '14:00', '15:00');

        $options = '<option value="">Wybierz godzinę</option>';
        foreach ($available_times as $time) {
            $options .= '<option value="' . $time . '">' . $time . '</option>';
        }

        echo $options;
        wp_die();
    }
}
