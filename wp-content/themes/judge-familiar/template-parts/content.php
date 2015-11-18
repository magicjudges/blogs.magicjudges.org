<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Judge_Familiar
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="<?php judge_familiar_category_classes(); ?>">
			<?php printf( __( '%2$s', 'judge_familiar' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
		</div>
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php judge_familiar_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php if ( has_post_thumbnail() ) : // check if the post has a Post Thumbnail assigned to it. ?>
			<div class="featured-image">
				<?php the_post_thumbnail(); ?>
			</div>
		<?php else : ?>
			<?php judge_familiar_featured_author(); ?>
		<?php endif; ?>
		<div class="entry-summary">
			<p><?php echo get_the_excerpt(); ?></p>
		</div><!-- .entry-summary -->

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'judge-familiar' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php judge_familiar_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
