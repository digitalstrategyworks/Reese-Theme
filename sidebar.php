<?php
/* the sidebar is simple
 * because it only contains
 * one dynamic sidebar that is
 * used on every page. if users want
 * to customize the widgets that
 * are seen on each page i recommend
 * the plugin ts custom widgets
 */ ?>
			<!-- begin right bar -->
			<div id="rightBar" class="grid_4">				
				
				<!-- widgets, which appear as list items -->
				
				<?php if ( is_sidebar_active('sidebar') ) : ?>
					<div id="sidebar" class="widget-area">
						<ul class="xoxo">
							<?php dynamic_sidebar('sidebar'); ?>
							<?php wp_meta(); ?>
			
						</ul>
					</div> <!-- end sidebar widget area -->
				<?php endif; ?>
				
				
			</div><!-- end rightBar -->	