<?php

header('Content-Type: text/plain;charset=utf-8');

if( current_user_has_auth() ) {
	print '{ "ip" : "' . get_client_ip('service') . '" }';
} else {
	// TODO: Redirect to error page
	print '401 Not Authorized';
}
