jQuery(document).ready(function ($) {
 "use strict";	

	
	//Quick Mail
	$('#quick_mail_form').submit(function(e) {
		e.preventDefault();
		var form_data = $('#quick_mail_form').serialize();
		$.ajax({
			url: base_url + 'admin/send_quick_mail_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#q_status_msg' ).html('<div class="alert alert-success text-center">Mail successfully sent.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				} else {
					$('#q_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});
	
	
	//Bulk Email
	$('#bulk_mail_form').submit(function(e) {
		e.preventDefault();
		var form_data = $('#bulk_mail_form').serialize();
		$.ajax({
			url: base_url + 'admin/send_bulk_mail_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#b_status_msg' ).html('<div class="alert alert-success text-center">Mail sent successfully to group.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				} else {
					$('#b_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});



	//Edit Profile
	$('#edit_profile_form').submit(function(e) {
		e.preventDefault();
		var form_data = $('#edit_profile_form').serialize();
		$.ajax({
			url: base_url + 'admin/edit_profile_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#status_msg' ).html('<div class="alert alert-success text-center">Profile updated successfully.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
					setTimeout(function() { 
						location.reload(); //reload page
					}, 3000);
				} else {
					$('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});


	
	//Contact Vendor
	$('#contact_vendor_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			url: base_url + 'admin/contact_vendor_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#status_msg' ).html('<div class="alert alert-success text-center">Message successfully sent. We\'ll get in touch with you shortly.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
					$('#contact_us_form')[0].reset(); //reset form fields
				} else {
					$('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});

	
	
	
	//Notifications
	var csrf_hash = $('#csrf_hash').val();
	var notifs_table = $('#notifs_table').DataTable({ 
		paging: true,
		pageLength : 10,
		lengthChange: true, 
		searching: true,
		info: true,
		autoWidth: false,
		ordering: true,
		stateSave: true,
		processing: false, 
		serverSide: true, 
		pagingType: "simple_numbers", 
		dom: "<'dt_len_change'l>ftrip", //lengthChange, filter, table, processing, info, pagination (in this order)
		order: [], //Initial no order.
		language: {
			search: "Search/filter notifications: ",
			processing: "Please wait a sec...",
			info: "Showing _START_ to _END_ of _TOTAL_ notifications",
			infoFiltered: "(filtered from _MAX_ total notifications)",
			emptyTable: "No notification to show.",
			lengthMenu: "Show _MENU_ notifications",
		},
		ajax: {
			url: base_url + 'admin/notifications_ajax',
			dataType: "json",
			type: "POST",
			data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
		},
		columnDefs: [
			{targets: 0, orderable: false}
		],
	});
	
	


}); //jQuery(document).ready(function)