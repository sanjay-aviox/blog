<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Dublin
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="preload" href="//use.fontawesome.com/releases/v5.4.1/css/all.css" as="style" onload="this.onload = null;
                        this.rel = 'stylesheet'">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php if ( get_theme_mod('site_favicon') ) : ?>
	<link rel="shortcut icon" href="<?php echo esc_url(get_theme_mod('site_favicon')); ?>" />
<?php endif; ?>

<?php wp_head(); ?>
<script type='text/javascript' src='//dsms0mj1bbhn4.cloudfront.net/assets/pub/shareaholic.js' data-shr-siteid='f0e6f93cccba05cd5f8d87922e0060db' data-cfasync='false' async='async'></script>
<style type="text/css">
	.site-header{background: url(http://www.gridironstuds.com/blog/wp-content/uploads/bg-checker.png);}
	.main-navigation {
    display: block;
    float: none;
    text-align: left;
    margin: 0;
}
.top_menu {
    background: #fbfaf9;
    border: 1px solid #444444;
    border-left: 0;
    border-right: 0;
}
.top_menu .main-navigation a:hover {
    border-top: 0;
    color: #4d4c4c;
    background: #ccc;
}
.main-navigation a {
    display: block;
    text-decoration: none;
    color: #4d4c4c;
    font-weight: bold;
    padding: 10px 20px !important;
    letter-spacing: 2px;
    font-size: 22px;
    font-family: 'Bebas Neue';
}
.site-header h2 {
    color: #ff0303;
    text-transform: uppercase;
    font-size: 30px;
    font-family: 'Bebas Neue';
    margin-bottom: 35px;
}
.site-header h1 {
    color: #333333;
    text-transform: uppercase;
    font-size: 40px;
    font-family: 'Bebas Neue';
}
.site-footer {
    background-color: #fbfaf9;
    padding: 30px 0;
    border-top: 10px solid #ff0303;
}
.footer-logo{max-width: 150px}
ul.social-media {
    padding: 0;
    margin: 0;
    list-style-type: none;
}
ul.social-media li {
    display: inline-block;
    font-size: 30px;
    margin: 8px;
}
.bottom-footer {
    border-top: 3px solid;
    padding: 30px 0 0;
}
</style>

</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'dublin' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="container">
			<div class="row">
			<div class="col-sm-2">
				<?php if ( get_theme_mod('site_logo') ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo('name'); ?>"><img class="site-logo" src="<?php echo esc_url(get_theme_mod('site_logo')); ?>" alt="<?php bloginfo('name'); ?>" /></a>
				<?php else : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
				<?php endif; ?>
			</div>

					<div class="col-sm-10">
						<h1>Gridiron Studs Blog: High School and College Football Recruiting Talk</h1>
                        <h2>Latest news, articles and information on college football recruiting</h2>
					</div>
				</div>
			</div>
<div class="top_menu">
	<div class="container">
		<div class="row">
			<div class="col-sm-7">
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<button class="menu-toggle"><i class="fa fa-bars"></i></button>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'fallback_cb' => false ) ); ?>
			</nav><!-- #site-navigation -->
		</div>
			<div class="col-sm-5">
				<div class="searchbox">
					<?php echo dynamic_sidebar('sidebar-6'); ?> 
                    <div class="cart_item">
                        <a class="cart-customlocation" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php echo sprintf ( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?>
                            <img src="<?php echo get_template_directory_uri()?>/images/cart_icon.png"> 

                        </a>
                         
                      </div>
				</div>
			</div>
		</div>
	</div> 
</div>
</header>

 <?php echo do_shortcode('[smartslider3 slider=2]');?>

<!-- #masthead -->
<a title="Real Time Web Analytics" href="http://clicky.com/100770392"><img alt="Real Time Web Analytics" src="//static.getclicky.com/media/links/badge.gif" border="0" /></a>
<script src="//static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(100770392); }catch(e){}</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100770392ns.gif" /></p></noscript>
	<div id="content" class="site-content">