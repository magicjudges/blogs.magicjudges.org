<?php
/**
 * Judge Familiar Theme Customizer.
 *
 * @package Judge_Familiar
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function judge_familiar_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->add_setting( 'show_excerpts', array(
		'default'   => 'excerpts',
		'transport' => 'refresh',
	) );

	$wp_customize->add_section( 'judge_child_customizer', array(
		'title'    => __( 'Content Options', 'twentyten-judge' ),
		'priority' => 100,
	) );

	$wp_customize->add_control( 'judge_child_excerpt_control', array(
		'label'    => __( 'Content Excerpts' ),
		'section'  => 'judge_child_customizer',
		'settings' => 'show_excerpts',
		'type'     => 'radio',
		'choices'  => array(
			'excerpts' => __( 'Only Excerpts', 'twentyten-judge' ),
			'full'     => __( 'Full Posts', 'twentyten-judge' ),
		),
	) );
}
add_action( 'customize_register', 'judge_familiar_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function judge_familiar_customize_preview_js() {
	wp_enqueue_script( 'judge_familiar_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'judge_familiar_customize_preview_js' );
