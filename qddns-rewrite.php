<?php

function qddns_rewrite_activate() {
	qddns_rewrite_init();
	flush_rewrite_rules();
}
register_activation_hook(plugin_name(), 'qddns_rewrite_activate');

function qddns_rewrite_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook(plugin_name(), 'qddns_rewrite_deactivate'); 

function qddns_rewrite_init() {
	add_rewrite_rule('dns\??([^/]*)', 'index.php?pagename=qddns&$matches[1]', 'top');
}
add_action( 'init', 'qddns_rewrite_init' );

function qddns_rewrite_query_vars( $query_vars ) {
	array_push($query_vars, 'qdf', 'qda');
	
    return $query_vars;
}
add_filter( 'query_vars', 'qddns_rewrite_query_vars' );

function qddns_custom_page_display() {
	$page = get_query_var('pagename');
	$auth = get_query_var('qda');
	$fmt = get_query_var('qdf');
	
	if ('qddns' == $page) {
		$auth = get_query_var('qda');
		$fmt = get_query_var('qdf');
		
		// TODO: Get user auth token built
		//if( current_user_has_auth($auth) ) {
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
		//} else {
			//print '401';
		//}
	
		exit();
	}
}
add_filter('template_redirect', 'qddns_custom_page_display');
 
function qddns_rewrite_parse_request( $wp ) {
	if ( array_key_exists( 'name', $wp->query_vars ) ) {
		$k = $wp->query_vars['name'];
		
		if (strpos($k, 'qddns') !== false) {
			print var_dump($wp->query_vars);
			
			include 'qddns-svc.php';
			exit();
		}
    }
    return;
}
//add_action( 'parse_request', 'qddns_rewrite_parse_request' );
?>
