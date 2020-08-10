<?php

	/*
	Plugin Name: Round Social Media Buttons
	Plugin URI: http://solomonscott.com
	Description: A plugin that displays social media buttons such as Facebook, Twitter, Google+, etc.
	Version: 1.0
	Author: Solomon Scott
	Author URI: http://solomonscott.com
	License: GPLv2 or later
	*/


	/* 
	*	Assign Global Variables
	*/

	$plugin_url = WP_PLUGIN_URL . '/round_social_media_buttons';
	$options = array();

	/* 
	*	Add a link to the plugin in the admin menu
	*	Under Settings page
	*/

	function round_social_media_buttons_menu() {

		add_options_page(
			'Round Social Media Buttons',
			'Social Media Buttons',
			'manage_options',
			'round-social-media-buttons',
			'round_social_media_buttons_options_page'
			);
	}
	add_action( 'admin_menu', 'round_social_media_buttons_menu' );


	function round_social_media_buttons_options_page() {

		if (current_user_can( 'mange_options' )) {

			wp_die( 'You do not have suffiecient permissions to view the page.');

		}

		global $plugin_url;
		global $options;

		if ( isset($_POST['round_social_media_buttons_form_submitted'])) {

			$hidden_field = esc_html( $_POST['round_social_media_buttons_form_submitted'] );

			if ( $hidden_field == 'Y' ) {

				$round_btn_facebook_url = esc_html( $_POST['facebook_btn'] );

				$round_btn_twitter_url = esc_html( $_POST['twitter_btn'] );

				$round_btn_google_url = esc_html( $_POST['google_plus_btn'] );

				$round_btn_youtube_url = esc_html( $_POST['youtube_btn'] );

				$round_btn_linkedin_url = esc_html( $_POST['linkedin_btn'] );

				$round_btn_instagram_url = esc_html( $_POST['instagram_btn'] );

				$round_btn_pinterest_url = esc_html( $_POST['pinterest_btn'] );

				$round_btn_tumblr_url = esc_html( $_POST['tumblr_btn'] );

				$options['facebook_btn']	= $round_btn_facebook_url;
				$options['twitter_btn']		= $round_btn_twitter_url;
				$options['google_plus_btn']	= $round_btn_google_url;
				$options['youtube_btn']		= $round_btn_youtube_url;
				$options['linkedin_btn']	= $round_btn_linkedin_url;
				$options['instagram_btn']	= $round_btn_instagram_url;
				$options['pinterest_btn']	= $round_btn_pinterest_url;
				$options['tumblr_btn']		= $round_btn_tumblr_url;

				update_option( 'round_social_media_buttons', $options );

			}

		}

		$options = get_option( 'round_social_media_buttons' );

		if ($options != '') {
			
			$round_btn_facebook_url = $options['facebook_btn'];
			$round_btn_twitter_url = $options['twitter_btn'];
			$round_btn_google_url = $options['google_plus_btn'];
			$round_btn_youtube_url = $options['youtube_btn'];
			$round_btn_linkedin_url = $options['linkedin_btn'];
			$round_btn_instagram_url = $options['instagram_btn'];
			$round_btn_pinterest_url = $options['pinterest_btn'];
			$round_btn_tumblr_url = $options['tumblr_btn'];

		}

		require ( 'inc/settings_page_wrapper.php' );
	}

	class Round_Social_Media_Buttons_Widget extends WP_Widget {

		function round_social_media_buttons_widget() {
			// Instantiate the parent object
			parent::__construct( false, 'Round Social Media Buttons' );
		}

		function widget( $args, $instance ) {
			// Widget output

			extract($args);
			$title = apply_filters( 'widget_title', $instance['title'] );

			$options = get_option( 'round_social_media_buttons' );
			//Social Media Links
			$round_btn_facebook_url = $options['facebook_btn'];
			$round_btn_twitter_url = $options['twitter_btn'];
			$round_btn_google_url = $options['google_plus_btn'];
			$round_btn_youtube_url = $options['youtube_btn'];
			$round_btn_linkedin_url = $options['linkedin_btn'];
			$round_btn_instagram_url = $options['instagram_btn'];
			$round_btn_pinterest_url = $options['pinterest_btn'];
			$round_btn_tumblr_url = $options['tumblr_btn'];

			require 'inc/front-end.php';
		}

		function update( $new_instance, $old_instance ) {
			// Save widget options

			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);

			return $instance;
		}

		function form( $instance ) {
			// Output admin widget options form

			$title = esc_attr( $instance['title'] );

			require 'inc/widget_fields.php';
		}
	}

	function round_social_media_buttons_register_widgets() {
		register_widget( 'Round_Social_Media_Buttons_Widget' );
	}

	add_action( 'widgets_init', 'round_social_media_buttons_register_widgets' );

	function round_social_media_buttons_styles() {
		wp_enqueue_style ( 'round_social_media_buttons_styles', plugins_url( 'round_social_media_buttons.css' , __FILE__ ) );
	}
	add_action( 'wp_enqueue_scripts', 'round_social_media_buttons_styles' );


?>