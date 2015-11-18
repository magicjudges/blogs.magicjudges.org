<?php
/*
Plugin Name: CETS Network Analytics

Description: MU Plugin for outputting network analytics code
Author: Jason Lemahieu
Version: 0.1
Author URI: 
*/

/*
Copyright (c) 2012 UW Board of Regents
*/

add_action('wpmu_options', 'cets_network_analytics_add_options', 5, 0);
add_action('update_wpmu_options', 'cets_network_analytics_update_options', 5, 0);


function cets_network_analytics_add_options() {
	
	$ua = get_site_option('cets_network_ua');
	
	echo "<h3>Network Analytics Settings</h3>";
	
	echo "<table class='form-table'>";
	echo "<tr>";
	echo "<th>Google Analytics UA:</th>";
	
	echo "<td><input type='text' name='cets_network_ua' value='" . esc_html($ua) . "'>";
	
	echo "</tr>";
	echo "</table>";
	
}
function cets_network_analytics_update_options() {
	if (isset($_REQUEST['cets_network_ua'])) {
		$ua =  trim(wp_filter_nohtml_kses($_REQUEST['cets_network_ua']));
	}
	update_site_option('cets_network_ua', $ua);
}


function cets_network_analytics_output_code() {
	//hide stat tracking from anyone who is a Contributor or above
		if (!current_user_can('edit_posts')) {
			$ua = get_site_option('cets_network_ua', '');
			if ($ua) {
				echo "<!-- network analytics -->";
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
			
		
}

add_action('wp_footer', 'cets_network_analytics_output_code');