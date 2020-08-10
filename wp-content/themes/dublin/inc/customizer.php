<?php
/**
 * Dublin Theme Customizer
 *
 * @package Dublin
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function dublin_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

    //Extra titles
    class Dublin_Titles extends WP_Customize_Control {
        public $type = 'titles';
        public $label = '';
        public function render_content() {
        ?>
            <h3 style="padding: 10px; border: 1px solid #3FB8AF; color: #3FB8AF;"><?php echo esc_html( $this->label ); ?></h3>
        <?php
        }
    }
    //___General___//
    $wp_customize->add_section(
        'dublin_general',
        array(
            'title' => __('General', 'dublin'),
            'priority' => 9,
        )
    );
    //Logo Upload
    $wp_customize->add_setting(
        'site_logo',
        array(
            'default-image' => '',
            'sanitize_callback' => 'esc_url_raw',

        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'site_logo',
            array(
               'label'          => __( 'Upload your logo', 'dublin' ),
               'type'           => 'image',
               'section'        => 'dublin_general',
               'settings'       => 'site_logo',
               'priority' => 9,
            )
        )
    );
    //Favicon Upload
    $wp_customize->add_setting(
        'site_favicon',
        array(
            'default-image' => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'site_favicon',
            array(
               'label'          => __( 'Upload your favicon', 'dublin' ),
               'type'           => 'image',
               'section'        => 'dublin_general',
               'settings'       => 'site_favicon',
               'priority' => 10,
            )
        )
    );
    //Sidebar widgets
    $wp_customize->add_setting(
        'sidebar_widgets',
        array(
            'sanitize_callback' => 'dublin_sanitize_checkbox',
            'default' => 0,         
        )       
    );
    $wp_customize->add_control(
        'sidebar_widgets',
        array(
            'type' => 'checkbox',
            'label' => __('Hide the sidebar widgets on screen widths below 1024px', 'dublin'),
            'section' => 'dublin_general',
            'priority' => 11,           
        )
    ); 
    //Footer widgets
    $wp_customize->add_setting(
        'footer_widgets',
        array(
            'sanitize_callback' => 'dublin_sanitize_checkbox',
            'default' => 0,         
        )       
    );
    $wp_customize->add_control(
        'footer_widgets',
        array(
            'type' => 'checkbox',
            'label' => __('Hide the footer widgets on screen widths below 1024px', 'dublin'),
            'section' => 'dublin_general',
            'priority' => 12,           
        )
    );    
    //___Contact info___//
    $wp_customize->add_section(
        'dublin_top_contact',
        array(
            'title'     => __('Top Contact info', 'dublin'),
            'priority'  => 11,
        )
    );
    //Display
    $wp_customize->add_setting(
        'contact_display',
        array(
            'sanitize_callback' => 'dublin_sanitize_checkbox',
        )       
    );
    $wp_customize->add_control(
        'contact_display',
        array(
            'type'      => 'checkbox',
            'label'     => __('Check this box to display your contact info in the top bar.', 'dublin'),
            'section'   => 'dublin_top_contact',
            'priority'  => 10,
        )
    );    
    //Phone number
    $wp_customize->add_setting(
        'phone_number',
        array(
            'default' => '',
            'sanitize_callback' => 'dublin_sanitize_text',
        )
    );
    $wp_customize->add_control(
        'phone_number',
        array(
            'label' => __( 'Phone number', 'dublin' ),
            'section' => 'dublin_top_contact',
            'type' => 'text',
            'priority' => 11
        )
    );
    //Email address
    $wp_customize->add_setting(
        'email_address',
        array(
            'default' => '',
            'sanitize_callback' => 'dublin_sanitize_text',
        )
    );
    $wp_customize->add_control(
        'email_address',
        array(
            'label' => __( 'Email address', 'dublin' ),
            'section' => 'dublin_top_contact',
            'type' => 'text',
            'priority' => 12
        )
    );
    //Address
    $wp_customize->add_setting(
        'p_address',
        array(
            'default' => '',
            'sanitize_callback' => 'dublin_sanitize_text',
        )
    );
    $wp_customize->add_control(
        'p_address',
        array(
            'label' => __( 'Address', 'dublin' ),
            'section' => 'dublin_top_contact',
            'type' => 'text',
            'priority' => 13
        )
    );
    //___Blog options___//
    $wp_customize->add_section(
        'blog_options',
        array(
            'title' => __('Blog options', 'dublin'),
            'priority' => 12,
        )
    );
    //Full content posts
    $wp_customize->add_setting(
      'full_content',
      array(
        'sanitize_callback' => 'dublin_sanitize_checkbox',
        'default' => 0,     
      )   
    );
    $wp_customize->add_control(
        'full_content',
        array(
            'type' => 'checkbox',
            'label' => __('Full content posts on index?', 'dublin'),
            'section' => 'blog_options',
            'priority' => 11,
        )
    );
    //Excerpt
    $wp_customize->add_setting(
        'exc_lenght',
        array(
            'sanitize_callback' => 'absint',
            'default'           => '55',
        )       
    );
    $wp_customize->add_control( 'exc_lenght', array(
        'type'        => 'number',
        'priority'    => 12,
        'section'     => 'blog_options',
        'label'       => __('Excerpt lenght', 'dublin'),
        'description' => __('Excerpt length. Default is 55 words', 'dublin'),
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 200,
            'step'  => 5,
            'style' => 'padding: 15px;',
        ),
    ) );
    //Index
    $wp_customize->add_setting('dublin_options[titles]', array(
            'type' => 'titles_control',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'dublin_no_sanitize',
        )
    );
    $wp_customize->add_control( new Dublin_Titles( $wp_customize, 'index_meta', array(
        'label' => __('Blog page', 'dublin'),
        'section' => 'blog_options',
        'settings' => 'dublin_options[titles]',
        'priority' => 13
        ) )
    );    
    //Hide date
    $wp_customize->add_setting(
      'dublin_date',
      array(
        'sanitize_callback' => 'dublin_sanitize_checkbox',
        'default' => 0,     
      )   
    );
    $wp_customize->add_control(
      'dublin_date',
      array(
        'type' => 'checkbox',
        'label' => __('Hide post date on index?', 'dublin'),
        'section' => 'blog_options',
        'priority' => 14,
      )
    );
    //Hide categories
    $wp_customize->add_setting(
      'dublin_cats',
      array(
        'sanitize_callback' => 'dublin_sanitize_checkbox',
        'default' => 0,     
      )   
    );
    $wp_customize->add_control(
      'dublin_cats',
      array(
        'type' => 'checkbox',
        'label' => __('Hide post categories on index?', 'dublin'),
        'section' => 'blog_options',
        'priority' => 15,
      )
    );
    //Singles
    $wp_customize->add_setting('dublin_options[titles]', array(
            'type' => 'titles_control',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'dublin_no_sanitize',
        )
    );
    $wp_customize->add_control( new Dublin_Titles( $wp_customize, 'single_meta', array(
        'label' => __('Single posts', 'dublin'),
        'section' => 'blog_options',
        'settings' => 'dublin_options[titles]',
        'priority' => 16
        ) )
    );
    //Hide date
    $wp_customize->add_setting(
      'dublin_single_date',
      array(
        'sanitize_callback' => 'dublin_sanitize_checkbox',
        'default' => 0,     
      )   
    );
    $wp_customize->add_control(
      'dublin_single_date',
      array(
        'type' => 'checkbox',
        'label' => __('Hide post date &amp; author on single posts?', 'dublin'),
        'section' => 'blog_options',
        'priority' => 17,
      )
    );
    //Hide categories
    $wp_customize->add_setting(
      'dublin_single_cats',
      array(
        'sanitize_callback' => 'dublin_sanitize_checkbox',
        'default' => 0,     
      )   
    );
    $wp_customize->add_control(
      'dublin_single_cats',
      array(
        'type' => 'checkbox',
        'label' => __('Hide post categories on single posts?', 'dublin'),
        'section' => 'blog_options',
        'priority' => 18,
      )
    );
    //Hide tags
    $wp_customize->add_setting(
      'dublin_single_tags',
      array(
        'sanitize_callback' => 'dublin_sanitize_checkbox',
        'default' => 0,     
      )   
    );
    $wp_customize->add_control(
      'dublin_single_tags',
      array(
        'type' => 'checkbox',
        'label' => __('Hide post tags on single posts?', 'dublin'),
        'section' => 'blog_options',
        'priority' => 19,
      )
    );           
    //___Colors___//
     
    //Primary color
    $wp_customize->add_setting(
        'primary_color',
        array(
            'default'           => '#3fb8af',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'primary_color',
            array(
                'label' => __('Primary color', 'dublin'),
                'section' => 'colors',
                'settings' => 'primary_color',
                'priority' => 12
            )
        )
    );    
    //Header bg
    $wp_customize->add_setting(
        'header_bg_color',
        array(
            'default'           => '#20272b',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'header_bg_color',
            array(
                'label' => __('Header background color', 'dublin'),
                'section' => 'colors',
                'settings' => 'header_bg_color',
                'priority' => 13
            )
        )
    );
    //Footer bg
    $wp_customize->add_setting(
        'footer_bg_color',
        array(
            'default'           => '#20272b',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'footer_bg_color',
            array(
                'label' => __('Footer widget area background color', 'dublin'),
                'section' => 'colors',
                'settings' => 'footer_bg_color',
                'priority' => 14
            )
        )
    );
    //Footer credits bg
    $wp_customize->add_setting(
        'credits_bg_color',
        array(
            'default'           => '#101314',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'credits_bg_color',
            array(
                'label' => __('Footer credits area background color', 'dublin'),
                'section' => 'colors',
                'settings' => 'credits_bg_color',
                'priority' => 15
            )
        )
    );

    //Site title
    $wp_customize->add_setting(
        'site_title_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'site_title_color',
            array(
                'label' => __('Site title', 'dublin'),
                'section' => 'colors',
                'settings' => 'site_title_color',
                'priority' => 16
            )
        )
    );
    //Site description
    $wp_customize->add_setting(
        'site_desc_color',
        array(
            'default'           => '#5e5e5e',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'site_desc_color',
            array(
                'label' => __('Site description', 'dublin'),
                'section' => 'colors',
                'settings' => 'site_desc_color',
                'priority' => 17
            )
        )
    );
    //Contact info
    $wp_customize->add_setting(
        'contact_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'contact_color',
            array(
                'label' => __('Top contact info (address, social etc.)', 'dublin'),
                'section' => 'colors',
                'settings' => 'contact_color',
                'priority' => 18
            )
        )
    );    
    //Entry title
    $wp_customize->add_setting(
        'entry_title_color',
        array(
            'default'           => '#3c3c3c',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'entry_title_color',
            array(
                'label' => __('Entry title', 'dublin'),
                'section' => 'colors',
                'settings' => 'entry_title_color',
                'priority' => 19
            )
        )
    );  
    //Body
    $wp_customize->add_setting(
        'body_text_color',
        array(
            'default'           => '#3c3c3c',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'body_text_color',
            array(
                'label' => __('Text', 'dublin'),
                'section' => 'colors',
                'settings' => 'body_text_color',
                'priority' => 20
            )
        )
    );
    //___Fonts___//
    $wp_customize->add_section(
        'dublin_typography',
        array(
            'title' => __('Fonts', 'dublin' ),
            'priority' => 15,
        )
    );
    $font_choices = 
        array(
            'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',     
            'Droid Sans:400,700' => 'Droid Sans',
            'Lato:400,700,400italic,700italic' => 'Lato',
            'Arvo:400,700,400italic,700italic' => 'Arvo',
            'Lora:400,700,400italic,700italic' => 'Lora',
            'PT Sans:400,700,400italic,700italic' => 'PT Sans',
            'PT+Sans+Narrow:400,700' => 'PT Sans Narrow',
            'Arimo:400,700,400italic,700italic' => 'Arimo',
            'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
            'Bitter:400,700,400italic' => 'Bitter',
            'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
            'Open+Sans:400italic,700italic,400,700' => 'Open Sans',
            'Roboto:400,400italic,700,700italic' => 'Roboto',
            'Oswald:400,700' => 'Oswald',
            'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
            'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
            'Raleway:400,700' => 'Raleway',
            'Roboto Slab:400,700' => 'Roboto Slab',
            'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
            'Rokkitt:400' => 'Rokkitt',
        );
    
    $wp_customize->add_setting(
        'headings_fonts',
        array(
            'sanitize_callback' => 'dublin_sanitize_fonts',
        )
    );
    
    $wp_customize->add_control(
        'headings_fonts',
        array(
            'type' => 'select',
            'label' => __('Headings font (default: Oswald)', 'dublin'),
            'section' => 'dublin_typography',
            'choices' => $font_choices
        )
    );
    
    $wp_customize->add_setting(
        'body_fonts',
        array(
            'sanitize_callback' => 'dublin_sanitize_fonts',
        )
    );
    
    $wp_customize->add_control(
        'body_fonts',
        array(
            'type' => 'select',
            'label' => __('Body font (default: Source Sans Pro)', 'dublin'),
            'section' => 'dublin_typography',
            'choices' => $font_choices
        )
    );        
}
add_action( 'customize_register', 'dublin_customize_register' );

/**
 * Sanitization
 */
