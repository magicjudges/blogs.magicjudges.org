<?php
/*
Plugin Name: Auto Excerpt everywhere
Plugin URI: http://www.josie.it/wordpress/wordpress-plugin-auto-excerpt-everywhere/
Description: The plugin shows excerpts instead of contents in your blog, single posts and pages excluded. It tries to display your custom excerpt text and if it doesn't find it it will show an automatically generated excerpt. You can also define an excerpt length (default is 500) and a custom read more link.
Version: 1.5
Author: Serena Villa
Author URI: http://www.josie.it
*/

function auto_excerpt_everywhere_activation() {
	if (!get_option("excerpt_everywhere_length")){
		update_option("excerpt_everywhere_length","500");
	}
	if (!get_option("excerpt_everywhere_align")){
		update_option("excerpt_everywhere_align","alignleft");
	}
	if (!get_option("excerpt_everywhere_moretext")){
		update_option("excerpt_everywhere_moretext","Read more [...]");
	}
	if (!get_option("excerpt_everywhere_moreimg")){
		update_option("excerpt_everywhere_moreimg","");
	}
	if (!get_option("excerpt_everywhere_rss")){
		update_option("excerpt_everywhere_rss","yes");
	}
	if (!get_option("excerpt_everywhere_homepage")){
		update_option("excerpt_everywhere_homepage","no");
	}
	if (!get_option("excerpt_everywhere_sticky")){
		update_option("excerpt_everywhere_sticky","no");
	}
	if (!get_option("excerpt_everywhere_thumb")){
		update_option("excerpt_everywhere_thumb","none");
	}
}
function auto_excerpt_everywhere_construct() {
	$rss_disable=get_option("excerpt_everywhere_rss");
	$sticky_disable=get_option("excerpt_everywhere_sticky");
	$homepage_disable=get_option("excerpt_everywhere_homepage");
	if ($rss_disable=="yes" && $sticky_disable=="yes" && $homepage_disable=="yes"){
		if(!is_single() && !is_page() && !is_feed() && !is_sticky() && !is_home()) {
			add_filter('the_content','auto_excerpt');
		} 
	}
	else if ($rss_disable=="yes" && $sticky_disable=="yes"){
		if(!is_single() && !is_page() && !is_feed() && !is_sticky()) {
			add_filter('the_content','auto_excerpt');
		}
	}
	else if ($sticky_disable=="yes" && $homepage_disable=="yes"){
		if(!is_single() && !is_page() && !is_sticky() && !is_home()) {
			add_filter('the_content','auto_excerpt');
		} 
	}
	else if ($rss_disable=="yes" && $homepage_disable=="yes"){
		if(!is_single() && !is_page() && !is_feed() && !is_home()) {
			add_filter('the_content','auto_excerpt');
		} 
	} else if ($rss_disable=="yes"){
		if(!is_single() && !is_page() && !is_feed()) {
			add_filter('the_content','auto_excerpt');
		} 
	} else if ($sticky_disable=="yes") {
		if(!is_single() && !is_page() && !is_sticky()) {
			add_filter('the_content','auto_excerpt');
		} 
	} else if ($homepage_disable=="yes") {
		if(!is_single() && !is_page() && !is_home()) {
			add_filter('the_content','auto_excerpt');
		} 
	} else if ($sticky_disable!="yes" && $rss_disable!="yes" && $homepage_disable!="yes") {
		if(!is_single() && !is_page()) {
			//add_filter('the_excerpt_rss','auto_excerpt');
			add_filter('the_content','auto_excerpt');
		}	
	}
	if (!get_option("excerpt_everywhere_length")){
		update_option("excerpt_everywhere_length","500");
	}
	if (!get_option("excerpt_everywhere_align")){
		update_option("excerpt_everywhere_align","alignleft");
	}
	if (!get_option("excerpt_everywhere_rss")){
		update_option("excerpt_everywhere_rss","yes");
	}
	if (!get_option("excerpt_everywhere_homepage")){
		update_option("excerpt_everywhere_homepage","no");
	}
	if (!get_option("excerpt_everywhere_sticky")){
		update_option("excerpt_everywhere_sticky","no");
	}
	if (!get_option("excerpt_everywhere_thumb")){
		update_option("excerpt_everywhere_thumb","none");
	}
}

