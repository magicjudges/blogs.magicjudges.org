<?php

/**
 * @title TinyMCE V3 Button Integration (for Wp2.5)
 * @author Alex Rabe
 */

class add_cets_EmbedRSS_button {
	
	
	var $pluginname = "cets_EmbedRSS";
	
	function add_cets_EmbedRSS_button()  {
		// Modify the version when tinyMCE plugins are changed.
		add_filter('tiny_mce_version', array (&$this, 'change_tinymce_version') );
		
		
		// init process for button control
		add_action('init', array (&$this, 'addbuttons') );
	}

	function addbuttons() {
	
		// Don't bother doing this stuff if the current user lacks permissions
		if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) return;
		
		// set the default post_type
		$post_type = 'post';
		
		//if it's not a page, get out of here (if not allowed on posts)
		
		if (! $this->cets_is_page() )
			return;
		
		// Add only in Rich Editor mode
		if ( get_user_option('rich_editing') == 'true') {
		 
		// add the button for wp2.5 in a new way
			add_filter("mce_external_plugins", array (&$this, "add_tinymce_plugin" ), 5);
			
			add_filter( 'mce_buttons_' . 3, array(&$this, 'register_button') );
			
			
			//add_filter('mce_buttons', array (&$this, 'register_button' ), 5);
		}
	}
	
// This utility function determines if something is a page or a post before the admin UI does
	function cets_is_page() {
	//look for post type for new posts/pages
		if ( !isset($_GET['post_type']) )
			$post_type = 'post';
		elseif ( in_array( $_GET['post_type'], get_post_types( array('show_ui' => true ) ) ) )
			$post_type = $_GET['post_type'];
			
					
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
		if ($post_type == 'page')
			return true;
		else 
			return false;
	}
	
	// used to insert button in wordpress 2.5x editor
	function register_button($buttons) {
	
		array_push($buttons, "separator", $this->pluginname );
	
		return $buttons;
	}
	
	// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
	function add_tinymce_plugin($plugin_array) {    
	
		$plugin_array[$this->pluginname] =  cets_EmbedRSS_URLPATH.'tinymce/editor_plugin.js';
		
		return $plugin_array;
	}
	
	function change_tinymce_version($version) {
		return ++$version;
	}
	
}

// Call it now
$tinymce_button = new add_cets_EmbedRSS_button ();