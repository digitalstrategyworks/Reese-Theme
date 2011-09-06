<?php 
/*
 * categories were originally broken up into individual pages because i could not figure out
 * how to organize all of the queries i wanted. we also originally sorted our featured areas
 * by category. this was found to be ineffective so we switched to custom taxonomies
 * the get_query_var allows you to find the default category that is being queried and i
 * then update it with the featured-location query. therefore, we only get posts
 * from the current category being viewed as well as the top area featured-location of category
 *
 * there are plans to redesign the category page now so that it can reflect subcategories, etc.
 * this ability is now completely functional because of the use of custom taxonomies to organize
 * featured website placement.
 *
 */
get_header(); ?>

<div id="content" class="grid_16">
	
	<div id="categoryContent" class="grid_12">
			
			<?php
				$featured_ids = array();
				
				//find out which category we're on so we can get the featured post
				//this will give us the category ID
				$curr_category = get_query_var('cat');
				$subcats = get_categories( array (
							'child_of' => $curr_category,
							'hide_empty' => 1
							) );
				$vars = array(
						'cat' => $curr_category,
						'featured-location' => 'category',
						'showposts' => 1
					);
					
				//query the featured category post		 
				query_posts($vars); ?>
			
			<?php 
				//wordpress loop
				if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				
			<?php 
				//do not show the post that's featured in the regular feed
				global $post;
				$featured_ids = array();
				array_push($featured_ids, $post->ID); ?>
				
			<div id="featuredContent">
			
				<img src="<?php 
					//get category image
					the_DifferentTypeFacts($post->ID, 'category-image'); ?>" />
				
				<!-- css screen that covers the image and allows for the caption -->
				<div id="featuredScreen"></div>
				
				<!-- caption is a separate div positioned overtop of the screen -->
				<div id="featuredCaption">
					<a href="<?php the_permalink(); ?>">
					<span class="head"><?php if ( check_DifferentTypeFacts($post->ID, 'category-head') ) {
					the_DifferentTypeFacts($post->ID, 'category-head'); } else { the_title(); } ?></span>
					<br />
					<span class="subhead"><?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'subhead'); ?></span>
					
					</a>
					<p class="byline">By <?php coauthors_posts_links(); ?></p>
				</div>
			
			</div>
			
			<?php 
				//end of wordpress loop rewind posts and reset query so the regular content shows up
				endwhile; endif; ?>
			<?php rewind_posts(); ?>
			<?php wp_reset_query(); ?>
				
		<div id="right_category">
		
		<?php 
			echo '<div class="subcats">';
			foreach($subcats as $subcat) {
				echo '<div class="subcat">';
				echo '<h3>'.$subcat->cat_name.'</h3>';
				$args = array(
							'cat' => $subcat->cat_ID,
							'showposts' => 3
						);
				$sub_query = new WP_Query($args);
				
				echo '<ul>';
				while( $sub_query->have_posts() ) : $sub_query->the_post();
				?>
					
					<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
					
				<?php 

				endwhile;
				echo '</ul>';
								
				wp_reset_postdata();
				echo '</div>';
			}
			echo '</div>'	
		?>
		
		
		<?php
			$vars = array(
				'cat' => $curr_category,
				'showposts' => 5,
				'featured-location' => 'category-side'
			);
			
		?>
			
		<?php query_posts($vars); ?>
		
		<?php 
			//start the loop again for regularly queried posts
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		
		global $post; ?>
			
			
			<div class="news element">				
				<div class="topGutter">
					<a href="<?php the_permalink(); ?>"><p class="head"><?php the_title(); ?></p>
					<p class="subhead"><?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'subhead'); ?></p></a>
					<p class="byline">By <?php coauthors_posts_links(); ?></p>
				</div>
				<div class="imageHolder">					
					<a href="<?php the_permalink(); ?>"><?php echo get_the_post_thumbnail($post->ID, array(255, 255)); ?></a>
				</div>
			</div>
		
		
		<?php array_push($featured_ids, $post->ID); ?>
			
		<?php endwhile; endif; ?>
			
			
		</div><!-- end right side -->
		
		<?php rewind_posts(); ?>
		<?php wp_reset_query(); ?>
		
		<div id="left_category">
		
		<?php
			//get category again
			$curr_category = get_query_var('cat');
			
			//enable pagination for query and get 10 posts per page
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
						
			$vars = array(
				'posts_per_page' => 10,
				'paged' => $paged,
				'cat' => $curr_category,
				'post__not_in' => $featured_ids
			);
			
			query_posts($vars); ?>
		
		<?php 
			//start the loop again for regularly queried posts
			if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
			<!-- each story is made up of its own box element -->
			<div class="element">
				
				<div class="topGutter">
					<a href="<?php the_permalink(); ?>"><p class="head"><?php the_title(); ?></p>
					<p class="subhead"><?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'subhead'); ?></p></a>
					<p class="byline">By <?php coauthors_posts_links(); ?></p>
				</div>
				
				<div class="storyDescription">
					<?php 
							//if the post has a thumbnail then output it
						if ( get_the_post_thumbnail($post->ID, 'thumbnail') ) { ?>
						
						<div class="thumbnail">
							<?php echo get_the_post_thumbnail($post->ID, array(100, 100)); ?>
						</div>
						
					<?php } ?>
						
					<?php the_excerpt(); ?>
					
					<!-- extra information about each story as well as icon links that pop up in a lightbox -->
					
					<p class="comments"><a href="<?php comments_link(); ?>"><?php comments_number('no comments', 'one comment', '% comments'); ?></a></p>
					
				</div><!-- end story description and then clear the float-->
			
			<div class="clear"></div>
				
			
			</div><!-- end element -->
				
		<?php endwhile; endif; ?>
		
		<div id="navigation">
			<div id='nav_left'>
				<?php next_posts_link('« Older Entries') ?>
			</div>
			
			<div id='nav_right'>
				<?php previous_posts_link('Newer Entries »') ?>
			</div>
		</div>
		
		</div><!-- end left side -->
		
		<?php rewind_posts(); ?>
		<?php wp_reset_query(); ?>
					
	</div><!-- end categoryContent -->
	
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>