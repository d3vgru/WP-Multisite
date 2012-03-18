<?php
/**
 * This file contains code to work with an Ajax call to clear out all the image caches
 *
 * @package WordPress
 * @subpackage webphysiology-portfolio plugin
 * @since webphysiology-portfolio 1.3.1
 */

/*  UPDATES

	1.3.? - ???
	
*/

session_start();

if ( isset($_SESSION['cache']) ) {
	
	$dir = $_SESSION['cache'] . "file_functions.php";
	
	include_once($dir);
	
	$dir = str_replace("clear_image_caches.php","",__FILE__)."/temp";
	cleardir($dir);
	
	$dir = str_replace("clear_image_caches.php","",__FILE__)."/scripts/imageresizer/cache";
	cleardir($dir);
	
	$dir = str_replace("clear_image_caches.php","",__FILE__)."/scripts/stw/cache";
	cleardir($dir);
	
	check_temp_dir();
	
	echo "Image Caches Cleared";
	
} else {
	
	echo "Unable to Clear Image Caches";
	
}
?>