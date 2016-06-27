jQuery(document).ready(function($){
	$('.qddns_button_generate').on('click', function( event ){
		$('#qddns_client_auth').val($('#qddns_new_auth_token').val());
	});
});
