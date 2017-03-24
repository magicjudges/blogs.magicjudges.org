<?php
/*
Outputs the open graph protocol components
*/
add_action('wp_head', 'cets_og_do_head');

function cets_og_do_head() {

    global $post;

    echo "<!-- CETS Open Graph -->";

    /* og:site_name */

    echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '"/>' . "\n";

    /*og:title */

    if (is_home() || is_front_page()) {
        $cetsog_title = get_bloginfo('name');
    } else if (is_category()) {
        $cetsog_title = single_cat_title("", false);
    } else if (is_archive()) {
        $cetsog_title = get_bloginfo('name');
    } else {
        $cetsog_title = get_the_title();
    }

    echo '<meta property="og:title" content="' . $cetsog_title . '"/>' . "\n";

    /* og:type */

    if (is_single()) {
        $cetsog_type = 'article';
    } else {
        $cetsog_type = 'website';
    }

    echo '<meta property="og:type" content="' . $cetsog_type . '"/>' . "\n";


    /* og:description */

    if (is_singular() && isset($post)) {
        $cetsog_description = get_bloginfo('name');
    } else {
        $cetsog_description = get_bloginfo('description');
    }
    echo '<meta property="og:description" content="' . esc_attr(apply_filters('cetsog_description', $cetsog_description)) . '"/>' . "\n";


    /* og:url */

    if (is_home() || is_front_page()) {
        $cetsog_url = get_bloginfo('url');
    } else {
        $cetsog_url = 'http' . (is_ssl() ? 's' : '') . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    $cetsog_url = trailingslashit($cetsog_url);
    echo '<meta property="og:url" content="' . esc_url(apply_filters('$cetsog_url', $cetsog_url)) . '"/>' . "\n";

    $cetsog_images = array();
    if (function_exists('cets_get_og_images'))
        $cetsog_images = cets_get_og_images();

    //**output the image options
    if (!empty($cetsog_images) && is_array($cetsog_images)) {
        foreach (array_reverse($cetsog_images) as $image) {
            echo '<meta property="og:image" content="' . apply_filters('cetsog_images', $image) . '"/>' . "\n";
        }
    }
}

function cets_get_og_images() {
    /* og:image */

    global $post;

    $cetsog_images = array();
    $cetsog_default_image = cets_og_get_network_default_image();
    $cetsog_custom_default_image = get_option('cets_og_default_image_source', '');

    // 1a) if there is a post thumbnail, add it
    if (function_exists('has_post_thumbnail') && isset($post) && has_post_thumbnail($post->ID)) {
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium');
        $cetsog_images[] = $thumbnail_src[0]; // Add to images array
    }

    // 1b) if using external author plugin, use that
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    if (is_plugin_active('external-author/external-author.php') && is_plugin_active('lems-judge-image-helper/lems-judge-image-helper.php')) {
        $external_authors = get_post_meta($post->ID, '_external_authors', true);
        $featured_index = get_post_meta($post->ID, '_external_authors_featured', true);
        if (isset($external_authors[$featured_index])) {
            $featured_author = $external_authors[$featured_index];
            if ($featured_author && !empty($featured_author['dci'])) {
                $cetsog_images[] = get_source_from_dci($featured_author['dci']); // Add to images array
            }
        }
    }

    //2a) add the custom default image
    if (isset($cetsog_custom_default_image) && $cetsog_custom_default_image != '') {
        $cetsog_images[] = $cetsog_custom_default_image;
    }

    //2b) add the standard default image
    if (isset($cetsog_default_image) && $cetsog_default_image != '') {
        $cetsog_images[] = $cetsog_default_image;
    }

//    global $post, $posts;
//    if (isset($post)) {
//        $content = $post->post_content;
//    }

    //3) add the first three post/page images
//	if (isset($content) && $content) {
//		$dom = new DOMDocument;
//		libxml_use_internal_errors(true);
//		$dom->loadHTML($content);
//		$dom->preserveWhiteSpace = false;
//		$images = $dom->getElementsByTagName('img');
//
//		$count = 0;
//		foreach ($images as $image) {
//			if($count == 3)
//	       		break;
//			$cetsog_images[] = $image->getAttribute('src');
//			$count++;
//		}
// 	}
    return $cetsog_images;
}
