<?php
/*
Plugin Name: Hetjens Expiration Date
Plugin URI: 
Version: 0.8.cets
Description: Adds an Expiration Date to Posts and Pages.
Author: Philip Hetjens & Jason Lemahieu
Author URI: 
Text Domain: Hetjens_Expiration_Date
*/

/*

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 3 of the License.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


  /*
   * Registers this plugin
   */
  function hetjens_expiration_date_register() {
    load_plugin_textdomain('Hetjens_Expiration_Date', false, '/'.dirname(plugin_basename(__FILE__)));
    
    add_action('admin_menu', 'hetjens_expiration_date_admin_register');
    add_action('save_post', 'hetjens_expiration_date_save_post');
    add_action('Hetjens_Expiration_Date', 'hetjens_expiration_date_cron');
    add_action('untrashed_post', 'hetjens_expiration_date_untrashed_post');
  }
  
  /*
   * Registers the Meta-Box for the writing pages
   */
  function hetjens_expiration_date_admin_register() {
    add_meta_box('Hetjens_Expiration_Date', __('Expiration Date','Hetjens_Expiration_Date'), 'hetjens_expiration_date_meta_box', 'post', 'side', 'low');
    add_meta_box('Hetjens_Expiration_Date', __('Expiration Date','Hetjens_Expiration_Date'), 'hetjens_expiration_date_meta_box', 'page', 'side', 'low');
  }
  
  /*
   * This function is called every day after midnight
   */
  function hetjens_expiration_date_cron() {
    global $wpdb;

    $result = $wpdb->get_results($wpdb->prepare('SELECT post_id FROM '.$wpdb->postmeta.' WHERE meta_key = "_expiration_date" AND meta_value < %s',time()));
    foreach ($result as $a) {
      wp_trash_post($a->post_id);
    }
  }
  
  /*
   * Displays the Meta-Box on the writing pages
   */
  function hetjens_expiration_date_meta_box() {
    global $wpdb, $post, $wp_locale;
    
    $ed = get_post_meta($post->ID,'_expiration_date',true);

    if ($ed) {
      $edy = date('Y',$ed);
      $edm = date('m',$ed);
      $edd = date('d',$ed);
    }
    else {
      $edy = $edm = $edd = '';
    }
  
    $month = '<select id="edm" name="edm" index="5002">';
    $month .= '<option value=""';
    if ($edm == '') {
      $month .= ' selected="selected"';
    }
    $month .= '></option>\n';
    for ($i=1; $i<13; $i=$i+1) {
      $month .= '<option value="'.zeroise($i, 2).'"';
      if ($i == $edm)
        $month .= ' selected="selected"';
      $month .= '>'.$wp_locale->get_month($i).'</option>';
    }
    $month .= '</select>';
    $day = '<input type="text" id="edd" name="edd" value="'.$edd.'" size="2" maxlength="2" autocomplete="off" index="5003" />';
    $year = '<input type="text" id="edy" name="edy" value="'.$edy.'" size="4" maxlength="4" autocomplete="off" index="5001" />';

    echo '<input type="hidden" name="expiration_date_nonce" value="'.wp_create_nonce('expiration_date').'">';

    echo '<p>'.__('Post expires at the end of (Year/Month/Day)','Hetjens_Expiration_Date').'<br />'.$year.' '.$month.' '.$day.'</p>';
    echo '<p>'.__('Leave blank for no expiration date.','Hetjens_Expiration_Date').'</p>';

    // todo
    echo '<p><a class="hide-no-js" id="clear-expiration-date-link" href="#" onclick="cets_clear_expiration_date(); return false;">Clear Expiration Date</a></p>';
  }
  
  /*
   * Saves the expiration date of the post
   */
  function hetjens_expiration_date_save_post() {
    
    if (!isset($_POST['expiration_date_nonce'])) {
      return;
    }

    if (wp_verify_nonce($_POST['expiration_date_nonce'], 'expiration_date')) {
      $year = $_POST['edy'];
      $month = $_POST['edm'];
      $day = $_POST['edd'];

      if ($year && $month && $day) {
        update_post_meta($_POST['post_ID'],'_expiration_date', mktime(23, 59, 59, $month, $day, $year));
      }
      else {
        delete_post_meta($_POST['post_ID'],'_expiration_date');
      }
    }
  }

  /**
   * Removes the expiration_date if a post is restored from trash and the expiration date is in history
   * @param int $post_id
   */
  function hetjens_expiration_date_untrashed_post($post_id) {
    $ed = get_post_meta($post_id,'_expiration_date',true);
    if (($ed > 0) && ($ed < time()))
      delete_post_meta($post_id,'_expiration_date');
  }



/*
 * This function registers the cron event
 */
function Hetjens_Expiration_Date_install() {
  $midnight  = mktime(0, 0, 0, date('m'), date('d')+1, date('Y'));
  wp_schedule_event($midnight, 'daily', 'Hetjens_Expiration_Date');
}

/*
 * This function unregisters the cron event
 */
function Hetjens_Expiration_Date_uninstall() {
  wp_clear_scheduled_hook('Hetjens_Expiration_Date');
}


/* Initialise ourselves */
add_action('plugins_loaded', 'hetjens_expiration_date_register');
register_activation_hook(__FILE__, 'Hetjens_Expiration_Date_install');
register_deactivation_hook(__FILE__, 'Hetjens_Expiration_Date_uninstall');



function Hetjens_Expiration_Date_scripts() {
  wp_enqueue_script('hetjens_ui', plugins_url('/js/hetjens-javascript.js', __FILE__), array('jquery'));
}
if (is_admin()) {
  add_action('admin_enqueue_scripts', 'Hetjens_Expiration_Date_scripts');
}