<?php
/*
Plugin Name: Sharing Prompts for Authors (CETS)
Plugin URI:  
Description: Presents prompts for easy sharing of published posts and pages on Facebook and Twitter
Version: 0.3
Author: Jason Lemahieu 
Author URI: 
*/

function cets_share_prompts_add_boxes() {
	
	global $post;
	if ($post->post_status == 'publish') {
	
		add_meta_box(
			'cets_share_prompts_meta_box',
			'Sharing Prompts',
			'cets_share_prompts_box',
			'post',
			'side',
			'high'
			);
		add_meta_box(
			'cets_share_prompts_meta_box',
			'Sharing Prompts',
			'cets_share_prompts_box',
			'page',
			'side',
			'high'
			);
		}
}
add_action('add_meta_boxes', 'cets_share_prompts_add_boxes');

function cets_share_prompts_box($post, $metabox) {

	global $post;
	$title 		= str_replace('+','%20',urlencode($post->post_title));
	$permalink 	= urlencode(get_permalink($post->ID));
	$excerpt	= urlencode(strip_tags(strip_shortcodes($post->post_excerpt)));
	$excerpt	= str_replace('+','%20',$excerpt);
	
	
	echo "<p>Click an icon below to share this post:</p>";
	
	echo "<a target='_blank' href='http://www.facebook.com/share.php?u=" . $permalink . "&amp;t=" . $title . "'><img src='" . plugins_url( 'images/facebook.png', __FILE__) . "'></a>&nbsp;";
	echo "<a target='_blank' href='http://twitter.com/home?status=" . $title . "%20-%20" . $permalink . "'><img src='" . plugins_url( 'images/twitter.png', __FILE__) . "'></a>&nbsp;";
	echo "<a target='_blank' href='https://plus.google.com/share?url=" . $permalink . "'><img src='" . plugins_url( 'images/googleplus.png', __FILE__) . "'></a>";

}

function cets_sharing_prompts_get_sites() {

	$sites = array();   
	
	$sites[] = array( "name" => "Facebook",
						"id" => "facebook",
						"url" => "http://www.facebook.com/share.php?u=PERMALINK&amp;t=TITLE",
				);

	$sites[] = array( "name" => "Twitter",
						"id" => "twitter",
						'url' => 'http://twitter.com/home?status=TITLE%20-%20PERMALINK',
				);
	
	$sites[] = array( "name" => "Google+",
						"id" => "googleplus",
						'url' => 'https://plus.google.com/share?url=PERMALINK',
				);
	return $sites;
}