<?php	
	if(empty($_POST['kads_hidden']))
		{
			$kads_logo_position = get_option( 'kads_logo_position' );
			$kads_logo_img_link = get_option( 'kads_logo_img_link' );
		}
	elseif($_POST['kads_hidden'] == 'Y')
		{
			//Form data sent
			

			$kads_logo_position = $_POST['kads_logo_position'];
			update_option('kads_logo_position', $kads_logo_position);

			$kads_logo_img_link = $_POST['kads_logo_img_link'];
			update_option('kads_logo_img_link', $kads_logo_img_link);			


			?>
			<div class="updated"><p><strong><?php _e('Changes Saved.' ); ?></strong></p>
            </div>

			<?php
		} else {
			//Normal page display
		
			

		}

?>


<div class="wrap">
	<div id="icon-tools" class="icon32"><br></div><?php echo "<h2>".__('KADS Settings')."</h2>";?>
		<form  method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type="hidden" name="kads_hidden" value="Y">
        <?php settings_fields( 'kads_plugin_options' );
				do_settings_sections( 'kads_plugin_options' );
			
		?>

<table class="form-table">
               
	<tr valign="top">
		<th scope="row">Logo position</th>
		<td style="vertical-align:middle;">
<label for="kads-logo-pos-top-left">
<input type="radio" name="kads_logo_position" id="kads-logo-pos-top-left" value ="top-left" <?php  if ( $kads_logo_position=="top-left" ) echo "checked"; ?> >Top Left</label><br />

<label for="kads-logo-pos-top-right">
<input type="radio" name="kads_logo_position" id="kads-logo-pos-top-right" value ="top-right" <?php  if ( $kads_logo_position=="top-right" ) echo "checked"; ?> >Top Right</label><br />

<label for="kads-logo-pos-bottom-left">
<input type="radio" name="kads_logo_position" id="kads-logo-pos-bottom-left" value ="bottom-left" <?php  if ( $kads_logo_position=="bottom-left" ) echo "checked"; ?> >Bottom Left   </label> <br />          

<label for="kads-logo-pos-bottom-right">
<input type="radio" name="kads_logo_position" id="kads-logo-pos-bottom-right" value ="bottom-right" <?php  if ( $kads_logo_position=="bottom-right" ) echo "checked"; ?> >Bottom Right  </label>  <br />             
                             
                     
		</td>
	</tr>






	<tr valign="top">
		<th scope="row"><label for="kads_logo_img_link">Logo Link</label></th>
		<td style="vertical-align:middle;">

<input type="text" name="kads_logo_img_link" id="kads_logo_img_link" value ="<?php  if (isset($kads_logo_img_link) ) echo $kads_logo_img_link;  ?>"  >
             <input class="upload_image_button button" type="button" value="Upload Image" /><br />  <br /> 
                <?php 
			if(!empty($kads_logo_img_link))
				{
			?>
            <img  height="20px" width="auto" id="kads-logo-img-link-preview" src="<?php if ( isset( $kads_logo_img_link ) ) echo $kads_logo_img_link; ?>" /><br />** width:30px, Height:30px
            <?php } ?>
                     
		</td>
	</tr>

<tr valign="top">
		<th scope="row">Need Help ?</th>
		<td style="vertical-align:middle;">We will be happy to help you :) <br />
        Please report any issue via our support forum <a href="http://kentothemes.com/questions-answers/">kentothemes.com &raquo; Q&A</a> or aks any question if you need. <br />
        Checkout Our Latest Plugin <a href="http://kentothemes.com/">http://kentothemes.com</a>
        
		</td>
	</tr>


</table>
                <p class="submit">
                    <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes' ) ?>" />
                </p>
		</form>

   
</div>
