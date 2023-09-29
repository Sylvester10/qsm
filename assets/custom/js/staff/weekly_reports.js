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
	
 
	
	//Weekly Reports
	var session = $('#session').val();
	var term = $('#term').val();
	var csrf_hash = $('#csrf_hash').val();
	var weekly_reports_table = $('#weekly_reports_table').DataTable({ 
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
			search: "Search/filter reports: ",
			processing: "Please wait a sec...",
			info: "Showing _START_ to _END_ of _TOTAL_ reports",
			infoFiltered: "(filtered from _MAX_ total reports)",
			emptyTable: "No report to show.",
			lengthMenu: "Show _MENU_ reports",
		},
		ajax: {
			url: base_url + 'weekly_reports_staff/weekly_reports_ajax/' + session + '/' + term,
			dataType: "json",
			type: "POST",
			data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
		},
		columnDefs: [
			{targets: [0, 1], orderable: false},
			{targets: 4, className: "dt-hide-column"}
		],
		buttons: ExportButtons
	});
	
	
	
	
}); //jQuery(document).ready(function)