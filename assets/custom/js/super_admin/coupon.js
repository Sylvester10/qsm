jQuery(document).ready(function ($) {
 "use strict";  



    //New Discount Voucher
    $('#new_free_trial_coupon_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + 'coupon/new_free_trial_coupon_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Discount voucher added successfully.</div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        location.reload(); //reload page
                    }, 3000);
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });



});