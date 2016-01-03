<?php
/*
Plugin Name: Site Status (CETS)
Plugin URI:
Description: This plugin lets you take your site offline temporarily or redirect traffic to a new website.
Author: Jason Lemahieu, Chrissy Dillhunt
Version: 1.8
Author URI:

Copyright 2013 Jason Lemahieu & Chrissy Dillhunt

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


/*
Settings Object Structure
settings[]
settings['setting_method']
	1. nothing - site acts as normal
	2. custom message - display a custom message
		settings['custom_message']
		settings['custom_message_temporary']
		1: yes - 302
		2: no - 301
	3. redirect
		settings['redirect_to']
		settings['redirect_full_path']
			1: yes
			0: no
			settings['redirect_temporary']
			1: yes - 302
			2: no - 301
		settings['drop_url_param']
			1: yes
			0: no

*/

/**
 * Add the Site Status submenu page to Settings menu
 **/
function site_status_admin_submenu() {
	add_options_page('Site Status', 'Site Status', 'manage_options', 'site_status', 'site_status_settings_page');
}
add_action('admin_menu', 'site_status_admin_submenu');

/**
 * Actually output the settings page
 **/
function site_status_settings_page() {

	$settings = get_option('site_status_settings', 'not-set');
	if ($settings == 'not-set') {
		$settings = site_status_settings_defaults();
	}

	//check to see if we should process form data
	if (isset($_REQUEST['site_status_save']) && $_REQUEST['site_status_save']) {
		check_admin_referer('site-status-settings');

		//redirect_to
		$redirect_to = strtolower(trim(wp_filter_nohtml_kses($_REQUEST['site_status_to'])));

		//enforce starting http
		if (strlen($redirect_to) && (substr($redirect_to, 0, 4) != "http")) {
			$redirect_to = "http://" . $redirect_to;
		}

		$own_site = strtolower(site_url());

		//method
		if ($_REQUEST['site_status_method'] == 3 && strlen($_REQUEST['site_status_to']) == 0) {
			echo "<div class='error'>If you want to redirect to a new site, you must enter a redirection URL.  Please confirm your settings below.</div>";
			$settings['method'] = 1;
		} else {
			$settings['method'] = $_REQUEST['site_status_method'];
		}

		// let super admins set this up.
		if (!(strpos($redirect_to, $own_site) === false) && !is_super_admin()) {
			echo "<div class='error'>You can't redirect to your own site. This screen is for redirecting traffic FROM this site to a DIFFERENT site.  Please confirm your settings below.</div>";
			$settings['redirect_to'] = '';
			$settings['method'] = 1;
		} else {
			$settings['redirect_to'] = $redirect_to;
		}

		//message
		$settings['custom_message'] = $_REQUEST['site_status_custom_message'];

		//message-temporary
		if (isset($_REQUEST['site_status_custom_message_temporary'])) {
			$settings['custom_message_temporary'] = 1;
		} else {
			$settings['custom_message_temporary'] = 0;
		}

		//redirect-temporary
		if (isset($_REQUEST['site_status_redirect_temporary'])) {
			$settings['redirect_temporary'] = 1;
		} else {
			$settings['redirect_temporary'] = 0;
		}

		//full path
		if (isset($_REQUEST['site_status_redirect_full_path'])) {
			$settings['redirect_full_path'] = 1;
		} else {
			$settings['redirect_full_path'] = 0;
		}


		//drop_url_param
		if (is_super_admin()) {
			if(isset($_REQUEST['site_status_drop_url_param']) ) {
				$settings['drop_url_param'] = 1;
			} else {
				$settings['drop_url_param'] = 0;
			}
		}
		update_option('site_status_settings', $settings);

		//blog topics integration
		if (function_exists('cets_bt_toggle_blog_exclusion')) {

			if ($settings['method'] == 2 || $settings['method'] == 3) {
				//turn aggregation off
				update_option('cets_notification', 1);

				//turn notification on
				global $blog_id;
				cets_bt_toggle_blog_exclusion($blog_id, 'e');
			}
		}

		$settings = get_option('site_status_settings', array());
		$msg = "Settings Saved!";
		echo "<div class='updated fade'><p>$msg</p></div>";
	}

	echo "<div class='wrap'><h2>Site Status</h2>";

	echo "<div class='site-status-currently'>";
		echo get_site_status_get_admin_message();
	echo "</div><!-- /site-status-currently -->";

	// form setup
	echo '<form action="' . esc_attr( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	wp_nonce_field('site-status-settings');




echo "<br /><strong><p class='site-status-heading'>Select a Site Status:</p></strong><br />";
echo "<div class='options-selection'>";
			echo "<div class='site-status-method-wrapper'>";

				/*echo "<h4>Run your site normally </h4>";*/
				echo "<input type='radio' name='site_status_method' id='site_status_method_1' value='1' " . checked($settings['method'], 1, false) . "> Make no changes (default).  <div class='radio-options'><p class='option-content'>If this plugin is currently in use, selecting this option will save your settings should you decide to redirect or take your site offline again in the future.</p>";

			echo "</div></div><!-- /site-status-method-wrapper -->";

		// Option 2 - Site Offline Message
			echo "<div class='site-status-method-wrapper'>";

					echo "<input type='radio' name='site_status_method' id='site_status_method_2' value='2' " . checked($settings['method'], 2, false) . "> Take your site offline and display a custom message. (This will also remove your site from the network homepage.)<br /><br /> ";
				echo "<div class='option-content'>";



							$editor_settings = array(
									'media_buttons' => true,
									'textarea_name' => 'site_status_custom_message',
									'tinymce' => true,
									'textarea_rows' => 10
									);
							wp_editor(stripslashes($settings['custom_message']), 'site_status_custom_message', $editor_settings);





			// permanent vs temporary
			echo "<p><input type='checkbox' name='site_status_custom_message_temporary' id='site_status_custom_message_temporary'" . checked($settings['custom_message_temporary'], 1, false) . "> This site will be offline <strong>temporarily</strong> (default is <strong>permanently</strong> offline)</p>";

			echo "</div><!-- /option-content -->";


			echo "</div><!-- /site-status-method-wrapper -->";

		// Option 3 - Site Redirect
				echo "<div class='site-status-method-wrapper'>";

				/*echo "<h4>Redirect your website</h4>";*/
				echo "<input type='radio' name='site_status_method' id='site_status_method_3' value='3' " . checked($settings['method'], 3, false) . "> Redirect all traffic on your website to another website.  (This will also remove your site from the network homepage.)";

				echo "<div class='option-content'>";
					//todo
					echo "<p>New Site Location:<input type='text' name='site_status_to' id='site_status_to' value='" . esc_attr(stripslashes($settings['redirect_to'])) . "' size='60'></p>";
					// permanent vs temporary
					echo "<p><input type='checkbox' name='site_status_redirect_temporary' id='site_status_redirect_temporary'" . checked($settings['redirect_temporary'], 1, false) . "> Use a <strong>temporary</strong> redirect (default is a <strong>permanent</strong> redirect)</p>";

					echo "<p><input type='checkbox' name='site_status_redirect_full_path' id='site_status_redirect_full_path'" . checked($settings['redirect_full_path'], 1, false) . ">  Full Path Redirect <strong>(Advanced Users Only)</strong>: Copy the full path and structure when redirecting.  This option is for a very specific type of site transfer and you probably shouldn't check this. (www.myoldsite.com/example will redirect to www.mynewsite.com/example).</p>";

					if (is_super_admin()) {
						echo "<p><input type='checkbox' name='site_status_drop_url_param' id='site_status_drop_url_param'" . checked($settings['drop_url_param'], 1, false) . ">  Drop 'ss_redir' URL Parameter <strong>(Network Admins Only)</strong>: Remove the 'ss_redir' URL parameter from the redirect URL.</p>";
					}

				echo "<br /></div><!-- /option-content -->";
			echo "</div><!-- /site-status-method-wrapper -->";

	/*echo "</td></tr></tbody></table>";*/

	echo '<input type="submit" name="site_status_save" class="button-primary" value="Save Settings" />';

	echo "</form>";
	echo "</div><!-- options-selection -->";
	echo "</div><!-- wrap -->";
}

/**
 * Retrieves an appropriate message describing what the settings should currently do
 */
function get_site_status_get_admin_message() {

	$settings = site_status_get_settings();
	$message = '<p>';

	switch ($settings['method']) {
		//nothing
		case 1:
			return "Your website is not currently offline or redirecting.";
			break;

		//offline message
		case 2:
			if ($settings['custom_message_temporary'] == 1) {
				$duration = 'temporary';
			} else {
				$duration = 'permanent';
			}
			$message = "Your website is currently displaying a " . $duration . " offline message instead of its normal content to regular visitors.";
			break;

		//redirect
		case 3:
			//todo
			if ($settings['redirect_temporary'] == 1) {
				$duration = 'temporary';
			} else {
				$duration = 'permanent';
			}

			if (isset($_GET['page']) && $_GET['page'] == 'site_status') {
				//admin side
				return "Your site is using a " . $duration . " redirect for its normal visitors.";
			} else {
				//public side
				$new_path = site_status_get_redirect_path();
				$redirect_link = "<a class='site-status-redirect-preview-link' href='" . $new_path . "'>" . $new_path . "</a>";
				$message = "Your site is using a " . $duration . " redirect for its normal visitors.  They are being automatically redirected to: " . $redirect_link;

			}

			break;
	} //switch

	$message .= "</p>";

	if (current_user_can('manage_options') && !is_admin()) {
		//add change settings link

		$status_page = trailingslashit(site_url()) . "wp-admin/options-general.php?page=site_status";

		$message .= "<p>";
		$message .= "<a class='site-status-change-settings-link' href='" . $status_page . "'>Change These Settings</a>";
		$message .= "</p>";
	}
	return $message;
}

/**
 * Displays an overlaid message for site members when viewing the site.
 */
function site_status_dump_admin_message() {
	global $site_status_admin_message;
	echo "<div id='site-status-admin-message'>" . get_site_status_get_admin_message() . "</div>";
}

/**
 * Process page requests. Do we redirect? where? site offline?
 */
function site_status_do_redirect() {

	$settings = site_status_get_settings();

	if ( current_user_can('edit_posts') ) {
		//site admin or author, dump a message, ignore rest of settings, and let them continue browsing.
		global $site_status_admin_message;
		if ($settings['method'] != 1) {
			add_action('wp_footer', 'site_status_dump_admin_message');
		}
		return;
	}

	switch ($settings['method']) {

		//do nothing
		case 1:
			return;
			break;

		//custom message
		case 2:

			//see if we're already at the site-offline page
			if (isset($_GET['site-offline']) && $_GET['site-offline'] == 1) {
				//display message
				include 'custom_message_header.php';
					echo "<div class='custom-message'>" . wpautop(wptexturize(($settings['custom_message']))) . "</div>";
				include 'custom_message_footer.php';

			} else {
				//send them 'home'/?site-offline=1
				if ($settings['custom_message_temporary'] == 1) {
					$header_code = "302 Moved Temporarily";
				} else {
					$header_code = "301 Moved Permanently";
				}
				$current_site_root = trailingslashit(site_url());
				$new_path = $current_site_root . "?site-offline=1";
				header("HTTP/1.1 " . $header_code);
				header("Location: " . $new_path);
				header("Status: " . $header_code);
			}

			exit();
			break;

		//redirect
		case 3:

			if (isset($_GET['ss_dir']) && $_GET['ss_redir'] == 1) {
				include 'custom_message_header.php';
					echo "<div class='custom-message'>" . "This website is experiencing a problem with redirects and is currently unavailable.  We apologize for the inconvenience." . "</div>";
				include 'custom_message_footer.php';

				exit();
			}

			nocache_headers();
			if ($settings['redirect_temporary'] == 1) {
				$header_code = "302 Moved Temporarily";
			} else {
				$header_code = "301 Moved Permanently";
			}

			$new_path = site_status_get_redirect_path();

			if (isset($settings['drop_url_param']) && $settings['drop_url_param'] == 1) {
				//
			} else {
				$new_path = site_status_add_qp($new_path, 'ss_redir', 1);
			}


			header("HTTP/1.1 " . $header_code);
			header("Location: " . $new_path);
			header("Status: " . $header_code);
			exit();
			break;

	}

}
add_action('template_redirect', 'site_status_do_redirect', 5);  //lower priority to sneak it in before more privacy options on blogs

/**
 * Get full current page path
 */
function site_status_get_full_path() {
	$s = '';
	$s .= $_SERVER['SCRIPT_URI'];
	return $s;
}

/**
 * Calculate redirection path based on settings.
 */
function site_status_get_redirect_path() {
	$settings = site_status_get_settings();

	if ($settings['redirect_full_path'] == 1) {
		//copy path
		$full_request_path = site_status_get_full_path();
		$current_site_root = trailingslashit(site_url());
		$trim_count = strlen($current_site_root);
		$copied_path = substr($full_request_path, $trim_count);
		return $settings['redirect_to'] . $copied_path;
	} else {
		//single page
		return $settings['redirect_to'];
	}
}

/**
 * Add our poor man's attempt at a loop detector to the redirect url
 **/
function site_status_add_qp($url, $name, $value) {
	$loc = strrchr($url, '?');
	if( $loc == FALSE) {
		$url = $url."?".$name."=". rawurlencode($value);
	} else {
		$url = $url."&".$name."=". rawurlencode($value);
	}
	return $url;
}

/**
 * Prefill some options in case of lost or not-yet-set-up data
 */
function site_status_settings_defaults() {
		$settings = array();
		$settings['method'] = 1;
		$settings['permanent'] = 0;
		$settings['custom_message'] = '<p>Our website is not available at the moment.  We apologize for the inconvenience.</p>';
		$settings['custom_message_temporary'] = 0;
		$settings['redirect_to'] = '';
		$settings['redirect_full_path'] = 0;
		$settings['redirect_temporary'] = 0;
		$settings['drop_url_param'] = 0;
	return $settings;
}

function site_status_get_settings() {
	$settings = get_option('site_status_settings', 'oops-i-dont-exist');
	if ($settings == 'oops-i-dont-exist') {
		site_status_settings_activate();  //rerun activation
		$settings = get_option('site_status_settings', array());
	}

	if (!isset($settings['method'])) { $settings['method'] = 1; }
	if (!isset($settings['permanent'])) { $settings['permanent'] = 0; }
	if (!isset($settings['custom_message'])) { $settings['custom_message'] = "<p>Our website is not available at the moment.  We apologize for the inconvenience.</p>"; }
	if (!isset($settings['custom_message_temporary'])) { $settings['custom_message_temporary'] = 0; }
	if (!isset($settings['redirect_to'])) { $settings['redirect_to'] = 0; }
	if (!isset($settings['redirect_full_path'])) { $settings['redirect_full_path'] = ''; }
	if (!isset($settings['redirect_temporary'])) { $settings['redirect_temporary'] = 0; }
	if (!isset($settings['drop_url_param'])) { $settings['drop_url_param'] = 0; }

	return $settings;
}

/**
 * Prefills database with options to prevent warnings, etc. run on acativation.
 */
function site_status_settings_activate() {

	if (get_option('site_status_settings', 'oops-i-dont-exist') == "oops-i-dont-exist") {
		//prefill settings
		$fresh = site_status_settings_defaults();
		update_option('site_status_settings', $fresh);
	}
}
register_activation_hook( __FILE__, 'site_status_settings_activate' );

/**
 * CSS - admin
 */
function site_status_css_admin() {

	// CSS and Javascript for HTML HEAD
	?>
	<!-- Site Status Admin CSS -->
	<link rel="stylesheet" href="<?php echo plugins_url('css/site-status-admin.css',__FILE__)?>" type="text/css" />

    <?php
}



// Add the CSS to public page's Head section
if (
	strpos($_SERVER['REQUEST_URI'], 'options-general.php') &&
	isset($_GET['page']) &&
	$_GET['page'] == 'site_status') {
	add_action( 'admin_head', 'site_status_css_admin' );
} //if on relevant page

/**
 * CSS Public
 */
function site_status_css_public() {

	// CSS and Javascript for HTML HEAD
	?>
	<!-- Simply Social Public CSS -->
	<link rel="stylesheet" href="<?php echo plugins_url('css/site-status.css',__FILE__)?>" type="text/css" />
	<?php
}

// Add the CSS to public page's Head section
add_action('wp_head', 'site_status_css_public');



function cets_site_status_action_links($links, $file) {

	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);

	if ($file == $this_plugin) {

		$settings_link = '<a href="' . admin_url( 'options-general.php?page=site_status' ) . '">Settings</a>';
		array_unshift($links, $settings_link);
	}
	return $links;

}
add_filter('plugin_action_links', 'cets_site_status_action_links', 10, 2);
