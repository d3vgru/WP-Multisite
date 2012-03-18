jQuery(document).ready(function($) {

	// Select shortcode
	$('#su-generator-select').live( "change", function() {
		var queried_shortcode = $('#su-generator-select').find(':selected').val();
		$('#su-generator-settings').addClass('su-loading-animation');
		$('#su-generator-settings').load($('#su-generator-url').val() + '/lib/generator.php?shortcode=' + queried_shortcode, function() {
			$('#su-generator-settings').removeClass('su-loading-animation');
		});
	});

	// Insert shortcode
	$('#su-generator-insert').live('click', function() {
		var queried_shortcode = $('#su-generator-select').find(':selected').val();
		var su_compatibility_mode_prefix = $('#su-compatibility-mode-prefix').val();
		$('#su-generator-result').val('[' + su_compatibility_mode_prefix + queried_shortcode);
		$('#su-generator-settings .su-generator-attr').each(function() {
			if ( $(this).val() !== '' ) {
				$('#su-generator-result').val( $('#su-generator-result').val() + ' ' + $(this).attr('name') + '="' + $(this).val() + '"' );
			}
		});
		$('#su-generator-result').val($('#su-generator-result').val() + ']');

		// wrap shortcode
		if ( $('#su-generator-content').val() != 'false' ) {
			$('#su-generator-result').val($('#su-generator-result').val() + $('#su-generator-content').val() + '[/' + su_compatibility_mode_prefix + queried_shortcode + ']');
		}
		window.send_to_editor(jQuery('#su-generator-result').val());
		return false;
	});

});