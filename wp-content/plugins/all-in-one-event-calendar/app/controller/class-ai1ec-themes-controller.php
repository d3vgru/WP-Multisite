<?php
//
//  class-ai1ec-themes-controller.php
//  all-in-one-event-calendar
//
//  Created by The Seed Studio on 2012-04-05.
//

/**
 * Ai1ec_Themes_Controller class
 *
 * @package Controllers
 * @author The Seed Studio
 **/
class Ai1ec_Themes_Controller {
	/**
	 * _instance class variable
	 *
	 * Class instance
	 *
	 * @var null | object
	 **/
	private static $_instance = NULL;

	/**
	 * get_instance function
	 *
	 * Return singleton instance
	 *
	 * @return object
	 **/
	static function get_instance() {
		if( self::$_instance === NULL ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * Default constructor
	 **/
	private function __construct() { }

	/**
   * Returns the root path of ai1ec-themes.
	 *
	 * @return string
	 **/
	public function template_root_path( $template ) {
		return AI1EC_THEMES_ROOT . '/' . $template;
	}

	/**
	 * Returns the root URL of ai1ec-themes.
	 *
	 * @return string
	 **/
	public function template_root_url( $template ) {
		return AI1EC_THEMES_URL . '/' . $template;
	}

  /**
   * Returns the path to the active calendar theme.
   *
   * @return string
   */
  public function active_template_path() {
    return apply_filters(
      'ai1ec_template_root_path',
      apply_filters(
        'ai1ec_template',
        get_option( 'ai1ec_template', AI1EC_DEFAULT_THEME_NAME )
      )
    );
  }

  /**
   * Returns the URL to the active calendar theme.
   *
   * @return string
   */
  public function active_template_url() {
    return apply_filters(
      'ai1ec_template_root_url',
      apply_filters(
        'ai1ec_template',
        get_option( 'ai1ec_template', AI1EC_DEFAULT_THEME_NAME )
      )
    );
  }

	/**
	 * are_themes_available function
	 *
	 * @return bool
	 **/
	public function are_themes_available() {
		//  are themes folder and Vortex theme available?
		if( @is_dir( AI1EC_THEMES_ROOT ) === true && @is_dir( AI1EC_DEFAULT_THEME_PATH ) === true ) {
			return true;
		} else {
			// try to create AI1EC_THEMES_ROOT
			if( ! @mkdir( AI1EC_THEMES_ROOT ) )
				return false;

			// copy themes-ai1ec from plugin's root to wp-content's themes root
			$this->copy_directory( AI1EC_PATH . '/' . AI1EC_THEMES_FOLDER, AI1EC_THEMES_ROOT );

			if( @is_dir( AI1EC_THEMES_ROOT ) === false || @is_dir( AI1EC_DEFAULT_THEME_PATH ) === false )
				return false;
		}
		return true;
	}

	/**
	 * copy_directory function
	 *
	 * @return void
	 **/
	private function copy_directory( $source, $destination ) {
		if( is_dir( $source ) ) {
			@mkdir( $destination );
			$directory = dir( $source );
			while( FALSE !== ( $readdirectory = $directory->read() ) ) {
				if( $readdirectory == '.' || $readdirectory == '..' ) {
					continue;
				}
				$PathDir = $source . '/' . $readdirectory;
				if( is_dir( $PathDir ) ) {
					$this->copy_directory( $PathDir, $destination . '/' . $readdirectory );
					continue;
				}
				copy( $PathDir, $destination . '/' . $readdirectory );
			}

			$directory->close();
		} else {
			copy( $source, $destination );
		}
	}

  /**
   * Called immediately after WP theme's functions.php is loaded. Load our own
   * theme's functions.php at this time, and the default theme's functions.php.
   */
  function setup_theme() {
    $functions_files = array(
      $this->active_template_path() . '/functions.php',
      AI1EC_DEFAULT_THEME_PATH . '/functions.php',
    );

    $functions_files = array_unique( $functions_files );

    foreach( $functions_files as $file ) {
      if ( file_exists( $file ) ) {
        include( $file );
      }
    }
  }
}
// END class
