jQuery(document).ready(function ($) {
    "use strict";

    
    //Select Lesson Period Type
    $('#period_type').change(function () {
        if ( $('#pt_subject').is(':selected') ) {
            $('#subject_area').css('display', 'block');
            $('#break_area').css('display', 'none');
            $('#other_activity_area').css('display', 'none');
            $('#subject_id').attr('required', 'required');
            $('#break_type').removeAttr('required');
            $('#other_activity').removeAttr('required');
        } else if ( $('#pt_break').is(':selected') ) {
            $('#subject_area').css('display', 'none');
            $('#break_area').css('display', 'block');
            $('#other_activity_area').css('display', 'none');
            $('#subject_id').removeAttr('required');
            $('#break_type').attr('required', 'required');
            $('#other_activity').removeAttr('required');
        } else if ( $('#pt_other_activity').is(':selected') ) {
            $('#subject_area').css('display', 'none');
            $('#break_area').css('display', 'none');
            $('#other_activity_area').css('display', 'block');
            $('#subject_id').removeAttr('required');
            $('#break_type').removeAttr('required');
            $('#other_activity').attr('required', 'required');
        }
    });



    //New Lesson Period
    $('#new_lesson_period_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + 'timetable_admin/new_lesson_period_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Lesson period added successfully.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });
    
    
    
    //Edit Lesson Periods
    $('#edit_lesson_period_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        var lesson_period_id = $('#lesson_period_id').val();
        var class_id = $('#class_id').val();
        var redirect_url = base_url + 'timetable_admin/lesson_periods/' + class_id;
        $.ajax({
            url: base_url + 'timetable_admin/edit_lesson_period_ajax/' + lesson_period_id, 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Lesson period edited successfully.</div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        $(location).attr('href', redirect_url); 
                    }, 3000);
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });




    //Duplicate Lesson Periods
    $('#duplicate_period_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        var old_class_id = $('#old_class_id').val();
        var old_class = $('#old_class').val();
        $.ajax({
            url: base_url + 'timetable_admin/duplicate_lesson_period_ajax/' + old_class_id, 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Lesson periods duplicated successfully.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });



    //New Test Schedule
    $('#new_test_schedule_form').submit(function(e) {
        e.preventDefault();
        var form_data = $('#new_test_schedule_form').serialize();
        $.ajax({
            url: base_url + 'timetable_admin/new_test_schedule_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Test schedule added successfully.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });


    //New Exam Schedule
    $('#new_exam_schedule_form').submit(function(e) {
        e.preventDefault();
        var form_data = $('#new_exam_schedule_form').serialize();
        $.ajax({
            url: base_url + 'timetable_admin/new_exam_schedule_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Exam schedule added successfully.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });



});