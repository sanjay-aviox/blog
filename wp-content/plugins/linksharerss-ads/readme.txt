=== LinkShare Dealfeed ===
Contributors: pwayner
Donate link: http://example.com/
Tags: Linkshare, ads,   links,  widget,  affiliate marketing, RSS, coupons
Requires at least: 2.0.2,
Tested up to: 3.4.1
Stable tag: trunk

LinkShare Dealfeed uses information from both the Coupon API and
the RSS feeds from LinkShare Advertisers to automatically populate your Wordpress 
blog with deals and other promotional content. You can earn a commission on every sale on an Advertiser site that your blog refers. 
Please note that you'll need to join the LinkShare Network and be approved into an Advertisers affiliate program in order to earn a commission on sales.

== Description ==


LinkShare  Dealfeed uses streams of offers from LinkShare Advertisers to automatically populate your Wordpress 
blog with deals and other promotional content. You can earn a commission on every sale on an Advertiser site that 
your blog refers.  Just set up the plug-in once, and then your Wordpress site will automatically display the latest 
promotional information provided by the Advertiser.

IMPORTANT - Please note that you'll need to join the LinkShare Network and be approved into an Advertisers affiliate program in order to display the feed and earn a commission on sales.

LinkShare is a leading Performance Marketing Network that offers you access to partnerships with hundreds of top Advertisers.
  

== Installation ==

This section describes how to install the plugin and get it working.

1. Simply download the plug-in and manage it in your Wordpress Admin panel, under the Settings option. 
2. Unzip the downloaded package and upload the LinkShare RSS folder into your Wordpress plugins folder.  
3. Log into your Wordpress admin panel
4. Go to Plug-ins and Activate the LinkShare Dealfeed plug-in
5. LinkShare Dealfeed will now be displayed as an option in your Settings section.
6. For first step instructions, go to Settings > LinkShare RSS
7. Enter your feed token
8. Choose which Advertiser Dealfeed you'd like to display
9. If you want to include offers from the RSS feed, insert the right PHP tag, `<?php get_linkshareRSS(); ?>` , into your WP theme. I put mine in the sidebar.php file in the default theme.
10. If you want to include offers from the Coupon feed, insert the right PHP tag, `<?php get_linkshareCoupons(); ?>` , into your WP theme. I put mine in the sidebar.php file in the default theme. You can use one or the other in different locations as you choose.

== Frequently Asked Questions ==


= Where do I go to join LinkShare? =
Sign up at https://cli.linksynergy.com/cli/publisher/registration/registration.php

= How do I partner with Advertisers? =
Once you're in the LinkShare Network, navigate through the Programs tab to find Advertisers to partner with. It's a good idea to first install the plug-in and find which Advertisers offer RSS feeds you'd like to promote and then apply to their programs. 

= Where do I find my "token"? =
In the Publisher Dashboard at http://cli.linksynergy.com/cli/publisher/links/webServices.php

= Do all LinkShare Advertisers offer RSS feeds? =
No, only certain Advertisers offer feeds. 

= Do all LinkShare Advertisers offer Coupons? =
No, only certain Advertisers offer feeds. 


= Do I need to continually update my plug-in? =
No! The plug-in is powered by RSS feeds and the coupon API that are continually updated :with new content at the Advertisers discretion. 

You'll find a comprehensive Publisher help center at http://helpcenter.linkshare.com/publisher. 

= Can you tell us about the provenance of the work? =
Yes, we grabbed an RSS plugin from Dave Kellam and tweaked it to work with Linkshare's RSS feeds. This is also released with the GPL license.

= Why am I getting a 'fatal error' message? =
The plugin needs to know where to put the adds.  You need to copy `<?php get_linkshareRSS(); ?>` or `<?php get_linkshareCoupons(); ?>` into your theme somewhere. I often use the sidebar.php file.
 
 
 = Do I have to use both? =
No, you can use either one. Just include whichever call you need.  You don't need to put them near each other although we tend to put both in sidebar.php.


= Where can I preview my RSS feeds? =
http://cli.linksynergy.com/cli/publisher/links/dataFeeds.php?subpage=RSS

= How can I contact you? =
Write us at developers (at) linkshare.com 



 
 
