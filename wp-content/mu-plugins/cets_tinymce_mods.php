<?php
/**
 Plugin Name: CETS TinyMCE Mods
 Plugin URI: 
 Description: Adds additional TinyMCE buttons
 Version: 1.0.1
 Author: Jason Lemahieu
 Author URI: 

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action("admin_init","cets_tinymce_mods_buttons_setup");

function cets_tinymce_mods_buttons_setup() {
	//only if editing permissions do we bother
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) return;

	if ( get_user_option('rich_editing') == 'true') {
		add_filter('mce_buttons', 'cets_tinymce_mods_add_buttons');
	}
}

function cets_tinymce_mods_add_buttons($buttons) {
   array_push($buttons, "anchor");
   return $buttons;
}
?>