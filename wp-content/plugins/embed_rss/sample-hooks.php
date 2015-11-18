<?php

/*
	NOTE: This file is included only as a sample, and this code is NOT executed by Embed RSS by default.

  	Embed RSS (CETS) supports several hooks for allowing you to customize its behavior.  This file containings examples of using them.  You may add them, or variants of them, to your theme's functions.php files or create your own plugin.
*/


/* 
	Filter: cets_embedrss_allowed_types 
	Embed RSS filters an array containing a list of post types on which RSS feeds can be embedded.
*/

 add_filter('cets_embedrss_allowed_types', 'cets_disallow_rss_on_posts');

function cets_disallow_rss_on_posts($types_array) {

	$bad_types = array("post");

	foreach($types_array as $key=>$value) {
		if (in_array($value, $bad_types)) {
			unset($types_array[$key]);
		}
	}
	return $types_array;
}



/* 
	Action: cets_embedrss_url_field_after
	If you want to provide assistance to users in helping users find the RSS url
*/

add_action('cets_embedrss_url_field_after', 'cets_embedrss_how_find');

function cets_embedrss_how_find() {
	echo "<span class='help_finding_wrapper'><a target='_blank' href='https://www.google.com/#q=how+find+rss+feed+url'>How do I find the feed URL?</a></span>";
}


