<?php
/*
Plugin Name: Kento Ads Rotator
Plugin URI: http://kentothemes.com
Description: unlimited ads rotator, ads rotator, Ad Rotate, Ad Rotator, banner ads rotator, ads rotator wordpress, swf banner ads, flash banner Rotator
Version: 1.3
Author: KentoThemes
Author URI: http://kentothemes.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
require_once( plugin_dir_path( __FILE__ ) . 'includes/Browser.php');
define('KENTO_ADS_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
function kads_init_script()
	{
		wp_enqueue_script('jquery');
		wp_enqueue_style('kento-pricing-table-style', KENTO_ADS_PLUGIN_PATH.'css/style.css');
		wp_enqueue_script('kads_ajax_js', plugins_url( '/js/kads-ajax.js' , __FILE__ ) , array( 'jquery' ));
		wp_localize_script( 'kads_ajax_js', 'kads_ajax', array( 'kads_ajaxurl' => admin_url( 'admin-ajax.php')));
		
	
	
	}
add_action("init","kads_init_script");







add_filter('widget_text', 'do_shortcode');


















function joe_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');

wp_register_script('my-upload', KENTO_ADS_PLUGIN_PATH.'/js/kads-ajax.js', array('jquery','media-upload','thickbox'));

}
function my_admin_styles() {
wp_enqueue_style('thickbox');
}
add_action('admin_print_scripts', 'joe_admin_scripts');
add_action('admin_print_styles', 'my_admin_styles');


register_activation_hook(__FILE__, 'kads_install');


function kads_install()
	{
	global $wpdb;
        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "kads_info"
                 ."( UNIQUE KEY id (id),
					id int(100) NOT NULL AUTO_INCREMENT,
					bannerid  int(10) NOT NULL,
					event  VARCHAR( 50 ) NOT NULL,
					date  DATE NOT NULL,
					time  TIME NOT NULL,
					city  VARCHAR( 50 ) NOT NULL,
					country  VARCHAR( 50 ) NOT NULL,
					browser  VARCHAR( 50 ) NOT NULL,
					platform  VARCHAR( 50 ) NOT NULL
					
					)";
		$wpdb->query($sql);

		}


function kads_get_count($bannerid_serve)
	{
		
	global $wpdb;
	$table = $wpdb->prefix . "kads_info";
	
	// Get City And Country using get_city_country() Function
	$geo = explode(",",get_city_country());
	$geo_country =$geo[0];
	$geo_city =$geo[1];	
	//----Get City And Country----- 
	
	// Get Browser name using Browser.php class
	$browser = new Browser_KADS();
	$platform = $browser->getPlatform();
	$browser = $browser->getBrowser();


	// ----Get Browser----- 



$date = date('Y-m-d', strtotime('+'.get_option('gmt_offset').' hour'));
$time = date('H:i', strtotime('+'.get_option('gmt_offset').' hour'));

		if(isset($_POST['bannerid']))
			{
				$bannerid = (int)$_POST['bannerid'];
				$wpdb->query( $wpdb->prepare("INSERT INTO $table 
										( id, bannerid, event, date, time, city, country, browser, platform )
								VALUES	( %d, %d, %s,%s, %s, %s, %s, %s, %s )",
								array	( '', $bannerid,'click', $date, $time, $geo_country, $geo_city, $browser, $platform)
										));
			}
		elseif(isset($_POST['bannerid_hover']))
			{
				$bannerid = $_POST['bannerid_hover'];
				$wpdb->query( $wpdb->prepare("INSERT INTO $table 
										( id, bannerid, event, date, time, city, country, browser, platform )
								VALUES	( %d, %d, %s,%s, %s, %s, %s, %s, %s )",
								array	( '', $bannerid,'hover', $date, $time, $geo_country, $geo_city, $browser, $platform)
										));
			}
		
		elseif(isset($bannerid_serve))
			{
				$bannerid = $bannerid_serve;
				
				$wpdb->query( $wpdb->prepare("INSERT INTO $table 
										( id, bannerid, event, date, time, city, country, browser, platform )
								VALUES	( %d, %d, %s,%s, %s, %s, %s, %s, %s  )",
								array	( '', $bannerid,'serve', $date, $time, $geo_country, $geo_city, $browser, $platform)
										));
				
			}	




		
		return true;

	
	}

add_action('wp_ajax_kads_get_count', 'kads_get_count');
add_action('wp_ajax_nopriv_kads_get_count', 'kads_get_count');



function get_city_country()
	{
	
	$ip = $_SERVER['REMOTE_ADDR'];
	@$content = file_get_contents("http://www.geoplugin.net/xml.gp?ip=".$ip);
	preg_match('/<geoplugin_city>(.*)/i', $content, $matches);
	$city = !empty($matches[1]) ? $matches[1] : 0;
	$city = substr($city,0,-17);
	
	if($city == "")
		{
		$city = "none";
		}
	else
		{
		$city = $city;
		}
	preg_match('/<geoplugin_countryName>(.*)/i', $content, $matches);
	$country= !empty($matches[1]) ? $matches[1] : 0;
	$country = substr($country,0,-24);
	
	if($country == ""){
		$country = "none";}
	else {
		$country = $country;
		}
		
		return $city.",".$country;
	}


























add_action('init', 'kads_register');
 
function kads_register() {
 
        $labels = array(
                'name' => _x('KADS', 'post type general name'),
                'singular_name' => _x('KADS', 'post type singular name'),
                'add_new' => _x('Add New KADS', 'KADS'),
                'add_new_item' => __('Add New KADS'),
                'edit_item' => __('Edit KADS'),
                'new_item' => __('New KADS'),
                'view_item' => __('View KADS'),
                'search_items' => __('Search KADS'),
                'not_found' =>  __('Nothing found'),
                'not_found_in_trash' => __('Nothing found in Trash'),
                'parent_item_colon' => ''
        );
 
        $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => true,
                'show_ui' => true,
                'query_var' => true,
                'menu_icon' => null,
                'rewrite' => true,
                'capability_type' => 'post',
                'hierarchical' => false,
                'menu_position' => null,
                'supports' => array('title'),
				'menu_icon' => KENTO_ADS_PLUGIN_PATH.'/css/ads.png',
				

          );
 
        register_post_type( 'kads' , $args );

}



// Custom Taxonomy
 
function add_kads_taxonomies() {
 
        register_taxonomy('kad', 'kads', array(
                // Hierarchical taxonomy (like categories)
                'hierarchical' => true,
                'show_admin_column' => true,
                // This array of options controls the labels displayed in the WordPress Admin UI
                'labels' => array(
                        'name' => _x( 'kads Group', 'taxonomy general name' ),
                        'singular_name' => _x( 'kads Group', 'taxonomy singular name' ),
                        'search_items' =>  __( 'Search kads Groups' ),
                        'all_items' => __( 'All kads Groups' ),
                        'parent_item' => __( 'Parent kads Group' ),
                        'parent_item_colon' => __( 'Parent kads Group:' ),
                        'edit_item' => __( 'Edit kads Group' ),
                        'update_item' => __( 'Update kads Group' ),
                        'add_new_item' => __( 'Add New kads Group' ),
                        'new_item_name' => __( 'New kads Group Name' ),
                        'menu_name' => __( 'kads Groups' ),
						
                ),
                // Control the slugs used for this taxonomy
                'rewrite' => array(
                        'slug' => 'kads', // This controls the base slug that will display before each term
                        'with_front' => false, // Don't display the category base before "/locations/"
                        'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
                ),
        ));
}
add_action( 'init', 'add_kads_taxonomies', 0 );
















/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function meta_boxes_kads()
	{
		$screens = array( 'kads' );
		foreach ( $screens as $screen )
			{
				add_meta_box('kads_sectionid',__( 'KADS Banner Options','kads_textdomain' ),'meta_boxes_kads_input', $screen);
			}
	}
add_action( 'add_meta_boxes', 'meta_boxes_kads' );



/* Prints the box content. */
function meta_boxes_kads_input( $post ) {

	wp_nonce_field( 'meta_boxes_kads_input', 'meta_boxes_kads_input_nonce' );

	/*
	* Use get_post_meta() to retrieve an existing value
	* from the database and use the value for the form.
	*/


	$kads_bn_img = get_post_meta( $post->ID, 'kads_bn_img', true );
	$kads_bn_link = get_post_meta( $post->ID, 'kads_bn_link', true );	
	$kads_bn_size = get_post_meta( $post->ID, 'kads_bn_size', true );
	$kads_bn_target_window = get_post_meta( $post->ID, 'kads_bn_target_window', true );	
	$kads_bn_type = get_post_meta( $post->ID, 'kads_bn_type', true );
	$kads_bn_country = get_post_meta( $post->ID, 'kads_bn_country', true );

  
   ?>

	<table class="form-table">
		<tr valign="top">
			<th scope="row"><label for="kads-shortcode"><?php echo __('<strong>Shortcode</strong>'); ?>: </label></th><?php $tearms = wp_get_post_terms( get_the_ID(), 'kad', array("fields" => "names") );	?>
            <td style="vertical-align:middle;">

            <strong>Only This Banner</strong><br />
			<input size='30' onClick="this.select();" name='kads_shortcode' class='kads-shortcode' id="kads-shortcode" type='text' value='<?php echo '[kads id="'.get_the_ID().'"]'; ?>' /><br /><br />
            
            
            
            <strong>Banner Group</strong><br />
            <input size='30' onClick="this.select();" name='kads_shortcode' class='kads-shortcode' id="kads-shortcode" type='text' value='<?php echo '[kads group="'.implode(',',$tearms).'"]'; ?>' /><br /><span class="kads-shortcode-hint">Plese use this shortcode to disply table on your post or page</span>
			</td>
		</tr> 




		<tr valign="top">
			<th scope="row"><?php echo __('<strong>Banner Type</strong>'); ?></th>
			<td style="vertical-align:middle;">
            <label for="kads-bn-type-img"><input required="required" name="kads_bn_type" id="kads-bn-type-img" type="radio"   value="img" <?php if ($kads_bn_type=="img") echo "checked"; ?> /><?php echo __('Image Banner(.png, .jpg, .jpeg, .gif)'); ?></label><br />

            <label for="kads-bn-type-swf"><input name="kads_bn_type" id="kads-bn-type-swf" type="radio" value="swf" <?php if ($kads_bn_type=="swf") echo "checked"; ?>  /><?php echo __('SWF Banner'); ?></label><br /><br />
            
            
            <label for="kads-bn-img"><?php echo __('<strong>Banner Source Link</strong>'); ?>: </label><br />
            <input required="required" type="text" size='40' id="kads_bn_img" name="kads_bn_img" value="<?php if ( isset( $kads_bn_img ) ) echo $kads_bn_img; ?>" />
            <input class="upload_image_button button" type="button" value="Upload Image" /><br /><br />
            <?php 
			if(!empty($kads_bn_img))
				{
				$kads_bn_wh = explode(",",$kads_bn_size);
				
				
				if($kads_bn_type=="img")
					{
					
					
				
			?>
            
            <img width="<?php echo $kads_bn_wh[0]; ?>px" height="<?php echo $kads_bn_wh[1]; ?>px"  id="kads-bn-img-preview" src="<?php if ( isset( $kads_bn_img ) ) echo $kads_bn_img; ?>" />
            <?php
            		}
				elseif($kads_bn_type=="swf")
					{	
			?>
            
            <object width="<?php echo $kads_bn_wh[0]; ?>" height="<?php echo $kads_bn_wh[1]; ?>" data="<?php if ( isset( $kads_bn_img ) ) echo $kads_bn_img; ?>"></object>
            
            
            <?php
					}
				 }
			 ?>
			</td>
		</tr>
        
		<tr valign="top">
			<th scope="row"><label for="kads-bn-size"><?php echo __('<strong>Banner Size</strong>'); ?>: </label></th>
			<td style="vertical-align:middle;">
		<select name="kads_bn_size" class='kads-bn-size' id="kads-bn-size" >
            <optgroup label="Medium Rectangle" >
				<option value='300,250' <?php if ($kads_bn_size=="300,250") echo "selected"; ?> >300x250</option>
			</optgroup>
			<optgroup label="Square Pop-Up" >
				<option value='250,250' <?php if ($kads_bn_size=="250,250") echo "selected"; ?> >250x250</option>
			</optgroup>
			<optgroup label="Vertical Rectangle" >
				<option value='240,400' <?php if ($kads_bn_size=="240,400") echo "selected"; ?> >240x400</option>
			</optgroup>
			<optgroup label="Large Rectangle" >
				<option value='336,280' <?php if ($kads_bn_size=="336,280") echo "selected"; ?> >336x280</option>
			</optgroup>
			<optgroup label="Rectangle" >
				<option value='180,150' <?php if ($kads_bn_size=="180,150") echo "selected"; ?> >180x150</option>
			</optgroup>
			<optgroup label="3:1 Rectangle" >
				<option value='300,100' <?php if ($kads_bn_size=="300,100") echo "selected"; ?> >300x100</option>
			</optgroup>
			<optgroup label="Pop-Under" >
				<option value='720,300' <?php if ($kads_bn_size=="720,300") echo "selected"; ?> >720x300</option>
			</optgroup>
			<optgroup label="Full Banner" >
				<option value='468,60' <?php if ($kads_bn_size=="468,60") echo "selected"; ?> >468x60</option>
			</optgroup>
			<optgroup label="Half Banner" >
				<option value='234,60' <?php if ($kads_bn_size=="234,60") echo "selected"; ?> >234x60</option>
			</optgroup>
			<optgroup label="Micro Bar" >
				<option value='88,31' <?php if ($kads_bn_size=="88,31") echo "selected"; ?> >88x31</option>
			</optgroup>
			<optgroup label="Button 1" >
				<option value='120,90' <?php if ($kads_bn_size=="120,90") echo "selected"; ?> >120x90</option>
			</optgroup>
			<optgroup label="Button 2" >
				<option value='120,60' <?php if ($kads_bn_size=="120,60") echo "selected"; ?> >120x60</option>
			</optgroup>
			<optgroup label="Vertical banner" >
				<option value='120,240' <?php if ($kads_bn_size=="120,240") echo "selected"; ?> >120x240</option>
			</optgroup>
			<optgroup label="Square button" >
				<option value='125,125' <?php if ($kads_bn_size=="125,125") echo "selected"; ?> >125x125</option>
			</optgroup>
			<optgroup label="Leaderboard" >
				<option value='728,90' <?php if ($kads_bn_size=="728,90") echo "selected"; ?> >728x90</option>
			</optgroup>
			<optgroup label="Wide skyscraper" >
				<option value='160,600' <?php if ($kads_bn_size=="160,600") echo "selected"; ?> >160x600</option>
			</optgroup>
			<optgroup label="Skyscraper" >
				<option value='120,600' <?php if ($kads_bn_size=="120,600") echo "selected"; ?> >120x600</option>
			</optgroup>
			<optgroup label="Half page ad" >
				<option value='300,600' <?php if ($kads_bn_size=="300,600") echo "selected"; ?> >300x600</option>
			</optgroup>
		 </select>
		</td>
	</tr>
    
<tr valign="top">
			<th scope="row"><label for="kads-bn-target-window"><?php echo __('<strong>Target Window</strong>'); ?>: </label></th>
			<td style="vertical-align:middle;">
                <select name="kads_bn_target_window" class='kads-bn-target-window' id="kads-bn-target-window" >
                        <option value='_blank' <?php if ($kads_bn_target_window=="_blank") echo "selected"; ?> >New Window</option>
                        <option value='_self' <?php if ($kads_bn_target_window=="_self") echo "selected"; ?> >Same Window</option>
                 </select>
		</td>
	</tr>
    
    
  	<tr valign="top">
		<th scope="row"><label for="kads-bn-link"><?php echo __('<strong>Banner Target Link</strong>'); ?>: </label></th>
		<td style="vertical-align:middle;">
        <input required="required" size='40' name='kads_bn_link' class='kads-bn-link' id="kads-bn-link" type="text" value='<?php if ( isset( $kads_bn_link ) ) echo $kads_bn_link; ?>' placeholder="Banner Target Link" />
        </td>
	</tr>



  	<tr valign="top">

		<td colspan="2" style="vertical-align:middle;">
        <div id="kads-events">
        <?php echo __('<h2>Event Stats</h2>'); ?>
        <?php $postid = get_the_ID();

		$kads_recent_items = isset( $_GET['kads_recent_items'] ) ? $_GET['kads_recent_items'] : 10;
		$kads_event = isset( $_GET['kads_event'] ) ? $_GET['kads_event'] : "click";
		 ?>
        
    <select name="kads_recent_items" class="kads-recent-items" >
    	
    	<option <?php if($kads_recent_items=="10") echo "selected='selected'" ?>  value="10" >10 Items</option>
    	<option <?php if($kads_recent_items=="20") echo "selected='selected'" ?>  value="20" >20 Items</option>
    	<option <?php if($kads_recent_items=="50") echo "selected='selected'" ?>  value="50" >50 Items</option>
    	<option <?php if($kads_recent_items=="100") echo "selected='selected'" ?>  value="100" >100 Items</option>
    	<option <?php if($kads_recent_items=="500") echo "selected='selected'" ?>  value="500" >500 Items</option>
	</select>
    
    <select name="kads_event" class="kads-event" >
    	<option <?php if($kads_event=="click") echo "selected='selected'" ?>  value="click" >Click</option>
    	<option <?php if($kads_event=="serve") echo "selected='selected'" ?>  value="serve" >Serve</option>
    	<option <?php if($kads_event=="hover") echo "selected='selected'" ?>  value="hover" >Hover</option>
	</select>    
    
    <div class="button kads-update-sats" >Submit</div>
	<script>
    jQuery(document).ready(function() {
        
	
	jQuery(".kads-update-sats").click(function(){

			var kads_recent_items = jQuery(".kads-recent-items").val();
			var kads_event = jQuery(".kads-event").val();

			
			location = "<?php echo get_admin_url(); ?>post.php?post=<?php echo $postid; ?>&action=edit&kads_recent_items="+kads_recent_items+"&kads_event="+kads_event+"#kads-events";
			})
			
		})
	
	
	
	
	
    </script>
    
        
<?php
	global $wpdb;
	$kads_event = isset( $_GET['kads_event'] ) ? $_GET['kads_event'] : "click";
	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	
	$limit = isset( $_GET['kads_recent_items'] ) ? absint( $_GET['kads_recent_items'] ) : 10;
	$offset = ( $pagenum - 1 ) * $limit;
	$entries = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}kads_info WHERE event='$kads_event' AND bannerid=$postid ORDER BY id DESC LIMIT $offset, $limit" );
 

 
