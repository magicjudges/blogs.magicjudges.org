<?php
/*
Template Name: Topic
*/

// Set some global variables

global $wpquery, $topicid;

add_filter('body_class', 'remove_home_class');

// check to make sure the functions we need exists
if (function_exists('cets_get_topic_id_from_slug')){
		
	// use the query var (text) to get the topicid (int)
	$topicvar = explode("/", $wp_query->query_vars['topic']);
	$topicid = cets_get_topic_id_from_slug($topicvar[0]);
	// return an object with the topic info
	$topic = cets_get_topic($topicid);
	
	//echo "about to call cets_get_recent_posts_from_topic_id with id: " . $topicid;
	
	// Get the recent posts
	$posts = cets_get_recent_posts_from_topic_id($topicid, 0, 0, 1);
	
	//echo "posts:<br>"; var_dump($posts); //debug
	
	// check to see if this is a feed page
	if (isset($topicvar[1]) && $topicvar[1] == 'feed') {
		$template = TEMPLATEPATH . "/topicfeed.php";
		if (file_exists($template)) {
		require_once($template);
		die();
		echo ("hey we should be on the feed page!");
		}
		
	}

	// get the feed icon
	$imageurl = get_template_directory_uri() . "/images/feed-icon.png";


?>


<?php get_header(); ?>

  <div id="sideContent1">
  <!-- 280 x 210 / 280 x 158 -->
  <div class="topicListing">



<?php /* ?>      <h3>New Sites</h3>
      <?php cets_get_blogs_from_topic_id_html($topicid, 5, 0, 'added'); // change the 2 to the number of sites you want. If you want to pass a class for the UL, it's the last parameter ?><?php */?>

	<?php
			if (isset($topic->short_name) && $topic->short_name) {
				$display_name = $topic->short_name;
			} else {
				$display_name = $topic->topic_name;
			}
		
		?>	  

	   <p class="showAllSites">All <?php echo $topic->topic_name; ?> Sites</p>
        <div class="siteListing">
        	<?php 
				
		  $bloglist = cets_get_blog_details_from_topic_id($topicid, 0, 0, 'alpha'); //$topic_id, $max_rows = 0, $blog_id = 0, $orderby = 'last_updated|alpha'
	  
		  if (sizeOf($bloglist) > 0) {
		  echo("<ul>");
		  foreach ($bloglist as $key=>$value) {
			  echo "<li><a href='http://" . $bloglist[$key]['domain'] . $bloglist[$key]['path'] . "'>" . $bloglist[$key]['blogname'] . "</a></li>";
		  }
		  echo("</ul>");
		  
		  }
		  else {
			  echo("There are no blogs in this topic.");
		  }

	  ?>
	</div> <!-- / .siteListing -->


    </div> <!-- / .topicListing -->
    </div><!-- / #sideContent1 --> 


  <div id="mainContent">
  	
	
	
	<?php if (sizeOf($posts) > 0) {
	echo("<hr>");
	echo("<h2 class='yellowHeading'> Latest Updates <a href='/topic/" . $topicvar[0] . "/feed' class='rss imagereplacement' target='_new'>RSS</a></h2>");
	
	echo("<div class='topicListing'>");
	echo("<ul>");
	foreach ($posts as $post) {
		
		$link = get_blog_permalink( $post->blogid, $post->id );
		$post->post_date = date("m/d/Y",strtotime($post->post_date));  // Format the date
		if ($post->post_title != '' || $post->post_title != NULL) {
			echo "<li><span class='headline'><a href='" .$link . "'>" . $post->post_title . "</a></span> - <span class='date'>" . $post->post_date . "</span><br />";
		}
		else {
			echo "<li><span class='headline'><a href='" .$link . "'> Latest Update </a></span> - <span class='date'>" . $post->post_date . "</span><br />";
		}
		
		//if excerpt
		$blurb = '&nbsp;';
		if ($post->post_excerpt) {
			//has an excerpt, use this.
			$blurb = wp_html_excerpt(strip_shortcodes($post->post_excerpt), 160);
		} else {
			$blurb = wp_html_excerpt(strip_shortcodes($post->post_content), 160);
		}
		$blurb = cets_truncate($blurb, 150, " ", "...");
		//display the blurb
		echo  "<span class='blurb'>" . $blurb . "</span> <br />";
		
		echo "<span class='sitename'>From: <a href='" . $post->siteurl . "'>" . $post->blogname . "</a></span>";
		echo "</li>";
		echo("<br />");
		
	}
	echo("</ul>");
	echo("</div>");
	}
else {



	echo("<p class='noPosts'> There are no recent posts in this topic. </p>");
}
?>
    
    <!-- end #mainContent -->
  </div>

	<br class="clear" />  
 
 <?php 
 
 } // this ends the if that checks for the blog topics functions- needs to be at bottom of page
 else {
 	echo("<p>This page is to be used with the cets_blog_topics plugin only."); 
	
 }
 ?>

<?php get_footer(); ?>
