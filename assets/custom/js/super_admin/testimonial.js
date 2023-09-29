jQuery(document).ready(function ($) {
 "use strict";  



    //New Testimonial
    $('#new_testimonial_form').submit(function(e) {
        e.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url: base_url + 'testimonial/add_new_testimonial_ajax', 
            type: 'POST',
            data: form_data, 
            success: function(msg) {
                if (msg == 1) {
                    $( '#status_msg' ).html('<div class="alert alert-success text-center">Testimonial added successfully.</div>').fadeIn( 'fast' );
                    setTimeout(function() { 
                        location.reload(); //reload page
                    }, 3000);
                } else {
                    $('#status_msg').html('<div class="alert alert-danger text-center">' + msg + '</div>').fadeIn( 'fast' ).delay( 30000 ).fadeOut( 'slow' );
                }
            }
        });
    });




    //Testimonials
    var csrf_hash = $('#csrf_hash').val();
    var testimonials_table = $('#testimonials_table').DataTable({ 
        paging: true,
        pageLength : 10,
        lengthChange: true, 
        searching: true,
        info: true,
        autoWidth: false,
        ordering: true,
        stateSave: true,
        processing: false, 
        serverSide: true, 
        pagingType: "simple_numbers", 
        dom: "<'dt_len_change'l>ftrip", //lengthChange, filter, table, processing, info, pagination (in this order)
        order: [], //Initial no order.
        language: {
            search: "Search/filter testimonials: ",
            processing: "Please wait a sec...",
            info: "Showing _START_ to _END_ of _TOTAL_ testimonials",
            infoFiltered: "(filtered from _MAX_ total testimonials)",
            emptyTable: "No testimonial to show.",
            lengthMenu: "Show _MENU_ testimonials",
        },
        ajax: {
            url: base_url + 'testimonial/all_testimonials_ajax',
            dataType: "json",
            type: "POST",
            data: { 'q2r_secure' : csrf_hash } //cross site request forgery protection
        },
        columnDefs: [
            {targets: 0, orderable: false}
        ],
    });




});