jQuery(document).ready(function ($) {
 "use strict";
 
	
	//Quick Mail
	$('#quick_mail_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			url: base_url + 'student/send_quick_mail_ajax', 
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

	
	
	
	//Check Attendance
	$('#check_attendance_form').submit(function(e) {
		e.preventDefault();
        var form_data = $(this).serialize();
		$.ajax ({
			type: "POST",
			url: base_url + 'student/check_attendance_ajax',
			data: form_data,
			success: function (res) {
				var jres = JSON.parse(res); //json response
				var validation_status = jres.validation_status;
				var validation_errors = jres.validation_errors;
				if (validation_status == 0) {
					$( '#attendance_details_box' ).css('display', 'none');
					$( '#status_msg' ).html('<div class="alert alert-danger text-center">' + validation_errors + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
				} else {
					var att_exists = jres.att_exists;
					if (att_exists == 'true') {
						var session = jres.session; 
						var term = jres.term; 
						var s_class = jres.class; 
						var date = jres.date; 
						var status = jres.status; 
						$( '#status_msg' ).css('display', 'none');
						$( '#attendance_details_box' ).css('display', 'block');
						$( '#session' ).html(session);
						$( '#term' ).html(term);
						$( '#class' ).html(s_class);
						$( '#date' ).html(date);
						$( '#status' ).html(status);
					} else {
						var message = jres.message; 
						$( '#status_msg' ).css('display', 'block');
						$( '#attendance_details_box' ).css('display', 'none');
						$( '#status_msg' ).html('<div class="alert alert-danger text-center">' + message + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
					}
				} 
			}
		});
    });



	//Class Students
	var csrf_hash = $('#csrf_hash').val();
	var student_class_table = $('#student_class_table').DataTable({ 
		paging: true,
		pageLength :30,
		lengthChange: false, 
		searching: true,
		info: false,
		scrollX: true,
		autoWidth: false,
		ordering: true,
		stateSave: true,
		processing: false, 
		serverSide: true, 
		pagingType: "simple_numbers", 
		dom: "<'dt_len_change'l>ftrip", 
		order: [], //Initial no order.
		language: {
			search: "Search classmate: ",
			processing: "Please wait a sec...",
			info: "Showing _START_ to _END_ of _TOTAL_ students",
			infoFiltered: "(filtered from _MAX_ total students)",
			emptyTable: "No student to show.",
			lengthMenu: "Show _MENU_ students",
		},
		ajax: {
			url: base_url + 'student/student_class_ajax',
			dataType: "json",
			type: "POST",
			data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
		},
		columnDefs: [
			{targets: [0, 1], orderable: false}
		],
	});
	
	

}); //jQuery(document).ready(function)