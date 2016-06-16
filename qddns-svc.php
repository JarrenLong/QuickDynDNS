<?php
	
$auth = '';
$fmt = $wp->query_vars["qddns"];
$raw = split( "-", $fmt );

if( count( $raw ) > 1) {
	$fmt = $raw[0];
	$auth = $raw[1];
} else {
	$auth = $raw[0];
}

if( current_user_has_auth() ) {
	$cur_ip = get_client_ip( 'service' );
	
	if( $fmt == 'json' ) {
		// Send response as JSON
		header('Content-Type: application/json;charset=utf-8');
		
		wp_send_json( array(
			'QDDNS' => array(
				'IP' => $cur_ip,
				'AuthToken' => $auth
			)
		) );
	} else if( $fmt == 'xml') {
		// Send response as XML
		header('Content-Type: application/xml;charset=utf-8');
		
		print '<?xml version="1.0"?>
<QDDNS xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<IP>' . $cur_ip . '</IP>
	<AuthToken>' . $auth . '</AuthToken>
</QDDNS>';
	} else {
		// Send response as Text
		header('Content-Type: text/plain;charset=utf-8');

		print $cur_ip;
	}
} else {
	// TODO: Redirect to error page
	print '401 Not Authorized';
}
