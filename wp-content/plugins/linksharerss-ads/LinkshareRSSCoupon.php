<?php
/*
Plugin Name: Linkshare Dealfeed
Plugin URI: http://www.linkshare.com/
Description: Allows you to incorporate coupons and deals from the LinkShare affiliate network into your site.
Version: 0.2.4
License: GPL
Author: Peter Wayner and Dave Kellam
Author URI: http://www.linkshare.com/
*/

// Based on the work of Dave Kellam


function get_linkshareRSS() {

	// the function can accept up to seven parameters, otherwise it uses option panel defaults 	
  	for($i = 0 ; $i < func_num_args(); $i++) {
    	$args[] = func_get_arg($i);
    	}
  	if (!isset($args[0])) $num_items = get_option('linkshareRSS_display_numRSSitems'); else $num_items = $args[0];
  	if (!isset($args[1])) $type = get_option('LinkshareRSS_display_type'); else $type = $args[1];
  	if (!isset($args[2])) $tags = trim(get_option('linkshareRSS_tags')); else $tags = trim($args[2]);
  	if (!isset($args[3])) $imagesize = get_option('linkshareRSS_display_imagesize'); else $imagesize = $args[3];
  	if (!isset($args[4])) $before_image = stripslashes(get_option('linkshareRSS_before')); else $before_image = $args[4];
  	if (!isset($args[5])) $after_image = stripslashes(get_option('linkshareRSS_after')); else $after_image = $args[5];
  	if (!isset($args[6])) $id_number = stripslashes(get_option('linkCoupon_linkshareid')); else $id_number = $args[6];
  	if (!isset($args[7])) $RSSHeadline_id = stripslashes(get_option('linkshareRSS_RSSHeadline')); else $RSSHeadline_id = $args[7];
        
	# use image cache & set location
	$useImageCache = get_option('linkshareRSS_use_image_cache');
	$cachePath = get_option('linkshareRSS_image_cache_uri');
	$fullPath = get_option('linkshareRSS_image_cache_dest'); 

	if (!function_exists('MagpieRSS')) { // Check if another plugin is using RSS, may not work
		include_once (ABSPATH . WPINC . '/rss.php');
		error_reporting(E_ERROR);
	}
	
	
	
	// get the feeds
	$rss_url=$type.$id_number;
	
	# get rss file
	$rss = @ fetch_rss($rss_url);
	
	if ($rss) {
	    $items = array_slice($rss->items, 0, $num_items);
 
        print '<h5>'.$RSSHeadline_id.'</h5>';
        print '<ul>';
        foreach ( $items as $item ) {
          $parts=array_slice($item,0,10);
          $pieces=array_values($item);
         ## print_r(array_values($parts));
          print '<li> <a href="';
          print $pieces[3];
          print '"> ';
          print $pieces[0];
          
          #foreach ($parts as $part){
          #   print '<li> Part:';
          #   print $part;
          #   print '</li>';
          # }
         print '</a></li>';
       } // end foreach
    print '</ul>';
  } // end if($rss)
} # end get_linkshareRSS() function



