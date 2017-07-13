<?php
/*
Plugin Name: Logo Showcase
Plugin URI: http://themepoints.com/
Description: Logo Showcase plugin allow to Display a list of clients, supporters, partners or sponsors logos in your WordPress website easily.
Version: 1.5
Author: Themepoints
Author URI: http://themepoints.com
TextDomain: logoshowcase
License: GPLv2
*/


if ( ! defined( 'ABSPATH' ) )
die( "Can't load this file directly" );


define('LOGO_SHOWCASE_WP_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
define('logo_showcase_wp_plugin_dir', plugin_dir_path( __FILE__ ) );



/*==========================================================================
	After setup plugins
==========================================================================*/

function logo_showcase_wordpress_init() {

	// include pricing post type
    include("inc/logo-showcase-wordpress-post-type.php");
	// register post type
	add_action('init', 'logo_showcase_wordpress_post_types_register');
	// custom title
	add_filter( 'enter_title_here', 'logo_showcase_wordpress_title' );
	// Include Meta Box Class File
	include( plugin_dir_path( __FILE__ ) . 'metabox/custom-meta-boxes.php' );
	// Include pricing theme File
	include( plugin_dir_path( __FILE__ ) . 'themes/logo-showcase-wordpress-themes.php' );	
	// enqueue scripts
	add_action('wp_enqueue_scripts', 'logo_showcase_wordpress_post_script');
	// add text domain
	add_action('plugins_loaded', 'logo_showcase_wordpress_load_textdomain');
	// admin enqueue scripts
	add_action('admin_enqueue_scripts', 'logo_showcase_wordpress_admin_enqueue_scripts');
	// add meta boxes
	add_action( 'add_meta_boxes', 'logo_showcase_wordpress_add_custom_box' );
	// Do something with the data entered
	add_action( 'save_post', 'logo_showcase_wordpress_save_postdata' );
	// filter meta boxes
	add_filter( 'cmb_meta_boxes', 'logo_showcase_wordpress_filter_meta_box' );
	add_filter('widget_text', 'do_shortcode');	
}
add_action('after_setup_theme', 'logo_showcase_wordpress_init');



/*==========================================================================
	pricing table wordpress enqueue scripts
==========================================================================*/
function logo_showcase_wordpress_post_script()
	{
    wp_enqueue_script("jquery-ui-sortable");
    wp_enqueue_script("jquery-ui-draggable");
    wp_enqueue_script("jquery-ui-droppable");
	wp_enqueue_style('logo-showcase-style', LOGO_SHOWCASE_WP_PLUGIN_PATH.'css/logo-showcase-wordpress.css');	
	wp_enqueue_style('logo-showcase-owl', LOGO_SHOWCASE_WP_PLUGIN_PATH.'css/owl.carousel.css');	
	wp_enqueue_style('logo-showcase-owl-theme', LOGO_SHOWCASE_WP_PLUGIN_PATH.'css/owl.theme.css');	
	wp_enqueue_style('logo-showcase-owl-transitions', LOGO_SHOWCASE_WP_PLUGIN_PATH.'css/owl.transitions.css');	
	wp_enqueue_script('logo-showcase-owl-js', plugins_url('js/owl.carousel.js', __FILE__), array('jquery'), '2.4', true);	
	}


/*==========================================================================
	logo showcase wordpress Load Translation
==========================================================================*/
function logo_showcase_wordpress_load_textdomain(){
	load_plugin_textdomain('logoshowcase', false, dirname( plugin_basename( __FILE__ ) ) .'/languages/' );
}


/*==========================================================================
	logo showcase wordpress Admin enqueue scripts
==========================================================================*/
function logo_showcase_wordpress_admin_enqueue_scripts(){
		global $typenow;

		if(($typenow == 'tplogoshowcase')){
	    wp_enqueue_style('logo-showcase-admin-css', LOGO_SHOWCASE_WP_PLUGIN_PATH.'admin/css/logo-showcase-backend-admin.css');

		wp_enqueue_script('logo-showcase-admin-js', LOGO_SHOWCASE_WP_PLUGIN_PATH.'admin/js/logo-showcase-backend-admin.js', array('jquery'), '1.0.0', true );			
		
        wp_enqueue_style('wp-color-picker');	
        wp_enqueue_script( 'logo_showcase_color_picker', plugins_url('admin/js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
        wp_enqueue_script("jquery-ui-sortable");
        wp_enqueue_script("jquery-ui-draggable");
        wp_enqueue_script("jquery-ui-droppable");		
		}
}




/*==========================================================================
	pricing table wordpress meta boxes
==========================================================================*/

function logo_showcase_wordpress_filter_meta_box( $meta_boxes ) {
  $meta_boxes[] = array(

    'id'          => 'lowo_showcase_wordpress_feature',
    'title'       => 'Logo Showcase Column Features',
    'pages'       => array('tplogoshowcase'),
    'context'     => 'normal',
    'priority'    => 'high',
    'show_names'  => true, 
    'fields' => array(

      array(
        'id'   => 'logo_showcase_columns',
        'name'    => 'Logo Showcase Details',
        'type' => 'group',
        'repeatable'     => true,
        'repeatable_max' => 8,
        
        'fields' => array(

          array(
            'id'              => 'logo_showcase_title',
            'name'            => 'Logo Title',                
            'type'            => 'text',
            'cols'            => 4
            ),          

			
          array(
            'id'              => 'logo_showcase_link_url',
            'name'            => 'Url',                
            'type'            => 'text_url',
            'default'         => '#',
            'cols'            => 4              
            ),

          array(
            'id'              => 'logo_showcase_link_target',
            'name'            => 'Target Link (_self/_blank)',                
            'type'            => 'text',
            'cols'            => 4,
            'default'         => '_self'
            ),
			
          array(
            'id'              => 'logo_showcase_uploader',
            'name'            => 'Upload Logo',                
            'type'            => 'image',
            'cols'            => 4
            )
			

          )
      )
  )
);


return $meta_boxes;
}


/*==========================================================================
	Logo Showcase wordpress register shortcode
==========================================================================*/
function logo_showcase_wordpress_shortcode_register($atts, $content = null){
	$atts = shortcode_atts(
		array(
			'id' => "",
		), $atts);
		global $post;
		$post_id = $atts['id'];
		
		$content = '';
        $content.= Logo_Showcase_wordpress_table_body($post_id);
		return $content;
}

// shortcode hook
add_shortcode('logo_showcase', 'logo_showcase_wordpress_shortcode_register');


