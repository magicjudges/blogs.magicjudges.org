<?php
/*

Plugin Name: Content Blocks (CETS)
Description: Create reusable content blocks that can be re-used in pages in posts or as Widgets
Version: 0.1
Author: Jason Lemahieu
Author URI: http://madtownlems.wordpress.com

*/

// Register Custom Post Type
function custom_post_type() {

	$labels = array(
		'name'                => _x( 'Content Blocks', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'Content Block', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Content Blocks', 'text_domain' ),
		'name_admin_bar'      => __( 'Content Block', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
		'all_items'           => __( 'All Content Blocks', 'text_domain' ),
		'add_new_item'        => __( 'Add Content Block', 'text_domain' ),
		'add_new'             => __( 'Add New', 'text_domain' ),
		'new_item'            => __( 'New Content Block', 'text_domain' ),
		'edit_item'           => __( 'Edit Content Block', 'text_domain' ),
		'update_item'         => __( 'Update Content Block', 'text_domain' ),
		'view_item'           => __( 'View Content Block', 'text_domain' ),
		'search_items'        => __( 'Search Content Blocks', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
	);
	$args = array(
		'label'               => __( 'cets_content_block', 'text_domain' ),
		'description'         => __( 'Reusable block of content to use in Pages, Posts, or as a Widget', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', ),
		'hierarchical'        => false,
		'public'              => false, 
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 40,
		'menu_icon'           => 'dashicons-grid-view',
		'show_in_admin_bar'   => false,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'rewrite'             => false,
		'capability_type'     => 'page',
	);
	register_post_type( 'cets_content_block', $args );

}

// Hook into the 'init' action
add_action( 'init', 'custom_post_type', 0 );

require_once( dirname(__FILE__) . '/widget.php');

if ( is_admin() ) {
	require_once( dirname(__FILE__) . '/functions-admin.php');
	require_once( dirname(__FILE__) . '/thickbox.php');
} else {
	require_once( dirname(__FILE__) . '/shortcode.php');
}