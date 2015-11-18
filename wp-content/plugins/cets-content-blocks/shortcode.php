<?php

// Add Shortcode
function cets_content_block_shortcode( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'id' => '',
		), $atts )
	);

	if ( ! $id ) {
		return '';
	}

	$block = get_post( $id );

	if ( ! $block ) {
		return '';
	}

	if ( $block->post_type != 'cets_content_block' || $block->post_status != 'publish' ) {
		return '';
	}


	// todo - how to stop infinite looping
	
	$arr_functions = array(
		'do_shortcode',
		'wptexturize',
		'wpautop',
		'shortcode_unautop',
		'convert_chars',
		);

	$output = $block->post_content;

	global $wp_embed;
	$output = $wp_embed->run_shortcode( $output );

	foreach( $arr_functions as $function ) {
		add_filter( 'cets_snippet_output', $function );
	}

	$output = apply_filters( 'cets_snippet_output', $output );

	$output = "<p class='clear'>&nbsp;</p>{$output}<p class='clear'>&nbsp;</p>";

	return $output;

}
add_shortcode( 'cets_content_block', 'cets_content_block_shortcode' );