function widget_linkshareRSSFeed_init() {
	if (!function_exists('register_sidebar_widget')) return;

	function widget_linkshareRSS($args) {
		
		extract($args);

		$options = get_option('widget_linkshareRSS');
		$title = $options['title'];
		$before_images = $options['before_images'];
		$after_images = $options['after_images'];
		
		echo $before_widget . $before_title . $title . $after_title . $before_images;
		get_linkshareRSS();
		echo $after_images . $after_widget;
	}

	function widget_linkshareRSS_control() {
		$options = get_option('widget_linkshareRSS');

		if ( $_POST['linkshareRSS-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['linkshareRSS-title']));
			$options['before_images'] = $_POST['linkshareRSS-beforeimages'];
			$options['after_images'] = $_POST['linkshareRSS-afterimages'];
			update_option('widget_linkshareRSS', $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$before_images = htmlspecialchars($options['before_images'], ENT_QUOTES);
		$after_images = htmlspecialchars($options['after_images'], ENT_QUOTES);
		
		echo '<p style="text-align:right;"><label for="linkshareRSS-title">Title: <input style="width: 180px;" id="gsearch-title" name="linkshareRSS-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="linkshareRSS-beforeimages">Before all images: <input style="width: 180px;" id="linkshareRSS-beforeimages" name="linkshareRSS-beforeimages" type="text" value="'.$before_images.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="linkshareRSS-afterimages">After all images: <input style="width: 180px;" id="linkshareRSS-afterimages" name="linkshareRSS-afterimages" type="text" value="'.$after_images.'" /></label></p>';
		echo '<input type="hidden" id="linkshareRSS-submit" name="linkshareRSS-submit" value="1" />';
	}		

	register_sidebar_widget('linkshareRSS', 'widget_linkshareRSS');
	register_widget_control('linkshareRSS', 'widget_linkshareRSS_control', 300, 100);
}



/////---------------------------------------------------------------------
///// Coupon section
/////---------------------------------------------------------------------




$linkcount=0;
$linkdata=array();
$state='';

function startElementHandler ($parser,$name,$attrib){
global $linkcount;
global $linkdata;
global $state;

switch ($name) {

case $name=="STREET" : {
$linkdata[$linkcount]["hstreet"] = $attrib["HOME"];
$linkdata[$linkcount]["bstreet"] = $attrib["WORK"];
break;
}
case $name=="WEB" : {
$linkdata[$linkcount]["hweb"] = $attrib["HOME"];
$linkdata[$linkcount]["bweb"] = $attrib["WORK"];
break;
}

default : {$state=$name;break;}
}
}

function endElementHandler ($parser,$name){
global $linkcount;
global $linkdata;
global $state;
$state='';
if($name=="LINK") {$linkcount++;}
}

function characterDataHandler ($parser, $data) {
global $linkcount;
global $linkdata;
global $state;
if (!$state) {return;}
if (strlen($data)<=0) {return;}
if ($state=="OFFERSTARTDATE") { $linkdata[$linkcount]["OFFERSTARTDATE"] .= $data;}
if ($state=="OFFERENDDATE") { $linkdata[$linkcount]["OFFERENDDATE"] .= $data;}
if ($state=="COUPONRESTRICTION") { $linkdata[$linkcount]["COUPONRESTRICTION"] .= $data;}
if ($state=="CLICKURL") { $linkdata[$linkcount]["CLICKURL"] .= $data;}
if ($state=="IMPRESSIONPIXEL") { $linkdata[$linkcount]["IMPRESSIONPIXEL"] .= $data;}
if ($state=="ADVERTISERID") { $linkdata[$linkcount]["ADVERTISERID"] .= $data;}
if ($state=="ADVERTISERNAME") { $linkdata[$linkcount]["ADVERTISERNAME"] .= $data;}
if ($state=="NETWORK") { $linkdata[$linkcount]["NETWORK"] .= $data;}
if ($state=="OFFERDESCRIPTION") { $linkdata[$linkcount]["OFFERDESCRIPTION"] .= $data; }
}


 function myplugin_coupon_lookup()
 {
   // read submitted information
   $token=get_option('linkCoupon_linkshareid');
   $mid=get_option('linkCoupon_display_merchant');
   $net=get_option('linkCoupon_display_network');
   $promo=get_option('linkCoupon_display_promo');
   $category=get_option('linkCoupon_display_category');
      
     
  
   
   // Build Snoopy URL request
 
   if ( !class_exists( 'WP_Http' ) ) { include_once( ABSPATH . WPINC. '/class-http.php' );}
   $sno = new WP_Http;
   $sno->agent = 'WordPress/' . $wp_version;
   $sno->read_timeout = 10;
   $reqURL= "http://couponfeed.linksynergy.com/coupon?tag=wpdealfeed-c2&token=".$token;

   if ((strlen($mid)>0) && $mid!=0) {
      $reqURL=$reqURL."&mid=".$mid;
   } 

   if ((strlen($net)>0) && $net!=0){
      $reqURL=$reqURL."&network=".$net;
   } 
    if ((strlen($promo)>0) && $promo!=0){
      $reqURL=$reqURL."&promotiontype=".$promo;
   } 
    if ((strlen($category)>0) && $category!=0){
      $reqURL=$reqURL."&category=".$category;
   } 
   $reqURL=$reqURL."&resultsperpage=20"; 

	$result = $sno->request($reqURL);

   if( !$result) {  die( "alert('Could not connect to lookup host.')" );  } 
 // Compose JavaScript for return

 	
   return $result['body'];
 }  


function get_linkshareCoupons() {
global $linkcount;
global $linkdata;
global $state;
     add_filter( 'http_request_timeout', 'wp_filter_timeout_time');
     $tmp=myplugin_coupon_lookup();

	$maxDisplay=get_option('linkCoupon_display_numitems');
	$headline=get_option('linkCoupon_headline');
	

    $linkcount=0;
    $linkdata=array();
    $state='';
 
    if (!($xml_parser = xml_parser_create())) die("Couldn't create parser.");
    xml_set_element_handler( $xml_parser, "startElementHandler", "endElementHandler");
    xml_set_character_data_handler( $xml_parser, "characterDataHandler");

   if (!xml_parse($xml_parser, $tmp)){
     print "<br><b>Error on line " . xml_get_current_line_number($xml_parser)."</b>";
     print substr($tmp,0,300);
   }
    
    xml_parser_free($xml_parser);
	
	###print $tmp;
	
	if ($linkcount<$maxDisplay){$maxDisplay=$linkcount;}

        print '<h5> '.$headline.'</h5>';
        print '<ul>';
       for ($i=0;$i<$maxDisplay; $i++) {
       
          ## print_r(array_values($linkdata));
          print '<li> <a href="';
          print $linkdata[$i]["CLICKURL"];
          print '"> ';
          print $linkdata[$i]["OFFERDESCRIPTION"];
          #foreach ($parts as $part){
          #   print '<li> Part:';
          #   print $part;
          #   print '</li>';
          # }
         print '</a></li>';
       } // end for 
    print '</ul>';
 
}  

function wp_filter_timeout_time($time) {
	$time = 10; //new number of seconds
	return $time;
}

function widget_linkCoupon_init() {
	if (!function_exists('register_sidebar_widget')) return;

	function widget_linkCoupon($args) {
		
		extract($args);

		$options = get_option('widget_linkCoupon');
		$title = $options['title'];
		$before_images = $options['before_images'];
		$after_images = $options['after_images'];
		
		echo $before_widget . $before_title . $title . $after_title . $before_images;
		get_linkCoupon();
		echo $after_images . $after_widget;
	}

	function widget_linkCoupon_control() {
		$options = get_option('widget_linkCoupon');

		if ( $_POST['linkCoupon-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['linkCoupon-title']));
			$options['before_images'] = $_POST['linkCoupon-beforeimages'];
			$options['after_images'] = $_POST['linkCoupon-afterimages'];
			update_option('widget_linkCoupon', $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$before_images = htmlspecialchars($options['before_images'], ENT_QUOTES);
		$after_images = htmlspecialchars($options['after_images'], ENT_QUOTES);
		
		echo '<p style="text-align:right;"><label for="linkCoupon-title">Title: <input style="width: 180px;" id="gsearch-title" name="linkCoupon-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="linkCoupon-beforeimages">Before all images: <input style="width: 180px;" id="linkCoupon-beforeimages" name="linkCoupon-beforeimages" type="text" value="'.$before_images.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="linkCoupon-afterimages">After all images: <input style="width: 180px;" id="linkCoupon-afterimages" name="linkCoupon-afterimages" type="text" value="'.$after_images.'" /></label></p>';
		echo '<input type="hidden" id="linkCoupon-submit" name="linkCoupon-submit" value="1" />';
	}		

	register_sidebar_widget('linkCoupon', 'widget_linkCoupon');
	register_widget_control('linkCoupon', 'widget_linkCoupon_control', 300, 100);
}

function linkCoupon_subpanel() {
     if (isset($_POST['save_linkCoupon_settings'])) {
       $option_linkshareid = $_POST['linkshare_id'];
       $option_display_merchant = $_POST['display_merchant'];
       $option_display_numitems = $_POST['display_numitems'];
       $option_display_category = $_POST['display_category'];
       $option_display_promo = $_POST['display_promo'];
       $option_display_network = $_POST['display_network'];
       $option_headline = $_POST['display_headline'];
 
       update_option('linkCoupon_linkshareid', $option_linkshareid);
       update_option('linkCoupon_display_merchant', $option_display_merchant);
       update_option('linkCoupon_display_numitems', $option_display_numitems);
       update_option('linkCoupon_display_category', $option_display_category);
       update_option('linkCoupon_display_promo', $option_display_promo);
       update_option('linkCoupon_display_network', $option_display_network);
       update_option('linkCoupon_headline', $option_headline);
       
       /// RSS stuff
 
       $option_display_type = $_POST['display_type'];
         
       $option_display_numRSSitems = $_POST['display_numRSSitems'];
       $option_RSSHeadline = $_POST['RSSHeadline'];
       update_option('linkshareRSS_RSSHeadline', $option_RSSHeadline);
       update_option('LinkshareRSS_display_type', $option_display_type);
       update_option('linkshareRSS_display_numRSSitems', $option_display_numRSSitems);
 
 
 
       ?> <div class="updated"><p>LinkShare settings saved</p></div> <?php
     }

	?>
<script type="text/javascript" src="../wp-content/plugins/linksharerss-ads/LinkshareRSSCoupon.js"></script>
	<div class="wrap">
		<h2>LinkShare Dealfeed</h2>
		
		<form method="post">
		<table class="form-table">
		 <tr valign="top" >
		  <th scope="row"><h3> Token</h3></th>
	      <td><input onchange="loadMerchants();" name="linkshare_id" type="text" id="linkshare_id" value="<?php echo get_option('linkCoupon_linkshareid'); ?>" size="64" />
        		Get your LinkShare token from  <a href="http://cli.linksynergy.com/cli/publisher/links/webServices.php" target="_blank">here</a> </p></td>
         </tr>
          <tr > <td><div style="padding:15px 2px 2px 2px;"> </div> </td><td> Note: The coupon and
RSS Deal Feed pull from different data sources.  You could display
information from one or both feeds. </td></tr>
 
   <tr > <th> <h3> Coupon Feed </h3><br> You must apply for the coupon feed separately <a href=" http://cli.linksynergy.com/cli/publisher/links/webServices.php?serviceID=21"> here.</a></th><td><i> Insert these deals by including the function call <tt>get_linkshareCoupons() </tt> in your template. A common location is the file sidebar.php. </td></tr>
 
         <tr valign="top" >
          <th scope="row">  Choose a Merchant for Coupons  </th>
          <td>
        	<select name="display_merchant" id="display_merchant"> 
<option <?php if(get_option('linkCoupon_display_merchant') == '0') { echo 'selected'; } ?> value="0">- All Merchants - </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35761') { echo 'selected'; } ?> value="35761">1-800-BASKETS.COM</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '773') { echo 'selected'; } ?> value="773">1-800-FLOWERS.COM</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2101') { echo 'selected'; } ?> value="2101">1-800-PetMeds</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3085') { echo 'selected'; } ?> value="3085">3balls Golf</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3248') { echo 'selected'; } ?> value="3248">4 All Memory</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24611') { echo 'selected'; } ?> value="24611">49ers.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35785') { echo 'selected'; } ?> value="35785">4Imprint CA</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35932') { echo 'selected'; } ?> value="35932">A Matter of Fax</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35916') { echo 'selected'; } ?> value="35916">Abahna</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35729') { echo 'selected'; } ?> value="35729">ABC Distributing LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35431') { echo 'selected'; } ?> value="35431">ABetterStay.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35502') { echo 'selected'; } ?> value="35502">Acadient and Boston Institute of Finance</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24315') { echo 'selected'; } ?> value="24315">Ace Hardware </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35678') { echo 'selected'; } ?> value="35678">Adorn.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35968') { echo 'selected'; } ?> value="35968">AHAVA</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35750') { echo 'selected'; } ?> value="35750">Alford and Hoff</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24390') { echo 'selected'; } ?> value="24390">Alibris UK: Books, Music, & Movies</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2653') { echo 'selected'; } ?> value="2653">Alibris: Books, Music, & Movies</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35863') { echo 'selected'; } ?> value="35863">AllergyStore.com (drugstore.com)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13504') { echo 'selected'; } ?> value="13504">Alloy.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35606') { echo 'selected'; } ?> value="35606">Always At Market Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35814') { echo 'selected'; } ?> value="35814">Amanda Wakeley</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24752') { echo 'selected'; } ?> value="24752">AmericaRx.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24404') { echo 'selected'; } ?> value="24404">AmeriMark.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35900') { echo 'selected'; } ?> value="35900">Andrew Christian</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35520') { echo 'selected'; } ?> value="35520">Angara Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35713') { echo 'selected'; } ?> value="35713">Anna Scholz Ltd</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35995') { echo 'selected'; } ?> value="35995">Antique Rivet</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35437') { echo 'selected'; } ?> value="35437">Apothica LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3176') { echo 'selected'; } ?> value="3176">Appetizerstogo.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35504') { echo 'selected'; } ?> value="35504">Apple Bottoms (eFashions)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13508') { echo 'selected'; } ?> value="13508">Apple iTunes</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35821') { echo 'selected'; } ?> value="35821">Aqua Superstore</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35757') { echo 'selected'; } ?> value="35757">Artbox.co.uk</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24250') { echo 'selected'; } ?> value="24250">Artful Home</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24489') { echo 'selected'; } ?> value="24489">Artistic Labels</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24823') { echo 'selected'; } ?> value="24823">As We Change</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35719') { echo 'selected'; } ?> value="35719">ASOS.com USA</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35173') { echo 'selected'; } ?> value="35173">Aspinal of London (US)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13798') { echo 'selected'; } ?> value="13798">AT&T</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35804') { echo 'selected'; } ?> value="35804">athisbest.com (drugstore.com, inc)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13883') { echo 'selected'; } ?> value="13883">Atom Entertainment (formerly AtomShockwave)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35682') { echo 'selected'; } ?> value="35682">Austique</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35207') { echo 'selected'; } ?> value="35207">Auto Parts Warehouse</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24953') { echo 'selected'; } ?> value="24953">Autoparts123.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14005') { echo 'selected'; } ?> value="14005">Autos.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '122') { echo 'selected'; } ?> value="122">Avon</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13828') { echo 'selected'; } ?> value="13828">Avon Canada</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24346') { echo 'selected'; } ?> value="24346">Avon UK</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24312') { echo 'selected'; } ?> value="24312">B&T Factory Direct</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35490') { echo 'selected'; } ?> value="35490">B2C Jewels </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24243') { echo 'selected'; } ?> value="24243">Babies R Us</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35505') { echo 'selected'; } ?> value="35505">Baby Phat (eFashion Solutions)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35868') { echo 'selected'; } ?> value="35868">baby star</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24618') { echo 'selected'; } ?> value="24618">Baby Universe</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35531') { echo 'selected'; } ?> value="35531">Backyard Ocean</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14063') { echo 'selected'; } ?> value="14063">Bake Me  A Wish</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35141') { echo 'selected'; } ?> value="35141">Balsam Hill LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35361') { echo 'selected'; } ?> value="35361">"Bangalla - TuckerBags">') { echo 'selected'; } ?> value=""</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1623') { echo 'selected'; } ?> value="1623">Bare Necessities</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35716') { echo 'selected'; } ?> value="35716">BD Wedding Favors</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35150') { echo 'selected'; } ?> value="35150">Beautorium.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3002') { echo 'selected'; } ?> value="3002">Beauty.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35617') { echo 'selected'; } ?> value="35617">BeCheeky.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35433') { echo 'selected'; } ?> value="35433">BedHead Pajamas</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24862') { echo 'selected'; } ?> value="24862">Benefit Cosmetics (UK)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24765') { echo 'selected'; } ?> value="24765">Benefit Cosmetics LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35371') { echo 'selected'; } ?> value="35371">Beyond the Rack</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13745') { echo 'selected'; } ?> value="13745">Bidz, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35731') { echo 'selected'; } ?> value="35731">Black.co.uk</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3432') { echo 'selected'; } ?> value="3432">Blinds.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24827') { echo 'selected'; } ?> value="24827">Bliss World, LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35724') { echo 'selected'; } ?> value="35724">Blitz Sport</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13867') { echo 'selected'; } ?> value="13867">Bloomingdale's</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3026') { echo 'selected'; } ?> value="3026">Blooms Today </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35471') { echo 'selected'; } ?> value="35471">Blossom Mother and Child Ltd</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35189') { echo 'selected'; } ?> value="35189">blueinc.co.uk</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35154') { echo 'selected'; } ?> value="35154">Bobbi Brown Cosmetics</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24223') { echo 'selected'; } ?> value="24223">Boca Java, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35662') { echo 'selected'; } ?> value="35662">Brastop Limited</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35118') { echo 'selected'; } ?> value="35118">Browns Fashion</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2652') { echo 'selected'; } ?> value="2652">Buckle.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24419') { echo 'selected'; } ?> value="24419">Cafe Britt</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1165') { echo 'selected'; } ?> value="1165">Cambridge SoundWorks</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3332') { echo 'selected'; } ?> value="3332">Camping World</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35613') { echo 'selected'; } ?> value="35613">CarRentals, LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24572') { echo 'selected'; } ?> value="24572">Cascio Interstate Music</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24221') { echo 'selected'; } ?> value="24221">Casual Living</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25026') { echo 'selected'; } ?> value="25026">Casual Male Retail Group Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13922') { echo 'selected'; } ?> value="13922">Casual Male XL</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24318') { echo 'selected'; } ?> value="24318">Cath Kidston Ltd.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35233') { echo 'selected'; } ?> value="35233">CCA Occasions Ltd. UK</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35889') { echo 'selected'; } ?> value="35889">CCL COMPUTERS LIMITED</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13824') { echo 'selected'; } ?> value="13824">CCS.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35907') { echo 'selected'; } ?> value="35907">Champions On Display</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24515') { echo 'selected'; } ?> value="24515">Champs Sports</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3307') { echo 'selected'; } ?> value="3307">Charles Tyrwhitt</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24302') { echo 'selected'; } ?> value="24302">Charles Tyrwhitt UK</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2291') { echo 'selected'; } ?> value="2291">Chase (JPMorgan Chase & Co.)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25020') { echo 'selected'; } ?> value="25020">CheapOair.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35743') { echo 'selected'; } ?> value="35743">CheapOstay</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2836') { echo 'selected'; } ?> value="2836">CheapTickets</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14089') { echo 'selected'; } ?> value="14089">Chemistry.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35564') { echo 'selected'; } ?> value="35564">Cherrybrook</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13875') { echo 'selected'; } ?> value="13875">Cheryl & Co. </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25000') { echo 'selected'; } ?> value="25000">Chico's </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35152') { echo 'selected'; } ?> value="35152">Chocolate Covered Company, Incredible Berries, Indulged</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35277') { echo 'selected'; } ?> value="35277">Chocolate.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24928') { echo 'selected'; } ?> value="24928">Christopher & Banks</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35472') { echo 'selected'; } ?> value="35472">Chronicle Books</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24929') { echo 'selected'; } ?> value="24929">CJ Banks</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24775') { echo 'selected'; } ?> value="24775">Clinique Online</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24395') { echo 'selected'; } ?> value="24395">CoastalContacts.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35824') { echo 'selected'; } ?> value="35824">Coffees of Hawaii</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2293') { echo 'selected'; } ?> value="2293">Colorful Images</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35227') { echo 'selected'; } ?> value="35227">Comodo </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24845') { echo 'selected'; } ?> value="24845">CompUSA</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2488') { echo 'selected'; } ?> value="2488">Computers4SURE (4SURE.com - An Office Depot Co.)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24676') { echo 'selected'; } ?> value="24676">CookiesKids </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35571') { echo 'selected'; } ?> value="35571">Cost Plus World Market</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35337') { echo 'selected'; } ?> value="35337">Country Store Catalog </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35537') { echo 'selected'; } ?> value="35537">Crocs, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35941') { echo 'selected'; } ?> value="35941">Crutchfield Corporation</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35269') { echo 'selected'; } ?> value="35269">Cult Beauty Ltd.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35442') { echo 'selected'; } ?> value="35442">CurlFriends </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24490') { echo 'selected'; } ?> value="24490">Current Catalog</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24488') { echo 'selected'; } ?> value="24488">Current Labels</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2792') { echo 'selected'; } ?> value="2792">Cutter and Buck, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35769') { echo 'selected'; } ?> value="35769">CWI Medical</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2337') { echo 'selected'; } ?> value="2337">David's Cookies (Fairfield Gourmet Foods Corporation)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35923') { echo 'selected'; } ?> value="35923">Decorative Product Source</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '768') { echo 'selected'; } ?> value="768">dELiA*s</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3178') { echo 'selected'; } ?> value="3178">Dell Canada Inc</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35660') { echo 'selected'; } ?> value="35660">Delta Force Paintball</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35507') { echo 'selected'; } ?> value="35507">Dereon (eFashion Solutions)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1787') { echo 'selected'; } ?> value="1787">DERMAdoctor.com, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35158') { echo 'selected'; } ?> value="35158">Designers Guild Ltd.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24918') { echo 'selected'; } ?> value="24918">DHC Skincare</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35479') { echo 'selected'; } ?> value="35479">DHC Skincare Canada</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35262') { echo 'selected'; } ?> value="35262">DHC Skincare UK</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3200') { echo 'selected'; } ?> value="3200">Diamonds International</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35825') { echo 'selected'; } ?> value="35825">DinoDirect</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35157') { echo 'selected'; } ?> value="35157">diViene.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35349') { echo 'selected'; } ?> value="35349">Doheny's Water Warehouse LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35267') { echo 'selected'; } ?> value="35267">Dollar Rent-a-Car, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2762') { echo 'selected'; } ?> value="2762">drugstore.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35658') { echo 'selected'; } ?> value="35658">drugstore.com, inc. (Canada Program)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35707') { echo 'selected'; } ?> value="35707">drugstore.com, inc. (sexual well being Program)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24664') { echo 'selected'; } ?> value="24664">Duke Diet</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14129') { echo 'selected'; } ?> value="14129">DunhamsSports.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3342') { echo 'selected'; } ?> value="3342">eastbay.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35536') { echo 'selected'; } ?> value="35536">EASTWESTWORLDWIDE.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24520') { echo 'selected'; } ?> value="24520">Easy Comforts (Miles Kimball Company)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25040') { echo 'selected'; } ?> value="25040">eBags, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35212') { echo 'selected'; } ?> value="35212">Electronics-Expo.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35748') { echo 'selected'; } ?> value="35748">Elegance Fashion and Design UK Ltd </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24791') { echo 'selected'; } ?> value="24791">Elemis</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1695') { echo 'selected'; } ?> value="1695">Enterprise Rent-A-Car</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35787') { echo 'selected'; } ?> value="35787">Equal Exchange, Inc </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13914') { echo 'selected'; } ?> value="13914">ESPN Shop</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25038') { echo 'selected'; } ?> value="25038">eSportsonline</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3446') { echo 'selected'; } ?> value="3446">etoys.com (GSI)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24517') { echo 'selected'; } ?> value="24517">Exposures (Miles Kimball Company)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24184') { echo 'selected'; } ?> value="24184">Extended Stay Hotels</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35628') { echo 'selected'; } ?> value="35628">FADS</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35721') { echo 'selected'; } ?> value="35721">Faith Shoe Group Ltd</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35501') { echo 'selected'; } ?> value="35501">Fanzz.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35860') { echo 'selected'; } ?> value="35860">FAO Schwarz</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35482') { echo 'selected'; } ?> value="35482">Fashion Union</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13568') { echo 'selected'; } ?> value="13568">FastFloors</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24809') { echo 'selected'; } ?> value="24809">FavorAffair.com (The Shops at 24Seven)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35557') { echo 'selected'; } ?> value="35557">Feelfit Ltd.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35779') { echo 'selected'; } ?> value="35779">Fenn Wright Manson </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2984') { echo 'selected'; } ?> value="2984">Figis, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35940') { echo 'selected'; } ?> value="35940">FIJI Water Company</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3437') { echo 'selected'; } ?> value="3437">Fingerhut Direct Marketing, Inc</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24911') { echo 'selected'; } ?> value="24911">Firebugdiamonds.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24364') { echo 'selected'; } ?> value="24364">Fleurop-Interflora EBC AG</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14154') { echo 'selected'; } ?> value="14154">Fleurop.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3382') { echo 'selected'; } ?> value="3382">Florsheim</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35935') { echo 'selected'; } ?> value="35935">Focus28, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35196') { echo 'selected'; } ?> value="35196">Foot Action (Footlocker)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35727') { echo 'selected'; } ?> value="35727">Foot Petals</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3071') { echo 'selected'; } ?> value="3071">Footlocker.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25033') { echo 'selected'; } ?> value="25033">Footnote.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35967') { echo 'selected'; } ?> value="35967">Footwear etc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1805') { echo 'selected'; } ?> value="1805">FORZIERI.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35809') { echo 'selected'; } ?> value="35809">Fossil Partners, L.P.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35802') { echo 'selected'; } ?> value="35802">Fossil UK Ltd  </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '216') { echo 'selected'; } ?> value="216">FragranceNet.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1263') { echo 'selected'; } ?> value="1263">Franklin Mint</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13529') { echo 'selected'; } ?> value="13529">Frederick's of Hollywood, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24990') { echo 'selected'; } ?> value="24990">French Connection Limited</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24922') { echo 'selected'; } ?> value="24922">FTPress.com (Pearson Education)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2208') { echo 'selected'; } ?> value="2208">Fujitsu America, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35193') { echo 'selected'; } ?> value="35193">Funny T Shirts Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24416') { echo 'selected'; } ?> value="24416">fye.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13544') { echo 'selected'; } ?> value="13544">Gaiam - As Seen on TV</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24674') { echo 'selected'; } ?> value="24674">Gaiam Subscription Clubs</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2311') { echo 'selected'; } ?> value="2311">Gaiam.com, Inc</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25015') { echo 'selected'; } ?> value="25015">Gameduell Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '982') { echo 'selected'; } ?> value="982">Gardener's Supply Company</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35735') { echo 'selected'; } ?> value="35735">Gene Smart Wellness</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35675') { echo 'selected'; } ?> value="35675">Gettington </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35424') { echo 'selected'; } ?> value="35424">Getzs.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1716') { echo 'selected'; } ?> value="1716">GiftBaskets.com, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1493') { echo 'selected'; } ?> value="1493">GigaGolf, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24568') { echo 'selected'; } ?> value="24568">giggle</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35723') { echo 'selected'; } ?> value="35723">Glassesshop.com, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14033') { echo 'selected'; } ?> value="14033">GNC / General Nutrition Centers</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35784') { echo 'selected'; } ?> value="35784">GO SMiLE</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35741') { echo 'selected'; } ?> value="35741">Golden State Fruit</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24605') { echo 'selected'; } ?> value="24605">Goldsmiths Ltd.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35710') { echo 'selected'; } ?> value="35710">Goldsmiths Outlet </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2119') { echo 'selected'; } ?> value="2119">Golfballs.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24988') { echo 'selected'; } ?> value="24988">Goodwill Too</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24503') { echo 'selected'; } ?> value="24503">Gordon's Jewelers</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24671') { echo 'selected'; } ?> value="24671">GourmetGiftBaskets.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35419') { echo 'selected'; } ?> value="35419">Greensbury Market</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2637') { echo 'selected'; } ?> value="2637">Guthy Renker Corporation</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35806') { echo 'selected'; } ?> value="35806">Hamleys of London Ltd</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24434') { echo 'selected'; } ?> value="24434">HandHelditems</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24366') { echo 'selected'; } ?> value="24366">Hanes.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35576') { echo 'selected'; } ?> value="35576">Hanna Andersson</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24452') { echo 'selected'; } ?> value="24452">Hatley </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13988') { echo 'selected'; } ?> value="13988">Hawaiian Airlines</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35947') { echo 'selected'; } ?> value="35947">Health to Happiness </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2920') { echo 'selected'; } ?> value="2920">HearthSong</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2948') { echo 'selected'; } ?> value="2948">Hello Direct, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24193') { echo 'selected'; } ?> value="24193">HerRoom</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '732') { echo 'selected'; } ?> value="732">Hickory Farms</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3436') { echo 'selected'; } ?> value="3436">Highlights for Children</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24954') { echo 'selected'; } ?> value="24954">HisRoom</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35133') { echo 'selected'; } ?> value="35133">Holiday Classics </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24525') { echo 'selected'; } ?> value="24525">Holland USA, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35906') { echo 'selected'; } ?> value="35906">Home Furniture Land</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24213') { echo 'selected'; } ?> value="24213">Homestead an Intuit Co.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35241') { echo 'selected'; } ?> value="35241">Honest Florist</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25004') { echo 'selected'; } ?> value="25004">Horchow.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35583') { echo 'selected'; } ?> value="35583">HostelBookers.com Ltd. (Canada Program)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35649') { echo 'selected'; } ?> value="35649">Hotelclub.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2043') { echo 'selected'; } ?> value="2043">Hotwire </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24751') { echo 'selected'; } ?> value="24751">House of Fraser Ltd</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13602') { echo 'selected'; } ?> value="13602">Hudson Reed</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35745') { echo 'selected'; } ?> value="35745">iBuyOfficesupply.ca</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35702') { echo 'selected'; } ?> value="35702">iBuyOfficesupply.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1110') { echo 'selected'; } ?> value="1110">ICE.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24594') { echo 'selected'; } ?> value="24594">Identity Direct</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24592') { echo 'selected'; } ?> value="24592">Identity Direct UK</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35773') { echo 'selected'; } ?> value="35773">ihampers.co.uk</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13585') { echo 'selected'; } ?> value="13585">Improvement Direct</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35651') { echo 'selected'; } ?> value="35651">In The Hole Golf</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24808') { echo 'selected'; } ?> value="24808">InformIT (Pearson Education)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14109') { echo 'selected'; } ?> value="14109">InstantCarLoan.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35815') { echo 'selected'; } ?> value="35815">Instawares LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2065') { echo 'selected'; } ?> value="2065">Internet Florist</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35833') { echo 'selected'; } ?> value="35833">Internet MegaMeeting, LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2898') { echo 'selected'; } ?> value="2898">Interstate Batteries.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35224') { echo 'selected'; } ?> value="35224">Intrepid Travel (Intrepid Guerba)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35135') { echo 'selected'; } ?> value="35135">Invitations by Dawn Canada</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25039') { echo 'selected'; } ?> value="25039">Iolo technologies, LLC </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35151') { echo 'selected'; } ?> value="35151">Isabella Oliver Ltd </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35383') { echo 'selected'; } ?> value="35383">Isabella Oliver Ltd. (Canada)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35382') { echo 'selected'; } ?> value="35382">Isabella Oliver Ltd. (US)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35597') { echo 'selected'; } ?> value="35597">iTeleCenter (COA Network)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24553') { echo 'selected'; } ?> value="24553">iWin, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1145') { echo 'selected'; } ?> value="1145">J&R Computer/Music World</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35491') { echo 'selected'; } ?> value="35491">Jaeger</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '788') { echo 'selected'; } ?> value="788">JCPenney</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35264') { echo 'selected'; } ?> value="35264">Jelly Belly</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '781') { echo 'selected'; } ?> value="781">JewelryWeb.com Inc</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24666') { echo 'selected'; } ?> value="24666">Jillian Michaels</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14131') { echo 'selected'; } ?> value="14131">Joe's Sports</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25112') { echo 'selected'; } ?> value="25112">JoyBauer.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35469') { echo 'selected'; } ?> value="35469">JUARA Skincare</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13997') { echo 'selected'; } ?> value="13997">Just Because Baskets</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1697') { echo 'selected'; } ?> value="1697">Kaplan Test Prep and Admissions (Kaptest.com)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24797') { echo 'selected'; } ?> value="24797">Karen Millen Fashions Limited</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13930') { echo 'selected'; } ?> value="13930">Karmaloop.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35611') { echo 'selected'; } ?> value="35611">Keebra</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35226') { echo 'selected'; } ?> value="35226">King.com (Midasplayer.com Ltd.)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35332') { echo 'selected'; } ?> value="35332">Kipling USA </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25047') { echo 'selected'; } ?> value="25047">Kitchen Universe LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35530') { echo 'selected'; } ?> value="35530">KL Sport </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3348') { echo 'selected'; } ?> value="3348">Knetgolf.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35522') { echo 'selected'; } ?> value="35522">Knewton, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24863') { echo 'selected'; } ?> value="24863">Kosher.com </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35588') { echo 'selected'; } ?> value="35588">Kuati Ltd.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24803') { echo 'selected'; } ?> value="24803">Kurt Geiger Ltd. </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13979') { echo 'selected'; } ?> value="13979">L'Occitane</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24359') { echo 'selected'; } ?> value="24359">L'Occitane UK</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2808') { echo 'selected'; } ?> value="2808">La Quinta Corporation</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24972') { echo 'selected'; } ?> value="24972">Lab Series</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25028') { echo 'selected'; } ?> value="25028">Lakeside Collection</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24278') { echo 'selected'; } ?> value="24278">LampsPlus.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25098') { echo 'selected'; } ?> value="25098">Lancome Canada</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35677') { echo 'selected'; } ?> value="35677">Lands End UK</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1189') { echo 'selected'; } ?> value="1189">Lands' End Inc</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13505') { echo 'selected'; } ?> value="13505">Lane Bryant</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24450') { echo 'selected'; } ?> value="24450">Laura Ashley Ltd</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35251') { echo 'selected'; } ?> value="35251">Lavera Skin Care</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35870') { echo 'selected'; } ?> value="35870">Layla Grayce </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35827') { echo 'selected'; } ?> value="35827">Leanin Tree</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24575') { echo 'selected'; } ?> value="24575">LeapFrog Enterprises Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13923') { echo 'selected'; } ?> value="13923">LEGO Brand Retail</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24340') { echo 'selected'; } ?> value="24340">LEGO Company Ltd</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35689') { echo 'selected'; } ?> value="35689">Leonisa</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24432') { echo 'selected'; } ?> value="24432">Lighting By Gregory</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2305') { echo 'selected'; } ?> value="2305">Lillian Vernon Online</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2142') { echo 'selected'; } ?> value="2142">Limoges Jewelry</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24222') { echo 'selected'; } ?> value="24222">LinenSource, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35701') { echo 'selected'; } ?> value="35701">Lingerie.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24969') { echo 'selected'; } ?> value="24969">Liquidation Channel</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24532') { echo 'selected'; } ?> value="24532">Livingxl.com </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35767') { echo 'selected'; } ?> value="35767">LOMBOK</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35728') { echo 'selected'; } ?> value="35728">LTD Commodities LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24194') { echo 'selected'; } ?> value="24194">Lucky Brand Jeans</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35624') { echo 'selected'; } ?> value="35624">Lucy Activewear</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1565') { echo 'selected'; } ?> value="1565">Luggage OnLine</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24290') { echo 'selected'; } ?> value="24290">Lumber Liquidators</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3184') { echo 'selected'; } ?> value="3184">macys.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2904') { echo 'selected'; } ?> value="2904">Magazineline.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1154') { echo 'selected'; } ?> value="1154">Magazines.com, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35674') { echo 'selected'; } ?> value="35674">MagicKitchen.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25065') { echo 'selected'; } ?> value="25065">Manhattanite</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35394') { echo 'selected'; } ?> value="35394">Marie-Chantal Ltd</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35934') { echo 'selected'; } ?> value="35934">MarketingProfs</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24937') { echo 'selected'; } ?> value="24937">Martha Stewart for 1-800-Flowers.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13668') { echo 'selected'; } ?> value="13668">Match.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35725') { echo 'selected'; } ?> value="35725">Matches</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35747') { echo 'selected'; } ?> value="35747">Maurices</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14125') { echo 'selected'; } ?> value="14125">MC Sports</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35489') { echo 'selected'; } ?> value="35489">McAfee Canada</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1106') { echo 'selected'; } ?> value="1106">McAfee, Inc</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35635') { echo 'selected'; } ?> value="35635">Memorable Gifts</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13622') { echo 'selected'; } ?> value="13622">MenScience</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3192') { echo 'selected'; } ?> value="3192">Mercantila</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13882') { echo 'selected'; } ?> value="13882">MetroPCS, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24542') { echo 'selected'; } ?> value="24542">Microsoft Store</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24516') { echo 'selected'; } ?> value="24516">Miles Kimball Company</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35883') { echo 'selected'; } ?> value="35883">Miss Selfridge </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24973') { echo 'selected'; } ?> value="24973">MLB.com Shop</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35933') { echo 'selected'; } ?> value="35933">Modern Furniture Warehouse</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2692') { echo 'selected'; } ?> value="2692">Mondera.com, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35642') { echo 'selected'; } ?> value="35642">Monster Parties Ltd</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13956') { echo 'selected'; } ?> value="13956">Mountain Gear, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1479') { echo 'selected'; } ?> value="1479">Mrs. Fields Gifts, Inc</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35171') { echo 'selected'; } ?> value="35171">MrWatch</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35764') { echo 'selected'; } ?> value="35764">MTV Networks, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35483') { echo 'selected'; } ?> value="35483">Music Factory Direct</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13770') { echo 'selected'; } ?> value="13770">Musicnotes.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24266') { echo 'selected'; } ?> value="24266">MusicSpace.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13946') { echo 'selected'; } ?> value="13946">My Wines Direct</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24804') { echo 'selected'; } ?> value="24804">Myflavia.com (Mars Drinks North America, LLC)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35137') { echo 'selected'; } ?> value="35137">MyJean M </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25070') { echo 'selected'; } ?> value="25070">Napster, LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14121') { echo 'selected'; } ?> value="14121">NASCAR Superstore</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2456') { echo 'selected'; } ?> value="2456">National Business Furniture, Inc</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35496') { echo 'selected'; } ?> value="35496">National Wildlife Federation </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35331') { echo 'selected'; } ?> value="35331">Nautica Retail USA, Inc. </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14080') { echo 'selected'; } ?> value="14080">NBAStore.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24198') { echo 'selected'; } ?> value="24198">NFLShop.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35445') { echo 'selected'; } ?> value="35445">Nick Chavez Beverly Hills</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14120') { echo 'selected'; } ?> value="14120">Nickelodeon Shop</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35216') { echo 'selected'; } ?> value="35216">Noodle & Boo, LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1237') { echo 'selected'; } ?> value="1237">NORDSTROM.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35828') { echo 'selected'; } ?> value="35828">Nu-Kitchen</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35127') { echo 'selected'; } ?> value="35127">Nunn Bush</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3454') { echo 'selected'; } ?> value="3454">NutriSystem, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24798') { echo 'selected'; } ?> value="24798">Oasis Fashions Limited</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25092') { echo 'selected'; } ?> value="25092">OCInkjet.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '926') { echo 'selected'; } ?> value="926">Office Depot, Inc</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1519') { echo 'selected'; } ?> value="1519">Officefurniture.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35352') { echo 'selected'; } ?> value="35352">Oliver Bonas Ltd</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '147') { echo 'selected'; } ?> value="147">OmahaSteaks.com, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35420') { echo 'selected'; } ?> value="35420">OneTravel.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14152') { echo 'selected'; } ?> value="14152">Onlineshoes.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35510') { echo 'selected'; } ?> value="35510">Orange County Choppers (eFashion Solutions)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2194') { echo 'selected'; } ?> value="2194">Orbitz</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24776') { echo 'selected'; } ?> value="24776">Origins Online</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35379') { echo 'selected'; } ?> value="35379">OrionGadgets.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2829') { echo 'selected'; } ?> value="2829">Palmbeachjewelry.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '775') { echo 'selected'; } ?> value="775">Paul Fredrick MenStyle</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24509') { echo 'selected'; } ?> value="24509">PCSecurityShield</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24921') { echo 'selected'; } ?> value="24921">PeachPit (Pearson Education)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35740') { echo 'selected'; } ?> value="35740">Peapod LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35132') { echo 'selected'; } ?> value="35132">Pear Tree Greetings </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35632') { echo 'selected'; } ?> value="35632">Perfectmatch.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3434') { echo 'selected'; } ?> value="3434">Perfume Worldwide, Inc</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2071') { echo 'selected'; } ?> value="2071">Personal Creations</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3346') { echo 'selected'; } ?> value="3346">PersonalizationMall.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35939') { echo 'selected'; } ?> value="35939">PetMountain.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13565') { echo 'selected'; } ?> value="13565">PetSmart</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24653') { echo 'selected'; } ?> value="24653">PexSupply</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35512') { echo 'selected'; } ?> value="35512">Phat Farm (eFashion Solutions)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35800') { echo 'selected'; } ?> value="35800">PhotoWorks</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13970') { echo 'selected'; } ?> value="13970">Pingo</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '939') { echo 'selected'; } ?> value="939">Pitney Bowes, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35925') { echo 'selected'; } ?> value="35925">PJ Outlet</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2848') { echo 'selected'; } ?> value="2848">Plow & Hearth </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35582') { echo 'selected'; } ?> value="35582">Pool Cue Guru</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35148') { echo 'selected'; } ?> value="35148">Popcornopolis</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35321') { echo 'selected'; } ?> value="35321">PowerScore, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24777') { echo 'selected'; } ?> value="24777">Prescriptives</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25077') { echo 'selected'; } ?> value="25077">PrintMyThing.com (Century Marketing)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35302') { echo 'selected'; } ?> value="35302">PrintRunner.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35119') { echo 'selected'; } ?> value="35119">Proboardshop (Active Sports)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14144') { echo 'selected'; } ?> value="14144">ProGolf.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1065') { echo 'selected'; } ?> value="1065">PromGirl</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35503') { echo 'selected'; } ?> value="35503">Pulse Telecom LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24352') { echo 'selected'; } ?> value="24352">Pure Collection Ltd.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24591') { echo 'selected'; } ?> value="24591">PurelyGadgets </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24196') { echo 'selected'; } ?> value="24196">Puritan's Pride</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35605') { echo 'selected'; } ?> value="35605">Quiz Clothing</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13974') { echo 'selected'; } ?> value="13974">RadioShack</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35715') { echo 'selected'; } ?> value="35715">Rawlings Gear</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24930') { echo 'selected'; } ?> value="24930">Real Goods Solar, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35771') { echo 'selected'; } ?> value="35771">Red Ruby Rouge Ltd</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24754') { echo 'selected'; } ?> value="24754">RefurbDepot.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1825') { echo 'selected'; } ?> value="1825">Register.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2750') { echo 'selected'; } ?> value="2750">Relax The Back</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35519') { echo 'selected'; } ?> value="35519">Ren Ltd.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35739') { echo 'selected'; } ?> value="35739">Right Start</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35372') { echo 'selected'; } ?> value="35372">RiseSmart Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35513') { echo 'selected'; } ?> value="35513">Rocawear (eFashion Solutions)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25027') { echo 'selected'; } ?> value="25027">Rochester Clothing UK</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35386') { echo 'selected'; } ?> value="35386">Rock Bottom Golf</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14130') { echo 'selected'; } ?> value="14130">Rockport.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24291') { echo 'selected'; } ?> value="24291">Roots Canada Ltd.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3096') { echo 'selected'; } ?> value="3096">Roots USA</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24176') { echo 'selected'; } ?> value="24176">SA Test Merchant Venue</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13816') { echo 'selected'; } ?> value="13816">Saks Fifth Avenue</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35126') { echo 'selected'; } ?> value="35126">Sam Ash Quikship Corp.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35438') { echo 'selected'; } ?> value="35438">Scientific Learning Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2617') { echo 'selected'; } ?> value="2617">SeaBear Smokehouse</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35514') { echo 'selected'; } ?> value="35514">Sean John (eFashion Solutions)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35793') { echo 'selected'; } ?> value="35793">Secondlife.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14060') { echo 'selected'; } ?> value="14060">SecondSpin.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35786') { echo 'selected'; } ?> value="35786">See's Candies, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35645') { echo 'selected'; } ?> value="35645">Select Bedding</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13904') { echo 'selected'; } ?> value="13904">Select Blinds, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35646') { echo 'selected'; } ?> value="35646">Select Rugs</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35958') { echo 'selected'; } ?> value="35958">Select Specs </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35714') { echo 'selected'; } ?> value="35714">Select-A-Ticket</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1985') { echo 'selected'; } ?> value="1985">Sensational Beginnings</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2417') { echo 'selected'; } ?> value="2417">Sephora.com, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35631') { echo 'selected'; } ?> value="35631">Shambhala Publications Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '815') { echo 'selected'; } ?> value="815">Sharper Image</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35492') { echo 'selected'; } ?> value="35492">Shoes.co.uk Ltd.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35336') { echo 'selected'; } ?> value="35336">Shop Taste of Home</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25008') { echo 'selected'; } ?> value="25008">ShopEcko.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14138') { echo 'selected'; } ?> value="14138">ShopNBC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24228') { echo 'selected'; } ?> value="24228">ShopPBS.Org</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2069') { echo 'selected'; } ?> value="2069">Shutterfly.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2047') { echo 'selected'; } ?> value="2047">Sierra Club</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2640') { echo 'selected'; } ?> value="2640">Sierra Trading Post</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35792') { echo 'selected'; } ?> value="35792">Silver Jeans</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35774') { echo 'selected'; } ?> value="35774">Simple Floors, Inc</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3476') { echo 'selected'; } ?> value="3476">Simply Audiobooks, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35243') { echo 'selected'; } ?> value="35243">Simply Electronics Ltd</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24511') { echo 'selected'; } ?> value="24511">SIRIUS|XM Radio</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24358') { echo 'selected'; } ?> value="24358">SkyMall, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13892') { echo 'selected'; } ?> value="13892">SmartBargains.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1845') { echo 'selected'; } ?> value="1845">Smarthome, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35985') { echo 'selected'; } ?> value="35985">SMobile Systems Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25001') { echo 'selected'; } ?> value="25001">Soma.com (Chico's)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24659') { echo 'selected'; } ?> value="24659">South Beach Diet</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35197') { echo 'selected'; } ?> value="35197">Speedo International Limited </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13638') { echo 'selected'; } ?> value="13638">SpinLife.com, LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24673') { echo 'selected'; } ?> value="24673">Spiritual Cinema Circle</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14119') { echo 'selected'; } ?> value="14119">SportsAuthority.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3383') { echo 'selected'; } ?> value="3383">Stacy Adams</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35627') { echo 'selected'; } ?> value="35627">Star Struck</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2661') { echo 'selected'; } ?> value="2661">Stonewall Kitchen, LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35919') { echo 'selected'; } ?> value="35919">Stylebop GmbH</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35563') { echo 'selected'; } ?> value="35563">Stylebop Ltd.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35711') { echo 'selected'; } ?> value="35711">SuperJeweler.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35205') { echo 'selected'; } ?> value="35205">Sweaty Betty</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3004') { echo 'selected'; } ?> value="3004">SwissOutpost and Swiss Knife Depot</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35669') { echo 'selected'; } ?> value="35669">Swoopo  Entertainment  Shopping, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1617') { echo 'selected'; } ?> value="1617">Szul.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1280') { echo 'selected'; } ?> value="1280">Tactics.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35640') { echo 'selected'; } ?> value="35640">Taymark Anderson's School Spirit</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35639') { echo 'selected'; } ?> value="35639">Taymark Giant Party Store</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35443') { echo 'selected'; } ?> value="35443">Tea Forte, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2504') { echo 'selected'; } ?> value="2504">Tech Depot - An Office Depot Co.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1947') { echo 'selected'; } ?> value="1947">textbookx.com (Akademos, Inc.)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13962') { echo 'selected'; } ?> value="13962">The Body Shop</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35286') { echo 'selected'; } ?> value="35286">The Childrens Wear Outlet</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24518') { echo 'selected'; } ?> value="24518">The Home Marketplace (Miles Kimball Company)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35587') { echo 'selected'; } ?> value="35587">The Karaoke Channel</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35543') { echo 'selected'; } ?> value="35543">The Limited Stores, LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24650') { echo 'selected'; } ?> value="24650">The New York Times Home Delivery </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25086') { echo 'selected'; } ?> value="25086">The North Face</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14112') { echo 'selected'; } ?> value="14112">The Occasions Group</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2901') { echo 'selected'; } ?> value="2901">The Popcorn Factory</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24356') { echo 'selected'; } ?> value="24356">The Savile Row Company</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24308') { echo 'selected'; } ?> value="24308">The White Company</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1674') { echo 'selected'; } ?> value="1674">The Wine Messenger</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25087') { echo 'selected'; } ?> value="25087">The-House (Active Sports)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1142') { echo 'selected'; } ?> value="1142">TheBabyOutlet</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35864') { echo 'selected'; } ?> value="35864">TheNaturalStore.com (drugstore.com)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35290') { echo 'selected'; } ?> value="35290">theOutnet.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35291') { echo 'selected'; } ?> value="35291">theOutnet.com US</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35329') { echo 'selected'; } ?> value="35329">TheTopSecret</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1866') { echo 'selected'; } ?> value="1866">Things Remembered</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35542') { echo 'selected'; } ?> value="35542">Thomas Lyte</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24220') { echo 'selected'; } ?> value="24220">Thompson Cigar</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3088') { echo 'selected'; } ?> value="3088">Thrifty Rent-A-Car System, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14029') { echo 'selected'; } ?> value="14029">TigerDirect (CA)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35575') { echo 'selected'; } ?> value="35575">Tilly's</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25102') { echo 'selected'; } ?> value="25102">TimeForMeCatalog.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3318') { echo 'selected'; } ?> value="3318">TimeLife.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35183') { echo 'selected'; } ?> value="35183">TM Lewin and Sons Ltd.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35174') { echo 'selected'; } ?> value="35174">TM Lewin and Sons Ltd.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35858') { echo 'selected'; } ?> value="35858">TOPMAN</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35859') { echo 'selected'; } ?> value="35859">Topman US</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35838') { echo 'selected'; } ?> value="35838">TOPSHOP</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35861') { echo 'selected'; } ?> value="35861">Topshop  US</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13589') { echo 'selected'; } ?> value="13589">Toshiba - Toshibadirect.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3467') { echo 'selected'; } ?> value="3467">Total Training Online and DVD (Software Training)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24242') { echo 'selected'; } ?> value="24242">Toys R Us</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '14056') { echo 'selected'; } ?> value="14056">Toys R Us Canada</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35230') { echo 'selected'; } ?> value="35230">Tractor Supply Company</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24538') { echo 'selected'; } ?> value="24538">TravelCountry.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24714') { echo 'selected'; } ?> value="24714">TriCityNewBalance.com (Southern Sports LLC)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24995') { echo 'selected'; } ?> value="24995">Trueshopping Ltd </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25046') { echo 'selected'; } ?> value="25046">Turkishtowels.com LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35826') { echo 'selected'; } ?> value="35826">Ultra Diamonds</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1961') { echo 'selected'; } ?> value="1961">UncommonGoods</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24724') { echo 'selected'; } ?> value="24724">UNIQLO</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35732') { echo 'selected'; } ?> value="35732">Universal Calling Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35237') { echo 'selected'; } ?> value="35237">Utrecht Art.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35744') { echo 'selected'; } ?> value="35744">Value Health Card Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13669') { echo 'selected'; } ?> value="13669">Valueline/PM Co. Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24747') { echo 'selected'; } ?> value="24747">Vans,a Division of VF Outdoor, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35880') { echo 'selected'; } ?> value="35880">Variety Theater</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24943') { echo 'selected'; } ?> value="24943">Vera Bradley Designs, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2736') { echo 'selected'; } ?> value="2736">Veterans Advantage, Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35873') { echo 'selected'; } ?> value="35873">Vie At Home Ltd.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35164') { echo 'selected'; } ?> value="35164">Villeroy & Boch Tableware</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3458') { echo 'selected'; } ?> value="3458">Vision Direct</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '1155') { echo 'selected'; } ?> value="1155">Vitacost.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13772') { echo 'selected'; } ?> value="13772">Vital Savings by Aetna</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24550') { echo 'selected'; } ?> value="24550">Vitamin World</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35699') { echo 'selected'; } ?> value="35699">VitaminMenu</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35268') { echo 'selected'; } ?> value="35268">Vitamins Of The Month Inc. </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35418') { echo 'selected'; } ?> value="35418">Wahanda</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2149') { echo 'selected'; } ?> value="2149">Wal-Mart.com USA, LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35953') { echo 'selected'; } ?> value="35953">Wallpaper Direct (C. Brewer & Sons)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35963') { echo 'selected'; } ?> value="35963">Walmart MP3 Music Downloads</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2393') { echo 'selected'; } ?> value="2393">Walt Disney Parks and Resorts Online</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24519') { echo 'selected'; } ?> value="24519">Walter Drake (Miles Kimball Company)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '3470') { echo 'selected'; } ?> value="3470">Waterford, Wedgwood, and Royal Doulton</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35908') { echo 'selected'; } ?> value="35908">Wavee US, LLC</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24709') { echo 'selected'; } ?> value="24709">Webroot Software Inc.</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35898') { echo 'selected'; } ?> value="35898">Wellgosh</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25073') { echo 'selected'; } ?> value="25073">West Coast Capital (USC) LTD</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '25002') { echo 'selected'; } ?> value="25002">White House Black Market  (Chico's)</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35687') { echo 'selected'; } ?> value="35687">Wholesale Furniture Brokers</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35885') { echo 'selected'; } ?> value="35885">Wholesale Sports</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '194') { echo 'selected'; } ?> value="194">Wicks End</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24427') { echo 'selected'; } ?> value="24427">Wilson's Leather</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35720') { echo 'selected'; } ?> value="35720">Wind and Weather</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2025') { echo 'selected'; } ?> value="2025">wine.com</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2274') { echo 'selected'; } ?> value="2274">Wirefly  </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24380') { echo 'selected'; } ?> value="24380">Wolfgang's Vault</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24522') { echo 'selected'; } ?> value="24522">World of Watches</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '24285') { echo 'selected'; } ?> value="24285">YOOX.COM</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35395') { echo 'selected'; } ?> value="35395">YourCover </option>
<option <?php if(get_option('linkCoupon_display_merchant') == '13630') { echo 'selected'; } ?> value="13630">Zales</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '2678') { echo 'selected'; } ?> value="2678">ZIRH</option>
<option <?php if(get_option('linkCoupon_display_merchant') == '35495') { echo 'selected'; } ?> value="35495">Zuneta Ltd.</option>



		    </select> <br>
		 	 (You must be a partner of the Advertiser to display the feed.)<br>
		 	 </td></tr>
		 	  <tr valign="top">
 <th scope="row"> Which Category?</th>
 <td>
