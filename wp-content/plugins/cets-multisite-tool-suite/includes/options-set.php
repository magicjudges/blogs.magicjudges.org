<?php

/*** SET OPTIONS ********************************************************************************************************************************************************** SET OPTIONS *** */
function cesuite_page_set_options() {
	_cesuite_page_head('CESuite - Set Options');

	if (!(isset($_POST['cesuite_submitted']) && $_POST['cesuite_submitted'] =='Go')) {
		//display form
		_cesuite_page_description("Use this form to set an option on every blog in the network to a given value.");
		_cesuite_form_start();
		_cesuite_input_field('cesuite_option_name', 'Option Name');
		echo "<br/>";
		_cesuite_input_field('cesuite_option_value', 'Option Value');
		_cesuite_form_end();
		
	} else {
		//handler
		if ($_POST['cesuite_option_name'] == '') {
			wp_die('You must enter an option name to set.', 'Option Name Required', array('back_link'=>true));
		}
	
		_cesuite_repeat_link("Set Another Option");
		
		?>
		<table class='widefat'>
		<tr>
			<th>Site</th><th>Option Name</th><th>Old Value</th><th>New Value</th>
		</tr>
		<?php
		
		$blogs = _cesuite_get_blogs();
		foreach ($blogs as $blog) {
			
			switch_to_blog($blog->blog_id);
			
			$old_option = get_option($_POST['cesuite_option_name'], 'cesuite-default');
			$updated = update_option($_POST['cesuite_option_name'], $_POST['cesuite_option_value']);
			
			if ($updated) {
				$row_class = "class='cesuite-emphasis'";
			} else {
				$row_class = '';
			}
			
			echo "<tr $row_class>";
			
			/* SITE */
			echo "<td>";
			_cesuite_site_link();
			echo "</td>";
			
			/* Option name */
			echo "<td>";
			echo $_POST['cesuite_option_name'];
			echo "</td>";
			
			/* old value */
			echo "<td>";
			if ($old_option == 'cesuite-default') {
				echo "{NOT FOUND}";
			} else { 
				_cesuite_print_option_value($old_option);
			}
			echo "</td>";
			
			/* new value */
			echo "<td>" ;
			_cesuite_print_option_value($_POST['cesuite_option_value']);
			echo "</td>";
			
			echo "</tr>";
		
			restore_current_blog();
		}			
		echo "</table>";
		_cesuite_repeat_link("Set Another Option");
	}
	_cesuite_page_foot();
}