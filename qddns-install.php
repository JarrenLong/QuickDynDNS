<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/* Plugin installation and activation */
function qddns_install() {
	global $wpdb;
	global $qddns_db_version;

	// Fresh install of the database
	$db_prefix = $wpdb->prefix . 'qddns_';
	$table_users = $db_prefix . 'users';
	$table_iplog = $db_prefix . 'iplog';
	
	$charset_collate = $wpdb->get_charset_collate();

	// Create the QDDNS user table
	$sql = "CREATE TABLE $table_users (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id bigint(20),
		service_token text,
		max_service_requests mediumint(8),
		UNIQUE KEY id (id)
	) $charset_collate;
	
	CREATE TABLE $table_iplog (
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
	// Make sure our default settings are defined
	add_option( 'qddns_enabled', '1' );
	add_option( 'qddns_enable_user_auth', '1' );
	add_option( 'qddns_show_powered_by_widget', '1' );
	
	// Check for a newer version and upgrade the database if necessary
	$installed_ver = get_option( "qddns_db_version" );
	if ( $installed_ver != $qddns_db_version ) {
		// Create the QDDNS user table
		$sql = "CREATE TABLE $table_users (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_id bigint(20),
			service_token text,
			max_service_requests mediumint(8),
			UNIQUE KEY id (id)
		);
		
		CREATE TABLE $table_iplog (
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
?>