?>  
      
<table class="widefat kads-events" id="" >
    <thead>
        <tr>
            <th scope="col" class="manage-column column-name" style=""><strong>Event</strong></th>
            <th scope="col" class="manage-column column-name" style=""><strong>Date & Time</strong></th>            
            <th scope="col" class="manage-column column-name" style=""><strong>Country</strong></th>
            <th scope="col" class="manage-column column-name" style=""><strong>City</strong></th>
            <th scope="col" class="manage-column column-name" style=""><strong>Browser</strong></th>
            <th scope="col" class="manage-column column-name" style=""><strong>Platform</strong></th> 
        </tr>
    </thead>
 
    <tfoot>
        <tr>
            <th scope="col" class="manage-column column-name" style=""><strong>Event</strong></th>
            <th scope="col" class="manage-column column-name" style=""><strong>Date & Time</strong></th> 
            <th scope="col" class="manage-column column-name" style=""><strong>Country</strong></th>
            <th scope="col" class="manage-column column-name" style=""><strong>City</strong></th>
            <th scope="col" class="manage-column column-name" style=""><strong>Browser</strong></th>
            <th scope="col" class="manage-column column-name" style=""><strong>Platform</strong></th> 
        </tr>
    </tfoot>
        
        
        
	<tbody>
        <?php if( $entries ) { ?>
 
            <?php
            $count = 1;
            $class = '';
            foreach( $entries as $entry ) {
                $class = ( $count % 2 == 0 ) ? ' class="alternate"' : '';
            ?>
 
            <tr<?php echo $class; ?>>
                <td><?php echo	$entry->event; ?></td>
                <td><?php echo	$entry->date." ".$entry->time;  ?></td>                
                <td><?php echo	$entry->country; ?></td>   
                <td><?php echo	$entry->city; ?></td>
                <td><?php
echo '<span class="browser '.$entry->browser.'" title="Browser: '.$entry->browser.'"> </span>';
				 ?></td>
                <td><?php echo	"<span class='platform ".$entry->platform."' title='Operating System:".$entry->platform."'></span>"; ?>
                
                
                </td>                
                            
            </tr>
 
            <?php
                $count++;
            }
            ?>
 
        <?php } else { ?>
        <tr>
            <td colspan="2">No Data</td>
        </tr>
        <?php } ?>
	</tbody> 
