<div class="wrap">
	
	<div id="icon-options-general" class="icon32"></div>
	<h2>Social Media Buttons</h2>
	
	<div id="poststuff">
	
		<div id="post-body" class="metabox-holder columns-2">
		
			<!-- main content -->
			<div id="post-body-content">
				
				<div class="meta-box-sortables ui-sortable">
					
					<div class="postbox">
					
						<h3><span>Social Media</span></h3>
						<div class="inside">
							<form name="round_social_media_buttons_form" method="post" action="">

								<input type="hidden" name="round_social_media_buttons_form_submitted" value="Y">

								<table class="form-table">
									<tr>
										<td scope="row">
											<label for="facebook_btn">Facebook</label>
										</td>
										<td>
											<input name="facebook_btn" id="facebook_btn" type="text" value="<?php if (isset($options['facebook_btn']) || $hidden_field == 'Y') { echo $options['facebook_btn']; } ?>" class="regular-text" />
										</td>
									</tr>
									<tr valign="top" class="alternate">
										<td scope="row">
											<label for="twitter_btn">Twitter</label>
										</td>
										<td>
											<input name="twitter_btn" id="twitter_btn" type="text" value="<?php if (isset($options['twitter_btn']) || $hidden_field == 'Y') { echo $options['twitter_btn']; } ?>" class="regular-text" />
										</td>
									</tr>
									<tr valign="top">
										<td scope="row">
											<label for="google_plus_btn">Google+</label>
										</td>
										<td>
											<input name="google_plus_btn" id="google_plus_btn" type="text" value="<?php if (isset($options['google_plus_btn']) || $hidden_field == 'Y') { echo $options['google_plus_btn']; } ?>" class="regular-text" />
										</td>
									</tr>
									<tr valign="top" class="alternate">
										<td scope="row">
											<label for="youtube_btn">YouTube</label>
										</td>
										<td>
											<input name="youtube_btn" id="youtube_btn" type="text" value="<?php if (isset($options['youtube_btn']) || $hidden_field == 'Y') { echo $options['youtube_btn']; } ?>" class="regular-text" />
										</td>
									</tr>
									<tr valign="top">
										<td scope="row">
											<label for="linkedin_btn">LinkedIn</label>
										</td>
										<td>
											<input name="linkedin_btn" id="linkedin_btn" type="text" value="<?php if (isset($options['linkedin_btn']) || $hidden_field == 'Y') { echo $options['linkedin_btn']; } ?>" class="regular-text" />
										</td>
									</tr>
									<tr valign="top" class="alternate">
										<td scope="row">
											<label for="instagram_btn">Instagram</label>
										</td>
										<td>
											<input name="instagram_btn" id="instagram_btn" type="text" value="<?php if (isset($options['instagram_btn']) || $hidden_field == 'Y') { echo $options['instagram_btn']; } ?>" class="regular-text" />
										</td>
									</tr>
									<tr valign="top">
										<td scope="row">
											<label for="pinterest_btn">Pinterest</label>
										</td>
										<td>
											<input name="pinterest_btn" id="pinterest_btn" type="text" value="<?php if (isset($options['pinterest_btn']) || $hidden_field == 'Y') { echo $options['pinterest_btn']; } ?>" class="regular-text" />
										</td>
									</tr>
									<tr valign="top" class="alternate">
										<td scope="row">
											<label for="tumblr_btn">Tumblr</label>
										</td>
										<td>
											<input name="tumblr_btn" id="tumblr_btn" type="text" value="<?php if (isset($options['tumblr_btn']) || $hidden_field == 'Y') { echo $options['tumblr_btn']; } ?>" class="regular-text" />
										</td>
									</tr>
								</table>
								<p>
									<input class="button-primary" type="submit" name="round_social_media_buttons_submit" value="Save" />
								</p>
							</form>
							
						</div> <!-- .inside -->
					
					</div> <!-- .postbox -->
					
				</div> <!-- .meta-box-sortables .ui-sortable -->
				
			</div> <!-- post-body-content -->
			
			<!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">
				
				<div class="meta-box-sortables">
					
					<div class="postbox">
					
						<h3><span>About</span></h3>
						<div class="inside">
							<?php
								echo '<img src="' . plugins_url( 'images/round_sm_buttons-01.png' , __FILE__ ) . '" > ';
							?>
							<ol>
								<li>Enter your URL to any of the social media websites.</li>
								<li>Click Save</li>
							</ol>
						</div> <!-- .inside -->
						
					</div> <!-- .postbox -->
					
				</div> <!-- .meta-box-sortables -->
				
			</div> <!-- #postbox-container-1 .postbox-container -->
			
		</div> <!-- #post-body .metabox-holder .columns-2 -->
		<br class="clear">
	</div> <!-- #poststuff -->
	
</div> <!-- .wrap -->