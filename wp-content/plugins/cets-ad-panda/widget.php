<?php

/**
 * Adds Foo_Widget widget.
 */
class Cets_Ad_Panda_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'cets_ad_panda_widget', // Base ID
			'Ad Panda', // Name
			array( 'description' => __( 'A predefined ad or image', 'text_domain' ), ) // Args
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
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		
		if (isset($instance['ad_panda_ad'])) {
			cets_ad_panda_widget_render($instance['ad_panda_ad']);
		}
		
		echo $after_widget;
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
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['ad_panda_ad'] = strip_tags($new_instance['ad_panda_ad']);

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( '', 'text_domain' );
		}
		if (isset ($instance['ad_panda_ad'])) {
			$ad_panda_ad = $instance['ad_panda_ad'];
		} else {
			$ad_panda_ad = 'default';
		}
		

		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		
		<p>
		Choose an Ad:<br/>
		<select id="<?php echo $this->get_field_id( 'ad_panda_ad' ); ?>" name="<?php echo $this->get_field_name( 'ad_panda_ad' ); ?>" onchange="ad_panda_preview('<?php echo $this->get_field_id('ad_panda_ad') . "','" . $this->get_field_id('ad-panda-preview');?>');"> 
		<?php 
			
			$ads = cets_ad_panda_get_ads();
		
			foreach ($ads as $ad) {
				if ($ad_panda_ad == $ad['id']) {
					$selectedhtml = " selected=SELECTED ";
				}  else {
					$selectedhtml = " ";
				}
				echo "<option $selectedhtml value='" . esc_html($ad['id']) . "'>" . esc_html($ad['title']) . "</option>";
			}
		
		?>
		</select>
				
		<?php
		
		if (isset($instance['ad_panda_ad'])) {
			$ad_id = $instance['ad_panda_ad'];
		} else {
			$ad_id = 'default';
		}
			echo "<h3>Preview:</h3>";
						
			echo "<div class='ad-panda-preview' id='" . $this->get_field_id('ad-panda-preview') . "'>";
			cets_ad_panda_widget_render($ad_id);
			echo "</div>";
		
	}

} // class 



// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "cets_ad_panda_widget" );' ) );