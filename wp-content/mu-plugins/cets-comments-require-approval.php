<?php
/*
Plugin Name: CETS Require Comment Approval
Description: This plugin prevents Site Admins from letting comments be posted without explicit approval
Author: Jason Lemahieu
Version:  0.1
Author URI: 

        Copyright (c) 20012 UW Board of Regents
        CETS Require Comment Approval is released under the GNU General Public License (GPL)
        http://www.gnu.org/licenses/gpl-2.0.txt

*/

// Hook in
if (is_admin() && (strpos($_SERVER['REQUEST_URI'], 'options-discussion.php'))) {
	add_action('init', 'cets_comments_require_approval_head');
	add_action('admin_footer', 'cets_comments_require_approval_foot');
	
	update_option('comment_moderation', 1 );

	// Load jQuery 
	function cets_comments_require_approval_head() {
		wp_enqueue_script('jquery');
		}

	// Modify
	function cets_comments_require_approval_foot() {
?>
<!-- Begin CETS comments require approval -->
<script type="text/javascript">
/* <![CDATA[ */	
	//get the 4th tr inside table.form-table's tbody
	//var beforecommentappearscelldata = jQuery('table.form-table tr:eq(3) > td');
	//var text = beforecommentappearsrow.text();
	
	jQuery('table.form-table tr:eq(3) > td').html("<div class='cets-network-override'>Due to excessive comment spam, all comments require explicit approval.");
	
	var commentmoderationrow = jQuery('table.form-table tr:eq(4)');
	commentmoderationrow.hide();
	
/* ]]> */
</script>
<!-- End CETS comments require approval -->
<?php 
	} 
}