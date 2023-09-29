jQuery(document).ready(function ($) {
 "use strict";



    //Cron Jobs: DB
    var cron_name = $('#cron_name').val();
    var csrf_hash = $('#csrf_hash').val();
    var cron_jobs_db_table = $('#cron_jobs_db_table').DataTable({ 
        paging: true,
        pageLength : 100,
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
            search: "Search/filter cron jobs: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ cron jobs",
            infoFiltered: "(filtered from _MAX_ total cron jobs)",
            emptyTable: "No cron job to show.",
            lengthMenu: "Show _MENU_ cron jobs",
        },
        ajax: {
            url: base_url + 'cron_jobs/cron_jobs_db_ajax/' + cron_name,
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });
    



    //Cron Jobs: School Data
    var csrf_hash = $('#csrf_hash').val();
    var cron_name = $('#cron_name').val();
    var cron_jobs_school_data_table = $('#cron_jobs_school_data_table').DataTable({ 
        paging: true,
        pageLength : 100,
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
            search: "Search/filter cron jobs: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ cron jobs",
            infoFiltered: "(filtered from _MAX_ total cron jobs)",
            emptyTable: "No cron job to show.",
            lengthMenu: "Show _MENU_ cron jobs",
        },
        ajax: {
            url: base_url + 'cron_jobs/cron_jobs_school_data_ajax/' + cron_name,
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: [0, 1], orderable: false}
        ],
        buttons: ExportButtons
    });
    
    

}); //jQuery(document).ready(function)