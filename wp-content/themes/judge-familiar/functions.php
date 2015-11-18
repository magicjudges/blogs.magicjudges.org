<?php
/**
 * Judge Familiar functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Judge_Familiar
 */

if ( ! function_exists( 'judge_familiar_setup' ) ) :

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function judge_familiar_setup() {
	load_theme_textdomain( 'judge-familiar', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );

	add_theme_support( 'post-thumbnails' );

	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'judge-familiar' ),
	) );

	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	add_theme_support( 'custom-background', apply_filters( 'judge_familiar_custom_background_args', array(
		'default-color' => 'f1f1f1',
		'default-image' => '',
	) ) );

	add_theme_support( 'custom-header', array(
		'width'					=>	980,
		'height'				=>	190
	) );

	if ( ! get_theme_mod( 'show_excerpts' ) ) {
		set_theme_mod( 'show_excerpts', 'excerpts' );
	}

	register_nav_menus( array(
		'language'        => __( 'Language Navigation', 'judge-familiar' ),
		'language-single' => __( 'Language Navigation Single', 'judge-familiar' ),
	) );

	add_filter( 'get_the_excerpt', 'do_shortcode' );
}
endif; // judge_familiar_setup
add_action( 'after_setup_theme', 'judge_familiar_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function judge_familiar_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'judge_familiar_content_width', 980 );
}
add_action( 'after_setup_theme', 'judge_familiar_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function judge_familiar_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'judge-familiar' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'judge_familiar_widgets_init' );

if ( ! function_exists('judge_familiar_excerpt') ) :
	function judge_familiar_excerpt($text = '') {
		return sprintf('%s <p class="readmore"><a href="%s">Read more.</a></p>',
			$text,
			esc_url( get_permalink() )
		);
	}
endif;

add_filter( 'get_the_excerpt', 'judge_familiar_excerpt', 99, 1 );

/**
 * Enqueue scripts and styles.
 */
function judge_familiar_scripts() {
	wp_enqueue_style( 'bootstrap-v4', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css', array(), '20151026' );
	wp_enqueue_style( 'judge-familiar-style', get_stylesheet_uri() );

	wp_enqueue_script( 'bootstrap-v4', get_template_directory_uri() . '/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '20151026', true );
	wp_enqueue_script( 'judge-familiar-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
	wp_enqueue_script( 'judge-familiar-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'judge_familiar_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
