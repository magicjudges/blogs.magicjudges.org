<?php
/*

Plugin Name: Google Custom Search Engine (CETS)
Description: Uses our Google CSE to replace WordPress default search
Version: 1.1
Author: Jason Lemahieu
Author URI: http://madtownlems.wordpress.com

*/

/* CHANGELOG

	1.1 - use Site URL instead of Domain for Site Search Restriction

*/

if ( is_admin() ) {

	require_once( dirname(__FILE__) . '/settings.php');

} 

function cets_cse_get_global_search_page() {

	return "https://blogs.magicjudges.org/";
}

function cets_cse_get_google_script() {

	$site_restriction = cets_cse_get_site_restriction();

	if ($site_restriction) {
		$site_restriction_parameter = "as_sitesearch='{$site_restriction}'";
	} else {
		$site_restriction_parameter = "";
	}

	$cx = cets_cse_get_cx();
	
	$results_url = get_site_url();
		

 	$script = "<script>
	  (function() {
	    var cx = '{$cx}';
	    var gcse = document.createElement('script');
	    gcse.type = 'text/javascript';
	    gcse.async = true;
	    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
	        '//www.google.com/cse/cse.js?cx=' + cx;
	    var s = document.getElementsByTagName('script')[0];
	    s.parentNode.insertBefore(gcse, s);
	  })();
	</script>
	<br/>
	<gcse:searchbox-only {$site_restriction_parameter} resultsUrl='{$results_url}' queryParameterName='s'></gcse:searchbox-only>
	<gcse:searchresults-only {$site_restriction_parameter} ></gcse:searchresults-only>
	";
	
	/*
	if ( is_admin_bar_showing() ) { 
		 $script .= "<style> .gsc-completion-container { margin-top: 32px !important; } </style>";
	}
	*/

	return $script;

}

function cets_cse_get_cx() {
	
	return "013946628675272308402:jdscoozq5hc";
}


function cets_cse_get_site_restriction() {

	global $blog_id;

	if ($blog_id == 1) {

		// no restriction when searching from root site
		$site_restriction = "";
	 	
	 } else {
	
		$site_restriction = get_home_url();
		
 	}

 	return $site_restriction;
}


function cets_cse_display_search_results() {

	if ( !is_search() ) {
		return '';
	}

	global $blog_id;
	
	$html = '';

	if ($blog_id != 1) {
		$site_name = get_bloginfo('name');
		$site_url = site_url();
		$html .= "<p>Showing results from <a href='{$site_url}'>{$site_name}</a>. " . cets_cse_get_global_search_link() . "</p>";
	}

	$html .= cets_cse_get_google_script();

	if ($blog_id != 1) {
		$html .= cets_cse_get_global_search_suggestion();
	}
		
	echo $html;

}


function cets_cse_get_no_results_message() {
	global $blog_id;

	if ($blog_id == 1) {
		return "<p>No matches.</p>";
	} else {
		return "<p class='cets-cse cets-cse-no-results'>No matches on this site. " . cets_cse_get_global_search_link() . "</p>";
	}
}

function cets_cse_get_global_search_link() {
	return "Try <a href='" . cets_cse_get_global_search_page() . "?s=" . get_search_query() . "'>searching all sites</a>.";
}

function cets_cse_get_global_search_suggestion() {
	return "<div class='cets-cse cets-cse-suggest-global'><p>Didn't find what you were looking for on this site? " . cets_cse_get_global_search_link() . "</p></div>";
}



function cets_cse_cancel_query( $query ) {
    if ( is_search() && !is_admin() && !is_feed() && cets_cse_use_google_search() ) {
        $query = false;
    }
    return $query;
}
add_action( 'posts_request', 'cets_cse_cancel_query' );



/* HELPERS */
function cets_cse_use_google_search() {

	$cets_cse_site_search_results_method_setting = get_option( 'cets_cse_site_search_results_method_setting', 0 );
	
	$blog_public = get_option('blog_public', 1);

	if ($cets_cse_site_search_results_method_setting == 1 && $blog_public == 1) {
		return true;
	} else {
		return false;
	}

}

	
