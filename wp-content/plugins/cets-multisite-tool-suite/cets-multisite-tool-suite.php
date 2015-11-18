<?php
/*
Plugin Name: CETS Multisite Tool Suite [CESuite] (CETS)
Plugin URI:
Description: A collection of tools for managing a network of sites
Author: Jason Lemahieu 
Version: 2.0
Network: true
Author URI: 
*/   

/******* TABLE OF CONTENTS
	Get Options
	Set Options
	Delete Options
	PHP Info
	Network Overview
	Post Sleuth
	(Site Sleuth) (in progress)
	Flush Transients
	Flush Rewrites/Permalinks
	Admin Email List
****/

if ( !is_network_admin() ) {
	return;
}

require_once( dirname(__FILE__) . '/includes/options-get.php');
require_once( dirname(__FILE__) . '/includes/options-set.php');
require_once( dirname(__FILE__) . '/includes/options-delete.php');
require_once( dirname(__FILE__) . '/includes/phpinfo.php');
require_once( dirname(__FILE__) . '/includes/overview.php');
require_once( dirname(__FILE__) . '/includes/find-posts.php');
require_once( dirname(__FILE__) . '/includes/find-sites.php');
require_once( dirname(__FILE__) . '/includes/flush-transients.php');
require_once( dirname(__FILE__) . '/includes/flush-rewrites.php');
require_once( dirname(__FILE__) . '/includes/admin-email-list.php');
require_once( dirname(__FILE__) . '/includes/plugin-swap.php');

// Network Admin Menu
add_action( 'network_admin_menu', 'cesuite_network_admin_menus');

function cesuite_network_admin_menus() {
	add_menu_page('CESuite', 'CESuite', 'manage_network', 'cesuite', 'cesuite_page_main');
	add_submenu_page('cesuite', 'Get Options', 'Get Options', 'manage_network', 'get_options', 'cesuite_page_get_options');
	add_submenu_page('cesuite', 'Set Options', 'Set Options', 'manage_network', 'set_options', 'cesuite_page_set_options');
	add_submenu_page('cesuite', 'Delete Options', 'Delete Options', 'manage_network', 'delete_options', 'cesuite_page_delete_options');
	add_submenu_page('cesuite', 'PHP Info', 'PHP Info', 'manage_network', 'php_info', 'cesuite_page_php_info');
	add_submenu_page('cesuite', 'Network Overview', 'Network Overview', 'manage_network', 'network_overview', 'cesuite_page_network_overview');
	add_submenu_page('cesuite', 'Post Sleuth', 'Post Sleuth', 'manage_network', 'post_sleuth', 'cesuite_page_post_sleuth');
	//add_submenu_page('cesuite', 'Site Sleuth', 'site Sleuth', 'manage_network', 'site_sleuth', 'cesuite_page_site_sleuth');
	add_submenu_page('cesuite', 'Flush Transients', 'Flush Transients', 'manage_network', 'flush_transients', 'cesuite_page_flush_transients');
	add_submenu_page('cesuite', 'Flush Rewrites', 'Flush Rewrites', 'manage_network', 'flush_rewrites', 'cesuite_page_flush_rewrites');


	add_submenu_page('cesuite', 'Admin Email List', 'Admin Email List', 'manage_network', 'admin_email_list', 'cesuite_page_admin_email_list');
	add_submenu_page('cesuite', 'Swap Plugins', 'Swap Plugins', 'manage_network', 'swap_plugins', 'cesuite_page_swap_plugins');

	
	
	}

	
/*** MAIN PAGE ****************************************************************************************************************************************************************** MAIN PAGE *** */
function cesuite_page_main() {
	_cesuite_page_head('CESuite - MultiSite Administration Tool Suite');
		
	?>
	<p>CESuite provides a suite of tools for helping manage a MultiSite network. (You'll find these tools in the main menu to the left.)</p>
	<?php

	
	_cesuite_page_foot();
}






/* Header Styles and Scripts */
function cesuite_styles_scripts() {
	$pstyle = "
		<style>
		#cesuite-phpinfo td.e {background-color: #9999CC; padding: 2px; font-weight: bold;}
		#cesuite-phpinfo td.v {background-color: white; padding: 2px; text-align: center;}
		tr.cesuite-emphasis td {font-weight: bold;}
		</style>
	";
	echo $pstyle;
}
if (is_admin() && (strpos($_SERVER['REQUEST_URI'], 'admin.php'))) {
	add_action('admin_head', 'cesuite_styles_scripts');
}



/* _cesuite_page_head
	Starts a CESuite admin page
*/
function _cesuite_page_head($title='CESuite') {
	
	if (!is_super_admin()) {
		wp_die("These are not the tools you're looking for. *handwave*");
	}
	
	echo "<div class='wrap'>";
	echo "<h2>" . __($title, 'text_domain') . "</h2>";
	
}


/* _cesuite_page_foot
	Ends a CESuite admin page
*/
function _cesuite_page_foot() {
	echo "</div><!-- /wrap -->";
}


function _cesuite_form_start() {
	echo '<form action="' . esc_attr( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	if ( function_exists('wp_nonce_field') ) {wp_nonce_field('cesuite'); }  //todo - verify nonce at some point
}
function _cesuite_form_end() {
	submit_button( 'Go', 'Primary', 'cesuite_submitted');
	echo '</form>';
}

function _cesuite_page_description($text) {
	echo "<div class='cesuite-page-description'><p>" . $text . "</p></div>";
}

function _cesuite_get_blogs() {
	global $wpdb, $current_site;
	$blogs  = $wpdb->get_results("SELECT blog_id, domain, path FROM " . $wpdb->blogs . " WHERE site_id = {$current_site->id} ORDER BY domain ASC");
	return $blogs;
}

function _cesuite_site_link() {
	echo get_bloginfo('name') . "&nbsp;(<a href='" . get_bloginfo('url') . "'>" . get_bloginfo('url') . "</a>)";
}

function _cesuite_edit_options_link() {
	echo "&nbsp;(<a href='" . get_admin_url() . "options.php'>Edit</a>)";
}

function _cesuite_repeat_link($text='Go Again') {
	echo "<p class='cesuite-repeat'><a href='" . esc_attr( $_SERVER['REQUEST_URI'] ) . "'>" . $text . "</a></p>";
}

function _cesuite_input_field($name, $label) {
	echo "<label for='" . $label . "'>" . $label . "</label><input type='text' name='" . $name .  "' value=''>";
}

function _cesuite_input_field_plugin($name) {

	$plugins = get_plugins();

	echo "<select name='{$name}'>";
	
	foreach($plugins as $key => $plugin) {
		echo "<option value='{$key}'>{$plugin[Title]}</option>";
	}

	echo "</select>";
}

function _cesuite_print_option_value($option) {
	if (is_array($option)) {
		echo "<pre>";
		print_r($option);
		echo "</pre>";
		echo "<br/>";
	} else {
		echo $option;
	}
	_cesuite_edit_options_link();
}

