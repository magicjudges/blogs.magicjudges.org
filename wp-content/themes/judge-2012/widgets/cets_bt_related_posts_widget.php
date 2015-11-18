<?php

	// check for sidebar existance
	if ( !function_exists('wp_register_sidebar_widget') || !function_exists('wp_register_widget_control') )
		return;

	// Check for the required blog topic class
	if ( !class_exists('cets_blog_topics') )
		return;
	
	$widget_title = "Blog Topics - Recent Posts";
	$widget_description = "Show a linked list of related (by topic) recent posts ordered by most recent activity.";

	// This saves options and prints the widget's config form.
	function fyi_widget_cets_bt_recent_posts_control() {
		global $blog_id;
		
		// Get the list of used topics
		$topics = cets_get_used_topics();
		$thistopic =  cets_get_topic_id_from_blog_id($blog_id);
		
		$options = get_option('widget_cets_bt_recent_posts');
		
		// this section sets defaults
		if ( !is_array($options) ) {
			$options = array('posts_title'=> 'Recent Posts',
			 'postrows' => '5',
			 'topicid' => $thistopic);
			
		}
		// let's try to manually set this option	 
		update_option('widget_cets_bt_recent_posts', $options);	 
		
		
			 
		// here we set the options to whatever was posted		
		if ( isset($_POST['cets_bt_recent_posts_submit']) && $_POST['cets_bt_recent_posts_submit'] ) {
			$options['posts_title'] = strip_tags(stripslashes($_POST['cets_bt_posts_title']));
			$options['postrows'] = strip_tags(stripslashes($_POST['cets_bt_postrows']));
			$options['topicid'] = $_POST['cets_topic_id'];
			
		}
		
		// Update the options
		update_option('widget_cets_bt_recent_posts', $options);
		

	?>
	 <strong>Network-wide Recent Posts</strong>
     <p>
     <label for="cets_bt_p_title"><?php _e('Title:', 'widgets'); ?> <input type="text" id="cets_bt_posts_title" name="cets_bt_posts_title" value="<?php echo esc_html($options['posts_title'], true); ?>" /></label>
	 </p>
	 <p>
     <label for="cets_bt_postrows"><?php _e('Number of recent posts to include:', 'widgets'); ?> <input type="text" id="cets_bt_postrows" name="cets_bt_postrows" size="5" value="<?php echo esc_html($options['postrows'], true); ?>" /></label>
    </p>
	<p>
		
	<label for="cets_topic_id">Select the topic for Recent Posts:<br/>
	<select name="cets_topic_id" size="1" id="cets_topic_id">
		<?php foreach ($topics as $topic){
			?> <option value="<?php echo $topic->id; ?>" <?php
			
			if ($options['topicid'] == $topic->id) { 
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
	
	<input type="hidden" name="cets_bt_recent_posts_submit" id="cets_bt_recent_posts_submit" value="TRUE" />
				
	<?php
	}

	// This prints the widget
	function fyi_widget_cets_bt_recent_posts($args) {
		global $blog_id;
		$this_id = $blog_id;
		extract($args);
		$options = get_option('widget_cets_bt_recent_posts');
		$topic_id = $options['topicid'];
		$topic = cets_get_topic($topic_id);
		$postrows_default = 10; // we'll default to 10 otherwise the query gets too huge
		
		//$title = !isset($options['title']) == 0 ? $widget_title : $options['title'];
		
		if (isset($options['title'])) {
			$title = $options['title'];
		}  else {
			$title = "Latest Updates";
		}
		
		// check to see if they added a number for the postrows to include
		if (strlen($options['postrows']) == 0) {
			// if no number, set it to the default 
			$options['postrows'] = $postrows_default;
		}
		
		// if they entered something that's not a number, set it to 5
		if (!$options['postrows'] == (int) $options['postrows']) {
			$postrows = 5;
		}
		else {
			// otherwise, let them choose how many
			$postrows = $options['postrows'];
		}
		

			if (isset($topic->short_name) && $topic->short_name) {
				$display_name = $topic->short_name;
			} else {
				$display_name = $topic->topic_name;
			}
		
		  
		
		// the network-wide recent posts
			echo $before_widget . $before_title . $options['posts_title']  . $after_title;
			echo ("<ul>");
			cets_get_recent_posts_from_topic_id_html($topic_id, $postrows, $this_id);
			echo ("</ul>");
			echo ("<div class='topicMore'>");
			echo ("<a href='/topic/" . strtolower($topic->slug) . "'>More " . $displayname . " Updates</a>");
			echo ("</div>");
			echo $after_widget;
		
		
		

	}


	// Tell Dynamic Sidebar about our new widget and its control
	$widget_ops = array('classname' => 'widget_cets_bt_recent_posts', 'description' => __( "$widget_description") );
	wp_register_sidebar_widget('widget_cets_bt_recent_posts', $widget_title, 'fyi_widget_cets_bt_recent_posts', $widget_ops);
	wp_register_widget_control('widget_cets_bt_recent_posts', $widget_title, 'fyi_widget_cets_bt_recent_posts_control' );


