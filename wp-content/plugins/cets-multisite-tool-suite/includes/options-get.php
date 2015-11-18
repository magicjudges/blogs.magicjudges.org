<?php

/*** GET OPTIONS ********************************************************************************************************************************************************** GET OPTIONS *** */
function cesuite_page_get_options() {
	_cesuite_page_head('CESuite - Get Options');
	
	if (!(isset($_POST['cesuite_submitted']) && $_POST['cesuite_submitted'] =='Go')) {
		//display form
		_cesuite_page_description("Use this form to get an option from every blog.");
		_cesuite_form_start();
		_cesuite_input_field('cesuite_option_name', 'Option Name');
		
		_cesuite_form_end();
	
	} else {
		// handler
		_cesuite_repeat_link("Get Another Option");
		?>
		<table class='widefat'>
		<tr><th>Site</th><th>Option Name</th><th>Option Value</th>
		<?php
		
		$blogs = _cesuite_get_blogs();
		foreach ($blogs as $blog) {
			
			switch_to_blog($blog->blog_id);
			$current_option = get_option($_POST['cesuite_option_name'], 'cesuite-default');
			
			echo "<tr>";
			
			/* SITE */
			echo "<td>";
			_cesuite_site_link();
			echo "</td>";
			
			/* Option name */
			echo "<td>";
			echo $_POST['cesuite_option_name'];
			echo "</td>";
			
			/* Option Value */
			echo "<td>";
			if ($current_option == 'cesuite-default') {
				echo "{NOT FOUND}";
			} else {
				_cesuite_print_option_value($current_option);
			}
			echo "</td>";
			
			echo "</tr>";
			restore_current_blog();
		}
		
		echo "</table>";
		_cesuite_repeat_link("Get Another Option");
	}
	
	_cesuite_page_foot();
	
} //cesuite_page_set_options()