<?php


/**
 * Adds Foo_Widget widget.
 */
class Blogtopics_Expansion_Topic_with_Posts_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'cets_blogtopics_expansion_topic_with_posts_widget', // Base ID
			'Blog Topic with Posts', // Name
			array( 'description' => __( 'A selected blog topic and corresponding posts', 'text_domain' ), ) // Args
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

		
		if (!isset($instance['topicid'])) {
			return false;
		} else {
			$topic_id = $instance['topicid'];
		}
		$topic = cets_get_topic($topic_id);
		
		if (isset($instance['postrows'])) {
			$postrows = $instance['postrows'];
		} else {
			$postrows = 4;
		}
		
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		
			if (isset($topic->short_name) && $topic->short_name) {
							$display_name = $topic->short_name;
						} else {
								$display_name = $topic->topic_name;
						}
		
		// Actual Widget Output
		echo ("<ul>");
			cets_get_recent_posts_from_topic_id_html($topic_id, $postrows, 0);
			echo ("</ul>");
			echo ("<div class='topicMore'>");
			echo ("<a href='/topic/" . strtolower($topic->slug) . "'>More " . $display_name . " Updates</a>");
			echo ("</div>");
		
		
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
		$instance['topicid'] = $new_instance['topicid'];
		$instance['postrows'] = $new_instance['postrows'];
		
		
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
		
		$topics = cets_get_used_topics();
		$thistopic =  cets_get_topic_id_from_blog_id($blog_id);
		
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		
		if ( isset ($instance['postrows'] )) {
			$postrows = $instance['postrows'];
		} else {
			$postrows = 4;
		}
		
		if ( isset ($instance['topicid'] )) {
			$topicid = $instance['topicid'];
		} else {
			$topicid = '';
		}
		
		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
		
	<label for="cets_topic_id">Select the topic for Recent Posts:<br/>
	<select name="<?php echo $this->get_field_name( 'topicid' ); ?>" size="1" id="<?php echo $this->get_field_id( 'title' ); ?>">
		<?php foreach ($topics as $topic){
			?> <option value="<?php echo $topic->id; ?>" <?php
			
			if ($topicid == $topic->id) { 
				echo ' selected="selected" ';
			}
			echo ('>');
			echo $topic->topic_name;
			echo ("</option>");
			
		}
		?>
	</select>
	</label>
	</p>	
	
	<p>
     <label for="cets_bt_postrows"><?php _e('Number of recent posts to include:', 'widgets'); ?> <input type="text" id="<?php echo $this->get_field_id( 'postrows' ); ?>" name="<?php echo $this->get_field_name( 'postrows' ); ?>" size="5" value="<?php echo esc_html($postrows, true); ?>" /></label>
    </p>
		
		<?php
		
	}

} // class Foo_Widget

add_action( 'widgets_init', create_function( '', 'register_widget( "Blogtopics_Expansion_Topic_with_Posts_Widget" );' ) );