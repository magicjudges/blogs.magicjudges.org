<?php
/*
Plugin Name: Transient Toilet
Plugin URI: 
Description: Easily flush the site's transients (including cached RSS feeds)
Author: Jason Lemahieu
Version: 0.4
Author URI: 
*/


add_action( 'admin_menu', 'cets_rss_transient_toilet_admin_menu' );

function cets_rss_transient_toilet_admin_menu() {
	add_submenu_page('tools.php', 
		'Flush Caches', 
		'Flush Caches', 
		'manage_options', 
		'cets_flush_transients', 
		'cets_transient_toilet_admin_page');
}



function cets_transient_toilet_admin_page() {
	
	cets_transient_toilet_do_flush();

	echo "<div class='wrap'><h2>Flush Caches</h2>";

		?>
		<p>
		In order to keep your sites working optimally, WordPress caches certain parts of your website that might be time consuming to calculate or create.  Some examples of things that get cached are RSS feeds and data about embedded files or videos.  Typically, this speeds up the website without a noticable effect to the visitors.  However, there are times when you'd like to clear these caches and make sure your site is using the most recent data.
		</p>

		<p>
		By visiting this page, you've already cleared your site's cache. Nice work!  :)
		</p>

		<p>
		(But again, you really shouldn't have to do this regularly.  This only speeds up the refreshing of some very small and specific content.)
		</p>

		<?php

		
		echo "<div class='updated'><p>Successfully flushed your site's cache.</p></div>";
		

	echo "</div>"; // div.wrap
}


function cets_transient_toilet_do_flush() {
	global $wpdb;
	
	/* Transients */
	$wpdb->query( 
		$wpdb->prepare( 
			"
			 DELETE FROM {$wpdb->options}
			 WHERE option_name LIKE %s",
		     '_transient_%' 
	        )
	);
	

	/* oEmbed */
	$wpdb->query( 
		$wpdb->prepare( 
			"
			 DELETE FROM {$wpdb->postmeta}
			 WHERE meta_key LIKE %s",
		     '_oembed_%' 
	        )
		);

}
