<?php

/**
 * The main short ninja class. Designed as singleton.
 *
 * @author    Joel Krebs <joel.krebs@gmai.com>
 * @license   GPL-2.0+
 *
 * @link      http://www.aleaiactaest.ch
 *
 * @copyright 2015 Joel Krebs
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

if (!class_exists('Short_Ninja')) :

final class Short_Ninja
{
    const base_name = 'short-ninja'
    const site_option = Short_Ninja::base_name;
    const network_option = 'network_'.Short_Ninja::base_name;
    const version = '1.0.0';
    const db_version = '1';

    public static function instance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new self();
        }

        return $instance;
    }

    private function __construct()
    {
        $this->file = __FILE__;
        $this->plugin_dir = plugin_dir_path($this->file);
        $this->plugin_url = plugin_dir_url($this->file);

        register_activation_hook($this->file, array($this, 'activate'));
        register_deactivation_hook($this->file, array($this, 'deactivate'));

        require $this->plugin_dir.'includes/functions.php';

        if (is_admin()) {
            require $this->plugin_dir.'includes/admin.php';
        }
    }

    public function activate()
    {
      $this->create_tables();
    }

    private function create_tables()
    {
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      global $wpdb;
      $wpdb->short_ninja_tags = $wpdb->base_prefix . 'short_ninja_tags';
      $engine = $wpdb->get_row( "SELECT ENGINE FROM information_schema.TABLES where TABLE_NAME = '$wpdb->blogs'", ARRAY_A );
		  $engine = $engine['ENGINE'];

      $sql = "CREATE TABLE $wpdb->short_ninja_tags (
  			id BIGINT NOT NULL AUTO_INCREMENT,
  			created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  			name VARCHAR(55) NOT NULL,
  			description VARCHAR(255) DEFAULT NULL,
        code TEXT DEFAULT ""
  			PRIMARY KEY id (id)
  		)
  		ENGINE $engine,
  		DEFAULT COLLATE utf8_general_ci;";
		  dbdelta( $sql );
    }
}

function short_ninja()
{
    return Short_Ninja::instance();
}

short_ninja();

endif; // class_exists check
