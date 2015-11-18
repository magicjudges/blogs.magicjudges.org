<?php
/*
Plugin Name: Judge Banner
Plugin URI: http://www.aleaiactaest.ch
Description: Adds the global judge banner header to the blog.
Version: 0.4
Author: Joel Krebs
Author URI: http://www.aleaiactaest.ch
License: GPL2
*/

add_action('wp_enqueue_scripts', 'judge_banner_scripts');

function judge_banner_scripts()
{
    wp_enqueue_script(
        'judge-banner',
        'http://assets.magicjudges.org/judge-banner/judge-banner.js',
        array('jquery'),
        '0.4'
    );
}
