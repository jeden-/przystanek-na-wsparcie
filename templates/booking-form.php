<form id="przystanek-booking-form">
    <!-- Pola formularza rezerwacji -->
    <input type="text" name="name" placeholder="Twoje imię" required>
    <input type="email" name="email" placeholder="Twój email" required>
    <input type="text" name="phone" placeholder="Twój telefon" required>

    <!-- Wybór usługi -->
    <select name="service_id" required>
        <?php
        global $wpdb;
        $services = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}przystanek_rodzaje_wsparcia" );
        if ( $services ) {
            foreach ( $services as $service ) {
                echo '<option value="' . esc_attr( $service->id ) . '">' . esc_html( $service->nazwa ) . '</option>';
            }
        } else {
            echo '<option value="">Brak dostępnych usług</option>';
        }
        ?>
    </select>

    <!-- Data rezerwacji -->
    <input type="date" name="appointment_date" required>

    <button type="submit">Zarezerwuj</button>
</form>

<script>
jQuery(document).ready(function($) {
    $('#przystanek-booking-form').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        $.post('<?php echo admin_url('admin-ajax.php?action=create_booking'); ?>', data, function(response) {
            if (response.success) {
                alert('Rezerwacja została utworzona!');
            } else {
                alert('Błąd: ' + response.data.message);
            }
        });
    });
});
</script>
