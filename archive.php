<?php
/* This template is almost identical to the category.php template
 * the main difference is that there is no featured image area
 * the only time I could see people running into this page is if they
 * are trying to navigate by tag, term, search, etc
*/

get_header(); ?>

<div id="content" class="grid_16">
	
	<div id="categoryContent" class="grid_12">
			
		<?php
			//enable pagination for query and get 10 posts per page
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			query_posts("posts_per_page=10&paged=$paged&category_name=biz"); ?>
		
		<?php 
			//start the loop again for regularly queried posts
			if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				
		<!-- each story is made up of its own box element -->
		<div class="element">
			
		<?php 
				//if the post has a thumbnail then output it
			if ( get_the_post_thumbnail($post->ID, 'thumbnail') ) { ?>
			
			<div class="thumbnail">
				<?php echo get_the_post_thumbnail($post->ID, 'thumbnail'); ?>
			</div>
			
		<?php } ?>
			
			<div class="topGutter">
				<a href="<?php the_permalink(); ?>"><p class="head"><?php the_title(); ?></p>
				<p class="subhead"><?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'subhead'); ?></p></a>
			</div>
			
			
			
			<div class="storyDescription">
					
				<?php the_excerpt(); ?>
				
				<!-- extra information about each story as well as icon links that pop up in a lightbox -->
				
				<p class="comments"><a href="<?php comments_link(); ?>"><?php comments_number('no comments', 'one comment', '% comments'); ?></a></p>
				
				<?php 
				//first we do an overarching to check if there is a photo, video, or audio in the story
				//otherwise we will skip these icons all together
				if ( check_DifferentTypeFacts($post->ID, 'story-photo') || check_DifferentTypeFacts($post->ID, 'story-video') || check_DifferentTypeFacts($post->ID, 'sidebar-audio') ) { ?>
								
				<div class="media">
				<!-- unordered list of the icons which when clicked will pop up a lightbox of media -->
				<ul>	
					
					<?php 
					//PHOTOS
					//if contains a story photo then add the list item to the unordered list
					if ( check_DifferentTypeFacts($post->ID, 'story-photo') ) : ?>
						<li><a href="<?php the_DifferentTypeFacts($post->ID, 'story-photo'); ?>" rel="lightbox" title="<?php the_DifferentTypeFacts($post->ID, 'image-caption'); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icon-photos.png" /></a></li>
					<?php endif; ?>
					
					<?php 
					//VIDEO
					//if contains a video then put a video icon and let brightcove player pop up on click
					if ( check_DifferentTypeFacts($post->ID, 'story-video') ) : ?>
						<li><a href="#video-<?php echo $post->ID; ?>" rel="lightbox" title="<?php the_title(); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icon-video.png" /></a></li>
						
						<!-- this video is hidden via CSS until the user clicks to open and then it plays in a lightbox -->
						<div id="video-<?php echo $post->ID; ?>" class="icon-video hide">
							<?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'story-video'); ?>
						</div>
					<?php endif; ?>
					
					
					<?php 
					//AUDIO
					//if the story has audio in the sidebar then we will place an
					//icon that allows audio to pop up in a lightbox
					if ( check_DifferentTypeFacts($post->ID, 'sidebar-audio') ) : ?>
					
					<li><a href="#audio-<?php echo $post->ID; ?>" rel="lightbox"><img src="<?php bloginfo('template_directory'); ?>/images/icon-audio.png" /></a></li>
					
							<?php 
								//get the .mp3 link for the audio
								$audio_meta = check_DifferentTypeFacts($post->ID, 'sidebar-audio'); ?>
					
							<div id="audio-<?php 
								//important to provide this audio distinction in case there 
								//are multiple posts with audio. this stops the conflict.
								echo $post->ID; ?>" class="hide">
								
								<ul class="playlist">
									<li><a href="<?php echo $audio_meta; ?>"><?php the_DifferentTypeFacts($post->ID, 'sidebar-audio-title'); ?></a></li>
								</ul>
								
								<div id="control-template"><!-- soundmanager 2 uses these controls -->
								  	<div class="controls"> 
								   		<div class="statusbar"> 
											<div class="loading"></div> 
											<div class="position"></div> 
								   		</div> 
								  	</div> 
									<div class="timing"> 
										<div id="sm2_timing" class="timing-data"> 
										<span class="sm2_position">%s1</span> / <span class="sm2_total">%s2</span></div> 
								  	</div> 
								 	<div class="peak"> 
								   		<div class="peak-box"><span class="l"></span><span class="r"></span>
								   		</div> 
								  	</div> 
								 </div> 
								 
								 <div id="spectrum-container" class="spectrum-container"> 
								 	<div class="spectrum-box"> 
								   		<div class="spectrum"></div> 
								 	</div> 
								 </div>
							</div><!-- end audio -->
								
					<?php endif; ?>
				</ul></div><!-- end unordered media icon list and media div -->
				<?php } ?>
			</div><!-- end story description and then clear the float-->
			
			
		
		<div class="clear"></div>
			
		
		</div><!-- end element -->
		
		<?php 
			//end of conditional to stop from displaying featured post id in this feed
			} ?>
				
		<?php endwhile; endif; ?>
		
		<div id="navigation">
			<div id='nav_left'>
				<?php next_posts_link('« Older Entries') ?>
			</div>
			
			<div id='nav_right'>
				<?php previous_posts_link('Newer Entries »') ?>
			</div>
		</div>
		
		<?php rewind_posts(); ?>
		<?php wp_reset_query(); ?>
					
	</div><!-- end categoryContent -->
	
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>