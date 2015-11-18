<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Judge_Familiar
 */

if ( ! function_exists( 'judge_familiar_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function judge_familiar_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		'<a href="%s" rel="bookmark">%s</a>',
		esc_url( get_permalink() ),
		$time_string
	);

	$author_html = sprintf(
		'<a class="url fn n" href="%s">%s</a>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() )
	);
	$no_authors = false;

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'external-author/external-author.php' ) ) {
		$external_authors = get_post_meta( get_the_ID(), '_external_authors', true );
		$no_authors = get_post_meta( get_the_ID(), '_external_authors_no_author', true );

		if ( !$no_authors && $external_authors && count( $external_authors ) ) {
			$author_html = '';
			foreach ( $external_authors as $key => $author) {
				if ( is_plugin_active( 'lems-judge-image-helper/lems-judge-image-helper.php' ) && ! empty( $author['dci'] ) ) {
					$single_author_html = do_shortcode( sprintf( '[judge dci=%2$s]%1$s[/judge]',
						esc_html( $author['name'] ),
						esc_html( $author['dci'] )
					) );
				} else {
					$single_author_html = esc_html( $author['name'] );
				}

				$author_html .= sprintf( '<span class="author vcard">%1$s</span>',
					$single_author_html
				);

				if ( $key < count( $external_authors ) - 2 ) {
					$author_html .= ', ';
				} else if ( $key < count( $external_authors ) - 1 ) {
					$author_html .= ' ' . __( ' and ', 'judge-familiar' );
				}
			}
		}
	}

	if ($no_authors) {
			$byline = '';
	} else {
		$byline = sprintf(
			'| <span class="authors">%s</span>',
			$author_html
		);
	}

	printf(
		'<span class="posted-on">%s</span> <span class="byline">%s</span>',
		$posted_on,
		$byline
	);

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'judge-familiar' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

if ( ! function_exists( 'judge_familiar_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function judge_familiar_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'judge-familiar' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'judge-familiar' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'judge-familiar' ), esc_html__( '1 Comment', 'judge-familiar' ), esc_html__( '% Comments', 'judge-familiar' ) );
		echo '</span>';
	}
}
endif;

/**
 * Adds categories slugs as classes to the category in the loop to allow styling.
 */
if ( ! function_exists( 'judge_familiar_category_classes' ) ) :
function judge_familiar_category_classes() {
	echo 'cat-links';
	$categories = get_the_category();
	foreach ( $categories as $cat ) {
		echo ' ' . $cat->slug;
	}
}
endif;

if ( ! function_exists( 'judge_familiar_featured_author' ) ) :
function judge_familiar_featured_author() {
	$image_html = '';
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'external-author/external-author.php' )
	     && is_plugin_active( 'lems-judge-image-helper/lems-judge-image-helper.php' )
	) {
		$external_authors = get_post_meta( get_the_ID(), '_external_authors', true );
		$featured_index = get_post_meta( get_the_ID(), '_external_authors_featured', true );
		$featured_author = false;
		if ( isset( $external_authors[ $featured_index ] ) ) {
			$featured_author = $external_authors[ $featured_index ];
		}

		if ( $featured_author && ! empty( $featured_author['dci'] ) ) {
			$image_html =
				'<div class="featured-image">
					<img src="' . get_source_from_dci( $featured_author['dci'] ) . '" class="wp-post-image" alt="' . htmlentities( $featured_author['name'] ) . '">
				</div>';
		}
	}
	echo $image_html;
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function judge_familiar_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'judge_familiar_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'judge_familiar_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so judge_familiar_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so judge_familiar_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in judge_familiar_categorized_blog.
 */
function judge_familiar_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'judge_familiar_categories' );
}
add_action( 'edit_category', 'judge_familiar_category_transient_flusher' );
add_action( 'save_post',     'judge_familiar_category_transient_flusher' );
