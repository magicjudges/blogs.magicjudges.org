<?php
/*
Registers, displays, and processes settings code.

Settings: 
 1) Site / Default image (string)

*/

add_action('admin_init', 'cets_og_settings_init');

function cets_og_settings_init() {

	add_settings_section('cets_og_settings_section',
		'Open Graph Settings',
		'cets_og_settings_section_callback',
		'media');

	add_settings_field('cets_og_default_image_source',
		'Default Image URL',
		'cets_og_default_image_setting_callback',
		'media',
		'cets_og_settings_section');

	
	register_setting('media', 'cets_og_default_image_source');
}

function cets_og_settings_section_callback() {
		echo "<p>Open graph improves the quality of your links on social media sites such as Google+ and Facebook by helping to display the most appropriate image.</p>";
	}

function cets_og_default_image_setting_callback() {
	$default_image_source = get_option('cets_og_default_image_source', '');

	echo "<input type='text' name='cets_og_default_image_source' size='80' value='" . sanitize_text_field($default_image_source) . "'>";
	echo "<p>To enter a custom default image, upload it to your <a target='_blank' href='" . admin_url('media-new.php') . "'>Media Library</a> and then paste the image's URL here. This setting requires that you upload a square image of 200px x 200px. The image will be displayed when no image is otherwise available.";

	if ($default_image_source == '') {
		$default_image_source = cets_og_get_network_default_image();
	}

	if ($default_image_source) {
		echo "<div class='cets-og-default-image-preview'><p>This is what your default image will look like:</p><img style='border:1px solid black;' width='200' height='200' src='" . esc_html($default_image_source) . "'></div>";
	}	


}