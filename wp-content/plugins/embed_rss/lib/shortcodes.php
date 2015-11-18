<?php

class cets_EmbedRSS_shortcodes {
	
	var $count = 1;
	
	// register the new shortcodes
	function cets_EmbedRSS_shortcodes() {
	
		add_shortcode( 'cetsEmbedRSS', array(&$this, 'show_RSS') );
			
	}

	function curPageURL() {
	 $pageURL = 'http';
	 if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
	
	
	
	function show_RSS( $atts ) {
	
		global $cets_EmbedRSS;
	
		extract(shortcode_atts(array(
			'id' 		=> false,
			'itemcount' => 0,
			'itemcontent' => false,
			'itemauthor' => false,
			'itemdate' => false
		), $atts ));
		
		
		
		// if there's no feed passed, just get out of here.
		if (strlen($id) == 0) {
			return;
		}
		
		//if this is a post and not a page and we're not allowing embedding on posts, just get out of here
		if (! is_page()) {
			return;
			
		}
		
		//set an error message for circular feeds
		
		if (is_user_logged_in()) {
			$circularfeedmsg = "Embedded RSS plugin used incorrectly. Please read the documentation.";
		}
		else{
			$circularfeedmsg = "Content currently unavailable.";
		}
		
		
		//remove the last slash with /feed
	 	$feed = substr($id, 0, strlen($id)-5);
		
		// Check to make sure this isn't embedding a feed for the same page
		$thispage =  $this->curPageURL();
		
		if (strtolower($thispage) == strtolower($feed) && !is_home() && !is_page()) {
			return $circularfeedmsg;
			
		}
		
		
		// Now look for feeds from the same tag or category as this post (only relevant if it's a feed from the same site)
		$explodedfeed = explode("/", $feed);
		$explodedpage = explode("/", $thispage);
		//The category would be the last bit of this - grab that
		$cat = $explodedfeed[sizeof($explodedfeed)-2];
		
		$baseurl =  $explodedfeed[2];
		
		
		//if this is true, it's potentially the same site (but not definitively, need to test more things, but only if this is true)
		if ($baseurl == $_SERVER["SERVER_NAME"]){
			
			$feedsite = $explodedfeed[3];
			$thissite = $explodedpage[3];
			
			
			if (in_category($cat) && $feedsite == $thissite)
			{
				return $circularfeedmsg;
				
			}
			
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
	
		

		
		$out =  "<ul class='cets_embedRSS'>\n";

		
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
			
			/* list($author,$post) = explode( ':', $title, 2 ); */
			
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
				$out .= "<li><a class='post' href='$link' title='$desc'>$title</a>{$date}{$summary}{$author}</li>";
			}
			
		
		$out .= "\n";
	}

	$out .= "</ul>\n<br class='clear' />\n";
		
		
		return $out;
	}

	
}

// use it
$cets_EmbedRSSShortcodes = new cets_EmbedRSS_Shortcodes;