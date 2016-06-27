jQuery(document).ready(function($) {
	$('.qddns_button_generate').on('click', function( event ) {
		$('#qddns_client_auth_token').val($('#qddns_new_auth_token').val());
	});
	
	$('#qddns_client_auth_enabled').change(function() {
        if(!$(this).is(":checked")) {
            $('#qddns_client_auth_token').val('');
        } else {
			$('#qddns_client_auth_token').val($('#qddns_new_auth_token').val());
		}
    });
});
