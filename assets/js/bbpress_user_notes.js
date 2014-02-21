/*! bbPress User Notes - v0.1.0
 * http://www.webdevstudios.com
 * Copyright (c) 2014; * Licensed GPLv2+ */
(function(window, document, $, undefined){
	'use strict';

	var $edit = $('#bbpun_toggle_edit');
	var $stop = $('#bbpun_toggle_edit_stop');

	function bbpun_update_user(){
		$.ajax({
			type: 'POST',
			url: bbp_user_notes.ajax_url,
			data: {
				'action': 'bbpun-update-user-notes',
				'note': $('#bbpun_edit').val(),
				'user': bbp_user_notes.user
			},
			dataType: 'json',
			success: function(response) {
				if( response.success) {
					$('#bbpun_messages').html(response.data.message);
				}
				bbpun_turn_off_editing();
			}
		});
	}

	function bbpun_turn_off_editing(){
		$stop.toggleClass('hidden');
		$edit.toggleClass('hidden');
		$('#bbpun_edit').attr( 'disabled', 'disabled' );
	}

	function bbpun_turn_on_editing(){
		$edit.toggleClass('hidden');
		$stop.toggleClass('hidden');
		$('#bbpun_edit').removeAttr( 'disabled', 'disabled' );
	}
	$('#bbpun_toggle_note').on( 'click', function(e){
		e.preventDefault();

		$('.bbpun_notice').toggleClass('fullwidth');
		$('#bbpun_hidden_note').toggleClass('hidden');
	});

	$edit.on('click',function(e){
		e.preventDefault();

		bbpun_turn_on_editing();
	});
	$stop.on('click',function(e){
		e.preventDefault();

		bbpun_update_user();
	});

})(window, document, jQuery);
