<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

//jatteviktigt att det inte ar tomrader i borjan av filen
//skitphp.

$content_width = 620;
add_theme_support( 'post-thumbnails' , array('post')); 
add_action('template_redirect',"THS_redirect");
add_action('init','THS_permalink_init');
add_filter('posts_join', 'THS_event_join' );
add_filter('posts_where', 'THS_event_where' );
add_filter('posts_orderby', 'THS_event_order' );
add_filter('date_rewrite_rules','THS_rewrite_filter');

function THS_get_month_link($year, $month) {
        global $wp_rewrite;
        if ( !$year )
                $year = gmdate('Y', current_time('timestamp'));
        if ( !$month )
	                $month = gmdate('m', current_time('timestamp'));
	        $monthlink = str_replace("date","event",$wp_rewrite->get_month_permastruct());
	       
		   if ( !empty($monthlink) ) {
	                $monthlink = str_replace('%year%', $year, $monthlink);
	                $monthlink = str_replace('%monthnum%', zeroise(intval($month), 2), $monthlink);
	                return apply_filters('month_link', get_option('home') . user_trailingslashit($monthlink, 'month'), $year, $month);
	        } else {
	                return apply_filters('month_link', trailingslashit(get_option('home')) . '?m=' . $year . zeroise($month, 2), $year, $month);
	        }
	}
function THS_get_day_link($year, $month, $day) {
	        global $wp_rewrite;
	        if ( !$year )
	                $year = gmdate('Y', current_time('timestamp'));
	        if ( !$month )
	                $month = gmdate('m', current_time('timestamp'));
	        if ( !$day )
	                $day = gmdate('j', current_time('timestamp'));
	
	        $daylink = str_replace("date","event",$wp_rewrite->get_day_permastruct());
	        if ( !empty($daylink) ) {
	                $daylink = str_replace('%year%', $year, $daylink);
	                $daylink = str_replace('%monthnum%', zeroise(intval($month), 2), $daylink);
	                $daylink = str_replace('%day%', zeroise(intval($day), 2), $daylink);
	               return apply_filters('day_link', get_option('home') . user_trailingslashit($daylink, 'day'), $year, $month, $day);
	        } else {
	                return apply_filters('day_link', trailingslashit(get_option('home')) . '?m=' . $year . zeroise($month, 2) . zeroise($day, 2), $year, $month, $day);
	        }
	}
function THS_rewrite_filter($d){
		foreach($d as $k => $e){
		
		$a[str_replace("date","event",$k)] = $e."&event=1";
		
		}
		return array_merge($a,$d);
	}
function THS_event_order($order){
	global $wpdb;
	if(is_date() && get_query_var('event'))
		$order = "$wpdb->postmeta.meta_value ASC";
		
	return $order;
	
	}
function THS_event_join($join){
	global $wpdb;
	if(is_date() && get_query_var('event'))
		$join = "JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)";
		
	return $join;
	
	}
function THS_event_where($where){
	global $wpdb,$wp_query,$eventtimestamp;
	if(is_date() && get_query_var('event')) :
	

	$year = $wp_query->query_vars['year'];
	$day = $wp_query->query_vars['day'];
	$monthnum = $wp_query->query_vars['monthnum'];
	
	
if( is_day() ) {
	$timestamp = mktime(0,0,0,$monthnum,$day,$year)-1;
	$nexttime = $timestamp + (60 * 60 * 24);



} elseif( is_month() ){
		
		$timestamp = mktime(0,0,0,$monthnum,1,$year)-1;
		$eventtimestamp = mktime(0,0,0,$monthnum,1,$year);
	if(intval($monthnum > 11)) :
	$ny = intval($year) +1;
	$nm = 1;
	else :
	$nm = intval($monthnum)+1;
	$ny = $year;
	endif;
	
	$nexttime = mktime(0,0,0,$nm,1,$ny);
	
} elseif( is_year() ){
	
	$timestamp = mktime(0,0,0,1,1,$year)-1;
	$nexttime = mktime(0,0,0,1,1,$year+1);
	
}
		$eventtimestamp = $timestamp + 1;
		$where = " AND $wpdb->postmeta.meta_key = '_isEvent' AND $wpdb->postmeta.meta_value > ".$timestamp." AND $wpdb->postmeta.meta_value < ".$nexttime." AND post_type = 'post' AND post_status = 'publish'";
	endif;
	return $where;
	
	}

automatic_feed_links();
/* PRetty Permalink structure */
add_filter('query_vars', 'THS_queryvars' );

function THS_queryvars( $qvars )
{
  $qvars[] = 'event';
  $qvars[] = 'cal-ajax';
  return $qvars;
}

function THS_permalink_init()
{
 global $wp_rewrite;


add_rewrite_rule('cal-ajax/([0-9]{4,6})/?$', 'index.php?cal-ajax=1&event=$matches[1]','top');
add_rewrite_rule('cal-ajax/?$', 'index.php?cal-ajax=1','top');
add_rewrite_rule('cal-ajax?$', 'index.php?cal-ajax=1','top');



	
}

if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		 'name'          => "Always",
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));

	
	register_sidebar(array(
		 'name'          => "Pages",
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
		
		));
	register_sidebar(array(
		 'name'          => "Start",
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));

	register_sidebar(array(
		'name'          => "Translate",
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));
}


//add_action('do_feed_rss2',"THS_do_feed_rss2");
//add_action('do_feed_rss',"THS_do_feed_rss2");


	function THS_get_caller() {
		// requires PHP 4.3+
		if ( !is_callable('debug_backtrace') )
			return '';

		$bt = debug_backtrace();
		$caller = array();

		$bt = array_reverse( $bt );
		foreach ( (array) $bt as $call ) {
			if ( @$call['class'] == __CLASS__ )
				continue;
			$function = $call['function'];
			if ( isset( $call['class'] ) )
				$function = $call['class'] . "->$function";
			$caller[] = $function;
		}
		$caller = join( ', ', $caller );

		return $caller;
	}
function THS_do_feed_rss2($for_comments) {

	global $wp_query;

	if($post) :
		
		$saved_cats = get_post_meta($post_ID,'_LoopThePage_cats',true);
		if($saved_cats) :
		
		$cats_query = implode(',',$saved_cats);
		query_posts("cat=".$cats_query);
		$for_comments = false;
		endif;
	endif;
       if ( $for_comments ) {
           load_template(ABSPATH . 'wp-commentsrss2.php');
       } else {
           load_template(ABSPATH . 'wp-rss2.php');
       }
  }

