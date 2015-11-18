<?php

require_once("cets-enable-document-replace-functions.php");

$wppath = str_replace("wp-content/plugins/cets-enable-document-replace/upload.php", "", __FILE__);

require_once($wppath . "wp-load.php");
require_once($wppath . "wp-admin/admin.php");


if (!current_user_can('upload_files'))
	wp_die(__('You do not have permission to upload files.'));
	
global $wpdb;

// Define DB table names
$table_name = $wpdb->prefix . "posts";
$postmeta_table_name = $wpdb->prefix . "postmeta";

// Get old guid and filetype from DB
$sql = "SELECT guid, post_mime_type FROM $table_name WHERE ID = {$_POST["ID"]}";
list($current_filename, $current_filetype) = mysql_fetch_array(mysql_query($sql));

// Massage a bunch of vars
$current_guid = $current_filename;
$current_filename = substr($current_filename, (strrpos($current_filename, "/") + 1));

$current_file = get_attached_file($_POST["ID"], true);
$current_path = substr($current_file, 0, (strrpos($current_file, "/")));
$current_file = str_replace("//", "/", $current_file);
$current_filename = basename($current_file);


//$replace_type = $_POST["replace_type"];
// We have two types: replace / replace_and_search

if (is_uploaded_file($_FILES["userfile"]["tmp_name"])) {
	$new_filename = $_FILES["userfile"]["name"];
	$new_filetype = $_FILES["userfile"]["type"];
	$new_filesize = $_FILES["userfile"]["size"];


		//confirm same extension			
		$pattern = "/\.[a-zA-Z]{1,5}$/";
		preg_match($pattern, $current_filename, $current_extension);
		preg_match($pattern, $new_filename, $new_extension);

		//DEBUG
		print "<!--";
		print "Debugging Info:<br>";
		print "current_extension[0]: " . $current_extension[0] . "<br>";
		print "new_extension[0]: " . $new_extension[0] . "<br>";
		print "new_filetype: " . $new_filetype . "<br>"; 
		print "-->";

		//confirm new file is NOT an INVALID mimetype
		if (!cedr_is_allowed_mime_type($new_filetype)) {
			//invalid mimetype
			cedr_die_with_message("You cannot upload documents of that mime type.");
		}
		
		if (!cedr_is_allowed_extension($current_extension[0])) {
			cedr_die_with_message("You cannot replace files of the type you are trying to.");
		}

		if (!cedr_is_allowed_extension($new_extension[0])) {
			cedr_die_with_message("You cannot replace a file with a file of the type you are trying to.");
		}
			
		if(strtolower($current_extension[0]) != strtolower($new_extension[0])) {
			cedr_die_with_message("File extensions do not match.  You may only replace the document with one of the same type.");
		}

		//PERFORM REPLACE

		// Delete old file
		unlink($current_file);
		
		// Move new file to old location/name
		move_uploaded_file($_FILES["userfile"]["tmp_name"], $current_file);
		
		// Chmod new file to 644
		chmod($current_file, 0644);
		
		// Make thumb and/or update metadata
		wp_update_attachment_metadata( $_POST["ID"], wp_generate_attachment_metadata( $_POST["ID"], $current_file ) );
		
	
	
	
	
}
	
?><html><head></head><body><script type="text/javascript">window.close();window.opener.location.reload();</script></body></html>