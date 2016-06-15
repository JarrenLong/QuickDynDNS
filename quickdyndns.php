<?php
/*
 * Plugin name: Quick DynDNS
 * Plugin URI: http://jlong.co
 * Description: A simple plugin to show client IP address information anywhere on your site, and provide Dynamic DNS services.
 * Version: 1.0.0
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
$qddns_db_version = '1.0.0';

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

function qddns_insert_data($uid, $ip, $src = '') {
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'qddns';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'user_id' => $uid,
			'time' => current_time( 'mysql' ), 
			'ip_address' => $ip,
			'source' => $src
		) 
	);
}

function qddns_install_data() {
	get_client_ip('install');
}
register_activation_hook( __FILE__, 'qddns_install_data' );


/* Plugin CSS */
wp_enqueue_style('custom-style', plugins_url( 'css/style-qddns.css', __FILE__ ), array(), 'all');

/* Plugin Settings Page */
function qddns_show_settings(){
	echo '
	<h1>Show IP Address Information</h1> <div>A simple plugin to show your IP address information on any of your pages, posts or widgets. <br>Shows your IP address on your dashboard from any location.</div> <hr> <div>The idea is simple, give it a go, let us know what you think of this plugin. Any suggested updates <br> I\'ll consider in each build <a href="http://www.keith-griffiths.com/contact-me">click here</a> to contact me anytime.</div>
	<h2>Use on pages, widgets or posts</h2> <hr> <div>Let\'s say a user lands on your page or post, and you want to show them there IP address. <br> <h3>You could use</h3> What\'s my IP address: [show_ip]</div> <br>
	<strong>Q)</strong>. What\'s the point in showing a person there IP address? <br> <strong>A)</strong>. You may want to create a secure page are on your website and show the user their IP address.<hr>
	';
}

function qddns_plugin_settings() {
    add_menu_page('Quick DynDNS Settings', 'Quick DynDNS', 'administrator', 'qddns', 'qddns_show_settings');
}

add_action('admin_menu', 'qddns_plugin_settings');


if(is_admin()) {
	/* Dashboard widget */
	function qddns_dashboard_widget_function() {
		// admin ip address
		$admin_ip_address = $_SERVER['REMOTE_ADDR'];
		if( !$admin_ip_address )
			$admin_ip_address = 'unknown';
		
		// admin hostname
		$admin_hostname = @gethostbyaddr( $admin_ip_address );
		if( !$admin_hostname || $admin_hostname == $admin_ip_address )
			$admin_hostname = 'Unknown';
		
		// display information
		echo '<div style="display:table; width: 100%;">';
		echo '<div style="display:table-cell;"><big><strong>' . $admin_ip_address . '</strong></big></div>';
		if($admin_hostname != 'unknown')
			echo '<div style="display:table-cell; text-align: right;"><small>(' . __('hostname', 'qddns-address') . ' : ' . $admin_hostname . ')</small></div>';
		echo "</div>\n\n";
		echo '<div class="box-ip"><hr> Your IP address is something you might rarely think about, it\'s important to know what your IP address is and when it changes. Your IP address is used to identify computers on the Internet. <hr> Want to know more about IP addresses <a href="https://en.wikipedia.org/wiki/IP_address">click here</a> - Wiki Reference.  <hr>Show IP address, version 1.3</div>';
	}
	
	function qddns_add_dashboard_widgets() {
		wp_add_dashboard_widget('qddns_dashboard_widget', __('Your IP Address & Hostname', 'qddns-address'), 'qddns_dashboard_widget_function');
	}
	
	add_action('wp_dashboard_setup', 'qddns_add_dashboard_widgets' );

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



// Creating the widget 
class qddns_widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'qddns_widget', 
			// Widget name will appear in UI
			__('Quick DynDNS', 'qddns_widget_domain'),
			// Widget description
			array( 'description' => __( 'Quick DynDNS widget', 'qddns_widget_domain' ), ) 
		);
	}

	// Creating widget frontend
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( !empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		// This is where you run the code and display the output
		echo __( get_client_ip('widget'), 'qddns_widget_domain' );
		echo '<br/><small><a href="http://jlong.co/qddns">Powered by Quick DynDNS</a></small>';

		echo $args['after_widget'];
	}
		
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Your IP Address Is:', 'qddns_widget_domain' );
		}
		// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
	}
	
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'qddns_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );


/* Add custom fields to the user's profile config page */
function my_show_extra_profile_fields( $user ) { ?>
	<h3>Quick DynDNS Settings</h3>
	<table class="form-table">
		<tr>
			<th><label for="qddns_enabled">Enable Web Service</label></th>
			<td>
				<input type="checkbox" name="qddns_enabled" id="qddns_enabled" value="<?php echo esc_attr( get_the_author_meta( 'qddns_enabled', $user->ID ) ); ?>" />
				Enable/disable QDDNS client services for your account
			</td>
		</tr>
		<tr>
			<th><label for="qddns_client_auth">Client Auth Token</label></th>
			<td>
				<input type="text" name="qddns_client_auth" id="qddns_client_auth" value="<?php echo esc_attr( get_the_author_meta( 'qddns_client_auth', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Used for authenticating you when you use the QDDNS web service</span>
			</td>
		</tr>
	</table>
<?php
}
add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields */
	update_usermeta( $user_id, 'qddns_enabled', $_POST['qddns_enabled'] );
	update_usermeta( $user_id, 'qddns_client_auth', $_POST['qddns_client_auth'] );
}
add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

?>
