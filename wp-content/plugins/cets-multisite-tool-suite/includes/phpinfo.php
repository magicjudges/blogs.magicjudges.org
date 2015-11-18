<?php

/*** PHP INFO ******************************************************************************************************************************************************************* PHP INFO *** */
function cesuite_page_php_info() {
	_cesuite_page_head('CESuite - PHP Info');
	
	ob_start();
	phpinfo();
	$pinfo = ob_get_contents();
	ob_end_clean();
	 
	$pinfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);
	echo "<div id='cesuite-phpinfo'>$pinfo</div>";
	
	_cesuite_page_foot();
}