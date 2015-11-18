<?php

/************** FLUSH TRANSIENTS *********************************************************************************************/

function cesuite_page_flush_transients() {
	_cesuite_page_head('CESuite - Flush Transients');

	if (!function_exists('cets_transient_toilet_do_flush')) {
		echo "<p>Transient Toilets needs to be Network Activated in order to flush transients for the network.</p>";
	} else {


	global $blog_id, $wpdb;
			
		global $wpdb, $current_site;
			$blogs  = $wpdb->get_results("SELECT blog_id, domain, path FROM " . $wpdb->blogs . " WHERE site_id = {$current_site->id} ORDER BY domain ASC");
			
			if ($blogs) {
						
				echo "<ul>";

				foreach ($blogs as $blog) {
					
					switch_to_blog($blog->blog_id);

						if( constant( 'VHOST' ) == 'yes' ) {
							$blogurl = $blog->domain;			
						} else {
							$blogurl =  trailingslashit( $blog->domain . $blog->path );
						}
							
						$bloginfo = get_bloginfo();
						
						//name
						$blogname = get_bloginfo('name');
						$blogurl = home_url();

						$transient_turds = cets_transient_toilet_do_flush();
					
						echo "<li>$blogname: <a target='_blank' href='$blogurl'>$blogurl</a> // {$transient_turds} flushed</li>";

				restore_current_blog();
			} //foreach ($blogs as $blog)
			
			echo "</ul>";

		} // if $blogs

	} // if function exists

	_cesuite_page_foot();
}

