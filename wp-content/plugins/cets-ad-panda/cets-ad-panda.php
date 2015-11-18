<?php
/*
Plugin Name: Ad Panda (CETS)
Plugin URI: 
Description: Select an advertisement or image from a list to be included in your sidebar or footer
Author: Jason Lemahieu
Version: 0.1
Author URI: 
*/   

require_once( dirname(__FILE__). '/render.php');
require_once( dirname(__FILE__) . '/ads.php');
 require_once( dirname(__FILE__) . '/widget.php');
 
 //enqueue the preview javascript on the widget screen
 //TODO - if widgets page
 add_action('admin_enqueue_scripts', 'cets_ad_panda_enqueue_widget_scripts');
 function cets_ad_panda_enqueue_widget_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script("ces_ad_panda_preview", plugins_url("/js/preview.js", __FILE__), 'jquery');
 }
 