<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
 
if(is_front_page()){wp_enqueue_script('jquery');}
$theID = $post->ID;
if(is_single()) :
$root = get_post_THS_page(get_the_ID()); 
  if($root):
  $theID = $root;
  $current = "#meny li.page-item-".$root.", ";
 	endif;
  endif; 


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?> xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<style media="screen" type="text/css">
.widget {
	border-top:solid 1px <?php the_root_color($theID); ?> ;
	}
</style>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<?php if(is_single()): ?>
<!-- <?php #print_r($post); ?>-->
<meta property="og:image" content="<?php echo (has_post_thumbnail($post->ID) ? wp_get_attachment_url(get_post_thumbnail_id($post->ID)) : "http://www.ths.kth.se/wp-content/uploads/2010/04/THS_logo_doc.jpg"); ?>" />
<link rel="image_src" href="<?php echo (has_post_thumbnail($post->ID) ? wp_get_attachment_url(get_post_thumbnail_id($post->ID)) : "http://www.ths.kth.se/wp-content/uploads/2010/04/THS_logo_doc.jpg"); ?>" />
<meta property="og:title" content="<?php echo $post->post_title; ?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo $post->guid; ?>" />
<meta property="og:site_name" content="THS" />
<meta property="fb:admins" content="121470594571005"/>
<?php endif; ?>



<style media="screen" type="text/css">
.byuser, .byuser a {
	background-color: <?php $a = Hex2RGB(get_root_color($theID)); 
	$b = array();
	for ( $i = 0; $i < 3; $i++) { 
		$b[$i] = ((255-$a[$i])/1.2)+$a[$i];
	}
$hex = "#";
$hex.= dechex($b[0]);
$hex.= dechex($b[1]);
$hex.= dechex($b[2]);
echo $hex;
	?>;
	color:#333;
	}
#modnet-subpages ul.mainul > li:first-child a {
	border-top: 1px <?php the_root_color($theID); ?> solid;
	}

#modnet-subpages ul li.current_page_item>a {
	background:<?php the_root_color($theID); ?> no-repeat left center url(<?php bloginfo('stylesheet_directory'); ?>/images/subpages_sel.png);
	color:#fff;
}
<?php echo $current ?>#meny li.current_page_item, #meny li.current_page_ancestor{
	height:55px;
	background:<?php the_root_color($theID); ?> url(<?php bloginfo('stylesheet_directory'); ?>/images/btn_arrow.png) center !important;
	
	}
.innerclass{
	border-top:1px solid <?php the_root_color($theID); ?>;
	
	}
#meny li.page_item {
	background:url(<?php bloginfo('stylesheet_directory'); ?>/images/btn_gradient.png) repeat-x;
	}


<?php
$colors = get_option("THS-colors");
foreach($colors as $key => $col){
	if($key == get_root_id($post->ID))
		continue;
	?>#meny li.page-item-<?php echo $key ?>:hover {
background-color:#<?php echo $col?>;
	}

<?
	
	}
?>

</style>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_enqueue_script( 'swfobject' ); ?>
<?php wp_head(); ?>
<!--[if IE 6]>
<style type="text/css">
#wpcombar, #wpcombar .menupop a span, #wpcombar .menupop ul li a:hover, #wpcombar .myaccount a, .quicklinks a:hover,#wpcombar .menupop:hover {
	background-image: none !important;
}
</style>
<![endif]-->
<script type="text/javascript" src="<?php bloginfo("siteurl"); ?>/cal.php?m=<?php if(is_date()){ if($year) { echo $year; } if($monthnum) {printf("%02d",$monthnum);}} ?><?  if(is_front_page()): ?>&amp;exclude_jquery<?php endif; ?>"></script>
<?php if(is_front_page()): ?>
<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery(".hide-if-no-js").show();
	jQuery('#restofsneak').hide();
	jQuery("#readmore").click(function(){
						jQuery('#sneak').hide();					  
				jQuery('#restofsneak').show('slow');	
				return false;
									  });
	});
</script>
<?php endif; ?>

<script type="text/javascript">
	var params = {wmode: "transparent"};
			var attributes = {};
			var flashvars = {color: "<?php echo get_root_color($theID) ?>", bannerurl:  "<?php bloginfo('stylesheet_directory'); ?>/banners.xml?koft"};
    swfobject.embedSWF("<?php bloginfo('stylesheet_directory'); ?>/banner.swf?sadsad", "headerimg", "960", "200", "7", false, flashvars, params, attributes) 
    </script>
	<?
?>
<!--<script type="text/javascript" src="/wp-content/themes/THS/jquery.min.js"></script>-->
<script type='text/javascript' src='http://www.ths.kth.se/wp-includes/js/jquery/jquery.js?ver=1.3.2'></script>
<script type='text/javascript' src='http://www.ths.kth.se/wp-content/plugins/gtranslate/jquery-translate.js?ver=2.9.1'></script>
<script type="text/javascript" src="/wp-content/themes/THS/chili-1.7.pack.js"></script>
<script type="text/javascript" src="/wp-content/themes/THS/jquery.cycle.all.2.72.js"></script>

<script type="text/javascript">
$(function() {
    
    $('#jsbannerslideshow').cycle({
        timeout: 5000,
        slideExpr: null,
        pause: 1,
        pager:  '#jsbannernav',
        pagerAnchorBuilder: pagerFactory        
    });
    
    function pagerFactory(idx, slide) { 
            // return selector string for existing anchor 
            return '#jsbannernav li:eq(' + idx + ') a'; 
        };
    
});


