// JavaScript Document

// scripts/clear_img_caches.js
//
// * This file contains code to work with the clear image caches options button
// *
// * @package WordPress
// * @subpackage webphysiology-portfolio plugin
// * @since webphysiology-portfolio 1.3.1
//
//   UPDATES
//   
//	 1.3.? - ???
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
