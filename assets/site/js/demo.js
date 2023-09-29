jQuery(document).ready(function ($) {
 "use strict";
	

	//Admin Role/Level
	$('#demo_admin_role_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax ({
			type: "POST",
			data: form_data,
			url: base_url + 'demo/admin_role_ajax',
			beforeSend: function () { 
				$(".d_loader").css('display', 'inline'); 
			},
        	complete: function () { 
       	 		$(".d_loader").css('display', 'none'); 
       	 	},     
			success: function (res) {
				var jres = JSON.parse(res); //json response
				var validation_status = jres.validation_status;
				var validation_errors = jres.validation_errors;
				if (validation_status == 0) {
					$( '#admin_email' ).val('');
					$( '#admin_name' ).val(''); 
					$( '#admin_login_msg' ).html('<div class="alert alert-danger text-center">' + validation_errors + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
				} else {
					var found = jres.found;
					if (found == 'true') {
						var email = jres.email; 
						var d_name = jres.d_name; 
						$( '#admin_email' ).val(email);
						$( '#admin_name' ).val(d_name);	
					} else {
						var message = jres.message; 
						$( '#admin_email' ).val('');
						$( '#admin_name' ).val(''); 
						$( '#admin_login_msg' ).html('<div class="alert alert-danger text-center">' + message + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
					}
				}
			}
		});
    });


	
	//Admin login
	$('#demo_admin_login_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		var redirect_url = $('#requested_page_admin').val();		
		$.ajax({
			url: base_url + 'demo/admin_login_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#admin_login_msg' ).html('<div class="alert alert-success text-center"> Login successful. Redirecting... <p>If you are not automatically redirected, <a href="' + redirect_url + '">click here</a></p> </div>').fadeIn('fast');
					setTimeout(function() { 
						$(location).attr('href', redirect_url);
					}, 3000);					
				} else {
					$( '#admin_login_msg' ).html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );	
				}
			}
		});
	});
	
	


	//Staff Role
	$('#demo_staff_role_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax ({
			type: "POST",
			data: form_data,
			url: base_url + 'demo/staff_role_ajax',
			beforeSend: function () { 
				$(".d_loader").css('display', 'inline'); 
			},
        	complete: function () { 
       	 		$(".d_loader").css('display', 'none'); 
       	 	},     
			success: function (res) {
				var jres = JSON.parse(res); //json response
				var validation_status = jres.validation_status;
				var validation_errors = jres.validation_errors;
				if (validation_status == 0) {
					$( '#staff_email' ).val('');
					$( '#staff_name' ).val(''); 
					$( '#staff_login_msg' ).html('<div class="alert alert-danger text-center">' + validation_errors + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
				} else {
					var found = jres.found;
					if (found == 'true') {
						var email = jres.email; 
						var d_name = jres.d_name; 
						$( '#staff_email' ).val(email);
						$( '#staff_name' ).val(d_name);
					} else {
						var message = jres.message; 
						$( '#staff_email' ).val('');
						$( '#staff_name' ).val(''); 
						$( '#staff_login_msg' ).html('<div class="alert alert-danger text-center">' + message + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
					}
				}
			}
		});
    });



	//Staff login
	$('#demo_staff_login_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		var redirect_url = $('#requested_page_staff').val();	
		$.ajax({
			url: base_url + 'demo/staff_login_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#staff_login_msg' ).html('<div class="alert alert-success text-center"> Login successful. Redirecting... <p>If you are not automatically redirected, <a href="' + redirect_url + '">click here</a></p> </div>').fadeIn('fast');
					setTimeout(function() { 
						$(location).attr('href', redirect_url);
					}, 3000);					
				} else {
					$( '#staff_login_msg' ).html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );	
				}
			}
		});
	});





	//Student Role
	$('#demo_student_role_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax ({
			type: "POST",
			data: form_data,
			url: base_url + 'demo/student_role_ajax',
			beforeSend: function () { 
				$(".d_loader").css('display', 'inline'); 
			},
        	complete: function () { 
       	 		$(".d_loader").css('display', 'none'); 
       	 	},     
			success: function (res) {
				var jres = JSON.parse(res); //json response
				var validation_status = jres.validation_status;
				var validation_errors = jres.validation_errors;
				if (validation_status == 0) {
					$( '#student_reg_id' ).val('');
					$( '#student_name' ).val(''); 
					$( '#student_login_msg' ).html('<div class="alert alert-danger text-center">' + validation_errors + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
				} else {
					var found = jres.found;
					if (found == 'true') {
						var reg_id = jres.reg_id; 
						var d_name = jres.d_name; 
						$( '#student_reg_id' ).val(reg_id);
						$( '#student_name' ).val(d_name);		
					} else {
						var message = jres.message; 
						$( '#student_reg_id' ).val('');
						$( '#student_name' ).val(''); 
						$( '#student_login_msg' ).html('<div class="alert alert-danger text-center">' + message + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
					}
				}
			}
		});
    });
	
	
	//Student Login
	$('#demo_student_login_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		var redirect_url = $('#requested_page_student').val();					
		$.ajax({
			url: base_url + 'demo/student_login_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#student_login_msg' ).html('<div class="alert alert-success text-center"> Login successful. Redirecting... <p>If you are not automatically redirected, <a href="' + redirect_url + '">click here</a></p> </div>').fadeIn('fast');
					setTimeout(function() { 
						$(location).attr('href', redirect_url);
					}, 3000);					
				} else {
					$( '#student_login_msg' ).html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );	
				}
			}
		});
	});
	
	
	

	//parent Role/Level
	$('#demo_parent_role_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		$.ajax ({
			type: "POST",
			data: form_data,
			url: base_url + 'demo/parent_role_ajax',
			beforeSend: function () { 
				$(".d_loader").css('display', 'inline'); 
			},
        	complete: function () { 
       	 		$(".d_loader").css('display', 'none'); 
       	 	},     
			success: function (res) {
				var jres = JSON.parse(res); //json response
				var validation_status = jres.validation_status;
				var validation_errors = jres.validation_errors;
				if (validation_status == 0) {
					$( '#parent_email' ).val('');
					$( '#parent_name' ).val(''); 
					$( '#parent_login_msg' ).html('<div class="alert alert-danger text-center">' + validation_errors + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
				} else {
					var found = jres.found;
					if (found == 'true') {
						var email = jres.email; 
						var d_name = jres.d_name; 
						$( '#parent_email' ).val(email);
						$( '#parent_name' ).val(d_name);	
					} else {
						var message = jres.message; 
						$( '#parent_email' ).val('');
						$( '#parent_name' ).val(''); 
						$( '#parent_login_msg' ).html('<div class="alert alert-danger text-center">' + message + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
					}
				}
			}
		});
    });



	//Parent login
	$('#demo_parent_login_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		var redirect_url = $('#requested_page_parent').val();					
		$.ajax({
			url: base_url + 'demo/parent_login_ajax', 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#parent_login_msg' ).html('<div class="alert alert-success text-center"> Login successful. Redirecting... <p>If you are not automatically redirected, <a href="' + redirect_url + '">click here</a></p> </div>').fadeIn('fast');
					setTimeout(function() { 
						$(location).attr('href', redirect_url);
					}, 3000);					
				} else {
					$( '#parent_login_msg' ).html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );	
				}
			}
		});
	});



}); 