</script>


<style type="text/css">

#jslogo { z-index: 50; position: relative; top: 0px; left: 54px; border: 0 }

#jsbannernav { z-index: 50; position: relative; top: -4px; left: 20px; background: #F40 }
#jsbannernav ul { margin: 0; padding: 0 }

#jsbannernav li { float: left; list-style: none}

#jsbannernav a {
    margin: 0px 2px 0px 0px;
    padding: 0px;
    border: 1px solid #FFF;
    background: #1c305f;
    text-decoration: none;
    height: 6px;
    width: 6px;
    display: block;
    }
#jsbannernav a.activeSlide {
    background: #ea4416;
    color: black
    }
#jsbannernav a:focus {
    outline: none;
    }

.jsbanner {
    height: 200px; width: 960px; padding:0; margin: -177px 0 0 0; overflow: hidden; background: #000; color: #FFF; text-decoration: none;
 }

.jsbanner img { height: 200px; width: 960px; padding: 0px; border: 0px solid #ccc; background-color: #eee; top:0; left:0 }

.jsbox {
	background-color: <?php the_root_color($theID)?>;
        background-image: url(/wp-content/themes/THS/bannerboxbg.png);
        background-position: center center;

	border: 3px;
	height: 138px;
	width: 138px;
	overflow: hidden;
	color: #FFF;
	font: 11px/18px "Century Gothic", Arial, Helvetica, sans-serif;    
	padding: 16px 20px;
	margin: -186px 0 16px 740px;
	z-index: 60;
	position: relative;

}
.jsbox h2 {
	font-size: 14px;
	font-weight: normal;
	padding: 0px;
	margin: 0px 0px 10px;
	text-decoration: none;
color: #FFF;

    
}

#jsbannerslideshow a:link, #jsbannerslideshow a:visited, #jsbannerslideshow a:hover, #jsbannerslideshow a:active {
	text-decoration: none;
}

</style>
</head>
<body <?php body_class(); ?>>

<div id="wpcombar" style="z-index: 1001;"><!--<span>Sektioner</span></a> listan --><div style="width:960px; margin:auto;">

<div class="quicklinks">

<ul class="thelinks">
<?php wp_list_bookmarks(array("category_orderby" => 'id',"exclude_category"=> "5",'before' => '<li class="menupop">','category_before' => '<li class="menupop">' ,"title_li" => '','title_before' => '<a href="#" onclick="return false;"><span>','title_after' => '</span></a>' , 'categorize'=>'1')); ?>
<?php wp_list_bookmarks(array("category"=>"5","title_li" => '', 'categorize'=>'0')); ?>
</ul>
</div>

<div id="english"><a href="<?php echo get_option("english_url"); ?>" title="In English"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/english.png" alt="English" /></a></div>

<div id="kugghjul"><a href="<?php echo get_option('embed_url') ?>" title="THS i din hemsida"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/gearwheel.png" alt="kugghjul" /></a></div>


<form id="adminbarsearch" method="get" action="<?php bloginfo('siteurl') ?>"><input type="text" onblur="this.value=(this.value=='') ? '<?php _e('Search')?>' : this.value;" onfocus="this.value=(this.value=='<?php _e('Search')?>') ? '' : this.value;" maxlength="150" value="<?php _e('Search')?>" id="s" name="s" class="adminbar-input"/> <button class="adminbar-button" type="submit"><span><?php _e('Search')?></span></button></form></div></div>
<div id="page">

<?php 

$agent = getenv("HTTP_USER_AGENT");

if(!empty($_GET['flash']) || preg_match("/MSIE/i", $agent)) {

?>

<div id="header" role="banner">
	<div id="headerimg">
<!--test-->
    <div style="background-image:url(<?php $a =get_option("THS-banners"); echo $a[0]['src']; ?>); height:200px;text-shadow:#000 0px 0px 3px;">
		<h1 style="font-size:4em;"><a  style="" href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
		<div style="font-size:2em;" class="description"><?php bloginfo('description'); ?>
		</div>
	</div>
    </div>
</div>

<?php

} 

else {

?>



<a href="/"><img id="jslogo" src="/wp-content/themes/THS/thslogo.png" width="121" height="167" alt="THS logotyp" title="THS (hem)" /></a>

    <?php $banners = get_option("ths-banners"); ?>

<ul id="jsbannernav">
  <?php echo str_repeat("<li><a href=\"#\"></a></li>\n", count($banners)); ?>
</ul>

<div id="jsbannerslideshow" class="jsbanner">

<?php  foreach($banners as $key => $banner) { ?>

<div>
   <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['src']; ?>" alt="<?php echo $banner['title']; ?>" width="960" height="200" /></a>
      <a href="<?php echo $banner['link']; ?>"><div class="jsbox"><h2><?php echo $banner['title']; ?></h2>
      <p><?php echo str_replace("\n", "</p><p>", $banner['body']); ?></p>
      </div></a>
</div>

<?php } ?>

</div>

<?php } ?>

<div id="meny" role="navigation">
	<ul>

		<?php wp_list_pages('title_li=&depth=1&exclude=24489'); ?>


	</ul>
</div>
<div class="clear"></div>
<hr  class="clear" />
