<?php
/*
Plugin Name: Password Protected Modifier
Plugin URI: 
Description: Adds an aditional setting for displaying a custom message when prompting users to log in
Author: Jason Lemahieu 
Version: 0.5
Author URI: 
*/   

/* For Version 1.5 of Password Protected (http://wordpress.org/plugins/password-protected/) */


/* 
	Add the message to the login
*/
add_filter('password_protected_login_message', 'cets_password_protected_modifier_add_login_message');

function cets_password_protected_modifier_add_login_message($messages) {
		
	$my_message = get_option("password_protected_message", "This site requires you to log in to read.");
	if ($my_message) {
		$my_message = wpautop(wptexturize($my_message));
		$my_message = "<div class='message'>$my_message</div>";
	}
	
	return $messages . $my_message;
}



/* 
	Settings
*/
if (is_admin()) {
	add_action('admin_init', 'cets_password_protected_modifier_register_settings', 11);
	function cets_password_protected_additional_message_field() {
		$message = get_option("password_protected_message", "This site requires you to log in to read.");
		wp_editor($message, 'password_protected_message');
	}

	function cets_password_protected_modifier_register_settings() {
		
		if (!is_admin()) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		if (!is_plugin_active('password-protected/password-protected.php')) {
			return;
		} 
		
		add_settings_section(
				'password_protected_modifier',
				'Password Protected Additional Settings',
				'cets_password_protected_modifier_settings_section',
				'password-protected'
			);
		
		add_settings_field(
				'password_protected_message',
				'Password Protection Message',
				'cets_password_protected_additional_message_field',
				'password-protected',
				'password_protected_modifier'
			);
		register_setting( 'password-protected', 'password_protected_message', 'wp_kses_post' );
	}

	function cets_password_protected_modifier_settings_section() {
		echo '<p>When password protection is enabled, this message will be displayed to users while being prompted to log in.</p>';
	}


	/*
		Save Password
	*/
	function cets_password_protected_modifier_save_pw($newvalue, $oldvalue) {
		
		if ($newvalue != $oldvalue) {
			update_option('password_protected_raw_password', $newvalue);
		}
		return $newvalue;
	}
	add_filter( 'pre_update_option_password_protected_password', 'cets_password_protected_modifier_save_pw', 9, 2 );


	/*
		Show Raw Password to Admins
	*/
	function cets_password_protected_modifier_display_pw() {
		$status = get_option('password_protected_status', 0);
		if ($status == 0) { return; }

		$raw_pw = get_option('password_protected_raw_password', 'shoestoobigevenforanelephant');
		
		if ($raw_pw != 'shoestoobigevenforanelephant') {
			echo "<div class='updated'><p>Pssst... the password is: <strong>" . esc_html($raw_pw) . "</strong></p></div>";
		}
	}
	if (strpos($_SERVER['REQUEST_URI'], 'options-general.php')) {
		if (isset($_GET['page']) && $_GET['page'] == 'password-protected') {
			add_action('admin_notices', 'cets_password_protected_modifier_display_pw');
		}
	}
}