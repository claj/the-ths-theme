<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>

	<div id="content" class="narrowcolumn" role="main">
<div class="loopthepage">
	<?php if (have_posts()) : ?>

		<h2 class="pagetitle">S&ouml;kresultat:</h2>
			<?php while (have_posts()) : the_post(); ?>

	
  <div class="post" id="post-<?php the_ID(); ?>">
<div class="cat-color">
	<span style="background-color:<?php the_category_color(); ?>"></span><?php the_THS_category($saved_cats); ?>
</div>
<?php the_category_image() ?>
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			

				<div class="entry">
						<? 
					$event = get_post_meta(get_the_ID(),'_isEvent',true);
				
					if($event) : ?>
					<div class="eventimage"><span class="monthname"><?php echo $wp_locale->get_month(date("m",$event)) ?></span><span class="monthday"><?php echo date("j",$event) ?></span></div>
					<? endif; ?>
					<? the_excerpt('L�s mer &raquo;'); ?>
				</div>

				<br class="clear" />
			</div>
		<?php endwhile; ?>

	<?php else : ?>

<h2 >Hittade inget. S&ouml;k igen.</h2>
		

	<?php endif; ?>
</div></div>


<?php get_sidebar(); ?>

<?php get_footer(); ?>
