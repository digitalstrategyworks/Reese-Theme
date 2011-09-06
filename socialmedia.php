<? /*
Template Name: social media
*/
 get_header(); ?>

<div id="content" class="grid_16">
	
	<div id="pageContent" class="grid_12">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			<!-- headline content and main image here -->
			<h2 class="page-head"><?php the_title(); ?></h2>
			<h3 class="page-subhead"><?php echo get_post_meta($post->ID, 'subhead', true); ?></h3>
			
			<?php if ( get_post_meta ($post->ID, 'story image permalink', true ) ) { ?>
			
			<div id="imageBlock"><img src="<?php echo get_post_meta($post->ID, 'story image permalink', true); ?>" />
				<div id="screen"></div>
				<div id="image-caption"><p><?php echo get_post_meta($post->ID, 'image caption', true); ?></p></div>
			</div>
			
			<?php } ?>
				
				<div class="entry-content">
				
<div>
<div class="socialmediapagebox" style="width:250px; display:inline; float:left">

<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'list',
  rpp: 30,
  interval: 6000,
  title: 'reesenews',
  subject: 'from the newsroom',
  width: 250,
  height: 300,
  theme: {
    shell: {
      background: '#759fc7',
      color: '#ffffff'
    },
    tweets: {
      background: '#ffffff',
      color: '#444444',
      links: '#759fc7'
    }
  },
  features: {
    scrollbar: true,
    loop: false,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: true,
    behavior: 'all'
  }
}).render().setList('reesenews', 'newsroom').start();
</script>

</div>

<!-- widget 1 end -->


<!-- twitter widget 2 new journalism start -->

<div class="socialmediapagebox" style="width:250px; display:inline; float:left position:relative; margin-left:20px">

<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'list',
  rpp: 30,
  interval: 6000,
  title: '',
  subject: 'new journalism',
  width: 250,
  height: 300,
  theme: {
    shell: {
      background: '#759fc7',
      color: '#ffffff'
    },
    tweets: {
      background: '#ffffff',
      color: '#444444',
      links: '#759fc7'
    }
  },
  features: {
    scrollbar: true,
    loop: false,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: true,
    behavior: 'all'
  }
}).render().setList('reesenews', 'new-journalism').start();
</script>

</div>
</div>

<!-- widget 2 end -->

<!-- widget 3 unc centers and departments start -->
<div style="clear:both">&nbsp;</div>
<div>
<div style="width:250px; display:inline; float:left position:relative;" class="socialmediapagebox">

<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'list',
  rpp: 30,
  interval: 6000,
  title: 'university of north carolina',
  subject: 'centers and departments',
  width: 250,
  height: 300,
  theme: {
    shell: {
      background: '#759fc7',
      color: '#ffffff'
    },
    tweets: {
      background: '#ffffff',
      color: '#444444',
      links: '#759fc7'
    }
  },
  features: {
    scrollbar: true,
    loop: false,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: true,
    behavior: 'all'
  }
}).render().setList('reesenews', 'unc-centers-departments').start();
</script>


</div>

<!-- widget 3 end -->








<div  class="socialmediapagebox" style="width:250px; display:inline; float:left position:relative; margin-left:20px">


<iframe style="border: medium none; overflow: hidden; width: 250px; height: 587px;" frameborder="0" scrolling="no" src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Freesenews&amp;width=250&amp;colorscheme=light&amp;connections=15&amp;stream=true&amp;header=true&amp;height=587"></iframe>

</div>
</div>

					
				</div><!-- end entry -->
								
			
		</div><!-- end post -->
		
	
	<?php endwhile; //loop end ?>
	<?php endif; ?>
	
	</div> <!-- end pageContent -->
			
<?php get_sidebar(); ?>
<?php get_footer(); ?>