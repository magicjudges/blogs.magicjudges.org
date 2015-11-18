<?php

	// check for sidebar existance
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	// Check for the required blog topic class
	if ( !class_exists('cets_blog_topics') )
			
		return;
	
	$widget_title = "Blog Topics - Topics with Posts";
	$widget_description = "Show a linked list of topics and their most recent posts.";

	// This saves options and prints the widget's config form.
	function fyi_widget_cets_bt_topics_with_posts_control() {
		global $blog_id;
		
		// Get the list of used topics
		$topics = cets_get_used_topics();
		
		
		$options = get_option('widget_cets_bt_topics_with_posts', 'string-that-isnt-an-array');
		
		// this section sets defaults
		if ( !is_array($options) ) {
			$options = array(
			'posts_title'=> 'Latest Posts',
			 'postrows' => '5',
			 'exclude' => array(),
			);
		}
		// let's try to manually set this option	 
		update_option('widget_cets_bt_topics_with_posts', $options);	 
		
		
			 
		// here we set the options to whatever was posted		
		if (isset($_POST['cets_bt_topics_with_posts_submit']) && $_POST['cets_bt_topics_with_posts_submit'] ) {
			$options['posts_title'] = strip_tags(stripslashes($_POST['cets_bttp_posts_title']));
			$options['postrows'] = strip_tags(stripslashes($_POST['cets_bttp_postrows']));
			
			if (isset($_POST['cets_bttp_exclude'])) {
				$options['exclude'] = $_POST['cets_bttp_exclude'];
			} else {
				$options['exclude'] = array();
			}
		}
		
		// Update the options
		update_option('widget_cets_bt_topics_with_posts', $options);
		

	?>
	 <strong>Sitewide Recent Posts</strong>
     <p>
     <label for="cets_bttp_posts_title"><?php _e('Title:', 'widgets'); ?> <input type="text" id="cets_bttp_posts_title" name="cets_bttp_posts_title" value="<?php echo esc_html($options['posts_title'], true); ?>" /></label>
	 </p>
	 <p>
     <label for="cets_bttp_postrows"><?php _e('Number of posts to include:', 'widgets'); ?> <input type="text" id="cets_bttp_postrows" name="cets_bttp_postrows" size="5" value="<?php echo esc_html($options['postrows'], true); ?>" /></label>
    </p>
	<p>
		
	Exclude these topics:<br/>
    
   	
		<?php foreach ($topics as $topic){
			echo('<label for="cets_bttp_exclude_' . $topic->id . '"><input id="cets_bttp_exclude_' . $topic->id . '" name="cets_bttp_exclude[]" type="checkbox" value="' . $topic->id . '"'); 
			if (in_array($topic->id, $options['exclude'])) { echo ' checked="checked" ';}
			echo ('>');
			echo $topic->topic_name;
			echo("</label><br/>");
			
		}
		?>
	</p>	
	
	<input type="hidden" name="cets_bt_topics_with_posts_submit" id="cets_bt_topics_with_posts_submit" value="TRUE" />
				
	<?php
	}

	// This prints the widget
	function fyi_widget_cets_bt_topics_with_posts($args) {
		extract($args);
		$options = get_option('widget_cets_bt_topics_with_posts');
		$exclude = $options['exclude'];
		// check to see that exclude is an array, if not, initialize it
		if(!is_array($exclude)){
			$exclude = array();	
		}
		$postrows_default = 5; // we'll default to 5 otherwise the query gets too huge
		
		//$title = !isset($options['title']) == 0 ? $widget_title : $options['title'];
		
		if (isset($options['title'])) {
			$title = $options['title'];
		} else {
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
		
		// get the topics
		$topics = cets_get_used_topics();
		
		// the sitewide recent posts
			//echo $before_widget . $before_title . $options['posts_title']  . $after_title;
			
					foreach($topics as $topic) {
						if (!in_array($topic->id, $exclude)){ //is not in the array of excluded ones
						
							$option_name = "side_image_" . $topic->slug;
							if (function_exists('of_get_option')) {
								$imageurl = of_get_option(strtolower($option_name), 'none');
							} else {
								$imageurl = '';
							}
							if ($imageurl == 'none' || $imageurl == '') {
								$imageurl = get_template_directory_uri() . "/images/thumbs/" . $topic->slug . ".png";
							}	
						
						if (isset($topic->short_name) && $topic->short_name) {
							$display_name = $topic->short_name;
						} else {
								$display_name = $topic->topic_name;
						}
						
						    echo ("<div class='topicName'><hr><h3 class='yellowHeading'><a href='topic/" . strtolower($topic->slug) . "'>" . $topic->topic_name . "</a></h3></div>");
							echo ("<div class='sideImage'>");					
							echo ("<img src='" . $imageurl . "' alt='" . $topic->slug ." side image' />");
							echo ("</div>");
							echo ("<div class='topicListing " . $topic->slug . "'>");
							echo ("<ul class='siteList'>");
							echo (cets_get_recent_posts_from_topic_id_html($topic->id, $postrows, 0, 1));
							echo ("</ul>");
							echo ("<div class='topicMore'>");
							echo ("<a href='/topic/" . strtolower($topic->slug) . "'>More " . $display_name . " Updates</a>");
							echo ("</div>");
							echo ("</div>");
					
						}// end if
					}
				  
				 



			echo $after_widget;
		
		
		

	}
	
	

	// Tell Dynamic Sidebar about our new widget and its control
	$widget_ops = array('classname' => 'widget_cets_bt_topics_with_posts', 'description' => __( "$widget_description") );
	wp_register_sidebar_widget('widget_cets_bt_topics_with_posts', $widget_title, 'fyi_widget_cets_bt_topics_with_posts', $widget_ops);
	wp_register_widget_control('widget_cets_bt_topics_with_posts', $widget_title, 'fyi_widget_cets_bt_topics_with_posts_control' );