</table>  
        
<?php
 
$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM {$wpdb->prefix}kads_info  WHERE event='$kads_event' AND bannerid=$postid" );
$num_of_pages = ceil( $total / $limit );
$page_links = paginate_links( array(
    'base' => add_query_arg( 'pagenum', '%#%#kads-events' ),
    'format' => '',
    'prev_text' => __( '&laquo;', 'aag' ),
    'next_text' => __( '&raquo;', 'aag' ),
    'total' => $num_of_pages,
    'current' => $pagenum
) );
 
if ( $page_links ) {
    echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
}
 


		$result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}kads_info WHERE event='click' AND bannerid=$postid", ARRAY_A);
		$total_click = $wpdb->num_rows;
		echo "<br /><br />Total Click: ".$total_click."<br />";
		
		$result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}kads_info WHERE event='hover' AND bannerid=$postid", ARRAY_A);
		$total_hover = $wpdb->num_rows;
		echo "Total Hover: ".$total_hover."<br />";
		
		$result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}kads_info WHERE event='serve' AND bannerid=$postid", ARRAY_A);
		$total_serve = $wpdb->num_rows;
		echo "Total Serve: ".$total_serve."<br />";	







?>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        </div>
        </td>
	</tr>




 
</table>

  
<?php }

function meta_boxes_kads_save( $post_id )
	{

		if ( ! isset( $_POST['meta_boxes_kads_input_nonce'] ) )
			return $post_id;

		$nonce = $_POST['meta_boxes_kads_input_nonce'];

		if ( ! wp_verify_nonce( $nonce, 'meta_boxes_kads_input' ) )
			return $post_id;


		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		$kads_bn_img = $_POST['kads_bn_img'];
		$kads_bn_link =  $_POST['kads_bn_link'];
		$kads_bn_size = $_POST['kads_bn_size'];
		$kads_bn_target_window = $_POST['kads_bn_target_window'];		
	
		if(!empty($_POST['kads_bn_type']))
			{
			$kads_bn_type = $_POST['kads_bn_type'];
			}
		else
			{
			$kads_bn_type ="";
			}
			
		if(!empty($_POST['kads_bn_country']))
			{
			$kads_bn_country = $_POST['kads_bn_country'];
			}		
		else
			{
			$kads_bn_country ="";
			}
		update_post_meta( $post_id, 'kads_bn_img', $kads_bn_img);
		update_post_meta( $post_id, 'kads_bn_link', $kads_bn_link);	
		update_post_meta( $post_id, 'kads_bn_size', $kads_bn_size);
		update_post_meta( $post_id, 'kads_bn_target_window', $kads_bn_target_window);		
		update_post_meta( $post_id, 'kads_bn_type', $kads_bn_type);	
		update_post_meta( $post_id, 'kads_bn_country', $kads_bn_country);
	}
	
