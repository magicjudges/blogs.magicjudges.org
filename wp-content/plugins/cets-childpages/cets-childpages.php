<?php /*
Plugin Name: CETS Child Pages
Plugin URI: 
Description: Adds a TinyMCE button and shortcode to display Child pages of current page in a html list with links to pages
Version: 1.0
Author: Jason Lemahieu
Author URI: 

*/
function function_cets_childpages_addbuttons() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "add_function_cets_childpages_tinymce_plugin");
     add_filter('mce_buttons', 'register_function_cets_childpages_button');
   }
}
 
function register_function_cets_childpages_button($buttons) {

   array_push($buttons, "separator", "js_cets_childpages");
   return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function add_function_cets_childpages_tinymce_plugin($plugin_array) {
	
   $plugin_array['js_cets_childpages'] = plugins_url('tinymce/editor_plugin.js',__FILE__);
   return $plugin_array;
}
 
 
// init process for button control
add_action('init', 'function_cets_childpages_addbuttons');



function function_cets_childpages_admin() {
	?>
	<script type="text/javascript" src="<?php echo plugins_url('js/plugin_javascript.js',__FILE__)?>" /></script>
	<?php
}


// Add the js to admin page's Head section
add_action('admin_head', 'function_cets_childpages_admin');




function function_cets_childpages_do_shortcode($atts) {
	if( is_feed() )
		return '';
	
	
	extract(shortcode_atts(array(
		'heading' => 'More Information',
		'depth' => 1,
		'child_of' => get_the_ID(),
	), $atts));

	$args['echo'] = 0;
	$args['title_li'] = '';
	$args['depth'] = $depth;
	$args['child_of'] = $child_of;
	
	unset($args['link_before']);
	unset($args['link_after']);
	
	//check for no children
	$num_kids = count(get_pages($args));
	if ($num_kids == 0) {return '';}
	
	$html = wp_list_pages($args);
	
	// Remove the classes added by WordPress
	$html = preg_replace('/( class="[^"]+")/is', '', $html);
	
	$prepages = '<div class="cets-childpages"><h3>' . $heading . '</h3><ul>';
	
	$postpages = '</ul></div>';

	return $prepages . $html . $postpages;


}

add_shortcode('list-children', 'function_cets_childpages_do_shortcode');