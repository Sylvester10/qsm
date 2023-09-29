jQuery(document).ready(function ($) {
    "use strict";     
    

    
    //Check Staff Attendance
    $('#check_attendance_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        var staff_id = $('#staff_id').val();
        $.ajax ({
            type: "POST",
            url: base_url + 'school_staff/check_attendance_ajax/' + staff_id,
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
                        var session = jres.session; 
                        var term = jres.term; 
                        var date = jres.date; 
                        var status = jres.status; 
                        $( '#status_msg' ).css('display', 'none');
                        $( '#attendance_details_box' ).css('display', 'block');
                        $( '#session' ).html(session);
                        $( '#term' ).html(term);
                        $( '#date' ).html(date);
                        $( '#status' ).html(status);
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




    //Staff Role
    $('#staff_role_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        var id = $('#staff_id').val();
        $.ajax({
            url: base_url + 'school_staff/staff_role_ajax/' + id, 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center"> Role updated successfully.</div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        location.reload(); //reload page
                    }, 3000);
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });



    //Subject Teacher Assignment
    $('#subject_teacher_assignment_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        var id = $('#subject_teacher_id').val();
        $.ajax({
            url: base_url + 'school_staff/subject_teacher_assignment_ajax/' + id, 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center"> Subject teacher assignment updated successfully.</div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        location.reload(); //reload page
                    }, 3000);
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });




    //Staff
    var csrf_hash = $('#csrf_hash').val();
    var staff_table = $('#staff_table').DataTable({ 
        paging: true,
        pageLength : 10,
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
            search: "Search/filter staff: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ staff",
            infoFiltered: "(filtered from _MAX_ total staff)",
            emptyTable: "No staff to show.",
            lengthMenu: "Show _MENU_ staff",
        },
        ajax: {
            url: base_url + 'school_staff/staff_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });
    



    //Staff: Class Teachers
    var csrf_hash = $('#csrf_hash').val();
    var class_teachers_table = $('#class_teachers_table').DataTable({ 
        paging: true,
        pageLength : 10,
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
            search: "Search/filter teachers: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ teachers",
            infoFiltered: "(filtered from _MAX_ total teachers)",
            emptyTable: "No teacher to show.",
            lengthMenu: "Show _MENU_ teachers",
        },
        ajax: {
            url: base_url + 'school_staff/class_teachers_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });



    //Staff: Subject Teachers
    var csrf_hash = $('#csrf_hash').val();
    var subject_teachers_table = $('#subject_teachers_table').DataTable({ 
        paging: true,
        pageLength : 10,
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
            search: "Search/filter teachers: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ teachers",
            infoFiltered: "(filtered from _MAX_ total teachers)",
            emptyTable: "No teacher to show.",
            lengthMenu: "Show _MENU_ teachers",
        },
        ajax: {
            url: base_url + 'school_staff/subject_teachers_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });
    
    
    
    //Imported Staff
    var csrf_hash = $('#csrf_hash').val();
    var imported_staff_table = $('#imported_staff_table').DataTable({ 
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
            search: "Search/filter imported staff: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ imported staff",
            infoFiltered: "(filtered from _MAX_ total imported staff)",
            emptyTable: "No imported staff data to show.",
            lengthMenu: "Show _MENU_ imported staff",
        }, 
        ajax: {
            url: base_url + 'staff_import/imported_staff_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
    });




});