add_action( 'save_post', 'meta_boxes_kads_save' );






function kads_display($atts,  $content = null ) {
		$atts = shortcode_atts(
			array(
				'group' => "",
				'id' => "",
				), $atts);
	
	
	if(!empty($atts['id']))
		{
			$postid = $atts['id'];
		}
	else
		{
			$group = $atts['group'];
			
			query_posts(array( 
					'post_type' => 'kads',
					'kad' => $group 
				) );  
			
			$t=0;
			while (have_posts()) : the_post();
			
			$ids_tax[$t]= get_the_id();
			
			$t++;
			endwhile;
			wp_reset_query();
		
			if(empty($ids ))
				{
					$ids = $ids_tax;
				}
			else
				{
					$ids = $postid;
				}
		
		
		
			$total_banner = count($ids);
			$select_banner_id = rand(0,($total_banner-1));
			$postid = $ids_tax[$select_banner_id];
		}
	
	
	
	
	
	$kads_bn_img = get_post_meta( $postid, 'kads_bn_img', true );
	$kads_bn_link = get_post_meta( $postid, 'kads_bn_link', true );
	$kads_bn_size = get_post_meta( $postid, 'kads_bn_size', true );
	$kads_bn_target_window = get_post_meta( $postid, 'kads_bn_target_window', true );	
	$kads_bn_type = get_post_meta( $postid, 'kads_bn_type', true );
	$kads_logo_img_link = get_option( 'kads_logo_img_link' );





	$kads_bn_wh = explode(",",$kads_bn_size);
	$cont= "";
	$cont.= "<div bannerid='".$postid."' target='".$kads_bn_link."' target-window='".$kads_bn_target_window."'  style='width:".$kads_bn_wh[0]."px; height:".$kads_bn_wh[1]."px;'  class='kads-main' id='kads-main' >";
	
	if($kads_bn_type=='img')
		{
		$cont.=  "<img src='".$kads_bn_img."' />";
		}
	elseif($kads_bn_type=='swf')
		{
		$cont.= "<object>";
		$cont.=  "<embed allowscriptaccess='always' id='banner-swf' width='".$kads_bn_wh[0]."' height='".$kads_bn_wh[1]."' src='".$kads_bn_img."'>";
		$cont.= "</object>";
		
		}
	

	$cont.=  "<span class='kads-logo' style='background-image:url(".$kads_logo_img_link.");background-repeat: no-repeat;'></span>";


	
	$cont.=  "</div>";
	kads_get_count($postid);
	
	
	
	return $cont;



}

