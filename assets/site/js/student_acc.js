jQuery(document).ready(function ($) {
 "use strict";
	
	
	//Student Login
	$('#student_login_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		var redirect_url = $('#requested_page_student').val();
		$.ajax({
			url: base_url + 'student_acc/login_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#student_status_msg' ).html('<div class="alert alert-success text-center"> Login successful. Redirecting... <p>If you are not automatically redirected, <a href="' + redirect_url + '">click here</a></p> </div>').fadeIn('fast');
					setTimeout(function() { 
						$(location).attr('href', redirect_url);
					}, 3000);					
				} else {
					$( '#student_status_msg' ).html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );	
				}
			}
		});
	});
	
	
	
	//Set Password: Student
	$('#set_pass_student_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		var redirect_url = base_url + 'user_login';				
		$.ajax({
			url: base_url + 'student_acc/set_password_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#status_msg' ).html('<div class="alert alert-success text-center"> Password set successfully. Redirecting to login page... <p>If you are not automatically redirected, <a href="' + redirect_url + '">click here</a></p> </div>').fadeIn('fast');
					setTimeout(function() { 
						$(location).attr('href', redirect_url);
					}, 3000);					
				} else {
					$( '#status_msg' ).html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );	
				}
			}
		});
	});
	
	
	//Password Recovery: Student
	$('#recover_pass_student_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		var redirect_url = base_url + 'user_login';		
		$.ajax({
			url: base_url + 'student_acc/recover_password_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$('#student_status_msg').html('<div class="alert alert-success text-center"> Your password was changed successfully. Use your Registration ID and new password to login. <p>If you are not automatically redirected, <a href="' + redirect_url + '">click here</a></p> </div>').fadeIn( 'fast' );
					setTimeout(function() { 
						$(location).attr('href', redirect_url);
					}, 5000);	
				} else {
					$('#student_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
				}
			}
		});
	});



}); 