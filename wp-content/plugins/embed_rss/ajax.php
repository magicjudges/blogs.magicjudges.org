<?php
/*  
	File: ajax.php
	Plugin: Embed RSS (CETS)
	Author: Jason Lemahieu
*/

add_action( 'wp_ajax_cets_embedrss_ajax_preview', 'cets_embedrss_ajax_preview');
function cets_embedrss_ajax_preview() {
	header ( "Content-Type: application/json" );
	$shortcode = $_POST['shortcode'];
	$html = do_shortcode($shortcode);
	
	$array_response = array('html'=>$html);
	$response = json_encode($array_response);
	echo $response;
	exit;
}