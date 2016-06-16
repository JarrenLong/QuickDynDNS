<?php

/* Plugin installation and activation */
function qddns_install() {
	global $wpdb;
	global $qddns_db_version;

	// Fresh install of the database
	$table_name = $wpdb->prefix . 'qddns';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id bigint(20),
		time datetime DEFAULT '0000-00-00 00:00:00',
		ip_address text,
		source tinytext,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'qddns_db_version', $qddns_db_version );
	
	// Check for a newer version and upgrade the database if necessary
	$installed_ver = get_option( "qddns_db_version" );
	if ( $installed_ver != $qddns_db_version ) {
		$table_name = $wpdb->prefix . 'qddns';

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_id bigint(20),
			time datetime DEFAULT '0000-00-00 00:00:00',
			ip_address text,
			source tinytext,
			UNIQUE KEY id (id)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option( "qddns_db_version", $qddns_db_version );
	}
}
register_activation_hook( __FILE__, 'qddns_install' );

function qddns_update_db_check() {
    global $qddns_db_version;
	
    if ( get_site_option( 'qddns_db_version' ) != $qddns_db_version ) {
        qddns_install();
    }
}
add_action( 'plugins_loaded', 'qddns_update_db_check' );

function qddns_install_data() {
	get_client_ip('install');
}
register_activation_hook( __FILE__, 'qddns_install_data' );
