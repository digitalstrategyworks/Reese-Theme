<?php
/*
 *
 * The header has now been optimized to work with only three stylesheets and one IE stylesheet
 * there is now also a master scripts file which has been minified
 * we are also allowing google to host jquery for us
 * so that our server gets fewer requests and is optimized
 *
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	
	<!-- Base Stylesheets -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/style/960.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/style/reset.css" type="text/css" media="screen" />
	<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/favicon.ico" />
	
	
	<!-- IE Fixes -->
	<!--[if IE 7]> <link href="<?php bloginfo('template_directory'); ?>/style/ie.css" rel="stylesheet" type="text/css"> <![endif]-->
	
	<!-- Antenna -->
	<link href="http://cloud.webtype.com/css/c627f6fc-a07a-4bd6-8fa5-e26d70bbecb1.css" rel="stylesheet" type="text/css" />
	
	<!-- jQuery & SoundManager II & Master Scripts -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/scripts/master.js"></script>
				
	<?php //need to do one loop to get a query so that each of these meta data values can be filled
		if (have_posts()):while(have_posts()):the_post();endwhile;endif; ?>
	
	<!-- Social Networking & Google Meta Data -->	
	<meta name="google-site-verification" content="Kw9LwWk0_aLRFpSMklHTfILWk5zOA8ZLY_xtkd2KOI0" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?php echo get_permalink($post->ID); ?>" />
	<meta property="og:site_name" content="reesenews" />
	<meta property="fb:admins" content="tonyzeoli, noel.cody, 1396140343 " />
	<meta property="og:image" >
	<meta property="fb:page_id" content="157282820949364" />

	<?php if (is_single()) { ?>
	    <meta property="og:title" content="<?php single_post_title(''); ?>" />
	    <meta property="og:description" content="<?php echo strip_tags(get_the_excerpt($post->ID)); ?>" />
	    <meta property="og:type" content="article" />
	    <meta property="og:image" content="<?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'home-featured-permalink'); ?>" />
	    <meta property="og:image" content="<?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'story-photo'); ?>" />
	    <meta property="og:image" content="<?php echo wp_get_attachment_thumb_url( get_post_thumbnail_id( $post->ID ) ) ?>" />
	<?php } ?>
	
	<!-- Scrolling Share Box on Single Pages -->
	<?php if ( is_single() ) : ?>
		<script type="text/javascript">
			$(function() {
			 var offset = $("#box").offset();
			 var topPadding = 50;
			 $(window).scroll(function() {
				 if ($(window).scrollTop() > offset.top) {
					 $("#box").stop().animate({
						 marginTop: $(window).scrollTop() - offset.top + topPadding
					 });
				 } else {
					 $("#box").stop().animate({
						 marginTop: 0
					 });
				 };
			 });
			});
		</script>
	<?php endif; ?>
		
	<title>reesenews</title>
	
	<?php wp_head(); ?>
	
</head>

<body>


<!-- two wrappers here because we have the floating share box on the left -->
<div id="wrapper" class="container_16">
	<div id="topics">
		
		<?php wp_nav_menu(array('menu' => 'top_menu')); ?>
	
	</div>

	<div id="page-wrap">
		
		<div id="IEFix" style="visibility:hidden">&nbsp;</div>
		
		<!-- header -->
		<div id="header" class="grid_16">	
							
			<div id="headerLogo" class="grid_9">
				
				<a href="<?php bloginfo('url'); ?>">
					<div class="reese"></div>
						<div class="category-label"><?php
					
						$header = get_category_header();
						
						?>
						
							<img src="<?php bloginfo("template_directory"); echo '/images/'; echo $header; echo '.png'; ?>" />
						</div>
				</a>
			</div>
			
			<div id="beta"><p>Your link to Chapel Hill and the University of North Carolina</p></div>
						
			<div id="search" class="grid_4">
				<?php get_search_form(); ?>
				
				<!-- Date & Time JavaScript -->	
				<div id="date_time" class="grid_3">
					<div id="date"><script type="text/javascript">
						var d = new Date();
						var curr_date = d.getDate();
						var curr_month = d.getMonth();
						var curr_year = d.getFullYear();
						
						var month_name=new Array(12);
						month_name[0]="Jan";
						month_name[1]="Feb";
						month_name[2]="Mar";
						month_name[3]="Apr";
						month_name[4]="May";
						month_name[5]="June";
						month_name[6]="July";
						month_name[7]="Aug";
						month_name[8]="Sept";
						month_name[9]="Oct";
						month_name[10]="Nov";
						month_name[11]="Dec";
						
						document.write(month_name[curr_month] + " " + curr_date + " <br />" + curr_year);
					
						</script>
					</div>
					
					<div id="time"><span class="blue" id="clock"><script type="text/javascript">
						
						function start_clock() {
							var curr_time = new Date();
							var hours = curr_time.getHours();
							var minutes = curr_time.getMinutes();
							
							if (minutes < 10){
								minutes = "0" + minutes;
							}
											
							if (hours > 12 && hours < 24) {
								hours = hours - 12;
								display = hours + "." + minutes + "p";
							} else if (hours == 12) {
								display = hours + "." + minutes + "p";
							} else if (hours == 0) {
								hours = 12;
								display = hours + "." + minutes + "a";
							} else if (hours < 12 && hours > 0) {
								display = hours + "." + minutes + "a";
							} else {
								//impossible
							}
							
							document.getElementById('clock').innerHTML = display;
							
							setTimeout("start_clock()", 60000);
						}
						
						start_clock();
					
						</script></span>
					</div>
					
				</div>
				
				<div id="headerResources">
					<div id="forecastButton">
						<img src="<?php bloginfo('template_directory'); ?>/images/5daybutton.jpg" />
					</div>
					<div id="forecastOverlay"></div>
					<?php include('weather.php'); ?>	
				</div>
			</div>
						
			<!--end below_header -->
			
			<!-- embedded this script so that the page load wasn't ugly -->
			<script>
				$("div#weatherDrawer").hide();
					
				$("div#headerResources").click(
				  function () {
					$("div#weatherDrawer").stop(true, true).slideToggle();
				  }
				);
				
				$("div#forecastOverlay").css('opacity', 0.05);
					
				$("div#headerResources").hover( function() {
					$("div#forecastOverlay").css('opacity', 0);
				}, function() {
					$("div#forecastOverlay").css('opacity', 0.05);	
					
				});
				
			</script>
				
				
		</div><!-- end header --><div class="clear"></div>
