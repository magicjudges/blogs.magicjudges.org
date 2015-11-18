<?php

class Message_Board_Comments
{
	public function __construct() {
		add_action( 'add_meta_boxes', array($this, 'add_custom_meta_box') );
		add_action( 'save_post', array($this, 'save') );

		add_action( 'admin_init', array(&$this, 'admin_init') );
	}

	public function admin_init() {
		$this->init_settings();
	}

	public function init_settings() {
		add_settings_section( 'comments_link_section', 'Comments URL', array($this, 'render_settings_section'), 'discussion' );
		add_settings_field( 'comments_url_text', 'Link Text', array($this, 'render_link_text_setting'), 'discussion', 'comments_link_section' );

		register_setting( 'discussion', 'comments_link_text', array($this, 'validate_setting') );
	}

	public function render_settings_section() {
		printf( '<p>%1$s</p>', __( 'Define the text displayed for each link instead of the comments link. The target of the link is defined
		on each post individually.' ) );
	}

	public function render_link_text_setting() {
		printf( '<input style="width: 200px" type="text" class="text" id="comment-link-text" name="comments_link_text" value="%1$s" />',
			get_option( 'comments_link_text', __( 'Leave a comment' ) )
		);
	}

	public function validate_setting( $input ) {
		return sanitize_text_field( $input );
	}

	public function add_custom_meta_box() {
		$screens = array('post', 'page');

		foreach ( $screens as $screen ) {
			add_meta_box(
				'comment-url-meta-box',
				__( 'URL for comments' ),
				array($this, 'render_meta_box'),
				$screen,
				'side'
			);
		}
	}

	public function render_meta_box( $post ) {
		$commentURL = get_post_meta( $post->ID, '_comment_url', true );

		wp_register_style( 'comments_url_admin', plugins_url( '/css/comments-url-admin.css', __FILE__ ) );
		wp_enqueue_style( 'comments_url_admin' );

		wp_register_script( 'disable_comments', plugins_url( '/js/disable-comments.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( 'disable_comments' );

		wp_nonce_field( 'comment_url_meta_box', 'comment_url_meta_box_nonce' );

		printf( '<label for="comment-url">%1$s</label><input class="text" type="url" id="comment-url" name="comment_url" value="%2$s" />',
			__( 'URL' ),
			$commentURL
		);
	}

	public function save( $post_id ) {
		if ( ! isset($_POST['comment_url_meta_box_nonce']) ) {
			return $post_id;
		}

		if ( ! wp_verify_nonce( $_POST['comment_url_meta_box_nonce'], 'comment_url_meta_box' ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( 'page' == $_POST['post_type'] && ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}

		if ( 'post' == $_POST['post_type'] && ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		$commentURL = sanitize_text_field( $_POST['comment_url'] );

		update_post_meta( $post_id, '_comment_url', $commentURL );
	}
}