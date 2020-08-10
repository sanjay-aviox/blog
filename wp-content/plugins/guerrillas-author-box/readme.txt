=== Plugin Name ===
Contributors: madebyguerrilla
Tags: author box, author info, author
Requires at least: 3.0
Tested up to: 4.4.2
Stable tag: 1.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This is a plugin that adds an author box to the end of your posts, showing off the authors name, description, website link and gravatar powered avatar.

== Description ==

This is a plugin that adds an author box to the end of your posts, showing off the authors name, description, website link and gravatar powered avatar.

As of version 1.3 the following input boxes were removed:

* aim
* yim
* jabber

The following input boxes were added:

* twitter
* facebook
* google+
* linkedin
* dribbble
* github

== Frequently Asked Questions ==

= Will there ever be more options added? =

Yes, I do have plans on adding more options for this plugin. For instance, I plan on adding a page in the WordPress admin panel for you to be able to edit the CSS so the author box can be styled to fit with your theme design.

= What are the default CSS codes? =

As of version 1.3, the css is contained in it's own style.css file within the plugin folder instead of directly added to the html of your web page.

You can copy/paste the below css codes into your themes CSS file and adjust them accordingly in order to override the css that's been added with the plugin.

	.guerrillawrap {
		background: #f8f8f8;
		-webkit-box-sizing:border-box;
		-moz-box-sizing:border-box;
		-ms-box-sizing:border-box;
		box-sizing:border-box;
		border: 1px solid #d0d0d0;
		float: left;
		padding: 2%;
		width: 100%;
	}

	.guerrillagravatar {
		float: left;
		margin: 0 10px 0 0;
		width: 10%;
	}

	.guerrillatext {
		float: left;
		width: 84%;
	}

	.guerrillatext h4 {
		font-size: 20px;
		line-height: 20px;
		margin: 0 0 0 0;
		padding: 0;
	}

	.guerrillatext p {
		margin: 5px 0 12px 0;
	}

	.guerrillasocial {
		float: left;
		width: 100%;
	}

	.guerrillasocial a {
		border: 0;
		margin-right: 10px;
	}

== Installation ==

1. Upload 'guerrilla-author-box.php' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. This is how the author box displays in the Clarity WordPress theme

== Changelog ==

= 1.1 =
* Automatically remove the aim/yim/jabber profile options
* Automatically add twitter/facebook/google+/linkedin options

= 1.2 =
* Fixed a PHP error which caused the plugin to deactivate

= 1.3 =
* Automatically add dribbble/github options
* Fixed the way social links were displayed within the author box (no url added in the backend, no link shows up on the live site)
* Fixed how the css styles are used to style the author box (previously added to html, now in their own style.css file)

= 1.4 =
* Minor CSS fixes

= 1.5 =
* various html fixes

= 1.6 =
* various html fixes

= 1.7 =
* added fontawesome support and icons to social profile links
* cleaned up css and adjusted the styles of the links/text

= 1.8 =
* Updated various CSS styles
* Updated fontawesome version

== Upgrade Notice ==

= 1.1 =
New version adds twitter/facebook/google+/linkedin profile options and removes some seldom used options

= 1.5 =
New version adds dribbble/github profile options and also fixes various css and html issues

= 1.7 =
