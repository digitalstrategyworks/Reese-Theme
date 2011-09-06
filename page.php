<?php 
/*
 * page templates simply display the content that is written in them
 * the reese post fields do not currently show up on the page backend
 * so there is no need to display anything other than what the user has placed
 * into the page
 *
 */
get_header(); ?>

<div id="content" class="grid_16">
	
	<div id="pageContent" class="grid_12">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			<!-- headline content and main image here -->
			<h2 class="page-head"><?php the_title(); ?></h2>
							
				<div class="entry-content">
				
					<?php the_content(); ?>
					
				</div><!-- end entry -->
								
			
		</div><!-- end post -->
		
	
	<?php endwhile; //loop end ?>
	<?php endif; ?>
	
	</div> <!-- end pageContent -->
			
<?php get_sidebar(); ?>
<?php get_footer(); ?>