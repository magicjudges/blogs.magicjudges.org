<?php


function cets_content_blocks_media_button() {
	
	// only do this if i have any blocks
	if ( ! cets_content_blocks_has_valid_blocks() ) {
		return;
	}

	// do not show while adding|editing other Content Blocks
	$screen = get_current_screen();
	if ($screen->post_type == 'cets_content_block') {
		return;
	}

	add_thickbox();
	add_action( 'admin_footer', 'cets_content_blocks_thickbox_content' );

	$href = "#TB_inline?width=640&height=525&inlineId=cets_content_blocks";

	echo "<a href='{$href}' class='thickbox button cets_content_blocks' title='Insert Block' id='content_blocks_button'><span class='cets_content_blocks_media_button'></span>Block</a>";

	wp_enqueue_script('cets-content-blocks-admin-js', plugins_url('/js/cets-content-blocks-admin.js', __FILE__), 'jquery', null, true );
	wp_enqueue_style('cets-content-blocks-admin-css', plugins_url('/css/cets-content-blocks-admin.css', __FILE__) );
}
add_action('media_buttons', "cets_content_blocks_media_button", 21);


function cets_content_blocks_thickbox_content() { 
		
	?>

		<div id="cets_content_blocks" style="display:none;">
			<div class="wrap">      

					<p><strong>Choose a Content Block:</strong></p>
					
					<?php
					cets_content_blocks_select_box( 'cets_content_block_select', 'cets_content_block_select' );
					?>

				<p>
					<a class="button" href="#" onClick="cets_content_blocks_send_to_editor();">Insert into Content</a>
				</p>
					




			</div>
		</div>
		
	<?php
}


