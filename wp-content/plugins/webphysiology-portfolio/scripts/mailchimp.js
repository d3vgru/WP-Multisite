/* Form submission functions for the MailChimp Widget */
;(function($){
	$(function($) {
		// Change our submit type from HTML (default) to JS
		jQuery('#mc_submit_type').val('js');
		
		// Attach our form submitter action
		jQuery('#mc_signup_form').ajaxForm({
			url: mailchimpSF.ajax_url, 
			type: 'POST', 
			dataType: 'text',
			beforeSubmit: mc_beforeForm,
			success: mc_success
		});
	});
	
	function mc_beforeForm(){
		// Disable the submit button
		jQuery('#mc_signup_submit').attr("disabled","disabled");
	}
	function mc_success(data){
		// Re-enable the submit button
		jQuery('#mc_signup_submit').attr("disabled","");
		
		// Put the response in the message div
		jQuery('#mc_message').html(data);
		
		// See if we're successful, if so, wipe the fields
		var reg = new RegExp("class='mc_success_msg'", 'i');
		if (reg.test(data)){
			jQuery('#mc_signup_form').each(function(){
				this.reset();
			});
			jQuery('#mc_submit_type').val('js');
		}
	}
})(jQuery);
