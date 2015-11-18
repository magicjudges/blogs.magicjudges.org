<?php
/******************************************************************************************************************
 Plugin Name: CETS Widgets Modifier
Plugin URI:
Description: WordPress Multisite plugin to remove certain widgets

Version: 0.2

Author: Jason Lemahieu
******************************************************************************************************************/

//Akismet
function cets_widgets_remove() {
	unregister_widget('Akismet_Widget');
}
add_action('widgets_init', 'cets_widgets_remove', 11);
