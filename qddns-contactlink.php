<?php

if(is_admin()) {
	/* Add a Contact link to the plugin page */
	function user_ip_contact_link( $links ) {
		$contact_link = '<a href="http://jlong.co/contact" target="_blank">Contact Developer</a>';
		array_push( $links, $contact_link );
		return $links;
	}
	
	$plugin = plugin_basename( __FILE__ ); 
	add_filter("plugin_action_links_$plugin", 'user_ip_contact_link' );
}
