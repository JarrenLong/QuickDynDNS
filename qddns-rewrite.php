<?php

function qddns_rewrite2() {
    add_rewrite_rule( 'qddns-svc.php$', 'index.php?qddns=1', 'top' );
}
add_action( 'init', 'qddns_rewrite2' );

function qddns_query_vars( $query_vars ) {
    $query_vars[] = 'qddns';
    return $query_vars;
}
add_filter( 'query_vars', 'qddns_query_vars' );

function qddns_parse_request( $wp ) {
    if ( array_key_exists( 'qddns', $wp->query_vars ) ) {
        include 'qddns-svc.php';
        exit();
    }
    return;
}
add_action( 'parse_request', 'qddns_parse_request' );
