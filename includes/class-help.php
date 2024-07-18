<?php

class PNW_Help {
    public function __construct() {
        add_action('admin_head', array($this, 'add_help_tabs'));
    }

    public function add_help_tabs() {
        $screen = get_current_screen();

        if (strpos($screen->id, 'pnw_reservation') !== false) {
            $screen->add_help_tab(array(
                'id'      => 'pnw_help_overview',
                'title'   => __('Przegląd', 'przystanek-na-wsparcie'),
                'content' => '<p>' . __('Ten ekran pozwala na zarządzanie rezerwacjami. Możesz dodawać nowe rezerwacje, edytować istniejące lub usuwać je.', 'przystanek-na-wsparcie') . '</p>',
            ));

            $screen->add_help_tab(array(
                'id'      => 'pnw_help_calendar',
                'title'   => __('Kalendarz', 'przystanek-na-wsparcie'),
                'content' => '<p>' . __('Widok kalendarza pozwala na łatwe przeglądanie i zarządzanie rezerwacjami. Kliknij na datę, aby dodać nową rezerwację, lub przeciągnij istniejącą rezerwację, aby zmienić jej termin.', 'przystanek-na-wsparcie') . '</p>',
            ));

            $screen->set_help_sidebar(
                '<p><strong>' . __('Więcej informacji:', 'przystanek-na-wsparcie') . '</strong></p>' .
                '<p><a href="https://example.com/plugin-docs" target="_blank">' . __('Dokumentacja', 'przystanek-na-wsparcie') . '</a></p>' .
                '<p><a href="https://example.com/plugin-support" target="_blank">' . __('Wsparcie', 'przystanek-na-wsparcie') . '</a></p>'
            );
        }
    }
}
