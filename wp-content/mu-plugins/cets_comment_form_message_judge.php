<?php
/*
Plugin Name: Comment Form Message
Plugin URI: http://www.jasonlemahieu.com
Description: Adds some legal mumbo jumbo underneath comment forms
Author: Jason Lemahieu
Version: 1.0
Author URI: 
*/   
 function cets_comment_form_message() {
	echo "<div class='cets-discussion-policy'><p>You will not be added to any email lists and we will not distribute your personal information.</p></div>";
 }
 
 add_action('comment_form', 'cets_comment_form_message');
