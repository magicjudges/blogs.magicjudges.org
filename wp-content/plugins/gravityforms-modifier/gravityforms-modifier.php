<?php
/*
Plugin Name: Gravity Forms Modifier
Plugin URI: 
Description: Removes advanced and dangerous form elements from a gravity forms install
Author: Jason Lemahieu
Version: 1.4
Author URI: 
*/   


add_filter("gform_add_field_buttons", "remove_fields");
function remove_fields($field_groups){

	if (!is_super_admin()) {

		$index = 0;
		$post_field_index = -1;
		$advanced_field_index = -1;
		$pricing_field_index = -1;
	
		//Finding group indexes
		foreach($field_groups as $group){
			
			switch ($group['name']) {
				case 'post_fields':
					$post_field_index = $index;
					break;
				case 'advanced_fields':
					$advanced_field_index = $index;
					break;
				case 'pricing_fields':
					$pricing_field_index = $index;
					break;
			}
	
			$index ++;
		}
	
		//removing file upload field
		if($advanced_field_index >=0){
			$file_upload_index = -1;
			$index = 0;
			foreach($field_groups[$advanced_field_index]["fields"] as $advanced_field){
	
				if($advanced_field["value"] == "File Upload")
					$file_upload_index = $index;
				$index++;
			}
	
			unset($field_groups[$advanced_field_index]["fields"][$file_upload_index]);
		}
	
		//removing post field group
		if($post_field_index >= 0)
			unset($field_groups[$post_field_index]);
			
		//removing Pricing fields
		if ($pricing_field_index >= 0) {
			unset($field_groups[$pricing_field_index]);
		}
		
	} // !is_super_admin
			
    return $field_groups;
}

// roles and capabilities
if (is_admin()) {
	add_filter('user_has_cap', 'ces_gravity_forms_modifier_capabilities', 9, 3);
}
function ces_gravity_forms_modifier_capabilities($allcaps, $cap, $args) {
	remove_filter("user_has_cap", array("RGForms", "user_has_cap"), 10, 3);

	if (is_super_admin()) {
		return $allcaps;
	} else {
		$allcaps['gform_full_access'] = false;
		
		$allcaps['gravityforms_create_form'] = false;
		$allcaps['gravityforms_delete_forms'] = false;
		$allcaps['gravityforms_edit_forms'] = false;		
		$allcaps['gravityforms_edit_settings'] = false;
		$allcaps['gravityforms_uninstall'] = false;
		$allcaps['gravityforms_view_settings'] = false;
		$allcaps['gravityforms_addon_browser'] = false;
		$allcaps['gravityforms_view_updates'] = false;		
		$allcaps['gravityforms_view_entries'] = true;
		$allcaps['gravityforms_edit_entries'] = true;
		$allcaps['gravityforms_view_entry_notes'] = true;
		$allcaps['gravityforms_edit_entry_notes'] = true;
		$allcaps['gravityforms_delete_entries'] = true;
	}	
	
	return $allcaps;
}



//remove help link
function ces_gravity_forms_modifier_remove_menus() {

	remove_submenu_page('gf_entries', 'gf_help');
	
}
add_action('admin_menu', 'ces_gravity_forms_modifier_remove_menus', 999 );

