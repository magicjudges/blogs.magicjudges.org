<?php
/*
Plugin Name: Message Board Comments
Plugin URI: http://www.aleaiactaest.ch
Description: Adds the possibility to redirect readers to a specific message board (or any url) for droping comments.
Version: 0.1
Author: Joel Krebs
Author URI: http://www.aleaiactaest.ch
License: GPL2
*/

include_once dirname( __FILE__ ) . '/class-message-board-comments.php';

if ( class_exists( 'Message_Board_Comments' )  ) {
	new Message_Board_Comments();
}