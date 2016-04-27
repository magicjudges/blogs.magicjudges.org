<?php
/*
Plugin Name: Google Analytics

Description: MU Plugin for outputting network analytics code.
Author: Jason Lemahieu, Joel Krebs
Version: 0.2
Author URI:
*/

add_action('wpmu_options', 'google_analytics_add_options', 5, 0);
add_action('update_wpmu_options', 'google_analytics_update_options', 5, 0);

function google_analytics_add_options()
{
		$google_analytics = get_site_option('google_analytics', array(
			'ua' => '',
			'content_groups' => true,
		));
?>
<h3>Network Analytics Settings</h3>
<table class='form-table'>
	<tr>
		<th>
			Google Analytics UA
		</th>
		<td>
			<input type='text' name='google_analytics_ua' value='<? echo esc_html($google_analytics['ua']) ?>'>
		</td>
	</tr>
		<th>
			Group By Page
		</th>
		<td>
			<input type='checkbox' name='google_analytics_content_groups' <?php checked( $google_analytics['content_groups'] ); ?>/>
		</td>
	</tr>
</table>
<?php
}

function google_analytics_update_options()
{
	$options = array(
		'ua' => '',
		'content_groups' => true,
	);
	if (isset($_REQUEST['google_analytics_ua'])) {
		$options['ua'] = trim(wp_filter_nohtml_kses($_REQUEST['google_analytics_ua']));
	}
	if (!isset($_REQUEST['google_analytics_content_groups'])) {
		$options['content_groups'] = false;
	}
	update_site_option('google_analytics', $options);
}

function google_analytics_output_code()
{
	//hide stat tracking from anyone who is a Contributor or above
	if (!current_user_can('edit_posts')) :
		$google_analytics = get_site_option('google_analytics', array(
			'ua' => '',
			'content_groups' => true,
		));
		if ($google_analytics['ua']) :
?>
<!-- Google Analytics -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', '<?php echo esc_html($google_analytics['ua']) ?>', 'auto');
<?php
	if ($google_analytics['content_groups']) {
		$site = get_blog_details(get_current_blog_id(), true);
		echo "ga('set', 'contentGroup1', '".$site->blogname."');\n";
	}
?>
ga('send', 'pageview');
</script>
<!-- End Google Analytics -->
<?php
		endif;
	endif;
}

add_action('wp_head', 'google_analytics_output_code');
