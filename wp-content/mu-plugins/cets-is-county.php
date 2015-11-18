<?php
/*

Plugin Name: Is County (CETS)
Description: A function for easily determining if we're on a County website
Version: 0.1
Author: Jason Lemahieu
Author URI: http://madtownlems.wordpress.com

*/


if ( ! function_exists('is_county') ) {
	function is_county() {

 		if (defined('COUNTIES_SERVER')  && COUNTIES_SERVER === true)  {
   			$counties_server = true;
   		} else {
  			$counties_server = false;
     	}

    	return $counties_server;

	}
}