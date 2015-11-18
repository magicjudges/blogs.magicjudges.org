<?php
/*
Plugin Name: Enable Document Replace
Plugin URI: 
Description: Enable replacing certain files (based on type) by uploading a new file in the "Edit Media" section of the WordPress Media Library. 
Version: 1.1
Author: Jason Lemahieu
Author URI: 

NOTE: This plugin is based HEAVILY off of Enable Media Replace (http://wordpress.org/extend/plugins/enable-media-replace/), by Måns Jonasson.  
	  It is more restrictive and has been crafted for use in a WPMU environment.

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html
 
TODO:
ensure unique name space
translate strings

*/

require_once("cets-enable-document-replace-functions.php");

add_action( 'init', 'enable_document_replace_init' );
add_filter('attachment_fields_to_edit', 'enable_document_replace', 10, 2);





// Initialize this plugin. Called by 'init' hook.
function enable_document_replace_init() {
	load_plugin_textdomain( 'enable-document-replace', false, dirname( plugin_basename( __FILE__)  )  );
	}

function enable_document_replace( $form_fields, $post ) {
		
	if (isset($_GET['attachment_id']) && $_GET["attachment_id"]) {
			
		//if it has a valid mime type, make sure it's one we allow replacing.
		if (!cedr_is_allowed_mime_type(cedr_get_mime_type_by_post_id($_GET["attachment_id"]))) {
			return $form_fields;
		}
		if (!cedr_is_allowed_extension(cedr_get_extension_by_post_id($_GET["attachment_id"]))) {
			return $form_fields;
		}
		

		
		$popupurl = plugins_url("popup.php?attachment_id={$_GET["attachment_id"]}", __FILE__);
				
		$link = "href=\"#\" onclick=\"window.open('$popupurl', 'enable_document_replace_popup', 'width=500,height=500');\"";
		$form_fields["enable-media-replace"] = array("label" => __("Replace document", "enable-document-replace"), "input" => "html", "html" => "<p><a $link>" . __("Upload a new file", "enable-document-replace") . "</a></p>", "helps" => __("To replace the current file, click the link and upload a replacement.", "enable-document-replace"));
	}
	return $form_fields;
}



?>
