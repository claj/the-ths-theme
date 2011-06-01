<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
	<div id="sidebar" role="complementary">
		<ul>
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
				if ( function_exists('dynamic_sidebar')) : ?>
			<?php
                            if(is_page())
                               dynamic_sidebar(2);

                               dynamic_sidebar(4);

			if(is_front_page())
			    dynamic_sidebar(3);
		               
			    dynamic_sidebar(1);
			?>
			<?php endif; ?>
		</ul>
	</div>