function THS_redirect(){
	global $wp_query,$wp_rewrite;
	

	if(get_query_var('cal-ajax')):
		get_THS_calendar();
	exit();
	endif;
	if(get_query_var('paged') && is_home()):
		
		$template = get_page_template();
		
		set_query_var('page_id',get_option('page_on_front'));
		$wp_query->query($wp_query->query_vars);
		include($template);
		
	exit();
	endif;
	}
class THS_Widget_Calendar extends WP_Widget {

	function THS_Widget_Calendar() {
		$widget_ops = array('classname' => 'ths_widget_calendar', 'description' => __( 'A calendar of your blog&#8217;s events') );
		$this->WP_Widget('ths_calendar', "THS ".__('Calendar'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
		 
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo '<div id="ths_calendar_wrap">';
		get_THS_calendar();
		echo '</div>';
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
	}
}

class THS_Widget_Categories extends WP_Widget {

	function THS_Widget_Categories() {

		$widget_ops = array( 'classname' => 'ths_widget_categories', 'description' => __( "A list or dropdown of categories" ) );
		$this->WP_Widget('THS-categories', "THS ".__('Categories'), $widget_ops);
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		$title = $instance['title'];
		$c = $instance['cat'];
		
		$n = $instance['number'] ? $instance['number'] : '3'; 
		$d = $instance['isEvent'] ? '1' : '0';

		echo $before_widget;
		
		?>
        <div class="inner" style="border-top-color:<?php the_category_color($c); ?>">
        <?
		if ( $title )
			echo $before_title . $title . $after_title;

		
?>
<p><?php echo $instance['text']?></p>
		<ul>
<?php
global $post;

if($instance['event']) :
query_posts('posts_per_page='.$n.'&meta_key=_isEvent&meta_compare=>&meta_value='.time().'&cat='.$c."&orderby=meta_value&order=ASC"); ?>
<? else: 
query_posts('posts_per_page='.$n.'&cat='.$c); 
endif; ?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); 
if($instance['event'])
$event = "<strong>".date("d.m.y",get_post_meta(get_the_ID(),"_isEvent",true))."</strong>";

		?>
		<li style="color:<?php the_category_color($c) ?>"><span><a href="<? the_permalink(); ?>"><?php echo $event ?> <?php the_title(); ?></a> </span></li>
<?php endwhile; else : ?>
<li>Inga aktiviteter för tillfället</li>
<?php endif; ?>

		</ul>
        </div>
<?php
	
wp_reset_query();

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] = $new_instance['text'];
		$instance['number'] = (int) $new_instance['number'];
		$instance['cat'] = (int) $new_instance['cat'];
		$instance['event'] = (bool) $new_instance['event'];
		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$text = esc_attr( $instance['text'] );
		$isEvent = (bool) $instance['event'];
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 3;
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
        <input class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo $text; ?>" /></p>

	<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br /></p>
        
        	<p><label for="<?php echo $this->get_field_id('event'); ?>"><?php _e('Events'); ?></label>
		<input id="<?php echo $this->get_field_id('event'); ?>" name="<?php echo $this->get_field_name('event'); ?>" type="checkbox" <? if($isEvent) : echo "checked=\"checked\""; endif; ?> value="1" /><br /></p>

<label for="<?php echo $this->get_field_id('name'); ?>"><p><?php _e('Select Category') ?></label>
<? 
wp_dropdown_categories( array('name' =>  $this->get_field_name('cat') ,'selected' => $instance['cat'])); ?>
</p>
<?php
	}

}

register_widget('THS_Widget_Categories');
register_widget('THS_Widget_Calendar');
function get_THS_category($post_cats,$loopthepage_cats) {
// 0 querys;
$descendants = array();
foreach($loopthepage_cats as $l) :
$descendants = array_merge($descendants,get_term_children( (int) $l, 'category'));
array_push($descendants,$l);
	
endforeach;


$found = false;



foreach($post_cats as $p) :
	if(in_array($p,$descendants)){
		$category = get_category($p);
		 $found = '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" ' . $rel . '>' . $category->cat_name . "</a>";
		break;
		} 
endforeach;

		return $found;
		

	
	}
function post_is_in_descendant_category( $cats, $_post = null )
{
	foreach ( (array) $cats as $cat ) {
		// get_term_children() accepts integer ID only
		$descendants = get_term_children( (int) $cat, 'category');
		if ( $descendants && in_category( $descendants, $_post ) )
			return true;
	}
	return false;
}

function the_THS_category($loopthepage_cats){
	// 1 query
	$cats = get_the_category();

	if(empty($loopthepage_cats))
				$loopthepage_cats[] = $cats[0]->term_id;

	foreach($cats as $c):
	
	$finCat[] = $c->term_id;
	
	endforeach;
	echo get_THS_category($finCat,$loopthepage_cats);

	}

function get_category_ancenstors( $id ) {
      // 1 query
	  $chain = array();
		
		if(!$id)
		return false;
		
        $parent = &get_category( $id );
        if ( is_wp_error( $parent ) )
            return array($parent->term_id);
    
       
         $chain[] = $parent->term_id;
    
        if ( $parent->parent && ( $parent->parent != $parent->term_id )  ) {
           
			$chain = array_merge($chain,get_category_ancenstors($parent->parent));
        }
    
       
        return $chain;
    }
