<?php
/*
Plugin Name: Plugin Commander (CETS)
Plugin URI: 
Description: Plugin Commander is a plugin management plugin for WP Multisite
Version: 2.0.cets
Author: Omry Yadan.  (cleaned up for WordPress 3.3-3.5 by Jason Lemahieu)
Author URI: http://profiles.wordpress.org/users/madtownlems
License: GPL (see http://www.gnu.org/copyleft/gpl.html)

Instructions: copy into mu-plugins  
*/

/* 
changelog
	2.0 - Fixed TWO bugs. 1: no Mass Deactivate message showing up. 2. Mass Deactivate disabling other stuff
	1.9.cets*
		- remove version number from user table
	1.7.7.cets:
		remove author info from plugin page
	1.7.6.cets:
		added text about visiting dashboard to make menu items appear.
		added <br>'s between action links to support multiple.
	1.7.5.cets:
	 - removed author column from network display
	 - added indication of sidewide activation to network display
	 - added alternating row colors to network admin page

*/

function pc_textdomain()
{
    global $locale;
    $old = $locale;
    //$locale = WPLANG;
    load_plugin_textdomain('plugin-commander', '', 'wp-content/mu-plugins/plugin-commander-i18n');
    $locale = $old;
}


add_action('init', 'pc_textdomain');
add_action('network_admin_menu', 'pc_add_menu_network');
add_action('admin_menu', 'pc_add_menu_site');

add_action('wpmu_new_blog','pc_new_blog');

define( 'PC_CMD_BASE',substr($_SERVER["SCRIPT_NAME"],  strrpos($_SERVER["SCRIPT_NAME"],"/")+1)."?page=PluginCommander");

define( 'PC_PLUGINS_HOME', admin_url('edit.php') );
define( 'PC_PLUGINS_CMD_BASE', PC_PLUGINS_HOME."?page=Plugins" );

function pc_add_menu_network() {
	add_submenu_page('plugins.php', 'Plugin Commander', 'Plugin Commander', 'manage_network', 'PluginCommander', 'pc_page');
}

function pc_add_menu_site() {
	
	// only show plugins menu to site admins or if the can control one plugin or more.
	if 	( is_super_admin() || 
		( strlen(get_site_option('pc_user_control_list') ) > 0 && current_user_can('activate_plugins') ) )
	{
		
		add_submenu_page('edit.php',
				__('Plugins','plugin-commander'), 
				__('Plugins','plugin-commander'), 
				'activate_plugins',
				'Plugins', 
				'pc_user_plugins_page');
	}

	if ( !is_super_admin() ) {
		remove_menu_page('plugins.php');
	}
}



function pc_user_plugins_page() {
	pc_handle_plugins_cmd();
?>

	<div class='wrap'>
	<h2><?php _e('Manage plugins', 'plugin-commander'); ?></h2>
	<?php if (is_super_admin())
	{
	?>
	<?php _e('As site administrator, you can activate or deactivate all plugins for this blog,<br/>even plugins that are not under user control in Site Admin -> Plugin Commander.<br/>Plugins that are under user control are marked in grey.', 'plugin-commander'); ?><br/>

	<?php
	}
	?>

	<p>
	Please only activate plugins that you are going to use.  Activating unnecessary plugins hurts server performance and makes it difficult for us to determine which sites are really using each plugin.
	</p>

	<?php
		echo"<p><strong>Note:</strong> If a newly activated plugin adds Menu Items to your main menu (on the left), you'll need to <a href='" . admin_url() . "'>visit your dashboard</a> before they appear.</p>";
	?>

	<table class="widefat">

		<!-- Header row in USER Table -->
		<thead>
		<tr>
			<th><?php _e('Name', 'plugin-commander'); ?></th>
			<th><?php _e('Description', 'plugin-commander'); ?></th>
			<th><?php _e('Action', 'plugin-commander'); ?></th>
			<th><?php _e('Quick Links', 'plugin-commander'); ?></th>
		</tr>
		</thead>
		<tbody>
	<?php
	$plugins = get_plugins();

	$user_control = explode(',',get_site_option('pc_user_control_list'));
	$active_plugins = pc_get_active_plugins();

	foreach( $plugins as $file=>$p ) {

		$user_controls = in_array($file, $user_control);

		if ( !is_super_admin() && !$user_controls ) {
			continue;
		}
		$bg = $user_controls ? "#eeeeee" : "#ffffff";
		

		/* Single Row in USER Table */
		?>
		<tr style="background-color:<?php echo $bg;?>;">
			<td><?php echo $p['Name']; ?></td>
			<td><?php echo $p['Description']; ?></td>
			<td>
			<?php 
				$checked = in_array( $file, $active_plugins );
				if ($checked)
				{
					$cmd = "deactivate=$file";
					$text = "<span style='color:#9e0b0f;'><strong>".__('Deactivate', 'plugin-commander')."</strong></span>";
				}
				else
				{
					$cmd = "activate=$file";
					$text = "<span class='pc_off'>".__('Activate', 'plugin-commander')."</span>";
				}

				
				$href = esc_url( PC_PLUGINS_CMD_BASE . "&$cmd") ;
				echo "<a href='{$href}'>$text</a>";

				
			?>
			</td>
			
			<td>
			<?php // Quick Links for USER table
				if ( in_array($file, $active_plugins )) {
					$more_actions = array();
					
					$content = "dropins";
					// removed vars at end: $context
					$more_actions = apply_filters( 'plugin_action_links', array_filter( $more_actions ), $file, $p);
					$more_actions = apply_filters( "plugin_action_links_$file", $more_actions, $file, $p);
					
					foreach ( $more_actions as $action ) {
						
						echo $action . "<br/>";
					}
				} else {
					echo "&nbsp;";
				}	
			?>
			</td>
			
		</tr>
	<?php
	}
	echo "</tbody></table></div>";
}


