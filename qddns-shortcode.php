<?php

function get_client_ip_shortcode() {
	print get_client_ip( 'shortcode' );
}
add_shortcode( 'qddns', 'get_client_ip_shortcode' );
?>
