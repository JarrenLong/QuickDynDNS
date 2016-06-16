<?php

header('Content-Type: application/json;charset=utf-8');

if( current_user_has_auth() ) {
	wp_send_json( array( 'ip' => get_client_ip( 'service' ) ) );
} else {
	// TODO: Redirect to error page
	print '401 Not Authorized';
}
