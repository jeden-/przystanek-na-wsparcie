<?php
/**
 * Plugin Name: Przystanek Na Wsparcie
 * Plugin URI: https://przystaneknawsparcie.pl/
 * Description: System zarządzania dla firmy Przystanek Na Wsparcie
 * Version: 1.0.0
 * Author: Twoje Imię
 * Author URI: https://przystaneknawsparcie.pl/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: przystanek-na-wsparcie
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Define plugin constants
define( 'PNW_VERSION', '1.0.0' );
define( 'PNW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PNW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include the main Przystanek Na Wsparcie class.
require_once PNW_PLUGIN_DIR . 'includes/class-przystanek-na-wsparcie.php';

// Include and initialize the help class
require_once PNW_PLUGIN_DIR . 'includes/class-help.php';
$plugin_help = new PNW_Help();

// Include and initialize the FAQ class
require_once PNW_PLUGIN_DIR . 'includes/class-faq.php';
$plugin_faq = new PNW_FAQ();

/**
 * Begins execution of the plugin.
 */
function run_przystanek_na_wsparcie() {
    $plugin = new Przystanek_Na_Wsparcie();
    $plugin->run();
}
run_przystanek_na_wsparcie();
