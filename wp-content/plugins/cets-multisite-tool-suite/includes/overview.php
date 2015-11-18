<?php


function cesuite_page_network_overview() {
	_cesuite_page_head('CESuite - Network Overview');
	
	
	global $blog_id, $wpdb;
		
	global $wpdb, $current_site;
		$blogs  = $wpdb->get_results("SELECT blog_id, domain, path FROM " . $wpdb->blogs . " WHERE site_id = {$current_site->id} ORDER BY domain ASC");
		
		if ($blogs) {
		
			echo "<style>
				.odd-row {background-color: #FBFDFF;} 
				.even-row {background-color: #E6E6E6; }

				table#multisite-details td {padding: 2px;}
				
				table#multisite-detailsb tr.deleted {background-color: #FFD6CC; }
				table#multisite-detailsb td { text-align: center;}
				
				.cellSize {
   					 display: inline-block;
    				 overflow: hidden;
					 padding: 2px;
				}
				
				</style>";
		
			echo "<table id='multisite-details' style='position:fixed; height:45px; background-color: #CCC;' ><tr>
				<th style='width:30px;'>ID</th>
				<th style='width:130px;'>Name</th>
				<th style='width:180px;'>Registered</th>
				<th style='width:110px;'>Admins</th>
				<th style='width:40px;'>Posts</th>
				<th style='width:80px;'>Last Post</th>
				<th style='width:40px;'>Pages</th>
				<th style='width:80px;'>Last Page</th>
				<th style='width:85px;'>Comments</th>
				<th style='width:40px;'>Comments Open</th>
				<th style='width:40px;'># Com Forms</th>
				<th style='width:90px;'>Theme</th>
				<th style='width:80px;'>Media</th>
				<th style='width:90px;'>Mapping</th></tr></table>
				<table id='multisite-detailsb' style='margin-top:50px;'>
				";
				
			//loop-scoped variables
			$lastpostargs = array(
						'numberposts' => 1
					);
			
			$lastpageargs = array(
						'numberposts' => 1,
						'post_type' => 'page'
					);
			
			$now = current_time('timestamp');
		
			$count = 0;
			$deleted_count = 0;
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
					
					//status
					$status = "";
					$status_sql = "SELECT deleted FROM wp_blogs WHERE blog_id = $blog_id";
					$status_info = $wpdb->get_results($status_sql);
					$this_status = $status_info[0]->deleted;
					if ($this_status == 1) {
						$status = "deleted";
						$deleted_count++;
					}
					
					
					//registered
					$reg_sql = "SELECT date_registered, email FROM wp_registration_log WHERE ID = $blog_id";
					$reg_info = $wpdb->get_results($reg_sql);
					
					//admins
					$admin_args = array('blog_id'=>$blog_id, 'role'=>'administrator','order'=>'DESC','orderby'=>'post_count');
					$admins = get_users($admin_args);
					
					//posts
					$numposts_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post'";
					$numposts  = $wpdb->get_var($numposts_sql);
								
					$lastposts = get_posts($lastpostargs);
					if ($lastposts) {
					$lastpost = $lastposts[0];
					$lastpost_id = $lastpost->ID;
					$lastpost_title = $lastpost->post_title;
					$lastpost_permalink = get_permalink($lastpost_id);
					$lastpost_time = get_post_time('G', true, $lastpost);
					$lastpost_time_ago = human_time_diff($lastpost_time, $now) . " ago";
					} else {
						$lastpost_title = "";
						$lastpost_permalink = "";
						$lastpost_time = "never";
						$lastpost_timeago = "";
					}
					
					
						
					
					//pages
					$numpages_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'page'";
					$numpages  = $wpdb->get_var($numpages_sql);
					
					
					$lastpages = get_posts($lastpageargs);
					if ($lastpages) {
					$lastpage = $lastpages[0];
					$lastpage_id = $lastpage->ID;
					$lastpage_title = $lastpage->post_title;
					$lastpage_permalink = get_permalink($lastpage_id);
					$lastpage_time = get_post_time('G', true, $lastpage);
					$lastpage_time_ago = human_time_diff($lastpage_time, $now) . " ago";
					} else {
						$lastpage_title = "";
						$lastpage_permalink = "";
						$lastpage_time = "never";
						$lastpage_time_ago = "";
					}
					
					
					//comments
					$numcomments_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 1";
					$numcomments = $wpdb->get_var($numcomments_sql);
					$numcomments_ua_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 0";
					$numcomments_ua = $wpdb->get_var($numcomments_ua_sql);
					$numcomments_spam_sql = "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = 'spam'";
					$numcomments_spam = $wpdb->get_var($numcomments_spam_sql);
					
					//comments open?
					$comments_open = get_option('default_comment_status','');

					//number comment forms
					$numforms_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type IN ('post','page') AND comment_status = 'open'";
					$num_comment_forms  = $wpdb->get_var($numforms_sql);

					//theme
					$theme = get_option('template');
					
					//mapping
					$mapping = "&nbsp;";
					$mapping_sql = "SELECT domain FROM wp_domain_mapping WHERE active = 1 AND blog_id = $blog_id";
					$mapping = $wpdb->get_var($mapping_sql);

					
					//quota
					$uploads = wp_upload_dir();
					$quota = get_space_allowed();				
					$used = get_dirsize( $uploads['basedir'] ) / 1024 / 1024;
					if ( $used > $quota )
						$percentused = '100';
					else
						$percentused = ( $used / $quota ) * 100;
					$used = round( $used, 2 );
					$percentused = number_format( $percentused );
					
					//media
					$nummedia_sql = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'attachment'";
					$nummedia = $wpdb->get_var($nummedia_sql);
					
					
					//do something with the blog
					if ($count % 2 == 1) {$rowclass="odd-row";} else {$rowclass="even-row";}
					
					if ($status) {
						$rowclass .= " $status";
					}
					
					echo "<tr class='$rowclass'>";
						
					//id
					echo "<td class='cellSize' style='width:30px;'>$blog_id<br></td>";

					//name
					echo "<td class='cellSize' style='width:130px'>$blogname<br/><a href='$blogurl'>" . $blog->path . "</a><br>(<a href='$blogurl/wp-admin'>dashboard</a>)</td>";
					
					//Registered
					echo "<td class='cellSize' style='width:180px'>";
					if (isset($reg_info[0])) {
						$date_reg = $reg_info[0]->date_registered;
						$reg_email = $reg_info[0]->email;
						echo "<a href='mailto:$reg_email'>$reg_email</a><br/>" .  $date_reg . "<br/>" . human_time_diff(strtotime($date_reg), $now) . " ago";
					} else {
						echo "Unavailable";
					}
					echo "</td>";
					
					//admins
					echo "<td class='cellSize' style='width:110px;'>";
					foreach($admins as $admin) {
						$email = $admin->user_email;
						echo "<a href='mailto:$email'>{$admin->user_nicename}</a>";
						echo "<br/>";
					}
					echo "</td>";
					
					
					//posts
					echo  "<td class='cellSize' style='width:40px;'><a href='$blogurl/wp-admin/edit.php'>$numposts</a></td>";
					echo  "<td class='cellSize' style='width:80px;'>";
					if ($lastpost_permalink) {	
						echo "<a href='$lastpost_permalink'>" . substr($lastpost_title, 0, 20) . "...</a><br>$lastpost_time_ago";
					} else {
						echo "&nbsp;";
					}
					echo "</td>";
					
					//pages
					echo  "<td class='cellSize' style='width:40px;'><a href='$blogurl/wp-admin/edit.php?post_type=page'>$numpages</a></td>";
					echo  "<td class='cellSize' style='width:80px;'>";
					if ($lastpage_permalink) {
						echo "<a href='$lastpage_permalink'>" . substr($lastpage_title, 0, 20) . "...</a><br>$lastpage_time_ago";
					} else {
						echo "&nbsp;";
					} 
					echo "</td>";
					
					//comments
					echo "<td class='cellSize' style='width:85px;'><a href='$blogurl/wp-admin/edit-comments.php?comment_status=approved'>$numcomments</a> Approved <br><a href='$blogurl/wp-admin/edit-comments.php?comment_status=moderated'>$numcomments_ua</a> Pending <br/><a href='{$blogurl}/wp-admin/edit-comments.php?comment_status=spam'>{$numcomments_spam}</a> Spam</td>";
					
					//comments open?
					echo "<td class='cellSize' style='width:40px;'><a href='$blogurl/wp-admin/options-discussion.php'>$comments_open</a></td>";

					//number comment forms
					echo "<td class='cellSize' style='width:40px;'><a href='$blogurl/wp-admin/plugins.php'>$num_comment_forms</a></td>";

					//theme
					echo "<td class='cellSize' style='width:90px;'>$theme</td>";
					
					
					//media
					echo "<td class='cellSize' style='width:80'><a href='$blogurl/wp-admin/upload.php'>$nummedia</a> Items<br>$used / $quota MB<br>($percentused %)</td>";
					
									
					//mapping
					echo "<td class='cellSize' style='width:90px;'>$mapping</td>";
					
					echo "</tr>";
				
				
				restore_current_blog();
			
				$count++;
			} //for each blog	
			
			echo "</table>";
			
			echo "<p><strong>Aggregate Info</strong><br/>";
			echo "Total Sites: $count <br/>";
			echo "User Disabled Sites: $deleted_count <br/>";
			echo "</p>";
			
		}
	
	_cesuite_page_foot();
}