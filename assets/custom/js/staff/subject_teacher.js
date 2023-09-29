jQuery(document).ready(function ($) {
 "use strict";  

    
    //Class Students: Subject Teacher
    var csrf_hash = $('#csrf_hash').val();
    var class_id = $('#class_id').val();
    var subject_teacher_class_table = $('#subject_teacher_class_table').DataTable({ 
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
            url: base_url + 'subject_teacher/single_class_ajax/' + class_id,
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