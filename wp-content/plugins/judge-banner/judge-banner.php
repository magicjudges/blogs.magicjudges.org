<?php
/*
Plugin Name: Judge Banner
Plugin URI: http://www.aleaiactaest.ch
Description: Adds the global judge banner header to the blog.
Version: 1.0
Author: Joel Krebs
Author URI: http://www.aleaiactaest.ch
License: GPL2
*/

add_action("wp_enqueue_scripts", "judge_banner_scripts");

function judge_banner_scripts() {

		// Fallback for the world before judge-familiar
		wp_enqueue_script(
			"judge-banner",
			"https://assets.magicjudges.org/judge-banner/judge-banner.js",
			array("jquery")
		);
}

function judge_banner() {
	//include plugin_dir_path(__FILE__) . "/banner.html";
}