function str_img_id($html) {
        if (stripos($html, '<img') !== false) {
            $imgsrc_regex = '/wp-image-([0-9]{1,})/';
            preg_match($imgsrc_regex, $html, $matches);
            unset($imgsrc_regex);
            unset($html);
            if (is_array($matches) && !empty($matches)) {
                return $matches[1];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

function the_category_image(){
	global $post, $catimage_cache;
	if(!$catimage_cache):
		$catimage_cache = get_option('catimage_cache');
	endif;

	if($catimage_cache[$post->ID]) :
	echo $catimage_cache[$post->ID];
	return;
	else :
	
	
	$thumb = get_the_post_thumbnail($post->ID,array(150,120),array('class'=> 'category-thumb alignleft'));
	if ( $thumb ) {
	
		echo $thumb = "<a href=\"".get_permalink()."\" rel=\"bookmark\" title=\"permanent länk till ".the_title_attribute("echo=0")."\">".$thumb."</a>";
		
		
	} else {
		$att = get_category_default_image();
		// Skip the category_id subarray and move to attachment_image array
		$att = $att['src'];
		if($att)
		echo $thumb = "<a href=\"".get_permalink()."\" rel=\"bookmark\" title=\"permanent länk till ".the_title_attribute("echo=0")."\"><img alt='".the_title_attribute("echo=0")."' class=\"category-thumb defaultthumb alignleft\" src=\"$att[0]\" width=\"$att[1]\" height=\"$att[2]\" /></a>";
		
		
	}
	
	
	$catimage_cache[$post->ID] = $thumb;
	add_option("catimage_cache", $catimage_cache) or update_option("catimage_cache", $catimage_cache);

	
	endif;
	
		
	}
	
function get_THS_calendar($initial = true,$echo = true) {
	// 5 querys
	global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
	
	if(get_query_var('event') > 1 && !$year){
		$monthnum = substr(get_query_var('event'),4,2);
		$year = substr(get_query_var('event'),0,4);
		$m = get_query_var("m");
		}
	
	$cache = array();
	$key = md5( $m . $monthnum . $year );
	if ( $cache = wp_cache_get( 'get_THS_calendar', 'calendar' ) ) {
		if ( is_array($cache) && isset( $cache[ $key ] ) ) {
			echo $cache[ $key ];
			return;
		}
	}

	if ( !is_array($cache) )
		$cache = array();

	// Quick check. If we have no posts at all, abort!
	if ( !$posts ) {
		$gotsome = $wpdb->get_var("SELECT 1 as test FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' LIMIT 1");
		if ( !$gotsome ) {
			$cache[ $key ] = '';
			wp_cache_set( 'get_THS_calendar', $cache, 'calendar' );
			return;
		}
	}

	ob_start();
	if ( isset($_GET['w']) )
		$w = ''.intval($_GET['w']);

	// week_begins = 0 stands for Sunday
	$week_begins = intval(get_option('start_of_week'));

	// Let's figure out when we are
	if ( !empty($monthnum) && !empty($year) ) {
		
		$thismonth = ''.zeroise(intval($monthnum), 2);
		$thisyear = ''.intval($year);
	} elseif ( !empty($w) ) {
		// We need to get the month from MySQL
		$thisyear = ''.intval(substr($m, 0, 4));
		$d = (($w - 1) * 7) + 6; //it seems MySQL's weeks disagree with PHP's
		$thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('${thisyear}0101', INTERVAL $d DAY) ), '%m')");
	} elseif ( !empty($m) ) {

		$thisyear = ''.intval(substr($m, 0, 4));
		if ( strlen($m) < 6 ) :
				$thismonth = '01';
		else :
				$thismonth = ''.zeroise(intval(substr($m, 4, 2)), 2);
				$thisyear = ''.zeroise(intval(substr($m, 0, 4)), 4);
		endif;
	} else {
		$thisyear = gmdate('Y', current_time('timestamp'));
		$thismonth = gmdate('m', current_time('timestamp'));
	}

	$unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
	//echo $thisyear;
	$nextmonth = intval($thismonth)+1;
	$nextyear = intval($thisyear);
	
	if($thismonth > 11) :
		$nextmonth = 1;
		$nextyear = $thisyear+1;
	endif;
		
	$unixnextmonth = mktime(0, 0 , 0, $nextmonth, 1, $nextyear);
	
	
	// Get the next and previous month and year with at least one post
	$previous = $wpdb->get_row("SELECT DISTINCT MONTH(post_date) AS month, YEAR(post_date) as year, $wpdb->postmeta.meta_value as meta_value FROM $wpdb->posts  JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)  WHERE 1=1  AND $wpdb->posts.post_type = 'post' AND $wpdb->posts.post_status = 'publish' AND $wpdb->postmeta.meta_key = '_isEvent' AND $wpdb->postmeta.meta_value < ".$unixmonth." GROUP BY $wpdb->posts.ID ORDER BY $wpdb->postmeta.meta_value DESC LIMIT 1");
	
	
	$next = $wpdb->get_row("SELECT DISTINCT MONTH(post_date) AS month, YEAR(post_date) as year, $wpdb->postmeta.meta_value as meta_value FROM $wpdb->posts JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)  WHERE 1=1  AND $wpdb->posts.post_type = 'post' AND $wpdb->posts.post_status = 'publish' AND $wpdb->postmeta.meta_key = '_isEvent' AND  $wpdb->postmeta.meta_value > ".$unixnextmonth."  GROUP BY $wpdb->posts.ID ORDER BY $wpdb->postmeta.meta_value ASC LIMIT 1");

if($previous) :
	$previous->month = date('m', $previous->meta_value);
	$previous->year = date('Y', $previous->meta_value);

endif;

if($next) :
	$next->month = date('m', $next->meta_value);
	$next->year = date('Y', $next->meta_value);

endif;


	/* translators: Calendar caption: 1: month name, 2: 4-digit year */
	$calendar_caption = _x('%1$s %2$s', 'calendar caption');
	echo '<table id="wp-calendar" summary="' . esc_attr__('Calendar') . '">
	<caption><a href="'.THS_get_month_link(date('Y', $unixmonth), $thismonth) .'">' . sprintf($calendar_caption, $wp_locale->get_month($thismonth), date('Y', $unixmonth)) . '</a></caption>
	<thead>
	<tr>';

	$myweek = array();

	for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
		$myweek[] = $wp_locale->get_weekday(($wdcount+$week_begins)%7);
	}

	foreach ( $myweek as $wd ) {
		$day_name = (true == $initial) ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
		$wd = esc_attr($wd);
		echo "\n\t\t<th abbr=\"$wd\" scope=\"col\" title=\"$wd\">$day_name</th>";
	}

	echo '
	</tr>
	</thead>

	<tfoot>
	<tr>';

	if ( $previous ) {
		echo "\n\t\t".'<td abbr="' . $wp_locale->get_month($previous->month) . '" colspan="3" id="prev"><a href="' .
		THS_get_month_link($previous->year, $previous->month) . '" onclick="try{get_cal(\''.$previous->year.$previous->month.'\')}catch(e){};return false;" title="' . sprintf(__('View posts for %1$s %2$s'), $wp_locale->get_month($previous->month),
			date('Y', mktime(0, 0 , 0, $previous->month, 1, $previous->year))) . '">&laquo; ' . $wp_locale->get_month_abbrev($wp_locale->get_month($previous->month)) . '</a></td>';
	} else {
		echo "\n\t\t".'<td colspan="3" id="prev" class="pad">&nbsp;</td>';
	}

	echo "\n\t\t".'<td class="pad">&nbsp;</td>';

	if ( $next ) {
		echo "\n\t\t".'<td abbr="' . $wp_locale->get_month($next->month) . '" colspan="3" id="next"><a href="' .
		get_month_link($next->year, $next->month) . '"  onclick="try{get_cal(\''.$next->year.$next->month.'\')}catch(e){};return false;" title="' . esc_attr( sprintf(__('View posts for %1$s %2$s'), $wp_locale->get_month($next->month) ,
			date('Y', mktime(0, 0 , 0, $next->month, 1, $next->year))) ) . '">' . $wp_locale->get_month_abbrev($wp_locale->get_month($next->month)) . ' &raquo;</a></td>';
	} else {
		echo "\n\t\t".'<td colspan="3" id="next" class="pad">&nbsp;</td>';
	}

	echo '
	</tr>
	</tfoot>

	<tbody>
	<tr>';

	// Get days with posts
	//$dayswithposts = $wpdb->get_results("SELECT wp_postmeta.meta_value as meta_value FROM wp_posts  JOIN wp_postmeta ON (wp_posts.ID = wp_postmeta.post_id)  WHERE 1=1  AND wp_posts.post_type = 'post' AND wp_posts.post_status = 'publish' AND wp_postmeta.meta_key = '_isEvent' AND wp_postmeta.meta_value > '".$unixmonth."' AND wp_postmeta.meta_value < '".$unixnextmonth."'  GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC", ARRAY_N);
	
	$dayswithposts = $wpdb->get_results("SELECT $wpdb->postmeta.meta_value as meta_value FROM $wpdb->posts  JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)  WHERE 1=1  AND $wpdb->posts.post_type = 'post' AND $wpdb->posts.post_status = 'publish' AND $wpdb->postmeta.meta_key = '_isEvent' AND $wpdb->postmeta.meta_value > '".$unixmonth."' AND $wpdb->postmeta.meta_value < '".$unixnextmonth."'  GROUP BY $wpdb->posts.ID ORDER BY $wpdb->posts.post_date DESC", ARRAY_N);



	
	if ( $dayswithposts ) {
		foreach ( (array) $dayswithposts as $daywith ) {
			$daywithpost[] = date("j",$daywith[0]);
			
		}
	} else {
		$daywithpost = array();
	}

	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'camino') !== false || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'safari') !== false)
		$ak_title_separator = "\n";
	else
		$ak_title_separator = ', ';

	$ak_titles_for_day = array();
