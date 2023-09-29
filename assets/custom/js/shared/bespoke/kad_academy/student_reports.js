jQuery(document).ready(function ($) {
    "use strict"; 


    //ensure value entered in progress field does not exceed 3
    $('.progress_field').keyup(function() {
        $(this).each(function() {
            var value = $(this).val();
            if (value < 1 || value > 3) {
                $(this).val('');
            } 
        });  
    });

    //ensure value entered in effort field does not exceed 3
    $('.effort_field').keyup(function() {
        $(this).each(function() {
            var value = $(this).val();
            if (value < 1 || value > 3) {
                $(this).val('');
            } 
        });  
    });

    //ensure value entered in assessment field does not exceed 100
    $('.assessment_field').keyup(function() {
        $(this).each(function() {
            var value = $(this).val();
            if (value > 100) {
                $(this).val('');
            } 
        });  
    });


    //MID TERM REPORTS


    //Mid-term Reports
    $('#produce_mid_term_report_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + produce_report_url, 
            type: 'POST',
            data: form_data, 
            beforeSend: function () { 
                $("#d_loader_achievement").css('display', 'inline'); 
            },
            complete: function () { 
                $("#d_loader_achievement").css('display', 'none'); 
            },
            success: function(msg) {
                if (msg == 1) {
                    $( '#achievement_status_msg' ).html('<div class="alert alert-success text-center">Subject scores submitted successfully.</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                    setTimeout(function() { 
                        $( '#extra_msg' ).html('<div class="alert alert-success text-center">Reload this page to delete scores for newly produced subject scores or to add class teacher comment (if allowed).</div>').fadeIn( 'slow' ).delay( 15000 ).fadeOut( 'slow' );
                    }, 3000);
                } else {
                    $('#achievement_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                }
            }
        });
    });


    //Mid-term Class Teacher Comment
    $('#produce_mid_term_ct_comment_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + produce_ct_comment_url, 
            type: 'POST',
            data: form_data,
            beforeSend: function () { 
                $("#d_loader_ct_comment").css('display', 'inline'); 
            },
            complete: function () { 
                $("#d_loader_ct_comment").css('display', 'none'); 
            }, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#ct_comment_status_msg' ).html('<div class="alert alert-success text-center">Class Teacher comment submitted successfully.</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                } else {
                    $('#ct_comment_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                }
            }
        });
    }); 



    //Mid-term Students Report
    var csrf_hash = $('#csrf_hash').val();
    var session = $('#session').val();
    var term = $('#term').val();
    var class_id = $('#class_id').val();
    var students_mid_term_report_table = $('#students_mid_term_report_table').DataTable({ 
        paging: true,
        pageLength :50,
        lengthChange: true, 
        searching: true,
        info: true,
        scrollX: true,
        autoWidth: false,
        ordering: false,
        stateSave: true,
        processing: false, 
        serverSide: true, 
        pagingType: "simple_numbers", 
        dom: "<'dt_len_change'l>f<'dt_buttons'B>trip", 
        order: [], //Initial no order.
        language: {
            search: "Search/filter results: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ results",
            infoFiltered: "(filtered from _MAX_ total results)",
            emptyTable: "No result to show.",
            lengthMenu: "Show _MENU_ results",
        },
        ajax: {
            url: base_url + c_controller + '/students_report_ajax/' + session + '/' + term + '/' + class_id,
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        buttons: ExportButtons
    });





    //END-OF-TERM REPORTS


    //select all: colours
    $('#select_all_colours').change(function(){  
        $('.colour_checkbox').prop('checked', $(this).prop('checked'));
    });

    //select all: shapes
    $('#select_all_shapes').change(function(){  
        $('.shape_checkbox').prop('checked', $(this).prop('checked'));
    });

    //select all: letters upper
    $('#select_all_letters_upper').change(function(){  
        $('.letter_upper_checkbox').prop('checked', $(this).prop('checked'));
    });

    //select all: letters lower
    $('#select_all_letters_lower').change(function(){  
        $('.letter_lower_checkbox').prop('checked', $(this).prop('checked'));
    });

    //select all: letters isolated
    $('#select_all_letters_isolated').change(function(){  
        $('.letter_isolated_checkbox').prop('checked', $(this).prop('checked'));
    });

    //select all: numbers
    $('#select_all_numbers').change(function(){  
        $('.number_checkbox').prop('checked', $(this).prop('checked'));
    });

    //attendance type (default)
    if ( $('#customize_att').is(':checked') ) {
        $('#class_att_table').css('display', 'none');
        $('#custom_att_table').css('display', 'block');
    } else {
        $('#class_att_table').css('display', 'block');
        $('#custom_att_table').css('display', 'none');
    }
    //attendance type (on change)
    $('#customize_att').change(function(){ 
        if ( $(this).is(':checked') ) {
            $('#class_att_table').css('display', 'none');
            $('#custom_att_table').css('display', 'block');
        } else {
            $('#class_att_table').css('display', 'block');
            $('#custom_att_table').css('display', 'none');
        }
    });


    //Skills: Exemplary (E)
    $('#select_all_skill_E').change(function() {
        $('.skill_E_checkbox').prop('checked', true).change();
    });
    //Skills: Consistently (C)
    $('#select_all_skill_C').change(function() {
        $('.skill_C_checkbox').prop('checked', true).change();
    });
    //Skills: Usually (U)
    $('#select_all_skill_U').change(function() {
        $('.skill_U_checkbox').prop('checked', true).change();
    });
    //Skills: Sometimes (S)
    $('#select_all_skill_S').change(function() {
        $('.skill_S_checkbox').prop('checked', true).change();
    });
    //Skills: Rarely (R)
    $('#select_all_skill_R').change(function() {
        $('.skill_R_checkbox').prop('checked', true).change();
    });
    //Deselect all
    $('#deselect_all_skills').change(function() {
        $('.skill_E_checkbox').prop('checked', false).change();
        $('.skill_C_checkbox').prop('checked', false).change();
        $('.skill_U_checkbox').prop('checked', false).change();
        $('.skill_S_checkbox').prop('checked', false).change();
        $('.skill_R_checkbox').prop('checked', false).change();
    });


    //End-term Reports
    $('#produce_end_term_report_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + produce_report_url, 
            type: 'POST',
            data: form_data, 
            beforeSend: function () { 
                $("#d_loader_achievement").css('display', 'inline'); 
            },
            complete: function () { 
                $("#d_loader_achievement").css('display', 'none'); 
            },
            success: function(msg) {
                if (msg == 1) {
                    $( '#achievement_status_msg' ).html('<div class="alert alert-success text-center">Subject scores submitted successfully.</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                    setTimeout(function() { 
                        $( '#extra_msg' ).html('<div class="alert alert-success text-center">Reload this page to delete scores for newly produced subject scores or to add class teacher comment (if allowed).</div>').fadeIn( 'slow' ).delay( 15000 ).fadeOut( 'slow' );
                    }, 3000);
                } else {
                    $('#achievement_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                }
            }
        });
    });



    //End-term Misc
    $('#produce_end_term_misc_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + produce_misc_url, 
            type: 'POST',
            data: form_data,
            beforeSend: function () { 
                $("#d_loader_misc").css('display', 'inline'); 
            },
            complete: function () { 
                $("#d_loader_misc").css('display', 'none'); 
            }, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#misc_status_msg' ).html('<div class="alert alert-success text-center">Parameters submitted successfully.</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                } else {
                    $('#misc_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                }
            }
        });
    }); 



    //End-term Year 3 - 6 Progress score
    $('#produce_end_term_year_3_6_progress_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + produce_year_3_6_progress_url, 
            type: 'POST',
            data: form_data,
            beforeSend: function () { 
                $("#d_loader_year_3_6_progress").css('display', 'inline'); 
            },
            complete: function () { 
                $("#d_loader_year_3_6_progress").css('display', 'none'); 
            }, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#year_3_6_progress_status_msg' ).html('<div class="alert alert-success text-center">Parameters submitted successfully.</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                    setTimeout(function() { 
                        $( '#year_3_6_extra_msg' ).html('<div class="alert alert-success text-center">Reload this page to delete scores for newly produced progress scores.</div>').fadeIn( 'slow' ).delay( 15000 ).fadeOut( 'slow' );
                    }, 3000);
                } else {
                    $('#year_3_6_progress_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                }
            }
        });
    }); 




    //End-term Attendance
    $('#produce_end_term_att_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + produce_att_url, 
            type: 'POST',
            data: form_data,
            beforeSend: function () { 
                $("#d_loader_att").css('display', 'inline'); 
            },
            complete: function () { 
                $("#d_loader_att").css('display', 'none'); 
            }, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#att_status_msg' ).html('<div class="alert alert-success text-center">Attendance scores submitted successfully.</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                } else {
                    $('#att_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                }
            }
        });
    }); 




    //End-term Class Teacher Comment
    $('#produce_end_term_ct_comment_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + produce_ct_comment_url, 
            type: 'POST',
            data: form_data,
            beforeSend: function () { 
                $("#d_loader_ct_comment").css('display', 'inline'); 
            },
            complete: function () { 
                $("#d_loader_ct_comment").css('display', 'none'); 
            }, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#ct_comment_status_msg' ).html('<div class="alert alert-success text-center">Class Teacher comment submitted successfully.</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                } else {
                    $('#ct_comment_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                }
            }
        });
    }); 


    //End-term Head Teacher Comment
    $('#produce_end_term_ht_comment_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + produce_ht_comment_url, 
            type: 'POST',
            data: form_data,
            beforeSend: function () { 
                $("#d_loader_ht_comment").css('display', 'inline'); 
            },
            complete: function () { 
                $("#d_loader_ht_comment").css('display', 'none'); 
            }, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#ht_comment_status_msg' ).html('<div class="alert alert-success text-center">Head Teacher comment submitted successfully.</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                } else {
                    $('#ht_comment_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                }
            }
        });
    }); 


    //End-of-Students Report
    var csrf_hash = $('#csrf_hash').val();
    var session = $('#session').val();
    var term = $('#term').val();
    var class_id = $('#class_id').val();
    var students_report_table = $('#students_report_table').DataTable({ 
        paging: true,
        pageLength :50,
        lengthChange: true, 
        searching: true,
        info: true,
        scrollX: true,
        autoWidth: false,
        ordering: false,
        stateSave: true,
        processing: false, 
        serverSide: true, 
        pagingType: "simple_numbers", 
        dom: "<'dt_len_change'l>f<'dt_buttons'B>trip", 
        order: [], //Initial no order.
        language: {
            search: "Search/filter results: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ results",
            infoFiltered: "(filtered from _MAX_ total results)",
            emptyTable: "No result to show.",
            lengthMenu: "Show _MENU_ results",
        },
        ajax: {
            url: base_url + c_controller + '/students_report_ajax/' + session + '/' + term + '/' + class_id,
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        buttons: ExportButtons
    });



});