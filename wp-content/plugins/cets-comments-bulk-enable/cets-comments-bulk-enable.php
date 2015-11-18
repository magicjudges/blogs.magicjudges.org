<?php
/*
Plugin Name: Comments Bulk Enable / Disable (CETS)
Plugin URI: 
Description: Gives users the ability to enable or disable comments and/or pingbacks on all existing pages and/or posts
Version: 1.0
Author: Jason Lemahieu
Author URI: 
License: GPL2
*/

function cets_comments_bulk_enable_menu() {
	
	add_submenu_page('edit-comments.php', 'Bulk Enable/Disable', 'Bulk Enable/Disable', 'manage_options', 'cets_comments_bulk_enable', 'cets_comments_bulk_enable_page');
}
add_action('admin_menu', 'cets_comments_bulk_enable_menu');


function cets_comments_bulk_enable_page() {
	
	global $wpdb;
	
	
	echo "<div class='wrap'>";
	echo "<h2>Bulk Comment Enable/Disable</h2>";
	
	if (isset($_REQUEST['cets_comments_bulk_enable_submitted'])) {
		
		echo "<div id='message' class='updated below-h2'>";
		

		if (isset($_REQUEST['post-comments'])) {
			
			if ($_REQUEST['post-comments']) {
				$status = $_REQUEST['post-comments'];
				$wpdb->query( "UPDATE `$wpdb->posts` SET `comment_status` = '$status' WHERE `post_type` = 'post' AND `post_status` = 'publish'" );
			
				echo "<p><strong>Post comments</strong> have been set to <strong>$status</strong>.</p>";
			}	
			
		}

		if (isset($_REQUEST['post-pingbacks'])) {
			
			if ($_REQUEST['post-pingbacks']) {
				
				$status = $_REQUEST['post-pingbacks'];
				$wpdb->query( "UPDATE `$wpdb->posts` SET `ping_status` = '$status' WHERE `post_type` = 'post' AND `post_status` = 'publish'" );
				echo "<p><strong>Post pingbacks</strong> have been set to <strong>$status</strong>.</p>";
			}
		}
		
			if (isset($_REQUEST['page-comments'])) {
			
			if ($_REQUEST['page-comments']) {
				$status = $_REQUEST['page-comments'];		
				$wpdb->query( "UPDATE `$wpdb->posts` SET `comment_status` = '$status' WHERE `post_type` = 'page' AND `post_status` = 'publish'" );
				echo "<p><strong>Page comments</strong> have been set to <strong>$status</strong>.</p>";
			}	
			
		}

		if (isset($_REQUEST['page-pingbacks'])) {
			
			if ($_REQUEST['page-pingbacks']) {
				
				$status = $_REQUEST['page-pingbacks'];
				$wpdb->query( "UPDATE `$wpdb->posts` SET `ping_status` = '$status' WHERE `post_type` = 'page' AND `post_status` = 'publish'" );
				echo "<p><strong>Page pingbacks</strong> have been set to <strong>$status</strong>.</p>";
			}
		}
				if (current_user_can('activate_plugins')) {
				echo "<p>If you're done enabling and disabling stuff, did you want to deactivate the plugin for now from your <a href='" . admin_url('edit.php?page=Plugins') ."'>Plugins page</a>? Thanks :)</p>";
				}
		echo "</div>"; // message
		
	}
	
	
	$dis_url = admin_url('options-discussion.php');
	
	?>
	<style>
	div.cets-comments-bulk-type-wrapper {margin-top: 20px;}
	</style>
	
	<p>This tool will let you set discussion settings for all published content.</p>
	<p>You can control your default discussion settings for new content on your <a href="<?php echo $dis_url; ?>">Discussion Settings page</a>.</p>
	<?php
	
	echo '<form action="' . esc_attr( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	
	_cets_comments_bulk_enable_form_section('post');
	_cets_comments_bulk_enable_form_section('page');
	
		
	
	submit_button( 'Apply Changes', 'Primary', 'cets_comments_bulk_enable_submitted');
	echo "</form>";
	
	echo "</div>";

}


function _cets_comments_bulk_enable_form_section($post_type) {
	$type = get_post_type_object($post_type);
	
	echo "<div class='cets-comments-bulk-type-wrapper'>";
	echo "<h3>" . $type->labels->name . "</h3>";
	
		echo "Comments:";
		_cets_comments_bulk_enable_select($post_type . "-comments");
		echo "<br/>";
		echo "Pingbacks:";
		_cets_comments_bulk_enable_select($post_type . "-pingbacks");
	
	echo "</div>";
	
	
}

function _cets_comments_bulk_enable_select($name) {
	echo "<select name='$name'>";
		echo "<option value=''>Leave as is&nbsp;</option>";
		echo "<option value='open'>Open </option>";
		echo "<option value='closed'>Close </option>";
	
	echo "</select>";
}

function cets_comments_bulk_enable_links($links, $file) {
	
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	
	if ($file == $this_plugin) {
		
		$settings_link = '<a href="edit-comments.php?page=cets_comments_bulk_enable">Use Now</a>';
		array_unshift($links, $settings_link);
	}
	//var_dump($links);die();
	return $links;
	
}
add_filter('plugin_action_links', 'cets_comments_bulk_enable_links', 10, 2);