<?php

class Language_Link
{
	public static $languages = array(
		"cn" => array( "native" => "中文", "english" => "Chinese"),
		"de" => array( "native" => "Deutsch", "english" => "German"),
		"en" => array( "native" => "English", "english" => "English"),
		"it" => array( "native" => "Italiano", "english" => "Italian"),
		"es" => array( "native" => "Español", "english" => "Spanish"),
		"fr" => array( "native" => "Français", "english" => "French"),
		"ja" => array( "native" => "日本語", "english" => "Japanese"),
		"pt" => array( "native" => "Português", "english" => "Portuguese"),
		"ru" => array( "native" => "русском", "english" => "Russian"),
		"pl" => array( "native" => "język polski", "english" => "Polish")
	);

	public function __construct() {
		add_action( 'add_meta_boxes_post', array( $this, 'add_custom_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	public function add_custom_meta_box() {
		add_meta_box(
			'language-links-meta-box',
			__( 'Language Links' ),
			array( $this, 'render_meta_box' ),
			'post',
			'normal'
		);
	}

	public function render_meta_box( $post ) {
		wp_enqueue_style( 'language_links_admin', plugins_url( '/css/language-links-admin.css', __FILE__ ) );
		wp_enqueue_script( 'language_links', plugins_url( '/js/language-links.js', __FILE__ ), array( 'jquery' ) );
		wp_nonce_field( 'language_links_meta_box', 'language_links_meta_box_nonce' );

		$language_links = get_post_meta( $post->ID, '_language_links', true );

		if ( empty( $language_links ) ) {
			$language_links[] = array( 'language' => '', 'url' => '' );
		}

		echo '<div class="language-link-input">';
		foreach ( $language_links as $index => $language_link ) {
			echo '<div class="language-link">';

			$id_attr = 'language_links[' . $index . '][language]';
			echo '<div class="language-link-language"><label for="' . $id_attr . '">';
			_e( 'Language' );
			echo '</label>';
			echo '<select class="text" name="' . $id_attr . '" id="' . $id_attr . '" value="'
				. esc_attr( $language_link['language'] ) . '" >';
			echo '<option></option>';
			foreach(self::$languages as $key => $language) {
				echo '<option value="' . $key . '"' . selected($key, $language_link['language'], false) . '>' . $language['english'] . '</option>';
			}
			echo '</select>';
			echo '<input type="button" class="button remove-language" value="X"/>';
			echo '</div>';

			$id_attr = 'language_links[' . $index . '][url]';
			echo '<div class="language-link-url"><label for="' . $id_attr . '">';
			_e( 'URL' );
			echo '</label>';
			echo '<input class="text" type="url" min="0" name="' . $id_attr . '" id="' . $id_attr . '" value="' .
				esc_attr( $language_link['url'] ) . '" size="25" />';
			echo '</div>';

			echo '</div>';
		}
		echo '</div>';

		echo '<input id="language-link-add-language" class="button maybe-disable" type="button" value="' . __( 'Add additional language' ) . '">';
	}

	public function save( $post_id ) {
		if ( ! isset( $_POST['language_links_meta_box_nonce'] ) )
			return $post_id;

		if ( ! wp_verify_nonce( $_POST['language_links_meta_box_nonce'], 'language_links_meta_box' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		if ( isset( $_POST['language_links'] ) ) {
			$language_links = $_POST['language_links'];

			if ( ! is_array( $language_links ) ) {
				$language_links[0] = $language_links;
			}

			foreach ( $language_links as $index => $language_link ) {
				foreach ( $language_link as $key => $value ) {
					$language_link[$key] = sanitize_text_field( $language_link[$key] );
				}
				if ( empty( $language_link['url'] ) ) {
					unset( $language_links[$index] );
				}
			}

			update_post_meta( $post_id, '_language_links', $language_links );
		}
	}
}