<?php

function blog_topic_shortcode_site_list($atts) {
	extract( shortcode_atts( array(
			'id' => '',
			'sites' => 0,
		), $atts ) );

	echo "<ul>";

	 cets_get_recent_posts_from_topic_id_html($id, $sites, 0, 1);

	echo "</ul>";

	//$posts = cets_get_recent_posts_from_topic_id($id, $sites, 0, 1);


	$html = "";  // :/
	return $html;
}
add_shortcode('blog-topic-site-list', 'blog_topic_shortcode_site_list');

