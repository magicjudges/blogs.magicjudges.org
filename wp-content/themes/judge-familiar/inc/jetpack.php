<?php
/**
 * Jetpack Compatibility File.
 *
 * @link https://jetpack.me/
 *
 * @package Judge_Familiar
 */

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
function judge_familiar_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'judge_familiar_infinite_scroll_render',
		'footer'    => 'page',
	) );
} // end function judge_familiar_jetpack_setup
add_action( 'after_setup_theme', 'judge_familiar_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function judge_familiar_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	}
} // end function judge_familiar_infinite_scroll_render
