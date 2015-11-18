<?php
/*
 Plugin Name: Google Analytics (CETS)
 Plugin URI: 
 Description: Advanced tracking of your site's visitors. Requires a Google Account and Google Analytics Profile
 Author: Jason Lemahieu
 Version: 0.6
 Author URI: 
 */
 

 class CETS_Google_Analytics {
	
	//called when the object is instantiated
	function CETS_Google_Analytics() {
		$this->__construct();
	}
	
	function __construct() {
		//actions and filters here
		
		//admin menus
		add_action( 'admin_menu', array( &$this, 'my_admin_menus'));
		
		add_action( 'wp_footer', array( &$this, 'footer_scripts'));

		add_filter('plugin_action_links', array( &$this, 'activate_links'), 10, 2);
		
	}
	
	function my_admin_menus() {
		if (defined('COUNTIES_SERVER')  && COUNTIES_SERVER === true) {
			if (is_super_admin()) {
				add_submenu_page('options-general.php', 'Google Analytics', 'Google Analytics', 'manage_options', 'cets_google_analytics', array(&$this, 'settings_page'));
			}
		} else {
			add_submenu_page('options-general.php', 'Google Analytics', 'Google Analytics', 'manage_options', 'cets_google_analytics', array(&$this, 'settings_page'));
			
		}
		
	}
	function settings_page() {
		
		echo "<div class='wrap'>";
		echo "<h2>" . __('Google Analytics Settings', 'text_domain') . "</h2>";
		
		$step = 1;
		if (isset($_REQUEST['cets_google_analytics_ua'])) {
			
			//updated
			//purify and update settings
			$new_ua = trim(wp_filter_nohtml_kses($_REQUEST['cets_google_analytics_ua']));
			update_option('cets_google_analytics_ua', $new_ua);
			
			echo "<div class='updated fade'><p><a target='_blank' href='http://www.google.com/analytics'>Google Analytics Profile</a> information updated.  Note that it might take some time - even a few days - to have visits registered with <a target='_blank' href='http://www.google.com/analytics'>Google Analytics</a>.  Page visits from members of this site are <strong>not</strong> captured.</p></div>";
			
		}
		
		
			//basic settings form.
			$current_ua = esc_html(get_option('cets_google_analytics_ua', ''));
			
			
			echo "<p class='instructions'>To use Google Analytics, you must have a Google Account and a <a target='_blank' href='http://www.google.com/analytics'>Google Analytics Profile</a>.</p>";
			
			
			$ua_domain = "Error: Please contact CE Tech Services for assistance.";


			if (function_exists('cets_get_domain_public_url')) {
				$ua_domain = cets_get_domain_public_url();
			} else {
				$current_site = get_current_site();
				$ua_domain = trailingslashit("http://" . $current_site->domain);
			}
			
			
			
			echo "<p class='instructions'>When registering your site with Google Analytics, here is the Domain you should use: <strong>$ua_domain</strong></p>";
			
			
			echo "<p class='instructions'>Google Analytics Profiles can be obtained <a target='_blank' href='http://www.google.com/analytics'>here</a>.  Once configured, you'll be able to view your stats <a target='_blank' href='http://www.google.com/analytics'>here</a> as well.</p>";
			
			
			echo "";
			
			$form_action = esc_attr( $_SERVER['REQUEST_URI'] );			
			echo '<form action="' . $form_action . '" method="post">';
			
		
			
			echo "Tracking ID: <input type='text' size='20' name='cets_google_analytics_ua' value='" . $current_ua . "'> (UA-XXXXXXXX-X)<br>";
			
			submit_button( "Save Profile Data", "primary", "cets_google_analytics_submit");
			echo "</form>";
			
			
		
		
		
		
		
		echo "</div><!-- /wrap -->";
		
	}
	
	function footer_scripts() {
		//hide stat tracking from anyone who is a Contributor or above
		if (!current_user_can('edit_posts')) {
			$ua = get_option('cets_google_analytics_ua', '');
			if ($ua) {
				echo "<script type='text/javascript'>

					  var _gaq = _gaq || [];
					  _gaq.push(['_setAccount', '" . $ua . "']);
					  _gaq.push(['_trackPageview']);

					  (function() {
						var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
						ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
						var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
					  })();

					</script>";
			}
		} 
			
		
		
	}  //function footer scripts
	
	
	function activate_links($links, $file) {
	
		static $this_plugin;
		if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
	
		if ($file == $this_plugin) {
			$settings_link = '<a href="' . admin_url( 'options-general.php?page=cets_google_analytics' ) . '">Settings</a>';
			array_unshift($links, $settings_link);
		}

	return $links;
	
	}
	
	
	
 }
 
  // instantiate the object
 $cets_google_analytics = new CETS_Google_Analytics;