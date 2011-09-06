<? 
/*
 * Template Name: football timeline
*/
 get_header(); ?>

<div id="content" class="grid_16">
	
	<div id="pageContent" style="width: 940px">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			<!-- headline content and main image here -->
			<h2 class="page-head"><?php the_title(); ?></h2>
			<h3 class="page-subhead"><?php the_content(); ?></h3>
				<div class="entry-content">
				
					<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="940" height="1360" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"><param name="data" value="http://reesenews.org/files/2010/11/11.17.10_football_AAB.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF" /><param name="loop" value="false" /><param name="wmode" value="transparent" /><param name="wmode" value="transparent" /><param name="src" value="http://reesenews.org/files/2010/11/11.17.10_football_AAB.swf" /><embed type="application/x-shockwave-flash" width="940" height="1360" src="http://reesenews.org/files/2010/11/11.17.10_football_AAB.swf" wmode="transparent" loop="false" bgcolor="#FFFFFF" quality="high" data="http://reesenews.org/files/2010/11/11.17.10_football_AAB.swf"></embed></object>
					
				</div><!-- end entry -->
								
			
		</div><!-- end post -->
		
	
	<?php endwhile; //loop end ?>
	<?php endif; ?>
	
	</div> <!-- end pageContent -->
			
<?php get_footer(); ?>