add_shortcode('kads', 'kads_display');



function kads_style()
	{	

		
		$kads_logo_position = get_option( 'kads_logo_position' );
		
		
		
		echo "<style type='text/css'>";
		if($kads_logo_position=="top-left")
			{
			echo ".kads-main .kads-logo {left: 0;top: 0;}";
			}
		elseif($kads_logo_position=="top-right")
			{
			echo ".kads-main .kads-logo {right: 0;top: 0;}";
			}
		elseif($kads_logo_position=="bottom-left")
			{
			echo ".kads-main .kads-logo {bottom: 0;left: 0;}";
			
			}			
		elseif($kads_logo_position=="bottom-right")
			{
			echo ".kads-main .kads-logo {bottom: 0;right: 0;}";
			
			}			
		
		
	
		
		
			
		echo "</style>";
	}

add_filter('wp_head', 'kads_style');






function kads_stats($postid)
	{

	global $wpdb;
 
	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = isset( $_GET['kads_recent_items'] ) ? absint( $_GET['kads_recent_items'] ) : 10;










		global $wpdb;
		$table = $wpdb->prefix."kads_info";
		$result = $wpdb->get_results("SELECT * FROM $table WHERE event='click' AND bannerid=$postid ", ARRAY_A);
		$total_click = $wpdb->num_rows;

		?>
    
	<table class='kads-events widefat' id="kads-events">
    <thead>
        <tr>
            <th scope="col" class="manage-column column-name" style=""><strong>Event</strong></th>
            <th scope="col" class="manage-column column-name" style=""><strong>Country</strong></th>
            <th scope="col" class="manage-column column-name" style=""><strong>City</strong></th>
            <th scope="col" class="manage-column column-name" style=""><strong>Browser</strong></th>
            <th scope="col" class="manage-column column-name" style=""><strong>Platform</strong></th>                        
        </tr>
    </thead>
    
    
        <?php

		$i=0;
		while($total_click>$i)
			{
			echo "<tr><td>";
			echo $result[$i]['event'];
			echo "</td>";
			echo "<td>";
			echo $result[$i]['country'];
			echo "</td>";			
			echo "<td>";
			echo $result[$i]['city'];
			echo "</td>";

			echo "<td>";
			echo '<span class="browser '.$result[$i]['browser'].'" title="Browser: '.$result[$i]['browser'].'"> </span>';
			echo "</td>";

			echo "<td>";
			echo "<span class='platform ".$result[$i]['platform']."' title='Operating System:".$result[$i]['platform']."'></span>";
			echo "</td>";

			echo "</tr>";
	
			$i++;
			}
		echo "</table><br />";

		echo "Total Click: ".$total_click."<br />";
		
		$result = $wpdb->get_results("SELECT * FROM $table WHERE event='hover' AND bannerid=$postid", ARRAY_A);
		$total_hover = $wpdb->num_rows;
		echo "Total Hover: ".$total_hover."<br />";
		
		$result = $wpdb->get_results("SELECT * FROM $table WHERE event='serve' AND bannerid=$postid", ARRAY_A);
		$total_serve = $wpdb->num_rows;
		echo "Total Serve: ".$total_serve."<br />";		
		
		
	}
	






























////////////////////////////////////////////////////////////

add_action('admin_init', 'kads_init' );
add_action('admin_menu', 'kads_menu_init');

 function kads_init(){
	register_setting( 'kads_plugin_options', 'kads_logo_position');
	register_setting( 'kads_plugin_options', 'kads_logo_img_link');	

		
    }
	
function kads_settings(){
	include('kads-admin.php');	
}


function kads_menu_init() {
	
	add_submenu_page('edit.php?post_type=kads', __('kads Info','menu-kads'), __('kads Info','menu-kads'), 'manage_options', 'kads_settings', 'kads_settings');
	

	
}


?>