<?php
/**
 * Dublin functions and definitions
 *
 * @package Dublin
 */


if ( ! function_exists( 'dublin_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function dublin_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Dublin, use a find and replace
	 * to change 'dublin' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'dublin', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Set the content width based on the theme's design and stylesheet.
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 640;
	}

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size('dublin-project', 350, 250, true);
	add_image_size('dublin-thumb', 720);

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'dublin' ),
		'social' => __( 'Social', 'dublin' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'dublin_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // dublin_setup
add_action( 'after_setup_theme', 'dublin_setup' );





/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function dublin_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'dublin' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '<span class="widget-deco"></span></h3>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer A', 'dublin' ),
		'id'            => 'sidebar-3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );	
	register_sidebar( array(
		'name'          => __( 'Footer B', 'dublin' ),
		'id'            => 'sidebar-4',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );	
	register_sidebar( array(
		'name'          => __( 'Footer C', 'dublin' ),
		'id'            => 'sidebar-5',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );	
	register_sidebar( array(
		'name'          => __( 'Search bar', 'dublin' ),
		'id'            => 'sidebar-6',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );		

	//Register the custom widgets
	register_widget( 'Dublin_Video' );
	register_widget( 'Dublin_Recent_Posts' );
	register_widget( 'Dublin_Recent_Comments' );

}
add_action( 'widgets_init', 'dublin_widgets_init' );

/**
 * Load the custom widgets
 */
require get_template_directory() . "/widgets/video-widget.php";
require get_template_directory() . "/widgets/recent-posts.php";
require get_template_directory() . "/widgets/recent-comments.php";


/**
 * Enqueue scripts and styles.
 */

function dublin_scripts() {

	wp_enqueue_style( 'dublin-bootstrap', get_template_directory_uri() . '/css/bootstrap/css/bootstrap.min.css', array(), true );

	wp_enqueue_style( 'dublin-style', get_stylesheet_uri() );

	$headings_font = get_theme_mod('headings_fonts');
	$body_font = get_theme_mod('body_fonts');
	if( $headings_font ) {
		wp_enqueue_style( 'dublin-headings-fonts', '//fonts.googleapis.com/css?family='. esc_attr($headings_font) );	
	} else {
		wp_enqueue_style( 'dublin-headings-fonts', '//fonts.googleapis.com/css?family=Oswald:400');
	}	
	if( $body_font ) {
		wp_enqueue_style( 'dublin-body-fonts', '//fonts.googleapis.com/css?family='. esc_attr($body_font) );	
	} else {
		wp_enqueue_style( 'dublin-body-fonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,400italic,700italic');
	}	

	wp_enqueue_style( 'dublin-font-awesome', get_template_directory_uri() . '/fonts/font-awesome.min.css' );	

	wp_enqueue_script( 'dublin-fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array('jquery'), true );	

	wp_enqueue_script( 'dublin-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), true );	

	wp_enqueue_script( 'dublin-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'dublin-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
    	
}
add_action( 'wp_enqueue_scripts', 'dublin_scripts' );

/**
 * Excerpt length
 */
function dublin_excerpt_length( $length ) {
	
	$excerpt = get_theme_mod('exc_lenght', '55');
	return $excerpt;

}
add_filter( 'excerpt_length', 'dublin_excerpt_length', 999 );

/**
 * Load html5shiv
 */
function dublin_html5shiv() {
    echo '<!--[if lt IE 9]>' . "\n";
    echo '<script src="' . esc_url( get_template_directory_uri() . '/js/html5shiv.js' ) . '"></script>' . "\n";
    echo '<![endif]-->' . "\n";
}
add_action( 'wp_head', 'dublin_html5shiv' ); 

/**
 * Row style for the page builder
 */
function dublin_panels_row_styles($styles) {
	$styles['full'] = __('Full width', 'dublin');
	return $styles;
}
add_filter('siteorigin_panels_row_styles', 'dublin_panels_row_styles');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Page Builder row styles
 */
require get_template_directory() . '/inc/row-styles.php';

/**
 * Dynamic styles
 */
require get_template_directory() . '/styles.php';

/**
 *TGM Plugin activation.
 */
require_once dirname( __FILE__ ) . '/tgm/class-tgm-plugin-activation.php';
 
add_action( 'tgmpa_register', 'dublin_recommend_plugin' );
function dublin_recommend_plugin() {
 
    $plugins = array(
        array(
            'name'               => 'Page Builder by SiteOrigin',
            'slug'               => 'siteorigin-panels',
            'required'           => false,
        )       
    );
 
    tgmpa( $plugins);
 
}



// Our custom post type function
/*function create_posttype() {

     register_post_type( 'article_ads',
        array(
            'labels' => array(
                'name' => __( 'Articles ', 'post type general name'),
                'singular_name' => __( 'Articles & Ads', 'post type singular name' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'article_ads'),
            'category' => true,
            'supports'            => array( 'title', 'editor', 'thumbnail' , 'custom-fields','category'),
        )
    );

}*/
// Hooking up our function to theme setup
//add_action( 'init', 'create_posttype' );



// Hooking up our function to theme setup


  function register_destination_post_type() {
    $args = array(
        'labels'    => array(
            'name'               => __( 'Destinations', 'mt-destination' ),
            'singular_name'      => __( 'Destination', 'mt-destination' ),
            'menu_name'          => __( 'Destinations', 'mt-destination' ),
            'name_admin_bar'     => __( 'Destination', 'mt-destination' ),
            'add_new'            => __( 'Add New', 'mt-destination' ),
            'add_new_item'       => __( 'Add New Destination', 'mt-destination' ),
            'new_item'           => __( 'New Destination', 'mt-destination' ),
            'edit_item'          => __( 'Edit Destination', 'mt-destination' ),
            'view_item'          => __( 'View Destination', 'mt-destination' ),
            'all_items'          => __( 'All Destinations', 'mt-destination' ),
            'search_items'       => __( 'Search Destinations', 'mt-destination' ),
            'parent_item_colon'  => __( 'Parent Destinations:', 'mt-destination' ),
            'not_found'          => __( 'No Destinations found.', 'mt-destination' ),
            'not_found_in_trash' => __( 'No Destinations found in Trash.', 'mt-destination' )
        ),
        'query_var'              => 'mt_destinations',
        'public'                 => true,  // If you don't want it to make public, make it false
        'publicly_queryable'     => true,  // you should be able to query it
        'show_ui'                => true,  // you should be able to edit it in wp-admin
        'has_archive'            => 'destinations',    //true,
        'menu_position'          => 51,
        'supports'               => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    );
    flush_rewrite_rules();

    register_post_type('mt_destinations', $args);
}
add_action( 'init', 'register_destination_post_type' );


function taxonomies() {
    $taxonomies = array();

    $taxonomies['destination_category'] = array(
        'hierarchical'  => true,
        'query_var'     => 'destination-category',
        'rewrite'       => array(
            'slug'      => 'destination/category'
        ),
        'labels'            => array(
            'name'          => 'Destination Category',
            'singular_name' => 'Destination Category',
            'edit_item'     => 'Edit Destination Category',
            'update_item'   => 'Update Destination Category',
            'add_new_item'  => 'Add Destination Category',
            'new_item_name' => 'Add New Destination Category',
            'all_items'     => 'All Destination Category',
            'search_items'  => 'Search Destination Category',
            'popular_items' => 'Popular Destination Category',
            'separate_items_with_commas' => 'Separate Destination Categories with Commas',
            'add_or_remove_items' => 'Add or Remove Destination Categories',
            'choose_from_most_used' => 'Choose from most used categories',
        ),
        'show_admin_column' => true
    );

    $taxonomies['destination_location'] = array(
            'hierarchical'  => true,
            'query_var'     => 'location',
            'rewrite'       => array(
                'slug'      => 'destinations' 
            ),
            'labels'            => array(
                'name'          => 'Location',
                'singular_name' => 'Location',
                'edit_item'     => 'Edit Location',
                'update_item'   => 'Update Location',
                'add_new_item'  => 'Add Location',
                'new_item_name' => 'Add New Location',
                'all_items'     => 'All Location',
                'search_items'  => 'Search Location',
                'popular_items' => 'Popular Location',
                'separate_items_with_commas' => 'Separate Location Categories with Commas',
                'add_or_remove_items' => 'Add or Remove Location Categories',
                'choose_from_most_used' => 'Choose from most used categories',
            ),
            'show_admin_column' => true
        );

    flush_rewrite_rules();

    foreach( $taxonomies as $name => $args ) {
        register_taxonomy( $name, array( 'mt_destinations' ), $args );
    }
}
add_action( 'init', 'taxonomies' );


