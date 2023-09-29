jQuery(document).ready(function ($) {
    "use strict";  


    //Subject Short Name
    //extract first 6 letters from subject as it is typed
    $("#the_subject").keyup(function() { 
        //remove any space in subject name
        var the_subject = $("#the_subject").val();
        var subject_short_joined = the_subject.replace(" ", "");
        var subject_short = subject_short_joined.substring(0, 6);
        $("#subject_short").val(subject_short); 
    });


    //Add New Subject Group
    $('#new_subject_group_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + 'subjects/add_new_subject_group_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">New subject group added successfully.</div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        location.reload(); //reload page
                    }, 3000);
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });
    
    
    
    
    //Add New Subject
    $('#new_subject_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + 'subjects/add_new_subject_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">New subject added successfully.</div>').fadeIn( 'fast' );
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