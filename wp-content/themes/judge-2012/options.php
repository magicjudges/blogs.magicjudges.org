<?php
/* Options for the Options Framework plugin */

function optionsframework_option_name() {
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = 'optionsframework';
	update_option('optionsframework', $optionsframework_settings);
}

function optionsframework_options() {

	global $cets_wpmubt;

	$topics = $cets_wpmubt->get_topics();

		$options[] = array ( "name" => "Home",
									"type" => "heading" );
		
		$options[] = array( "name" => "Large Image",
							"desc" => "This is the large image on the Home page. (620x360)",
							"type" => "upload",
							"id" => "large_image_home");
							
		$options[] = array( "name" => "Large Image Caption",
							"desc" => "This text will be overlaid on the Large Image above.",
							"type" => "textarea",
							"id" => "large_image_caption_home");	
							
		$options[] = array( "name" => "Link URL",
							"desc" => "Enter a fully qualified URL here to make the image be a link.",
							"type" => "text",
							"id" => "large_image_url_home");	
	
	
	foreach ($topics as $topic) {
		
		$options[] = array ( "name" => $topic->slug,
									"type" => "heading" );
	
		
	
		$options[] = array( "name" => "Large Image",
							"desc" => "This is the large image on the $topic->topic_name page. (620x360)",
							"type" => "upload",
							"id" => "large_image_" . $topic->slug);
							
		$options[] = array( "name" => "Large Image Caption",
							"desc" => "This text will be overlaid on the Large Image above.",
							"type" => "textarea",
							"id" => "large_image_caption_" . $topic->slug);	
							
		$options[] = array( "name" => "Link URL",
							"desc" => "Enter a fully qualified URL here to make the image be a link.",
							"type" => "text",
							"id" => "large_image_url_" . $topic->slug);	
	
							
		$options[] = array( "name" => "Side Image",
							"desc" => "This is the tall image used on the home page. (140x320)",
							"type" => "upload",
							"id" => "side_image_" . $topic->slug);					
	
	}

	return $options;
}