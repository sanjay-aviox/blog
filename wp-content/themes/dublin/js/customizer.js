/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'color': to,
					'position': 'relative'
				} );
			}
		} );
	} );

	// Primary color
	wp.customize('primary_color',function( value ) {
		value.bind( function( newval ) {
			$('.top-slider .bx-next, .top-slider .bx-prev, .list-container .fa, .rotator, #filter a, .above-title span, .employee-position, .employee-social a:hover, .entry-meta, .entry-footer, .tagcloud a, a, a:hover, .button, button, input[type="button"], input[type="reset"], input[type="submit"]').css('color', newval );
			$('#filter a, .service-icon, .tagcloud a, .widget-area .widget-title, .main-navigation ul ul, .main-navigation a:hover, .button, button, input[type="button"], input[type="reset"], input[type="submit"], input[type="text"], input[type="email"], input[type="url"], input[type="password"], input[type="search"], textarea').css('border-color', newval );
			$('.slide-title, .left-overlay, .right-overlay, #filter a.active, .fact-inner, .skill-value, .service-icon span, .top-bar, .widget-area .widget-deco, .button:hover, .main-navigation ul ul li:hover, th').css('background-color', newval );									
			$('.main-navigation a, .social-navigation li a').css('color', '#ffffff' );
			$('.main-navigation ul ul a').css('color', '#3c3c3c' );
		} );
	});
	//Site header
	wp.customize('header_bg_color',function( value ) {
		value.bind( function( newval ) {
			$('.site-header').css('background-color', newval );
		} );
	});
	//Footer widgets
	wp.customize('footer_bg_color',function( value ) {
		value.bind( function( newval ) {
			$('.footer-widget-area').css('background-color', newval );
		} );
	});
	//Footer credits
	wp.customize('credits_bg_color',function( value ) {
		value.bind( function( newval ) {
			$('.site-footer').css('background-color', newval );
		} );
	});
	//Site title
	wp.customize('site_title_color',function( value ) {
		value.bind( function( newval ) {
			$('.site-title a').css('color', newval );
		} );
	});
	//Site description
	wp.customize('site_desc_color',function( value ) {
		value.bind( function( newval ) {
			$('.site-description').css('color', newval );
		} );
	});
	//Contact info
	wp.customize('contact_color',function( value ) {
		value.bind( function( newval ) {
			$('.contact-info, .social-navigation li a').css('color', newval );
		} );
	});	
	//Entry title
	wp.customize('entry_title_color',function( value ) {
		value.bind( function( newval ) {
			$('.hentry .entry-title, .hentry .entry-title a').css('color', newval );
		} );
	});
	//Body text color
	wp.customize('body_text_color',function( value ) {
		value.bind( function( newval ) {
			$('body').css('color', newval );
		} );
	});								
} )( jQuery );
