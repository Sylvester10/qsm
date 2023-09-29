jQuery(document).ready(function ($) {
    "use strict";    



    //Search parent to associate to student
    var csrf_hash = $('#csrf_hash').val();
    var student_id = $('#student_id').val();
    $("#associate_parent_search").keyup(function () {
        $.ajax({
            type: "POST",
            url: base_url + c_controller + '/associate_parent_search_ajax', 
            data: {
                'q2r_secure' : csrf_hash,
                keyword: $("#associate_parent_search").val()
            },
            dataType: "json",
            beforeSend: function () { 
                $(".d_loader").css('display', 'inline'); 
            },
            complete: function () { 
                $(".d_loader").css('display', 'none'); 
            },
            success: function (data) {
                if (data.length > 0) {
                    $('#parentlist_dropdown').empty();
                    $('#associate_parent_search').attr("data-toggle", "dropdown");
                    $('#parentlist_dropdown').dropdown('toggle');
                }
                else if (data.length == 0) {
                    $('#associate_parent_search').attr("data-toggle", "");
                }
                $.each(data, function (key, value) {
                    var parent_id = value['id'];
                    var parent_name = value['name'];
                    var associate_url = base_url + c_controller + '/associate_parent_action/' + student_id + '/' + parent_id;
                    if (data.length >= 0) {
                        $('#parentlist_dropdown').append('<li>' + parent_name + '<span> <a class="btn btn-success btn-xs">Associate</a></span></li>');
                        $('ul.parentlist').on('click', 'li a', function () {
                            $(location).attr('href', associate_url);
                        });
                    }
                });
            }
        });
    });

 
    

    //Check Student Attendance
    $('#check_attendance_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        var student_id = $('#student_id').val();
        $.ajax ({
            type: "POST",
            url: base_url + 'students_admin/check_attendance_ajax/' + student_id,
            data: form_data,
            success: function (res) {
                var jres = JSON.parse(res); //json response
                var validation_status = jres.validation_status;
                var validation_errors = jres.validation_errors;
                if (validation_status == 0) {
                    $( '#attendance_details_box' ).css('display', 'none');
                    $( '#status_msg' ).html('<div class="alert alert-danger text-center">' + validation_errors + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
                } else {
                    var att_exists = jres.att_exists;
                    if (att_exists == 'true') {
                        var att_id = jres.att_id; 
                        var session = jres.session; 
                        var term = jres.term; 
                        var s_class = jres.class; 
                        var date = jres.date; 
                        var status = jres.status; 
                        $( '#status_msg' ).css('display', 'none');
                        $( '#attendance_details_box' ).css('display', 'block');
                        $( '#session' ).html(session);
                        $( '#term' ).html(term);
                        $( '#class' ).html(s_class);
                        $( '#date' ).html(date);
                        $( '#status' ).html(status);
                        //delete button
                        var delete_url = base_url + 'students_admin/delete_attendance/' + att_id;
                        $( '#delete_attendance' ).html('<a class="btn btn-danger btn-sm" href="'+delete_url+'"><i class="fa fa-trash"></i> Delete</a>');
                    } else {
                        var message = jres.message; 
                        $( '#status_msg' ).css('display', 'block');
                        $( '#attendance_details_box' ).css('display', 'none');
                        $( '#status_msg' ).html('<div class="alert alert-danger text-center">' + message + '</div>').fadeIn('fast').delay( 30000 ).fadeOut( 'slow' );
                    }
                } 
            }
        });
    });




    //All Students
    var csrf_hash = $('#csrf_hash').val();
    var all_students_table = $('#all_students_table').DataTable({ 
        paging: true,
        pageLength :30,
        lengthChange: true, 
        searching: true,
        info: true,
        scrollX: true,
        autoWidth: false,
        ordering: true,
        stateSave: true,
        processing: false, 
        serverSide: true, 
        pagingType: "simple_numbers", 
        dom: "<'dt_len_change'l>f<'dt_buttons'B>trip", 
        order: [], //Initial no order.
        language: {
            search: "Search/filter students: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ students",
            infoFiltered: "(filtered from _MAX_ total students)",
            emptyTable: "No student to show.",
            lengthMenu: "Show _MENU_ students",
        },
        ajax: {
            url: base_url + 'students_admin/all_students_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });
    
    
    
    //Suspended Students
    var csrf_hash = $('#csrf_hash').val();
    var suspended_students_table = $('#suspended_students_table').DataTable({ 
        paging: true,
        pageLength :30,
        lengthChange: true, 
        searching: true,
        info: true,
        scrollX: true,
        autoWidth: false,
        ordering: true,
        stateSave: true,
        processing: false, 
        serverSide: true, 
        pagingType: "simple_numbers", 
        dom: "<'dt_len_change'l>f<'dt_buttons'B>trip", 
        order: [], //Initial no order.
        language: {
            search: "Search/filter students: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ students",
            infoFiltered: "(filtered from _MAX_ total students)",
            emptyTable: "No student to show.",
            lengthMenu: "Show _MENU_ students",
        },
        ajax: {
            url: base_url + 'students_admin/suspended_students_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });
    
    
    
    //Revoked Students
    var csrf_hash = $('#csrf_hash').val();
    var revoked_students_table = $('#revoked_students_table').DataTable({ 
        paging: true,
        pageLength :30,
        lengthChange: true, 
        searching: true,
        info: true,
        scrollX: true,
        autoWidth: false,
        ordering: true,
        stateSave: true,
        processing: false, 
        serverSide: true, 
        pagingType: "simple_numbers", 
        dom: "<'dt_len_change'l>f<'dt_buttons'B>trip", 
        order: [], //Initial no order.
        language: {
            search: "Search/filter students: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ students",
            infoFiltered: "(filtered from _MAX_ total students)",
            emptyTable: "No student to show.",
            lengthMenu: "Show _MENU_ students",
        },
        ajax: {
            url: base_url + 'students_admin/revoked_students_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });



    //Graduated Students
    var csrf_hash = $('#csrf_hash').val();
    var graduated_students_table = $('#graduated_students_table').DataTable({ 
        paging: true,
        pageLength :30,
        lengthChange: true, 
        searching: true,
        info: true,
        scrollX: true,
        autoWidth: false,
        ordering: true,
        stateSave: true,
        processing: false, 
        serverSide: true, 
        pagingType: "simple_numbers", 
        dom: "<'dt_len_change'l>f<'dt_buttons'B>trip", 
        order: [], //Initial no order.
        language: {
            search: "Search/filter students: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ students",
            infoFiltered: "(filtered from _MAX_ total students)",
            emptyTable: "No student to show.",
            lengthMenu: "Show _MENU_ students",
        },
        ajax: {
            url: base_url + 'students_admin/graduated_students_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });
    
    
    
    //Imported Students
    var csrf_hash = $('#csrf_hash').val();
    var imported_students_table = $('#imported_students_table').DataTable({ 
        paging: true,
        pageLength :100,
        lengthChange: true, 
        searching: true,
        info: true,
        scrollX: true,
        autoWidth: false,
        ordering: true,
        stateSave: true,
        processing: false, 
        serverSide: true, 
        pagingType: "simple_numbers", 
        dom: "<'dt_len_change'l>ftrip", 
        order: [], //Initial no order.
        language: {
            search: "Search/filter imported students: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ imported students",
            infoFiltered: "(filtered from _MAX_ total imported students)",
            emptyTable: "No imported student data to show.",
            lengthMenu: "Show _MENU_ imported students",
        },
        ajax: {
            url: base_url + 'student_import/imported_students_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
    });
    
    
    
    
    //Class Students
    var the_slug = $('#the_slug').val();
    var csrf_hash = $('#csrf_hash').val();
    var single_class_table = $('#single_class_table').DataTable({ 
        paging: true,
        pageLength :30,
        lengthChange: true, 
        searching: true,
        info: true,
        scrollX: true,
        autoWidth: false,
        ordering: true,
        stateSave: true,
        processing: false, 
        serverSide: true, 
        pagingType: "simple_numbers", 
        dom: "<'dt_len_change'l>f<'dt_buttons'B>trip", 
        order: [], //Initial no order.
        language: {
            search: "Search/filter students: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ students",
            infoFiltered: "(filtered from _MAX_ total students)",
            emptyTable: "No student to show.",
            lengthMenu: "Show _MENU_ students",
        },
        ajax: {
            url: base_url + 'students_admin/single_class_ajax/' + the_slug,
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });



});