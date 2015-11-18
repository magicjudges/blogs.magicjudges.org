<?php
// Plugin Name: Mini Loops for Multisite
// Description: Adds a 'blog id' field so you can get and display posts from other sites on the same network. Subject to limitations of switch_to_blog. Must be a Super Admin to see the option.
// Version: 2013.03.02
// Author: Kailey Lampert
// Author URI: kaileylampert.com

add_action( 'init', 'miniloops_multisite_init' );
function miniloops_multisite_init() {
	// bail if no Mini Loops, not MS, or user isn't Super Admin
	if ( ! function_exists( 'miniloops' ) ) return;
	if ( ! is_multisite() ) return;
	//if ( ! is_super_admin() ) return;

	add_action( 'before_the_miniloop', 'mlms_before_the_miniloop', 10, 2 );

	add_action( 'in_widget_form', 'mlms_add_blog_id_field', 10, 3 );
	add_filter( 'widget_update_callback', 'mlms_widget_update_callback', 10, 2 );
}

function mlms_before_the_miniloop( $query, $args ) {
	if ( 0 === $args['switch_to_id'] ) return;
	switch_to_blog( intval( $args['switch_to_id'] ) );
	add_action( 'after_the_miniloop', 'mlms_after_the_miniloop' );
}
function mlms_after_the_miniloop() {
	restore_current_blog();
}

function mlms_add_blog_id_field( $widget, $return, $instance ) {
	if ( ! is_a( $widget, 'miniloops' ) ) return;

	$switch_to_id = isset( $instance['switch_to_id'] ) ? $instance['switch_to_id'] : '';
	?><p>
		<label for="<?php echo $widget->get_field_id( 'switch_to_id' ); ?>"><?php _e( 'Get from blog ID:' );?>
			<input class="widefat" id="<?php echo $widget->get_field_id('switch_to_id'); ?>" name="<?php echo $widget->get_field_name('switch_to_id'); ?>" type="text" value="<?php echo $switch_to_id; ?>" />
		</label>
	</p><?php

}

function mlms_widget_update_callback( $instance, $new_instance ) {
	$instance['switch_to_id'] = intval( $new_instance['switch_to_id'] );
	return $instance;
}
