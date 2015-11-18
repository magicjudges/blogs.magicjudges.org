<?php

class WP_Translators
{
    const CURRENT_VERSION = '0.0.1';

    private static $instance;

    public static function get_instance() {
        if (null == self::$instance) {
            self::$instance = new WP_Translators();
        }

        return self::$instance;
    }

    private function __construct() {
        add_action('init', array($this, 'init'));
        add_action('set_user_role', array($this, 'set_user_role'), 10, 2);
    }

    public function init() {
        add_filter('user_has_cap', array($this, 'check_cap'), 10, 3);
    }

    public function set_user_role($user_id, $role) {
        global $wpdb;
        if ($role == 'translator') {
            update_user_meta($user_id, $wpdb->get_blog_prefix() . 'user_level', 2);
        }
    }

    public function check_cap($allcaps, $caps, $args) {
        if (!in_array($args[0], array('edit_post', 'edit_posts', 'edit_others_posts', 'edit_published_posts')))
            return $allcaps;

        if (isset($allcaps['edit_others_posts']) && $allcaps['edit_others_posts'])
            return $allcaps;

        if (!isset($allcaps['publish_posts']) or !$allcaps['publish_posts'])
            return $allcaps;

		if(count($args) < 2) {
			return $allcaps;
		}

        $post = get_post($args[2]);

		if ($post != null) {
			if ($args[1] == $post->post_author)
				return $allcaps;

			$author = get_user_by('id', $post->post_author);

			if (in_array('translator', $author->roles)) {
				$allcaps[$caps[0]] = true;
			}
		}

        return $allcaps;
    }

    /**
     * Performs the required setup on activation. Setting default values for the settings.
     */
    public static function activate() {
        add_role('translator', __('Translator', 'wp-translator'), array(
            'delete_posts' => true,
            'delete_published_posts' => true,
            'edit_posts' => true,
            'edit_published_posts' => true,
            'publish_posts' => true,
            'read' => true,
            'upload_files' => true
        ));

        update_option('wp_translators_version', WP_Translators::CURRENT_VERSION);
    }

    public static function deactivate() {
        remove_role('translator');
        delete_option('wp_translators_version');
    }
}