function auto_excerpt_everywhere_options() {
	add_options_page('Auto Excerpt Everywhere', 'Auto Excerpt Everywhere', 'manage_options','auto-excerpt-everywhere/options.php');
}

function myTruncate($string, $limit, $break=".", $pad="...") {
	
	if(strlen($string) <= $limit) return $string; 
	if(false !== ($breakpoint = strpos($string, $break, $limit))) {
		if($breakpoint < strlen($string) - 1) {
			$string = substr($string, 0, $breakpoint) . $pad;
		}
	} return $string;
}

function auto_excerpt($content) {
	global $post;
	$testomore = get_option("excerpt_everywhere_moretext");
	$imgmore = get_option("excerpt_everywhere_moreimg");
	$whatthumb = get_option("excerpt_everywhere_thumb");
	$customclass = get_option("excerpt_everywhere_class");
	$alignment=get_option("excerpt_everywhere_align"); if ($alignment=="none"){$alignment="";}
	if ($whatthumb=="none"){ $thumb = ""; }
	else {
		if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
			$default_attr = array(
			'class'	=> "attachment-".$whatthumb." ".$alignment." autoexcerpt_thumb ".$customclass,
			'alt'	=> trim(strip_tags(strip_shortcodes( $attachment->post_excerpt ))),
			'title'	=> trim(strip_tags(strip_shortcodes( $attachment->post_title ))),
			);
			  $thumb=get_the_post_thumbnail($post->ID, $whatthumb,$default_attr);
			} 
	}
	if ($post->post_excerpt!=""){
		$excerpt=$thumb.$post->post_excerpt;
		if ($imgmore!=""){$linkmore = ' <a href="'.get_permalink().'" class="more-link"><img src="'.$imgmore.'" border="0" alt="Read more" /></a>';} 
		else if ($testomore!=""){$linkmore = ' <a href="'.get_permalink().'" class="more-link">'.$testomore.'</a>';}
	} else {
		if (strlen($post->post_content)>get_option("excerpt_everywhere_length")){
			$excerpt= $thumb.myTruncate(strip_tags(strip_shortcodes($post->post_content)), get_option("excerpt_everywhere_length"), " ", "");
			if ($imgmore!=""){$linkmore = ' <a href="'.get_permalink().'" class="more-link"><img src="'.$imgmore.'" border="0" alt="Read more" /></a>';} 
			else if ($testomore!=""){$linkmore = ' <a href="'.get_permalink().'" class="more-link">'.$testomore.'</a>';}
		} else {
			$excerpt=$thumb.$content;
			$linkmore="";
		}
	}
	
	return $excerpt.$linkmore;
	
}
function custom_excerpt_everywhere_length() {
	return get_option("excerpt_everywhere_length");
}


function add_settings_link($links, $file) {
static $this_plugin;
if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
 
if ($file == $this_plugin){
$settings_link = '<a href="options-general.php?page=auto-excerpt-everywhere/options.php">'.__("Settings", "auto-excerpt-everywhere").'</a>';
 array_unshift($links, $settings_link);
}
return $links;
 }

register_activation_hook(__FILE__,'auto_excerpt_everywhere_activation');
add_action('the_post', 'auto_excerpt_everywhere_construct');
add_action('admin_menu','auto_excerpt_everywhere_options');
add_filter('excerpt_length', 'custom_excerpt_everywhere_length');
add_filter('plugin_action_links', 'add_settings_link', 10, 2 );
if ( function_exists( 'add_theme_support' ) ) { 
  add_theme_support( 'post-thumbnails' ); 
}

?>