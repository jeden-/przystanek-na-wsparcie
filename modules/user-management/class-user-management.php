<?php

class PNW_User_Management {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_action('init', array($this, 'register_user_roles'));
        add_action('show_user_profile', array($this, 'add_custom_user_fields'));
        add_action('edit_user_profile', array($this, 'add_custom_user_fields'));
        add_action('personal_options_update', array($this, 'save_custom_user_fields'));
        add_action('edit_user_profile_update', array($this, 'save_custom_user_fields'));
    }

    public function register_user_roles() {
        add_role('pnw_therapist', 'Terapeuta', array(
            'read' => true,
            'edit_posts' => false,
            'delete_posts' => false,
        ));

        add_role('pnw_receptionist', 'Recepcjonista', array(
            'read' => true,
            'edit_posts' => false,
            'delete_posts' => false,
        ));

        // Klient będzie korzystał z domyślnej roli 'subscriber'
    }

    public function add_custom_user_fields($user) {
        if (in_array('pnw_therapist', $user->roles)) {
            ?>
            <h3>Informacje o terapeucie</h3>
            <table class="form-table">
                <tr>
                    <th><label for="pnw_specialization">Specjalizacja</label></th>
                    <td>
                        <input type="text" name="pnw_specialization" id="pnw_specialization"
                               value="<?php echo esc_attr(get_user_meta($user->ID, 'pnw_specialization', true)); ?>"
                               class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th><label for="pnw_hourly_rate">Stawka godzinowa</label></th>
                    <td>
                        <input type="number" name="pnw_hourly_rate" id="pnw_hourly_rate"
                               value="<?php echo esc_attr(get_user_meta($user->ID, 'pnw_hourly_rate', true)); ?>"
                               class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th><label for="pnw_public_bio">Publiczny opis</label></th>
                    <td>
                        <textarea name="pnw_public_bio" id="pnw_public_bio" rows="5" cols="30">
                            <?php echo esc_textarea(get_user_meta($user->ID, 'pnw_public_bio', true)); ?>
                        </textarea>
                    </td>
                </tr>
            </table>
            <?php
        }
    }

    public function save_custom_user_fields($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }

        update_user_meta($user_id, 'pnw_specialization', sanitize_text_field($_POST['pnw_specialization']));
        update_user_meta($user_id, 'pnw_hourly_rate', floatval($_POST['pnw_hourly_rate']));
        update_user_meta($user_id, 'pnw_public_bio', wp_kses_post($_POST['pnw_public_bio']));
    }
}
