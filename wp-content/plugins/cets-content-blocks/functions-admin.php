<?php

function cets_content_blocks_select_box( $select_name = 'cets_content_block_select', $select_id = 'cets_content_block_select', $content_block_id = '' ) {
	$the_query = cets_content_blocks_get_valid_blocks_query();
	
	if ( $the_query->have_posts() ) {

		echo "<select name='{$select_name}' id='{$select_id}'>";	

		?>
			<!-- the loop -->
				<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
					<option value="<?php the_ID(); ?>" <?php selected( get_the_ID(), $content_block_id ); ?> ><?php the_title(); ?></option>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<!-- end of the loop -->
			<?php
		echo "</select>";
	}
}

function cets_content_blocks_has_valid_blocks() {

	$the_query = cets_content_blocks_get_valid_blocks_query();

	if ( $the_query->have_posts() ) {
		return true;
	} else {
		return false;
	}
}

function cets_content_blocks_get_valid_blocks_query() {
	$wpq_args = array(
			'post_type' => 'cets_content_block',
			'posts_per_page' => -1,
			'order' => 'ASC',
			'orderby' => 'title'
			);

	$the_query = new WP_Query( $wpq_args );

	return $the_query;

}


// remove quick edit stuff
add_filter( 'post_row_actions', 'cets_content_blocks_post_row_actions', 10, 2 );

function cets_content_blocks_post_row_actions( $actions, $post ) {

	if ( $post->post_type != 'cets_content_block' ) {
		return $actions;
	}	

	unset( $actions['inline hide-if-no-js'] );

	// View and Preview automatically hidden because not a public custom post type

	return $actions;

}

function cets_content_blocks_get_create_new_link() {
	return admin_url('post-new.php?post_type=cets_content_block');
}


/* MetaBox Help */
add_action( 'add_meta_boxes', 'cets_content_block_add_meta_box' );

function cets_content_block_add_meta_box() {
	add_meta_box( 'cets_content_block_meta_box', 'Content Block Help', 'cets_content_blocks_meta_box_callback', 'cets_content_block', 'normal', 'default' );

	// remove expiration date

}

function cets_content_blocks_meta_box_callback( $post ) {
	cets_content_blocks_print_help_link();
}

function cets_content_blocks_print_help_link() {
	// get fuller help link
	$widgets_url = admin_url( 'widgets.php' );

	if ( function_exists( 'cets_link_library_get_link' ) ) {
		$help_link = cets_link_library_get_link( 'PLUGIN_CONTENT_BLOCKS' );
	}

	?>
		<div class='cets-help-box'>
			<p>Content Blocks are reusable pieces of rich text content that can either be inserted into a Page or Post, or used as a Widget.</p>
			<p>Publishing a content block won't make the content appear on your site until it is added as a <a tareget='_blank' href='<?php echo $widgets_url; ?>'>Widget</a> or inserted onto a page or post using a Shortcode.</p>
			<?php
				if ( $help_link ) {
					echo "<p>For more information, <a href='{$help_link}'>read our Help Article on Content Blocks</a>.<p>";
				}	
			?>
		</div>

	<?php
}

// remove page links to integration
add_filter( 'page-links-to-post-types', 'cets_content_blocks_page_links_to_post_types', 10, 2 );

function cets_content_blocks_page_links_to_post_types( $types ) {

	foreach ($types as $key => $type) {
		if ( $type == 'cets_content_block' ) {
			unset( $types[$key] );
		}
	}
	return $types;
}

// remove View Post after saving a Content Block
add_filter( 'post_updated_messages', 'cets_content_blocks_filter_post_updated_message' );

function cets_content_blocks_filter_post_updated_message( $messages ) {
	global $post, $post_ID;

	if ( $post->post_type != 'cets_content_block' ) {
		return $messages;
	}

	$messages['cets_content_block'][1] = 'Block updated.';
	$messages['cets_content_block'][4] = 'Block updated.';
	$messages['cets_content_block'][6] = "Block published.";
	$messages['cets_content_block'][7] = "Block saved.";
	$messages['cets_content_block'][10] = "Block draft updated.";

	return $messages;

}