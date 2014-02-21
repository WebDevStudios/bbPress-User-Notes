<?php

function bbp_user_notes_admin_profile_fields( $user ) {

	if ( !current_user_can( 'manage_options' ) ) {
		return;
	}
	?>

	<h3><?php _e( 'Support user notes', 'bbpuser_notes' ); ?></h3>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="bbp_user_notes"><?php _e( 'User notes', 'bbpuser_notes' ); ?></label></th>
				<td>
					<textarea name="bbp_user_notes" id="bbp_user_notes"><?php echo esc_attr( get_the_author_meta( 'bbp_user_notes', $user->ID ) ); ?></textarea><br />
					<span class="description"><?php _e( 'Admin-only notes about the user', 'bbpuser_notes' ); ?></span>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}
add_action( 'show_user_profile', 'bbp_user_notes_admin_profile_fields' );
add_action( 'edit_user_profile', 'bbp_user_notes_admin_profile_fields' );


function save_extra_user_profile_fields( $user_id ) {

	if ( !current_user_can( 'manage_options' ) ) {
		return;
	}

	update_user_meta( $user_id, 'bbp_user_notes', sanitize_text_field( $_POST['bbp_user_notes'] ) );
}
add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );
