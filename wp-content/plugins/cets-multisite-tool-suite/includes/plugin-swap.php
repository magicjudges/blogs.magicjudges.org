<?php

function cesuite_page_swap_plugins() {
	_cesuite_page_head('CESuite - Swap Plugins');


	if (isset($_POST['cesuite_submitted']) && $_POST['cesuite_submitted'] =='Go') {

		$old = $_REQUEST['old_plugin'];
		$new = $_REQUEST['new_plugin'];

		$sites = wp_get_sites( array('limit' => 9999) );
		
		foreach ($sites as $site) {
			
			switch_to_blog($site['blog_id']);

			if ( is_plugin_active( $old ) ) {

				if ( is_plugin_active( $new ) ) {
					echo "{$site['path']}: {$new} already active";
				} else {
					activate_plugin( $_REQUEST['new_plugin'] );
					echo "{$site['path']}: {$new} ACTIVATED";
				}
				
				if (isset($_REQUEST['deactivate_original']) && $_REQUEST['deactivate_original'] == 1) {
					deactivate_plugins($_REQUEST['old_plugin']);
					echo " - deactivated {$old}";
				}
				echo "<br>";
			}

			restore_current_blog();
		}

	} else {
		_cesuite_form_start();

		echo "<p>Find all sites with the following plugin currently active:</p>";

		_cesuite_input_field_plugin('old_plugin');

		echo "<p>And activate the following plugin:</p>";

		_cesuite_input_field_plugin('new_plugin');


		?>
		<br><br><input type='checkbox' name='deactivate_original' value='1'>
		 &nbsp;Then deactivate the original plugin.

		<?php


		_cesuite_form_end();
	}


	_cesuite_page_foot();
}