function pc_page() {
	if ( !is_super_admin() ) {
	 return;
	}
	pc_handle_command();

	?>
	<div class='wrap'>
	<h2><?php _e('Manage plugins', 'plugin-commander'); ?></h2>

	<?php if (isset($_REQUEST['auto_activate_on']) && $_REQUEST['auto_activate_on']) { ?>
	<div id="message" class="updated fade"><p><?php _e('Update: AUTO-ACTIVATE for', 'plugin-commander')?> <span style="color:#FF3300;"><?=$_REQUEST['auto_activate_on']?></span> <?php _e('has been turned', 'plugin-commander')?> <strong><?php _e('ON', 'plugin-commander')?></strong>.</p></div>
	<?php } ?>
	<?php if (isset($_REQUEST['auto_activate_off']) && $_REQUEST['auto_activate_off']) { ?>
	<div id="message" class="updated fade"><p><?php _e('Update: AUTO-ACTIVATE for', 'plugin-commander')?> <span style="color:#FF3300;"><?=$_REQUEST['auto_activate_off']?></span> <?php _e('has been turned', 'plugin-commander')?> <strong><?php _e('OFF', 'plugin-commander')?></strong>.</p></div>
	<?php } ?>
	<?php if (isset($_REQUEST['user_control_on']) && $_REQUEST['user_control_on']) { ?>
	<div id="message" class="updated fade"><p><?php _e('Update: USER CONTROL for', 'plugin-commander')?> <span style="color:#FF3300;"><?=$_REQUEST['user_control_on']?></span> <?php _e('has been', 'plugin-commander')?> <strong><?php _e('ENABLED', 'plugin-commander')?></strong>.</p></div>
	<?php } ?>
	<?php if (isset($_REQUEST['user_control_off']) && $_REQUEST['user_control_off']) { ?>
	<div id="message" class="updated fade"><p><?php _e('Update: USER CONTROL for', 'plugin-commander')?> <span style="color:#FF3300;"><?=$_REQUEST['user_control_off']?></span> <?php _e('has been', 'plugin-commander')?> <strong><?php _e('DISABLED', 'plugin-commander')?></strong>.</p></div>
	<?php } ?>
	<?php if (isset($_REQUEST['mass_activate']) && $_REQUEST['mass_activate']) { ?>
	<div id="message" class="updated fade"><p><?php _e('Update', 'plugin-commander')?>: <span style="color:#FF3300;"><?=$_REQUEST['mass_activate']?></span> <?php _e('has been', 'plugin-commander')?> <strong><?php _e('MASS ACTIVATED', 'plugin-commander')?></strong>.</p></div>
	<?php } ?>
	<?php if (isset($_REQUEST['mass_deactivate']) && $_REQUEST['mass_deactivate']) { ?>
	<div id="message" class="updated fade"><p><?php _e('Update', 'plugin-commander')?>: <span style="color:#FF3300;"><?=$_REQUEST['mass_deactivate']?></span> <?php _e('has been', 'plugin-commander')?> <strong><?php _e('MASS DEACTIVATED', 'plugin-commander')?></strong>.</p></div>
	<?php } ?>


	<h3><?php _e('Help', 'plugin-commander'); ?></h3>
	<p><strong><?php _e('Auto activation', 'plugin-commander'); ?></strong><br/>
	<?php _e('When auto activation is on for a plugin, newly created blogs will have that plugin activated automatically.<br/>this does not affect existing blogs.', 'plugin-commander'); ?></p>
	
	<p><strong><?php _e('User control', 'plugin-commander'); ?></strong><br/>
	<?php _e('When user control is enabled for a plugin, all users will be able to activate/deactivate the plugin through the <cite>Manage->Plugins</cite> menu.<br/>This menu will only appear if there is at least one plugin with user control enabled.<br/>Note: if you want to use this, be sure to disable the built-in plugins menu from <cite>Site Admin->Options->Menus</cite> to prevent users from activating plugins which should not be under user control.', 'plugin-commander'); ?></p>
	
	<p><strong><?php _e('Mass activation/deactivation', 'plugin-commander'); ?></strong><br/>
	<?php _e('Mass activate and Mass deactivate buttons activate/deactivates the specified plugin for all blogs. this only affects existing blogs.', 'plugin-commander'); ?></p>
	
	<table class="widefat">
		<tr>
			<th><?php _e('Name', 'plugin-commander'); ?></th>
			<th><?php _e('Version', 'plugin-commander'); ?></th>
			<th>Network Activated</th>
			<th title="<?php _e('Automatically activate for new blogs', 'plugin-commander'); ?>"><?php _e('Auto-activate', 'plugin-commander'); ?></th>
			<th title="<?php _e('Users may activate/deactivate', 'plugin-commander'); ?>"><?php _e('User control', 'plugin-commander'); ?></th>
			<th><?php _e('Mass activate', 'plugin-commander'); ?></th>
			<th><?php _e('Mass deactivate', 'plugin-commander'); ?></th>
		</tr>
	<?php


	$plugins = get_plugins();
	$auto_activate = explode(',',get_site_option('pc_auto_activate_list'));
	$user_control = explode(',',get_site_option('pc_user_control_list'));
	$active_sitewide_plugins = maybe_unserialize( get_site_option( 'active_sitewide_plugins') );

	$row_number = 1;

	foreach( $plugins as $file=>$p ) {

		/* Single row in ADMIN table */
		?>
		<tr <?php if ($row_number % 2 == 1) { echo "class='alternate'"; }?> >
			<td><?php echo $p['Name']?></td>
			<td><?php echo $p['Version']?></td>
			<td><?php if (is_array($active_sitewide_plugins) && array_key_exists($file, $active_sitewide_plugins)) { echo ("NETWORK");} ?></td>
			<td>
			<?php 
				$checked = in_array( $file, $auto_activate );
				if ($checked)
				{
					$cmd = "auto_activate_off=$file";
					$text = "<span style='color:#9e0b0f;'><strong>".__('On, click to turn off', 'plugin-commander')."</strong></span>";
				}
				else
				{
					$cmd = "auto_activate_on=$file";
					$text = "<span class='pc_off'>".__('Off, click to turn on', 'plugin-commander')."</span>";
				}
				echo "<a href='".PC_CMD_BASE."&$cmd'>$text</a>";
			?>
			</td>
			<td>
			<?php 
				$checked = in_array( $file, $user_control );
				if ($checked)
				{
					$cmd = "user_control_off=$file";
					$text = "<span style='color:#9e0b0f;'><strong>".__('Enabled, click to disable', 'plugin-commander')."</strong></span>";
				}
				else
				{
					$cmd = "user_control_on=$file";
					$text = "<span class='pc_off'>".__('Disabled, click to enable', 'plugin-commander')."</span>";
				}
				echo "<a href='".PC_CMD_BASE."&$cmd'>$text</a>";
			?>
			</td>
			<td><?php echo "<a href='".PC_CMD_BASE."&mass_activate=$file'>".__('Activate all', 'plugin-commander')."</a>"?></td>
			<td><?php echo "<a href='".PC_CMD_BASE."&mass_deactivate=$file'>".__('Deactivate all', 'plugin-commander')."</a>"?></td>
		</tr>
	<?php
	$row_number++;
	}
	?>
	</table>
	</div>
	<?php
}

