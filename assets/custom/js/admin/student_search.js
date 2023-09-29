jQuery(document).ready(function ($) {
 "use strict";  


    //Search Student
    var csrf_hash_search = $('#csrf_hash_search').val();
    var search_student_table = $('#search_student_table').DataTable({ 
        paging: true,
        pageLength : 2,
        lengthChange: false, 
        searching: true,
        info: false,
        scrollX: false,
        autoWidth: false,
        ordering: false,
        stateSave: false,
        processing: false, 
        serverSide: true, 
        pagingType: "simple_numbers", 
        order: [], //Initial no order.
        language: {
            search: " ",
            searchPlaceholder: "Search student...",
        },
        ajax: {
            url: base_url + 'student_search_admin/search_student_ajax/',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash_search } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
    });


});