<?php
/*
Plugin Name: Open Graph Metadata (CETS)
Plugin URI: 
Description: This plugin adds Open Graph metadata to web page headers to improve the description, title, and images that are displayed when linking to the page on various social networking sites.
Author: Jason Lemahieu & Chrissy Dillhunt
Version: 1.4

*/



function cets_og_get_network_default_image() {

	$img_name = "judge-logo-200-200.png";

	$img = plugins_url('images/' . $img_name, __FILE__);
	return $img;
}



if (is_admin()) {
	require_once( dirname(__FILE__) . '/settings.php');
} else {
	require_once( dirname(__FILE__) . '/head.php');
}

