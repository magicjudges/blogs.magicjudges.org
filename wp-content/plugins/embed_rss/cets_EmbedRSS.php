<?php
/*
Plugin Name: Embed RSS (CETS)
Plugin URI: http://wordpress.org/extend/plugins/embed-rss/
Description: This plugin adds an RSS icon to the Media Row that allows a user to embed an RSS feed into a page
Author: Deanna Schneider & Jason Lemahieu
Version: 3.1


Copyright 2008-2013 UW Board of Regents

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

include_once (dirname (__FILE__)."/cets-lib/cets-plugin-functions.php");

add_action('admin_enqueue_scripts', 'cets_embed_rss_admin_enqueue_scripts');
function cets_embed_rss_admin_enqueue_scripts() {
	wp_enqueue_script( 'cets_embed_rss_ajax_preview', plugin_dir_url(__FILE__) . "js/ajax.js", array( 'jquery', 'json2' ) );
	wp_enqueue_style( 'cets_embed_rss_admin_css', plugin_dir_url(__FILE__) . "css/admin.css" );
}


if (is_admin()) {
	include_once (dirname (__FILE__)."/ajax.php");
}

function cets_embedrss_media_button() {
	
	 if (!cets_embedrss_is_valid_type()) {
	 	return;
	 }

	add_thickbox();
	add_action( 'admin_footer', 'cets_embedrss_thickbox_content' );

	$href = "#TB_inline?width=640&height=525&inlineId=cets_embedRSS";
	$src = plugins_url('img/cets_EmbedRSS-16-16.gif',__FILE__);;

	echo "<a href='{$href}' class='thickbox button cets_embed_rss' title='Embed RSS' id='embed_rss_button'><span class='cets_embedrss_media_button'></span>RSS</a>";
}
add_action('media_buttons', "cets_embedrss_media_button", 21);



function cets_embedrss_thickbox_content() { 
	?>
		<style>#cets_embedrss_preview_wrapper {padding: 4px; border: 1px solid black; margin: 4px; } #cets_embedrss_button_wrapper div.button {margin-left: 5px; margin-right: 5px;} #cets_embedrss_button_wrapper {margin-bottom: 10px;}</style>
		<div id="cets_embedRSS" style="display:none;">
			<div class="wrap">      

				<p>
				<label for="cets_RSS-dialog-input">RSS Feed URL:</label>
				<br />
				<input type="text" id="cets_RSS-dialog-input" size="60"><br/>
				<?php do_action('cets_embedrss_url_field_after'); ?>
				</p>

				<p>
					How many items would you like displayed? 
		            <select name="cets_RSS-dialog-itemcount" id="cets_RSS-dialog-itemcount">
		            	<?php for ($i=0; $i < 11; $i++) {
						if ($i > 0) {
							if ($i == 5) { $selected = " selected=SELECTED "; } else { $selected = " "; }
							echo('<option ' . $selected . ' value="' . $i . '">' . $i . "</option>");
						} else {
							echo ('<option value="0">Include all</option>');
						}
					}
		           ?>
				   </select>
				</p>

				<p>
				<input type="checkbox" id="cets_RSS-dialog-itemcontent" value="1" /> <label for="cets_RSS-dialog-itemcontent">Include Content</label><br/>
				<input type="checkbox" id="cets_RSS-dialog-itemauthor" value="1"  /> <label for="cets_RSS-dialog-itemauthor">Include Author</label><br/>
				<input type="checkbox" id="cets_RSS-dialog-itemdate"  value="1"  /> <label for="cets_RSS-dialog-itemdate">Include Date</label> 
				</p>

				<div id="cets_embedrss_button_wrapper">
					<a class="button" href="#" onClick="cets_embedrss_live_preview();">Preview Feed Output</a>
					<a class="button" href="#" onClick="cets_embedrss_to_editor();">Insert into Content</a>
				</div>

				<div><strong>Feed Preview</strong> (title links open in new tabs) - <a href="#" onClick="cets_embedrss_live_preview();">Refresh Preview</a></div>
				<div id="cets_embedrss_preview_wrapper">
					<p>Enter a Feed URL above and click <a href="#" onClick="cets_embedrss_live_preview()">Preview Feed Output</a> to test and preview your feed.</p>
				</div>	

			</div>
		</div>
		
	<?php
}




add_shortcode( 'cetsEmbedRSS', 'cets_embedrss_show_RSS' );
	
function cets_embedrss_show_RSS( $atts ) {

	$out = '';

	extract(shortcode_atts(array(
		'id' 		=> false,
		'itemcount' => 0,
		'itemcontent' => false,
		'itemauthor' => false,
		'itemdate' => false
	), $atts ));

	/* temporarily (at a minimum) removing because of the bug where if you try to run this on a post, then on a page, you've cached the error
	$shortcode_string = hash("md5","cets_embedrss|id:{$id}|count:{$itemcount}|content:{$itemcontent}|author:{$itemauthor}|date:{$itemdate}", false);
	$transient = get_transient($shortcode_string);
	if ( !empty( $transient ) ) {
		return $transient;
	}
	*/

	// if there's no feed passed, just get out of here.
	if (strlen($id) == 0) {
		return;
	}

	$skip_type_check = false;
	if ( (defined('DOING_AJAX') && DOING_AJAX) ) {
		// only previewing the content
		$skip_type_check = true;
	}


	if (!$skip_type_check) {
		if (!cets_embedrss_is_valid_type()) {
			if (current_user_can('edit_posts')) {
				$current_post_type = embed_rss_cets_get_post_type();
				return "<p><strong>Error:</strong> Embed RSS cannot be used on this post type ({$current_post_type})</p>";
			} else {
				return "<p>Content currently unavailable.</p>";
			}
			
		} 
	}

	//set an error message for circular feeds
	if (current_user_can('edit_posts')) {
		$circularfeedmsg = "Embed RSS plugin was used in a way that would likely never end, so we ended it for you. :)";
	}
	else{
		$circularfeedmsg = "Content currently unavailable.";
	}
	
	//remove the last slash with /feed
 	$feed = substr($id, 0, strlen($id)-5); 
	
	
	// Check to make sure this isn't embedding a feed for the same page
	$thispage =  cets_current_page_url();
	
	if (strtolower($thispage) == strtolower($feed) && !is_home() && !is_page()) {
		return $circularfeedmsg;
	}
					
	$rss = fetch_feed( $id );

	if (is_wp_error($rss) ) {
		
		if (is_super_admin()) {
			return "<p><strong>RSS Error: </strong>" . $rss->get_error_message() . "</p>";
		} else {
			return "Unable to display feed at this time.";
		}
	}

	if ( !$rss->get_item_quantity() ) {
		return "<p>No items to display at this time.</p>";
	}
			
	$maxitems = $rss->get_item_quantity($itemcount);
	$rss_items = $rss->get_items(0, $maxitems);
			
	if (count($rss_items) == 0) {
		return "<p>No items to display at this time.</p>";
	}

	
	$out .=  "<ul class='cets_embedRSS'>\n";

	foreach ($rss_items as $item ) {
		
		$link = $item->get_link();
		while ( stristr($link, 'http') != $link )
			$link = substr($link, 1);
		$link = esc_url(strip_tags($link));
		$title = esc_attr(strip_tags($item->get_title()));
		if ( empty($title) )
			$title = __('Untitled');
		
		$desc = str_replace( array("\n", "\r"), ' ', esc_attr( strip_tags( @html_entity_decode( $item->get_description(), ENT_QUOTES, get_option('blog_charset') ) ) ) );
		$desc = wp_html_excerpt( $desc, 360 );
		
		if ( '[...]' == substr( $desc, -5 ) )
			$desc = substr( $desc, 0, -5 ) . '[&hellip;]';
		 elseif ( '[&hellip;]' != substr( $desc, -10 ) && 355 < mb_strlen( $desc ) ) 
			$desc .= ' [&hellip;]';

		$desc = esc_html( $desc );
		
		if ( $itemcontent ) {
			$summary = "<div class='rssSummary'>$desc</div>";
		} else {
			$summary = '';
		}
		
				$date = '';
		if ( $itemdate ) {
			$date = $item->get_date( 'U' );

			if ( $date ) {
				$date = ' <span class="rss-date">' . date_i18n( get_option( 'date_format' ), $date ) . '</span>';
			}
		}

		$author = '';
		if ( $itemauthor ) {
			$author = $item->get_author();
			if ( is_object($author) ) {
				$author = $author->get_name();
				$author = ' <cite>' . esc_html( strip_tags( $author ) ) . '</cite>';
			}
		}
		


		if ( $link == '' ) {
			$out .= "<li>$title{$date}{$summary}{$author}</li>";
		} else {
			$out .= "<li><a class='post' href='$link' title='$title'>$title</a>{$date}{$summary}{$author}</li>";
		}
		
	
		$out .= "\n";
	} // foreach item

	$out .= "</ul>\n<br class='clear' />\n";
	
	/*
	$transient_timeout = apply_filters('cets_embedrss_transient_timeout', HOUR_IN_SECONDS);
	set_transient($shortcode_string, $out, HOUR_IN_SECONDS);
	*/
	return $out;
}

function cets_embedrss_is_valid_type() {
	// check against allowed types
	$default_types = get_post_types(array('public'=>true,'show_ui'=>true));
	$allowed_types = apply_filters('cets_embedrss_allowed_types', $default_types);
	$current_post_type = embed_rss_cets_get_post_type();
	if (in_array($current_post_type, $allowed_types)) {
		return true;
	} else {
		return false;
	}
}
