<?php
/**
Plugin Name: nrelate Related Content
Plugin URI: http://www.nrelate.com
Description: Easily display related content on your website. Click on <a href="admin.php?page=nrelate-related">nrelate &rarr; Related Content</a> to configure your settings.
Author: <a href="http://www.nrelate.com">nrelate</a> and <a href="http://www.slipfire.com">SlipFire</a>
Version: 0.51.1
Author URI: http://nrelate.com/


// Copyright (c) 2010 nrelate, All rights reserved.
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// This is a plugin for WordPress
// http://wordpress.org/
//
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************
**/

/**
 * Define Plugin constants
 */
define( 'NRELATE_RELATED_PLUGIN_VERSION', '0.51.1' );
define( 'NRELATE_RELATED_ADMIN_SETTINGS_PAGE', 'nrelate-related' );
define( 'NRELATE_RELATED_ADMIN_VERSION', '0.05.1' );
define( 'NRELATE_RELATED_NAME' , __('Related Content','nrelate'));
define( 'NRELATE_RELATED_DESCRIPTION' , sprintf( __('The related content plugin allows you to display related posts on your website.','nrelate')));

if(!defined('NRELATE_CSS_URL')) { define( 'NRELATE_CSS_URL', 'http://static.nrelate.com/common_wp/' . NRELATE_RELATED_ADMIN_VERSION . '/' ); }
if(!defined('NRELATE_BLOG_ROOT')) { define( 'NRELATE_BLOG_ROOT', urlencode(str_replace(array('http://','https://'), '', get_bloginfo( 'url' )))); }
if(!defined('NRELATE_JS_DEBUG')) { define( 'NRELATE_JS_DEBUG', isset($_REQUEST['nrelate_debug']) ? true : false ); }

/**
 * Define Path constants
 */
