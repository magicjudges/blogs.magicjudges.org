<?php
/*
	File: cets-plugin-functions.php
	Version: 1.0
	Author: Jason Lemahieu
*/

if (!function_exists('cets_current_page_url')) {
	function cets_current_page_url() {
		 $pageURL = 'http';
		 if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
	}
} // /pluggable

if (!function_exists('embed_rss_cets_get_post_type')) {
	// This utility function determines if something is a page or a post before the admin UI does
	function embed_rss_cets_get_post_type() {
		
		if (is_admin()) {

			//look for post type for new posts/pages/CPTs
			$post_type = "post";  // default
			if ( !isset($_GET['post_type']) ) {
				$post_type = 'post';
			} elseif ( in_array( $_GET['post_type'], get_post_types( array('show_ui' => true ) ) ) ) {
				$post_type = $_GET['post_type'];
				return $post_type;
			}
						
			// look for post ID and set post type for editing
			if ( isset($_GET['post']) )
				$post_id = (int) $_GET['post'];
			elseif ( isset($_POST['post_ID']) )
				$post_id = (int) $_POST['post_ID'];
			else
				$post_id = 0;	
				
			if ( $post_id ) {
			$post = get_post($post_id);
			if ( $post ) {
				$post_type_object = get_post_type_object($post->post_type);
				if ( $post_type_object ) {
					$post_type = $post->post_type;		
				}
			}
			} elseif ( isset($_POST['post_type']) ) {
				$post_type_object = get_post_type_object($_POST['post_type']);
				if ( $post_type_object ) {
					$post_type = $post_type_object->name;
				}
			}
			return $post_type;
		} else {
			//public site.
			global $post;
			return $post->post_type;
		}
	}
} // /pluggable

