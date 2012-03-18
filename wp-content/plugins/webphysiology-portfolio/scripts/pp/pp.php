<?php
/**
 * The code that manages the display of PagePeeker.com served thumbnails
 *
 * @package WordPress
 * @subpackage webphysiology-portfolio plugin
 * @since webphysiology-portfolio 1.4.0
*/

/*  UPDATES

	1.4.x - 
	
*/

abstract class App_PgPkr {
	
    public static function getScaledThumbnail($url, $width) {
		
		// strip out the protocol portion of the site's URL
		$url = str_replace("http://","",str_replace("https://","",$url));
		
		$code = "";
		
		switch ($width) {
			
			case ($width <= 90):
				$size = 't'; // t - Tiny (90x68px)
				break;
			case ($width <= 120):
				$size = 's'; // s - Small (120x90px)
				break;
			case ($width <= 200):
				$size = 'm'; // m - Medium (200x150px)
				break;
			case ($width <= 400):
				$size = 'l'; // l - Large (400x300px)
				break;
			default:
				$size = 'x'; // x - eXtra large (480x380px)
			
		}
		
		$code = get_option('webphysiology_portfolio_pagepeeker_account');
		
		// if the user has a custom PagePeeker account
		if ( ! empty($code) ) {
			
			$code = ""; // the actual custom account number is not currently needed, so, clear it
			
			$pp_custom_account = 'custom.'; // custom accounts are supported by PagePeeker's "custom" sub-domain, along with them checking the site the request is coming from, so, add the "custom" sub-domain to the PagePeeker URL
			
		}
		
		if ( !empty($url) ) {
			return '<img src="http://' . $pp_custom_account . 'pagepeeker.com/thumbs.php?size=' . $size . $code . '&url=' . $url . '" border="0">';
		} else {
			return "";
		}
	}
	
}
?>