jQuery(document).ready(function ($) {
    "use strict";  

    
   
    //Add New Section
    $('#new_section_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + 'sections/add_new_section_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">New section added successfully.</div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        location.reload(); //reload page
                    }, 3000);
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });




    //Edit Section
    $('#edit_section_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        var section_id = $('#section_id').val();
        $.ajax({
            url: base_url + 'sections/edit_section_ajax/' + section_id, 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Section edited successfully.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );        
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });



    //mid-term report template image popup on hover
    $('#mt_report_template_image').hover(function() {
        $('#modal_mt_report_template_image').modal('show');
    });

    //end of term report template image popup on hover
    $('#report_template_image').hover(function() {
        $('#modal_report_template_image').modal('show');
    });
    
    
   

}); //jQuery(document).ready(function)