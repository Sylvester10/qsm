jQuery(document).ready(function ($) {
 "use strict";
 
 
    //Account Activation
    $('#initiate_activation_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        //post to server
        $.ajax ({
            type: "POST",
            url: base_url + 'activate_rave/initiate_activation',
            data: form_data,
            success: function (res) {
                var jres = JSON.parse(res); //json response
                //console.log(jres);
                //console.log(res);
                activate_with_rave( jres.public_key, jres.amount, jres.currency, jres.email, jres.first_name, jres.phone, jres.payment_method, jres.tranx_ref, jres.integrity_hash);    
            }
        });
    });
    
    
    function activate_with_rave(public_key, amount, currency, email, first_name, phone, payment_method, tranx_ref, integrity_hash) {
        var school_id = $('#school_id').val();
        var school_name = $('#school_name').val();
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
            meta: [
                {metaname: "School ID", metavalue: school_id},
                {metaname: "School Name", metavalue: school_name},
            ],
            onclose: function() {},
            callback:function(response) {
                //process callback function using rave response values 
                complete_activation(response.tx.txRef, response.tx.vbvrespmessage, response.tx.status, response.tx.raveRef, response.tx.updatedAt, response.tx.amount);
            }   
        });
    }
    
    
    function complete_activation(tranx_ref, msg, status, rave_ref, date_updated, amount) {
        var csrf_hash = $('#csrf_hash').val();
        $.ajax ({
            type: "POST",
            url: base_url + 'activate_rave/complete_activation',
            data: {tranx_ref, msg, status, rave_ref, date_updated, amount,  'q2r_secure' : csrf_hash},
            success: function (res) {
                console.log(res); 
                //redirect to account info page
                window.location.href = base_url + 'plan/account_info';
            }
        });
    }
    


    //Account Upgrade
    $('#initiate_upgrade_with_rave_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        //post to server
        $.ajax ({
            type: "POST",
            url: base_url + 'upgrade_rave/initiate_upgrade',
            data: form_data,
            success: function (res) {
                var jres = JSON.parse(res); //json response
                var validation_status = jres.validation_status;
                var validation_errors = jres.validation_errors;
                if (validation_status == 0) {
                    $( '#status_msg' ).html('<div class="alert alert-danger text-center">' + validation_errors + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
                } else {
                    upgrade_with_rave( jres.public_key, jres.amount, jres.currency, jres.email, jres.first_name, jres.phone, jres.payment_method, jres.tranx_ref, jres.integrity_hash, jres.upgrade_plan_id);   
                } 
            }
        });
    });
    
    
    function upgrade_with_rave(public_key, amount, currency, email, first_name, phone, payment_method, tranx_ref, integrity_hash, upgrade_plan_id) {
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
                complete_upgrade(response.tx.txRef, response.tx.vbvrespmessage, response.tx.status, response.tx.raveRef, response.tx.updatedAt, response.tx.amount, upgrade_plan_id);
            }   
        });
    }
    
    
    function complete_upgrade(tranx_ref, msg, status, rave_ref, date_updated, amount, upgrade_plan_id) {
        var csrf_hash = $('#csrf_hash').val();
        var redirect_url = base_url + 'plan/account_info';
        $.ajax ({
            type: "POST",
            url: base_url + 'upgrade_rave/complete_upgrade/' + upgrade_plan_id,
            data: {tranx_ref, msg, status, rave_ref, date_updated, amount,  'q2r_secure' : csrf_hash},
            success: function (res) {
                console.log(res); 
                if (res == 0) { //success
                    $( '#status_msg' ).html('<div class="alert alert-success text-center"> Payment successful...</div>').fadeIn('fast');
                    setTimeout(function() { 
                        //redirect to account info page
                        $(location).attr('href', redirect_url);
                    }, 5000);           
                } else {
                    $( '#status_msg' ).html('<div class="alert alert-danger text-center">' + res + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );   
                }
            }
        });
    }
    
    
    

});