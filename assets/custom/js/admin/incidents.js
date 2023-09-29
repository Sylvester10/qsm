jQuery(document).ready(function ($) {
 "use strict";



	//New Incident
	$('#new_incident_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		var student_id = $('#student_id').val();
		var redirect_url = base_url + 'incidents/student_incidents/' + student_id;
		$.ajax({
			url: base_url + 'incidents/new_incident_ajax/' + student_id, 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#status_msg' ).html('<div class="alert alert-success text-center">Incident recorded successfully.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
					$('#new_incident_form')[0].reset(); //reset form fields
					setTimeout(function() { 
						$(location).attr('href', redirect_url);
					}, 3000);
				} else {
					$('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});



	//Edit Incident
	$('#edit_incident_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		var incident_id = $('#incident_id').val();
		var student_id = $('#student_id').val();
		var redirect_url = base_url + 'incidents/student_incidents/' + student_id;
		$.ajax({
			url: base_url + 'incidents/edit_incident_ajax/' + incident_id, 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#status_msg' ).html('<div class="alert alert-success text-center">Incident updated successfully.</div>').fadeIn( 'fast' );
					setTimeout(function() { 
						$(location).attr('href', redirect_url);
					}, 3000);
				} else {
					$('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});
	
 
	
	//All Incidents
	var csrf_hash = $('#csrf_hash').val();
	var incidents_table = $('#incidents_table').DataTable({ 
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
			search: "Search/filter incidents: ",
			processing: "Please wait a sec...",
			info: "Showing _START_ to _END_ of _TOTAL_ incidents",
			infoFiltered: "(filtered from _MAX_ total incidents)",
			emptyTable: "No incident to show.",
			lengthMenu: "Show _MENU_ incidents",
		},
		ajax: {
			url: base_url + 'incidents/all_incidents_ajax',
			dataType: "json",
			type: "POST",
			data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
		},
		columnDefs: [
			{targets: [0, 1], orderable: false}
		],
		buttons: ExportButtons
	});



	//Student Incidents
	var student_id = $('#student_id').val();
	var csrf_hash = $('#csrf_hash').val();
	var student_incidents_table = $('#student_incidents_table').DataTable({ 
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
			search: "Search/filter incidents: ",
			processing: "Please wait a sec...",
			info: "Showing _START_ to _END_ of _TOTAL_ incidents",
			infoFiltered: "(filtered from _MAX_ total incidents)",
			emptyTable: "No incident to show.",
			lengthMenu: "Show _MENU_ incidents",
		},
		ajax: {
			url: base_url + 'incidents/student_incidents_ajax/' + student_id,
			dataType: "json",
			type: "POST",
			data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
		},
		columnDefs: [
			{targets: [0, 1], orderable: false}
		],
		buttons: ExportButtons
	});

	
	
	//Incident Evidence
	var csrf_hash = $('#csrf_hash').val();
	var incident_id = $('#incident_id').val();
	var evidence_table = $('#evidence_table').DataTable({ 
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
		order: [], //Initial no order.
		language: {
			search: "Search/filter evidence: ",
			processing: "Please wait a sec...",
			info: "Showing _START_ to _END_ of _TOTAL_ evidences",
			infoFiltered: "(filtered from _MAX_ total evidences)",
			emptyTable: "No evidence to show.",
			lengthMenu: "Show _MENU_ evidences",
		},
		ajax: {
			url: base_url + 'incidents/evidence_ajax/' + incident_id,
			dataType: "json",
			type: "POST",
			data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
		},
		columnDefs: [
			{targets: [0, 1, 2], orderable: false}
		],
	});
	
	
}); //jQuery(document).ready(function)