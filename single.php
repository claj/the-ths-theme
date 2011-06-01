<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header();
?>

	<div id="content" class="narrowcolumn" role="main">

	<?php if (have_posts()) : while (have_posts()) : the_post(); 
	$event = get_post_meta(get_the_ID(),'_isEvent',true);
	?>


		<div <?php post_class("realpost") ?> id="post-<?php the_ID(); ?>">
			<h2><?php the_title(); ?></h2>

			<div class="entry">
            <? if($event) : ?>
					<div class="eventbig eventimage"><span class="monthname"><?php echo $wp_locale->get_month(date("m",$event)) ?></span><span class="monthday"><?php echo date("j",$event) ?></span><span class="eventtime"><?php echo date("H:i",$event) ?> - <?php echo @date("H:i",get_post_meta(get_the_ID(),'_endEvent',true)) ?></span></div>
					<? endif; ?>
				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>
<div id="meta" style="overflow:auto;clear:both">
<div class="alignleft">
	
	<? echo get_avatar(get_the_author_meta('user_email'), $size = '100', $default = '' ); 
	?> <br /><p>Skriven av <?php the_author(); ?></p></div>
			<p class="postmetadata alt" style="margin-left:150px; clear:none;">
					<small>
						Detta inlägg publicerades av <?php the_author(); ?> den
						<?php /* This is commented, because it requires a little adjusting sometimes.
							You'll need to download this plugin, and follow the instructions:
							http://binarybonsai.com/wordpress/time-since/ */
							/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
					<strong class="postdate">	<?php the_time('d-m-Y') ?> klockan <?php the_time("H:i") ?></strong>
						och tillhör kategori/erna <?php the_category(', ') ?>.
						Du kan följa alla kommentarer till detta inlägg genom detta <?php post_comments_feed_link('RSS 2.0'); ?> flöde.

						<?php if ( comments_open() && pings_open() ) {
							// Both Comments and Pings are open ?>
							Du kan <a href="#respond">lämna en kommentar</a>, eller skicka en <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> från din egen site.

						<?php } elseif ( !comments_open() && pings_open() ) {
							// Only Pings are Open ?>
							Du kan skicka en <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> från din egen site.

						<?php } elseif ( comments_open() && !pings_open() ) {
							// Comments are open, Pings are not ?>
							Du kan <a href="#respond">lämna en kommentar nedan</a>

						<?php } elseif ( !comments_open() && !pings_open() ) {
							// Neither Comments, nor Pings are open ?>
							Både kommentarer och pings är för närvarande stängda för detta inlägg.

						<?php } edit_post_link('Redigera detta inlägg','','.'); ?>

					</small>
				</p>
</div>
			</div>
		</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

	</div>
<? get_sidebar(); ?>

<?php get_footer(); ?>
