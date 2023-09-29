jQuery(document).ready(function ($) {
 "use strict";
 
	
	//Quick Mail
	$('#quick_mail_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			url: base_url + 'school_parent/send_quick_mail_ajax', 
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
	
	
	
	//Email Notifications
	$('#email_notifications_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax({
			url: base_url + 'school_parent/update_email_notifications_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#status_msg' ).html('<div class="alert alert-success text-center">Email notifications updated successfully.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				} else {
					$('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});

	
	
	
	//Check Attendance
	$('#check_attendance_form').submit(function(e) {
		e.preventDefault();
        var form_data = $(this).serialize();
        var child_id = $('#child_id').val();
		$.ajax ({
			type: "POST",
			url: base_url + 'school_parent/check_attendance_ajax/' + child_id,
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



}); //jQuery(document).ready(function)