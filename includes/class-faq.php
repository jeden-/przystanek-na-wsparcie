<?php

class PNW_FAQ {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_faq_page'));
    }

    public function add_faq_page() {
        add_submenu_page(
            'edit.php?post_type=pnw_reservation',
            __('FAQ', 'przystanek-na-wsparcie'),
            __('FAQ', 'przystanek-na-wsparcie'),
            'manage_options',
            'pnw-faq',
            array($this, 'render_faq_page')
        );
    }

    public function render_faq_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <h2><?php _e('Często Zadawane Pytania', 'przystanek-na-wsparcie'); ?></h2>
            <dl>
                <dt><?php _e('Jak dodać nową rezerwację?', 'przystanek-na-wsparcie'); ?></dt>
                <dd><?php _e('Przejdź do zakładki "Kalendarz" i kliknij na wybraną datę. Wypełnij formularz rezerwacji i kliknij "Zapisz".', 'przystanek-na-wsparcie'); ?></dd>

                <dt><?php _e('Jak edytować istniejącą rezerwację?', 'przystanek-na-wsparcie'); ?></dt>
                <dd><?php _e('W widoku kalendarza kliknij na rezerwację, którą chcesz edytować. Możesz też przeciągnąć rezerwację, aby zmienić jej termin.', 'przystanek-na-wsparcie'); ?></dd>

                <!-- Dodaj więcej pytań i odpowiedzi -->
            </dl>
        </div>
        <?php
    }
}
