<?php

function cedr_is_allowed_mime_type($intype) {
	$illegal_types = array(
		"video/x-msvideo",
		"image/bmp",
		"application/x-msdownload",
		//"application/octet-stream",
		"image/gif",
		"text/html",
		"image/jpeg",
		"video/quicktime",
		"video/mpeg",
		"audio/mpeg",
		"audio/x-mpeg-3",
		"audio/mpeg3",
		"audio/x-pn-realaudio",
		"image/tiff",
		"application/x-javascript",
		"application/x-shockwave-flash",
		"audio/x-wav",
		"application/zip",
		"application/x-zip-compressed",
		"application/xhtml+xml",
		"text/xml",
		"application/xml",
		"text/css",
		"text/php",
	);
	
	if (in_array(strtolower($intype), $illegal_types)) {
		return false;
	} else {
		return true;
	}

}

function cedr_is_allowed_extension($inext) {
	$exts = array(
		".doc",
		".docx",
		".pdf",
		".ppt",
		".pptx",
		".rtf",
		".txt",
		".xls",
		".xlsx",
		);
		
	if (in_array(strtolower($inext), $exts)) {
		return true;
	} else {
		return false;
	}
}
function cedr_get_extension_by_post_id($pid) {
	$current_filename = get_attached_file($pid);
	
	$pattern = "/\.[a-zA-Z]{1,5}$/";
	preg_match($pattern, $current_filename, $current_extension);

	return $current_extension[0];
	
	
}
function cedr_get_mime_type_by_post_id($pid) {
	global $wpdb;
	$table_name = $wpdb->prefix . "posts";
	
	
	
	$sql = "SELECT post_mime_type FROM $table_name WHERE ID = {$pid}";
	$results = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
	
	
	
	if (sizeof($results) < 1) {
		return "";
	}
	return $results['post_mime_type'];
	
}

function cedr_die_with_message($error) {
	wp_die(__('<div class="cets-enable-document-replace-error">Error:' . $error . '</div><a href="#" onclick="window.close();">Close Window</a>'));
}