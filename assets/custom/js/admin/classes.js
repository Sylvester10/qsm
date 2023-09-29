jQuery(document).ready(function ($) {
    "use strict";  

    
    //Add New Class
    $('#new_class_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + 'classes/add_new_class_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">New class added successfully.</div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        location.reload(); //reload page
                    }, 3000);
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });
     
   

}); //jQuery(document).ready(function)