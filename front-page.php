<?php 
/*
 * the front page is controlled by custom taxonomies only
 * it is also composed of a left and middle sidebar area
 * which is filled by widgets
 *
 */

get_header(); ?>

<!-- slider  -->

<?php if ( is_sidebar_active('topstory-content') ) : ?>
    <?php dynamic_sidebar('topstory-content'); ?>
<?php endif; ?>

<! -- end slider -->

	
<div class="clear"></div>
		

<!-- content -->
		
<div id="content" class="grid_16">

		<!-- @@@ begin left bar @@@  -->
		<div id="leftBar" class="grid_5">
			
			<?php if ( is_sidebar_active('left-content') ) : ?>
					<?php dynamic_sidebar('left-content'); ?>
			<?php endif; ?>
									
		</div><!-- end left bar -->
							
			<!-- @@@ begin text featured stories @@@ -->
		<div id="featured_text" class="grid_11">
			<ul>
			
			<!-- pulls a list of posts from category 'featured_text' into list items -->
			 <?php $post_num = 1; //var so we can give classes depending on li position
			 	   $vars = array(
			 	   		'featured-location' => 'featured-text',
			 	   		'showposts' => 3
			 	   ); 
			 	   
			 ?>
			 	 
			 <?php query_posts($vars); ?>
			 <?php while ( have_posts() ) : the_post(); ?>		
			
				<li class="<?php if($post_num==3) { echo "last"; } else { echo "normal"; } ?>"><a href="<?php the_permalink(); ?>"><div class="text-hed"><?php the_title(); ?></div></a>
								
					<div class="clear"></div>
				</li>
				
			 <?php $post_num++; ?>
			 <?php endwhile; ?>
			 <?php rewind_posts(); ?>
			 <?php wp_reset_query(); ?>
			 </ul>
		</div>
			
		<!-- middle bar -->
		<div id="middleBar" class="grid_7">
				
				<?php 
				
				//the video element is hardcoded to be at the top of the area as a multiplayer.
				//there is also a video widget that could be used instead.
				
				?>
			
			<?php if ( is_sidebar_active('middle-content') ) : ?>
				<?php dynamic_sidebar('middle-content'); ?>
			<?php endif; ?>
			
						
		</div><!--end middleBar -->
			
<?php get_sidebar(); ?>
<?php get_footer(); ?>
