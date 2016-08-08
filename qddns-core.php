<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function qddns_insert_data($uid, $ip, $src = '') {
	global $wpdb;
	
	$wpdb->insert( 
		$wpdb->prefix . 'qddns_iplog', 
		array( 
			'user_id' => $uid,
			'time' => current_time( 'mysql' ), 
			'ip_address' => $ip,
			'source' => $src
		) 
	);
}

function get_client_ip($src, $log = true, $auth = '') {
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
		else
			qddns_insert_data( auth_to_uid( $auth ), $ip, $src );
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

function get_request_stats_table_by_day($src = '') {
	global $wpdb;
	
	$sql = "SELECT DATE(time) day, source, COUNT(source) requests FROM " . $wpdb->prefix . 'qddns_iplog WHERE MONTH(time) = MONTH(NOW()) AND YEAR(time) = YEAR(NOW())';
	
	if( !empty($src))
		$sql .= " AND source = '" . $src . "'";
	
	$sql .= " GROUP BY day, source ORDER BY day";
	
	return $wpdb->get_results( $sql );
}
function get_request_stats_table($src = '') {
	global $wpdb;
	
	$sql = "SELECT time, source FROM " . $wpdb->prefix . 'qddns_iplog WHERE MONTH(time) = MONTH(NOW()) AND YEAR(time) = YEAR(NOW())';
	
	if( !empty($src))
		$sql .= " AND source = '" . $src . "'";
	
	return $wpdb->get_results( $sql );
}
function get_request_stats_table_count($src = '') {
	return count( get_request_stats_table( $src ) );
}

function auth_to_uid($auth) {
	global $wpdb;
	
	$sql = "select user_id from " . $wpdb->prefix . "usermeta WHERE meta_key = 'qddns_client_auth_token' AND meta_value = '" . $auth . "'";
	$rec = $wpdb->get_results( $sql );
	if( count( $rec ) > 0)
		return $rec[0]->user_id;
	
	return 0;
}

function current_user_has_auth($auth = '') {
	global $wpdb;
	
	if( !get_option( 'ddns_enable_user_auth' ) )
		return false;
	
	if($auth == '') {
		$uid = get_current_user_id();
		if( $uid > 0 ) {
			$sql = "SELECT id FROM " . $wpdb->prefix . 'users WHERE id = ' . $uid;
			
			return count( $wpdb->get_results( $sql ) ) > 0;
		}
	} else {
		$sql = "select user_id from " . $wpdb->prefix . "usermeta WHERE meta_key = 'qddns_client_auth_token' AND meta_value = '" . $auth . "'";
		
		return count( $wpdb->get_results( $sql ) ) > 0;
	}
	
	return false;
}

function create_user_auth_token() {
	return substr( bin2hex( wp_hash( get_current_user()->user_login . current_time( 'mysql' ) . $wpdb->prefix ) ), 0, 32 );
}
?>