/*	$ak_post_titles = $wpdb->get_results("SELECT post_title, DAYOFMONTH(post_date) as dom, wp_postmeta.meta_value as meta_value "
		."FROM $wpdb->posts "
		."JOIN wp_postmeta ON ($wpdb->posts.ID = wp_postmeta.post_id) "
		."WHERE wp_postmeta.meta_key = '_isEvent' "
		."AND wp_postmeta.meta_value > '".$unixmonth."' "
		."AND wp_postmeta.meta_value < '".$unixnextmonth."' "
		."AND post_type = 'post' AND post_status = 'publish'"
	); */

//leos: rattar till en bugg som gor att man itne bara kan anvdnda wp_* tabeller, utan med alla prefix. ratt ska vara ratt.

$ak_post_titles = $wpdb->get_results("SELECT post_title, DAYOFMONTH(post_date) as dom, $wpdb->postmeta.meta_value as meta_value "
."FROM $wpdb->posts "
."JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id) "
."WHERE $wpdb->postmeta.meta_key = '_isEvent' "
."AND $wpdb->postmeta.meta_value > '".$unixmonth."' "
."AND $wpdb->postmeta.meta_value < '".$unixnextmonth."' "
."AND post_type = 'post' AND post_status = 'publish'"
);
	

	if ( $ak_post_titles ) {
		foreach ( (array) $ak_post_titles as $ak_post_title ) {
			$ak_post_title->dom = date('j',$ak_post_title->meta_value);

				$post_title = esc_attr( apply_filters( 'the_title', $ak_post_title->post_title ) );

				if ( empty($ak_titles_for_day['day_'.$ak_post_title->dom]) )
					$ak_titles_for_day['day_'.$ak_post_title->dom] = '';
				if ( empty($ak_titles_for_day["$ak_post_title->dom"]) ) // first one
					$ak_titles_for_day["$ak_post_title->dom"] = $post_title;
				else
					$ak_titles_for_day["$ak_post_title->dom"] .= $ak_title_separator . $post_title;
		}
	}


	// See how much we should pad in the beginning
	$pad = calendar_week_mod(date('w', $unixmonth)-$week_begins);
	if ( 0 != $pad )
		echo "\n\t\t".'<td colspan="'. esc_attr($pad) .'" class="pad">&nbsp;</td>';

	$daysinmonth = intval(date('t', $unixmonth));
	for ( $day = 1; $day <= $daysinmonth; ++$day ) {
		if ( isset($newrow) && $newrow )
			echo "\n\t</tr>\n\t<tr>\n\t\t";
		$newrow = false;

		if ( $day == gmdate('j', (time() + (get_option('gmt_offset') * 3600))) && $thismonth == gmdate('m', time()+(get_option('gmt_offset') * 3600)) && $thisyear == gmdate('Y', time()+(get_option('gmt_offset') * 3600)) )
			echo '<td id="today">';
		else
			echo '<td>';

		if ( in_array($day, $daywithpost) ) // any posts today?
				echo '<a href="' . THS_get_day_link($thisyear, $thismonth, $day) . "\" title=\"" . esc_attr($ak_titles_for_day[$day]) . "\">$day</a>";
		else
			echo $day;
		echo '</td>';

		if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) )
			$newrow = true;
	}

	$pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins);
	if ( $pad != 0 && $pad != 7 )
		echo "\n\t\t".'<td class="pad" colspan="'. esc_attr($pad) .'">&nbsp;</td>';

	echo "\n\t</tr>\n\t</tbody>\n\t</table>";

	$output = ob_get_contents();
	ob_end_clean();
	if($echo == true)
	echo $output;
	else return $output;
	
	
	$cache[ $key ] = $output;
	wp_cache_set( 'get_THS_calendar', $cache, 'calendar' );
}

