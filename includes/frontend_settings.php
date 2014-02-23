<?php

function bbpun_user_notes_ajax_data() {
	$data = array(
		'ajax_url'      => esc_url( admin_url( 'admin-ajax.php', 'relative' ) ),
		'nonce'         => wp_create_nonce( 'frontend_update_user_notes' ),
		'user'          => bbp_get_topic_author_id()
	);
	return $data;
}

function bbpun_load_frontend_js() {
	$data = bbpun_user_notes_ajax_data();

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	wp_enqueue_script( 'bbp_user_notes', plugin_dir_url( dirname( __FILE__ ) ) . "assets/js/bbpress_user_notes$min.js", array( 'jquery' ), '', true );
	wp_localize_script( 'bbp_user_notes', 'bbp_user_notes', $data );
}
add_action( 'wp_enqueue_scripts', 'bbpun_load_frontend_js' );

function bbpun_load_frontend_css() {
	echo '<style>.bbpun_notice { float: left; padding: 10px; } .fullwidth { width: 97%; } textarea { min-height: 100px; width: 100%; } .hidden { display: none; }</style>';
}
add_action( 'wp_head', 'bbpun_load_frontend_css' );

function bbpun_add_to_top() {
	if ( !current_user_can( 'manage_options' ) ) {
		return;
	}

	$user = get_user_by( 'id', bbp_get_topic_author_id() );
	$user_notes = get_user_meta( $user->ID, 'bbp_user_notes', true );

	echo '<div class="bbp-template-notice info bbpun_notice">';
	echo '<a id="bbpun_toggle_note" href="#">' . sprintf( __( 'Click to toggle %s\'s user notes', 'bbpuser_notes' ), $user->user_login ) . '</a>';

	echo '<div id="bbpun_hidden_note" class="hidden">';

	echo '<textarea id="bbpun_edit" disabled="disabled">' . $user_notes . '</textarea>';

	echo '<p><a id="bbpun_toggle_edit" href="#">' . __( 'Edit notes', 'bbpuser_notes' ) . '</a><a id="bbpun_toggle_edit_stop" class="hidden" href="#">' . __( 'Stop Editing', 'bbpuser_notes' ) . '</a></p>';
	echo '<div id="bbpun_messages"></div>';
	echo '</div>';

	echo '</div>';
}
add_action( 'bbp_template_after_pagination_loop', 'bbpun_add_to_top' );

function bbpun_handle_user_note_update() {
	if ( !empty( $_REQUEST ) ) {
		$success = update_user_meta( $_REQUEST['user'], 'bbp_user_notes', $_REQUEST['note'] );
	}
	if ( $success ) {
		wp_send_json_success( array(
			'message' => __( 'User updated successfully!', 'bbpuser_notes' )
		) );
	} else {
		wp_send_json_error( array(
			'message' => __( 'User not updated!', 'bbpuser_notes' )
		) );
	}
}
add_action( 'wp_ajax_bbpun-update-user-notes', 'bbpun_handle_user_note_update' );
add_action( 'wp_ajax_nopriv_bbpun-update-user-notes', 'bbpun_handle_user_note_update' );

function bbpun_bbpress_edit_profile() {
	if ( !current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div>
		<label for="bbp_user_notes"><?php _e( 'User Notes', 'bbpuser_notes' ); ?></label>
		<textarea name="bbp_user_notes" id="bbp_user_notes" rows="5" cols="30" tabindex="<?php bbp_tab_index(); ?>"><?php bbp_displayed_user_field( 'bbp_user_notes', 'edit' ); ?></textarea>
	</div>
<?php
}
add_action( 'bbp_user_edit_after_about', 'bbpun_bbpress_edit_profile' );

function bbpun_bbpress_edit_profile_handler() {

	if ( !current_user_can( 'manage_options' ) ) {
		return;
	}

	$original = get_user_meta( $_POST['user_id'], 'bbp_user_notes', true );

	//Unedited
	if ( $_POST['bbp_user_notes'] == $original ) {
		return;
	}

	$new = sanitize_text_field( $_POST['bbp_user_notes'] );

	update_user_meta( $_POST['user_id'], 'bbp_user_notes', $new );
}
add_action( 'bbp_post_request_bbp-update-user', 'bbpun_bbpress_edit_profile_handler', 1 );
