<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="<?php echo plugins_url('css/offline-message.css',__FILE__)?>" type="text/css" />
<title>Website Offline</title>
</head>
<?php

echo "
<body>
	<div id='left-bar'>
		<div id='logo'></div>
		<!--<h2 id='topics-head'>Extension Topics</h2>-->";
		echo "<ul class='topics-list'>";
			if (function_exists('cets_get_topics_html')) { cets_get_topics_html(false, false, true, true); }
		echo "</ul>";

		echo "
	</div><!--left bar -->

  <div id='content'> 
 	  <div id='top-bar'></div>
	  <div id='main'>
		";
			echo "<div class='site-title'><h1>"; 
			echo bloginfo('name') . "</h1></div>";
			echo"
	   <!--<h2>Website Offline</h2>-->";
	   
	   