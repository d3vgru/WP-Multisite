<?php
/**
 * Implements sourcing thumbnails from http://www.shrinktheweb.com
 *
 * Dependent on PHP5, but could be easily back-ported.  All config
 * information is defined in constants.  No reason to ever create
 * an instance of this class, hence abstract.
 *
 * adjusted by: Jeff Lambert, WEBphysiology.com
 * adjusted on: 2010-01-09
 * 
 * updated by: Jeff Lambert, WEBphysiology.com
 * updated on: 2011-09-07
 * updated   : Since non-Pro STW cannot be sized like other images within the portfolio, the code was enhanced to get the largest
 *             image from STW that is <= the image width defined within the WEBphysiology Portfolio Options
 * 
 * updated by: Jeff Lambert, WEBphysiology.com
 * updated on: 2011-03-28
 * updated   : Updates made to support the changes to ShrinkTheWeb.com that removes local caching of thumbnails (GetScaledThumbnail)
 * 
 * updated by: Jeff Lambert, WEBphysiology.com
 * updated on: 2010-02-23
 * updated   : enhanced code that determines if the URL passed in is an inside page or not and updated
 *             the inside page URL argument from "inside" to "stwinside" as thie former was an outdated
 *             ShrinkTheWeb Pro argument
 * 
 * updated by: Jeff Lambert, WEBphysiology.com
 * updated on: 2010-01-21
 * updated   : added the "&inside=1" parameter so that URLs can be deeper than the primary domain
 *             this does require that the user purchase a higher level of ShrinkTheWeb subscription
 *             beyond Free
 * 
 * updated by: Jeff Lambert, WEBphysiology.com
 * updated on: 2010-01-11
 * updated   : added use of CURL when it is available to get the XML from ShrinkTheWeb.com
 *             added error reporting if thumbnail isn't retrieved
 * 
 * @author Entraspan, Based in part on STW sample code
 * @copyright Open Source/Creative Commons
 */

abstract class AppSTW {
	
    const THUMBNAIL_DIR = "/cache";
    const CACHE_DAYS = 3; // used 7 for Alexa!

    /**
     * Refreshes the thumbnail if it is expired or creates it if it does
     * not exist.  There is no cleanup of the thumbnails for ones that don't
     * get used again, e.g. find /static/images/thumbnails -type f -mtime +7 -delete
     *
     * Every combination of url and call arguments results in a unique filename
     * through a MD5 hash.  The size argument can also be an array where you can
     * add any parameter you wish to the request, or override any default.
     *
     * It is up to the calling function to decide what to do with the results when
     * a null is returned.  I often store the src in a database with a timestamp so
     * that I do not bombard the server with repeated requests for a thumbnail that
     * doesn't yet exist, although STW is very fast at processing.
     *
     * @param string $url URL to get thumbnail for
     * @param array $args Array of parameters to use
     * @param boolean $force Force call to bypass cache, was used for debugging
     * @return string Local SRC URI for the thumbnail.
     */
    public static function getThumbnail($url, $args = null, $force = false) {
		
        $args = $args ? $args : array("stwsize"=>"lg");
		$src = '/'.md5($url.serialize($args)).".jpg";
        $path = dirname( __FILE__ ) . self::THUMBNAIL_DIR.$src;
        $cutoff = time() - 3600 * 24 * self::CACHE_DAYS;
		
        if ($force || !file_exists($path) || filemtime($path) <= $cutoff) {
            if (($jpgurl = self::queryRemoteThumbnail($url, $args))) {
                if (($im = imagecreatefromjpeg($jpgurl))) {
					imagejpeg($im, $path, 100);
				}
			}
		}
        if (file_exists($path)) {
            return plugin_dir_url(__FILE__) . substr(self::THUMBNAIL_DIR, 1) . $src;
		}

        return null;
    }

    /**
     * Always retrieves the X-Large thumbnail from STW, then uses
     * local gd library to create arbitrary sized thumbnails.
     *
     * By passing the same arguments used for small/large should
     * generate cache hits so the only size ever retrieved would
     * be xlg.
     *
     * @param string $url URL to get thumbnail for
     * @param string $width The desired image width
     * @param string $height The desired image height
     * @param string $args Used to make name same as sm/lg fetches.
     */
    public static function getScaledThumbnail($url, $width, $height, $args = null, $force = false) {
		
		if ( strtolower(get_option( 'webphysiology_portfolio_use_stw_pro' )) == "true" ) {
			$args = $args ? $args : array("width"=>$width, "height"=>$height);
			$src = '/'.md5($url.serialize($args)).".jpg";
			$path = dirname( __FILE__ ) . self::THUMBNAIL_DIR.$src;
			$cutoff = time() - 3600 * 24 * self::CACHE_DAYS;
			
			if ( ($force || !file_exists($path) || filemtime($path) <= $cutoff) ) {
				if ( $xlg = self::getThumbnail($url, array("stwsize"=>"xlg")) ) {
					if ( $im = imagecreatefromjpeg($xlg) ) {
						
						list($xw, $xh) = getimagesize($xlg);
						$scaled = imagecreatetruecolor($width, $height);
	
						if (imagecopyresampled($scaled, $im, 0, 0, 0, 0, $width, $height, $xw, $xh)) {
							imagejpeg($scaled, $path, 100);
						}
					}
				}
			}
			
			if (file_exists($path)) {
				return plugin_dir_url(__FILE__) . substr(self::THUMBNAIL_DIR, 1) . $src;
			}
			
        	return null;
			
		} else {
			
			// if user doesn't have, or is not using, a STW pro account
			
			// determine the closest fit for the size of image to retrieve
			/*
			FROM: http://www.shrinktheweb.com/uploads/STW_API_Documentation.pdf
			
			75x56		mcr		Tells STW to return the "micro" size.
			90x68		tny		Tells STW to return the "tiny" size.
			100x75		vsm		Tells STW to return the "very small" size.
			120x90		sm		Tells STW to return the "small" size.
			200x150		lg		Tells STW to return the "large" size.
			320x240		xlg		Tells STW to return the "extra large" size.
			*/
			$opt_val_img_width = get_option( 'webphysiology_portfolio_image_width' );
			
			if ( $opt_val_img_width >= 320 ) {
				$stw_width = "xlg";
			} elseif ( $opt_val_img_width >= 200 ) {
				$stw_width = "lg";
			} elseif ( $opt_val_img_width >= 120 ) {
				$stw_width = "sm";
			} elseif ( $opt_val_img_width >= 100 ) {
				$stw_width = "vsm";
			} elseif ( $opt_val_img_width >= 90 ) {
				$stw_width = "tny";
			} else {
				$stw_width = "mcr";
			}
			
			$ak = get_option( 'webphysiology_portfolio_stw_ak' );
			if ( !empty($ak) ) {
				return '<script type="text/javascript">stw_pagepix("' . $url . '", "' . $ak . '", "' . $stw_width . '");</script>';
			} else {
				return null;
			}
			
		}
		
    }
	
//	public static function getProSTWScaledThumbnail($url, $width, $height, $args = null, $force = false) {
	
