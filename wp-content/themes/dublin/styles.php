<?php


//Dynamic styles
function dublin_custom_styles($custom) {
	//Primary color
	$primary_color = get_theme_mod( 'primary_color' );
	if ( isset($primary_color) && ( $primary_color != '#3fb8af' ) ) {
		$custom = "a.comment-reply-link, .comment-form-author:before, .comment-form-email:before, .comment-form-url:before, .comment-form-comment:before.top-slider .bx-next, .top-slider .bx-prev, .list-container .fa, .rotator, #filter a, .above-title span, .employee-position, .employee-social a:hover, .entry-meta, .entry-footer, .tagcloud a, a, a:hover, .button, button, input[type=\"button\"], input[type=\"reset\"], input[type=\"submit\"] { color:" . esc_html($primary_color) . "; }"."\n";
		$custom .= ".slide-title, .left-overlay, .right-overlay, #filter a.active, .fact-inner, .skill-value, .service-icon span, .top-bar, .widget-area .widget-deco, .button:hover, .main-navigation ul ul li:hover, th { background-color:" . esc_html($primary_color) . "; }"."\n";
		$custom .= ".comment-reply-link, #filter a, .service-icon, .tagcloud a, .widget-area .widget-title, .main-navigation ul ul, .main-navigation a:hover, .button, button, input[type=\"button\"], input[type=\"reset\"], input[type=\"submit\"], input[type=\"text\"], input[type=\"email\"], input[type=\"url\"], input[type=\"password\"], input[type=\"search\"], textarea { border-color:" . esc_html($primary_color) . "; }"."\n";
	}
	//Site header
	$header_color = get_theme_mod( 'header_bg_color' );
	if ( isset($header_color) && ( $header_color != '#20272b' ) ) {
		$custom .= ".site-header { background-color:" . esc_html($header_color) . "; }"."\n";
	}
	//Footer widgets area
	$footer_color = get_theme_mod( 'footer_bg_color' );
	if ( isset($footer_color) && ( $footer_color != '#20272b' ) ) {
		$custom .= ".footer-widget-area { background-color:" . esc_html($footer_color) . "; }"."\n";
	}
	//Footer credits area
	$credits_color = get_theme_mod( 'credits_bg_color' );
	if ( isset($footer_color) && ( $footer_color != '#101314' ) ) {
		$custom .= ".site-footer { background-color:" . esc_html($credits_color) . "; }"."\n";
	}
	//Site title
	$site_title = get_theme_mod( 'site_title_color' );
	if ( isset($site_title) && ( $site_title != '#fff' )) {
		$custom .= ".site-title a { color:" . esc_html($site_title) . "; }"."\n";
	}
	//Site description
	$site_desc = get_theme_mod( 'site_desc_color' );
	if ( isset($site_desc) && ( $site_desc != '#5e5e5e' )) {
		$custom .= ".site-description { color:" . esc_html($site_desc) . "; }"."\n";
	}
	//Entry title
	$entry_title = get_theme_mod( 'entry_title_color' );
	if ( isset($entry_title) && ( $entry_title != '#3c3c3c' )) {
		$custom .= ".entry-title, .entry-title a { color:" . esc_html($entry_title) . "; }"."\n";
	}
	//Body text
	$body_text = get_theme_mod( 'body_text_color' );
	if ( isset($body_text) && ( $body_text != '#3c3c3c' )) {
		$custom .= "body { color:" . esc_html($body_text) . "; }"."\n";
	}
	//Contact info
	$contact_info = get_theme_mod( 'contact_color' );
	if ( isset($contact_info) && ( $contact_info != '#ffffff' )) {
		$custom .= ".contact-info, .social-navigation li a { color:" . esc_html($contact_info) . "; }"."\n";
	}

	//Fonts
	$headings_font = esc_html(get_theme_mod('headings_fonts'));	
	$body_font = esc_html(get_theme_mod('body_fonts'));	
	
	if ( $headings_font ) {
		$font_pieces = explode(":", $headings_font);
		$custom .= "h1, h2, h3, h4, h5, h6, .fact-value { font-family: {$font_pieces[0]}; }"."\n";
	}
	if ( $body_font ) {
		$font_pieces = explode(":", $body_font);
		$custom .= "body { font-family: {$font_pieces[0]}; }"."\n";
	}

    //Widgets display on small screens
    //1. Sidebar
    if ( get_theme_mod( 'sidebar_widgets' ) == 1 ) {
        $custom .= "@media only screen and (max-width: 991px) { .widget-area { display: none; } }"."\n";
    }
    //2. Footer
    if ( get_theme_mod( 'footer_widgets' ) == 1 ) {
        $custom .= "@media only screen and (max-width: 991px) { .footer-widget-area { display: none; } }"."\n";
    }
	
	//Output all the styles
	wp_add_inline_style( 'dublin-style', $custom );	
}
add_action( 'wp_enqueue_scripts', 'dublin_custom_styles' );