<?php
/*
Template Name: Sites

Template deprecated - redirect to Topic page
*/

// Set up global vars
global $wpquery, $topicid;

if (function_exists('cets_get_topic_id_from_slug')) {
	$topicid = cets_get_topic_id_from_slug($wp_query->query_vars['sitelist']);
	$topic = cets_get_topic($topicid);
	$new_path = "http://" . $_SERVER['SERVER_NAME'] . "/topic/" . $topic->slug;

} else {
	$new_path = "http://" . $_SERVER['SERVER_NAME'];
}

header("HTTP/1.1 301 Moved Permanently");
header("Location: " . $new_path);
header("Status: 301 Moved Permanently");
exit();