<select name="display_category" id="display_category">
<option <?php if(get_option('linkCoupon_display_category') == '0') { echo 'selected'; } ?> value="0">- All Categories -</option>

<option <?php if(get_option('linkCoupon_display_category') == '30') { echo 'selected'; } ?> value="30">Travel & Vacations</option>
<option <?php if(get_option('linkCoupon_display_category') == '29') { echo 'selected'; } ?> value="29">Toys & Games</option>
<option <?php if(get_option('linkCoupon_display_category') == '28') { echo 'selected'; } ?> value="28">Sports & Fitness</option>
<option <?php if(get_option('linkCoupon_display_category') == '27') { echo 'selected'; } ?> value="27">Software & Downloads</option>
<option <?php if(get_option('linkCoupon_display_category') == '26') { echo 'selected'; } ?> value="26">Shoes</option>
<option <?php if(get_option('linkCoupon_display_category') == '25') { echo 'selected'; } ?> value="25">Services</option>
<option <?php if(get_option('linkCoupon_display_category') == '24') { echo 'selected'; } ?> value="24">Pet Care</option>
<option <?php if(get_option('linkCoupon_display_category') == '31') { echo 'selected'; } ?> value="31">Office & Small Business</option>
<option <?php if(get_option('linkCoupon_display_category') == '23') { echo 'selected'; } ?> value="23">Music & Movies</option>
<option <?php if(get_option('linkCoupon_display_category') == '21') { echo 'selected'; } ?> value="21">Jewelry & Accessories</option>
<option <?php if(get_option('linkCoupon_display_category') == '20') { echo 'selected'; } ?> value="20">House wares</option>
<option <?php if(get_option('linkCoupon_display_category') == '19') { echo 'selected'; } ?> value="19">Health & Wellness</option>
<option <?php if(get_option('linkCoupon_display_category') == '18') { echo 'selected'; } ?> value="18">Green Products</option>
<option <?php if(get_option('linkCoupon_display_category') == '17') { echo 'selected'; } ?> value="17">Gourmet Food & Drink</option>
<option <?php if(get_option('linkCoupon_display_category') == '16') { echo 'selected'; } ?> value="16">Gifts</option>
<option <?php if(get_option('linkCoupon_display_category') == '15') { echo 'selected'; } ?> value="15">Garden & Outdoors</option>
<option <?php if(get_option('linkCoupon_display_category') == '14') { echo 'selected'; } ?> value="14">Flowers</option>
<option <?php if(get_option('linkCoupon_display_category') == '13') { echo 'selected'; } ?> value="13">Electronic Equipment</option>
<option <?php if(get_option('linkCoupon_display_category') == '12') { echo 'selected'; } ?> value="12">Department Store</option>
<option <?php if(get_option('linkCoupon_display_category') == '11') { echo 'selected'; } ?> value="11">Dating</option>
<option <?php if(get_option('linkCoupon_display_category') == '10') { echo 'selected'; } ?> value="10">Computers</option>
<option <?php if(get_option('linkCoupon_display_category') == '9') { echo 'selected'; } ?> value="9">Car Rental</option>
<option <?php if(get_option('linkCoupon_display_category') == '8') { echo 'selected'; } ?> value="8">Cameras & Photography</option>
<option <?php if(get_option('linkCoupon_display_category') == '7') { echo 'selected'; } ?> value="7">Books & Magazines</option>
<option <?php if(get_option('linkCoupon_display_category') == '6') { echo 'selected'; } ?> value="6">Beauty & Fragrance</option>
<option <?php if(get_option('linkCoupon_display_category') == '32') { echo 'selected'; } ?> value="32">Babies & Kids</option>
<option <?php if(get_option('linkCoupon_display_category') == '5') { echo 'selected'; } ?> value="5">Automotive</option>
<option <?php if(get_option('linkCoupon_display_category') == '4') { echo 'selected'; } ?> value="4">Apparel - Woman’s</option>
<option <?php if(get_option('linkCoupon_display_category') == '3') { echo 'selected'; } ?> value="3">Apparel - Men’s</option>
<option <?php if(get_option('linkCoupon_display_category') == '2') { echo 'selected'; } ?> value="2">Apparel - Babies & Kids</option>
<option <?php if(get_option('linkCoupon_display_category') == '1') { echo 'selected'; } ?> value="1">Apparel</option>
 </select>
 </td>  </tr>
		 	 
