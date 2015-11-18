<?php
/*
Plugin Name: Custom Menu
Plugin URI: http://www.evolonix.com/wordpress/plugins/custom-menu
Description: This plugin allows you to display a custom menu that you've created in your theme's "Menus" section in a post or page. Use [menu name="Menu Name"] in your post or page to insert the custom menu. The "name" attribute is required. Since version 1.2, you can now provide a "title" attribute to add a header title to your custom menu (e.g. [menu name="Menu Name" title="My Menu"].)
Version: 1.8
Author: Evolonix
Author URI: http://www.evolonix.com
License: GPL2
Text Domain: custom-menu
*/
?>
<?php
/*  Copyright 2011  Evolonix  (email : info@evolonix.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php

// Install
// NOTE: References shortcode.
require_once(plugin_dir_path(__FILE__) . 'class-custom-menu-install.php');
register_activation_hook(__FILE__, array('Custom_Menu_Install', 'activate'));
register_deactivation_hook(__FILE__, array('Custom_Menu_Install', 'deactivate'));

// I18n
add_action('init', 'custom_menu_init');
// Removed anonymous function for PHP versions below 5.3.
function custom_menu_init() {
	load_plugin_textdomain('custom-menu', false, basename(dirname(__FILE__)) . '/languages');
}

// Admin
require_once(plugin_dir_path(__FILE__) . 'class-custom-menu-admin.php');
$custom_menu_admin = new Custom_Menu_Admin();
add_action('admin_init', array(&$custom_menu_admin, 'init'));

// Front-end (optional)
require_once(plugin_dir_path(__FILE__) . 'class-custom-menu.php');
$custom_menu = new Custom_Menu();
add_action('plugins_loaded', array(&$custom_menu, 'init'));

// Shortcode (optional)
require_once(plugin_dir_path(__FILE__) . 'class-custom-menu-shortcode.php');
$custom_menu_shortcode = new Custom_Menu_Shortcode();
add_shortcode('menu', array(&$custom_menu_shortcode, 'content'));

?>
