<?php get_header(); ?>

<!-- ******************** begin scroller ******************** -->
<div id="featured-noslide">

	<?php $imageCounter = 1; ?>
	<?php query_posts('showposts=1&category_name=wire-category-slider'); ?>
	<?php while ( have_posts() ) : the_post(); ?>
	
	<a href="<?php the_permalink(); ?>"><img src="<?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'category-image'); ?>" rel="caption-<?php echo $imageCounter; ?>" /></a>	
	
	<?php $imageCounter+=1; ?>
	
	<?php endwhile; ?>
	<?php rewind_posts(); ?>
	<?php wp_reset_query(); ?>
	
	<?php $imageCounter = 1; ?>
	<?php query_posts('showposts=1&category_name=wire-category-slider'); ?>
	<?php while ( have_posts() ) : the_post(); ?>	
	
	<span id="credit-<?php echo $imageCounter; ?>" class="photo-credit"><?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'photo-credit'); ?></span>
		
	<?php endwhile; ?>
	<?php rewind_posts(); ?>
	<?php wp_reset_query(); ?>
	
	<?php $imageCounter = 1; ?>
	<?php query_posts('showposts=1&category_name=wire-category-slider'); ?>
	<?php while ( have_posts() ) : the_post(); ?>
	
	<span class="orbit-caption" id="caption-<?php echo $imageCounter; ?>"><a href="<?php the_permalink(); ?>"><p class="head"><?php the_title(); ?></p><p class="subhead"><?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'subhead'); ?></p><span class="arrows">&gt;&gt;&gt;</span><span class="featured-more">FULL STORY</span></a></span>
	
	<?php $imageCounter++; ?>
	<?php endwhile; ?>
	<?php rewind_posts(); ?>
	<?php wp_reset_query(); ?>
	
	<div id="slider-blue"></div>
</div> 
			
<div class="clear"></div>
		
<!-- ******************** begin content ******************** -->
		
<div id="content" class="grid_16">

		<!-- @@@ begin left bar @@@  -->
		<div id="leftBar" class="grid_5">
			
			<?php query_posts('showposts=6&category_name=vote-left'); ?>
			<?php while ( have_posts() ) : the_post(); ?>
			<?php global $post; ?>
				
			<div class="news element">
								
				<div class="topGutter">
					<a href="<?php the_permalink(); ?>"><p class="head"><?php the_title(); ?></p>
					<p class="subhead"><?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'subhead'); ?></p></a>
				</div>
				<div class="imageHolder">					
					<div class="boxgrid captionfull">
						<a href="<?php the_permalink(); ?>"><img src="<?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'category-image'); ?>" /></a>
						<div class="cover boxcaption">
							<?php the_excerpt(); ?>
						</div>		
					</div>
					
				</div>
				
				
			</div>
			
			<?php endwhile; ?>
				<?php rewind_posts(); ?>
				<?php wp_reset_query(); ?>
									
		</div><!-- end left bar -->
							
			<!-- @@@ begin text featured stories @@@ -->
		<div id="featured_text" class="grid_11">
			<ul>
			
			<!-- pulls a list of posts from category 'featured_text' into list items -->
			 <?php
			 global $post;
			 $tmp_post = $post;
			 $post_num = 0; //var so we can give classes depending on li position
			 $myposts = get_posts('numberposts=5&offset=0&category_name=vote-featured-text');
			 foreach($myposts as $post) :
			   setup_postdata($post);
			   $post_num++;
			 ?>
				<li class="<?php if($post_num==3) { echo "last"; } else { echo "normal"; } ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
			 <?php endforeach; ?>
			 <?php $post = $tmp_post; ?>
			 
			 </ul>
		</div>
			
			
		
		<!-- @@@ begin middle bar @@@ -->
		<div id="middleBar" class="grid_7">
			
			<?php query_posts('showposts=4&category_name=vote-middle'); ?>
			<?php while ( have_posts() ) : the_post(); ?>
			<?php global $post; ?>
				
			<div class="news element">
								
				<div class="topGutter">
					<a href="<?php the_permalink(); ?>"><p class="head"><?php the_title(); ?></p>
					<p class="subhead"><?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'subhead'); ?></p></a>
				</div>
				<div class="imageHolder">					
					<div class="boxgrid captionfull">
						<a href="<?php the_permalink(); ?>"><img src="<?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'category-image'); ?>" /></a>
						<div class="cover boxcaption">
							<?php the_excerpt(); ?>
						</div>		
					</div>
					
				</div>
				
				
			</div>
		<?php endwhile; ?>
		<?php rewind_posts(); ?>
		<?php wp_reset_query(); ?>
									
		</div><!--end middleBar -->
			
<?php get_sidebar(); ?>
<?php get_footer(); ?>