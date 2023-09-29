jQuery(document).ready(function ($) {
 "use strict";
    
    
    //Login
    $('#staff_login_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        var redirect_url = $('#requested_page_staff').val();
        $.ajax({
            url: base_url + 'staff_acc/login_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#staff_status_msg' ).html('<div class="alert alert-success text-center"> Login successful. Redirecting... <p>If you are not automatically redirected, <a href="' + redirect_url + '">click here</a></p> </div>').fadeIn('fast');
                    setTimeout(function() { 
                        $(location).attr('href', redirect_url);
                    }, 3000);                   
                } else {
                    $( '#staff_status_msg' ).html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );   
                }
            }
        });
    });
    
    
    
    //Set Password
    $('#set_pass_staff_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        var redirect_url = base_url + 'login';              
        $.ajax({
            url: base_url + 'staff_acc/set_password_ajax', 
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
    
    
    //Password Recovery
    $('#recover_pass_staff_form').submit(function(e) {
        e.preventDefault();
        var email = $('#staff_email').val();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + 'staff_acc/recover_password_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $('#staff_status_msg').html('<div class="alert alert-success text-center"> Your request for password retrieval was successful. We have sent an email to [' + email + '] with further instructions to reset your password. </div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                } else {
                    $('#staff_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });
    
    
    //Change Password
    $('#change_pass_staff_form').submit(function(e) {
        e.preventDefault();
        var id = $('#staff_id').val();
        var redirect_url = base_url + 'login';        
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + 'staff_acc/change_password_ajax/' + id, 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $('#status_msg').html('<div class="alert alert-success text-center">Password reset successfully. Redirecting to login page... <p>If you are not automatically redirected, <a href="' + redirect_url + '">click here</a></p> </div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        $(location).attr('href', redirect_url);
                    }, 3000);   
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });




}); 