    
jQuery(document).ready(function ($) {
    "use strict";


    //New Fee Category
    $('#new_fee_category_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + c_controller + '/new_fee_category_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Fee category added successfully.</div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        location.reload(); //reload page
                    }, 3000);
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });



    //New Fee Discount Category
    $('#new_fee_discount_category_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + c_controller + '/new_fee_discount_category_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Discount category added successfully.</div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        location.reload(); //reload page
                    }, 3000);
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });



    //get student class
    function get_student_class(student_id) {
        var csrf_hash = $('#csrf_hash').val();
        $.ajax ({
            type: "POST",
            url: base_url + c_controller + '/get_student_class_ajax/' + student_id,
            data: {student_id, 'q2r_secure' : csrf_hash},
            success: function (res) {
                var jres = JSON.parse(res); //json response
                var found = jres.found;
                if (found == 'true') {
                    var student_class = jres.student_class;
                    //console.log(student_class);
                    return student_class;
                } else {
                    return '';
                }
            }
        });
    }



    //Search student for fee discount apply
    var csrf_hash = $('#csrf_hash').val();
    var discount_cat_id = $('#discount_cat_id').val();
    $("#fee_discount_student_apply").keyup(function () {
        $.ajax({
            type: "POST",
            url: base_url + c_controller + '/search_students_for_fee_discount_ajax', 
            data: {
                'q2r_secure' : csrf_hash,
                keyword: $("#fee_discount_student_apply").val()
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
                    $('#studentlist_dropdown').empty();
                    $('#fee_discount_student_apply').attr("data-toggle", "dropdown");
                    $('#studentlist_dropdown').dropdown('toggle');
                }
                else if (data.length == 0) {
                    $('#fee_discount_student_apply').attr("data-toggle", "");
                }
                $.each(data, function (key, value) {
                    var student_id = value['id'];
                    var last_name = value['last_name'];
                    var first_name = value['first_name'];
                    var other_name = value['other_name'];
                    var student_fullname = last_name + ' ' + first_name + ' ' + other_name;
                    //var student_class = get_student_class(student_id);
                    var apply_url = base_url + c_controller + '/apply_fee_discount_to_student/' + discount_cat_id + '/' + student_id;
                    if (data.length >= 0) {
                        $('#studentlist_dropdown').append('<li>' + student_fullname + '<span> <a class="btn btn-success btn-xs">Apply</a></span></li>');
                        $('ul.studentlist').on('click', 'li a', function () {
                            $(location).attr('href', apply_url);
                        });
                    }
                });
            }
        });
    });


    
    
    //New class fees
    $('#new_class_fees_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + c_controller + '/new_class_fees_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Fee details added successfully.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });



     //Edit class fees
    $('#edit_class_fees_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        var fee_id = $('#fee_id').val();
        $.ajax({
            url: base_url + c_controller + '/edit_class_fees_ajax/' + fee_id, 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Fee details added successfully.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });



    //sum up fee amount on the fly
    $('.fee_amount').keyup(function() {
        var fees_total = 0;
        $(".fee_amount").each(function(){
            fees_total += +$(this).val();
        });
        if (fees_total > 0) {
            var total_fees_payable = fees_total.toLocaleString(); //format number
            $("#total_fees_payable").val(total_fees_payable);
        }
    });

    //sum up fee amount on page load (for fee edit)
    var fees_total = 0;
    $(".fee_amount").each(function(){
        fees_total += +$(this).val();
    });
    if (fees_total > 0) {
        var total_fees_payable = fees_total.toLocaleString(); //format number
        $("#total_fees_payable").val(total_fees_payable);
    }




    //Import Fees
    $('#import_fees_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + c_controller + '/import_fees_ajax', 
            type: 'POST',
            data: form_data, 
            beforeSend: function () { 
                $(".d_loader").css('display', 'inline'); 
            },
            complete: function () { 
                $(".d_loader").css('display', 'none'); 
            },     
            success: function(msg) {
                if (msg == 1) {
                    $( '#import_status_msg' ).html('<div class="alert alert-success text-center">Fees imported successfully.</div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        $("#import_fees").modal("hide");
                        location.reload(); //reload page
                    }, 3000);
                } else {
                    $('#import_status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });




    //Collect Fees
    var class_id = $('#class_id').val();
    var csrf_hash = $('#csrf_hash').val();
    var collect_fees_table = $('#collect_fees_table').DataTable({ 
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
            url: base_url + c_controller + '/collect_fees_ajax/' + class_id,
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });
    
    
    
    //Fee: Full Payment
    var class_id = $('#class_id').val();
    var session = $('#session').val();
    var term = $('#term').val();
    var csrf_hash = $('#csrf_hash').val();
    var full_fees_table = $('#full_fees_table').DataTable({ 
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
            url: base_url + c_controller + '/full_payment_ajax/' + session + '/' + term + '/' + class_id,
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });
    
    
    
    //Fee: Partial Payment
    var class_id = $('#class_id').val();
    var session = $('#session').val();
    var term = $('#term').val();var csrf_hash = $('#csrf_hash').val();
    var partial_fees_table = $('#partial_fees_table').DataTable({ 
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
            url: base_url + c_controller + '/partial_payment_ajax/' + session + '/' + term + '/' + class_id,
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });
    
    
    
    //Fee: No Payment
    var class_id = $('#class_id').val();
    var session = $('#session').val();
    var term = $('#term').val();var csrf_hash = $('#csrf_hash').val();
    var no_fees_table = $('#no_fees_table').DataTable({ 
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
            url: base_url + c_controller + '/no_payment_ajax/' + session + '/' + term + '/' + class_id,
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
    