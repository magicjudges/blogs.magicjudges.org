<?php
/*

Plugin Name: Image Magic (CETS)
Description: Automatically displays galleries and image links with a lightbox effect
Version: 0.3
Author: Jason Lemahieu
Author URI: http://madtownlems.wordpress.com

*/

/*
	CHANGELOG
	0.3 - don't apply when target = _blank
	0.2 - use caption if provided

*/

add_action( 'wp_enqueue_scripts', 'cets_image_magic_enqueue_scripts' );

function cets_image_magic_enqueue_scripts() {
	wp_register_script( 'cets-image-magic', plugins_url( '/includes/imagelightbox/js/imagelightbox.min.js', __FILE__ ), array('jquery') );
	wp_enqueue_script( 'cets-image-magic' );

	wp_register_script( 'cets-image-magic-addons', plugins_url( '/includes/imagelightbox/js/imagelightbox-addons.js', __FILE__ ), array('jquery') );
	wp_enqueue_script( 'cets-image-magic-addons' );

	wp_register_style( 'cets-image-magic', plugins_url( '/includes/imagelightbox/css/imagelightbox.css', __FILE__) );
	wp_enqueue_style( 'cets-image-magic' );
}



add_filter( 'the_content', 'cets_image_magic_add_links_lightbox_selector' );
function cets_image_magic_add_links_lightbox_selector($content) {
	
	$selector = 'lightbox';

	preg_match_all('/<a(.*?)href=(?:\'|")([^<]*?).(bmp|gif|jpeg|jpg|png)(?:\'|")(.*?)>/i', $content, $links);

	if(isset($links[0])) {
	
		foreach($links[0] as $id => $link) {
		
				
			//don't do this for images that open in new tabs
			if ( !(strpos( $link, '_blank' ) === false) ) {
				continue;
			}

			if(preg_match('/<a.*?rel=(?:\'|")(.*?)(?:\'|").*?>/', $link, $result) === 1) {
				
				$content = str_replace($link, preg_replace('/rel=(?:\'|")(.*?)(?:\'|")/', 'rel="' . $selector . '">"', $link), $content);
				
			} else {

				$content = str_replace($link, '<a'.$links[1][$id].'href="'.$links[2][$id].'.'.$links[3][$id].'"'.$links[4][$id].' rel="'. $selector .'">', $content);
			}
		}

		return $content;
	}

	return $content;
}


add_filter('wp_get_attachment_link', 'cets_image_magic_add_gallery_lightbox_selector', 1000, 6);
function cets_image_magic_add_gallery_lightbox_selector($link, $id, $size, $permalink, $icon, $text) {

	$selector = "lightbox";

	$link = (preg_match('/<a.*? rel=("|\').*?("|\')>/', $link) === 1 ? preg_replace('/(<a.*? rel=(?:"|\').*?)((?:"|\').*?>)/', '$1 '.$selector.'$2', $link) : preg_replace('/(<a.*?)>/', '$1 rel="'.$selector.'">', $link));

	return (preg_match('/<a.*? href=("|\').*?("|\')>/', $link) === 1 ? preg_replace('/(<a.*? href=(?:"|\')).*?((?:"|\').*?>)/', '$1'.wp_get_attachment_url($id).'$2', $link) : preg_replace('/(<a.*?)>/', '$1 href="'.wp_get_attachment_url($id).'">', $link));
}

