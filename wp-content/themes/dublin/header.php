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
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php if ( get_theme_mod('site_favicon') ) : ?>
	<link rel="shortcut icon" href="<?php echo esc_url(get_theme_mod('site_favicon')); ?>" />
<?php endif; ?>

<?php wp_head(); ?>
<script type='text/javascript' src='//dsms0mj1bbhn4.cloudfront.net/assets/pub/shareaholic.js' data-shr-siteid='f0e6f93cccba05cd5f8d87922e0060db' data-cfasync='false' async='async'></script>


</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'dublin' ); ?></a>

	<?php if ( get_theme_mod('contact_display') || has_nav_menu( 'social' ) ) : //Check if the top bar has reasons to display ?>
	<div class="top-bar clearfix">
		<div class="container">
			<?php if ( get_theme_mod('contact_display') ) : ?>
				<div class="contact-info col-md-8">
					<?php if ( get_theme_mod('phone_number') ) : ?>
						<span><?php echo '<i class="fa fa-phone"></i>' . esc_html( get_theme_mod('phone_number') ) ;?></span>
					<?php endif; ?>
					<?php if ( get_theme_mod('email_address') ) : ?>
						<span><?php echo '<i class="fa fa-envelope-o"></i>' . esc_html( get_theme_mod('email_address') ) ;?></span>
					<?php endif; ?>	
					<?php if ( get_theme_mod('p_address') ) : ?>
						<span><?php echo '<i class="fa fa-map-marker"></i>' . esc_html( get_theme_mod('p_address') ) ;?></span>
					<?php endif; ?>										
				</div>
			<?php endif; ?>
			<?php if ( has_nav_menu( 'social' ) ) : ?>
				<nav class="social-navigation col-md-4 col-sm-12">
					<?php wp_nav_menu( array( 'theme_location' => 'social', 'link_before' => '<span class="screen-reader-text">', 'link_after' => '</span>', 'menu_class' => 'menu clearfix', 'fallback_cb' => false ) ); ?>
				</nav>
			<?php endif; ?>

		
		</div>
	</div>
	<?php endif; //Top bar end ?>
	<header id="masthead" class="site-header" role="banner">
		<div class="container">
			<div class="site-branding col-md-4 col-sm-12">
				<?php if ( get_theme_mod('site_logo') ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo('name'); ?>"><img class="site-logo" src="<?php echo esc_url(get_theme_mod('site_logo')); ?>" alt="<?php bloginfo('name'); ?>" /></a>
				<?php else : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
				<?php endif; ?>
                        <div>
                        <h1 style="font-family: 'Oswald', sans-serif; font-size: 29px; color:white;">Gridiron Studs Blog: High School and College Football Recruiting Talk</h1>
                        <h2 style="font-family: 'Oswald', sans-serif; font-size:16px; color:#5e5e5e; margin-bottom:-1px;">Latest news, articles and information on college football recruiting</h2>
                        </div>
			</div>

			<nav id="site-navigation" class="main-navigation col-md-8 col-sm-12" role="navigation">
				<button class="menu-toggle"><i class="fa fa-bars"></i></button>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'fallback_cb' => false ) ); ?>
			</nav><!-- #site-navigation -->
		</div>
<a title="Real Time Web Analytics" href="http://clicky.com/100770392"><img alt="Real Time Web Analytics" src="//static.getclicky.com/media/links/badge.gif" border="0" /></a>
<script src="//static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(100770392); }catch(e){}</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100770392ns.gif" /></p></noscript>
	</header><!-- #masthead -->

	<div id="content" class="site-content container">