<?php if ( have_comments() ) : ?>
	<h4 id="comments"><?php comments_number('No Comments', 'One Comment', '% Comments' );?></h4>
	<ol class="commentlist">
	<?php wp_list_comments(); ?>
	</ol>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
 <?php else : // this is displayed if there are no comments so far ?>
	<?php if ('open' == $post->comment_status) :?>
		<h3><?php echo('There are no comments yet.') ?></h3>
	<?php else :
	    echo('<br /> Comments are closed');// comments are closed 
	endif;
endif; ?>
<?php comment_form(); ?>

