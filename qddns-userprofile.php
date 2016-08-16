<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

wp_enqueue_script( 'scripts-qddns-userprofile', plugins_url( 'js/scripts-qddns-userprofile.js', __FILE__ ), array(), true);

/* Add custom fields to the user's profile config page */
function qddns_show_extra_profile_fields( $user ) {
?>
	<h3>Quick DynDNS Settings</h3>
	<table class="form-table">
		<tr>
			<th><label for="qddns_client_auth_enabled">Enable Web Service</label></th>
			<td>
				<input type="checkbox" name="qddns_client_auth_enabled" id="qddns_client_auth_enabled" value="qddns_client_auth_enabled" <?php if( get_the_author_meta( 'qddns_client_auth_enabled', $user->ID ) == 'qddns_client_auth_enabled' ) { ?>checked="checked"<?php } ?> />
				Enable/disable QDDNS client services for account
			</td>
		</tr>
		<tr>
			<th><label for="qddns_client_auth_token">Client Auth Token</label></th>
			<td>
				<input type="text" name="qddns_client_auth_token" id="qddns_client_auth_token" value="<?php echo esc_attr( get_the_author_meta( 'qddns_client_auth_token', $user->ID ) ); ?>" class="regular-text" />
				<input type="hidden" name="qddns_new_auth_token" id="qddns_new_auth_token" value="<?php echo create_user_auth_token(); ?>" />
				<input type='button' class="qddns_button_generate additional-user-image button-primary" value="Generate" id="qddns_button_generate"/><br />
				<span class="description">Used for authenticating you when you use the QDDNS web service</span>
			</td>
		</tr>
	</table>
<?php
}

if( get_option( 'qddns_enable_user_auth' ) ) {
	add_action( 'show_user_profile', 'qddns_show_extra_profile_fields' );
	add_action( 'edit_user_profile', 'qddns_show_extra_profile_fields' );
}

function qddns_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	update_usermeta( $user_id, 'qddns_client_auth_enabled', $_POST['qddns_client_auth_enabled'] );
	update_usermeta( $user_id, 'qddns_client_auth_token', $_POST['qddns_client_auth_token'] );
}

if( get_option( 'qddns_enable_user_auth' ) ) {
	add_action( 'personal_options_update', 'qddns_save_extra_profile_fields' );
	add_action( 'edit_user_profile_update', 'qddns_save_extra_profile_fields' );
}
?>
