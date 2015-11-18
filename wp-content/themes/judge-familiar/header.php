<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Judge_Familiar
 */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

		<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="hfeed site container">
		<a class="skip-link sr-only" href="#content"><?php esc_html_e( 'Skip to content', 'judge-familiar' ); ?></a>

		<header id="masthead" class="row site-header" role="banner">
			<div class="site-info">
				<h1 class="site-title col-sm-6">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				</h1>
				<p class="site-description col-sm-6">
					<span><?php bloginfo( 'description' ); ?></span>
				</p>
			</div>

			<?php if ( has_header_image() ): ?>
				<div class="site-header-image">
					<img 	class="col-lg-12"
								src="<?php header_image(); ?>"
								width="<?php echo get_theme_support( 'custom-header', 'width' ) ?>"
								height="<?php echo get_theme_support( 'custom-header', 'height' ) ?>"
								alt="Header Image"
					/>
				</div>
			<? endif; ?>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<div class="col-lg-9 col-xs-6 primary-menu">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
	    		<span></span>
	    		<span></span>
	    		<span></span>
	  		</button>
				<?php wp_nav_menu( array(
					'theme_location' => 'primary',
					'menu_id' => 'primary-menu',
				 	'depth' => 3
				) ); ?>
			</div>
			<div class="col-lg-3 col-xs-6 secondary-menu">
				<?php wp_nav_menu( array(
					'theme_location' => 'language',
					'menu_id' => 'secondary-menu',
				 	'depth' => 1
				) ); ?>
			</div>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="row site-content">
