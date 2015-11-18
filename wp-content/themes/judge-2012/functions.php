<?php 
/* ****************************************************************************************************************************
* Generate FYI Topics RSS feeds for browsers that support alternate feeds in the header
* ****************************************************************************************************************************
*/
function get_fyiTopicRSSforHead() {
// check to see if there's content in a topic. If so, create an alternate rss view for that topic
	if (function_exists('cets_get_used_topics')){
		$topics = cets_get_used_topics();
		if (sizeOf($topics) >= 1){
			foreach($topics as $topic) {
				echo('<link rel="alternate" type="application/rss+xml" title="' . $topic->slug . ' RSS Feed" href="/topic/' . strtolower($topic->slug) . '/feed" />');
			}
		  }	
		}
}

function fyi2011_body_classes($classes) {
	global $wp_query;
	
		if (isset($wp_query->query_vars['topic'])) {
		
		$classes[] = 'topic';
		$classes[] = 'twoCol';
	}	
	else if (isset($wp_query->query_vars['sitelist'])) {
		$classes[] = 'sites';
		$classes[] = 'oneCol';
	}
	
	if (is_home()) {
		$classes[] = 'home';
		$classes[] = 'twoCol';
	}
	
	if (is_front_page()) {
		$classes[] = 'front-page';
	}
	
	$currentFile = $_SERVER["REQUEST_URI"];
    $parts = Explode('/', $currentFile);
    $currentFile = $parts[count($parts) - 1];
	
	//wp-singup
	if ($currentFile == 'wp-signup.php') {
		$classes[] = 'signup';
	}
	return $classes;
	
}
add_filter( 'body_class', 'fyi2011_body_classes' );


/*
These are two super hacky functions for displaying correct body classes.
I *think* the original rewrites in this theme are done incorrectly. will look later. this works for now
*/
function remove_home_callback($var) {
	if ($var == 'home' || $var == 'blog') {
		return false;
	} else {
		return true;
	}
}

function remove_home_class($classes) {
	$classes = array_filter($classes, 'remove_home_callback');
	return $classes;
}


/* ***********************************************************
 * Register Sidebar widgets
 * ***********************************************************
 */

	if ( function_exists('register_sidebar') )
	register_sidebar(array(
	'name' => 'Main Widgets',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h2>',
	'after_title' => '</h2>',
	));
	
	if ( function_exists('register_sidebar') )
	register_sidebar(array(
	'name' => 'Sidebar Widgets',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h2>',
	'after_title' => '</h2>',
	));

include_once dirname(__FILE__) . '/widgets/cets_bt_related_posts_widget.php';
include_once dirname(__FILE__) . '/widgets/cets_bt_topics_with_posts.php';
	
/* ***********************************************************
 * Default Widgets
 * ***********************************************************
 */

wp_register_sidebar_widget(__('Tag Cloud'), 'cets_tag_cloud', null, 'tagcloud');
wp_unregister_widget_control('cets_tag_cloud');


// if there are extra slashes with the wp-signup.php get ride of them
function cets_fix_wp_signup_urls()
{
	if ( is_main_site() && substr_count($_SERVER["REQUEST_URI"], "/") > 1) {
	wp_redirect( network_home_url( 'wp-signup.php' ) );
	die();
}
}

add_action('signup_header','cets_fix_wp_signup_urls');


//include the rewrites
include_once dirname(__FILE__) . '/rewrites.php';


// flexible truncating function
function cets_truncate($string, $limit, $break=".", $pad="...") { // return with no change if string is shorter than $limit 
	
	if(strlen($string) < $limit) {
		return $string; 
	}
	
	$arr = explode($break, $string);	
	$glib = array_pop($arr);
	$string = implode($break, $arr);
	
	return $string . $pad;

}

function cets_comments_no_website($fields) {
	if (!is_user_logged_in()) {
		if (isset($fields['url'])) {
			unset($fields['url']);
		}
	}
	return $fields;
}
add_filter('comment_form_default_fields', 'cets_comments_no_website');


require_once( 'functions-lems.php' );