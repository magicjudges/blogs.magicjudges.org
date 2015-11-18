<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://www.w3.org/2005/10/profile">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <title><?php wp_title(); ?> <?php bloginfo('name'); ?></title>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	
	<link rel="alternate" type="application/rss+xml" title="Full <?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
    <?php get_fyiTopicRSSforHead(); ?>
    
    
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
  
    <?php wp_enqueue_script('jquery'); ?>
	
	<?php
	global $wp_query;
	
	$imagelink='';
	$headerText='';
	$imageurl='';
	
	//home page image options	
	if (function_exists('of_get_option')) {
		$imagelink = of_get_option('large_image_url_home', '');
		$headerText = of_get_option('large_image_caption_home', '');
		$imageurl = of_get_option('large_image_home', 'none');
	} else {
		$imagelink == '';
		$imageurl == '';
		$headerText == '';
	}	
	
	if ($imageurl == 'none' || $imageurl == '') {
		$imageurl = get_template_directory_uri() . "/images/homeimage.png";
	}	

		
	
	$imagefile = get_template_directory() . "/images/homeimage.png";
	$desc = "<p>Cooperative Extension educators don’t lecture or give grades in a typical classroom. Instead, we deliver education where people live and work –
	  on the farm, in schools and community centers.</p> <p><em>For Your Information</em> is a network of educator sites that continues this tradition online.</p>";
	$title = "Welcome";
	// if this is either a topic page or a sitelist page, grab the slug
	if (isset($wp_query->query_vars['topic']) || isset($wp_query->query_vars['sitelist'])){
		if (isset($wp_query->query_vars['topic'])) {
			$slug = $wp_query->query_vars['topic'];
		}
		if (isset($wp_query->query_vars['sitelist'])) {
			$slug = $wp_query->query_vars['sitelist'];
		}
		
		if (function_exists('cets_get_topic_id_from_slug')){
		// use the query var (text) to get the topicid (int)
		$topicid = cets_get_topic_id_from_slug($slug);
		// return an object with the topic info
		$topic = cets_get_topic($topicid);
		}
		if (strlen($slug)){
			
			$option_name = "large_image_" . $topic->slug;
			if (function_exists('of_get_option')) {
				$imageurl = of_get_option(strtolower($option_name), 'none');
			} else {
				$imageurl == '';
			}
			if ($imageurl == 'none' || $imageurl == '') {
				$imageurl = get_template_directory_uri() . "/images/topics/" . $topic->slug . ".jpg";
			}	
			
			$option_name = "large_image_url_" . $topic->slug;
			if (function_exists('of_get_option')) {
				$imagelink = of_get_option(strtolower($option_name), 'none');
			} else {
				$imagelink == '';
			}	
	}

	
		$desc = "<p>" . $topic->description . "</p>";
		$title = $topic->topic_name;

	}
	?>
    
	<?php wp_head(); ?>
    
    <!-- Script to display sites list in multiple columns -->
    <script src="<?php echo get_template_directory_uri(); ?>/js/columnizer.js" type="text/javascript"></script>
    <script type="text/javascript">
    //jQuery.noConflict();
	jQuery(document).ready(function($){
		// http://plugins.jquery.com/project/makeacolumnlists
		$('.siteList').makeacolumnlists({cols:2,colWidth:0,equalHeight:false,startN:1});
	});
	</script>

</head>

<body <?php body_class(); ?>>


<div id="header">
	   <div id="logoContainer">	
          <a href="<?php bloginfo('url'); ?>">
           <img src=' <?php echo get_template_directory_uri() . "/images/judge-logo.png"; ?>' alt="<?php bloginfo('name'); ?> logo" />
          </a>	
		</div>
    <div id="fyiNavigation">
        <ul id="topnav">
        <?php /*cets_get_topics_html(true, false, true, true)*/ ?>
		<?php 
			$args = array(
						'theme_location'  => 'judge2012-top',
					);
			wp_nav_menu($args);
		?>
        </ul>
    </div> 
    
  <!-- end #header --></div>
<div id="wrapper">
 <!-- <br class="clear" />-->
  <div id="pageHeader">
  
  <?php 
  
		if (isset($topic->topic_name)) {
			echo '<div id="topicHeader">' . $topic->topic_name . '</div>'; 
		}
?>

  	<div class="pageHeaderImage">
  		<?php 
		
			if (isset($topic->topic_name)) {
				$alt_text = $topic->topic_name;
			} else {
				$alt_text = "home page image";
			}
		
			if ($imagelink != '') {
				echo("<a href='" . $imagelink . "'><img src='" . $imageurl . "' alt='' /></a>");
			}
			else{
				echo("<img src='" . $imageurl . "' alt='" . $alt_text . " header image' />");
			}
		?>
	
        
        
        <?php
		
		if (isset($slug) && strlen($slug)){
        	$option_name = "large_image_caption_" . $topic->slug;
			if (function_exists('of_get_option')) {
				$headerText = of_get_option(strtolower($option_name), 'none');
			} else {
				$headerText == '';
			}
		}
		
		if ($headerText != '') {
				echo '<div class="pageHeaderTextBack">';
      		    echo '</div>';
     		    echo '<div class="pageHeaderText">';
				if($imagelink != ''){
				     echo "<a href='" . $imagelink . "'>" . $headerText . "</a>";
				}
				else{
					echo $headerText;
				}
				echo '</div>';
				
		}
		
        ?>
       	</div> <!-- /#pageHeaderImage -->
             
 
  </div><!-- / #pageHeader -->
  <div id="contentArea">