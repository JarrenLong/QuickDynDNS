<?php

function qddns_rewrite() {
    add_rewrite_rule( 'qddns-svc.php$', 'index.php?qddns=1', 'top' );
	//add_rewrite_rule( 'qddns-svc.json$', 'index.php?qddns=1;json=true', 'top' );
	//add_rewrite_rule( 'qddns-svc.xml$', 'index.php?qddns=1;xml=true', 'top' );
}
add_action( 'init', 'qddns_rewrite' );

function qddns_rewrite_query_vars( $query_vars ) {
	array_push($query_vars, 'qddns');
	
    return $query_vars;
}
add_filter( 'query_vars', 'qddns_rewrite_query_vars' );

function qddns_rewrite_parse_request( $wp ) {
	if ( array_key_exists( 'name', $wp->query_vars ) ) {
		$k = $wp->query_vars['name'];
		
		if ( $k == 'qddns.json' ) {
			include 'qddns-svc.json';
			exit();
		} else if ( $k == 'qddns.xml' ) {
			include 'qddns-svc.xml';
			exit();
		}
		
        include 'qddns-svc.php';
        exit();
    } 
    if ( array_key_exists( 'qddns', $wp->query_vars ) ) {
        include 'qddns-svc.php';
        exit();
    }
    return;
}
add_action( 'parse_request', 'qddns_rewrite_parse_request' );
