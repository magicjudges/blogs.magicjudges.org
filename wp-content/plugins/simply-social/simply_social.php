<?php
/*
Plugin Name: Simply Social (CETS)
Plugin URI: http://blogs.ces.uwex.edu/wordpress
Description: Automatically add selected social media sharing icons to pages and posts.
Author: Jason Lemahieu and Chrissy Dillhunt
Version: 1.8.2
Author URI: 
*/   

/* CHANGELOG
	1.8.3 - reorder icons a lil bit (put Print last cause it's analog)
	1.8.2: hide in widgets via !in_the_loop
	1.8.1: hide on mobile
*/

function simply_social_display_hook($content='') {
		
	if ( wp_is_mobile() ) {
		return $content;
	}

	if ( ! in_the_loop() ) {
		// prevent display in Snippets / Widgets
		return $content;
	}

	$ssout = '';
	
	global $post;

	if (!$post) {
		return $content;
	}

	if (get_post_meta($post->ID,'_simplysocialoff',true)) {
		return $content;
	}
	if (!is_single() && !is_page()) {
		return $content;
	}
	
	$permalink 	= urlencode(get_permalink($post->ID));
	$title 		= str_replace('+','%20',urlencode($post->post_title));
	$og_image = '';
	$using_og_images = false;
	if (function_exists('cets_get_og_images')){
		$using_og_images = true;
	}
	if ($using_og_images == true) {
		$cetsog_images = cets_get_og_images();
		$og_image = $cetsog_images[0];
		$og_image = str_replace('+','%20',urlencode($og_image));
	}

	/*
	$excerpt	= urlencode(strip_tags(strip_shortcodes($post->post_excerpt)));
	if ($excerpt == "") {
		$excerpt = urlencode(substr(strip_tags(strip_shortcodes($post->post_content)),0,250));
	}
	// Clean the excerpt for use with links
	$excerpt	= str_replace('+','%20',$excerpt);
	*/
	
	$sites = simply_social_sites();
	$checked_sites = get_option('simply-social-sites', array());
	
	if ($checked_sites == '') {
		$checked_sites = array();
	}
	
	if (count($checked_sites) == 0) {
		return $content;
	}
	
	$large_icons = get_option('simply-social-large', false);
	if ($large_icons) {
		$size = 32;
	} else {
		$size = 20;
	}
	$sizeclass = " size_$size ";
	$ssout .= "<div class='simply-social-wrapper $sizeclass'>";
	$ssout .= "<div class='simply-social-tagline'>" . stripslashes(get_option('simply-social-tagline', 'Sharing is Caring')) . "</div>";
	$ssout .= "<ul class='sociallinks'>";
	foreach ($sites as $site) {
		if (in_array($site['id'], $checked_sites)) {
			
			$url = $site['url'];
			$url = str_replace('TITLE', $title, $url);
			$url = str_replace('PERMALINK', $permalink, $url);	
			if ($og_image) {
				$url = str_replace('OGIMAGE', $og_image, $url);
			}
			//$url = str_replace('EXCERPT', $excerpt, $url);
			//$url = str_replace('RSS', $rss, $url);
			//$url = str_replace('BLOGNAME', $blogname, $url);
			//$url = str_replace('FEEDLINK', $blogrss, $url);
			$ssout .= "<li class='" . $site['id'] . "'><a rel='nofollow'  target='_blank' href='$url' title='" . $site['name'] . "' alt='" . $site['name'] . " Icon'>&nbsp;</a></li>";
			
		}
	}
	

	$ssout .= "</ul></div>";//simply-social-wrapper
	
	return $content . $ssout;
}
add_filter('the_content', 'simply_social_display_hook');


/**
 * Displays a checkbox that allows users to disable Sociable on a
 * per post or page basis.
 */
function simply_social_meta() {
	global $post;
	$ssoff = false;
	if (get_post_meta($post->ID,'_simplysocialoff',true)) {
		$ssoff = true;
	} 
	?>
	<input type="checkbox" id="simplysocialoff" name="simplysocialoff" <?php checked($ssoff); ?>/> <label for="simplysocialoff"><?php _e('Disable Simply Social <br/>(Check this box to hide sharing icons for this post.)') ?></label>
	<?php
}

/**
 * Add the checkbox defined above to post and page edit screens.
 */
function simply_social_meta_box() {
	add_meta_box('simplysocial','Simply Social','simply_social_meta','page','side');
	add_meta_box('simplysocial','Simply Social','simply_social_meta','post','side');
}
add_action('admin_menu', 'simply_social_meta_box');

/**
 * If the post is inserted, set the appropriate state for the sociable off setting.
 */
