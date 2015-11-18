<?php
/*
Plugin Name: Callout Box (CETS)
Description: Creates a shortcode for displaying a stylized callout box within your content
Version: 1.0
Author: Chrissy Dillhunt & Jason Lemahieu
*/

if ( is_admin() ) {
	

	/*************
	   BACKEND
	  ***********/

	function cets_callout_box_modal_content() {
		?>
			<div id="cets_callout_box" style="display:none;">
				<div class="wrap">
					<?php
					if (function_exists('cets_link_library_get_link')) {
						$help_link = cets_link_library_get_link('PLUGIN_CALLOUT_BOX');
						if ($help_link) {
							echo "<div class='cets-help-box'>";
							echo "<p>For more information about using a Callout Box, check out our <a target='_blank' href='{$help_link}'>Help Article</a>.</p>";
							echo "</div>";
						}
					}
					?>

					<h3>Callout Box Style</h3>
                    
                    <p> Select from the options below to insert a shortcode into your post or page that creates a callout box. Enter the content you want to display in the callout box in between the opening and closing shortcode. </p>
                    
                    <p>Example: <br /> [cets_callout_box style='blue' align='right' title='My Title']This is the content in my callout box.[/cets_callout_box]</p>
                    
                    <form>
						Title (Optional):  <input type="text" name="cets_callout_box_title" id="cets_callout_box_title"><br>
					</form>
                    
                    <p>
                       Style: 
                        <select name="cets_callout_box_style" id="cets_callout_box_style">
                            <option value="gray">Gray (Default)</option>
                            <option value="blue">Blue</option>
                            <option value="green">Green</option>
                            <option value="yellow">Yellow</option>
                        </select>
                    </p>
                    
                    
                    <p>
                       Size and Alignment: 
                        <select name="cets_callout_box_align" id="cets_callout_box_align">
                            <option value="left">Small Left</option>
                            <option value="right">Small Right</option>
                            <option value="full">Large Center</option>
                        </select>
                    </p>
	                
	                              
					<a href="#" class="button" onClick="cets_callout_box_send_to_editor('[sample-shortcode]'); return false;">Insert Into Content</a>
	           </div><!-- cets_callout_box -->
	     
				</div>
			</div>
		<?php
	}

	add_action('admin_enqueue_scripts', 'cets_callout_box_admin_enqueue_scripts');
	function cets_callout_box_admin_enqueue_scripts() {
		wp_enqueue_script( 'cets_callout_box_admin', plugin_dir_url(__FILE__) . "js/admin.js" );
		wp_enqueue_style( 'cets_callout_box_css', plugin_dir_url(__FILE__) . "css/admin.css" );
	}




	function cets_callout_box_media_button() {
		add_thickbox();
		add_action( 'admin_footer', 'cets_callout_box_modal_content' );

		$href = "#TB_inline?width=640&height=525&inlineId=cets_callout_box";
		$src = plugins_url('img/cets_callout_box-16.png',__FILE__);;

		echo "<a href='{$href}' class='thickbox button cets_callout_box' title='Callout Box' id='callout_box_button'><span class='cets_callout_box_button'></span>Callout Box</a>";
	}
	add_action('media_buttons', "cets_callout_box_media_button", 24);

} else {

	/*************
	   FRONTEND
	  ***********/

	function cets_callout_box_shortcode( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'align' => 'right',
			'style' => 'gray',
			'title' => ''
		), $atts, 'cets_callout_box' ) );
		if ($atts['title'] != '') {
			return '<div class="cets-callout-box ' . $atts['align'] . ' ' . $atts['style'] . '"> <h3 class="callout-box-title">' . $atts['title'] . '</h3>' . do_shortcode($content) . '<p class="clear"></p></div>'; 
		} else {
			return '<div class="cets-callout-box ' . $atts['align'] . ' ' . $atts['style'] . '">' . do_shortcode($content) . '<p class="clear"></p></div>'; 
		}

	}
	add_shortcode( 'cets_callout_box', 'cets_callout_box_shortcode' );

		function cets_callout_box_enqueue_scripts() {
		wp_register_style('cets_callout_box-style', plugins_url('css/public.css', __FILE__));
		wp_enqueue_style('cets_callout_box-style');
	}
	add_action('wp_enqueue_scripts', 'cets_callout_box_enqueue_scripts');
}