<tr valign="top">
 <th scope="row"> Which Type?</th>
 <td>
<select name="display_promo" id="display_promo">
<option <?php if(get_option('linkCoupon_display_promo') == '0') { echo 'selected'; } ?> value="0">- All Promotion Types -</option>

<option <?php if(get_option('linkCoupon_display_promo') == '11') { echo 'selected'; } ?> value="11">Percentage off</option>
<option <?php if(get_option('linkCoupon_display_promo') == '10') { echo 'selected'; } ?> value="10">Other</option>
<option <?php if(get_option('linkCoupon_display_promo') == '9') { echo 'selected'; } ?> value="9">Gift with Purchase</option>
<option <?php if(get_option('linkCoupon_display_promo') == '1') { echo 'selected'; } ?> value="1">General Promotion</option>
<option <?php if(get_option('linkCoupon_display_promo') == '8') { echo 'selected'; } ?> value="8">Friends and Family</option>
<option <?php if(get_option('linkCoupon_display_promo') == '6') { echo 'selected'; } ?> value="6">Free Trial / Usage</option>
<option <?php if(get_option('linkCoupon_display_promo') == '7') { echo 'selected'; } ?> value="7">Free Shipping</option>
<option <?php if(get_option('linkCoupon_display_promo') == '5') { echo 'selected'; } ?> value="5">Dollar Amount off</option>
<option <?php if(get_option('linkCoupon_display_promo') == '14') { echo 'selected'; } ?> value="14">Deal of the Day/Week</option>
<option <?php if(get_option('linkCoupon_display_promo') == '4') { echo 'selected'; } ?> value="4">Combination Savings</option>
<option <?php if(get_option('linkCoupon_display_promo') == '3') { echo 'selected'; } ?> value="3">Clearance</option>
<option <?php if(get_option('linkCoupon_display_promo') == '2') { echo 'selected'; } ?> value="2">Buy One / Get One</option>
 </select>
 </td>  </tr>
 	 	 <tr valign="top" >
 <th scope="row"> Which Network?</th>
 <td>
