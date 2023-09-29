jQuery(document).ready(function ($) {
 "use strict";
 
 
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
	
 
	
	//Pending Requests
	var session = $('#session').val();
	var term = $('#term').val();
	var csrf_hash = $('#csrf_hash').val();
	var pending_requests_table = $('#pending_requests_table').DataTable({ 
		paging: true,
		pageLength : 10,
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
			search: "Search/filter requests: ",
			processing: "Please wait a sec...",
			info: "Showing _START_ to _END_ of _TOTAL_ requests",
			infoFiltered: "(filtered from _MAX_ total requests)",
			emptyTable: "No request to show.",
			lengthMenu: "Show _MENU_ requests",
		},
		ajax: {
			url: base_url + 'prs_staff/pending_requests_ajax/' + session + '/' + term,
			dataType: "json",
			type: "POST",
			data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
		},
		columnDefs: [
			{targets: 0, orderable: false}
		],
		buttons: ExportButtons
	});
	
	
	//Approved Requests
	var session = $('#session').val();
	var term = $('#term').val();
	var csrf_hash = $('#csrf_hash').val();
	var approved_requests_table = $('#approved_requests_table').DataTable({ 
		paging: true,
		pageLength : 10,
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
			search: "Search/filter requests: ",
			processing: "Please wait a sec...",
			info: "Showing _START_ to _END_ of _TOTAL_ requests",
			infoFiltered: "(filtered from _MAX_ total requests)",
			emptyTable: "No request to show.",
			lengthMenu: "Show _MENU_ requests",
		},
		ajax: {
			url: base_url + 'prs_staff/approved_requests_ajax/' + session + '/' + term,
			dataType: "json",
			type: "POST",
			data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
		},
		columnDefs: [
			{targets: 0, orderable: false}
		],
		buttons: ExportButtons
	});
	
	
	//Declined Requests
	var session = $('#session').val();
	var term = $('#term').val();
	var csrf_hash = $('#csrf_hash').val();
	var declined_requests_table = $('#declined_requests_table').DataTable({ 
		paging: true,
		pageLength : 10,
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
			search: "Search/filter requests: ",
			processing: "Please wait a sec...",
			info: "Showing _START_ to _END_ of _TOTAL_ requests",
			infoFiltered: "(filtered from _MAX_ total requests)",
			emptyTable: "No request to show.",
			lengthMenu: "Show _MENU_ requests",
		},
		ajax: {
			url: base_url + 'prs_staff/declined_requests_ajax/' + session + '/' + term,
			dataType: "json",
			type: "POST",
			data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
		},
		columnDefs: [
			{targets: 0, orderable: false}
		],
		buttons: ExportButtons
	});
	
	
	
	//All Requests
	var session = $('#session').val();
	var term = $('#term').val();
	var csrf_hash = $('#csrf_hash').val();
	var requests_table = $('#requests_table').DataTable({ 
		paging: true,
		pageLength : 10,
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
			search: "Search/filter requests: ",
			processing: "Please wait a sec...",
			info: "Showing _START_ to _END_ of _TOTAL_ requests",
			infoFiltered: "(filtered from _MAX_ total requests)",
			emptyTable: "No request to show.",
			lengthMenu: "Show _MENU_ requests",
		},
		ajax: {
			url: base_url + 'prs_staff/requests_ajax/' + session + '/' + term,
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