jQuery(document).ready(function ($) {
 "use strict";  


    //Add New Admin
    $('#new_admin_form').submit(function(e) {
        e.preventDefault();
        var form_data = $('#new_admin_form').serialize();
        $.ajax({
            url: base_url + 'admin_users/add_new_admin_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $('#new_admin_form')[0].reset(); //reset form fields
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">New admin added successfully.</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });
    
    
    
    //Edit Admin
    $('#edit_admin_form').submit(function(e) {
        e.preventDefault();
        var form_data = $('#edit_admin_form').serialize();
        var id = $('#admin_id').val();
        var name = $('#admin_name').val();
        var redirect_url = base_url + 'admin_users';
        $.ajax({
            url: base_url + 'admin_users/edit_admin_ajax/' + id, 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">' + name + ' updated successfully.</div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        $(location).attr('href', redirect_url);
                    }, 3000);
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });





    //Admins
    var csrf_hash = $('#csrf_hash').val();
    var admins_table = $('#admins_table').DataTable({ 
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
            url: base_url + 'admin_users/admins_ajax',
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