<select name="display_network" id="display_network">
<option <?php if(get_option('linkCoupon_display_network') == '0') { echo 'selected'; } ?> value="0">- All Networks -</option>

<option <?php if(get_option('linkCoupon_display_network') == '1') { echo 'selected'; } ?> value="1">United States</option>
<option <?php if(get_option('linkCoupon_display_network') == '3') { echo 'selected'; } ?> value="3">LinkShare UK</option>
<option <?php if(get_option('linkCoupon_display_network') == '5') { echo 'selected'; } ?> value="5">LinkShare Canada</option>
 </select>
 </td>  </tr>
 
		 	 
		 	 <tr valign="top" >
		 	 <th scope="row"> How Many Coupon Links to Display </th>
		 	 <td>
        	<select name="display_numitems" id="display_numitems">
		      <option <?php if(get_option('linkCoupon_display_numitems') == '1') { echo 'selected'; } ?> value="1">1</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '2') { echo 'selected'; } ?> value="2">2</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '3') { echo 'selected'; } ?> value="3">3</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '4') { echo 'selected'; } ?> value="4">4</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '5') { echo 'selected'; } ?> value="5">5</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '6') { echo 'selected'; } ?> value="6">6</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '7') { echo 'selected'; } ?> value="7">7</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '8') { echo 'selected'; } ?> value="8">8</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '9') { echo 'selected'; } ?> value="9">9</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '10') { echo 'selected'; } ?> value="10">10</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '11') { echo 'selected'; } ?> value="11">11</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '12') { echo 'selected'; } ?> value="12">12</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '13') { echo 'selected'; } ?> value="13">13</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '14') { echo 'selected'; } ?> value="14">14</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '15') { echo 'selected'; } ?> value="15">15</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '16') { echo 'selected'; } ?> value="16">16</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '17') { echo 'selected'; } ?> value="17">17</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '18') { echo 'selected'; } ?> value="18">18</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '19') { echo 'selected'; } ?> value="19">19</option>
		      <option <?php if(get_option('linkCoupon_display_numitems') == '20') { echo 'selected'; } ?> value="20">20</option>
		      </select>
            
           </td> 
         </tr>
         <tr valign="top" >
		  <th scope="row">Coupon Section Headline</th>
          <td><input name="display_headline" type="text" id="display_headline" value="<?php echo get_option('linkCoupon_headline'); ?>" size="40" /></p>
         </tr>
         
          <tr > <td><div style="padding:65px 2px 2px 2px;"> </div> </td><td> </td></tr>
 
          <tr> <th><h3> RSS Deal Feed</h3></th><td><i> Insert these deals by including the function call <tt>get_linkshareRSS() </tt> in your template. A common location is the file sidebar.php. </td></tr>
          
                <tr valign="top">
          <th scope="row"><br> Choose an RSS feed.  </th>
          <td>
        	<select name="display_type" id="display_type"> 
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2909&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2909&amp;token=">A Matter of Fax - Deals of the Day</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3392&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3392&amp;token=">Active Coupons @Focalprice.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3409&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3409&amp;token=">All Deals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3271&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3271&amp;token=">ApexCCTV.com Promotion - ApexCCTV</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3088&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3088&amp;token=">AQ RSS</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3012&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3012&amp;token=">ASOS Latest Products - Beauty</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3011&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3011&amp;token=">ASOS Latest Products - Designers</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3010&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3010&amp;token=">ASOS Latest Products - Kids</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3008&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3008&amp;token=">ASOS Latest Products - Men</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3013&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3013&amp;token=">ASOS Latest Products - Outlet</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3009&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3009&amp;token=">ASOS Latest Products - Women</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3047&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3047&amp;token=">AtHisBest.com deals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1528&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1528&amp;token=">AVON Special Offers</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1510&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1510&amp;token=">Bare Necessities Product List</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2148&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2148&amp;token=">Beauty.com GWPs and Offers</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3039&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3039&amp;token=">Best Camcorder Deals at CompUSA.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3034&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3034&amp;token=">Best Desktop Computer Deals at CompUSA.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3038&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3038&amp;token=">Best Digital Camera Deals at CompUSA.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3110&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3110&amp;token=">Best Digital Picture Frames Deals at CompUSA</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3040&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3040&amp;token=">Best Home Video Deals at CompUSA.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3035&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3035&amp;token=">Best Laptops and Netbooks Deals at CompUSA.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3043&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3043&amp;token=">Best MP3 Players Deals at CompUSA.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3132&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3132&amp;token=">Best Netbook Deals at The All New CompUSA.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3036&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3036&amp;token=">Best Projector Deals at CompUSA.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1352&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1352&amp;token=">Best Sellers of the Week at The All New CompUSA.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1476&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1476&amp;token=">Best Sellers of the Week at TigerDirect.ca</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3037&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3037&amp;token=">Best TV Deals at CompUSA.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3041&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3041&amp;token=">Best Video Games Deals at CompUSA.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3042&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3042&amp;token=">Best Wireless Networking Deals at CompUSA.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2617&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2617&amp;token=">BlackBerry 9630 Accessories</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2341&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2341&amp;token=">Blackberry Best Sellers</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3469&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3469&amp;token=">Buy.com Category/Store Promotions</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3210&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3210&amp;token=">Buy.com Daily Deals and Weekend Specials</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3530&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3530&amp;token=">Cafe Britt Gourmet Coffee</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2699&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2699&amp;token=">Camping World Daily Deal</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2679&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2679&amp;token=">Camping World Internet-Only Specials</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2678&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2678&amp;token=">Camping World New Products</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2619&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2619&amp;token=">Cascio Interstate Music - Current Coupons and Promotions</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2638&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2638&amp;token=">Cascio Interstate Music - News</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2639&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2639&amp;token=">Cascio Interstate Music - Top Products</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2557&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2557&amp;token=">CheapOair.com : Hot Deals &amp; Coupon Codes</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2575&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2575&amp;token=">CheapOair.com : Top 25 Weekly Flight Deals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2767&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2767&amp;token=">CheapOair.com : Top 30 Weekly Hotel Deals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2574&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2574&amp;token=">CheapOair.com : Top Flight Deals Under $199</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2168&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2168&amp;token=">Coldwater Creek RSS Feed</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1854&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1854&amp;token=">Concert Vault</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1331&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1331&amp;token=">David&#039;s Cookies Categories</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3069&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3069&amp;token=">Deal Alerts</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1927&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1927&amp;token=">Diamonds International Deals and Coupons</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3289&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3289&amp;token=">Discounted Products</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1774&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1774&amp;token=">Drugstore.com Weekly Promotions</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3190&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3190&amp;token=">Eastbay Blog RSS Feed</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2930&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2930&amp;token=">EasyClickTravel.com - Exclusive Deals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2929&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2929&amp;token=">EasyClickTravel.com - Top Hotel Deals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=172&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=172&amp;token=">Electronic Appraiser News Blog</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3273&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3273&amp;token=">Electronics Expo</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2731&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2731&amp;token=">Electronics Expo - November Coupons</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2784&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2784&amp;token=">Electronics Expo 2010</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2765&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2765&amp;token=">Electronics Expo Coupons</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1488&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1488&amp;token=">Elemis Monthly Offers</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2698&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2698&amp;token=">Foot Petals Specials and Promotions</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2378&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2378&amp;token=">FORZIERI: ALL THAT SPARKLES</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2376&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2376&amp;token=">FORZIERI: ALL THE LATEST STYLES AND DEALS, REAL TIME</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2377&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2377&amp;token=">FORZIERI: BAG &amp; SHOE FANATIC</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2379&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2379&amp;token=">FORZIERI: TIES &amp; TIES</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2127&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2127&amp;token=">Fujitsu offers and specials</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1511&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1511&amp;token=">Goldsmiths Latest Offers</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3046&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3046&amp;token=">Great deals from drugstore.com Canada</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=151&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=151&amp;token=">GreatSkin Affiliate News and Updates</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1487&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1487&amp;token=">Handango: Windows Mobile (Touch Screen) Bestsellers</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3371&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3371&amp;token=">Hot Items From Focalprice</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3080&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3080&amp;token=">Hot Sellers Canada: Communications</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3074&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3074&amp;token=">Hot Sellers Canada: Computer Memory</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3079&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3079&amp;token=">Hot Sellers Canada: Computer Processors</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3076&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3076&amp;token=">Hot Sellers Canada: Computer Software</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3073&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3073&amp;token=">Hot Sellers Canada: Desktop Computers</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3078&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3078&amp;token=">Hot Sellers Canada: GPS Navigation Devices</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3072&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3072&amp;token=">Hot Sellers Canada: Hard Drives / Storage</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3071&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3071&amp;token=">Hot Sellers Canada: Laptops and Netbooks</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3070&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3070&amp;token=">Hot Sellers Canada: LCD Monitors</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3075&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3075&amp;token=">Hot Sellers Canada: Video Graphic Cards</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2434&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2434&amp;token=">iolo Video Feed</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3044&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3044&amp;token=">Issue Demonstration # 1</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3045&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3045&amp;token=">Issue Demonstration # 2</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1848&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1848&amp;token=">Jewelry Deal of the Day</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2988&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2988&amp;token=">Karen Millen&#039;s Latest Products</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2254&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2254&amp;token=">Karmlaoop - Top New Arrivals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2007&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2007&amp;token=">KegWorks Bonus (Pre-Coded)</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=152&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=152&amp;token=">Kegworks Partner News and Updates</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3470&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3470&amp;token=">Knetgofl 2011</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2869&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2869&amp;token=">LAMPS PLUS Blog</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3133&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3133&amp;token=">LampsPlus.com Deal of the Day</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2594&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2594&amp;token=">Latest iPhone 3G Accessories</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3112&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3112&amp;token=">LinkShare Blogger</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3452&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3452&amp;token=">Live In the Now</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3113&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3113&amp;token=">LS blog</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1089&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1089&amp;token=">Macys.com RSS Feed</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3068&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3068&amp;token=">Maya Test</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=329&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=329&amp;token=">MusicSpace Affiliates Rss Feed</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3393&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3393&amp;token=">New Angara Feed</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3230&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3230&amp;token=">New In Men&rsquo;s at French Connection</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3229&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3229&amp;token=">New In Women&rsquo;s at French Connection</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3290&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3290&amp;token=">New Products</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3370&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3370&amp;token=">New Releases @Focalprice.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=530&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=530&amp;token=">Office Depot: Hot Offers</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2576&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2576&amp;token=">OneTravel.com : Top 25 Flight Deals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2723&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2723&amp;token=">Orbitz Top Vacation Deals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=262&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=262&amp;token=">PersonalCreations.com Affiliate</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3274&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3274&amp;token=">Playspan RSS</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3291&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3291&amp;token=">Ricky&#039;s Blog</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2848&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2848&amp;token=">SexualWellBeing.com deals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1166&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1166&amp;token=">ShopNBC RSS Feeds</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1413&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1413&amp;token=">Shop[4]Tech Daily Feed</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=187&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=187&amp;token=">Sierra Trading Post RSS Feed</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=113&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=113&amp;token=">Smarthome Affiliates Only News &amp; Info</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1350&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1350&amp;token=">Smarthome Deals and Sales</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1349&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1349&amp;token=">Smarthome New Products</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=929&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=929&amp;token=">Smarthome News and Updates</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2614&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2614&amp;token=">Sprint HTC Ozone Latest Accessories</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1867&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1867&amp;token=">Szul - Jewelry Best Sellers</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2615&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2615&amp;token=">T-Mobile Dash 3G Accessories</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2616&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2616&amp;token=">T-Mobile myTouch 3G Accessories</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1237&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1237&amp;token=">Tactics Affiliate Notifications</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3048&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3048&amp;token=">Test</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=150&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=150&amp;token=">The Artful Life Blog</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2888&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2888&amp;token=">The Franklin Mint Blog</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3049&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3049&amp;token=">TheNaturalStore.com deals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=947&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=947&amp;token=">TigerDirect Featured Weekly Offers </option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3150&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3150&amp;token=">timetospa-Article of the Week</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3148&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3148&amp;token=">timetospa-Product of the Day</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3149&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3149&amp;token=">timetospa-Tip of the Week</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3131&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3131&amp;token=">Today&#039;s Best Sellers at The All New CompUSA.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3130&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3130&amp;token=">Today&#039;s Best Sellers at TigerDirect.ca</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3128&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3128&amp;token=">Today&#039;s Top 10 Best Sellers at TigerDirect.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3029&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3029&amp;token=">Top 10 Camcorders Deals at TigerDirect.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3030&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3030&amp;token=">Top 10 Cameras Deals at TigerDirect.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3032&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3032&amp;token=">Top 10 Computer Accesories at TigerDirect.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2952&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2952&amp;token=">Top 10 Desktop PCs at TigerDirect.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3109&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3109&amp;token=">Top 10 Digital Picture Frames at TigerDirect</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2950&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2950&amp;token=">Top 10 HDTV Deals at TigerDirect.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3033&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3033&amp;token=">Top 10 Home Video Deals at TigerDirect.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2948&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2948&amp;token=">Top 10 Laptops and Notebooks Deals at TigerDirect.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2951&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2951&amp;token=">Top 10 Monitors Deals at TigerDirect.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3111&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3111&amp;token=">Top 10 Printers Deals at TigerDirect.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=3031&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=3031&amp;token=">Top 10 Processors Deals at TigerDirect.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2949&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2949&amp;token=">Top 10 Projectors Deals at TigerDirect.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2969&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2969&amp;token=">Top Airfare Deals under $199 - Flightnetwork.com</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=2514&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=2514&amp;token=">Trueshopping 1</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=488&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=488&amp;token=">UncommonGoods - New Products</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=987&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=987&amp;token=">Wal-Mart - Apperal - 97 cent Shipping</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=989&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=989&amp;token=">Wal-Mart - Apperal - New Arrivals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=990&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=990&amp;token=">Wal-Mart - Apperal - Rollbacks</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=992&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=992&amp;token=">Wal-Mart - Baby - 97 Cent Shipping</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=993&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=993&amp;token=">Wal-Mart - Baby - Clearance</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=994&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=994&amp;token=">Wal-Mart - Baby - New Arrivals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=995&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=995&amp;token=">Wal-Mart - Baby - Rollbacks</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=996&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=996&amp;token=">Wal-Mart - Baby - Special Buys</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=997&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=997&amp;token=">Wal-Mart - Books - Top Releases</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=998&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=998&amp;token=">Wal-Mart - Books - Top Sellers</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=999&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=999&amp;token=">Wal-Mart - Electronics - Clearance</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1000&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1000&amp;token=">Wal-Mart - Electronics - New Arrivals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1001&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1001&amp;token=">Wal-Mart - Electronics - Rollbacks</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1002&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1002&amp;token=">Wal-Mart - Electronics - Special Buys</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1003&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1003&amp;token=">Wal-Mart - Garden - 97 Cent Shipping</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1004&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1004&amp;token=">Wal-Mart - Garden - Clearence</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1005&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1005&amp;token=">Wal-Mart - Garden - Rollbacks</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1006&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1006&amp;token=">Wal-Mart - Home - 97 Cent Shipping</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1007&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1007&amp;token=">Wal-Mart - Home - Clearance</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1009&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1009&amp;token=">Wal-Mart - Home - Rollbacks</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1010&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1010&amp;token=">Wal-Mart - Movies - 97 Cent Shipping</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1011&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1011&amp;token=">Wal-Mart - Movies - Coming Soon</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1012&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1012&amp;token=">Wal-Mart - Movies - New Releases</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1015&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1015&amp;token=">Wal-Mart - Movies - Weekly Specials</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1016&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1016&amp;token=">Wal-Mart - Music - 97 Cent Shipping</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1017&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1017&amp;token=">Wal-Mart - Music - Future Release</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1018&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1018&amp;token=">Wal-Mart - Music - New Releases</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1019&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1019&amp;token=">Wal-Mart - Music - Top Albums</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1020&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1020&amp;token=">Wal-Mart - Sports - Clearance</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1021&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1021&amp;token=">Wal-Mart - Sports - New Arrivals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1022&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1022&amp;token=">Wal-Mart - Sports - Rollbacks</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1023&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1023&amp;token=">Wal-Mart - Toys - Clearance</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1024&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1024&amp;token=">Wal-Mart - Toys - New Arrivals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1025&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1025&amp;token=">Wal-Mart - Toys - Rollbacks</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1026&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1026&amp;token=">Wal-Mart - Video Games - Clearance</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1027&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1027&amp;token=">Wal-Mart - Video Games - New Releases</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1029&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1029&amp;token=">Wal-Mart - Video Games - Rollbacks</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1028&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1028&amp;token=">Wal-Mart - Video Games - Special Releases</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1030&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1030&amp;token=">Wal-Mart - Video Games - Top Sellers</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1008&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1008&amp;token=">Walmart - Home - New Arrivals</option>
<option <?php if(get_option('LinkshareRSS_display_type') == 'http://rss.linksynergy.com/promo.rss?promoid=1014&token=') { echo 'selected'; } ?> value="http://rss.linksynergy.com/promo.rss?promoid=1014&amp;token=">Walmart - Movies - Exclusives</option>

		    </select> <br>
		 	 (You must be a partner of the Advertiser to display the feed.)<br>
		 	 </td></tr>
		 	 <tr valign="top">
		 	 <th scope="row"> How Many RSS Feed Links to Display </th>
		 	 <td>
        	<select name="display_numRSSitems" id="display_numRSSitems">
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '1') { echo 'selected'; } ?> value="1">1</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '2') { echo 'selected'; } ?> value="2">2</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '3') { echo 'selected'; } ?> value="3">3</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '4') { echo 'selected'; } ?> value="4">4</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '5') { echo 'selected'; } ?> value="5">5</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '6') { echo 'selected'; } ?> value="6">6</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '7') { echo 'selected'; } ?> value="7">7</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '8') { echo 'selected'; } ?> value="8">8</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '9') { echo 'selected'; } ?> value="9">9</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '10') { echo 'selected'; } ?> value="10">10</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '11') { echo 'selected'; } ?> value="11">11</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '12') { echo 'selected'; } ?> value="12">12</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '13') { echo 'selected'; } ?> value="13">13</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '14') { echo 'selected'; } ?> value="14">14</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '15') { echo 'selected'; } ?> value="15">15</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '16') { echo 'selected'; } ?> value="16">16</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '17') { echo 'selected'; } ?> value="17">17</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '18') { echo 'selected'; } ?> value="18">18</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '19') { echo 'selected'; } ?> value="19">19</option>
		      <option <?php if(get_option('linkshareRSS_display_numRSSitems') == '20') { echo 'selected'; } ?> value="20">20</option>
		      </select>
            
           </td> 
         </tr>
         <tr valign="top">
		  <th scope="row">RSS Deal Feed Section Headline</th>
          <td><input name="RSSHeadline" type="text" id="RSSHeadline" value="<?php echo get_option('linkshareRSS_RSSHeadline'); ?>" size="40" /></p>
         </tr>
          
          
          
         </table>      

      
        <div class="submit">
           <input type="submit" name="save_linkCoupon_settings" value="<?php _e('Save Settings', 'save_linkCoupon_settings') ?>" />
        </div>
        </form>
    </div>
