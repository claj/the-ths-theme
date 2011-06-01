<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>

<hr />
<br class="clear" />
</div>
<div  id="footer" role="contentinfo">
	<div id="innerfooter">
    <p style="margin:0; padding:0;margin-left:-3px;">
    <img src="<?php bloginfo('stylesheet_directory') ?>/images/THS_foot_logo-op.png" alt="Tekniska h�gskolans studentk�r" />
    </p>
   <?php 
   $foot = get_option('THS-footer');
   for($a=0;5 > $a;$a++) : ?>
   <p>
   <span><?php echo nl2br($foot[$a]['title']) ?></span>
   <?php echo nl2br(stripslashes($foot[$a]['text'])) ?>
   </p>
   <?php endfor; ?>
  <br  style="clear:both"/>

		<!-- <? echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
	
    </div>
<!-- Gorgeous design by Michael Heilemann - http://binarybonsai.com/kubrick/ -->
<div style="position:absolute;bottom:0px; right:10px;"><?php /* "Just what do you think you're doing Dave?" */ ?>
		<?php bloginfo('name'); ?>, org nr: 802005-9153, is proudly powered by
		<a href="http://wordpress.org/">WordPress</a>. <a href="<?php echo get_option('home'); ?>/wp-admin/">Logga in</a>
		<?php wp_footer(); ?>
        </div>
</div>
<?php /*
<!--<script type="text/javascript" src="<?php echo get_option('home'); ?>/banner.php"></script>     --> 
<!--<script type="text/javascript" src="http://kundo.se/embed/sv/tekniska-hogskolans-studentkar.js"></script>  -->
*/ ?>
</body>
</html>
