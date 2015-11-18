<?php
/*
Plugin Name: Custom Fields UI Remover
Description: This plugin removes the default UI for Custom Fields from the WP page/post writing screens
Author: Jason Lemahieu
Version: 0.1
Author URI: 

*/

function cets_custom_fields_ui_remove_fields() {
	remove_meta_box('postcustom', 'post', 'normal');
	remove_meta_box('postcustom', 'page', 'normal');
}
add_action( 'admin_menu', 'cets_custom_fields_ui_remove_fields' );