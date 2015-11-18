<?php
/*
Plugin Name: Advanced Sorting Options (CETS)
Plugin URI: 
Description: This plugin lets choose from a variety of sorting options for your archives
Author: Jason Lemahieu, Chrissy Dillhunt
Version: 0.3
Author URI: 

/* Add Settings Field to reading section */
function cets_advanced_sorting_add_settings() {
	if (is_super_admin()) {
		/* add_settings_section( $id,
											$title // displayed on page
											$callback // displays the section header
											$page);  // what page this is on. general, reading, writing, media, etc. */
											
		add_settings_section('cets_advanced_sorting_section', 
											'Advanced Sorting Options', 
											'cets_advanced_sorting_options_settings_section', 
											'reading');
		

		add_settings_field('cets_advanced_sorting_field', 
										'Sort Archives By', 
										'cets_advanced_sort_by_field_render', 
										'reading', 
										'cets_advanced_sorting_section');
		
		/*add_settings_field('cets_advanced_sorting_custom_field',
									'Custom Field:',
									'cets_advanced_sort_by_field_custom_render',
									'reading',
									'cets_advanced_sorting_section');*/
		
		/* register_setting('group-name', 
								'name-in-db')
								'sanitize_callback'; */
								
		register_setting('reading',
							'cets_advanced_sorting_options',
							'cets_advanced_sorting_options_sanitizer');
	
		}
}

	add_action('admin_init', 'cets_advanced_sorting_add_settings');

function cets_advanced_sorting_options_sanitizer( $input ) {
	//sort_archives_by	
	$sort_options = cets_advanced_sorting_get_sort_options();
	/*
	$valid_sort_archives_by = false;
	foreach ($sort_options as $sort_option) {
			if ($sort_option['id'] == $input['sort_archives_by']) {
				$valid_sort_archives_by = true;
			}
	}
	
	if (!$valid_sort_archives_by) {
		$input['sort_archives_by'] = '';
	}
	*/
	return $input;
}

function cets_advanced_sort_by_field_render() {
	$options = get_option('cets_advanced_sorting_options', cets_advanced_sort_by_get_defaults()); 

	

	
	echo "<table id='advanced-sorting-table-types' class='widefat'><tr><th>Post Type</th><th>Order By</th><th>Order</th><th>Labels</tr>";
	
	$post_types = get_post_types();
	$invalid_types = array('page','attachment','nav_menu_item','revision','view','view-template','wp-types-group');
	foreach( $post_types as $post_type ) {
		if (!in_array($post_type, $invalid_types)) {
			
			//$type = get_post_type($post_type);
			
			echo "<tr>";
			echo "<td>$post_type</td>";
			
			//sort by
			echo "<td>";
				cets_advanced_sort_by_input_sort_by('post_type', $post_type, $options['post_type'][$post_type]['orderby']); 
				echo "<br/>";
				cets_advanced_sort_by_input_custom_field('post_type', $post_type,  $options['post_type'][$post_type]['custom']);
			echo "</td>";
			
			//order
			echo "<td>";
				cets_advanced_sort_by_input_order('post_type', $post_type,  $options['post_type'][$post_type]['order']); 
			echo "</td>";
			
			//labels
			echo "<td>";
				cets_advanced_sort_input_prev_label('post_type', $post_type, $options['post_type'][$post_type]['prev_label']);
				echo "<br/>";
				cets_advanced_sort_input_next_label('post_type', $post_type, $options['post_type'][$post_type]['next_label']);
			echo "</td>";
			
			echo "</tr>";
		}
	}
	
	echo "</table>";
	
	echo "<p>&nbsp;</p>";
	
	echo "<table id='advanced-sorting-table-types' class='widefat'><tr><th>Taxonomy</th><th>Sort By</th><th>Order</th><th>Labels</th></tr>";
	
	
	$taxes = get_taxonomies();
	$invalid_taxes = array('post_format','link_category','nav_menu');
	foreach( $taxes as $tax ) {
		if (!in_array($tax, $invalid_taxes)) {
			
			//$type = get_post_type($post_type);
			
			echo "<tr>";
			echo "<td>$tax</td>";
			
			//sort by
			echo "<td>";
				cets_advanced_sort_by_input_sort_by('tax', $tax, $options['tax'][$tax]['orderby']); 
				echo "<br/>";
				cets_advanced_sort_by_input_custom_field('tax', $tax,  $options['tax'][$tax]['custom']);
			echo "</td>";
			
			//order
			echo "<td>";
				cets_advanced_sort_by_input_order('tax', $tax,  $options['tax'][$tax]['order']); 
			echo "</td>";
			
			//labels
			echo "<td>";
				cets_advanced_sort_input_prev_label('tax', $tax, $options['tax'][$tax]['prev_label']);
				echo "<br/>";
				cets_advanced_sort_input_next_label('tax', $tax, $options['tax'][$tax]['next_label']);
			echo "</td>";
			
			echo "</tr>";
		}
	}
	
	echo "</table>";
	
	

	
	
}

function cets_advanced_sort_input_prev_label($form_type, $name, $value='') {
	$sort_options = cets_advanced_sorting_get_sort_options();
	
	echo "Previous Label: <input type='text' name='cets_advanced_sorting_options[" . $form_type . "][" . $name . "][prev_label]' value='" . esc_html($value) . "'>"; 
	
}
function cets_advanced_sort_input_next_label($form_type, $name, $value='') {
	$sort_options = cets_advanced_sorting_get_sort_options();
	echo "Next Label: <input type='text' name='cets_advanced_sorting_options[" . $form_type . "][" . $name . "][next_label]' value='" . esc_html($value) . "'>"; 
}


