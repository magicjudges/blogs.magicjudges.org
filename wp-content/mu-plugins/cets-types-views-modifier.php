<?php
/*
Plugin Name: CETS Types & Views Modifier
Plugin URI: 
Description: This plugin removes the ability to use Types and Views from non-network Admins
Author: Jason Lemahieu
Version: 0.1
Author URI: 

*/

/*
	Tested through the following Versions:
		Types:  1.0.4
		Views :

	Table of Contents
		- Top level Menu items: cets_types_views_modifier_top_level_menus 
		   (cets_types_views_modifier_remove_top_level_menus)
		-	Marketing metabox from Add/Edit post screens
			(cets_types_views_modifier_remove_marketing_meta_box)
		- Content Template select box from Add/Edit content screens, as well as the Media Button row links for pages/posts
			(cets_types_views_modifier_remove_template_options)
		- Remove T and V from media button row (via CSS for now)
			(cets_types_views_modifier_remove_v_and_t_from_media_row)
		-  Edit View Template' link from the edit_post_link when viewing content displayed with a view template
			(cets_types_views_modifier_remove_edit_post_link_filter)
		- Edit View link on views and widgets	
			(cets_types_views_modifier_removed_edit_view_link_filter)
		- Types icon from the media button row on custom content types
			(cets_types_views_modifier_remove_types_button_from_custom_types) 
		- Hard check on post create screen against creating view and view-templates
			(	)
		- Hard check on post edit screen against editing view and view-template
			(cets_types_views_modifier_prevent_editing_views_and_view_templates)
*/

/*
	This function removes the top level menus for Types and Views from the dashboard main menu
*/
function cets_types_views_modifier_remove_top_level_menus() {
	if (is_super_admin()) {
		return;
	}
	
	remove_menu_page('wpcf');
	remove_menu_page('edit.php?post_type=view');
	
}
add_action('admin_menu', 'cets_types_views_modifier_remove_top_level_menus', 11);

/******
	TYPES
*****/

/*
 This function removes the Marketing metabox from Add/Edit post screens
*/
function cets_types_views_modifier_remove_marketing_meta_box() {
	remove_meta_box( 'wpcf-marketing' , '' , 'side' );
}
add_action( 'admin_head' , 'cets_types_views_modifier_remove_marketing_meta_box', 11);

/*
 This function removes the Types icon from the media button row on custom content types
*/
function cets_types_views_modifier_remove_types_button_from_custom_types() {
	if (is_super_admin()) { return; }
	
	remove_action('admin_enqueue_scripts', 'wpcf_admin_post_add_to_editor_js');
	
}
add_action('admin_enqueue_scripts', 'cets_types_views_modifier_remove_types_button_from_custom_types', 9);


/******
	VIEWS
*****/

/* 
	This function removes the Content Template select box from Add/Edit content screens, as well as the Media Button row links
*/

function cets_types_views_modifier_remove_template_options() {
	
	if (is_super_admin()) { return; }
	
	global $WPV_templates;
	global $WP_Views;
	remove_action('admin_head', array( $WPV_templates, 'post_edit_template_options'));
	
	// Remove the V from the media button row on both pages/posts and custom types
	//remove_action('admin_head', array( $WP_Views, 'post_edit_tinymce'));    // causes PHP Fatal Error
	/*
		Call to a member function add_form_button() on a non
-object in /data/apache/lamp/dev4-ces-lamp/docs/wp-content/plugins/wp-views/inc/wpv-filter-meta-html.php on line 41 
	*/
	
	remove_action('admin_enqueue_scripts', 'wpcf_admin_post_add_to_editor_js'); 
}
add_action('admin_init', 'cets_types_views_modifier_remove_template_options');

// obviously hacky, but the best I got for now :/
function cets_types_views_modifier_remove_v_and_t_from_media_row() {
	if (is_super_admin()) { return; }
	
	echo "<style>ul.editor_addon_wrapper { display: none; }</style>";
}
add_action( 'admin_head', 'cets_types_views_modifier_remove_v_and_t_from_media_row');

/*
  This function removes the 'Edit View Template' link from the edit_post_link when viewing content displayed with a view template
*/

function cets_types_views_modifier_remove_edit_post_link_filter() {
	if (is_super_admin()) { return; }
	
	global $WPV_templates;
	remove_filter('edit_post_link', array($WPV_templates, 'edit_post_link'));
}
add_action( 'init', 'cets_types_views_modifier_remove_edit_post_link_filter', 11 );



function cets_types_views_modifier_removed_edit_view_link_filter() {
	if (is_super_admin()) { return; }

	global $WP_Views;
	remove_filter('edit_post_link', array($WP_Views, 'edit_post_link'));
}
add_action( 'init', 'cets_types_views_modifier_removed_edit_view_link_filter', 11 );


// TODO - remove link from footer of widget (not currently possible - maybe with css?)   .widget a.post_edit_link

if (is_admin()) {
	add_action('admin_init', 'cets_types_views_modifier_prevent_creating_views_and_view_templates');
	add_action('admin_init', 'cets_types_views_modifier_prevent_editing_views_and_view_templates');
}
function cets_types_views_modifier_prevent_creating_views_and_view_templates() {
	if (is_super_admin()) { return; }
	
	if (strpos($_SERVER['REQUEST_URI'], 'post-new.php')) {
		if (isset($_GET['post_type']) && $_GET['post_type']) {
			
			$post_type = $_GET['post_type'];
			$no_edit_types = array("view", "view-template");
			if (in_array($post_type, $no_edit_types)) {
				wp_redirect(admin_url(), 301); exit;
			}
		}
	}
}
function cets_types_views_modifier_prevent_editing_views_and_view_templates() {
	
	if (is_super_admin()) { return; }
		
	if (strpos($_SERVER['REQUEST_URI'], 'post.php')) {
		if (isset($_GET['post']) && $_GET['post']) {
			$post = get_post($_GET['post']);
			$post_type = get_post_type($post);
			$no_edit_types = array("view", "view-template");
			if (in_array($post_type, $no_edit_types)) {
				wp_redirect(admin_url(), 301); exit;
			}
		}
	}
	
}


