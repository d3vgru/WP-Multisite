<?php
/**
Plugin Name: LazyLoader
Author: PageLines
Description: Defer image loading when image is off screen.
Version: 0.1
PageLines: true
Plugin URI: http://www.pagelines.com/
 */

class LazyLoad_Images {

	const version = '0.1';

	var $base_url = '';

	function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
		add_filter( 'post_thumbnail_html', array( $this, 'add_image_placeholders' ) );
		add_filter( 'the_content', array( $this, 'add_image_placeholders' ) );
	}

	function add_scripts() {
		wp_enqueue_script( 'wpcom-lazy-load-images',  $this->get_url( 'js/lazy-load.js' ), array( 'jquery', 'jquery-sonar' ), self::version, true );
		wp_enqueue_script( 'jquery-sonar', $this->get_url( 'js/jquery.sonar.min.js' ), array( 'jquery' ), self::version, true );
	}

	function add_image_placeholders( $content ) {
		// Don't lazyload for feeds, previews, mobile
		if( is_feed() || ( function_exists( 'is_mobile' ) && is_mobile() ) || isset( $_GET['preview'] ) )
			return $content;

		// In case you want to change the placeholder image
		$image = apply_filters( 'lazyload_images_placeholder_image', $this->get_url( 'images/1x1.trans.gif' ) );

		// This is a pretty simple regex, but it works
		$content = preg_replace( '#<img([^>]+?)src=#', sprintf( '<img${1}src="%s" data-lazy-src=', $image ), $content );

		return $content;
	}

	function get_url( $path = '' ) {
		return plugins_url( ltrim( $path, '/' ), __FILE__ );
	}
}

new LazyLoad_Images;
