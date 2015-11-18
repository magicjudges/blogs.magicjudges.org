<?php
/*
Plugin Name: cets_fix_float
Plugin URI: n/a
Description: Adds a shortcode to create linebreaks that drop to the line BELOW floating objects.
Version: 1.0
Author: Jason Lemahieu & Seth Mulhall
Author URI: n/a
License: 
*/

function cets_fix_float() {
	return '<br style="clear: both; height: 1px; margin: -1px 0pt 0pt; overflow: hidden;">';
}

add_shortcode('float-fix', 'cets_fix_float');
add_shortcode('fix-float', 'cets_fix_float');