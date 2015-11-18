<?php
/*

Plugin Name: Super Simple Dashboard (CETS)
Description: This plugin strips most of the components from users' dashboards
Version: 0.1
Author: Jason Lemahieu
Author URI: http://madtownlems.wordpress.com

*/

add_action('admin_menu', 'cets_super_simple_dashboard_admin_menu');

function cets_super_simple_dashboard_admin_menu() {
	remove_meta_box('dashboard_primary', 'dashboard', 'core');
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
	remove_meta_box('dashboard_plugins', 'dashboard', 'core');
	remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
	remove_meta_box('dashboard_secondary', 'dashboard', 'core');
}