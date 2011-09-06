<?php 
/*
 * 404 is the server response to not being able to find a query someone is making
 * this will often happen to when we have moved a page, etc
 *
 */

get_header(); ?>

<div id="content" class="grid_16">

	<div id="fouroh" class="grid_16">
	<?php $admin_email = get_option('admin_email'); ?>
	
	<a href="<?php bloginfo('url'); ?>"><p class="huge">&#58;&#40;</p><p>404'd it. We're sad you can't find what you're looking for too.</a> <a href="mailto:<?php echo $admin_email; ?>">Tell us about it.</a></p>
	
	</div>

</div>

<?php get_footer(); ?>