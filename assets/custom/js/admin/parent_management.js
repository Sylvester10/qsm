jQuery(document).ready(function ($) {
    "use strict";   


    //Search parent to associate to student
    var csrf_hash = $('#csrf_hash').val();
    var parent_id = $('#parent_id').val();
    $("#associate_student_search").keyup(function () {
        $.ajax({
            type: "POST",
            url: base_url + c_controller + '/associate_student_search_ajax', 
            data: {
                'q2r_secure' : csrf_hash,
                keyword: $("#associate_student_search").val()
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
                    $('#associate_student_search').attr("data-toggle", "dropdown");
                    $('#studentlist_dropdown').dropdown('toggle');
                }
                else if (data.length == 0) {
                    $('#associate_student_search').attr("data-toggle", "");
                }
                $.each(data, function (key, value) {
                    var student_id = value['id'];
                    var last_name = value['last_name'];
                    var first_name = value['first_name'];
                    var other_name = value['other_name'];
                    var student_fullname = last_name + ' ' + first_name + ' ' + other_name;
                    var associate_url = base_url + c_controller + '/associate_student_action/' + parent_id + '/' + student_id;
                    if (data.length >= 0) {
                        $('#studentlist_dropdown').append('<li>' + student_fullname + '<span> <a class="btn btn-success btn-xs">Associate</a></span></li>');
                        $('ul.studentlist').on('click', 'li a', function () {
                            $(location).attr('href', associate_url);
                        });
                    }
                });
            }
        });
    });
 


    //All Parents
    var csrf_hash = $('#csrf_hash').val();
    var all_parents_table = $('#all_parents_table').DataTable({ 
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
            search: "Search/filter parents: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ parents",
            infoFiltered: "(filtered from _MAX_ total parents)",
            emptyTable: "No parent to show.",
            lengthMenu: "Show _MENU_ parents",
        },
        ajax: {
            url: base_url + 'parents/all_parents_ajax',
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