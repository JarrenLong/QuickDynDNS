<?php
/*
 * Plugin name: Quick DynDNS
 * Plugin URI: http://jlong.co
 * Description: A simple plugin to show client IP address information anywhere on your site, and provide Dynamic DNS services.
 * Version: 1.0.16
 * Author: Jarren Long
 * Author URI: http://jlong.co
 * License: GPLv3
 */
/*
Copyleft 2016 Jarren Long (jarrenlong@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

global $qddns_db_version;
$qddns_db_version = '1.0.16';

include('qddns-install.php');

/* Plugin CSS */
wp_enqueue_style('custom-style', plugins_url( 'css/style-qddns.css', __FILE__ ), array(), 'all');

/* Plugin Settings Page */
include('qddns-settings.php');

if(is_admin()) {
	include('qddns-dashboard.php');
	
	/* Add a Contact link to the plugin page */
	function user_ip_contact_link( $links ) {
		$contact_link = '<a href="http://jlong.co/contact" target="_blank">Contact Developer</a>';
		array_push( $links, $contact_link );
		return $links;
	}
	
	$plugin = plugin_basename( __FILE__ ); 
	add_filter("plugin_action_links_$plugin", 'user_ip_contact_link' );
}

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

function get_client_ip_shortcode() {
	get_client_ip( 'shortcode' );
}
add_shortcode( 'qddns', 'get_client_ip_shortcode' );

include('qddns-widget.php');
include('qddns-userprofile.php');

?>
