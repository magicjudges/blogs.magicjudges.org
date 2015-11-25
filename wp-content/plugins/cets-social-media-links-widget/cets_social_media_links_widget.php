<?php
/**
Plugin Name: Social Media Links Widget
Plugin URI:
Description: Easily link to popular social media networks from your sidebars
Version: 1.8
Author: Jason Lemahieu and Chrissy Dillhunt
Author URI:
License: GPLv2
*/

/**
 * Widget Class
 */
class CETS_social_media_links_widget extends WP_Widget {
    /** constructor */
    function CETS_social_media_links_widget() {
        parent::__construct(false, $name = 'Social Media Links');
    }



    /** @see WP_Widget::widget */
    function widget($args, $instance) {

		$instance = $this->prefill_instance($instance);


    	extract( $args );
        $title = apply_filters('widget_title', $instance['title']);

		$facebook = esc_attr($instance['facebook']);
		$facebook = $this->string_prefix_check($facebook, 'http');
		$twitter = esc_attr($instance['twitter']);
		$twitter = $this->string_prefix_check($twitter, 'http');
		$googleplus = esc_attr($instance['googleplus']);
		$googleplus = $this->string_prefix_check($googleplus, 'https');
		$pinterest = esc_attr($instance['pinterest']);
		$pinterest = $this->string_prefix_check($pinterest, 'http');
		$flickr = esc_attr($instance['flickr']);
		$flickr = $this->string_prefix_check($flickr, 'http');
		$youtube = esc_attr($instance['youtube']);
		$youtube = $this->string_prefix_check($youtube, 'http');
		$picasa = esc_attr($instance['picasa']);
		$picasa = $this->string_prefix_check($picasa, 'http');
		$instagram = esc_attr($instance['instagram']);
		$instagram = $this->string_prefix_check($instagram, 'http');
		$podcasts = esc_attr($instance['podcasts']);
		$podcasts = $this->string_prefix_check($podcasts, 'http');


		//allow mailto: links
		$email = esc_attr($instance['email']);
			$tmpEmail = strtolower($email);
			$pos = strpos($tmpEmail, 'mailto:');
			if (!$pos === 0) {
				$email = $this->string_prefix_check($email, 'http');
			}
		$rss = esc_attr($instance['rss']);
		$rss = $this->string_prefix_check($rss, 'http://');

		//confirm there's SOMETHING here
		if (!$facebook && !$twitter && !$googleplus && !$pinterest && !$flickr && !$email && !$rss  && !$picasa && !$instagram && !$podcasts) {
			return;
		}


        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>


				<?php print "<ul class='cets-find-us-list'>";	?>
				 <?php
					if ($facebook) {
						$this->print_link('Facebook', $facebook);
					}
					if ($twitter) {
						$this->print_link('Twitter', $twitter);
					}
					if ($googleplus) {
						$this->print_link('Google Plus', $googleplus);
					}
					if ($flickr) {
						$this->print_link('Flickr', $flickr);
					}
					if ($pinterest) {
						$this->print_link('Pinterest', $pinterest);
					}
					if ($youtube) {
						$this->print_link('YouTube', $youtube);
					}
					if ($picasa) {
						$this->print_link('Picasa', $picasa);
					}
					if ($instagram) {
						$this->print_link('Instagram', $instagram);
					}
					if ($podcasts) {
						$this->print_link('Podcasts', $podcasts);
					}
					if ($email) {
						$this->print_link('Email Us', $email);
					}
					if ($rss) {
						$this->print_link('RSS', $rss);
					}


				 ?>
				 <?php print "</ul><br class='fix'>"; ?>

              <?php echo $after_widget; ?>
        <?php
    }

