jQuery(document).ready(function ($) {
 "use strict";  


    //Demo Admins
    var csrf_hash = $('#csrf_hash').val();
    var demo_admins_table = $('#demo_admins_table').DataTable({ 
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
            search: "Search/filter admins: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ admins",
            infoFiltered: "(filtered from _MAX_ total admins)",
            emptyTable: "No admin to show.",
            lengthMenu: "Show _MENU_ admins",
        },
        ajax: {
            url: base_url + 'demo_accounts/admins_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });




    //Demo Staff
    var csrf_hash = $('#csrf_hash').val();
    var demo_staff_table = $('#demo_staff_table').DataTable({ 
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
            url: base_url + 'demo_accounts/staff_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });




    //Demo Students
    var csrf_hash = $('#csrf_hash').val();
    var demo_students_table = $('#demo_students_table').DataTable({ 
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
            search: "Search/filter students: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ students",
            infoFiltered: "(filtered from _MAX_ total students)",
            emptyTable: "No student to show.",
            lengthMenu: "Show _MENU_ students",
        },
        ajax: {
            url: base_url + 'demo_accounts/students_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });





    //Demo Parents
    var csrf_hash = $('#csrf_hash').val();
    var demo_parents_table = $('#demo_parents_table').DataTable({ 
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
            search: "Search/filter parents: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ parents",
            infoFiltered: "(filtered from _MAX_ total parents)",
            emptyTable: "No parent to show.",
            lengthMenu: "Show _MENU_ parents",
        },
        ajax: {
            url: base_url + 'demo_accounts/parents_ajax',
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