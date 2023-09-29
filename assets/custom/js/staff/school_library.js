jQuery(document).ready(function ($) {
 "use strict";
 
 
	//New Book
	$('#new_book_form').submit(function(e) {
		e.preventDefault();
		var form_data = $('#new_book_form').serialize();
		$.ajax({
			url: base_url + 'school_library_staff/add_new_book_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#status_msg' ).html('<div class="alert alert-success text-center">New book added successfully.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
					$('#new_book_form')[0].reset(); //reset form fields
				} else {
					$('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});
	
	
	
	//Edit Book
	$('#edit_book_form').submit(function(e) {
		e.preventDefault();
		var form_data = $('#edit_book_form').serialize();
		var book_id = $('#book_id').val();
		var redirect_url = base_url + 'school_library_staff';
		$.ajax({
			url: base_url + 'school_library_staff/edit_book_ajax/' + book_id, 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#status_msg' ).html('<div class="alert alert-success text-center">Book edited successfully.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
					setTimeout(function() { 
						$(location).attr('href', redirect_url); //redirect to all books page
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
	
 
	
	//All Books
	var csrf_hash = $('#csrf_hash').val();
	var all_books_table = $('#all_books_table').DataTable({ 
		paging: true,
		pageLength :30,
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
		dom: "<'dt_len_change'l>f<'dt_buttons'B>trip", 
		order: [], //Initial no order.
		language: {
			search: "Search/filter books: ",
			processing: "Please wait a sec...",
			info: "Showing _START_ to _END_ of _TOTAL_ books",
			infoFiltered: "(filtered from _MAX_ total books)",
			emptyTable: "No book to show.",
			lengthMenu: "Show _MENU_ books",
		},
		ajax: {
			url: base_url + 'school_library_staff/all_books_ajax',
			dataType: "json",
			type: "POST",
			data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
		},
		columnDefs: [
			{targets: [0, 1], orderable: false}
		],
		buttons: ExportButtons
	});
	
	
	//Borrowed Books
	var csrf_hash = $('#csrf_hash').val();
	var borrowed_books_table = $('#borrowed_books_table').DataTable({ 
		paging: true,
		pageLength :30,
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
		dom: "<'dt_len_change'l>f<'dt_buttons'B>trip", 
		order: [], //Initial no order.
		language: {
			search: "Search/filter books: ",
			processing: "Please wait a sec...",
			info: "Showing _START_ to _END_ of _TOTAL_ books",
			infoFiltered: "(filtered from _MAX_ total books)",
			emptyTable: "No book to show.",
			lengthMenu: "Show _MENU_ books",
		},
		ajax: {
			url: base_url + 'school_library_staff/borrowed_books_ajax',
			dataType: "json",
			type: "POST",
			data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
		},
		columnDefs: [
			{targets: [0, 1], orderable: false}
		],
		buttons: ExportButtons
	});
	
	
}); //jQuery(document).ready(function)