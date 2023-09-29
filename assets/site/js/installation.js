jQuery(document).ready(function ($) {
 "use strict";


	//School Initials
	//extract first 3 letters from School name as it is typed during installation
	$("#school_name").keyup(function() { 
		var school_name = $(this).val().toUpperCase();
		//remove any space in school name
		var new_school_name = school_name.replace(" ", "");
		var school_abbr = new_school_name.substring(0, 3);
		$("#school_abbr").val(school_abbr); 
	});


	//School Initials
	//convert string to uppercase
	$("#school_abbr").keyup(function() { 
		this.value = this.value.toUpperCase();
	});


	
	//software install
	$('#free_trial_install_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		var email = $('#admin_email').val();
		var school_name = $('#school_name').val();
		$.ajax({
			type: 'POST',
			data: form_data, 
			url: base_url + 'installation/free_trial_install_ajax',
			beforeSend: function () { 
				$( '#processing_msg' ).html('<div class="alert alert-info">Processing form...please wait</div>').fadeIn('fast');
			},
        	complete: function () { 
       	 		$( '#processing_msg' ).css('display', 'none');
       	 	},     
			success: function(msg) {
				if (msg == 1) {
					$( '#status_msg' ).html('<div class="alert alert-success"> Application installed successfully for ' + school_name + '. Before you can begin using your account, we have to ensure your email address is valid. We have sent a message to [' + email + '] with further instructions to confirm your account. Please check your inbox or spam folder for this message. </div>').fadeIn('fast');
				} else {
					$( '#status_msg' ).html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );	
				}
			}
		});
	});



	$('#buy_install_form').submit(function(e) {
		e.preventDefault();
        var form_data = $(this).serialize();
		//post to server
		$.ajax ({
			type: "POST",
			data: form_data,
			url: base_url + 'installation/buy_install_ajax',
			beforeSend: function () { 
				$( '#processing_msg' ).html('<div class="alert alert-info">Processing form...please wait</div>').fadeIn('fast');
			},
        	complete: function () { 
       	 		$( '#processing_msg' ).css('display', 'none');
       	 	},     
			success: function (res) {
				var jres = JSON.parse(res); //json response
				var validation_status = jres.validation_status;
				var validation_errors = jres.validation_errors;
				if (validation_status == 0) {
					$( '#status_msg' ).html('<div class="alert alert-danger text-center">' + validation_errors + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
				} else {
					var school_id = jres.school_id;
					var plan_id = jres.plan_id;
					var redirect_url = base_url + 'installation/pay_with_paypal/' + school_id + '/' + plan_id;
					$( '#status_msg' ).html('<div class="alert alert-success text-center"> Application installed successfully. Initiating payment with PayPal...</div>').fadeIn('fast');
					setTimeout(function() { 
						$(location).attr('href', redirect_url);
					}, 3000);
				} 
			}
		});
    });



    //software install
	$('#buy_other_install_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		var email = $('#admin_email').val();
		var school_name = $('#school_name').val();
		$.ajax({
			type: 'POST',
			data: form_data, 
			url: base_url + 'installation/buy_other_install_ajax',
			beforeSend: function () { 
				$( '#processing_msg' ).html('<div class="alert alert-info">Processing form...please wait</div>').fadeIn('fast');
			},
        	complete: function () { 
       	 		$( '#processing_msg' ).css('display', 'none');
       	 	},     
			success: function(msg) {
				if (msg == 1) {
					$( '#status_msg' ).html('<div class="alert alert-success"> Application installed successfully for ' + school_name + '. Before you can begin using your account, we have to ensure your email address is valid. We have sent a message to [' + email + '] with further instructions to confirm your account. Please check your inbox or spam folder for this message. </div>').fadeIn('fast');
				} else {
					$( '#status_msg' ).html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );	
				}
			}
		});
	});
	

		
	
	//Confirm School Account
	$('#confirm_school_account_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		var school_id = $('#school_id').val();
		var confirm_code = $('#confirm_code').val();
		var redirect_url = base_url + 'login';				
		$.ajax({
			url: base_url + 'installation/confirm_school_account_ajax/' + school_id + '/' + confirm_code, 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#confirm_status_msg' ).html('<div class="alert alert-success text-center">Confirmation completed successfully. Redirecting to login page...</div>').fadeIn('fast');
					setTimeout(function() { 
						$(location).attr('href', redirect_url);
					}, 3000);					
				} else {
					$( '#confirm_status_msg' ).html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );	
				}
			}
		});
	});
	


	//Resend Confirmation
	$('#resend_confirmation_form').submit(function(e) {
		e.preventDefault();
		var form_data = $(this).serialize();
		var school_id = $('#school_id').val();
		$.ajax({
			url: base_url + 'installation/resend_confirmation_ajax/' + school_id, 
			type: 'POST',
			data: form_data, 
			success: function(msg) {
				if (msg == 1) {
					$( '#resend_status_msg' ).html('<div class="alert alert-success text-center">Confirmation resent successfully. Check your inbox/spam folder for this message.</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );	
					//hide resend button
					$('#resend_btn').css('display', 'none');	
				} else {
					$( '#resend_status_msg' ).html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );	
				}
			}
		});
	});


}); 