<script type="text/javascript"> loadMerchants();</script>

<?php } // end linkCoupon_subpanel()

function linkCoupon_admin_menu() {
   if (function_exists('add_options_page')) {
        add_options_page('LinkShare Dealfeed Settings', 'LinkShare Dealfeed', 8, basename(__FILE__), 'linkCoupon_subpanel');
        }
}


   function myplugin_ajax_merchantfinder_lookup()
 {
   // read submitted information
   $token=get_option('linkCoupon_linkshareid'); 
   $localtoken=$_POST['token'];
   $reqURL="http://findadvertisers.linksynergy.com/merchantsearch?token=".$localtoken;
   $reqURL=$reqURL."&tag=wp-merch-search-c1";
   if ( !class_exists( 'WP_Http' ) ) { include_once( ABSPATH . WPINC. '/class-http.php' );}
   $sno = new WP_Http;
   $sno->agent = 'WordPress/' . $wp_version;
   $sno->read_timeout = 15;
   // Send request to elevation server
   $result = $sno->request($reqURL);
   if( !$result) {  die( "alert('Could not connect to lookup host.')" );  } 
   header("Content-type: text/xml"); 
   die($result['body']);
 } // end of myplugin_ajax_merchantfinder_lookup function



 add_action('admin_menu', 'linkCoupon_admin_menu'); 
 add_action('plugins_loaded', 'widget_linkCoupon_init');
 add_action('wp_ajax_RSSCoupon_merchantfinder_lookup', 'myplugin_ajax_merchantfinder_lookup' );
?>
