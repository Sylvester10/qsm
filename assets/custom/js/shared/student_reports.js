jQuery(document).ready(function ($) {
    "use strict";  


    //MID TERM REPORTS

    //ensure value entered in field does not exceed set maximum
    //Classwork
    $('.classwork_field').keyup(function() {
        $(this).each(function() {
            var value = $(this).val();
            if (value > mt_classwork) {
                $(this).val('');
            } 
        });  
    });
    //Homework
    $('.homework_field').keyup(function() {
        $(this).each(function() {
            var value = $(this).val();
            if (value > mt_homework) {
                $(this).val('');
            } 
        });  
    });
    //Tests
    $('.tests_field').keyup(function() {
        $(this).each(function() {
            var value = $(this).val();
            if (value > mt_tests) {
                $(this).val('');
            } 
        });  
    });


    //Mid-term Target Grades
    $('#produce_mid_term_target_grade_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + produce_target_grade_url, 
            type: 'POST',
            data: form_data, 
            beforeSend: function () { 
                $("#d_loader_target_grade").css('display', 'inline'); 
            },
            complete: function () { 
                $("#d_loader_target_grade").css('display', 'none'); 
            },
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Target grades submitted successfully.</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                    setTimeout(function() { 
                        $( '#extra_msg' ).html('<div class="alert alert-success text-center">Reload this page to delete scores for newly produced subject grades.</div>').fadeIn( 'slow' ).delay( 15000 ).fadeOut( 'slow' );
                    }, 3000);
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                }
            }
        });  
    });  


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


    //ensure value entered in field does not exceed set maximum
    //CA
    $('.ca_field').keyup(function() {
        $(this).each(function() {
            var value = $(this).val();
            if (value > ca_max_score) {
                $(this).val('');
            } 
        });  
    });
    //Exam
    $('.exam_field').keyup(function() {
        $(this).each(function() {
            var value = $(this).val();
            if (value > exam_max_score) {
                $(this).val('');
            } 
        });  
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
    


    //End-of-term Target Grades
    $('#produce_end_term_target_grade_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + produce_target_grade_url, 
            type: 'POST',
            data: form_data, 
            beforeSend: function () { 
                $("#d_loader_target_grade").css('display', 'inline'); 
            },
            complete: function () { 
                $("#d_loader_target_grade").css('display', 'none'); 
            }, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Target grades submitted successfully.</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                    setTimeout(function() { 
                        $( '#extra_msg' ).html('<div class="alert alert-success text-center">Reload this page to delete scores for newly produced subject grades.</div>').fadeIn( 'slow' ).delay( 15000 ).fadeOut( 'slow' );
                    }, 3000);
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                }
            }
        });  
    });  



    //End-of-term Reports
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
                        $( '#extra_msg' ).html('<div class="alert alert-success text-center">Reload this page to delete scores for newly produced subject scores or to add class/head teacher comment (if allowed).</div>').fadeIn( 'slow' ).delay( 15000 ).fadeOut( 'slow' );
                    }, 3000);
                } else {
                    $('#achievement_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                }
            }
        });
    });


    //End-of-term Aptitudes
    $('#produce_end_term_aptitude_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + produce_aptitude_url, 
            type: 'POST',
            data: form_data, 
            beforeSend: function () { 
                $("#d_loader_aptitude").css('display', 'inline'); 
            },
            complete: function () { 
                $("#d_loader_aptitude").css('display', 'none'); 
            }, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#aptitude_status_msg' ).html('<div class="alert alert-success text-center">Aptitude scores submitted successfully.</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
                    setTimeout(function() { 
                        $( '#aptitude_extra_msg' ).html('<div class="alert alert-success text-center">Reload this page to delete scores for newly produced aptitude scores.</div>').fadeIn( 'slow' ).delay( 15000 ).fadeOut( 'slow' );
                    }, 3000);
                } else {
                    $('#aptitude_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 10000 ).fadeOut( 'slow' );
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


    //End-of-term Class Teacher Comment
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


    //End-of-term Head Teacher Comment
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


    

    //End-of-Term Reports
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



    //Report Broadsheet
    var report_broadsheet_table = $('#report_broadsheet_table').DataTable({ 
        paging: true,
        pageLength :300,
        lengthChange: true, 
        searching: true,
        info: true,
        scrollX: false,
        autoWidth: false,
        ordering: true,
        stateSave: true,
        processing: false, 
        pagingType: "simple_numbers", 
        dom: "<'dt_len_change'l>f<'dt_buttons'B>trip", 
        "language": {
            "search": "Search/filter results: ",
            "emptyTable": "No result to show.",
        },
        buttons: ExportButtons
    });



});