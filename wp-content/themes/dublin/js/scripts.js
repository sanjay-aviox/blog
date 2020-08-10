//Page loader
jQuery(document).ready(function($) {
	$("#page").show();
});

//Menu dropdown animation
jQuery(function($) {
	$('.sub-menu').hide();
	$('.main-navigation .children').hide();
	$('.menu-item').hover( 
		function() {
			$(this).children('.sub-menu').fadeIn();
		}, 
		function() {
			$(this).children('.sub-menu').hide();
		}
	);
	$('.main-navigation li').hover( 
		function() {
			$(this).children('.main-navigation .children').fadeIn();
		}, 
		function() {
			$(this).children('.main-navigation .children').hide();
		}
	);	
});
//Panels styling
jQuery(function($) {
	var width = $(window).width();
	var rowWidth = $('.panel-grid').width();
	var margin = (width - rowWidth)/2;
	var	titleHeight = $('.title-deco-left').closest('.panel-grid').height();
	$('.panel-row-style-full').css('margin', -margin);
	$('.panel-row-style-full').css('padding', margin);
	$('.panel-row-style-full .projects').css('margin', -margin - 30);
	$('.panel-row-style-full .video-header').css('margin', -margin - 30);
	$('.panel-row-style-full .top-slider').css('margin', -margin - 30);

	$(window).resize(function(){
		var width = $(window).width();
		var rowWidth = $('.panel-grid').width();
		var margin = (width - rowWidth)/2;
		var	titleHeight = $('.title-deco-left').closest('.panel-grid').height();
		$('.panel-row-style-full').css('margin', -margin);
		$('.panel-row-style-full').css('padding', margin);
		$('.panel-row-style-full .projects').css('margin', -margin - 30);
		$('.panel-row-style-full .video-header').css('margin', -margin - 30);
		$('.panel-row-style-full .top-slider').css('margin', -margin - 30);
	});

});

//Fit Vids
jQuery(function($) {
    $("body").fitVids();
});

//Remove bottom padding for cells that contain the Title Widget
jQuery(function($) {
	$('.widget_flypack_title').parent().css('padding-bottom', '0');
});

//Smooth scrolling
jQuery(function($) {
	$('a[href*=#]:not([href=#])').click(function() {
		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
			if (target.length) {
			$('html,body').animate({
			scrollTop: target.offset().top
			}, 800);
			return false;
			}
		}
	});
});
