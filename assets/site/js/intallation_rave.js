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


        

    $('#buy_application_with_rave_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        //post to server
        $.ajax ({
            type: "POST",
            url: base_url + 'buy_rave/buy_application_ajax',
            data: form_data,
            success: function (res) {
                var jres = JSON.parse(res); //json response
                var validation_status = jres.validation_status;
                var validation_errors = jres.validation_errors;
                if (validation_status == 0) {
                    $( '#status_msg' ).html('<div class="alert alert-danger text-center">' + validation_errors + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
                } else {
                    pay_with_rave( jres.public_key, jres.amount, jres.currency, jres.email, jres.first_name, jres.phone, jres.payment_method, jres.tranx_ref, jres.integrity_hash, jres.school_id); 
                } 
            }
        });
    });
    
    
    function pay_with_rave(public_key, amount, currency, email, first_name, phone, payment_method, tranx_ref, integrity_hash, school_id) {
        getpaidSetup({
            PBFPubKey: public_key,
            amount: amount,
            currency: currency, //NGN or USD depending on user's location
            customer_email: email,
            customer_firstname: first_name,
            customer_phone: phone,
            payment_method: payment_method,
            txref: tranx_ref,
            integrity_hash: integrity_hash,
            onclose: function() {},
            callback:function(response) {
                //process callback function using rave response values 
                complete_payment(response.tx.txRef, response.tx.vbvrespmessage, response.tx.status, response.tx.raveRef, response.tx.updatedAt, response.tx.amount, email, school_id);
            }   
        });
    }
    
    
    function complete_payment(tranx_ref, msg, status, rave_ref, date_updated, amount, email, school_id) {
        var csrf_hash = $('#csrf_hash').val();
        $.ajax ({
            type: "POST",
            url: base_url + 'buy_rave/complete_payment/' + school_id,
            data: {tranx_ref, msg, status, rave_ref, date_updated, amount,  'q2r_secure' : csrf_hash},
            success: function (res) {
                console.log(res); 
                if (res == 0) { //success
                    $( '#status_msg' ).html('<div class="alert alert-success"> Application installed successfully for ' + school_name + '. Before you can begin using your account, we have to ensure your email address is valid. We have sent a message to [' + email + '] with further instructions to confirm your account. Please check your inbox or spam folder for this message. </div>').fadeIn('fast');
                } else {
                    $( '#status_msg' ).html('<div class="alert alert-danger text-center">' + res + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );   
                }
            }
        });
    }



}); 