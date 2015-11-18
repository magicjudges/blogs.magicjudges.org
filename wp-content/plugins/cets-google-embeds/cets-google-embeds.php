<?php
/**
 * Plugin Name: Google oEmbed (CETS)
 * Plugin URI: 
 * Description: Adds Google Drive and Maps to the oEmbed functionality.
 * Version: 1.8.1
 * Author: Jason Lemahieu 
 * Author URI: 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

/* NOTE: This plugin based heavily on the work of Travis Smith and Samuel Wood (Otto) */

/*
	CHANGELOG
	1.8.1
	 - support for /spreadsheetS/ urls
	1.8
	 - support for drive.* in addition to docs.*
	1.6
	 - also handle www.google.com/maps URLs in addition to formerly only maps.google.com
	
*/

add_action('plugins_loaded','cets_wpgo_add_google_maps_docs');
/**
 * Registers The Google Maps & The Google Drive oEmbed handlers.
 * Google Maps & Google Drive does not support oEmbed.
 *
 * @see WP_Embed::register_handler()
 * @see wp_embed_register_handler()
 *
 */
function cets_wpgo_add_google_maps_docs() {
	
	wp_embed_register_handler( 'cets_googlemaps', '!^https?://(www|maps|mapsengine)\.google(\.co|\.com)?(\.[a-z]+)?/.*?(\?.+)!i', 'cets_wpgo_embed_handler_googlemaps' );

	wp_embed_register_handler( 'cets_googledocs', '#https?://(docs|drive).google.com/(document|spreadsheet|spreadsheets|presentation|forms|file|folder)/.*#i', 'cets_wpgo_embed_handler_googledrive' );

}

/**
 * The Google Maps embed handler callback. Google Maps does not support oEmbed.
 *
 * @see WP_Embed::register_handler()
 * @see WP_Embed::shortcode()
 *
 * @param array $matches The regex matches from the provided regex when calling {@link wp_embed_register_handler()}.
 * @param array $attr Embed attributes.
 * @param string $url The original URL that was matched by the regex.
 * @param array $rawattr The original unmodified attributes.
 * @return string The embed HTML.
 */

function cets_wpgo_embed_handler_googlemaps( $matches, $attr, $url, $rawattr ) {
	if ( ! empty( $rawattr['width'] ) && ! empty( $rawattr['height'] ) ) {
		$width  = (int) $rawattr['width'];
		$height = (int) $rawattr['height'];
	} else {
		list( $width, $height ) = wp_expand_dimensions( 425, 326, $attr['width'], $attr['height'] );
	}
	return apply_filters( 'embed_googlemaps', "<iframe width='{$width}' height='{$height}' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='{$url}&output=embed'></iframe><br/><span class='cets-google-map-embed-new-window-link-wrap'><a target='_blank' href='{$url}'>View Larger Map</a></span>" );
}



/**
 * The Google Drive embed handler callback. Google Drive does not support oEmbed.
 * Handles documents, spreadsheets, and presentations from Google Drive.
 *
 * @see WP_Embed::register_handler()
 * @see WP_Embed::shortcode()
 *
 * @param array $matches The regex matches from the provided regex when calling {@link wp_embed_register_handler()}.
 * @param array $attr Embed attributes.
 * @param string $url The original URL that was matched by the regex.
 * @param array $rawattr The original unmodified attributes.
 * @return string The embed HTML.
 */
function cets_wpgo_embed_handler_googledrive( $matches, $attr, $url, $rawattr ) {
	
	//echo "<br>[in handler with url: $url ]<br>";

	if ( !empty($rawattr['width']) && !empty($rawattr['height']) ) {
		$width  = (int) $rawattr['width'];
		$height = (int) $rawattr['height'];
	} else {
		list( $width, $height ) = wp_expand_dimensions( 425, 344, $attr['width'], $attr['height'] );
	}
	
	$original_url = $url;
	$extra = '';
	if ( $matches[1] == 'spreadsheet' ) {
		$url .= '&widget=true';
	} elseif ( $matches[1] == 'document' ) {
		$url .= '?embedded=true';
	} elseif ($matches[1] == 'file' ) {
		//replace usp=sharing with preview
		$url = str_replace( '/edit?usp=sharing', '/preview', $url);
	} elseif ( $matches[1] == 'presentation' ) {
		$url = str_replace( '/pub', '/embed', $url);
		$extra = 'allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"';
	}

	return apply_filters( 'embed_googledrive', "<iframe width='{$width}' height='{$height}' frameborder='0' src='{$url}' {$extra}></iframe><br/><span class='cets-google-doc-embed-new-window-link-wrap'><a href='{$url}' target='_blank'>Open in a new Window</a></span>" );
}


function cets_wp_embed_handler_googleappscal( $matches, $attr, $url, $rawattr ) {
	
	$set = false;
	global $content_width;


	// If the user supplied a fixed width AND height, use it
	if ( !empty($rawattr['width']) && !empty($rawattr['height']) ) {
		$width  = (int) $rawattr['width'];
		$height = (int) $rawattr['height'];
		$set = true;
	} 

	// User specified height but no width
	if (!$set && ( empty($rawattr['width']) && !empty($rawattr['height']) )) {
		$height = (int) $rawattr['height'];
		$width = $content_width;
		$set = true;
	}


	$mode = '';

	if ( !empty( $rawattr['mode'] ) ) {
		$supplied_mode = strtolower($rawattr['mode']);

		if ($supplied_mode == 'agenda') {
			$mode = "&mode=AGENDA";
		}

	}

	// User didn't specify shit
	if (!$set) {		
		
		list( $width, $height ) = wp_expand_dimensions(500, 400, $content_width, 1200);
	
	}



	return apply_filters( 'cets_embed_googleappscal', '<iframe src="http://www.google.com/calendar/' . esc_attr($matches[1]) . 'embed?src=' . esc_attr($matches[2]) . $mode . '&ctz="'. esc_attr($matches[3]) .'" style="border: 0" width="' . $width . '" height="' . $height . '" frameborder="0" scrolling="no"></iframe>', $matches, $attr, $url, $rawattr );
}
wp_embed_register_handler( 'cets_embed_googleappscal', '#https?://www.google.com/calendar/(.*?)embed\?src=(.*?)&ctz=(.*?)#i', 'cets_wp_embed_handler_googleappscal' );