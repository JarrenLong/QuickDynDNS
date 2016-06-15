<?php

/* Add custom fields to the user's profile config page */
function qddns_show_extra_profile_fields( $user ) {
?>
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
add_action( 'show_user_profile', 'qddns_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'qddns_show_extra_profile_fields' );

function qddns_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields */
	update_usermeta( $user_id, 'qddns_enabled', $_POST['qddns_enabled'] );
	update_usermeta( $user_id, 'qddns_client_auth', $_POST['qddns_client_auth'] );
}
add_action( 'personal_options_update', 'qddns_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'qddns_save_extra_profile_fields' );

?>
