<?php

/**
 * Adds Foo_Widget widget.
 */
class Cets_Content_Block_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'cets_content_block_widget', // Base ID
			__( 'Content Block', 'text_domain' ), // Name
			array( 'description' => __( 'The content from an existing Content Block', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		
		if ( isset($instance['content_block_id']) ) {
			$block_id = $instance['content_block_id'];
		}



		echo do_shortcode('[cets_content_block id=' . $block_id . ']');


		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( '', 'text_domain' );
		$block_id = ! empty( $instance['content_block_id'] ) ? $instance['content_block_id'] : '';

		$has_blocks = cets_content_blocks_has_valid_blocks();
			
		if ( $has_blocks ) {
			?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>Choose a Content Block:<br/>
			<?php

			cets_content_blocks_select_box( $this->get_field_name( 'content_block_id' ), $this->get_field_id( 'content_block_id' ), $block_id  );
			echo "</p>";
		} 

		if ( $has_blocks ) {
			?><p>Or <a href='<?php echo cets_content_blocks_get_create_new_link(); ?>' target='_blank'>Create a New Content Block</a></p><?php
		} else {
			?><p>You need to create a Content Block before you can use this widget. <a href='<?php echo cets_content_blocks_get_create_new_link(); ?>'>Create one now</a></p><?php
		}
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		$instance['content_block_id'] = intval( $new_instance['content_block_id'] );  // todo - verify integer?

		return $instance;
	}

} // class Foo_Widget


// register widget
function cets_content_blocks_widgets_init() {
    register_widget( 'Cets_Content_Block_Widget' );
}
add_action( 'widgets_init', 'cets_content_blocks_widgets_init' );