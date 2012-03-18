// JavaScript Document

// scripts/manage_img_caches.js
//
// * This file contains code to work with the clear image caches options button
// *
// * @package WordPress
// * @subpackage webphysiology-portfolio plugin
// * @since webphysiology-portfolio 1.3.1
//
//   UPDATES
//   
//	 1.4.1 - renamed from "clear_img_caches.js"
//           added sendChmodImageRequest and showChmodImageResponse functions
//

function sendClearImageRequest() {
	
	$('show-clear-response').innerHTML = "Clearing Image Caches...";
	document.getElementById('show-clear-response').className = "ShowAjaxContent";
	
	new Ajax.Request("../wp-content/plugins/webphysiology-portfolio/clear_image_caches.php", 
		{ 
		method: 'post',
		onComplete: showClearImageResponse 
		});
}

function showClearImageResponse(req){
	$('show-clear-response').innerHTML = req.responseText;
}

function sendChmodImageRequest() {
	
	$('show-chmod-response').innerHTML = "Updating Image Cache Permissions...";
	document.getElementById('show-chmod-response').className = "ShowAjaxContent";
	
	new Ajax.Request("../wp-content/plugins/webphysiology-portfolio/chmod_image_cache.php", 
		{ 
		method: 'post',
		onComplete: showChmodImageResponse 
		});
}

function showChmodImageResponse(req){
	$('show-chmod-response').innerHTML = req.responseText;
}