/**  
 * Retrive an array with info of default image for category 
 *
 * @param int $id Optional. Category ID.
 * @return Array
 */	
function get_category_default_image($id = false){
	if(!$id);
	$id = get_the_category();
	
	if(is_array($id)){
		$id = $id[0]->term_id;
		}
	$THS_image = (array) get_option("THS-images");

	return $THS_image[$id];
	}
function the_category_color($id = false){
	
	if($id == false)
		$id = get_the_category();
		
	if(is_array($id)){
		$id = $id[0]->term_id;
		}
	$color = get_category_color($id);
	if($color)
	echo "#".$color;
	}
function get_category_color($cat_id){
	global $catcolors;
	$root_id = get_category_root_id($cat_id);
	
	if(is_array($colors))
	return $catcolors[$root_id];


	$catcolors = (array) get_option("THS-catcolors");
	return $catcolors[$root_id];
	}
function get_category_root_id($id){
	global $root_cat_cache;
	
	if(is_array($id)){
		$id = $id[0]->term_id;
		}
	
	if(!is_array($root_cat_cache))
	$root_cat_cache = array();
	
	if($root_cat_cache[$id])
		return $root_cat_cache[$id];
	
	
	
	$ancestors = get_category_ancenstors($id);
	$root_id = $ancestors[count($ancestors)-1];
	if(!$ancestors)
		$root_id = $id;
		
	$root_cat_cache[$id] = $root_id;
	
	return $root_id;
	}
function get_root_id($id){
	global $root_cache;
	
	if(!is_array($root_cache))
	$root_cache = array();
	
	if($root_cache[$id])
		return $root_cache[$id];
	
	
	$ancestors = get_post_ancestors($id);
	$root_id = $ancestors[count($ancestors)-1];
	if(!$ancestors)
		$root_id = $id;
		
	$root_cache[$id] = $root_id;
	
	return $root_id;
	}

function the_root_color($p_id){

	echo "#".get_root_color($p_id);
	}
function get_root_color($post_id){
	global $colors;
	
	if(is_single($post_id) || is_category()){
		
	return get_category_color(get_the_category($post_id));
		}
	if(is_search()){
		$post_id = get_option("page_on_front");
		}
	
	$root_id = get_root_id($post_id);
	
	
	
	if(is_array($colors))
	return $colors[$root_id];

	
	$colors = (array) get_option("THS-colors");
	return $colors[$root_id];
	}
function get_post_THS_page($post_id){
	global $catPages;
	$cats = get_the_category($post_id);
	
	$root_id = get_category_root_id($cats);
	
	if(is_array($catPages) && $catPages[$root_id])
	return $catPages[$root_id];
	
	$catPages = (array) get_option("THS-pages");
	
	if(!$catPages[$root_id])
	return get_option("page_on_front");
		
		
	return $catPages[$root_id];
	 
	}
	
add_action('admin_menu', 'THS_add_theme_page');



