<?php
/**
 * This file contains code to work with an Ajax call to set the webphysiology-portfolio/scripts/imageresizer/cache folder permissions to 777
 *
 * @package WordPress
 * @subpackage webphysiology-portfolio plugin
 * @since webphysiology-portfolio 1.4.1
 */

/*  UPDATES

	1.4.? - ???
	
*/

$response = "Unable to update Image Cache permissions. You will need to update via File Manager.";

session_start();

if ( isset($_SESSION['cache']) ) {
	
	$directory = $_SESSION['cache'];
	$perm = '0777';
	
	$response = webphys_port_cache_chmod($directory, $perm);

}

echo $response;

function webphys_port_cache_chmod($dir, $perm) {
	
	$dir = $dir . 'scripts/imageresizer/cache';
	
	$perms = substr(sprintf('%o', fileperms($dir)), -4);
	
	if ( $perms != $perm ) {
		
		switch ($perm) {
		
			case "0744":
			
				$chmod = chmod($dir, 0744);
				break;
				
			case "0777":
			
				$chmod = chmod($dir, 0777);
				break;
				
		}
		
		if ( $chmod ) {
			
			$response = "Image cache permissions have been set to " . $perm;
			
		} else {
			
			$response = "Unable to update Image Cache permissions from " . $perms . " to " . $perm . ". You will need to update via File Manager.";
			
		}
		
	} else {
		
		$response = "Permissions are already set to " . $perm;
		
	}
	
	return $response;
	
}

?>