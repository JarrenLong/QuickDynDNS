<?php

function get_client_ip($src) {
	if ( !empty( $_SERVER['HTTP_CLIENT_IP'] ) )
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	elseif ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else
		$ip = $_SERVER['REMOTE_ADDR'];

	$ip = apply_filters( 'wpb_get_ip', $ip );
	
	// Save IP to database table
	$uid = get_current_user_id();
	if( $uid > 0 )
		qddns_insert_data( $uid, $ip, $src );
	
	return $ip;
}

?>
