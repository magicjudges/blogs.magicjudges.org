<?php
/*
Plugin Name: Google Site Verification (CETS)
Description: Allows site verification for Google Webmaster Tools
Version: 0.1
Author: Jason Lemahieu
Author URI: http://madtownlems.wordpress.com
*/

class CETS_Google_Site_Verification
{
    public function __construct()
    {
        add_action('admin_menu', array(&$this, 'my_admin_menus'));
        add_action('wp_head', array(&$this, 'header_scripts'));
        add_filter('plugin_action_links', array(&$this, 'activate_links'), 10, 2);
    }

    public function my_admin_menus()
    {
        if (is_super_admin()) {
            add_submenu_page(
              'options-general.php',
              'Site Verification',
              'Site Verification',
              'manage_network',
              'cets_google_site_verification',
              array(&$this, 'settings_page')
            );
        }
    }

    public function settings_page()
    {
        echo "<div class='wrap'>";
        echo '<h2>'.__('Google Site Verification Settings', 'text_domain').'</h2>';

        $step = 1;
        if (isset($_REQUEST['cets_google_site_verification_submit'])) {
          $new_code = trim(wp_filter_nohtml_kses($_REQUEST['cets_google_site_verification_code']));
            update_option('cets_google_site_verification_code', $new_code);
            echo "<div class='updated fade'><p>Verification Code updated. Be sure to click the <strong>Verify Now</strong> button inside Google Webmaster Tools.</p></div>";
        }
        $current_code = esc_html(get_option('cets_google_site_verification_code', ''));
        echo "<p class='instructions'>Enter your Google Site Verification code here:</p>";
        echo "<p class='instructions'><strong>Note:</strong> Enter only the code itself, not the entire Meta tag.</p>";

        $form_action = esc_attr($_SERVER['REQUEST_URI']);
        echo '<form action="'.$form_action.'" method="post">';
        echo "Verification Code: <input type='text' size='20' name='cets_google_site_verification_code' value='".$current_code."'> <br>";

        submit_button('Save Settings', 'primary', 'cets_google_site_verification_submit');
        echo '</form>';
        echo '</div><!-- /wrap -->';
    }

    public function header_scripts()
    {
        $site_verification_code = get_option('cets_google_site_verification_code', '');
        if ($site_verification_code) {
            echo '<meta name="google-site-verification" content="'.$site_verification_code.'" />';
        }
    }

    public function activate_links($links, $file)
    {
        static $this_plugin;
        if (!$this_plugin) {
            $this_plugin = plugin_basename(__FILE__);
        }

        if ($file == $this_plugin) {
            $settings_link = '<a href="'.admin_url('options-general.php?page=cets_google_site_verification').'">Settings</a>';
            array_unshift($links, $settings_link);
        }

        return $links;
    }
  }

$cets_google_site_verification = new CETS_Google_Site_Verification();
