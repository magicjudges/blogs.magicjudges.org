<?php
/* Template Name: Page with Index */
/**
 * The template for displaying pages with an index instead of widgets.
 *
 * @package Judge_Familiar
 */

get_header(); ?>

	<div id="primary" class="col-lg-9 col-xs-12 content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar("index"); ?>
<?php get_footer(); ?>
