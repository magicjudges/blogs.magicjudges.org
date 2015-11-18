<?php
/*

Plugin Name: Google Calendar Widget (CETS)
Description: Adds a widget for easily displaying a google calendar in a sidebar
Version: 0.3
Author: Jason Lemahieu
Author URI: http://madtownlems.wordpress.com

*/

/*
 CHANGELOG
    0.3
     - stop using foo_query_arg functions which stripped multiple src= from conjoined calendars
     - use new valid view parameters: MONTH instead of CAL
	0.2 
	- fixed saving of Calendar vs Agenda


 */

/**
 * Adds Foo_Widget widget.
 */
class CETS_Google_Calendar_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'cets_google_calendar_widget', // Base ID
			__('Google Calendar (CETS)', 'text_domain'), // Name
			array( 'description' => __( 'Embedded Google Calendar', 'text_domain' ), ) // Args
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
	
		if (isset($instance['google_cal_url'])) {
			$google_cal_url = $instance['google_cal_url'];
		} else {
			$google_cal_url = "";
		}
		if (isset($instance['mode'])) {
			$mode = $instance['mode'];
			if ($mode == 'CAL') {
				$mode = 'MONTH';
			}
		} else {
			$mode = "AGENDA";
		}

		
		if (!isset($google_cal_url) || !$google_cal_url) {

			if (current_user_can('manage_options')) {
				
				echo "ERROR: No Google Calendar URL provided to Google Calendar Widget.<br/>";
			}
			//empty calendar, return
			return;
		}

		if (!$this->validate_calendar_url($google_cal_url)) {
			if (current_user_can('manage_options')) {
				echo "ERROR: Invalid Google Calendar URL provided to Google Calendar Widget.<br/>";
			}
			return;
		}


		$title = apply_filters( 'widget_title', $instance['title'] );
		
		//update any CAL links to use MONTH instead
		$google_cal_url = str_ireplace('mode=cal', 'mode=MONTH', $google_cal_url);

		if ($mode == "AGENDA") {
			//$google_cal_url = add_query_arg('mode', 'AGENDA', $google_cal_url);
			if (stripos( $google_cal_url, 'mode=' ) == false ) {
				$google_cal_url .= "&mode=AGENDA";
			}
			if ( stripos( $google_cal_url, 'mode=month' ) ) {
				$google_cal_url = str_ireplace('mode=month', 'mode=AGENDA', $google_cal_url);
			}
		}
		if ($mode == "MONTH") {
			//$google_cal_url = add_query_arg('mode', false, $google_cal_url);
			if (stripos( $google_cal_url, 'mode=' ) == false ) {
				$google_cal_url .= "&mode=MONTH";
			}
			if ( stripos( $google_cal_url, 'mode=agenda' ) ) {
				$google_cal_url = str_ireplace('mode=agenda', 'mode=month', $google_cal_url);
			}
		}

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
		
		echo "<iframe style='width:100%;' height='360' src='{$google_cal_url}'></iframe>";


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
		


		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = "";
		}
		if ( isset( $instance[ 'google_cal_url' ] ) ) {
			$google_cal_url = $instance[ 'google_cal_url' ];
		}
		else {
			$google_cal_url = "";
		}
		if ( isset( $instance[ 'mode' ] ) ) {
			$mode = $instance[ 'mode' ];
		}
		else {
			$mode = "AGENDA";
		}
		?>
		<p>
		(Note that the calendar's title will be displayed, so you might not want another title here.)<br/>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<?php 
		$valid = $this->validate_calendar_url($google_cal_url);
		?>
		<p>
		<?php	if (!$valid) { echo "<p><strong><i>ERROR: Invalid Calendar URL</i></strong></p>"; } ?>
		<label for="<?php echo $this->get_field_id( 'google_cal_url' ); ?>"><?php _e( 'Google Calendar URL:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'google_cal_url' ); ?>" name="<?php echo $this->get_field_name( 'google_cal_url' ); ?>" type="text" value="<?php echo esc_attr( $google_cal_url ); ?>" /><br/>
		<?php
		
		
		if (function_exists('cets_link_library_get_link')) {
			$help_link_google_calendar_find_url = cets_link_library_get_link('GOOGLE_CALENDAR_URL');
			if ($help_link_google_calendar_find_url) {
				echo "<div class='cets-help-box'><p>Need help finding your calendar's public URL? <a href='{$help_link_google_calendar_find_url}' target='_blank'>Read our Help Article</a></p></div>";
			}
		}

		?>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id( 'mode' ); ?>"><?php _e( 'Mode:' ); ?></label> 
		<select id="<?php echo $this->get_field_id( 'mode' ); ?>" name="<?php echo $this->get_field_name( 'mode' ); ?>">
			<option value="AGENDA" <?php selected("AGENDA", $mode); ?>>Agenda</option>
			<option value="MONTH" <?php selected("MONTH", $mode); ?>>Calendar</option>
		</select>

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
	
		$instance['google_cal_url'] = ( ! empty( $new_instance['google_cal_url'] ) ) ? trim(strip_tags( $new_instance['google_cal_url'] )) : '';
		
		$instance['mode'] = ( ! empty( $new_instance['mode'] ) ) ? strip_tags( $new_instance['mode'] ) : '';
		return $instance;
	}


	function validate_calendar_url($url) {
		if (substr($url, 0, 30) == "http://www.google.com/calendar") {
			return true;
		}
		if (substr($url, 0, 31) == "https://www.google.com/calendar") {
			return true;
		}

		return false;
	}

} // class Foo_Widget

// register Foo_Widget widget
function cets_google_calendar_register_widget() {
    register_widget( 'CETS_Google_Calendar_Widget' );
}
add_action( 'widgets_init', 'cets_google_calendar_register_widget' );