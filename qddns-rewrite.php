<?php

function qddns_rewrite() {
    add_rewrite_rule( 'qddns-svc.php$', 'index.php?qddns=1', 'top' );
}
add_action( 'init', 'qddns_rewrite' );

function qddns_rewrite_query_vars( $query_vars ) {
    $query_vars[] = 'qddns';
    return $query_vars;
}
add_filter( 'query_vars', 'qddns_rewrite_query_vars' );

function qddns_rewrite_parse_request( $wp ) {
    if ( array_key_exists( 'qddns', $wp->query_vars ) ) {
        include 'qddns-svc.php';
        exit();
    }
    return;
}
add_action( 'parse_request', 'qddns_rewrite_parse_request' );
