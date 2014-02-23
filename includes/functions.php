<?php

function bbpun_get_user_note( $user = 0 ) {
	return get_user_meta( absint( $user ), 'bbp_user_notes', true );
}

function bbpun_set_user_note( $user = 0, $note = '' ) {
	return update_user_meta( absint( $user ), 'bbp_user_notes', $note );
}