function THS_add_theme_page() {
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'edit_foot' ) {
		if ( isset( $_REQUEST['action'] ) && 'save_footer' == $_REQUEST['action'] ) {
			check_admin_referer('THS-footer');
			
			add_option("THS-footer", $_POST['foot']) or update_option("THS-footer",$_POST['foot']);
			wp_redirect("themes.php?page=edit_foot&saved=true");
			die;
		}
		}
	
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'edit_rand' ) {
		if ( isset( $_REQUEST['action'] ) && 'save_rand' == $_REQUEST['action'] ) {
			check_admin_referer('THS-risros');
			
			foreach($_POST['foot'] as $f) :
				
				if($f)
				$fi[] = $f;
				
			endforeach;
			
			add_option("THS-risros", stripslashes_deep($fi)) or update_option("THS-risros",stripslashes_deep($fi));
			wp_redirect("themes.php?page=edit_rand&saved=true");
			die;
		}
		}
	
	if ( isset( $_GET['page'] ) && $_GET['page'] == basename(__FILE__) ) {
		if ( isset( $_REQUEST['action'] ) && 'save_banner' == $_REQUEST['action'] ) {
				check_admin_referer('THS-header-banner');
				
		if(!is_array($_POST['banners']))
				wp_die("Invalid input");
			
			$banners = array();
			
			foreach($_POST['banners'] as $bannerkey => $banner) :
				if($banner['image']) :
					$banner['src'] = wp_get_attachment_url($banner['image']);
					$banners[] = $banner;
					endif;
			endforeach;
			add_option("ths-banners", $banners) or update_option("ths-banners",$banners);
$file = '<?xml version="1.0" encoding="UTF-8"?>
<rotator isRandom="false">
  <bannerTime>5</bannerTime>
  <banners>';
  foreach($banners as $banner) :
 
  
  $file .= '
	<banner>
		<name>'.$banner['title'].'</name>
		<body>'.$banner['body'].'</body>
		<imagePath>'.$banner['src'].'</imagePath>
		<link>'.$banner['link'].'</link>
	 </banner>';
endforeach;
 $file .='
  </banners>
</rotator>';
$f = fopen(TEMPLATEPATH."/banners.xml","w");
fwrite($f,$file);
fclose($f);
wp_redirect("themes.php?page=functions.php&saved=true");
exit();

			
		}
			if ( isset( $_REQUEST['action'] ) && 'save_cat' == $_REQUEST['action'] ) {
			check_admin_referer('THS-header-cat');
			if(!is_array($_POST['CatColors']))
				wp_die("Invalid input");
			
			$colors = array();
			$images = array();
			$pages = array();
			
		
			
		
			foreach($_POST['catPages'] as $cat_id => $page_id) :
				
				if($page_id) :
					$pages[$cat_id] = $page_id;
				endif;
				
			endforeach;
			
			
			foreach($_POST['catImages'] as $cat => $image) :
				
				if($image) :
					$images[$cat]['src'] = wp_get_attachment_image_src($image);
					$images[$cat]['id'] = $image;
				endif;
				
			endforeach;
			foreach($_POST['CatColors'] as $cat => $color) :
				if($color)
					$colors[$cat] = $color;
			endforeach;
			add_option("english_url", $_POST['english_url']) or update_option("english_url",$_POST['english_url']);
			add_option("embed_url", $_POST['embed_url']) or update_option("embed_url",$_POST['embed_url']);
			add_option("THS-pages", $pages) or update_option("THS-pages",$pages);
			add_option("THS-images", $images) or update_option("THS-images",$images);
			add_option("THS-catcolors", $colors) or update_option("THS-catcolors",$colors);
			wp_redirect("themes.php?page=functions.php&saved=true");
			die;
			}
		if ( isset( $_REQUEST['action'] ) && 'save' == $_REQUEST['action'] ) {
			check_admin_referer('THS-header');
			
			if(!is_array($_POST['colors']))
				wp_die("Invalid input");
			
			$colors = array();
			
			foreach($_POST['colors'] as $page_id => $color) :
				if($color)
					$colors[$page_id] = $color;
			endforeach;
			add_option("THS-colors", $colors) or update_option("THS-colors",$colors);
			wp_redirect("themes.php?page=functions.php&saved=true");
			die;
		}
		add_action('admin_head', 'THS_theme_page_head');
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
	}
	add_meta_box( 'THS_cal', __( 'Calendar' ), 'THS_post_meta_box', 'post', 'side','high' );
	add_theme_page(__('Custom Header'), __('Custom Header'), 'edit_themes', basename(__FILE__), 'THS_theme_page');
	add_theme_page('Ris och Ros','Ris och Ros', 'edit_themes', "edit_rand", 'THS_theme_random_page');
	add_theme_page(__('Custom Footer'),__('Custom Footer'), 'edit_themes', "edit_foot", 'THS_theme_foot_page');
	wp_enqueue_style('cal',get_bloginfo('stylesheet_directory') ."/cal.css");
}
function THS_theme_page_head() {
?>
<script>
var current_cat;
function tb_close(){var win=window.dialogArguments||opener||parent||top;win.tb_remove();}

function send_to_editor(inp){
	inp = inp.match("wp-image-([0-9]{1,})");
	jQuery("#object-"+current_cat).children(".imgval").val(inp[1]);
	jQuery("#object-"+current_cat).children(".THShide").children(".imgprev").html('<span class="updated">Spara för att se ändringar.</span>');
	tb_close();
	}
var thedir = "<?php echo bloginfo("stylesheet_directory") ?>/images/";
jQuery(document).ready(function(){
				jQuery(".toggler").click(function(){
					jQuery(this).parent().children(".THShide").toggleClass("hidden");
												  }
				);
					  
					  });
</script>
<script type="text/javascript" src="<? bloginfo("stylesheet_directory") ?>/jscolor.js"></script>
<style>
.toggler {
-moz-border-radius-bottomleft:6px;
-moz-border-radius-bottomright:6px;
border-style:solid;
border-width:1px;
	-moz-border-radius-topleft:6px;
-moz-border-radius-topright:6px;
border-style:solid solid none;
border-width:1px 1px 0;
-moz-background-clip:border;
-moz-background-inline-policy:continuous;
-moz-background-origin:padding;
background:#F1F1F1 url(../images/menu-bits.gif) repeat-x scroll left -379px;
border-color:#E3E3E3;
line-height:18px;
min-width:10em;
padding:5px;
cursor:pointer;
	}
.ths-banner {
margin-bottom:20px;
	}
.ths-banner .toggler:hover {
color:#D54E21;
	}
.THShide {
-moz-background-clip:border;
-moz-background-inline-policy:continuous;
-moz-background-origin:padding;
border-left:1px solid #eee;
border-right:1px solid #eee;
border-bottom:1px solid #eee;
padding:25px;
}
	}
</style>
<?
	}
function THS_theme_foot_page() {
	$foot = get_option('THS-footer');
		if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.').'</strong></p></div>';
	?>
    <div class="wrap">
    <h2>Footer:</h2>
  <form method="post" action="" enctype="multipart/form-data">
   <?php wp_nonce_field('THS-footer'); ?>
   <input name="action" type="hidden" value="save_footer" />
   <p><input type="submit" value="<?php _e('Save') ?>" class="button" /></p>
   <?php 
   $a = 0;
   for($a;5 > $a;$a++) : ?>
    <p class="alignleft" style="margin-right:5px;">
    Titel:<br />
    <textarea name="foot[<?php echo $a ?>][title]"><?php echo $foot[$a]['title'] ?></textarea><br />	
    <textarea name="foot[<?php echo $a ?>][text]" style="width:200px; height:200px;"><?php echo stripslashes($foot[$a]['text']) ?></textarea></p>
    
   <?php endfor; ?>
   </form>
    </div>
    <?
	
	}
	

	function THS_theme_random_page() {
	$foot = get_option('THS-risros');
		if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.').'</strong></p></div>';
	?>
    <div class="wrap">
    <h2>Ris och Ros:</h2>
  <form method="post" action="" enctype="multipart/form-data">
   <?php wp_nonce_field('THS-risros'); ?>
   <input name="action" type="hidden" value="save_rand" />
   <p><input type="submit" value="<?php _e('Save') ?>" class="button" /></p>
   <?php 
   $a = 0;
   for($a;10 > $a;$a++) : ?>
    <p class="alignleft" style="margin-right:5px;">
    Text:<br />
   
    <textarea name="foot[<?php echo $a ?>]" style="width:200px; height:200px;"><?php echo $foot[$a] ?></textarea></p>
    
   <?php endfor; ?>
   </form>
    </div>
    <?
	
	}
