jQuery(document).ready(function ($) {
    "use strict";



    //Check Result: Mid-term
    $('#check_mid_term_result_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        var session = $('#session').val();
        var term = $('#term').val();
        var class_id = $('#class_id').val();
        $.ajax ({
            type: "POST",
            url: base_url + c_controller + '/check_result_ajax', 
            data: form_data,
            beforeSend: function () { 
                $("#d_loader").css('display', 'inline'); 
            },
            complete: function () { 
                $("#d_loader").css('display', 'none'); 
            },
            success: function (res) {
                var jres = JSON.parse(res); //json response
                var validation_status = jres.validation_status;
                var validation_errors = jres.validation_errors;

                if (validation_status == 0) {
                    $( '#status_msg' ).html('<div class="alert alert-danger text-center">' + validation_errors + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );

                } else {

                    var response = jres.response;
                    if (response == 1) {
                        var template_id = jres.template_id; 
                        var redirect_url = base_url + c_controller + '/report_card/' + template_id + '/' + session + '/' + term + '/' + class_id;   
                        $( '#status_msg' ).html('<div class="alert alert-success text-center">Result found! Fetching report card...</div>').fadeIn( 'fast' );
                        setTimeout(function() { 
                            $(location).attr('href', redirect_url); //redirect to report card page
                        }, 3000);
                    } else {
                        var message = jres.message; 
                        $('#status_msg').html('<div class="alert alert-danger text-center">' + message + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                    }
                } 
            }
        });
    });



    //Check Result: End-of-term
    $('#check_end_term_result_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        var session = $('#session').val();
        var term = $('#term').val();
        var class_id = $('#class_id').val();
        $.ajax ({
            type: "POST",
            url: base_url + c_controller + '/check_result_ajax', 
            data: form_data,
            beforeSend: function () { 
                $("#d_loader").css('display', 'inline'); 
            },
            complete: function () { 
                $("#d_loader").css('display', 'none'); 
            },
            success: function (res) {
                var jres = JSON.parse(res); //json response
                var validation_status = jres.validation_status;
                var validation_errors = jres.validation_errors;

                if (validation_status == 0) {
                    $( '#status_msg' ).html('<div class="alert alert-danger text-center">' + validation_errors + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );

                } else {

                    var response = jres.response;
                    if (response == 1) {
                        var template_id = jres.template_id; 
                        var redirect_url = base_url + c_controller + '/report_card/' + template_id + '/' + session + '/' + term + '/' + class_id;   
                        $( '#status_msg' ).html('<div class="alert alert-success text-center">Result found! Fetching report card...</div>').fadeIn( 'fast' );
                        setTimeout(function() { 
                            $(location).attr('href', redirect_url); //redirect to report card page
                        }, 3000);
                    } else {
                        var message = jres.message; 
                        $('#status_msg').html('<div class="alert alert-danger text-center">' + message + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                    }
                } 
            }
        });
    });



});