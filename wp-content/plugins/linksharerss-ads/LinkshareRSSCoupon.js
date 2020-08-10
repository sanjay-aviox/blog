/* Part of the LinkShare RSS Coupon module for Word Press. 
   Release covered by GPL 2.1.
   */

function loadMerchants(){
  var t1=jQuery('#linkshare_id');
  var token=t1.val();
  var  params={action:'RSSCoupon_merchantfinder_lookup',cookie:document.cookie, token:token};
  var loadMe="../wp-admin/admin-ajax.php";
   jQuery.post(loadMe, params, function(xml)
                     {insertMerchantXML(xml);
   });
}
 
 function insertMerchantXML(xml){
	   var items=jQuery('merchant',xml);
	   if (items.length==0){
	     handleError(xml);
	   } else {
	   var val='<option value="0">- All Merchants - </option>';
	   items.each(function(i){
	      var x=itemToMerchantDOM(jQuery(this));
	      val+=x;
	    });
	   jQuery('#display_merchant').html(val);
	  }
	}
	

function itemToMerchantDOM(i){
  var mid=i.find("mid").text();
  var nm=i.find("merchantname").text();
  var x="<option value='"+mid+"'>"+nm+"</option>";
  return x;
 }