function simply_social_insert_post($pID) {
	if (isset($_POST['simplysocialoff'])) {
		//if they checked the box, make sure it's disabled
		add_post_meta($pID, '_simplysocialoff', true, true);
	} else {
		//didn't check the box, so make sure it's NOT off
		delete_post_meta($pID, '_simplysocialoff');
	}
}
add_action('wp_insert_post', 'simply_social_insert_post');

/* Add Simply Social Settings to the Settings Menu
 *
  */
function simply_social_admin_menu() {
	add_options_page('Simply Social', 'Simply Social', 'manage_options', 'simply_social', 'simply_social_submenu');
}
add_action('admin_menu', 'simply_social_admin_menu');



/**
 * Displays the Simply social admin menu, first section (re)stores the settings.
 */
function simply_social_submenu() {
	echo "<div class='wrap'><h2>Simply Social</h2>";
	
	if (function_exists('cets_link_library_get_link')) {
		$help_link = cets_link_library_get_link('PLUGIN_SIMPLY_SOCIAL');
		if ($help_link) {
			echo "<div class='cets-help-box'>";
			echo "<p>For more information about using Simply Social, check out our <a target='_blank' href='{$help_link}'>Help Article</a>.</p>";
			echo "</div>";
		}
	}


	//see if we have to save options first
	if (isset($_REQUEST['simply_social_save']) && $_REQUEST['simply_social_save']) {
		check_admin_referer('simply-social-settings');
		
		//form new site option
		if (isset($_REQUEST['simply_social_site'])) {
			update_option('simply-social-sites', $_REQUEST['simply_social_site']);
		} else {
			update_option('simply-social-sites', '');
		}
		
		
		update_option('simply-social-tagline', wp_filter_nohtml_kses($_REQUEST['simply_social_tagline']));
		
		if (isset($_REQUEST['simply_social_large'])) {
			update_option('simply-social-large', true);
		} else {
			update_option('simply-social-large', false);
		}
		
		if (isset($_REQUEST['simply_social_left'])) {
			update_option('simply-social-left', true);
		} else {
			update_option('simply-social-left', false);
		}
		
		$msg = "Settings Saved!";
		echo "<div class='updated fade'><p>$msg</p></div>";

	
	}  // if (saving) 
	
	$sites = simply_social_sites();
	$checked_sites = get_option('simply-social-sites', array());

	if ($checked_sites == '') {
		$checked_sites = array();
	}
	
	echo '<form action="' . esc_attr( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo "<div id='simply-social-admin-left'>";  //icons and settings
	
	wp_nonce_field('simply-social-settings');
	


	echo '<p>Check which sites you\'d like to feature:</p>';
	echo '<ul id="simply-social-site-list">';
	
	foreach ($sites as $site) {
				
		$this_site = in_array($site['id'], $checked_sites);
		
		$checked = "";
		if ($this_site) {
			$checked = " checked='checked' ";
		}
		
		echo "<li class='li_" . $site['id'] . "'>";

		echo "<input type='checkbox' id='" . $site['id'] . "' " . $checked . "  name='simply_social_site[]' value='" . $site['id'] . "'> &nbsp;" . "<label for=". $site['id']   . "><div class='icon'> </div>" . " &nbsp; " . $site['name'];
		echo "</li>";
	}
	echo '</ul>';
	
	$large_check = "";
	if (get_option('simply-social-large', false)) {
		$large_check = " checked='checked' ";
	}
	echo '<p><input type="checkbox" name="simply_social_large" id="simply_social_large" ' . $large_check . '> Use Large Icons?   ( ' . simply_social_get_icon('facebook',16) . " vs " . simply_social_get_icon('facebook',32) . " )";
	
	$left_check = "";
	if (get_option('simply-social-left', false)) {
		$left_check = " checked='checked' ";
	}
	echo '<p><input type="checkbox" name="simply_social_left" id="simply_social_left" ' . $left_check . '> Left Align Icons? (instead of centered)';
	
	
	
	echo '<p>Tagline: <input type="text" name="simply_social_tagline" id="simply_social_tagline" value="' . esc_attr(stripslashes(get_option('simply-social-tagline', 'Sharing is Caring'))) . '" size="45"></p>';
	
	echo '<input type="submit" name="simply_social_save" value="Save Simply Social Settings" />';
	
	echo "</div><!-- social-admin-left -->";
	
	//screenshot
	echo "<div id='simply-social-shot-wrapper'>";
	echo "<p>Simply Social will display your selected icons as links after pages and posts. <br>Here's an example of how the default settings would look:</p>";
	echo "<img src='" . plugins_url('images',__FILE__). "/screenshot.png'></div>";
	
	
	echo '</form></div><!-- /wrap -->';
	
	
}

function simply_social_get_icon($id, $size='') {
	
	$imagepath = plugins_url('images',__FILE__);
	
	$large_icons = get_option('simply-social-large', false);
	
	if ($size) {
		$imagepath .= "/$size";
	} else {
		if ($large_icons) {
				$imagepath .= "/32";
		} else {
			$imagepath .= "/16";
		}
	}
	$sites = simply_social_sites();
	foreach($sites as $site) {
		if ($site['id'] == $id) {
			return "<img src='$imagepath/$id.png' class='simply-social-icon'>";
		}
	}
}

function simply_social_sites() {

	$sites = array();   
	
	$sites[] = array( "name" => "Facebook",
						"id" => "facebook",
						"url" => "http://www.facebook.com/share.php?u=PERMALINK&amp;t=TITLE",
				);
	$sites[] = array( "name" => "Twitter",
						"id" => "twitter",
						'url' => 'http://twitter.com/home?status=TITLE%20-%20PERMALINK',
				);

	$sites[] = array( "name" => "Google Plus",
						"id" => "googleplus",
						'url' => 'https://plus.google.com/share?url=PERMALINK',
				);

	$sites[] = array( "name" => "Digg",
						"id" => "digg",
						'url' => 'http://digg.com/submit?phase=2&amp;url=PERMALINK&amp;title=TITLE',
				);	
	$sites[] = array( "name" => "Delicious",
						"id" => "delicious",
						'url' => 'http://delicious.com/post?url=PERMALINK&amp;title=TITLE',
				);	
	
	$sites[] = array( "name" => "Pinterest",
						"id" => "pinterest",
						'url' => 'https://pinterest.com/pin/create/button/?url=PERMALINK&amp;description=TITLE&amp;media=OGIMAGE',
				);
				
	$sites[] = array( "name" => "Stumble Upon",
						"id" => "stumbleupon",
						"url" => "http://www.stumbleupon.com/submit?url=PERMALINK&amp;title=TITLE",
				);
	$sites[] = array( "name" => "Reddit",
						"id" => "reddit",
						'url' => 'http://reddit.com/submit?url=PERMALINK&amp;title=TITLE',
				);
	$sites[] = array("name" => "Email",
					"id" => "email",
					"url" => 'mailto:?subject=TITLE&amp;body=PERMALINK',
				);				

	$sites[] = array( "name" => "PDF",
						"id" => "pdf",
						'url' => 'http://www.printfriendly.com/print?url=PERMALINK',
				);	
	$sites[] = array( "name" => "Print",
						"id" => "print",
						'url' => 'http://www.printfriendly.com/print?url=PERMALINK',
				);	

	return $sites;
}

/* CSS */
function simply_social_css_public() {
	
	// CSS and Javascript for HTML HEAD
	?>
	<!-- Simply Social Public CSS -->
	<link rel="stylesheet" href="<?php echo plugins_url('css/simply-social.css',__FILE__)?>" type="text/css" />
	<?php
	if (get_option('simply-social-left', false)) {
		echo "<style>div.simply-social-wrapper {text-align: left;}</style>";
	}
	
}

// Add the CSS to public page's Head section
add_action('wp_head', 'simply_social_css_public');




function simply_social_css_admin() {
	
	// CSS and Javascript for HTML HEAD
	?>
	<!-- Simply Social Admin CSS -->
	<link rel="stylesheet" href="<?php echo plugins_url('css/simply-social-admin.css',__FILE__)?>" type="text/css" />

    <?php 
}

// Add the CSS to public page's Head section
if (strpos($_SERVER['REQUEST_URI'], 'options-general.php') && isset($_GET['page']) && $_GET['page'] == 'simply_social') {
	add_action('admin_head', 'simply_social_css_admin');
}

function simply_social_activate() {
	//set defaults
	
	$default_site_list = array('facebook','twitter','googleplus','digg','delicious','stumbleupon','email');
	if (get_option('simply-social-sites', 'no-exist') == 'no-exist') {
		update_option('simply-social-sites', $default_site_list);
		update_option('simply-social-large', 'true');
		update_option('simply-social-tagline', "Sharing is Caring - Click Below to Share");
	} 
}
register_activation_hook( __FILE__, 'simply_social_activate' );


function simply_social_action_links($links, $file) {
		static $this_plugin;
		if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	
		if ($file == $this_plugin) {
			$settings_link = '<a href="' . admin_url( 'options-general.php?page=simply_social' ) . '">Settings</a>';
			array_unshift($links, $settings_link);
		}

	return $links;
	
}
add_filter( 'plugin_action_links', 'simply_social_action_links', 10, 2);
