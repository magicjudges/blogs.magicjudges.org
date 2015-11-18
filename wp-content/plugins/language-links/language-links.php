<?php
/*
Plugin Name: Language Links
Plugin URI: http://www.aleaiactaest.ch
Description: Adds links to a post directing visitors to external translations.
Version: 1.0
Author: Joel Micha Krebs
Author URI: http://www.aleaiactaest.ch
License: GPL2
*/

include_once dirname( __FILE__ ) . '/class-language-links.php';
include_once dirname( __FILE__ ) . '/functions.php';

if ( class_exists( 'Language_Link' ) ) {
	new Language_Link();
}