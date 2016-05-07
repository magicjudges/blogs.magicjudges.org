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
	if (wp_get_theme()->get_stylesheet() === "judge-familiar") {
		wp_enqueue_style(
			"judge-banner-screen",
			"http://assets.magicjudges.org/judge-banner/css/screen.css",
			array(),
			"1.0",
			"(min-width: 1001px)"
		);
		wp_enqueue_style(
			"judge-banner-mobile",
			"http://assets.magicjudges.org/judge-banner/css/mobile.css",
			array(),
			"1.0",
			"(max-width: 1000px)"
		);
		wp_enqueue_script(
			"judge-banner-script",
			"http://assets.magicjudges.org/judge-banner/js/judge-banner.js",
			array(),
			"1.0",
			true // $in_footer
		);
	} else {
		// Fallback for the world before judge-familiar
		wp_enqueue_script(
			"judge-banner",
			"http://assets.magicjudges.org/judge-banner/judge-banner.js",
			array("jquery"),
			"0.4"
		);
	}
}

function judge_banner() {
	include plugin_dir_path(__FILE__) . "/banner.html";
}
