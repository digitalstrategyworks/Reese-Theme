<?php 
/*
 *
 * Single pages only require one loop
 * most of the content is pulled from the
 * plugin reese_posts which allows users to fill in fields
 * for featured images, subheads, etc.
 *
 */

get_header(); ?>

<div id="content" class="grid_16">

	<div id="singleContent" class="grid_12">
		
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
			global $post;
		
		?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			<!-- headline content and main image here -->
			<h2 class="page-head"><?php 
				//the headline
				the_title(); ?></h2>
				
			<h3 class="page-subhead"><?php 
				//the subhead
				if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'subhead'); ?></h3>
			
			<?php 
				//creates a ul
				//gets three list items which have been styled as descriptors for the story
				if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'listdata'); ?> 
			
			<?php 
				//checks for video and if there is one outputs it in a div
				if ( check_DifferentTypeFacts($post->ID, 'story-video') ) : ?>
				
				<div id="videoBlock">
					<?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'story-video'); ?>
					
				</div>
			<?php endif; ?>
			
			<?php 
				//checks if there is a story photo and if there is it outputs it
				if ( check_DifferentTypeFacts($post->ID, 'story-photo') ) : ?>
				
				<div id="imageBlock">
					<img src="<?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'story-photo'); ?>" />
					
					<!-- semi-transparent screen -->
					<div id="screen"></div>
					
					<div id="image-caption"><p><?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'image-caption'); ?></p></div>
					
					<?php if( check_DifferentTypeFacts($post->ID, 'photo-credit') ) : ?><div class="photo-credit"><span>Photo by <?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'photo-credit'); ?></span></div> <?php endif; ?>
					
				</div>
			<?php endif; ?>
			
			<?php 
				//using the coauthors plugin, we pull multiple images as well as titles, if needed
				$coauthors = get_coauthors(); ?>
				
			<div id="author-meta">
				<div id="byline_info">
					<div id="byline_img">
						<ul class="abab">
							<?php 
								//loop to output all photos we have retrieved
								for ( $i=0; $i < count($coauthors); $i+=1 ) { ?>
							<?php if( $coauthors[$i]->photo ) : ?>
								<li class="byline_img"><img src="<?php echo $coauthors[$i]->photo; ?>" /></li>
							<?php endif; ?>
							<?php } ?>
						</ul>
					</div>
						<ul>
							<li class="author"><span class="lower">By </span><?php coauthors_posts_links(); ?></li>
							<li class="job-desc"><?php 
							
							//if there is more than one author, put that they are both multimedia journalists
							if( count($coauthors) > 1 ) {
								echo "Multimedia Journalists";
							} else {
								echo $coauthors[0]->position;
							}		
							?></li>
						</ul>
						
					<!-- Date of story -->
					<div id="byline_date"><?php the_date('M. d'); echo " "; the_time('g:i a'); ?></div>
								
					
				</div><!--end byline -->
				
				<!-- emailing, printing, etc -->
				<div id="byline_icons">
					<ul>
					
					<?php $link = get_permalink($post->ID);
							  $mail = $link . "emailpopup/"
						?>
						
						<li><a href="<?php comments_link(); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icons/icon-comments.png" /></a></li>
						<?php if ( !in_category('wire') ) { ?>
						<!-- <li><img src="<?php bloginfo('template_directory'); ?>/images/icons/icon-cc.png" /></li> -->
						<?php } ?>
						<li><a href="<?php echo $mail; ?>" onclick="email_popup(this.href); return false;" title="E-mail" rel="nofollow"><img src="<?php bloginfo('template_directory'); ?>/images/icons/icon-mail.png" /></a></li>
						<li><img src="<?php bloginfo('template_directory'); ?>/images/icons/icon-print.png" /></li>
					</ul>
				</div>
				
				<!-- republishing feature was not added yet due to time constraint -->				
				<div id="byline_options">
					<ul>
						<li><a href="<?php comments_link(); ?>"><?php comments_number('no comments', 'one comment', '% comments'); ?></a></li>
						<?php if( !in_category('wire') ) { ?>
							<!-- <li id="copy">Republish</li> -->
						<?php } ?>
						<li><?php if(function_exists('wp_email')) { email_link(); } ?></li>
						<li><a href="javascript:window.print()">Print</a></li>
						
					</ul>
				</div>
				
			</div><!--end author-meta -->
			
			<div class="clear"></div>
			
			<!-- text sizing, which works off of javascript in master.js -->
			<div id="leftContent" class="grid_7">				
								
				<div id="rightContent">
					<!-- Facebook like -->
					<div id="fb-like"><p>Do you like this story?</p><iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;layout=standard&amp;show_faces=false&amp;width=210&amp;action=like&amp;font=verdana&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:210px; height:80px;" allowTransparency="true"></iframe></div>
					
					<?php 
					
					/*
					 *Below is a section of sidebar elements. For each element we are first going to check if it exists.
					 *If not, we will move by and output nothing. Otherwise, we will print out the media element
					 *
					 */
					
					?>
						
					<!-- custom html -->
					<?php if ( check_DifferentTypeFacts($post->ID, 'sidebar-custom') ) : ?>
					<div class="storyElement custom">	
						<h3><?php the_DifferentTypeFacts($post->ID, 'sidebar-custom-title'); ?></h3>
							<?php the_DifferentTypeFacts($post->ID, 'sidebar-custom'); ?>
					</div>
					<?php endif; ?>
					
					<?php if ( check_DifferentTypeFacts($post->ID, 'sources') ) : ?>
					<div class="storyElement sources">	
						<h3>Sources</h3>
						<?php the_DifferentTypeFacts($post->ID, 'sources'); ?>
					</div>
					<?php endif; ?>
					
					<!-- related -->
					<?php if( get_post_meta($post->ID, 'related', false) ) { ?>
							
							<div class="storyElement related">
							<h3>Related Stories</h3>
							
							<?php 
							
							$related_ids = get_post_meta($post->ID, 'related', false);
							
							echo "<ul>";
							
							foreach($related_ids as $related) {
								echo ('<li><a href="' . get_permalink($related) . '">' . get_the_title($related) . '</a></li>');
							}
							
							echo "</ul>";
							
							?>
							
							</div>
					<?php	
						}
					?>
						
				</div><!-- end right content -->
				
				<div id="entry-content">
				
					<?php the_content(); ?>
				
				</div><!-- end entry -->	
				
				<div id="comments-area">
					<?php comments_template(); ?>
				</div>
								
			</div><!-- end leftContent -->
			
		</div><!-- end post -->
		
	<?php endwhile; //loop end ?>
	<?php endif; ?>

	
	</div><!-- end singleContent -->
	
	
<?php get_sidebar(); ?>
<?php get_footer(); ?>