jQuery(document).ready(function ($) {
 "use strict";


    //Switch Plan
    $('#switch_plan_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + 'plan/switch_plan_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#sp_status_msg' ).html('<div class="alert alert-success text-center">Plan changed successfully.</div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        location.reload(); //reload page
                    }, 3000);
                } else {
                    $('#sp_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });
    
    


    //Activate Free Trial Coupon
    $('#activate_coupon_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + 'activate/activate_coupon_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#ac_status_msg' ).html('<div class="alert alert-success text-center">Free Trial Coupon activated successfully. Processing discount...</div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        location.reload(); //reload page
                    }, 3000);
                } else {
                    $('#ac_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });
 
    
    
    $('#initiate_upgrade_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        //post to server
        $.ajax ({
            type: "POST",
            url: base_url + 'upgrade/initiate_upgrade',
            data: form_data,
            success: function (res) {
                var jres = JSON.parse(res); //json response
                var validation_status = jres.validation_status;
                var validation_errors = jres.validation_errors;
                if (validation_status == 0) {
                    $( '#status_msg' ).html('<div class="alert alert-danger text-center">' + validation_errors + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
                } else {
                    var upgrade_plan_id = jres.upgrade_plan_id;
                    var redirect_url = base_url + 'upgrade/pay_with_paypal/' + upgrade_plan_id;
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Initiating payment with PayPal...</div>').fadeIn('fast');
                    setTimeout(function() { 
                        $(location).attr('href', redirect_url);
                    }, 3000);
                } 
            }
        });
    });



});