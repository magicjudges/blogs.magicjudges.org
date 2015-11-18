<?php

$wppath = str_replace("wp-content/plugins/cets-enable-document-replace/popup.php", "", __FILE__);

require_once($wppath . "wp-load.php");
require_once($wppath . "wp-admin/admin.php");

if (!current_user_can('upload_files'))
	wp_die(__('You do not have permission to upload files.', 'enable-document-replace'));

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));

global $wpdb;

$table_name = $wpdb->prefix . "posts";

$sql = "SELECT guid, post_mime_type FROM $table_name WHERE ID = {$_GET["attachment_id"]}";

list($current_filename, $current_filetype) = mysql_fetch_array(mysql_query($sql));

$current_filename = substr($current_filename, (strrpos($current_filename, "/") + 1));


?><html>
<head>
	<title><?=__("Replace media upload")?></title>
	
<link rel='stylesheet' href='<?=get_bloginfo("wpurl");?>/wp-admin/css/global.css?ver=20081210' type='text/css' media='all' />
<link rel='stylesheet' href='<?=get_bloginfo("wpurl");?>/wp-admin/wp-admin.css?ver=20081210' type='text/css' media='all' />
<link rel='stylesheet' href='<?=get_bloginfo("wpurl");?>/wp-admin/css/colors-fresh.css?ver=20081210' type='text/css' media='all' />
<link rel='stylesheet' href='<?=get_bloginfo("wpurl");?>/wp-admin/css/media.css?ver=20081210' type='text/css' media='all' />
<style>
	#icon-upload { background: none; }
</style>
</head>
<body id="media-upload">
<div class="wrap">
		<div id="icon-upload" class="icon32"><br /></div>
	<h2><?=__("Replace Media Upload", "enable-document-replace")?></h2>
	
	<form enctype="multipart/form-data" method="post" action="<?=get_bloginfo("wpurl") . "/wp-content/plugins/cets-enable-document-replace/upload.php"?>">
		<input type="hidden" name="ID" value="<?=$_GET["attachment_id"]?>" />
		<div id="message" class="updated fade"><p><?=__("NOTE: You are about to replace the media file", "enable-document-replace")?> "<?=$current_filename?>". <?=__("There is no undo. Think about it!", "enable-document-replace")?></p></div>
	
		<p><?=__("Choose a file to upload from your computer", "enable-document-replace")?></p>
	
		<input type="file" name="userfile" />

		
		<input type="submit" class="button" value="<?=__("Upload", "enable-document-replace")?>" /> <a href="#" onClick="window.close();"><?=__("Cancel", "enable-document-replace")?></a>

	</form>
</div>
</body>
</html>