//Checkboxes
function dublin_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return 1;
	} else {
		return '';
	}
}
//Empty function that does nothing except stop Theme Check plugin from throwing errors on Dublin_Titles which does not require sanitization
function dublin_no_sanitize() {
}
//Fonts
function dublin_sanitize_fonts( $input ) {
    $valid = array(
            'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',     
            'Droid Sans:400,700' => 'Droid Sans',
            'Lato:400,700,400italic,700italic' => 'Lato',
            'Arvo:400,700,400italic,700italic' => 'Arvo',
            'Lora:400,700,400italic,700italic' => 'Lora',
            'PT Sans:400,700,400italic,700italic' => 'PT Sans',
            'PT+Sans+Narrow:400,700' => 'PT Sans Narrow',
            'Arimo:400,700,400italic,700italic' => 'Arimo',
            'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
            'Bitter:400,700,400italic' => 'Bitter',
            'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
            'Open+Sans:400italic,700italic,400,700' => 'Open Sans',
            'Roboto:400,400italic,700,700italic' => 'Roboto',
            'Oswald:400,700' => 'Oswald',
            'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
            'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
            'Raleway:400,700' => 'Raleway',
            'Roboto Slab:400,700' => 'Roboto Slab',
            'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
            'Rokkitt:400' => 'Rokkitt',
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}
//Text
function dublin_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function dublin_customize_preview_js() {
	wp_enqueue_script( 'dublin_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '', true );
}
add_action( 'customize_preview_init', 'dublin_customize_preview_js' );
