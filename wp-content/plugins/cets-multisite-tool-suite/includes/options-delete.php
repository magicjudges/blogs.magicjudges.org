<?php


/*** DELETE OPTIONS ********************************************************************************************************************************************************** DELETE OPTIONS *** */
function cesuite_page_delete_options() {
	_cesuite_page_head('CESuite - Delete Options');
	
	if (!(isset($_POST['cesuite_submitted']) && $_POST['cesuite_submitted'] =='Go')) {
	
	_cesuite_page_description("Use this form to delete a specified option from every site.");
	_cesuite_form_start();
	_cesuite_input_field('cesuite_option_name', 'Option Name');
	_cesuite_form_end();
	
	} else {
		//handler
		
		if ($_POST['cesuite_option_name'] == '') {
			wp_die('You must enter an option name to set.', 'Option Name Required', array('back_link'=>true));
		}
		
		_cesuite_repeat_link("Delete Another Option");
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
			$deleted = delete_option($_POST['cesuite_option_name']);
			
			if ($deleted) {
				$row_class = "class='cesuite-emphasis'";
			} else {
				$row_class = '';
			}
			
			echo "<tr $row_class>";
			
			/* Site name and link */
			echo "<td>";
			_cesuite_site_link();
			echo "</td>";
			
			/* option name */
			echo "<td>";
			echo  $_POST['cesuite_option_name']; 
			echo "</td>";
			
			/* old value */
			echo "<td>";
			if ($old_option == 'cetsuite-default') {
				echo "{EMPTY}";
			} else {
				 _cesuite_print_option_value($old_option);
			}
			echo "</td>";
			
			
			echo "<td>";
			if ($deleted) {
				echo "-DELETED-";
			} else {
				echo "-skipped-";
			}
			echo "</td></tr>";
			
			restore_current_blog();
		}
		echo "</table>";
		_cesuite_repeat_link("Delete Another Option");
	}
	
	_cesuite_page_foot();
}