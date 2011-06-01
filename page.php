<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>

	<div id="content" class="narrowcolumn" role="main">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post realpost page" id="post-<?php the_ID(); ?>">
		<h2><?php the_title(); ?></h2>
			<div class="entry">
				<? if(is_front_page()) :  
					 $more = false;?>
				<div id="sneak" class="hide-if-no-js">	 <? echo get_the_content("",true)."..."; 	$more = true; ?><a id="readmore" href="#">Läs mer</a></div>
               <div id="restofsneak"><?php the_content(); ?></div>
				<? else : the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
    <? endif; ?>
				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div>
		</div>
		<?php endwhile; endif; ?>
        <?php if(function_exists("LoopThePage"))
									LoopThePage();			?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	
	<?php comments_template(); ?>
	
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