	//  function to determine if URL is an inside page or the primary domain page
	private static function inside_url($url) {
		
		$inside = false;
		$url = strtolower($url); // knock everything down to lowercase if it isn't
		$domtld = parse_url($url); //  break URL into components                                                                             
		$domtld = $domtld['host']; //  assign only the host portion
		$domtld = str_ireplace( "www.", "", $domtld ); //  strip off www for consistency
		if ( strpos($url,"http") !== FALSE ) { //  if it's not an IP address
			$basedom = $domtld;
		} else { //  support IP addresses as referrer
			$basedom = $url;
		}
		$outside = strpos($url,$basedom) + strlen($basedom) + 2; // extend by 2 to cover for ending / and possilby /#
		$remaining = substr($url, $outside);
		$inside = ( ! empty($remaining) );
		
		return $inside;
		
	}
	
    /**
     * Calls through the API and processes the results based on the
     * original sample code from STW.
     *
     * It is common for this routine to return a null value when the
     * thumbnail does not yet exist and is queued up for processing.
     *
     * @param string $url URL to get thumbnail for
     * @param array $args Array of parameters to use
     * @return string full remote URL to the thumbnail
     */
    private static function queryRemoteThumbnail($url, $args = null, $debug = false) {
		
		$inside_page = self::inside_url($url);
		
        $args = is_array($args) ? $args : array();
        $defaults["stwaccesskeyid"] = get_option( 'webphysiology_portfolio_stw_ak' );
        $defaults["stwu"] = get_option( 'webphysiology_portfolio_stw_sk' );
		if ( $inside_page ) {
			$defaults["stwinside"] = "1";
		}
//		$defaults["stwq"] = "100";
//		$defaults["stwredo"] = "1";
		
		foreach ($defaults as $k=>$v) {
            if (!isset($args[$k])) {
                $args[$k] = $v;
			}
		}
		
		$args["stwurl"] = $url;
		
        $request_url = "http://images.shrinktheweb.com/xino.php?".http_build_query($args);
        $line = self::make_http_request($request_url);
		$check = strtolower($line);
		
		if ( strpos($check, 'fix_and_retry') > 0 || strpos($check, 'invalid credentials') > 0 ) {
			$errorString = 'Unable to retrieve thumbnail from ShrinkTheWeb.com';
			echo '<pre>' . htmlentities($errorString) . '</pre>';
			return null;
		}
		
        if ($debug) {
            echo '<pre style=font-size:10px>';
            unset($args["stwaccesskeyid"]);
            unset($args["stwu"]);
            print_r($args);
            echo '</pre>';
            echo '<div style=font-size:10px>';
            highlight_string($line);
            echo '</div>';
        }

        $regex = '/<[^:]*:Thumbnail\\s*(?:Exists=\"((?:true)|(?:false))\")?[^>]*>([^<]*)<\//';
		
        if (preg_match($regex, $line, $matches) == 1 && $matches[1] == "true") {
            return $matches[2];
		}

        return null;
    }
	
    private static function make_http_request($url){
	
        // get file
		if (function_exists ('curl_init')) {
			
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // forces response into return string, not echo
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") );
			$return = curl_exec($ch);
			$info = curl_getinfo($ch);
			$errno = curl_errno($ch);
			$error = curl_error($ch);
			curl_close($ch);
			
//			$debug = true;
			$debug = false;
			if ($debug) {
				echo '<pre style=font-size:10px>';
				unset($info["stwaccesskeyid"]);
				unset($info["stwu"]);
				echo 'errno: ' . $errno . '<br />';
				echo 'error: ' . $error . '<br />';
				print_r($info);
				echo '</pre>';
			} elseif ( ! empty($errno) ) {
				echo '<pre style=font-size:10px>';
				echo 'An error was encountered in the request to ShrinkTheWeb.com:<br />';
				echo '    errno: ' . $errno . '<br />';
				echo '    error: ' . $error . '<br />';
				echo '</pre>';
			}
			
		} else {
			
			$lines = file($url);
			$return = implode("", $lines);
			
		}
		return $return;
}
}

?>