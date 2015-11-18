<?php
/*

Plugin Name: Duplicate Post Modifier (CETS)
Description: Modifies the behavior of the Duplicate Post plugin
Version: 0.1
Author: Jason Lemahieu
Author URI: http://madtownlems.wordpress.com

*/

/* Remove Clone Link */

add_filter('page_row_actions', 'duplicate_post_modifier_remove_clone_link',11,2);
add_filter('post_row_actions', 'duplicate_post_modifier_remove_clone_link',11,2);

function duplicate_post_modifier_remove_clone_link($actions, $post) {
	if (isset($actions['clone'])) {
		unset($actions['clone']);
	}
	return $actions;
}

/* Remove Settings Page */
add_action('admin_menu', 'duplicate_post_modifier_remove_menu_page', 99);
function duplicate_post_modifier_remove_menu_page() {
	remove_submenu_page('options-general.php', 'duplicatepost');
}