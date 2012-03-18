<?php
/**
 * This file contains code that deals with file level functions
 *
 * @package WordPress
 * @subpackage webphysiology-portfolio plugin
 * @since webphysiology-portfolio 1.4.1
 */

/*  UPDATES

	1.4.? - ???
	
*/

function check_temp_dir() {
	
	// define the temp folder's local path
	$tempdir = dirname ( __FILE__ ) . '/temp';
	
	// make sure temp directory exists. if it doesn't, create it
	if ( ! file_exists ($tempdir) ) {
		// give 777 permissions so that developer can overwrite
		// files created by web server user
		mkdir($tempdir);
		chmod($tempdir, 0744);
	}
	
	// define the path to the temp folder's index.php file
	$filestr = $tempdir."/index.php";
	create_index_file($filestr);
	
	// define the TimThumb cache folder's local path and index.php within it
	$cachedir = dirname ( __FILE__ ) . '/scripts/imageresizer/cache';
	$filestr = $cachedir."/index.php";
	create_index_file($filestr);
	
	// define the ShrinkTheWeb cache folder's local path and index.php within it
	$cachedir = dirname ( __FILE__ ) . '/scripts/stw/cache';
	$filestr = $cachedir."/index.php";
	create_index_file($filestr);
	
}

function create_index_file($path) {
	
	// if the index.php file does not exist, create it
	if ( ! file_exists ($path) ) {
		$content = "<?php" . "\n" . "  // silence is golden" . "\n" . "?>";
		file_put_contents($path, $content);
		chmod($path, 0644);
	}
	
}

// delete the specified directory and any files/directories within it
function rrmdir($dir) {
	if (is_dir($dir)) {
		cleardir($dir);
		rmdir($dir);
	}
}

// delete all files within the specified directory
function cleardir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				deletefile($dir, $object, "/");
			}
		}
		reset($objects);
	}
}

// delete a given file or directory
function deletefile($path, $filedir, $sep) {
	
	if ( file_exists($path.$sep.$filedir) ) {
		
		if (filetype($path.$sep.$filedir) == "dir") {
			
			rrmdir($path.$sep.$filedir);
			
		} else {
			
			unlink($path.$sep.$filedir);
			
		}
	}
}

?>