function pc_new_blog( $new_blog_id ) {

	// a work around wpmu bug (http://trac.mu.wordpress.org/ticket/497)
	global $wpdb;
	if (!isset($wpdb->siteid)) $wpdb->siteid = 1;
	$auto_activate_list = get_site_option('pc_auto_activate_list');
	$auto_activate = explode(',',$auto_activate_list);

	foreach($auto_activate as $plugin)	{
		pc_activate_plugin( $new_blog_id, $plugin, false );
	}
}


function pc_activate_plugin( $blog_id, $plugin, $check_access = true ) {
	
	if ( $check_access && !current_user_can('activate_plugins') ) {
		die('Plugin Commander: Access denied');
	}
	
	if (empty($plugin)) return;
	if (validate_file($plugin)) return;
	if (!file_exists(ABSPATH . PLUGINDIR . '/' . $plugin)) return;

	switch_to_blog($blog_id);

		$current = pc_get_active_plugins();
		ob_start();
		include_once(ABSPATH . PLUGINDIR . '/' . $plugin);
		$current[] = $plugin;
		sort($current);
		$current = array_unique($current);
		update_option('active_plugins', $current);
		do_action('activate_' . $plugin);
		$res = ob_get_clean();
		if (!empty($res)) echo __("Error activating $plugin for blog id=$blog_id: $res<br/>");
	
	restore_current_blog();
}

