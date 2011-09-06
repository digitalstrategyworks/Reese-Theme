<?php 
/* 
 * The author template is pretty customized right now for the reesenews vimeo videos.
 * Should we decide to stop using vimeo videos, and instead use static images,
 * I would recommend changing the #vimeo div to a div with an img link that
 * you echo out. The user information could stay the same
 *
 */
get_header(); ?>

<div id="content" class="grid_16">
	
	
	<?php 
		//this is the master call to get the users name as well as all of their data into an array which we will parse throughout this template
		
		if(isset($_GET['author_name'])) :
			$curauth = get_user_by('slug', $author_name);
		else:
			$curauth = get_userdata(intval($author));
		endif;
	?>		
						 
	<div id="authorContent" class="grid_12">
		<?php			
			echo '<h2 class="page-head">'.$curauth->display_name.'</h2>'; 
		?>
		
		<?php 
			//right now, the qualification to get the top vimeo and description area is for a user to have a vimeo video
			//if we wanted to change the qualification later we could either remove this statement or update it
			//however, this area is designed specifically to contain a vimeo video so it will have to have updated
			//templates in order to look correct.
			if( $curauth->vimeo ) : ?>
		
		<div id="author-info">
			
			<?php 
			//if the current author has a vimeo video, then place it
			//probably don't need this conditional statement twice
			//but i have left it for the convenience if anyone ever wants to update the qualification
			//to have a author-info area
			if ( $curauth->vimeo ) : ?>
			
			<div id="vimeo">
				
					<?php 
					$vimeo = $curauth->vimeo;
					
					//this code comes from vimeos default embed
					//the only thing that changes in our user videos is the vimeo video number
					echo '<iframe src="http://player.vimeo.com/video/' . $vimeo . '?title=0&byline=0&portrait=0&autoplay=0"'; 
					echo ' width="684" height="385" frameborder="0"></iframe>'; ?>
					
			</div>
			
			<?php endif; ?>
			
			<div class="clear"></div>
			
			<ul class="contact-author">
				<?php
					//get these contact values, which were set in functions.php to be different than the wordpress default
					//they are stored in an array $curauth which we are parsing here
					$email = $curauth->user_email;
					$website = $curauth->user_url;
					$twitter = $curauth->twitter;
					$hometown = $curauth->hometown;
					$major = $curauth->major;
					$class = $curauth->user_class;
					
					// ** below should maybe be updated later. right now this code assumes that each author has a position, major, class and hometown
					// this may cause php errors later
				?>
					<li class="position"><?php echo $curauth->position; ?></li>
					<li class="major"><?php echo $major; ?></li>
					<li class="hometown"><?php echo $class; echo ", "?><?php echo $hometown; ?></li>
					<li class="email"><a href="mailto:<?php echo $email ?>"><?php echo $email; ?></a></li>
					<?php if($website) { ?><li class="website"><a href="<?php echo $website; ?>"><?php echo $website; ?></a></li><?php } ?>
					<?php if($twitter) { ?><li class="twitter"><a href="<?php echo $twitter; ?>">Follow Me on Twitter</a></li><?php } ?>
			</ul>
			
			<p><?php echo $curauth->description; ?></p>
			
		<div class="clear"></div>
		</div>
		
		<?php endif; ?>
	
		<div id="author-posts">
			
			<?php
			//enable pagination for query and get 10 posts per page
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			query_posts("posts_per_page=10&paged=$paged&author_name=$author_name"); ?>
		
		<?php 
			//start the loop again for regularly queried posts
			if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				
		<!-- each story is made up of its own box element -->
		<div class="element">
			
			<div class="topGutter">
				<a href="<?php the_permalink(); ?>"><p class="head"><?php the_title(); ?></p>
				<p class="subhead"><?php if ( function_exists('the_DifferentTypeFacts') ) { the_DifferentTypeFacts($post->ID, 'subhead'); } ?></p></a>
			</div>
			
			<div class="storyDescription">
					
				<?php the_excerpt(); ?>
				
				<!-- extra information about each story as well as icon links that pop up in a lightbox -->
				
				<p class="comments"><a href="<?php comments_link(); ?>"><?php comments_number('no comments', 'one comment', '% comments'); ?></a></p>
				
			</div><!-- end story description and then clear the float-->
			
		
		<div class="clear"></div>
		
		</div><!-- end element -->
				
		<?php endwhile; endif; ?>
		
		<!-- pagination -->
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
		
		</div> <!-- end author posts -->
					
	</div><!-- end pageContent -->
			
<?php get_sidebar(); ?>
<?php get_footer(); ?>