;(function($) {
	
	// Gateway select change event
	$('.hide_class').hide();
	$('#satosms_gateway\\[sms_gateway\\]').on( 'change', function() {
		var self = $(this),
			value = self.val();
		$('.hide_class').hide();
		$('.'+value+'_wrapper').fadeIn();
	});

	// Trigger when a change occurs in gateway select box 
	$('#satosms_gateway\\[sms_gateway\\]').trigger('change');

	// handle send sms from order page in admin panale
	var w = $('.satosms_send_sms').width(),
		h = $('.satosms_send_sms').height(),
		block = $('#satosms_send_sms_overlay_block').css({
					'width' : w+'px',
					'height' : h+'px',
				});

})(jQuery);