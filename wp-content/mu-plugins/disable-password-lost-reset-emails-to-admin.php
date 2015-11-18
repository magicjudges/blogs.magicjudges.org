<?php
/*

Plugin Name: Disable Password Lost/Reset Emails to Admin
Description: Stops Lems from getting emails every time someone resets their password.
Version: 0.1
Author: Jason Lemahieu
Author URI: http://madtownlems.wordpress.com

*/

if ( !function_exists( 'wp_password_change_notification' ) ) {
    function wp_password_change_notification() {}
}