function THS_theme_page() {
$colors = (array) get_option("THS-colors");
$CatColors = (array) get_option("THS-catcolors");
$catImages = (array) get_option("THS-images");
$catPages = (array) get_option('THS-pages');
	if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.').'</strong></p></div>';
	
	$idcount = 1;
?>
<div class='wrap'>
  <h2>Koppla sidor till färger</h2>
  <form method="post">
    <br />
    <?php wp_nonce_field('THS-header'); ?>
    <?php $pages = get_pages(array("parent" => 0)); ?>
    <?php foreach($pages as $page) : 
	$idcount++;
	?>
    <div> <?php echo $page->post_title; ?>: Färgkod
      <input class="color {pickerMode:'HVS'}" name="colors[<?php echo $page->ID ?>]" value="<?php echo $colors[$page->ID] ?>" />
    </div>
    <? endforeach; ?>
    <input type="hidden" name="action" value="save" />
    <input type="submit" class="button" value="Spara"/>
  </form>
  <h2>Koppla Kategorier till färger</h2>
  <form method="post">
    <br />
    <?php wp_nonce_field('THS-header-cat'); ?>
    <?php  $categories = get_categories("parent=0");   ?>
    <?php foreach($categories as $cat) : 
	$idcount++;
	?>
    <div id="object-<?php echo $idcount; ?>">
        <input class="imgval" type="hidden" value="<?php echo $catImages[$cat->cat_ID]['id'] ?>" name="catImages[<?php echo $cat->cat_ID ?>]" />
      <div class="toggler"><?php echo $cat->cat_name ?></div>
      <div class="THShide hidden"> : Färgkod
        <input class="color {pickerMode:'HVS'}" name="CatColors[<?php echo $cat->cat_ID ?>]" value="<?php echo $CatColors[$cat->cat_ID] ?>" />
        <p>
        Sida som inläggen ska visas under:
      <select name="catPages[<?php echo $cat->cat_ID ?>]">
<option value="0">Välj Sida</option>
      <?php foreach($pages as $page) : ?>

 <option <?php selected($catPages[$cat->cat_ID],$page->ID) ?> value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
 <?php endforeach; ?>
 </select>
 </p>
       <p>Välj ny bild:
        <a onclick="current_cat = <?php echo $idcount; ?>;return false;" title="Add an Image" class="thickbox" id="add_image" href="media-upload.php?type=image&amp;tab=library&amp;TB_iframe=true&amp;width=640&amp;height=263"><img alt="Add an Image" src="images/media-button-image.gif"/> </a></p> <span class="hidden updated"><i>Spara för att se bild</i></span>
        <p class="imgprev">
		<?php if($catImages[$cat->cat_ID]) :
		?><img src="<? echo $catImages[$cat->cat_ID]["src"][0]; ?>" width="<? echo $catImages[$cat->cat_ID]["src"][1]; ?>" height="<? echo $catImages[$cat->cat_ID]["src"][2]; ?>" /> 
        
        <?
		else :
		?>
		Ingen bild vald.
		<?
		endif;
		?></p>
      </div>
    </div>
    <? endforeach; ?>
    <input type="hidden" name="action" value="save_cat" />
      <div>
    <p>Adresslänk för engelska ikonen</p>
    <p><input type="text" name="english_url" value="<?php echo get_option('english_url') ?>" /></p>
    <p>Adresslänk för kugghjulet</p>
    <p><input type="text" name="embed_url" value="<?php echo get_option('embed_url') ?>" /></p>
    </div>
    <input type="submit" class="button" value="Spara"/>
  </form>
  <form method="post" style="width:400px">
    <?php wp_nonce_field('THS-header-banner'); 
	$banners = get_option("ths-banners");
	$num = count($banners);
	$idcount++;
	?>
    <h2>Bannerbilder</h2>
    <div id="object-<?php echo $idcount; ?>"  class="ths-banner">
     <input class="imgval" type="hidden" value="<?php echo $banner['image'] ?>" name="banners[<?php echo $num ?>][image]" />
      <div class="toggler">Skapa ny banner&raquo;</div>
      <div class="THShide hidden"> Bildsökväg:
    	 <a onclick="current_cat = <?php echo $idcount; ?>;return false;" title="Add an Image" class="thickbox" id="add_image" href="media-upload.php?type=image&amp;tab=library&amp;TB_iframe=true&amp;width=640&amp;height=263"><img alt="Add an Image" src="images/media-button-image.gif"/> </a>
        <p class="imgprev">
         Ingen bild vald
        </p>
        <p>Titel
          <input name="banners[<?php echo $num ?>][title]" class="widefat" />
        </p>
        <p> Text:
          <textarea name="banners[<?php echo $num ?>][body]" class="widefat"></textarea>
        </p>
        <p> Länk:
          <input name="banners[<?php echo $num ?>][link]" class="widefat" />
        </p>
      </div>
    </div>
    <?php  foreach($banners as $key => $banner) : 
	$idcount++;
	?>
    <div id="object-<?php echo $idcount; ?>" class="ths-banner">
     <input class="imgval" type="hidden" value="<?php echo $banner['image'] ?>" name="banners[<?php echo $key ?>][image]" />
      <div class="toggler">Banner: "<?php echo $banner['title'] ?>"</div>
      <div class="THShide hidden"> Bild:
       <a onclick="current_cat = <?php echo $idcount; ?>;return false;" title="Add an Image" class="thickbox" id="add_image" href="media-upload.php?type=image&amp;tab=library&amp;TB_iframe=true&amp;width=640&amp;height=263"><img alt="Add an Image" src="images/media-button-image.gif"/> </a>
        <p class="imgprev">
         <?php if($banner["image"]) :
		$image = wp_get_attachment_image_src($banner["image"]);
		?><img src="<? echo $image[0]; ?>" width="<? echo $image[1]; ?>" height="<? echo $image[2]; ?>" /> 
        
        <?
		else :
		?>
		Ingen bild vald.
		<?
		endif;
		?>
        </p>
        <p>Titel
          <input value="<?php echo $banner['title'] ?>" name="banners[<?php echo $key ?>][title]" class="widefat" />
        </p>
        <p> Text:
          <textarea name="banners[<?php echo $key ?>][body]" class="widefat"><?php echo $banner['body'] ?></textarea>
        </p>
        <p> Länk:
          <input value="<?php echo $banner['link'] ?>" name="banners[<?php echo $key ?>][link]" class="widefat" />
        </p>
        <p>
          <button class="button" onclick="if(confirm('Är du säker att du vill radera denna banner?')) {jQuery(this).parent().parent().parent().remove()}; return false;">Radera</button>
        </p>
      </div>
    </div>
    <?php endforeach; ?>
    <input type="hidden" name="action" value="save_banner" />
    <input type="submit" value="Spara Banners" class="button" />
  
  </form>
</div>
<?php } 

