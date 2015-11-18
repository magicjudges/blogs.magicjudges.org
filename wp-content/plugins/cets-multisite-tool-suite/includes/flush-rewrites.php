<?php


/************** FLUSH REWRITES *********************************************************************************************/

function cesuite_page_flush_rewrites() {
	_cesuite_page_head('CESuite - Flush Rewrites');

	global $wp_rewrite, $wpdb, $current_site;
			
			$blogs  = $wpdb->get_results("SELECT blog_id, domain, path FROM " . $wpdb->blogs . " WHERE site_id = {$current_site->id} ORDER BY domain ASC");
			
			if ($blogs) {
						
				foreach ($blogs as $blog) {
					
					switch_to_blog($blog->blog_id);

					update_option( 'rewrite_rules', array() );

					restore_current_blog();
				} //foreach ($blogs as $blog)
			
			$wp_rewrite->flush_rules();
			echo "<p>Successfully flushed rewrite rules for the entire network.</p>";

			} // if $blogs

	_cesuite_page_foot();
}