	function print_link($sitename, $url) {
		$classname = strtolower(str_replace(" ", "", $sitename));
		if ($sitename == "Google Plus") {
			$sitename = "Google+";
		}
		echo "<li class='$classname'><a target='_blank' title='Link to $sitename' href='$url'>$sitename</a></li>";
	}

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['title'] = wp_filter_nohtml_kses(strip_tags($new_instance['title']));
	$instance['facebook'] = strip_tags($new_instance['facebook']);
	$instance['twitter'] = strip_tags($new_instance['twitter']);
	$instance['googleplus'] = strip_tags($new_instance['googleplus']);
	$instance['pinterest'] = strip_tags($new_instance['pinterest']);
	$instance['flickr'] = strip_tags($new_instance['flickr']);
	$instance['youtube'] = strip_tags($new_instance['youtube']);
	$instance['picasa'] = strip_tags($new_instance['picasa']);
	$instance['instagram'] = strip_tags($new_instance['instagram']);
	$instance['podcasts'] = strip_tags($new_instance['podcasts']);
	$instance['email'] = strip_tags($new_instance['email']);
	$instance['rss'] = strip_tags($new_instance['rss']);


        return $instance;
    }

	function prefill_instance($instance) {

		if (!isset($instance['title'])) {
			$instance['title'] = "Connect with Us";
		}
		if (!isset($instance['facebook'])) {
			$instance['facebook'] = "";
		}
		if (!isset($instance['twitter'])) {
			$instance['twitter'] = "";
		}
		if (!isset($instance['googleplus'])) {
			$instance['googleplus'] = "";
		}
		if (!isset($instance['pinterest'])) {
			$instance['pinterest'] = "";
		}
		if (!isset($instance['flickr'])) {
			$instance['flickr'] = "";
		}
		if (!isset($instance['youtube'])) {
			$instance['youtube'] = "";
		}
		if (!isset($instance['picasa'])) {
			$instance['picasa'] = "";
		}
		if (!isset($instance['instagram'])) {
			$instance['instagram'] = "";
		}
		if (!isset($instance['podcasts'])) {
			$instance['podcasts'] = "";
		}
		if (!isset($instance['email'])) {
			$instance['email'] = "";
		}
		if (!isset($instance['rss'])) {
			$instance['rss'] = "";
		}

		return $instance;
	}

    /** @see WP_Widget::form */
    function form($instance) {

		$instance = $this->prefill_instance($instance);


		$title = esc_attr($instance['title']);
		$facebook = esc_attr($instance['facebook']);
		$twitter = esc_attr($instance['twitter']);
		$googleplus = esc_attr($instance['googleplus']);
		$pinterest = esc_attr($instance['pinterest']);
		$flickr = esc_attr($instance['flickr']);
		$youtube = esc_attr($instance['youtube']);
		$picasa = esc_attr($instance['picasa']);
		$instagram = esc_attr($instance['instagram']);
		$podcasts = esc_attr($instance['podcasts']);
		$email = esc_attr($instance['email']);
		$rss = esc_attr($instance['rss']);

        ?>

		<div class="cets-social-wrap">


            <!-- Title -->
			<div class="cets-social-admin-section-wrap">
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?>
			<p>The title is displayed at the top of your widget. You might want to use something like "Find Us On", "Join Us On", or "Connect with Us"</p>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			</div>

			<div class="cets-social-admin-note-wrap">
		<p>Be sure to include FULL URLs that start with http:// and link to YOUR actual social media page.  It's always a good idea to test links after you add them!</p>
		</div>

			<!-- facebook -->
			<div class="cets-social-admin-section-wrap">
			<p><label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo $facebook; ?>" /></label></p>
			</div>


			<!-- twitter -->
			<div class="cets-social-admin-section-wrap">
			<p><label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo $twitter; ?>" /></label></p>
			</div>

			<!-- googleplus -->
			<div class="cets-social-admin-section-wrap">
			<p><label for="<?php echo $this->get_field_id('googleplus'); ?>"><?php _e('Google+:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('googleplus'); ?>" name="<?php echo $this->get_field_name('googleplus'); ?>" type="text" value="<?php echo $googleplus; ?>" /></label></p>
			</div>

            <!-- Pinterest -->
			<div class="cets-social-admin-section-wrap">
			<p><label for="<?php echo $this->get_field_id('pinterest'); ?>"><?php _e('Pinterest:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('pinterest'); ?>" name="<?php echo $this->get_field_name('pinterest'); ?>" type="text" value="<?php echo $pinterest; ?>" /></label></p>
			</div>

			<!-- flickr -->
			<div class="cets-social-admin-section-wrap">
			<p><label for="<?php echo $this->get_field_id('flickr'); ?>"><?php _e('Flickr:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('flickr'); ?>" name="<?php echo $this->get_field_name('flickr'); ?>" type="text" value="<?php echo $flickr; ?>" /></label></p>
			</div>


			<!-- YouTube -->
			<div class="cets-social-admin-section-wrap">
			<p><label for="<?php echo $this->get_field_id('youtube'); ?>"><?php _e('YouTube:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('youtube'); ?>" name="<?php echo $this->get_field_name('youtube'); ?>" type="text" value="<?php echo $youtube; ?>" /></label></p>
			</div>

            <!-- Picasa -->
			<div class="cets-social-admin-section-wrap">
			<p><label for="<?php echo $this->get_field_id('Picasa'); ?>"><?php _e('Picasa:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('picasa'); ?>" name="<?php echo $this->get_field_name('picasa'); ?>" type="text" value="<?php echo $picasa; ?>" /></label></p>
			</div>

            <!-- instagram -->
			<div class="cets-social-admin-section-wrap">
			<p><label for="<?php echo $this->get_field_id('instagram'); ?>"><?php _e('Instagram:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('instagram'); ?>" name="<?php echo $this->get_field_name('instagram'); ?>" type="text" value="<?php echo $instagram ?>" /></label></p>
			</div>

            <!-- podcasts -->
			<div class="cets-social-admin-section-wrap">
			<p><label for="<?php echo $this->get_field_id('podcasts'); ?>"><?php _e('Podcasts:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('podcasts'); ?>" name="<?php echo $this->get_field_name('podcasts'); ?>" type="text" value="<?php echo $podcasts ?>" /></label></p>
			</div>

			<!-- RSS -->
			<div class="cets-social-admin-section-wrap">
			<p><label for="<?php echo $this->get_field_id('rss'); ?>"><?php _e('RSS:'); ?></p>
			<p><strong>Note:</strong> Be sure this is a valid RSS feed URL.  For WordPress sites, you can obtain the RSS feed url be appending "/feed" to the end of the site's URL.  For example: http://fyi.uwex.edu/your-site-name/feed</p>
			<input class="widefat" id="<?php echo $this->get_field_id('rss'); ?>" name="<?php echo $this->get_field_name('rss'); ?>" type="text" value="<?php echo $rss; ?>" /></label></p>
			</div>

			<!-- Email -->
			<div class="cets-social-admin-section-wrap">
			<p><label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email:'); ?></p>
			<p><strong>Note:</strong> This should be a valid URL.  If you want to have this be an email link instead, enter "mailto:" followed by your email address.  <br>For example: mailto:jane.doe@ces.uwex.edu</p>
			 <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo $email; ?>" /></label></p>
			</div>

		</div> <!-- wrapper -->

		<?php

    }

	function string_prefix_check($string, $prefix='http') {
    	$string = trim($string);

		if (!$string) { return ''; } //verify we were given SOME string.

    	if(strncmp($string, $prefix, strlen($prefix)) != 0) {
    		$string = $prefix . $string;
    	}

    	return $string;
    }


} // class Widget

// register widget
add_action('widgets_init', create_function('', 'return register_widget("CETS_social_media_links_widget");'));

/* CSS */
function cets_social_media_widget_css_public() {

	// CSS and Javascript for HTML HEAD
	?>
	<link rel="stylesheet" href="<?php echo plugins_url('css/cets-social-media-links-widget.css',__FILE__)?>" type="text/css" />
	<?php
}

// Add the CSS to public page's Head section
add_action('wp_head', 'cets_social_media_widget_css_public');


function cets_social_media_widget_css_admin() {

	// CSS and Javascript for HTML HEAD
	?>
	<link rel="stylesheet" href="<?php echo plugins_url('css/cets-social-media-links-widget-admin.css',__FILE__)?>" type="text/css" />
	<?php
}

// Add the CSS to admin page's Head section
add_action('admin_head', 'cets_social_media_widget_css_admin');
