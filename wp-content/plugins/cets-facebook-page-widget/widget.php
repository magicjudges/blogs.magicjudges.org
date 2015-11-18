<?php

/**
 * Adds Foo_Widget widget.
 */
class CETS_Facebook_Page_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'cets_facebook_page_widget', // Base ID
			__( 'Facebook Page', 'text_domain' ), // Name
			array( 'description' => __( 'Info about your Facebook Page', 'text_domain' ), ) // Args
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
		
		if ( ! cets_facebook_page_widget_is_valid_url( $instance['page_url'] ) ) {
			return;
		}

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}

		if ( $instance['show_posts'] ) {
			$data_show_posts_html = ' data-show-posts="true" ';
		} else {
			$data_show_posts_html = ' data-show-posts="false" ';
		}

		wp_enqueue_script( 'cets-facebook-page-widget-js', plugins_url( '/js/cets-facebook-page-widget-public.js', __FILE__ ) );
		
		

		?>
		
		
  
  		<div class="fb-page" data-href="<?php echo $instance['page_url']; ?>" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false" <?php echo $data_show_posts_html; ?>><div class="fb-xfbml-parse-ignore"><blockquote cite="<?php echo $instance['page_url']; ?>"><a href="<?php echo $instance['page_url']; ?>">Loading Facebook Widget...</a></blockquote></div></div>


  <?php

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
		

		if ( ! isset( $instance['title'] ) ) {
			$instance['title'] = 'Find us on Facebook';
		}

		if ( ! isset( $instance['page_url'] ) ) {
			$instance['page_url'] = 'https://www.facebook.com/MagicJudges';
		}

		if ( !isset( $instance['show_posts'] ) ) { 
			$instance['show_posts'] = '';
		}


		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'page_url' ); ?>"><?php _e( 'Page URL:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'page_url' ); ?>" name="<?php echo $this->get_field_name( 'page_url' ); ?>" type="text" value="<?php echo esc_attr( $instance['page_url'] ); ?>">
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('show_posts'); ?>">
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_posts'); ?>" value="1" name="<?php echo $this->get_field_name('show_posts'); ?>" <?php checked( $instance['show_posts'], 1); ?>>
		<?php _e('Show Posts'); ?>
		</label>
		</p>

		<?php 
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
		$instance['page_url'] = ( ! empty( $new_instance['page_url'] ) ) ? strip_tags( trim( $new_instance['page_url'] ) ) : '';

		if ( ! cets_facebook_page_widget_is_valid_url( $instance['page_url'] ) ) {
			$instance['page_url'] = '';
		}


		$instance['show_posts'] = strip_tags( stripslashes( $new_instance['show_posts'] ) );
		return $instance;
	}

} // class Foo_Widget

// register Foo_Widget widget
function register_cets_facebook_page_widget() {
    register_widget( 'CETS_Facebook_Page_Widget' );
}
add_action( 'widgets_init', 'register_cets_facebook_page_widget' );


function cets_facebook_page_widget_is_valid_url( $url ) {

	if (substr( $url, 0, 24 ) == "http://www.facebook.com/") {
			return true;
	}
	if (substr($url, 0, 25) == "https://www.facebook.com/") {
		return true;
	}

	return false;
}