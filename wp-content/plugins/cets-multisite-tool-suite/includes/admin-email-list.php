<?php


function cesuite_page_admin_email_list() {

	$arr_admin_emails = array();
	$blogs = _cesuite_get_blogs();

	_cesuite_page_head('CESuite - Admin Emails');

	foreach ($blogs as $blog) {
		
		switch_to_blog($blog->blog_id);

		$args = array(
			'who' => 'administrators'
			);
		$users = get_users(array('role'=>'administrator'));
		foreach( $users as $user) {
			array_push($arr_admin_emails, $user->user_email);
		}

		restore_current_blog();
	}

	
	echo "<p>The following emails are associated with one or more site admin accounts on the network.  I'd suggest just clicking in the textarea, then using ctrl+A to select all the text.</p>";

	$arr_admin_emails = array_unique($arr_admin_emails);


	echo "<h2>Email List Format</h2>";

	echo "<textarea rows='10' cols='80'>"; echo implode($arr_admin_emails,",");  echo "</textarea>";



	echo "<hr>";

	echo "<h2>LDAP Bulk Add Format</h2>";

	echo "<textarea rows='12' cols='80'>";

		foreach($arr_admin_emails as $admin_email) {

			$email_no_at = substr($admin_email, 0, strpos($admin_email, '@'));
			echo $email_no_at . "\n";

		}

	echo "</textarea>";



	_cesuite_page_foot();
}
