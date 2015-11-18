<?php
/*

Plugin Name: Twitter Widget (CETS)
Description: Allows you to embed a Twitter Widget into your sidebar
Version: 0.1
Author: Jason Lemahieu
Author URI: http://madtownlems.wordpress.com

*/

/**
 * Adds Foo_Widget widget.
 */
class CETS_Twitter_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'cets_twitter_widget', // Base ID
			__( 'Twitter Widget', 'text_domain' ), // Name
			array( 'description' => __( 'Display tweets from a widget created at twitter.com/settings/widgets', 'text_domain' ), ) // Args
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
		
		$data_widget_id = $instance['data_widget_id'];
		if ($data_widget_id) {
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}
		
			echo "<a class='twitter-timeline' data-dnt='true' data-widget-id='{$data_widget_id}'>(JavaScript is required to view tweets.)</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script>";
		
			echo $args['after_widget'];
		}

		
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Latest Tweets', 'text_domain' );

		if (! empty( $instance['data_widget_id'] ) ) {
			$data_widget_id = $instance['data_widget_id'];
		} else {
			$data_widget_id = '';
		}

		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

		<?php
		if (function_exists('cets_link_library_get_link')) {
			$help_link_widget_id = cets_link_library_get_link('TWITTER_WIDGET_ID');
			if ($help_link_widget_id) {
				echo "<div class='cets-help-box'><p>Need help finding your Widget ID? <a href='{$help_link_widget_id}' target='_blank'>Read our Help Article</a></p></div>";
			}
		}
		?>

		<p>
		<label for="<?php echo $this->get_field_id( 'data_widget_id' ); ?>"><?php _e( 'Twitter Widget ID:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'data_widget_id' ); ?>" name="<?php echo $this->get_field_name( 'data_widget_id' ); ?>" type="text" value="<?php echo esc_attr( $data_widget_id ); ?>">
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
		
		// process $instance['data_widget_id']
		if ( !empty( $new_instance['data_widget_id'] ) ) {
			
			$id_val = trim(  $new_instance['data_widget_id']  );

			// first, see if they passed in just a number
			if ( is_numeric( $id_val ) ) {
				$instance['data_widget_id'] = $id_val;
				return $instance;
			}

			// secondly, see if they passed in the provided embed text
			if ( strpos( $id_val, 'data-widget-id' ) ) {

				$dom = new DOMDocument;
				libxml_use_internal_errors(true);
				$dom->loadHTML($id_val);
				$dom->preserveWhiteSpace = false;
				$anchors = $dom->getElementsByTagName('a');
			
				if ( !empty($anchors) ) {
					$first_anchor = $anchors->item(0);
					$data_attribute = $first_anchor->getAttribute('data-widget-id');				

					if ($data_attribute) {
						$instance['data_widget_id'] = strip_tags( $data_attribute );
						return $instance;
					}
				}	

			}

		} else {
			$instance['data_widget_id']= '';
		}
		
		return $instance;
	}

} // class CETS_Twitter_Widget



// register Foo_Widget widget
function cets_twitter_widget_register() {
    register_widget( 'CETS_Twitter_Widget' );
}
add_action( 'widgets_init', 'cets_twitter_widget_register' );