function pc_deactivate_plugin( $blog_id, $plugin ) {

	if (!current_user_can('activate_plugins')) {
			die('Plugin Commander: Access denied');
	}

	if (empty($plugin)) return;

	if (validate_file($plugin)) return;

	if (!file_exists(ABSPATH . PLUGINDIR . '/' . $plugin)) return;

	switch_to_blog($blog_id);

		$current = pc_get_active_plugins();

		//echo "Pre-off plugins:<br>";
		//var_dump( $current );


		$plugin_key = array_search($plugin, $current);

		if ( $plugin_key !== false ) {

			array_splice( $current, $plugin_key, 1 ); // Array-fu!
			$current = array_unique($current);
			update_option('active_plugins', $current);
			ob_start();
			do_action('deactivate_'.$plugin);
			$res = ob_get_clean();
			if (!empty($res)) echo "Error deactivating $plugin for blog id=$blog_id: $res<br/>";

		}

	restore_current_blog();
}

function pc_mass_activate( $plugin ) {
	
	if (!current_user_can('activate_plugins')) {
		die('Plugin Commander: Access denied');
	}
	

	global $wpdb;
	$res = $wpdb->get_results("select blog_id from wp_blogs");
	if ($res === false) 
	{
		echo "Failed to mass activate $plugin : error selecting blogs";
		return;
	}

	foreach( $res as $r )
	{
		pc_activate_plugin($r->blog_id, $plugin);
	}
}


function pc_mass_deactivate( $plugin ) {

	if ( ! current_user_can('activate_plugins') ) {
		die('Plugin Commander: Access denied');
	}
	global $wpdb;
	$res = $wpdb->get_results("select blog_id from wp_blogs");
	if ($res === false) 
	{
		echo "Failed to mass deactivate $plugin : error selecting blogs";
		return;
	}

	foreach( $res as $r )
	{
		pc_deactivate_plugin( $r->blog_id, $plugin );
	}
}


function pc_handle_plugins_cmd() {

	if (isset($_GET['activate'])) {
		$plugin = $_GET['activate'];
		global $blog_id;
		pc_activate_plugin($blog_id, $plugin);
	}

	if (isset($_GET['deactivate']))	{
		$plugin = $_GET['deactivate'];
		global $blog_id;
		pc_deactivate_plugin( $blog_id, $plugin );
	}

}

function pc_handle_command() {

	if (isset($_GET['auto_activate_on'])) {
		$plugins = get_plugins();
		$auto_activate = pc_get_auto_activate_array();
		$plugin = $_GET['auto_activate_on'];
		$auto_activate[] = $plugin;
		update_site_option('pc_auto_activate_list',implode(',',array_unique($auto_activate)));
	}
	if (isset($_GET['auto_activate_off'])) 	{
		$plugins = get_plugins();
		$auto_activate = pc_get_auto_activate_array();
		$plugin = $_GET['auto_activate_off'];
		array_splice($auto_activate, array_search($plugin, $auto_activate), 1);
		update_site_option('pc_auto_activate_list',implode(',',array_unique($auto_activate)));
	}
	if (isset($_GET['user_control_on'])) {
		$plugins = get_plugins();
		$user_control = pc_user_control_array(); 
		$plugin = $_GET['user_control_on'];
		$user_control[] = $plugin;
		update_site_option('pc_user_control_list',implode(',',array_unique($user_control)));
	}
	if (isset($_GET['user_control_off'])) {
		$plugins = get_plugins();
		$user_control = pc_user_control_array();
		$plugin = $_GET['user_control_off'];
		array_splice($user_control, array_search($plugin, $user_control), 1);
		update_site_option('pc_user_control_list',implode(',',array_unique($user_control)));
	}
	if (isset($_GET['mass_activate']))	{
		$plugins = get_plugins();
		$plugin = $_GET['mass_activate'];
		pc_mass_activate($plugin);
	}
	if (isset($_GET['mass_deactivate'])) {
		$plugins = get_plugins();
		$plugin = $_GET['mass_deactivate'];
		pc_mass_deactivate( $plugin );
	}
}

function pc_get_auto_activate_array() {
	$auto_activate = explode(',',get_site_option('pc_auto_activate_list'));
	if (empty($auto_activate)) $auto_activate = array();
	return $auto_activate;
}

function pc_user_control_array() {
	$user_control = explode(',',get_site_option('pc_user_control_list'));
	if (empty($user_control)) $user_control = array();
	return $user_control;
}

function pc_get_active_plugins() {
	$active_plugins = get_option('active_plugins');
	if ($active_plugins == "" || $active_plugins == null) $active_plugins = array();
	return $active_plugins;
}