function cets_advanced_sort_by_input_sort_by($form_type, $name, $value) {
	
	$sort_options = cets_advanced_sorting_get_sort_options();
	
	echo "<select name='cets_advanced_sorting_options[" . $form_type . "][" . $name . "][orderby]' >";
	
	foreach($sort_options as $sort_option) {
		echo "<option value='" . $sort_option['id'] . "' ";
			if ($sort_option['id'] == $value) {	 
				echo " selected='selected' ";
			}
		
		echo ">";
		echo $sort_option['readable'];
		echo "</option>";
	}

	echo "</select>";
}

function cets_advanced_sort_by_input_order($form_type, $name, $value='') {
	
	
	echo "<select name='cets_advanced_sorting_options[" . $form_type . "][" . $name . "][order]' >";
	
	?>
	<option value='' <?php if ($value == '') { echo " selected='selected' "; }?>>Default </option>
	<option value='ASC' <?php if ($value == 'ASC') { echo " selected='selected' "; }?>>ASC</option>
	<option value='DESC' <?php if ($value == 'DESC') { echo " selected='selected' "; }?>>DESC</option>

	</select><?php
}

function cets_advanced_sort_by_input_custom_field($form_type, $name, $value) {
	echo "Field: <input type='text' name='cets_advanced_sorting_options[" . $form_type . "][" . $name . "][custom]' value='" . esc_html($value) . "'>"; 
}
	

	
function cets_advanced_sorting_options_settings_section() {
	/* TODO: do i want a section header? */
}



function cets_advanced_sorting_get_sort_options() {
	$options = array();
	
		$options[] = array(
				'readable' => "Default",
				'id' => ""
			);
	
	$options[] = array(
				'readable' => "Post Title",
				'id' => "title"
			);
					
	$options[] = array(
				'readable' => "Post Slug",
				'id' => "name"
			);
			
		$options[] = array(
			'readable' => "Custom Field",
			'id' => 'custom'
		);
		
	return $options;
}

function cets_advanced_sort_by_get_defaults() {
	$options = array();
	// TODO
	$options_per_type = array();
	$options_per_type['order'] = '';
	$options_per_type['orderby'] = '';
	$options_per_type['custom'] = '';
	
	$options['post'] = '';
	
	return $options;
}

function cets_advanced_sorting_do_sort( $query ) {
	

		if (is_admin()) { return; }
		if (!$query->is_main_query()) { return; }
		
		$options = get_option('cets_advanced_sorting_options', cets_advanced_sort_by_get_defaults());

		if (is_category()) {
			// *special* taxonomy
			$tax_options = $options['tax']['category'];
			cets_advanced_sorting_modify_query($query, $tax_options);
			cets_advanced_sorting_modify_next_prev_labels($tax_options);
		}
		
		if (is_tax()) {
		
			$tax_options = $options['tax'];
			foreach ($tax_options as $option => $key) {
								
				if (is_tax($option)) {
					cets_advanced_sorting_modify_query($query, $key);
					cets_advanced_sorting_modify_next_prev_labels($key);
					//labels
					
				}
			}
		}
		
		if (is_post_type_archive()) {
			
			$type_options = $options['post_type'];
			foreach ($type_options as $option => $key) {
				if (is_post_type_archive($option)) {
					cets_advanced_sorting_modify_query($query, $key);
					cets_advanced_sorting_modify_next_prev_labels($key);
					//check labels
					
				}
			}
		}
}

add_action( 'pre_get_posts', 'cets_advanced_sorting_do_sort');

function cets_advanced_sorting_modify_query($query, $option_set) {
	
	switch ($option_set['orderby']) {
						case 'custom':
							$query -> set ('orderby', 'meta_value' );
							$query -> set ('meta_key', $option_set['custom']);
							break;
						
						default:
							$query -> set ('orderby', $option_set['orderby']);
							break;
					}

			$query -> set ('order', $option_set['order']);
	
	return $query;
}

function cets_advanced_sorting_modify_next_prev_labels($option_set) {
		
	if (isset($option_set['prev_label']) && $option_set['prev_label']) {
		add_filter( 'ces_prev_posts_link_text', 'cets_advanced_sorting_filter_prev_label');  //todo what function
		global $cets_advanced_sorting_prev_label;
		$cets_advanced_sorting_prev_label = $option_set['prev_label'];
	}
	
	if (isset($option_set['next_label']) && $option_set['next_label']) {
		add_filter( 'ces_next_posts_link_text', 'cets_advanced_sorting_filter_next_label');  //todo what function
		global $cets_advanced_sorting_next_label;
		$cets_advanced_sorting_next_label = $option_set['next_label'];
	}
}
function cets_advanced_sorting_filter_prev_label($label) {
	global $cets_advanced_sorting_prev_label;
	if (isset($cets_advanced_sorting_prev_label) && $cets_advanced_sorting_prev_label) {
		return $cets_advanced_sorting_prev_label;
	} else {
		return $label;
	}	
}
function cets_advanced_sorting_filter_next_label($label) {
	global $cets_advanced_sorting_next_label;
	if (isset($cets_advanced_sorting_next_label) && $cets_advanced_sorting_next_label) {
		return $cets_advanced_sorting_next_label;
	} else {
		return $label;
	}	
}


/*
function cets_advanced_sorting_up_me_maybe() {
	$last_v = get_option('cets_advanced_sorting_v', 0);
	
	$plugin_data = get_plugin_data( __FILE__, false );
	$curr_v = $plugin_data['Version'];
	
	
	if (version_compare($last_v, $curr_v, '<')) {
	
		update_option( 'cets_advanced_sorting_v', $curr_v );
	}
	
	
}
add_action( 'admin_init', 'cets_advanced_sorting_up_me_maybe' );
*/
