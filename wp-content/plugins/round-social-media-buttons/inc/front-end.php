<?php

	echo $before_widget;

	echo $before_title . $title . $after_title;

?>

<ul class="social_media_buttons_frontend">

	<?php if ( $round_btn_facebook_url != '' ) { ?>
		<li>
			<a class="icon-facebook" target="_blank" href="<?php echo $round_btn_facebook_url; ?>"></a>
		</li>
	<?php } ?>
	<?php if ( $round_btn_twitter_url != '' ) { ?>
		<li>
			<a class="icon-twitter-alt" target="_blank" href="<?php echo $round_btn_twitter_url; ?>"></a>
		</li>
	<?php } ?>
	<?php if ( $round_btn_google_url != '' ) { ?>
		<li>
			<a class="icon-google-plus" target="_blank" href="<?php echo $round_btn_google_url; ?>"></a>
		</li>
	<?php } ?>
	<?php if ( $round_btn_youtube_url != '' ) { ?>
		<li>
			<a class="icon-youtube-alt" target="_blank" href="<?php echo $round_btn_youtube_url; ?>"></a>
		</li>
	<?php } ?>
	<?php if ( $round_btn_linkedin_url != '' ) { ?>
		<li>
			<a class="icon-linkedin-alt" target="_blank" href="<?php echo $round_btn_linkedin_url; ?>"></a>
		</li>
	<?php } ?>
	<?php if ( $round_btn_instagram_url != '' ) { ?>
		<li>
			<a class="icon-instagram" target="_blank" href="<?php echo $round_btn_instagram_url; ?>"></a>
		</li>
	<?php } ?>
	<?php if ( $round_btn_pinterest_url != '' ) { ?>
		<li>
			<a class="icon-pinterest" target="_blank" href="<?php echo $round_btn_pinterest_url; ?>"></a>
		</li>
	<?php } ?>
	<?php if ( $round_btn_tumblr_url != '' ) { ?>
		<li>
			<a class="icon-tumblr" target="_blank" href="<?php echo $round_btn_tumblr_url; ?>"></a>
		</li>
	<?php } ?>


</ul>

<?php

	echo $after_widget;

?>