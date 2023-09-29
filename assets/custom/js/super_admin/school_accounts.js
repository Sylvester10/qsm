jQuery(document).ready(function ($) {
 "use strict";  



    //All Schools
    var csrf_hash = $('#csrf_hash').val();
    var all_schools_table = $('#all_schools_table').DataTable({ 
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
            search: "Search/filter schools: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ schools",
            infoFiltered: "(filtered from _MAX_ total schools)",
            emptyTable: "No school to show.",
            lengthMenu: "Show _MENU_ schools",
        },
        ajax: {
            url: base_url + 'school_account/all_schools_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });
    
    
    
    //Activated Schools
    var csrf_hash = $('#csrf_hash').val();
    var activated_schools_table = $('#activated_schools_table').DataTable({ 
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
            search: "Search/filter schools: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ schools",
            infoFiltered: "(filtered from _MAX_ total schools)",
            emptyTable: "No school to show.",
            lengthMenu: "Show _MENU_ schools",
        },
        ajax: {
            url: base_url + 'school_account/activated_schools_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });
    
    
    
    //Free Trial Schools
    var csrf_hash = $('#csrf_hash').val();
    var free_trial_schools_table = $('#free_trial_schools_table').DataTable({ 
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
            search: "Search/filter schools: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ schools",
            infoFiltered: "(filtered from _MAX_ total schools)",
            emptyTable: "No school to show.",
            lengthMenu: "Show _MENU_ schools",
        },
        ajax: {
            url: base_url + 'school_account/free_trial_schools_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });
    
    
    
    //Expired Free Trial Schools
    var csrf_hash = $('#csrf_hash').val();
    var expired_free_trial_schools_table = $('#expired_free_trial_schools_table').DataTable({ 
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
            search: "Search/filter schools: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ schools",
            infoFiltered: "(filtered from _MAX_ total schools)",
            emptyTable: "No school to show.",
            lengthMenu: "Show _MENU_ schools",
        },
        ajax: {
            url: base_url + 'school_account/expired_free_trial_schools_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });



    //Expired Annual Subscription Schools
    var csrf_hash = $('#csrf_hash').val();
    var expired_annual_subscription_schools_table = $('#expired_annual_subscription_schools_table').DataTable({ 
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
            search: "Search/filter schools: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ schools",
            infoFiltered: "(filtered from _MAX_ total schools)",
            emptyTable: "No school to show.",
            lengthMenu: "Show _MENU_ schools",
        },
        ajax: {
            url: base_url + 'school_account/expired_annual_subscription_schools_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });




    //School Admins
    var csrf_hash = $('#csrf_hash').val();
    var school_id = $('#school_id').val();
    var school_admins_table = $('#school_admins_table').DataTable({ 
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
            url: base_url + 'school_users/admins_ajax/' + school_id,
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });



    //School Staff
    var csrf_hash = $('#csrf_hash').val();
    var school_id = $('#school_id').val();
    var school_staff_table = $('#school_staff_table').DataTable({ 
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
            url: base_url + 'school_users/staff_ajax/' + school_id,
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });



    //School Students
    var csrf_hash = $('#csrf_hash').val();
    var school_id = $('#school_id').val();
    var school_students_table = $('#school_students_table').DataTable({ 
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
            url: base_url + 'school_users/students_ajax/' + school_id,
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });



    //School Parents
    var csrf_hash = $('#csrf_hash').val();
    var school_id = $('#school_id').val();
    var school_parents_table = $('#school_parents_table').DataTable({ 
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
            url: base_url + 'school_users/parents_ajax/' + school_id,
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