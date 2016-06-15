<?php

function qddns_insert_data($uid, $ip, $src = '') {
	global $wpdb;
	
	$wpdb->insert( 
		$wpdb->prefix . 'qddns', 
		array( 
			'user_id' => $uid,
			'time' => current_time( 'mysql' ), 
			'ip_address' => $ip,
			'source' => $src
		) 
	);
}

function get_client_ip($src, $log = true) {
	if ( !empty( $_SERVER['HTTP_CLIENT_IP'] ) )
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	elseif ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else
		$ip = $_SERVER['REMOTE_ADDR'];

	$ip = apply_filters( 'wpb_get_ip', $ip );
	
	// Save IP to database table
	if( $log ) {
		$uid = get_current_user_id();
		if( $uid > 0 )
			qddns_insert_data( $uid, $ip, $src );
	}
	
	return $ip;
}

function ip_address_to_hostname($ip) {
	$host = @gethostbyaddr( $ip );
	if( !$host || $host == $ip )
		$host = 'Unknown';
	
	return host;
}

function get_num_valid_users() {
	global $wpdb;
	
	return count( $wpdb->get_results( "SELECT DISTINCT user_id FROM " . $wpdb->prefix . 'qddns' ) );
}

function get_request_stats_table($src = '') {
	global $wpdb;
	
	$sql = "SELECT user_id, time, ip_address, source FROM " . $wpdb->prefix . 'qddns';
	
	if( !empty($src))
		$sql .= " WHERE source = '" . $src . "'";
	
	return count( $wpdb->get_results( $sql ) );
}
?>
