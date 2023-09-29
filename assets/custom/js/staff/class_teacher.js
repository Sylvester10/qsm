jQuery(document).ready(function ($) {
    "use strict";  

    
    //Check Attendance
    $('#check_attendance_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        var student_id = $('#student_id').val();
        $.ajax ({
            type: "POST",
            url: base_url + 'class_teacher/check_attendance_ajax/' + student_id,
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
                        var delete_url = base_url + 'class_teacher/delete_attendance/' + att_id;
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



    //Class Students: Class Teacher
    var csrf_hash = $('#csrf_hash').val();
    var single_class_table = $('#single_class_table').DataTable({ 
        paging: true,
        pageLength :50,
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
            url: base_url + 'class_teacher/single_class_ajax',
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