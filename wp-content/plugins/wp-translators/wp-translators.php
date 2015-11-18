<?php
/*
Plugin Name: WP Translators
Plugin URI: http://www.aleaiactaest.ch/wp-translators
Description: Adds a translator role to the system and lets group users to form translator teams.
Version: 0.0.1
Author: Joel Krebs
Author URI: http://www.aleaiactaest.ch
License: GPL2
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
} // end if

include_once dirname( __FILE__ ) . '/class-wp-translators.php';

if ( class_exists( 'WP_Translators' ) ) {
	register_activation_hook( __FILE__, array( 'WP_Translators', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'WP_Translators', 'deactivate' ) );

    add_action( 'plugins_loaded', array( 'WP_Translators', 'get_instance' ) );
}