<?php

/* SETTING */
add_action( 'admin_init', 'cets_cse_settings' );

function cets_cse_settings() {
		
		add_settings_field('cets_cse_site_search_results_method_setting', 'Site Search Results', 'cets_cse_site_search_results_method_setting_callback', 'reading');		
		register_setting('reading','cets_cse_site_search_results_method_setting');		
	
	}

function cets_cse_site_search_results_method_setting_callback() {

	$cets_cse_site_search_results_method_setting = get_option( 'cets_cse_site_search_results_method_setting', 0 );

	echo "<p><strong>Note:</strong> Be sure your site is set to allow search engines via the setting above this on this page if you wish to show results from Google.</p>";

		$html = '';

		$html .= '<input type="radio" id="cets_cse_site_search_results_method_setting_wp_search" name="cets_cse_site_search_results_method_setting" value="0"' . checked( 0, $cets_cse_site_search_results_method_setting, false ) . '/>';
	    $html .= '<label for="radio_example_one">Display WordPress\'s default search results</label><br>';
	     
	    $html .= '<input type="radio" id="cets_cse_site_search_results_method_setting_google_search" name="cets_cse_site_search_results_method_setting" value="1"' . checked( 1, $cets_cse_site_search_results_method_setting, false ) . '/>';
	    $html .= '<label for="radio_example_two">Display search results from Google</label>';

	    echo $html;
	


}