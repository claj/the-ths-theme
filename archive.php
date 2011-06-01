<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

global $wp_query,$eventtimestamp;



if(get_query_var('event') && is_day()) :
if($wp_query->post_count == 1) {
	the_post();
	
	wp_redirect(get_permalink());
	exit();
	}


endif;

get_header();
?>

	<div id="content" class="narrowcolumn" role="main">
<div class="loopthepage">
		<?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle">Arkiv f&ouml;r kategori: &#8217;<?php single_cat_title(); ?>&#8217;</h2>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle">Inl&auml;gg taggade med: &#8217;<?php single_tag_title(); ?>&#8217;</h2>
 	  <?php  /* If this is a daily archive and event */ } if( get_query_var('event')  && is_day() ) {?>
      <h2 class="pagetitle">Aktiviteter den <?php echo date_i18n( 'j F Y', $eventtimestamp ); ?></h2>
      <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Arkiv f&ouml;r <?php the_time('j F Y'); ?></h2>
        <?php  /* If this is a daily archive and event */ } if( get_query_var('event')  && is_month() ) {?>
      <h2 class="pagetitle">Aktiviteter i månaden <?php echo date_i18n( 'F Y', $eventtimestamp ); ?></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle">Arkiv f&ouml;r <?php the_time('F Y'); ?></h2>
      <?php  /* If this is a daily archive and event */ } if( get_query_var('event')  && is_year() ) {?>
      <h2 class="pagetitle">Aktiviteter i året <?php echo date_i18n( 'Y', $eventtimestamp ); ?></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle"> Arkiv f&ouml;r f&ouml;rfattare</h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Blog Archives</h2>
 	  <?php } ?>


		
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
					<? the_excerpt('Läs mer &raquo;'); ?>
				</div>

				<br class="clear" />
			</div>
    
    
		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Äldre inlägg') ?></div>
			<div class="alignright"><?php previous_posts_link('Nyare inlägg &raquo;') ?></div>
		</div>


<?php else :

		if ( is_category() ) { // If this is a category archive
			printf("<h2 class='center'>Tyvärr, det finns inga inlägg i kategorin %s äm.</h2>", single_cat_title('',false));
		} else if ( is_date() ) { // If this is a date archive
			echo("<h2>Tyvärr, det finns inga inlägg i detta datum.</h2>");
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf("<h2 class='center'>Tyvärr, %s har inte publicerat några inlägg än.</h2>", $userdata->display_name);
		} else {
			echo("<h2 class='center'>Inga inlägg hittades.</h2>");
		}
		get_search_form();

	endif;
?>

	</div> </div>

<?php get_sidebar(); ?>

<?php get_footer();  ?>