wp_register_sidebar_widget('modnet-subpages', 'Undersidemeny', 'list_root_parent_widget',$option);
function list_root_parent_widget($args){
	extract($args);
	global $post;
	if(is_page())
	$parent = get_root_id($post->ID);
	
	if(is_single())
	$parent = get_post_THS_page($post->ID);
	
	if($parent)
	$children = wp_list_pages('hierarchical=0&title_li=&child_of='.$parent.'&echo=0');
				if($children) :
			
			echo $before_widget
				?>

<ul class="mainul">
  <?php echo $children; ?>
</ul>
<?php 
	echo $after_widget;
	endif;
	}
wp_register_sidebar_widget('ths-risros', 'Ris & Ros', 'risros_widget',$option);
function risros_widget($args){
	extract($args);
	global $post;
	$curr = get_option('THS-risros');
	$curr = $curr[mt_rand(0,count($curr)-1)];
	
	
	
			
			echo $before_widget
				?>
<div class="inner">
<p><?php echo nl2br($curr); ?></p>
</div>
<?php 
	echo $after_widget;

	}
	
	
// Calendar functionality

add_action('save_post',"THS_save_post_cal");


function THS_post_meta_box($post){
	

if($post->ID) :
	
		if(get_post_meta($post->ID,"_isEvent",true)){
			$isEvent = true;
			$timestartstamp = (int) get_post_meta($post->ID,"_isEvent",true);
			$timeendstamp = (int) get_post_meta($post->ID,"_endEvent",true);
			
			$eventDate = date("F j, Y",$timestartstamp);
			$timeStart = array(date("H",$timestartstamp),date("i",$timestartstamp));
			$timeEnd = array(date("H",$timeendstamp),date("i",$timeendstamp));

			}
		else {
			$isEvent = false;
			$eventDate = false;
	
			$timeStart[0] = "12";
			$timeStart[1] = "00";
		
			$timeEnd[0] = "13";
			$timeEnd[1] = "00";
			}
		
endif;
?>

  <p> <label for="eventDate"> Lägg till i kalendern</label>
   <input name="isEvent" <?php if($isEvent){ echo "checked=\"checked\""; }?> value="1" type="checkbox" />     </p>
   <label for="eventDate"> <?php echo _e("Date") ?> </label>
  <input type="text" id="date" name="eventDate" value="<?php echo $eventDate; ?>" />
  <?php wp_nonce_field("WEC-post","wec_nonce"); ?>
<?php if($eventId) : ?>
<input type="hidden" name="eventid" value="<?php echo $eventId ?>" />
<?php endif; ?>
 
 <p>
<select name="startTimeHour" id="startTimeHour">
<?php for($a=0; $a <= 23;$a++) { ?>
<option value="<?php printf("%02d",$a)?>" <?php selected($timeStart[0],sprintf('%02d',$a))?>><?php printf("%02d",$a) ?></option>
<? } ?>
 </select> 
 
 
<select name="startTimeMin" id="startTimeMin">
<?php for($a=0; $a <= 55;$a=$a+5) { ?>
<option value="<?php printf("%02d",$a)?>" <?php selected($timeStart[1],sprintf('%02d',$a))?>><?php printf("%02d",$a) ?></option>
<? } ?>
 </select>
 </p> 

 <p>
<select name="endTimeHour" id="endTimeHour">
<?php for($a=0; $a <= 23;$a++) { ?>
<option value="<?php printf("%02d",$a)?>" <?php selected($timeEnd[0],sprintf('%02d',$a))?>><?php printf("%02d",$a) ?></option>
<? } ?>
 </select> 
 
 
<select name="endTimeMin" id="endTimeMin">
<?php for($a=0; $a <= 55;$a=$a+5) { ?>
<option value="<?php printf("%02d",$a)?>" <?php selected($timeEnd[1],sprintf('%02d',$a))?>><?php printf("%02d",$a) ?></option>
<? } ?>
 </select>
 </p>
 <p>
 
<script type="text/javascript" src="<?php echo bloginfo('stylesheet_directory') . "/jquery.datepicker.js" ?>"/></script>
<script>

	jQuery("#date").datepicker({ firstDay: 1 , dateFormat: "MM d, yy"});		  
					  
				

</script>
<?

	} 

function THS_save_post_cal($post_id){
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
    return $post_id;
	
	$nonce = $_REQUEST['wec_nonce'];
if (! wp_verify_nonce($nonce, 'WEC-post') ) 
	return $post_id;
	
	
	
	if(wp_is_post_revision($post_id))
		return;
	
	
		
	$_REQUEST['wec_nonce'] = false;

/* LEO rattar till


functions.php  - under funktion THS_save_post_cal

FEL --------- 

$catimages = (array) get_option('catimages_cache');
$catimages[$post_id] = false;
add_option('catimages_cache',$catimages) or update_option('catimages_cache',$catimages);

RDTT ------





*/		
//		$catimages = (array) get_option('catimage_cache');
//		$catimages[$post_id] = false;
//		add_option('catimage_cache',$catimages) or update_option('catimage_cache',$catimages);

$catimages = (array) get_option('catimage_cache');
$catimages[$post_id] = false;
add_option('catimage_cache',$catimages) or update_option('catimage_cache',$catimages);			
		if( !$_POST['isEvent'] ) {
					delete_post_meta($post_id,"_isEvent");
					delete_post_meta($post_id,"_endEvent");
				return;
		}
	$oldtime = date_default_timezone_get();
	date_default_timezone_set('UTC');

     //Turn these fields into timestamps
    $startTimestamp = strtotime($_POST['startTimeHour'].':'.$_POST['startTimeMin']." ".implode('', explode(',', $_POST['eventDate'])));
	$endTimestamp = strtotime($_POST['endTimeHour'].':'.$_POST['endTimeMin']." ".implode('', explode(',', $_POST['eventDate'])));


 	
	date_default_timezone_set($old);

	update_post_meta($post_id,"_isEvent",$startTimestamp);
	update_post_meta($post_id,"_endEvent",$endTimestamp);
	return true;
	}
	
	function new_excerpt_more($more) {
	return "<a class=\"readmore\" href=\"".get_permalink()."\">Läs mer</a>";
}
function Hex2RGB($color){
    $color = str_replace('#', '', $color);
    if (strlen($color) != 6){ return array(0,0,0); }
    $rgb = array();
    for ($x=0;$x<3;$x++){
        $rgb[$x] = hexdec(substr($color,(2*$x),2));
    }
    return $rgb;
}
add_filter('excerpt_more', 'new_excerpt_more')
?>