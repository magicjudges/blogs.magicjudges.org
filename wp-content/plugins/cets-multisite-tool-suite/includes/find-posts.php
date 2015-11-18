<?php


function cesuite_page_post_sleuth() {

	_cesuite_page_head('CESuite - Post Sleuth');
	
	if (!(isset($_POST['cesuite_submitted']) && $_POST['cesuite_submitted'] =='Go')) {


	_cesuite_form_start();

		// Post Type
		echo "<h3>Post Types</h3>";
		$types = get_post_types();
		echo "<ul>";
		foreach ($types as $type) {
			echo "<li><input type='checkbox' value='$type' name='sleuth_type[]'>&nbsp;" . $type . "</li>";
		}
		echo "</ul>";


		// Post Status
		echo "<h3>Post Statuses</h3>";
		$statuses = get_post_statuses();
		echo "<ul>";
		foreach ($statuses as $key=>$status) {
			echo "<li><input type='checkbox' value='$key' name='sleuth_status[]'>&nbsp;" . $status . "<li>";
		}
		echo "</ul>";

		// Body Like:
		echo "<h3>Post Title</h3>";
		echo "<input type='text' name='sleuth_title' size='40'>";

		echo "<h3>Post Content</h3>";
		echo "<input type='text' name='sleuth_content' size='60'>";

		_cesuite_form_end();


	} else {

	
		global $blog_id, $wpdb;
			
		global $wpdb, $current_site;
			$blogs  = $wpdb->get_results("SELECT blog_id, domain, path FROM " . $wpdb->blogs . " WHERE site_id = {$current_site->id} ORDER BY domain ASC");
			
			if ($blogs) {
			
				
				$extra_sql = "";
				
				if (isset($_POST['sleuth_type']) && $_POST['sleuth_type']) {
					
					$types = $_POST['sleuth_type'];
					
					$type_array = array();
					foreach ($types as $type) {
						array_push($type_array, "'" . $type . "'");
					}


					$type_string = implode($type_array, ",");

					$extra_sql .= " AND post_type IN (" . $type_string . ") ";
				}


				if (isset($_POST['sleuth_status']) && $_POST['sleuth_status']) {

					
					$statuses = $_POST['sleuth_status'];
					
					$status_array = array();
					foreach ($statuses as $status) {
						array_push($status_array, "'" . $status . "'");
					}


					$status_string = implode($status_array, ",");

					$extra_sql .= " AND post_status IN (" . $status_string . ") ";
				
				}



				if ($_POST['sleuth_title']) {
					$extra_sql .= " AND LOWER(post_title) LIKE '%" . strtolower($_POST['sleuth_title']) . "%' ";
				}
				if ($_POST['sleuth_content']) {
					$extra_sql .= " AND LOWER(post_content) LIKE '%" . strtolower($_POST['sleuth_content']) . "%' ";
				}


			
				foreach ($blogs as $blog) {
					
					switch_to_blog($blog->blog_id);

					$base_sql = "SELECT * FROM $wpdb->posts WHERE 1=1 ";
					$sql = $base_sql . $extra_sql;

					$my_rows = $wpdb->get_results($sql);

					if (count($my_rows) > 0) {


						if( constant( 'VHOST' ) == 'yes' ) {
							$blogurl = $blog->domain;			
						} else {
							$blogurl =  trailingslashit( $blog->domain . $blog->path );
						}
							
						$bloginfo = get_bloginfo();
						
						//name
						$blogname = get_bloginfo('name');
						$blogurl = home_url();

						echo "<br/><br/><div class='sleuth-site-wrapper'>";
						echo "$blogname - <a target='_blank' href='$blogurl'>$blogurl</a>";

						echo "<ul style='margin-left: 20px;'>";
						foreach($my_rows as $row) {
							//guid is inconsistent, get post's permalink instead
							$permalink = get_permalink($row->ID);

							echo "<li><a target='_blank' href='" . $permalink . "'>" . $row->post_title . "</a> (" . $row->post_type . " // " . $row->post_status . ")</li>";
						}
						echo "<ul>";

					echo "</div>";

				} // if my_rows

				restore_current_blog();
			} //foreach ($blogs as $blog)
		} // if $blogs

	}

	_cesuite_page_foot();

}