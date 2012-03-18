<?php
/**
 * nrelate Admin Messages
 *
 * Does system checks and sets messages for this particular nrelate plugin
 *
 * @package nrelate
 * @subpackage Functions
 */

function nr_rc_message_set(){

	 // Get related options
	$related_options = get_option('nrelate_related_options');
	
	// Related Thumbnail options
	$show_thumbnails = $related_options['related_thumbnail'];
	$thumbnailurl = $related_options['related_default_image'];
	// Related ad options
	$adcodeopt = isset($related_options['related_display_ad']) ? $related_options['related_display_ad'] : null;
	$msg = '';
	// Thumbnail
	if ($show_thumbnails == 'Thumbnails') {
		// Is there a default thumbnail set?
		if ($thumbnailurl == null || $thumbnailurl == '') {
				$msg = $msg . '<li><div class="red">Related Content is set to show thumbnails. It\'s a good idea to add a default image just in case a post does not have images in it. Add your <a href="admin.php?page=nrelate-related">default image here</a>.</div></li>';
		} else {
				$msg = $msg . '<li><div class="green">Related Content will show thumbnails, and default thumbnail is set.</div></li>';
		}
	};
	echo $msg;
};
add_action ('nrelate_admin_messages','nr_rc_message_set');


		
?>