<?php

class Przystanek_WooCommerce {

    public static function init() {
        add_action( 'woocommerce_thankyou', array( __CLASS__, 'handle_wc_order' ) );
        add_action( 'woocommerce_product_options_general_product_data', array( __CLASS__, 'add_custom_fields' ) );
        add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'save_custom_fields' ) );
    }

    public static function handle_wc_order( $order_id ) {
        $order = wc_get_order( $order_id );

        // Przetwarzanie zamówienia WooCommerce
        if ( $order ) {
            $order_data = $order->get_data();
            $user_id = $order_data['user_id'];
            $total = $order_data['total'];
            $items = $order->get_items();

            foreach ( $items as $item ) {
                $product_id = $item->get_product_id();
                $service_id = get_post_meta( $product_id, '_service_id', true );

                if ( $service_id ) {
                    // Aktualizacja rezerwacji w bazie danych
                    global $wpdb;
                    $wpdb->update(
                        $wpdb->prefix . 'przystanek_rezerwacje',
                        array( 'status' => 'confirmed' ),
                        array( 'service_id' => $service_id, 'user_id' => $user_id ),
                        array( '%s' ),
                        array( '%d', '%d' )
                    );
                }
            }
        }
    }

    public static function add_custom_fields() {
        global $wpdb;
        $services = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}przystanek_rodzaje_wsparcia" );

        echo '<div class="options_group">';

        woocommerce_wp_select( array(
            'id' => '_service_id',
            'label' => __( 'Rodzaj Wsparcia', 'przystanek-na-wsparcie' ),
            'options' => array_reduce($services, function($result, $service) {
                $result[$service->id] = $service->nazwa;
                return $result;
            }, array())
        ));

        echo '</div>';
    }

    public static function save_custom_fields( $post_id ) {
        $service_id = isset( $_POST['_service_id'] ) ? intval( $_POST['_service_id'] ) : '';
        update_post_meta( $post_id, '_service_id', $service_id );
    }
}
