<?php
    
/*

Plugin Name: Reese Post Fields
Plugin URI: http://reesenews.org/
Description: Add fields required for ReeseNews theme, a modified plugin
Author: Seth Wright
Version: 0.1
License: GPL
Author URI: http://www.sethawright.com/
Last change: 10.06.2011
*/

//check featured status
function check_featured_status($id, $featured) {
	
	$is_featured = false;
	
	for($i = 0; $i<count($featured); $i++) {
		//do not display the featured post down here
		if ($id == $featured[$i] ) {
			$is_featured = true;
		}
	}
	
	return $is_featured;
			
}

function check_num_featured($prev, $featured) {
	$num_featured = $prev;
	
	echo $prev;
	 
	for($i = 0; $i<count($featured); $i++) {
		//do not display the featured post down here
		if ( $post->ID == $featured[$i] ) {
			$num_featured++;
		}
	}
	
	echo $num_featured;
	
	return $num_featured;
			
}

//avoid file direct calls
if ( !function_exists('add_action') ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

if ( function_exists('add_action') ) {
	//wordpress define
	if ( !defined('WP_CONTENT_URL') )
        define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
    if ( !defined('WP_CONTENT_DIR') )
        define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
    if ( !defined('WP_PLUGIN_URL') )
        define('WP_PLUGIN_URL', WP_CONTENT_URL.'/themes/reesenews');
    if ( !defined('WP_PLUGIN_DIR') )
        define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/themes/reesenews');
    if ( !defined('PLUGINDIR') )
        define( 'PLUGINDIR', 'wp-content/themes/reesenews' ); // Relative to ABSPATH.  For back compat.
    if ( !defined('WP_LANG_DIR') )
        define('WP_LANG_DIR', WP_CONTENT_DIR . '/languages');

    // plugin definitions
    define( 'FB_DT_BASENAME', plugin_basename(__FILE__) );
    define( 'FB_DT_BASEDIR', dirname( plugin_basename(__FILE__) ) );
    define( 'FB_DT_TEXTDOMAIN', 'different-types' );
    
}

if ( !class_exists( 'DifferentType' ) ) {
    class DifferentType {

        // constructor
        function DifferentType() {

            if (is_admin() ) {
                add_action( 'admin_init', array(&$this, 'on_admin_init') );
                add_action( 'wp_insert_post', array(&$this, 'on_wp_insert_post'), 10, 2 );
                add_action( 'init', array(&$this, 'textdomain') );
                register_uninstall_hook( __FILE__, array(&$this, 'uninstall') );
                add_action( "admin_print_scripts-post.php", array($this, 'enqueue_script') );
                add_action( "admin_print_scripts-post-new.php", array($this, 'enqueue_script') );
                add_action( "admin_print_scripts-page.php", array($this, 'enqueue_script') );
                add_action( "admin_print_scripts-page-new.php", array($this, 'enqueue_script') );
            }
        }

        // active for multilanguage
        function textdomain() {

            if ( function_exists('load_plugin_textdomain') )
                load_plugin_textdomain( FB_DT_TEXTDOMAIN, false, dirname( FB_DT_BASENAME ) . '/languages' );
        }

        // unsintall all postmetadata
        function uninstall() {

            $all_posts = get_posts('numberposts=0&post_type=post&post_status=');

            foreach( $all_posts as $postinfo) {

                delete_post_meta($postinfo->ID, '_different-types');

            }
        }

        // add script
        function enqueue_script() {
            wp_enqueue_script( 'tinymce4dt', WP_PLUGIN_URL . '/' . FB_DT_BASEDIR . '/js/script.js', array('jquery') );
        }

        // admin init
        function on_admin_init() {

            if ( !current_user_can( 'publish_posts' ) )
                return;

            add_meta_box( 'different_types',
                                    __( 'ReeseNews Post Fields', FB_DT_TEXTDOMAIN ),
                                    array( &$this, 'meta_box' ),
                                    'post', 'normal', 'high'
                                    );
            
        }

        // check for preview
        function is_page_preview() {
            $id = (int)$_GET['preview_id'];
            if ($id == 0) $id = (int)$_GET['post_id'];
            $preview = $_GET['preview'];
            if ($id > 0 && $preview == 'true') {
                global $wpdb;
                $type = $wpdb->get_results("SELECT post_type FROM $wpdb->posts WHERE ID=$id");
                if ( count($type) && ($type[0]->post_type == 'page') && current_user_can('edit_page') )
                    return true;
            }
            return false;
        }

        // after save post, save meta data for plugin
        function on_wp_insert_post($id) {
            global $id;

            if ( !isset($id) )
                $id = (int)$_REQUEST['post_ID'];
            if ( $this->is_page_preview() && !isset($id) )
                $id = (int)$_GET['preview_id'];

            if ( !current_user_can('edit_post') )
                return;

            if ( isset($_POST['dt-heading']) && $_POST['dt-heading'] != '' )
                $this->data['home-featured-permalink'] = esc_attr( $_POST['dt-heading'] );
            if ( isset($_POST['dt-category-image']) && $_POST['dt-category-image'] != '' )
                $this->data['category-image'] = esc_attr( $_POST['dt-category-image'] );
            if ( isset($_POST['dt-additional-info']) && $_POST['dt-additional-info'] != '' )
                $this->data['subhead'] = $_POST['dt-additional-info'];
            if ( isset($_POST['dt-listdata']) && $_POST['dt-listdata'] != '' )
                $this->data['listdata'] = esc_attr( $_POST['dt-listdata'] );
            if ( isset($_POST['dt-story-photo']) && $_POST['dt-story-photo'] != '' )
                $this->data['story-photo'] = esc_attr( $_POST['dt-story-photo'] );
            if ( isset($_POST['dt-image-caption']) && $_POST['dt-image-caption'] != '' )
                $this->data['image-caption'] = esc_attr( $_POST['dt-image-caption'] );
            if ( isset($_POST['dt-story-video']) && $_POST['dt-story-video'] != '' )
                $this->data['story-video'] = $_POST['dt-story-video'];
                
            if ( isset($_POST['dt-category-head']) && $_POST['dt-category-head'] != '' )
                $this->data['category-head'] = $_POST['dt-category-head'];
            if ( isset($_POST['dt-slider-head']) && $_POST['dt-slider-head'] != '' )
                $this->data['slider-head'] = $_POST['dt-slider-head'];
            
            if ( isset($_POST['dt-sidebar-custom']) && $_POST['dt-sidebar-custom'] != '' )
                $this->data['sidebar-custom'] = $_POST['dt-sidebar-custom'];
            
            if ( isset($_POST['dt-sources']) && $_POST['dt-sources'] != '' )
                $this->data['sources'] = $_POST['dt-sources'];
            
            if ( isset($_POST['dt-photo-credit']) && $_POST['dt-photo-credit'] != '' )
                $this->data['photo-credit'] = $_POST['dt-photo-credit'];
            
            
            if ( isset($this->data) && $this->data != '' )
                update_post_meta($id, '_different-types', $this->data);
        }

        // load post_meta_data
        function load_post_meta($id) {

            return get_post_meta($id, '_different-types', true);
        }

        // meta box on post/page
        function meta_box($data) {

            $value = $this->load_post_meta($data->ID);
            ?>
            <table id="dt-page-definition" width="100%" cellspacing="5px">
            	
            	<tr valign="top">
                	<td><h4>Headlines</h4></td>
                </tr>
                
                <tr valign="top">
                    <td><label for="dt-additional-info"><?php _e( 'Subhead:', FB_DT_TEXTDOMAIN ); ?></label></td>
                    <td><input type="text" id="dt-additional-info" name="dt-additional-info" class="additional-info form-input-tip code" size="16" autocomplete="off" maxlength="79" style="width:99.5%" value="<?php echo $value['subhead']; ?>" />
                    </td>
                </tr>
                
                <tr valign="top">
                    <td><label for="dt-slider-head"><?php _e( 'Slider:', FB_DT_TEXTDOMAIN ); ?></label></td>
                    <td><input type="text" id="dt-slider-head" name="dt-slider-head" class="heading form-input-tip" size="16" maxlength="34" autocomplete="off" value="<?php echo $value['slider-head']; ?>" tabindex="6" style="width:99.5%" />
                    </td>
                </tr>
                
                <tr valign="top">
                    <td><label for="dt-category-head"><?php _e( 'Category:', FB_DT_TEXTDOMAIN ); ?></label></td>
                    <td><input type="text" id="dt-category-head" name="dt-category-head" class="heading form-input-tip" size="16" maxlength="36" autocomplete="off" value="<?php echo $value['category-head']; ?>" tabindex="6" style="width:99.5%"/>
                    </td>
                </tr>
                
                <tr valign="top">
                	<td><h4>Featured Location Setup</h4></td>
                </tr>
                
                <tr valign="top">
                    <td style="width:20%;"><label for="dt-heading"><?php _e( 'Homepage Image:', FB_DT_TEXTDOMAIN ); ?></label></td>
                    <td><input type="text" id="dt-heading" name="dt-heading" class="heading form-input-tip" size="16" autocomplete="off" value="<?php echo $value['home-featured-permalink']; ?>" tabindex="6" style="width:99.5%"/></td>
                </tr>
                
                <tr valign="top">
                    <td style="width:20%;"><label for="dt-photo-credit"><?php _e( 'Photo Credit:', FB_DT_TEXTDOMAIN ); ?></label></td>
                    <td><input type="text" id="dt-photo-credit" name="dt-photo-credit" class="heading form-input-tip" size="16" autocomplete="off" value="<?php echo $value['photo-credit']; ?>" tabindex="6" style="width:99.5%"/></td>
                </tr>
                
                <tr valign="top">
                    <td><label for="dt-category-image"><?php _e( 'Category Image:', FB_DT_TEXTDOMAIN ); ?></label></td>
                    <td><textarea cols="16" rows="1" id="dt-category-image" name="dt-category-image" class="additional-info form-input-tip code" size="16" autocomplete="off" tabindex="6" style="width:99.5%"/><?php echo $value['category-image']; ?></textarea>
                    </td>
                </tr>
                
                <tr valign="top">
                	<td><h4>Elements</h4></td>
                </tr>
                
                 <tr valign="top">
                    <td><label for="dt-listdata"><?php _e( 'Article Descriptor', FB_DT_TEXTDOMAIN ); ?></label></td>
                    <td><textarea cols="16" rows="3" id="dt-listdata" name="dt-listdata" class="listdata form-input-tip" size="20" autocomplete="off" tabindex="6" style="width:99.5%"/><?php echo $value['listdata']; ?></textarea><br /><small><?php _e( 'One item per line', FB_DT_TEXTDOMAIN ) ?></small></td>
                </tr>
                
                <tr valign="top">
                    <td><label for="dt-story-photo"><?php _e( 'Story Photo:', FB_DT_TEXTDOMAIN ); ?></label></td>
                    <td><textarea cols="16" rows="1" id="dt-story-photo" name="dt-story-photo" class="additional-info form-input-tip code" size="16" autocomplete="off" tabindex="6" style="width:99.5%"/><?php echo $value['story-photo']; ?></textarea>
                    </td>
                </tr>
                
                <tr valign="top">
                    <td><label for="dt-story-video"><?php _e( 'Story Video:', FB_DT_TEXTDOMAIN ); ?></label></td>
                    <td><textarea cols="16" rows="10" id="dt-story-video" name="dt-story-video" class="additional-info form-input-tip code" size="16" autocomplete="off" tabindex="6" style="width:99.5%"/><?php echo $value['story-video']; ?></textarea>
                    <small><?php _e( 'Cannot have Story Photo and Story Video. Will also receive Flash.', FB_DT_TEXTDOMAIN ) ?></small></td>
                </tr>
              
              	<tr valign="top">
                    <td><label for="dt-image-caption"><?php _e( 'Caption:', FB_DT_TEXTDOMAIN ); ?></label></td>
                    <td><textarea cols="16" rows="2" id="dt-image-caption" name="dt-image-caption" class="additional-info form-input-tip code" size="16" autocomplete="off" tabindex="6" style="width:99.5%"/><?php echo $value['image-caption']; ?></textarea>
                    </td>
                </tr>
                
                <tr valign="top">
                	<td><h4>Sidebar</h4></td>
                </tr>
                
                <tr valign="top">
                    <td><label for="dt-sources"><?php _e( 'Sources', FB_DT_TEXTDOMAIN ); ?></label></td>
                    <td><textarea cols="16" rows="3" id="dt-sources" name="dt-sources" class="listdata form-input-tip" size="20" autocomplete="off" tabindex="6" style="width:99.5%"/><?php echo $value['sources']; ?></textarea><br /><small><?php _e( 'One item per line', FB_DT_TEXTDOMAIN ) ?></small></td>
                </tr>

                <tr valign="top">
                    <td><label for="dt-sidebar-custom"><?php _e( 'Sidebar HTML:', FB_DT_TEXTDOMAIN ); ?></label></td>
                    <td><textarea cols="16" rows="6" id="dt-sidebar-custom" name="dt-sidebar-custom" class="additional-info form-input-tip code" size="16" autocomplete="off" tabindex="6" style="width:99.5%"/><?php echo $value['sidebar-custom']; ?></textarea>
                    </td>
                </tr>
                
            </table>
            <?php
        }
        
                
        // return facts incl. markup
        function get_DifferentTypeFacts($id, $type, $value) {

            if (!$value)
                return false;
            if ( $type == '' )
                return false;

            if ( 'home-featured-permalink' == $type && '' != $value['home-featured-permalink'] )
                return $value['home-featured-permalink'];
            if ( 'subhead' == $type && '' != $value['subhead'] )
                return $value['subhead'];
            if ( 'category-image' == $type && '' != $value['category-image'] )
                return $value['category-image'];
            if ( 'story-photo' == $type && '' != $value['story-photo'] ) {
            	return $value['story-photo'];
            }
            if ( 'story-video' == $type && '' != $value['story-video'] ) {
            	return $value['story-video'];
            }
            if ( 'image-caption' == $type && '' != $value['image-caption'] ) {
            	return $value['image-caption'];
            }
            if ( 'photo-credit' == $type && '' != $value['photo-credit'] ) {
            	return $value['photo-credit'];
            }
            
            if ( 'slider-head' == $type && '' != $value['slider-head'] ) {
            	return $value['slider-head'];
            }
            if ( 'category-head' == $type && '' != $value['category-head'] ) {
            	return $value['category-head'];
            }
            
           
            if ( 'sidebar-custom' == $type && '' != $value['sidebar-custom'] ) {
            	return $value['sidebar-custom'];
            }
            
            
            if ( 'listdata' == $type && '' != $value['listdata'] ) {
                $return = '';
                $listdatas = preg_split("/\r\n/", $value['listdata'] );

                foreach ( (array) $listdatas as $key => $listdata ) {

                    $return .= '<li>' . trim($listdata) . '</li>';

                }
                return '<ul class="descriptors">' . $return . '</ul>'. "\n";
            }
            
            if ( 'sources' == $type && '' != $value['sources'] ) {
                $return = '';
                $sourcedatas = preg_split("/\r\n/", $value['sources'] );

                foreach ( (array) $sourcedatas as $key => $sourcedata ) {

                    $return .= '<li>' . trim($sourcedata) . '</li>';

                }
                return '<ul class="sources">' . $return . '</ul>'. "\n";
            }
        }

        // echo facts, if exists
        function DifferentTypeFacts($id, $type, $string) {

            if ( $id ) {
                $value = $this->load_post_meta($id);

                echo $this->get_DifferentTypeFacts($id, $type, $value);
            }
        }
        
        //check if facts exist
        function ListDifferentTypeFacts($id, $type, $string) {

            if ( $id ) {
                $value = $this->load_post_meta($id);
				$newData = $this->get_DifferentTypeFacts($id, $type, $value);
               
               if($newData) {
                	return $newData;
                } else {
                	return false;
               	}
            }
        }
        
        

    } // End class

    // instance class
    $DifferentType = new DifferentType();

    // use in template
    function the_DifferentTypeFacts($id, $type = '', $string = '') {
        global $DifferentType;

        $DifferentType->DifferentTypeFacts($id, $type, $string);
    }
    
    function check_DifferentTypeFacts($id, $type = '', $string = '') {
        global $DifferentType;

        return $DifferentType->ListDifferentTypeFacts($id, $type, $string);
    }
    

} // End if class exists statement

	//set themes initial widget positions
	function theme_widgets_init() {
		
		//the sidebar for all pages
		//i recommend using TS custom widgets if you'd like to customize this
		register_sidebar( array (
			'name' => 'Sidebar',
			'id' => 'sidebar',
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)  );
		
		//footers are on every page.
		register_sidebar ( array (
			'name' => 'Footer 1',
			'id' => 'footer-1',
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)  );
		
		register_sidebar ( array (
			'name' => 'Footer 2',
			'id' => 'footer-2',
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)  );
		
		register_sidebar ( array (
			'name' => 'Footer 3',
			'id' => 'footer-3',
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)  );
		
		//this sidebar is only on the front-page.php template on the left side
		register_sidebar ( array (
			'name' => 'Left Content',
			'id' => 'left-content',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => ''
		)  );
		
		//this sidebar is only on the front-page.php template in the middle
		register_sidebar ( array (
			'name' => 'Middle Content',
			'id' => 'middle-content',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => ''
		)  );

		//this sidebar is only on the front-page.php template at the the very top.
		register_sidebar ( array (
			'name' => 'Top Story',
			'id' => 'topstory-content',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => ''
		)  );

		
	}
	
	//set excerpt length to work more appropriately with our image dropdowns
	function new_excerpt_length($length) {
		return 25;
	}
	
	//get orange arrows instead of [...]
	//this has been changed back to [...] but all you have to do is change the string
	function new_excerpt_more($more) {
		return '[...]';
	}
	
	//sets different contact methods than the original wordpress slots
	//to work with the themes news preferences.
	function change_contact_methods ( $contactmethods ) {
		$contactmethods['twitter'] = 'Twitter';
		$contactmethods['position'] = 'Position';
		$contactmethods['photo'] = 'Photo Permalink';
		$contactmethods['hometown'] = 'Home Town';
		$contactmethods['vimeo'] = 'Vimeo Number';
		$contactmethods['user_class'] = 'Class Standing';
		$contactmethods['major'] = 'Major';
		
		unset($contactmethods['yim']);
		unset($contactmethods['aim']);
		unset($contactmethods['jabber']);
		
		return $contactmethods;
	}
	
	//function to decide which word will appear in the header
	//this function MUST be updated when you add a new category because otherwise the posts wont have the header show up because is_category() will return false.
	//this function is only being used because we did not purchase Antenna Bold typeface
	//if one day we own that typeface it would be preferable to use that method so images do not have to be created
	function get_category_header() {	
		
		if ( is_author() ) {
			$category_name = "news";
		} elseif ( is_home() ) {
			$category_name = "news";
		
		} elseif ( is_category() ) {
			$category_name = single_cat_title('', false);
			$category_name = strtolower($category_name);
			
		} elseif ( is_page() ) {
			$category_name = "news";
		
		} elseif ( is_single() ) {
			//set default
			$category_name = 'news';
			
			if( in_category('nation') ) {
				$category_name = 'nation';
				return $category_name;
			}
			
			if ( in_category('health') ) {
				if( ( in_category('health-featured-category') ) || ( in_category('health-featured-home') ) ){
					$category_name = 'health';
					return $category_name;
				}
				
				$category_name = 'health';
			}
			
			if ( in_category('campus') ) {
				if( ( in_category('campus-featured-category') ) || ( in_category('u-featured-home') ) ){
					$category_name = 'campus';
					return $category_name;
				}
				$category_name = 'campus';
			}
			
			if ( in_category('biz') ) {
				if( ( in_category('biz-featured-category') ) || ( in_category('biz-featured-home') ) ){
					$category_name = 'biz';
					return $category_name;
				}
				$category_name = 'biz';
			}
			
			if ( in_category('life') ) {
				if( ( in_category('life-featured-category') ) || ( in_category('chill-featured-home') ) ) {
					$category_name = 'life';
					return $category_name;
				}
				$category_name = 'life';
			}
			
			if ( in_category('sport') ) {
				if( ( in_category('sport-featured-category') ) || ( in_category('sport-featured-home') ) ){
					$category_name = 'sport';
					return $category_name;
				}
				$category_name = 'sport';
			}
			
			if ( in_category('tech') ) {
				if( ( in_category('tech-featured-category') ) || ( in_category('talk-featured-home') ) ){
					$category_name = 'tech';
					return $category_name;
				}
				$category_name = 'tech';
			}
			
			if ( in_category('town') ) {
				if( ( in_category('town-featured-category') ) || ( in_category('town-featured-home') ) ){
					$category_name = 'town';
					return $category_name;
				}
				$category_name = 'town';
			}
		
		} else {
			$category_name = "news";
		}
		
		return $category_name;
	}
	
	//registers the menu at the top of the reese website
	function register_custom_menu() {
		register_nav_menu('reese_menu', __('Top Menu'));
	}
	
	//allows for iframes in tinyMCE, like if a google map is necessary or vimeo
	function my_change_mce_options( $init ) {
		$ext = 'iframe[id|class|title|style|align|frameborder|height|longdesc|marginheight|marginwidth|name|scrolling|src|width]';
	
		if ( isset( $init['extended_valid_elements'] ) ) {
			$init['extended_valid_elements'] .= ',' . $ext;
		} else {
			$init['extended_valid_elements'] = $ext;
		}
	
		return $init;
	}
	
	//checks if the sidebars are working as a error catch
	function is_sidebar_active( $index ){
		global $wp_registered_sidebars;
	 
		$widgetcolums = wp_get_sidebars_widgets();
	   
		if ($widgetcolums[$index]) return true;
	 
		return false;
	} 
	
	//allows for swf upload to the media library in wordpress
	function add_swf_support($mimes) {
		$mimes['swf'] = 'application/x-shockwave-flash';
		return $mimes;
	}

	
	/* REESE WIDGETS */
	
	class category_widget extends WP_Widget {
		
		function category_widget() {
			parent::WP_Widget(false, 'Category Widget');
		}
	
		function form($instance) {
		$category = esc_attr($instance['category']);
		
		$categories = get_categories();
 
		$cat_options = array();
		$cat_options[] = '<option value="BLANK">Select one...</option>';
 
		foreach ($categories as $cat) {
			$selected = $category === $cat->cat_ID ? ' selected="selected"' : '';
			$cat_options[] = '<option value="' . $cat->cat_ID .'"' . $selected . '>' . $cat->name . '</option>';
		}
		
			?><p>Select a Category</p>
			<select id="<?php echo $this->get_field_id('category'); ?>" class="widefat" name="<?php echo $this->get_field_name('category'); ?>">
				<?php echo implode('', $cat_options); ?>
			</select>
		
		<?php
		}
	
		function update($new_instance, $old_instance) {
			return $new_instance;
		}
	
		function widget($args, $instance) {
			extract($args);
			
			$category = (is_numeric($instance['category']) ? (int)$instance['category'] : '');
			
			?>
			
			<div class="news element">
			
			<?php
				$category_name = get_cat_name( $category );
				$category_link = get_category_link( $category );
				
				$vars = array(
					'cat' => $category,
					'showposts' => 1,
					'featured-location' => 'home'
				);
				
				 query_posts($vars); ?>
				<?php while ( have_posts() ) : the_post(); ?>
				<?php global $post; ?>
						
				<div class="topGutter">
					<a href="<?php echo $category_link; ?>"><div class="rss_button"><span><?php echo strtoupper($category_name); ?></span></div></a>
					
					<a href="http://www.reesenews.org/category/<?php echo strtolower($category_name); ?>/feed/" target="_blank"><img class="rss" src="<?php bloginfo('template_directory'); ?>/images/rss.png" /></a>
					
					<a href="<?php the_permalink(); ?>"><p class="head"><?php the_title(); ?></p>
					<p class="subhead"><?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'subhead'); ?></p></a>
						
					<p class="byline">By <?php coauthors_posts_links(); ?></p>
				</div>
				<div class="imageHolder">					
					<div class="boxgrid captionfull">
						<a href="<?php the_permalink(); ?>"><img src="<?php if ( function_exists('the_DifferentTypeFacts') ) the_DifferentTypeFacts($post->ID, 'home-featured-permalink'); ?>" /></a>
						<a href="<?php the_permalink(); ?>"><div class="cover boxcaption">
							<?php the_excerpt(); ?>
						</div></a>		
					</div>
					
					
					
				</div>
				
				<?php endwhile; ?>
				<?php rewind_posts(); ?>
				<?php wp_reset_query(); ?>
				
				<div class="navGutter">
				<p class="more">MORE <?php echo strtoupper($category_name); ?> <span class="arrows">&gt;&gt;&gt;</span></p>
					<ul>
						<?php					
							$vars = array(
								'cat' => $category,
								'showposts' => 4,
								'featured-location' => 'list'
							);
						?>
						
						<?php query_posts($vars); ?>
						<?php while ( have_posts() ) : the_post(); ?>
						<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><p class="byline">By <?php coauthors_posts_links(); ?></p></li>
						<?php endwhile; ?>
						<?php rewind_posts(); ?>
						<?php wp_reset_query(); ?>
					</ul>
				</div>
				
			</div>
			<?php 
		}
	
	}
	
	class video_widget extends WP_Widget {
		
		function video_widget() {
			parent::WP_Widget(false, 'Video Widget');
		}
	
		function form($instance) {
		?>
			<p>
				<label for="<?php echo $this->get_field_id('headline'); ?>">Headline:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('headline'); ?>" name="<?php echo $this->get_field_name('headline'); ?>" value="<?php echo $instance['headline']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('subhead'); ?>">Subhead:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('subhead'); ?>" name="<?php echo $this->get_field_name('subhead'); ?>" value="<?php echo $instance['subhead']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('permalink'); ?>">Permalink:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('permalink'); ?>" name="<?php echo $this->get_field_name('permalink'); ?>" value="<?php echo $instance['permalink']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('video'); ?>">Video ID:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('video'); ?>" name="<?php echo $this->get_field_name('video'); ?>" value="<?php echo $instance['video']; ?>" />
			</p>
		<?	
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			
			$instance['headline'] = strip_tags($new_instance['headline']);
			$instance['subhead'] = strip_tags($new_instance['subhead']);
			$instance['permalink'] = strip_tags($new_instance['permalink']);
			$instance['video'] = strip_tags($new_instance['video']);
			
			return $instance;
		}
	
		function widget($args, $instance) {
			extract( $args );
			
			$head = $instance['headline'];
			$subhead = $instance['subhead'];
			$link = $instance['permalink'];
			$video = $instance['video'];
						
		?>
			<div class="single-video element">				
				<div class="topGutter">
					<div class="alignright"><img src="<?php bloginfo('template_directory'); ?>/images/icon-video.png" /></div><a href="<?php echo $link; ?>"><p class="head"><?php echo $head; ?></p>
					<p class="subhead"><?php echo $subhead; ?></p></a>
				</div>
				<div class="videoHolder">					
					<!-- Start of Brightcove Player -->
	
					<div style="display:none">
					
					</div>
					
					<!--
					By use of this code snippet, I agree to the Brightcove Publisher T and C 
					found at https://accounts.brightcove.com/en/terms-and-conditions/. 
					-->
					
					<script language="JavaScript" type="text/javascript" src="http://admin.brightcove.com/js/BrightcoveExperiences.js"></script>
					
					<object id="myExperience<?php echo $video; ?>" class="BrightcoveExperience">
					  <param name="bgcolor" value="#FFFFFF" />
					  <param name="width" value="390" />
					  <param name="height" value="219" />
					  <param name="playerID" value="637606308001" />
					  <param name="playerKey" value="AQ~~,AAAAi6TjCsk~,poRVZzf2QsLRQDClx-6avFrO-uoiYULQ" />
					  <param name="isVid" value="true" />
					  <param name="isUI" value="true" />
					  <param name="dynamicStreaming" value="true" />
					  
					  <param name="@videoPlayer" value="<?php echo $video; ?>" />
					</object>
					
					<!-- 
					This script tag will cause the Brightcove Players defined above it to be created as soon
					as the line is read by the browser. If you wish to have the player instantiated only after
					the rest of the HTML is processed and the page load is complete, remove the line.
					-->
					<script type="text/javascript">brightcove.createExperiences();</script>
					
					<!-- End of Brightcove Player -->
				</div>
		
			</div>
		<?php 
		}
	
	}
	
	class single_widget extends WP_Widget {
		
		function single_widget() {
			parent::WP_Widget(false, 'Single Story Widget');
		}
	
		function form($instance) {
		?>
		
			<p>
				<label for="<?php echo $this->get_field_id('headline'); ?>">Headline:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('headline'); ?>" name="<?php echo $this->get_field_name('headline'); ?>" value="<?php echo $instance['headline']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('subhead'); ?>">Subhead:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('subhead'); ?>" name="<?php echo $this->get_field_name('subhead'); ?>" value="<?php echo $instance['subhead']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('permalink'); ?>">Permalink:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('permalink'); ?>" name="<?php echo $this->get_field_name('permalink'); ?>" value="<?php echo $instance['permalink']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('image'); ?>">Image:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" value="<?php echo $instance['image']; ?>" />
			</p>
		<?	
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			
			$instance['headline'] = strip_tags($new_instance['headline']);
			$instance['subhead'] = strip_tags($new_instance['subhead']);
			$instance['permalink'] = strip_tags($new_instance['permalink']);
			$instance['image'] = strip_tags($new_instance['image']);
			
			return $instance;
		}
	
		function widget($args, $instance) {
			extract( $args );
			
			$head = $instance['headline'];
			$subhead = $instance['subhead'];
			$link = $instance['permalink'];
			$img = $instance['image'];
						
		?>
			<div class="news element">				
				<div class="topGutter">
					<a href="<?php echo $link; ?>"><p class="head"><?php echo $head; ?></p>
					<p class="subhead"><?php echo $subhead; ?></p></a>
				</div>
				<div class="imageHolder">					
					<a href="<?php echo $link; ?>"><img src="<?php echo $img; ?>" /></a>
				</div>
			</div>
		<?php 
		}
	
	}
			
	class text_story_widget extends WP_Widget {
		
		function text_story_widget() {
			parent::WP_Widget(false, 'Text Story Widget');
		}
	
		function form($instance) {
		?>
			<h3 style="background-color:#ccc; border-radius: 5px; padding: 5px;">Entry 1</h4>
			
			<p>
				<label for="<?php echo $this->get_field_id('category_1'); ?>">Category:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('category_1'); ?>" name="<?php echo $this->get_field_name('category_1'); ?>" value="<?php echo $instance['category_1']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('headline_1'); ?>">Headline:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('headline_1'); ?>" name="<?php echo $this->get_field_name('headline_1'); ?>" value="<?php echo $instance['headline_1']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('permalink_1'); ?>">Permalink:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('permalink_1'); ?>" name="<?php echo $this->get_field_name('permalink_1'); ?>" value="<?php echo $instance['permalink_1']; ?>" />
			</p>
			
			<h3 style="background-color:#ccc; border-radius: 5px; padding: 5px;">Entry 2</h4>
			
			<p>
				<label for="<?php echo $this->get_field_id('category_2'); ?>">Category:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('category_2'); ?>" name="<?php echo $this->get_field_name('category_2'); ?>" value="<?php echo $instance['category_2']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('headline_2'); ?>">Headline:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('headline_2'); ?>" name="<?php echo $this->get_field_name('headline_2'); ?>" value="<?php echo $instance['headline_2']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('permalink_2'); ?>">Permalink:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('permalink_2'); ?>" name="<?php echo $this->get_field_name('permalink_2'); ?>" value="<?php echo $instance['permalink_2']; ?>" />
			</p>
			
			<h3 style="background-color:#ccc; border-radius: 5px; padding: 5px;">Entry 3</h4>
			
			<p>
				<label for="<?php echo $this->get_field_id('category_3'); ?>">Category:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('category_3'); ?>" name="<?php echo $this->get_field_name('category_3'); ?>" value="<?php echo $instance['category_3']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('headline_3'); ?>">Headline:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('headline_3'); ?>" name="<?php echo $this->get_field_name('headline_3'); ?>" value="<?php echo $instance['headline_3']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('permalink_3'); ?>">Permalink:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('permalink_3'); ?>" name="<?php echo $this->get_field_name('permalink_3'); ?>" value="<?php echo $instance['permalink_3']; ?>" />
			</p>
		<?	
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			
			$instance['category_1'] = strip_tags($new_instance['category_1']);
			$instance['headline_1'] = strip_tags($new_instance['headline_1']);
			$instance['permalink_1'] = strip_tags($new_instance['permalink_1']);
			
			$instance['category_2'] = strip_tags($new_instance['category_2']);
			$instance['headline_2'] = strip_tags($new_instance['headline_2']);
			$instance['permalink_2'] = strip_tags($new_instance['permalink_2']);
			
			$instance['category_3'] = strip_tags($new_instance['category_3']);
			$instance['headline_3'] = strip_tags($new_instance['headline_3']);
			$instance['permalink_3'] = strip_tags($new_instance['permalink_3']);
			
			return $instance;
			
			return $new_instance;
		}
	
		function widget($args, $instance) {
			extract( $args );
			
			for ( $j = 1; $j < 4; $j+=1 ) {
				$category[$j] = $instance['category_' . $j . ''];
				$headline[$j] = $instance['headline_' . $j . ''];
				$permalink[$j] = $instance['permalink_' . $j . ''];
			}
			
			echo '<div class="text-stories">';
											
			echo '<ul>';
			
			for ( $b = 1; $b < count($category)+1; $b+=1 ) {
				if ( $category[$b] ) {
					echo '<li>';
					echo '<div class="post-' . $b . '">';
				}
				
				if ( $category[$b] ) {
					
					echo '<h5>' . $category[$b] . '</h5>';
				}
				
				if ( $category[$b] ) {
					echo '<a href="'. $permalink[$b] . '"><p>' . $headline[$b] . '</p></a></div></li>';
				}
			}
			
			// end div
			echo '</ul>';
			echo '</div>';
			
		}
	
	}


	class bigstory_widget extends WP_Widget {
		
		function bigstory_widget() {
			parent::WP_Widget(false, 'Big Story');
		}
	
		function form($instance) {
		?>
			<p>
				<label for="<?php echo $this->get_field_id('headline'); ?>">Headline:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('headline'); ?>" name="<?php echo $this->get_field_name('headline'); ?>" value="<?php echo $instance['headline']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('subhead'); ?>">Subhead:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('subhead'); ?>" name="<?php echo $this->get_field_name('subhead'); ?>" value="<?php echo $instance['subhead']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('byline'); ?>">Byline:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('byline'); ?>" name="<?php echo $this->get_field_name('byline'); ?>" value="<?php echo $instance['byline']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('image'); ?>">Image: *</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" value="<?php echo $instance['image']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('permalink'); ?>">Permalink: *</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('permalink'); ?>" name="<?php echo $this->get_field_name('permalink'); ?>" value="<?php echo $instance['permalink']; ?>" />
			</p>
		<?	
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			
			$instance['headline'] = strip_tags($new_instance['headline']);
			$instance['subhead'] = strip_tags($new_instance['subhead']);
			$instance['byline'] = strip_tags($new_instance['byline']);
			$instance['image'] = strip_tags($new_instance['image']);
			$instance['permalink'] = strip_tags($new_instance['permalink']);
			
			return $instance;
		}
	
		function widget($args, $instance) {
			extract( $args );
			
			$headline = $instance['headline'];
			$subhead = $instance['subhead'];
			$byline = $instance['byline'];
			$image = $instance['image'];
			$permalink = $instance['permalink'];


                        echo '<div id="featured-noslide">';
                        echo '<a href="' . $link . '"><img height="293" width="940" src="' . $image . '" rel="caption-1" /></a>';

                        if ($headline) {
                            echo '<span class="orbit-caption" id="caption-1"><a href="' . $link . '"><p class="head">' . $headline . '</a></p>';
                            if ($subhead) {
                                echo '<a href="' . $link . '"><p class="subhead">' . $subhead . '</p></a>';
                            }
                            if ($byline) {
                                echo '<p class="byline">' . $byline . '</p>';

                            }
                            echo '</span>';
                        }


                        echo '<div id="slider-blue"></div></div>';

		}
	
	}
	
	
	class quote_widget extends WP_Widget {
		
		function quote_widget() {
			parent::WP_Widget(false, 'Pull Quote');
		}
	
		function form($instance) {
		?>
			<p>
				<label for="<?php echo $this->get_field_id('quote'); ?>">Quote:</label>
				<textarea rows="5" cols="25" id="<?php echo $this->get_field_id('quote'); ?>" name="<?php echo $this->get_field_name('quote'); ?>"><?php echo $instance['quote']; ?></textarea>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('name'); ?>">Name:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" value="<?php echo $instance['name']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('source'); ?>">Source:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('source'); ?>" name="<?php echo $this->get_field_name('source'); ?>" value="<?php echo $instance['source']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('name'); ?>">Image:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" value="<?php echo $instance['image']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('permalink'); ?>">Permalink:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('permalink'); ?>" name="<?php echo $this->get_field_name('permalink'); ?>" value="<?php echo $instance['permalink']; ?>" />
			</p>
		<?	
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			
			$instance['quote'] = strip_tags($new_instance['quote']);
			$instance['name'] = strip_tags($new_instance['name']);
			$instance['source'] = strip_tags($new_instance['source']);
			$instance['image'] = strip_tags($new_instance['image']);
			$instance['permalink'] = strip_tags($new_instance['permalink']);
			
			return $instance;
		}
	
		function widget($args, $instance) {
			extract( $args );
			
			$quote = $instance['quote'];
			$name = $instance['name'];
			$source = $instance['source'];
			$img = $instance['image'];
			$link = $instance['permalink'];
			
			//div class quote widget
			echo '<a href="' . $link . '">';
			echo '<div class="quote">';
			
			if ($quote) {
				echo '<p>' . $quote . '</p>';
			}
			
			echo '<div class="quote-source-area">';
			
			if ($img) {
				echo '<img height="80" width="80" src="' . $img . '" />';
			}
			
			if ($name) {
				echo '<p class="quote-name">' . $name . '</p>';
			}
			
			if ($source) {
				echo '<p class="source-line">' . $source . '</p>';
			}
			
			echo '</div><div class="clear"></div>';
			
			// end div
			echo '</div></a>';
		}
	
	}
	
	class blogs_widget extends WP_Widget {
		
		function blogs_widget() {
			parent::WP_Widget(false, 'Blog Posts');
		}
	
		function form($instance) {
		?>
			<h3 style="background-color:#ccc; border-radius: 5px; padding: 5px;">Entry 1</h4>
			<p>
				<label for="<?php echo $this->get_field_id('headline_1'); ?>">Entry Headline:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('headline_1'); ?>" name="<?php echo $this->get_field_name('headline_1'); ?>" value="<?php echo $instance['headline_1']; ?>" />
			</p>			
			
			<p>
				<label for="<?php echo $this->get_field_id('title_1'); ?>">Blog Name:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('title_1'); ?>" name="<?php echo $this->get_field_name('title_1'); ?>" value="<?php echo $instance['title_1']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('description_1'); ?>">Entry Description:</label>
				<textarea rows="5" cols="24" id="<?php echo $this->get_field_id('description_1'); ?>" name="<?php echo $this->get_field_name('description_1'); ?>"><?php echo $instance['description_1']; ?></textarea>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('image_1'); ?>">Image:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('image_1'); ?>" name="<?php echo $this->get_field_name('image_1'); ?>" value="<?php echo $instance['image_1']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('permalink_1'); ?>">Permalink:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('permalink_1'); ?>" name="<?php echo $this->get_field_name('permalink_1'); ?>" value="<?php echo $instance['permalink_1']; ?>" />
			</p>
			
			<h3 style="background-color:#ccc; border-radius: 5px; padding: 5px;">Entry 2</h4>
			<p>
				<label for="<?php echo $this->get_field_id('headline_2'); ?>">Entry Headline:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('headline_2'); ?>" name="<?php echo $this->get_field_name('headline_2'); ?>" value="<?php echo $instance['headline_2']; ?>" />
			</p>			
			
			<p>
				<label for="<?php echo $this->get_field_id('title_2'); ?>">Blog Name:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('title_2'); ?>" name="<?php echo $this->get_field_name('title_2'); ?>" value="<?php echo $instance['title_2']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('description_2'); ?>">Entry Description:</label>
				<textarea rows="5" cols="24" id="<?php echo $this->get_field_id('description_2'); ?>" name="<?php echo $this->get_field_name('description_2'); ?>"><?php echo $instance['description_2']; ?></textarea>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('image_2'); ?>">Image:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('image_2'); ?>" name="<?php echo $this->get_field_name('image_2'); ?>" value="<?php echo $instance['image_2']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('permalink_2'); ?>">Permalink:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('permalink_2'); ?>" name="<?php echo $this->get_field_name('permalink_2'); ?>" value="<?php echo $instance['permalink_2']; ?>" />
			</p>
			
			<h3 style="background-color:#ccc; border-radius: 5px; padding: 5px;">Entry 3</h4>
			<p>
				<label for="<?php echo $this->get_field_id('headline_3'); ?>">Entry Headline:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('headline_3'); ?>" name="<?php echo $this->get_field_name('headline_3'); ?>" value="<?php echo $instance['headline_3']; ?>" />
			</p>			
			
			<p>
				<label for="<?php echo $this->get_field_id('title_3'); ?>">Blog Name:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('title_3'); ?>" name="<?php echo $this->get_field_name('title_3'); ?>" value="<?php echo $instance['title_3']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('description_3'); ?>">Entry Description:</label>
				<textarea rows="5" cols="24" id="<?php echo $this->get_field_id('description_3'); ?>" name="<?php echo $this->get_field_name('description_3'); ?>"><?php echo $instance['description_3']; ?></textarea>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('image_3'); ?>">Image:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('image_3'); ?>" name="<?php echo $this->get_field_name('image_3'); ?>" value="<?php echo $instance['image_3']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('permalink_3'); ?>">Permalink:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('permalink_3'); ?>" name="<?php echo $this->get_field_name('permalink_3'); ?>" value="<?php echo $instance['permalink_3']; ?>" />
			</p>
			
			<h3 style="background-color:#ccc; border-radius: 5px; padding: 5px;">Entry 4</h4>
			<p>
				<label for="<?php echo $this->get_field_id('headline_4'); ?>">Entry Headline:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('headline_4'); ?>" name="<?php echo $this->get_field_name('headline_4'); ?>" value="<?php echo $instance['headline_4']; ?>" />
			</p>			
			
			<p>
				<label for="<?php echo $this->get_field_id('title_4'); ?>">Blog Name:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('title_4'); ?>" name="<?php echo $this->get_field_name('title_4'); ?>" value="<?php echo $instance['title_4']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('description_4'); ?>">Entry Description:</label>
				<textarea rows="5" cols="24" id="<?php echo $this->get_field_id('description_4'); ?>" name="<?php echo $this->get_field_name('description_4'); ?>"><?php echo $instance['description_4']; ?></textarea>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('image_4'); ?>">Image:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('image_4'); ?>" name="<?php echo $this->get_field_name('image_4'); ?>" value="<?php echo $instance['image_4']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('permalink_4'); ?>">Permalink:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('permalink_4'); ?>" name="<?php echo $this->get_field_name('permalink_4'); ?>" value="<?php echo $instance['permalink_4']; ?>" />
			</p>
			
			<h3 style="background-color:#ccc; border-radius: 5px; padding: 5px;">Entry 5</h4>
			<p>
				<label for="<?php echo $this->get_field_id('headline_5'); ?>">Entry Headline:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('headline_5'); ?>" name="<?php echo $this->get_field_name('headline_5'); ?>" value="<?php echo $instance['headline_5']; ?>" />
			</p>			
			
			<p>
				<label for="<?php echo $this->get_field_id('title_5'); ?>">Blog Name:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('title_5'); ?>" name="<?php echo $this->get_field_name('title_5'); ?>" value="<?php echo $instance['title_5']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('description_5'); ?>">Entry Description:</label>
				<textarea rows="5" cols="24" id="<?php echo $this->get_field_id('description_5'); ?>" name="<?php echo $this->get_field_name('description_5'); ?>"><?php echo $instance['description_5']; ?></textarea>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('image_5'); ?>">Image:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('image_5'); ?>" name="<?php echo $this->get_field_name('image_5'); ?>" value="<?php echo $instance['image_5']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('permalink_5'); ?>">Permalink:</label>
				<input type="text" class="heading form-input-tip" id="<?php echo $this->get_field_id('permalink_5'); ?>" name="<?php echo $this->get_field_name('permalink_5'); ?>" value="<?php echo $instance['permalink_5']; ?>" />
			</p>
		<?	
		}
	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			
			$instance['headline_1'] = strip_tags($new_instance['headline_1']);
			$instance['title_1'] = strip_tags($new_instance['title_1']);
			$instance['description_1'] = strip_tags($new_instance['description_1']);
			$instance['image_1'] = strip_tags($new_instance['image_1']);
			$instance['permalink_1'] = strip_tags($new_instance['permalink_1']);
			
			$instance['headline_2'] = strip_tags($new_instance['headline_2']);
			$instance['title_2'] = strip_tags($new_instance['title_2']);
			$instance['description_2'] = strip_tags($new_instance['description_2']);
			$instance['image_2'] = strip_tags($new_instance['image_2']);
			$instance['permalink_2'] = strip_tags($new_instance['permalink_2']);
			
			$instance['headline_3'] = strip_tags($new_instance['headline_3']);
			$instance['title_3'] = strip_tags($new_instance['title_3']);
			$instance['description_3'] = strip_tags($new_instance['description_3']);
			$instance['image_3'] = strip_tags($new_instance['image_3']);
			$instance['permalink_3'] = strip_tags($new_instance['permalink_3']);
			
			$instance['headline_4'] = strip_tags($new_instance['headline_4']);
			$instance['title_4'] = strip_tags($new_instance['title_4']);
			$instance['description_4'] = strip_tags($new_instance['description_4']);
			$instance['image_4'] = strip_tags($new_instance['image_4']);
			$instance['permalink_4'] = strip_tags($new_instance['permalink_4']);
			
			$instance['headline_5'] = strip_tags($new_instance['headline_5']);
			$instance['title_5'] = strip_tags($new_instance['title_5']);
			$instance['description_5'] = strip_tags($new_instance['description_5']);
			$instance['image_5'] = strip_tags($new_instance['image_5']);
			$instance['permalink_5'] = strip_tags($new_instance['permalink_5']);
			
			return $instance;
		}
	
		function widget($args, $instance) {
			extract( $args );
			
			for ( $i = 1; $i < 6; $i+=1 ) {
				$headline[$i] = $instance['headline_' . $i . ''];
				$title[$i] = $instance['title_' . $i . ''];
				$description[$i] = $instance['description_' . $i . ''];
				$img[$i] = $instance['image_' . $i . ''];
				$link[$i] = $instance['permalink_' . $i . ''];
			}
			
			echo '<div class="blogs">';
						
			$category_id = get_cat_ID('Blogs');
			$category_link = get_category_link($category_id);
								
			echo '<h3><span class="curly">&#123;</span><a href="#"> reeseblogs </a><span class="curly">&#125;</span></h3>';
			echo '<ul>';
			
			for ( $a = 1; $a < count($headline)+1; $a+=1 ) {
				if ( $headline[$a] ) {
					echo '<div class="blog-area">';
					echo '<a href="'. $link[$a] . '"><li>';
					echo '<img height="80" width="80" src="' . $img[$a] . '" />';
				}
				
				if ( $headline[$a] ) {
					
					echo '<h5>' . $headline[$a] . '</h5>';
				}
				
				if ( $title[$a] ) {
					echo '<p><span class="blog-title">' . $title[$a] . '</span>' . ' &#124; ';
				}
				
				if ( $description[$a] ) {
					echo '<span>' . $description[$a] . '</span></p>';
				}
				
				if ( $headline[$a] ) {
					echo '</li></a></div>';
				}
			}
			
			echo '</ul>';
			echo '</div>';
		}
	
	}
	
	//Reese custom taxonomy
	function placement_init() {
	  //create featured taxonomy
	  register_taxonomy(
	    'featured-location',
	    'post',
	    array( 'hierarchical' => true, 'label' => 'Placement Options', 'query_var' => true, 'rewrite' => true )
	  );
	}
	
	//register widgets
	register_widget('video_widget');
	register_widget('single_widget');
	register_widget('bigstory_widget');
	register_widget('quote_widget');
	register_widget('blogs_widget');
	register_widget('category_widget');
	register_widget('text_story_widget');
	
	
	add_filter('upload_mimes','add_swf_support');
	add_filter('tiny_mce_before_init', 'my_change_mce_options');
	add_filter('user_contactmethods', 'change_contact_methods', 10, 1);
	add_filter('excerpt_length', 'new_excerpt_length');
	add_filter('excerpt_more', 'new_excerpt_more');
		
	add_theme_support('post-thumbnails');
	add_action( 'init', 'theme_widgets_init' );
	add_action( 'init', 'register_custom_menu' );
	add_action( 'init', 'placement_init' );
?>
