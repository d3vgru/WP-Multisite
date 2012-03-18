// JavaScript Document

// updated by: jeff lambert
// updated on: 2011-08-11
// updated   : adjusted code to utilize some code in the secondary source (comments) to keep from having this hijack the standard media-uploader

// initial source of pulling this together: 

// secondary source that helped with not hijacking the standard media-uploader: http://austinpassy.com/2010/03/creating-custom-metaboxes-and-the-built-in-uploader/

jQuery(document).ready(function() {

	var formfield;
	var $image_input;

	jQuery('#upload_portfolio_image_button').click(function() {
		jQuery('html').addClass('Image');
		
		var $cont = jQuery(this).parent();
		$_imageurl = jQuery('input[type=text]', $cont);
		formfield = $_imageurl.attr('name');
		
		tb_show('', 'media-upload.php?type=image&TB_iframe=true');
		
		tbframe_interval = setInterval(function() {jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Use image');}, 2000);
		
		return false;
	});
	
	window.original_send_to_editor = window.send_to_editor;
	
	window.send_to_editor = function(html){

		if (formfield) {
			
			fileurl = jQuery('img',html).attr('src');

			jQuery('#_imageurl').val(fileurl);
			
			$_imageurl.val(fileurl);
			
			tb_remove();
			
			formfield = null;
			
			jQuery('html').removeClass('Image');

		} else {
			window.original_send_to_editor(html);
		}
	};

});