// Generic: will be assigned to the first nrelate plugin that loads
if (!defined( 'NRELATE_PLUGIN_BASENAME')) { define( 'NRELATE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); }
if (!defined( 'NRELATE_PLUGIN_NAME')) { define( 'NRELATE_PLUGIN_NAME', trim( dirname( NRELATE_PLUGIN_BASENAME ), '/' ) ); }
if (!defined( 'NRELATE_PLUGIN_DIR')) { define( 'NRELATE_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . NRELATE_PLUGIN_NAME ); }
if (!defined('NRELATE_ADMIN_DIR')) { define( 'NRELATE_ADMIN_DIR', NRELATE_PLUGIN_DIR .'/admin'); }
if (!defined('NRELATE_ADMIN_URL')) { define( 'NRELATE_ADMIN_URL', plugins_url( NRELATE_PLUGIN_NAME . '/admin')); }
if (!defined('NRELATE_API_URL')) { define ('NRELATE_API_URL', is_ssl() ? 'https://api.nrelate.com' : 'http://api.nrelate.com'); }
if (!defined('NRELATE_EXTENSIONS')) { define ('NRELATE_EXTENSIONS', NRELATE_ADMIN_DIR . '/extensions' ); }

// Plugin specific
define( 'NRELATE_RELATED_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'NRELATE_RELATED_PLUGIN_NAME', trim( dirname( NRELATE_RELATED_PLUGIN_BASENAME ), '/' ) );
define( 'NRELATE_RELATED_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . NRELATE_RELATED_PLUGIN_NAME );
define( 'NRELATE_RELATED_PLUGIN_URL', plugins_url( NRELATE_RELATED_PLUGIN_NAME ) );
define( 'NRELATE_RELATED_SETTINGS_DIR', NRELATE_RELATED_PLUGIN_DIR . '/related_settings' );
define( 'NRELATE_RELATED_SETTINGS_URL', NRELATE_RELATED_PLUGIN_URL . '/related_settings' );
define( 'NRELATE_RELATED_ADMIN_DIR', NRELATE_RELATED_PLUGIN_DIR . '/admin' );
define( 'NRELATE_RELATED_IMAGE_DIR', NRELATE_RELATED_PLUGIN_URL . '/images' );

// Load WP_Http
if( !class_exists( 'WP_Http' ) )
	include_once( ABSPATH . WPINC. '/class-http.php' );
	
// Load Language
load_plugin_textdomain('nrelate-related', false, NRELATE_RELATED_PLUGIN_DIR . '/language');

/**
 * Get the product status of all nrelate products.
 *
 * @since 0.49.0
 */
if ( !defined( 'NRELATE_PRODUCT_STATUS' ) ) { require_once ( NRELATE_RELATED_ADMIN_DIR . '/product-status.php' ); }

/**
 * Load plugin styles if another nrelate plugin has not loaded it yet.
 *
 * @since 0.46.0
 */
if (!isset($nrelate_thumbnail_styles)) { require_once ( NRELATE_RELATED_ADMIN_DIR . '/styles.php' ); }

/**
 * Initializes the plugin and it's features.
 *
 * @since 0.1
 */
if (is_admin()) {

		//load common admin files if not already loaded from another nrelate plugin
		if ( ! defined( 'NRELATE_COMMON_LOADED' ) ) { require_once ( NRELATE_RELATED_ADMIN_DIR . '/common.php' ); }
		if ( ! defined( 'NRELATE_COMMON_50_LOADED' ) ) { require_once ( NRELATE_RELATED_ADMIN_DIR . '/common-50.php' ); }
		
		//load plugin status
		require_once ( NRELATE_RELATED_SETTINGS_DIR . '/related-plugin-status.php' );
		
		//load related menu
		require_once ( NRELATE_RELATED_SETTINGS_DIR . '/related-menu.php' );
		
		// Load Tooltips
		if (!isset($nrelate_tooltips)) { require_once ( NRELATE_RELATED_ADMIN_DIR . '/tooltips.php' ); }
		
		// temporary file for 0.50.0 upgrades
		require_once ( 'nrelate-abstraction.php' );
}



/** Load common frontend functions **/
if ( ! defined( 'NRELATE_COMMON_FRONTEND_LOADED' ) ) { require_once ( NRELATE_RELATED_ADMIN_DIR . '/common-frontend.php' ); }
if ( ! defined( 'NRELATE_COMMON_FRONTEND_50_LOADED' ) ) { require_once ( NRELATE_RELATED_ADMIN_DIR . '/common-frontend-50.php' ); }

// temporary file for 0.50.0 upgrades
require_once ( 'nrelate-abstraction-frontend.php' );


/*
 * Load related styles
 *
 * since v.44.0
 * updated v46.0
 */
function nrelate_related_styles() {
	if ( nrelate_related_is_loading() ) {

		global $nrelate_thumbnail_styles, $nrelate_thumbnail_styles_separate, $nrelate_text_styles, $nrelate_text_styles_separate, $rc_styleclass, $rc_layout;
		$options = get_option('nrelate_related_options');
		$style_options = get_option('nrelate_related_options_styles');
		$ad_options = get_option('nrelate_related_options_ads');

		// Are we loading separate ads?
		if ($ad_options['related_ad_placement']=='Separate') {
			$style_suffix = '_separate';
		} else {
			$style_suffix = '';
		}

		// Thumbnails or Text?
		if ($options['related_thumbnail']=='Thumbnails') {
			$style_type = 'related_thumbnails_style' . $style_suffix;
			$style_array = 'nrelate_thumbnail_styles' . $style_suffix;
		} else {
			$style_type = 'related_text_style' . $style_suffix;
			$style_array = 'nrelate_text_styles' . $style_suffix;
		}
		
		// Get style name (i.e. Default)
		$style_name = $style_options [$style_type];
				
		// Get the style sheet and class from STYLES.PHP
		$style_array_convert = ${$style_array};
		$stylesheet = $style_array_convert[$style_name]['stylesheet'];
		$rc_styleclass = $style_array_convert[$style_name]['styleclass'];
		$rc_layout = $style_array_convert[$style_name]['layout'];


		// Get full stylesheet url
		$nr_css_url = NRELATE_CSS_URL . $stylesheet . '.min.css';
		
		/* For local development */
		//$nr_css_url = NRELATE_RELATED_PLUGIN_URL . '/' . $stylesheet . '.css';
		
		// Only load if style not set to NONE
		if ('none'!=$style_options[$style_type]) {
			nrelate_ie6_thumbnail_style();
			wp_register_style('nrelate-style-'. $style_name . "-" . str_replace(".","-",NRELATE_RELATED_ADMIN_VERSION), $nr_css_url, false, null );
			wp_enqueue_style( 'nrelate-style-'. $style_name . "-" . str_replace(".","-",NRELATE_RELATED_ADMIN_VERSION) );
		}
	}
}
add_action('wp_enqueue_scripts', 'nrelate_related_styles');

/*
 * Check if nrelate is loading (frontend only)
 *
 * @since 0.47.0
 */
function nrelate_related_is_loading() {
    $is_loading = false;
   
    if ( !is_admin() ) {   
        $options = get_option('nrelate_related_options');
       
        if ( isset($options['related_where_to_show']) ) {
            foreach ( (array)$options['related_where_to_show'] as $cond_tag ) {
                if ( function_exists( $cond_tag ) && call_user_func( $cond_tag ) ) {
                    $is_loading = true;
                    break;
                }
            }
        }
    }
   
    return apply_filters( 'nrelate_related_is_loading', $is_loading);
}



/**
 * Inject related posts into the content
 *
 * Stops injection into themes that use get_the_excerpt in their meta description
 *
 * @since 0.1
 */
function nrelate_related_inject($content) {
	global $post;
	
	if ( nrelate_should_inject('related') ) {
		$nrelate_related_options = get_option( 'nrelate_related_options' );

		$related_loc_top = $nrelate_related_options['related_loc_top'];
		$related_loc_bottom = $nrelate_related_options['related_loc_bottom'];

		if ($related_loc_top == "on"){
			$content_top = nrelate_related(true);
		} else {
			$content_top = '';
		};

		if ($related_loc_bottom == "on"){
			$content_bottom = nrelate_related(true);
		} else {
			$content_bottom = '';
		};

		$original = $content;

		$content  = $content_top;
		$content .= $original;
		$content .= $content_bottom;

	}
	
	return $content;
}
add_filter( 'the_content', 'nrelate_related_inject', 10 );
add_filter( 'the_excerpt', 'nrelate_related_inject', 10 );


/**
 * nrelate related shortcode
 *
 * @since 0.1
 */
function nrelate_related_shortcode ($atts) {
	extract(shortcode_atts(array(
		"float" => 'left',
		"width" => '100%',
	), $atts));

    return '<div class="nr-shortcode" style="float:'.$float.';width:'.$width.';\">'.nrelate_related(true).'</div>';
}
add_shortcode('nrelate-related', 'nrelate_related_shortcode');

/**
 * Register the widget.
 *
 * @uses register_widget() Registers individual widgets.
 * @link http://codex.wordpress.org/WordPress_Widgets_Api
 *
 * @written in 0.1
 * @live in 0.41.0
 */
function nrelate_related_load_widget() {

	//Load widget file.
	require_once( 'related-widget.php' );

	// Register widget.
	register_widget( 'nrelate_Widget_Related' );
};
add_action( 'widgets_init', 'nrelate_related_load_widget' );

/**
 * Primary function
 *
 * Gets options and passes to nrelate via Javascript
 *
 * @since 0.1
 */

$nr_counter = 0;

function nrelate_related($opt=false) {
	global $post, $nr_counter, $rc_styleclass, $rc_layout;
	
	$animation_fix = $nr_rc_nonjsbody = $nr_rc_nonjsfix = $nr_rc_js_str = '';
	
	if ( nrelate_related_is_loading() )  {	
		$nr_counter++;
		
		$nrelate_related_options = get_option('nrelate_related_options');
		$style_options = get_option('nrelate_related_options_styles');
		$style_code = 'nrelate_' . $rc_styleclass;
		$nr_width_class = 'nr_' . (($nrelate_related_options['related_thumbnail']=='Thumbnails') ? $nrelate_related_options['related_thumbnail_size'] : "text");
		
		// Get the page title and url array
		$nrelate_title_url = nrelate_title_url();
		
		$nonjs=$nrelate_related_options['related_nonjs'];
		
		$nr_url = "http://api.nrelate.com/rcw_wp/" . NRELATE_RELATED_PLUGIN_VERSION . "/?tag=nrelate_related";
		$nr_url .= "&keywords=$nrelate_title_url[post_title]&domain=" . NRELATE_BLOG_ROOT . "&url=$nrelate_title_url[post_urlencoded]&nr_div_number=".$nr_counter;
		$nr_url .= is_home() ? '&source=hp' : '';
		
		//is loaded only once per page for related
		if (!defined('NRELATE_RELATED_HOME')) {
			define('NRELATE_RELATED_HOME', true);
			
			$nrelate_related_options_ads = get_option('nrelate_related_options_ads');
			$animation_fix = '<style type="text/css">.nrelate_related .nr_sponsored{ left:0px !important; }</style>';
			
			if (!empty($nrelate_related_options_ads['related_ad_animation'])) {
				$animation_fix = '';
			}
		}
		//is loaded only once per page for nrelate
		if (!defined('NRELATE_HOME')) {
			define('NRELATE_HOME', true);
			$domain = addslashes(NRELATE_BLOG_ROOT);
			$nr_domain_init = "nRelate.domain = \"{$domain}\";";
		} else {
			$nr_domain_init = '';
		}
		
	if($nonjs){
		    $args=array("timeout"=>2);
		    $response = wp_remote_get($nr_url."&nonjs=1",$args);

		    if( !is_wp_error( $response ) ){
			    if($response['response']['code']==200 && $response['response']['message']=='OK'){
				    $nr_rc_nonjsbody=$response['body'];
			   		$nr_rc_nonjsfix='<script type="text/javascript">'.$nr_domain_init.'nRelate.fixHeight("nrelate_related_'.$nr_counter.'");';
			   		$nr_rc_nonjsfix.='nRelate.adAnimation("nrelate_related_'.$nr_counter.'");';
					$nr_rc_nonjsfix.='nRelate.tracking("rc");</script>';
			    }else{
			    	$nr_rc_nonjsbody="<!-- nrelate server not 200. -->";
			    }
		    }else{
		    	$nr_rc_nonjsbody="<!-- WP-request to nrelate server failed. -->";
		    }
	}else{
		$nr_rc_js_str= <<<EOD
<script type="text/javascript">
	/* <![CDATA[ */
		$nr_domain_init
		var entity_decoded_nr_url = jQuery('<span/>').html("$nr_url").text();
		nRelate.getNrelatePosts(entity_decoded_nr_url);
	/* ]]> */
	</script>
EOD;
	}
		
		$markup = <<<EOD
$animation_fix
<div class="nr_clear"></div>	
	<div id="nrelate_related_{$nr_counter}" class="nrelate nrelate_related $style_code nr_$rc_layout $nr_width_class">$nr_rc_nonjsbody</div>
	<!--[if IE 6]>
		<script type="text/javascript">jQuery('.$style_code').removeClass('$style_code');</script>
	<![endif]-->
	$nr_rc_nonjsfix
	$nr_rc_js_str
<div class="nr_clear"></div>
EOD;

		if ($opt){
			return $markup;
			}else{
			echo $markup;
		}
	}
}


//Activation and Deactivation functions
//Since 0.47.4, added uninstall hook
register_activation_hook(__FILE__, 'nr_rc_add_defaults');
register_deactivation_hook(__FILE__, 'nr_rc_deactivate');
register_uninstall_hook(__FILE__, 'nr_rc_uninstall');
?>