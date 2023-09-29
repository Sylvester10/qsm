jQuery(document).ready(function ($) {
 "use strict";


 	//Create General Announcement
	$('#create_general_announcement_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			url: base_url + 'publications_admin/create_general_announcement_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#create_status_msg' ).html('<div class="alert alert-success text-center">Announcement created successfully.</div>').fadeIn( 'fast' );
					setTimeout(function() { 
						location.reload(); //reload page
					}, 3000);
				} else {
					$('#create_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});
 
	
	//Update General Announcement
	$('#update_general_announcement_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			url: base_url + 'publications_admin/update_general_announcement_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#update_status_msg' ).html('<div class="alert alert-success text-center">Announcement updated successfully.</div>').fadeIn( 'fast' );
					setTimeout(function() { 
						location.reload(); //reload page
					}, 3000);
				} else {
					$('#update_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});



	//Create Staff Announcement
	$('#create_staff_announcement_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			url: base_url + 'publications_admin/create_staff_announcement_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#create_status_msg' ).html('<div class="alert alert-success text-center">Announcement created successfully.</div>').fadeIn( 'fast' );
					setTimeout(function() { 
						location.reload(); //reload page
					}, 3000);
				} else {
					$('#create_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});
 
	
	//Update Staff Announcement
	$('#update_staff_announcement_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			url: base_url + 'publications_admin/update_staff_announcement_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#update_status_msg' ).html('<div class="alert alert-success text-center">Announcement updated successfully.</div>').fadeIn( 'fast' );
					setTimeout(function() { 
						location.reload(); //reload page
					}, 3000);
				} else {
					$('#update_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});




	
	
	//Create Term Date
	$('#create_term_date_form').submit(function(e) {
		e.preventDefault();
		var form_data = $('#create_term_date_form').serialize();
		$.ajax({
			url: base_url + 'publications_admin/create_term_date_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#status_msg' ).html('<div class="alert alert-success text-center">New term date added successfully.</div>').fadeIn( 'fast' );
					setTimeout(function() { 
						location.reload(); //reload page
					}, 3000);
				} else {
					$('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});
	
	
	//Create Calendar Date
	$('#create_calendar_date_form').submit(function(e) {
		e.preventDefault();
		var form_data = $('#create_calendar_date_form').serialize();
		$.ajax({
			url: base_url + 'publications_admin/create_calendar_date_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#status_msg' ).html('<div class="alert alert-success text-center">New calendar event added successfully.</div>').fadeIn( 'fast' );
					setTimeout(function() { 
						location.reload(); //reload page
					}, 3000);
				} else {
					$('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});
	
	
	
	
	//print and export buttons for DataTables
	var ExportButtons = [
		{
			extend: 'colvis', //column visibility
			className: 'data_export_buttons'
		},
		{
			extend: 'print',
			className: 'data_export_buttons',
			exportOptions: {
				columns: ':visible'
			}
		},
		{
			extend: 'excel',
			className: 'data_export_buttons',
			exportOptions: {
				columns: ':visible'
			}
		},
		{
			extend: 'csv',
			className: 'data_export_buttons',
			exportOptions: {
				columns: ':visible'
			}
		},
		{
			extend: 'pdf',
			className: 'data_export_buttons',
			exportOptions: {
				columns: ':visible'
			}
		}
	];
	
	
		
	
	//Term Dates
	var csrf_hash = $('#csrf_hash').val();
	var term_dates_table = $('#term_dates_table').DataTable({ 
		paging: true,
		pageLength : 25,
		lengthChange: true, 
		searching: true,
		info: true,
		scrollX: true,
		autoWidth: false,
		ordering: true,
		stateSave: true,
		processing: false, 
		serverSide: true, 
		pagingType: "simple_numbers", 
		dom: "<'dt_len_change'l>f<'dt_buttons'B>trip", //lengthChange, filter, Buttons, table, processing, info, pagination (in this order)
		order: [], //Initial no order.
		language: {
			search: "Search/filter term dates: ",
			processing: "Please wait a sec...",
			info: "Showing _START_ to _END_ of _TOTAL_ term dates",
			infoFiltered: "(filtered from _MAX_ total term dates)",
			emptyTable: "No term date to show.",
			lengthMenu: "Show _MENU_ term dates",
		},
		ajax: {
			url: base_url + 'publications_admin/term_dates_ajax',
			dataType: "json",
			type: "POST",
			data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
		},
		columnDefs: [
			{targets: 0, orderable: false}
		],
		buttons: ExportButtons
	});
	

	
	
}); //jQuery(document).ready(function)