jQuery(document).ready(function ($) {
 "use strict";
 

	//auto-close flashdata alert boxes
	$(".alert-dismissable").delay(10000).fadeOut('slow', function() {
		$(this).alert('close');
	});
	
	
	//auto-close upload error
	$(".upload_error").delay(7000).fadeOut('slow', function() {
		$(this).alert('close');
	});
	
	
	//Initialize Popover
	$('[data-toggle="popover"]').popover();
	

	//Clipboard.js instantiation
	new ClipboardJS('.copy-it');


	//select picker
	$('.selectpicker').selectpicker();
	

	//bootstrap timepicker
	$('#timepicker').timepicker({
		minuteStep: 1,
	});


	//bootstrap timepicker
	$('#timepicker2').timepicker({
		minuteStep: 1,
	});

	
	
	//bootstrap datepicker
	$('.datepicker').datepicker({
		format: 'dd/mm/yyyy',
		startDate: '01-01-1978',
	});


	//bootstrap datepicker
	$('.datepicker_regular').datepicker({
		format: 'dd/mm/yyyy',
	});
	
	
	//bootstrap datepicker for term dates
	$('.term_date_datepicker').datepicker({
		format: 'mm/dd/yyyy',
		startDate: '01-01-1978',
	});
	
	
	//bootstrap datepicker for calendar dates
	$('.calendar_date_datepicker').datepicker({
		format: 'yyyy/mm/dd',
		startDate: '01/01/1978',
	});


	//bootstrap datepicker for calendar dates
	$('.date_datepicker_no_future').datepicker({
		format: 'yyyy/mm/dd',
		startDate: '01/01/1978',
		endDate: date_today,
	});


	//jQuery input tags
	$('.jq_input_tags').tagsInput({
		width: 'auto'
	});


	//Bootstrap input tags
	/*$('.bs_input_tags').tagsinput({
		maxTags: 4,
	  	confirmKeys: [13], //13 ENTER, 188 comma
	  	trimValue: true, //trim whitespaces around tags
	  	allowDuplicates: false, //duplicate tags not allowed
	});*/


  	//Tiny MCE editor 
	tinymce.init({
		selector: '.textarea',
		theme: "modern",
		menubar: false, //hide menu bar
		statusbar: false, //hide menu bar
		/* the ffg 3 lines are necessary to prevent unwanted p tags in textarea */
		force_br_newlines : true, //use br tags for new lines
		force_p_newlines : false, //disable use of p tags for new lines 
		forced_root_block : '', // prevent wrapping of non-blocking elements in p tags
        plugins: [
             "advlist autolink link image lists charmap preview hr anchor pagebreak spellchecker",
             "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
             "save table contextmenu directionality template paste textcolor"
       ],
       toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | link image | preview fullpage", 
    }); 


	//jQuery Marquee
    $('.j_marquee').marquee({
	    //speed in milliseconds of the marquee
	    duration: 15000, //15secs
	    //gap in pixels between the tickers
	    gap: 50,
	    //time in milliseconds before the marquee will start animating
	    delayBeforeStart: 0,
	    //'left' or 'right'
	    direction: 'left',
	    //true or false - should the marquee be duplicated to show an effect of continuous flow
	    duplicated: false,
	    //pause the animation on hover
	    pauseOnHover: true,
	});
	

	//Allow only numbers in digit-only fields
	$('.phone-field').keyup(function () { 
		this.value = this.value.replace(/[^0-9\+]/g,'');
	});
	
	
	//Allow only numbers in digit-only fields
	$('.numbers-only').keyup(function () { 
		this.value = this.value.replace(/[^0-9]/g,'');
	});
	
	
	//show purpose value in items box
	$("#purpose").keyup(function() { 
		var d_text = $(this).val();
		$("#items").html(d_text); 
	});
	
	
	//convert amount to words on-the-fly
	$("#amount_digits").keyup(function() {
		if ( $(this).val() != '' ) {
			var amount_digits = $(this).val();
			var amount_words = toWords(amount_digits);
			$("#amount_words").val(amount_words); 
		} else {
			$("#amount_words").val(''); 
		}
	});

	
	//show classes form in a modal when promote is selected in bulk actions for students in a class
	$("#bulk_action_students").change(function() {        
		if ( $(this).val() === 'promote' ) {
			$('#classes_form').modal('show');
		}
	});


	//live time
	setInterval(function() {
		var c_time = new Date();
		document.getElementById("current_ime").innerHTML = c_time.toLocaleTimeString();
	}, 1000);

	
	//Image Preview
	function ImagePreview(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#image_preview').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#the_image").change(function() {
		ImagePreview(this);
		$("#image_preview_area").css("display", "block");
	});
	$("#remove_image").click(function() {
		$("#the_image").val('');
		$("#image_preview_area").css("display", "none");
	});
	
	//Image preview on update: toggle current and new image
	$("#the_image_on_update").change(function() {
		ImagePreview(this);
		$("#image_preview_area").css("display", "block");
		$("#current_image_area").css("display", "none");
		$("#change_image_text").css("display", "none");
	});
	$("#remove_image").click(function() {
		$("#the_image_on_update").val('');
		$("#image_preview_area").css("display", "none");
		$("#current_image_area").css("display", "block");
		$("#change_image_text").css("display", "block");
	});
	
	
	//Send mail to client: invoice
	$('#send_mail_inv').change(function () {
		if ($('#send_mail_inv').is(':checked')) {
			$('.email-allowed-inv').css('display', 'block');
		} else {
			$('.email-allowed-inv').css('display', 'none');
		}
	});
	
	//Send mail to client: message
	$('#send_mail_msg').change(function () {
		if ($('#send_mail_msg').is(':checked')) {
			$('.email-allowed-msg').css('display', 'block');
		} else {
			$('.email-allowed-msg').css('display', 'none');
		}
	});
	
	
	//Profile edit: password change check
	$('#change_password').change(function () {
		if ( $(this).is(':checked') ) {
			$('#password_area').css('display', 'block');
			$('#password').attr('required', 'required');
			$('#c_password').attr('required', 'required');
		} else {
			$('#password_area').css('display', 'none');
			$('#password').removeAttr('required');
			$('#password').val('');
			$('#c_password').removeAttr('required');
			$('#c_password').val('');
		}
	});
	
	
	
	//Search student 
	$('#search_btn').click(function () {
		$(this).css('display', 'none');
		$('#collapse_btn').css('display', 'block');
		$('#the_search_area').css('display', 'block');
	});
	$('#collapse_btn').click(function () {
		$(this).css('display', 'none');
		$('#search_btn').css('display', 'block');
		$('#the_search_area').css('display', 'none');
	});



	//Restricted Acess More Info
	$('#more_info').click(function () {
		$(this).css('display', 'none');
		$('#less_info').css('display', 'block');
		$('#more_info_area').css('display', 'block');
	});
	$('#less_info').click(function () {
		$(this).css('display', 'none');
		$('#more_info').css('display', 'block');
		$('#more_info_area').css('display', 'none');
	});


	

	//bulk action: disable action button if no bulk action type is selected
	$('.bulk_action_type').change(function () {
		if ( $('.no_item').is(':selected') ) {
			$('.bulk_action_btn').attr('disabled', 'disabled');
		} else {
			$('.bulk_action_btn').removeAttr('disabled');	
		}
	});
	
	//bulk action: select all checkbox items if select all is checked
	$('.select_all').change(function(){  //"select all" change 
		$('.bulk_select_box').prop('checked', $(this).prop('checked'));//change all ".checkbox" checked status
	});
	
	//".checkbox" change 
	$('.bulk_select_box').change(function(){ 
		//uncheck "select all", if one of the listed checkbox item is unchecked
		if(false == $(this).prop('checked')){ //if this item is unchecked
			$('.select_all').prop('checked', false); //change "select all" checked status to false
		}
		//check "select all" if all checkbox items are checked
		if ($('.bulk_select_box:checked').length == $('.checkbox').length ){
			$('.select_all').prop('checked', true);
		}
	});



	//Items: 0
    $('#select_all_items_0').change(function() {
        $('.item_0_checkbox').prop('checked', true).change();
    });
	//Items: 1
    $('#select_all_items_1').change(function() {
        $('.item_1_checkbox').prop('checked', true).change();
    });
    //Items: 2
    $('#select_all_items_2').change(function() {
        $('.item_2_checkbox').prop('checked', true).change();
    });
    //Items: 3
    $('#select_all_items_3').change(function() {
        $('.item_3_checkbox').prop('checked', true).change();
    });
    //Items: 4
    $('#select_all_items_4').change(function() {
        $('.item_4_checkbox').prop('checked', true).change();
    });
    //Items: 5
    $('#select_all_items_5').change(function() {
        $('.item_5_checkbox').prop('checked', true).change();
    });
    //Deselect all
    $('#deselect_all_items').change(function() {
        $('.item_0_checkbox').prop('checked', false).change();
        $('.item_1_checkbox').prop('checked', false).change();
        $('.item_2_checkbox').prop('checked', false).change();
        $('.item_3_checkbox').prop('checked', false).change();
        $('.item_4_checkbox').prop('checked', false).change();
        $('.item_5_checkbox').prop('checked', false).change();
    });



	//Attendance
    $('#select_all_present').click(function() {
  		$('.bulk_select_present').prop('checked', true).change();
  		$('.bulk_select_absent').prop('checked', false).change();
    });
    $('#select_all_absent').click(function() {
  		$('.bulk_select_present').prop('checked', false).change();
  		$('.bulk_select_absent').prop('checked', true).change();
    });
    $('#deselect_all').click(function() {
  		$('.bulk_select_present').prop('checked', false).change();
  		$('.bulk_select_absent').prop('checked', false).change();
    });

    
	
	
	//Check if nationality is Nigeria and display state and LGA select box, else display input box
	//On load
	var selected_country = $('#nationality').val();
	if ( selected_country == 'Nigeria' ) {
		$('#nationality_nigeria').css('display', 'block');
		$('#nationality_other').css('display', 'none');
		$('#state').attr('required', 'required');
		$('#lga').attr('required', 'required');
		$('#state_other').removeAttr('required');
		$('#lga_other').removeAttr('required');
		$('#state_other').val(''); //clear other state field 
		$('#lga_other').val(''); //clear other lga field 
	} else {
		$('#nationality_nigeria').css('display', 'none');
		$('#nationality_other').css('display', 'block');
		$('#state_other').attr('required', 'required');
		$('#lga_other').attr('required', 'required');
		$('#state').removeAttr('required');
		$('#lga').removeAttr('required');
		$('#state').val(''); //clear nigeria state field 
		$('#lga').val(''); //clear nigeria lga field 
	}
	
	
	$('#nationality').change(function () {
		//On change of country
		var selected_country = $(this).val();
		if ( selected_country == 'Nigeria' ) {
			$('#nationality_nigeria').css('display', 'block');
			$('#nationality_other').css('display', 'none');
			$('#state').attr('required', 'required');
			$('#lga').attr('required', 'required');
			$('#state_other').removeAttr('required');
			$('#lga_other').removeAttr('required');
			$('#state_other').val(''); //clear other state field 
			$('#lga_other').val(''); //clear other lga field 
		} else {
			$('#nationality_nigeria').css('display', 'none');
			$('#nationality_other').css('display', 'block');
			$('#state_other').attr('required', 'required');
			$('#lga_other').attr('required', 'required');
			$('#state').removeAttr('required');
			$('#lga').removeAttr('required');
			$('#state').val(''); //clear nigeria state field 
			$('#lga').val(''); //clear nigeria lga field 
		}
	});




	//Parent Relationship Specify
	$('#parent_relationship').change(function() {
		if ( $('#parent_relationship_other_option').is(':selected') ) {
			$('#parent_relationship_other_area').css("display", "block");
		} else {
			$('#parent_relationship_other_area').css("display","none");
		}
	});

	//Second Parent Relationship Specify
	$('#sec_parent_relationship').change(function() {
		if ( $('#sec_parent_relationship_other_option').is(':selected') ) {
			$('#sec_parent_relationship_other_area').css("display", "block");
		} else {
			$('#sec_parent_relationship_other_area').css("display","none")
		}
	});



	//Non-server side datatables
	var table = $('#table').DataTable({ 
		"paging": true,
		"pageLength" : 25,
		"lengthChange": true, 
		"searching": true,
		"info": true,
		"scrollX": true,
		"autoWidth": false,
		"ordering": true,
		"stateSave": true,
		"processing": false, 
		"pagingType": "simple_numbers", 
		"language": {
			"search": "Search/filter: ",
			"emptyTable": "Nothing to show.",
		}
	});
	
	
	//Non-server side datatables
	var table2 = $('#table2').DataTable({ 
		"paging": true,
		"pageLength" : 25,
		"lengthChange": true, 
		"searching": true,
		"info": true,
		"scrollX": true,
		"autoWidth": false,
		"ordering": true,
		"stateSave": true,
		"processing": false, 
		"pagingType": "simple_numbers", 
		"language": {
			"search": "Search/filter: ",
			"emptyTable": "Nothing to show.",
		}
	});



	//Non-server side datatables for attendance
	var student_attendance_table = $('#student_attendance_table').DataTable({ 
		"paging": true,
		"pageLength" : 100, 
		"lengthChange": false, 
		"searching": true,
		"info": false,
		"scrollX": true,
		"autoWidth": false,
		"ordering": false,
		"stateSave": true,
		"processing": false, 
		"pagingType": "simple_numbers", 
		"language": {
			"search": "Search student: ",
			"emptyTable": "No student to show.",
		}
	});


	//Non-server side datatables for attendance
	var staff_attendance_table = $('#staff_attendance_table').DataTable({ 
		"paging": true,
		"pageLength" : 100, 
		"lengthChange": false, 
		"searching": true,
		"info": false,
		"scrollX": true,
		"autoWidth": false,
		"ordering": false,
		"stateSave": true,
		"processing": false, 
		"pagingType": "simple_numbers", 
		"language": {
			"search": "Search staff: ",
			"emptyTable": "No staff to show.",
		}
	});



	//Non-server side datatables for attendance details
	var att_details_table = $('#att_details_table').DataTable({ 
		"paging": true,
		"pageLength" : 25,
		"lengthChange": false, 
		"searching": true,
		"info": false,
		"scrollX": true,
		"autoWidth": false,
		"ordering": true,
		"stateSave": true,
		"processing": false, 
		"pagingType": "simple_numbers", 
		"language": {
			"search": "Search: ",
			"emptyTable": "No attendance data to show.",
		},
		"columnDefs": [
			{targets: [3], orderable: false}
		],
	});



	//Non-server side datatable for class students (produce report)
	var students_new_report_table = $('#students_new_report_table').DataTable({ 
		"paging": true,
		"pageLength" : 30,
		"lengthChange": false, 
		"searching": true,
		"info": true,
		"scrollX": true,
		"autoWidth": false,
		"ordering": false,
		"stateSave": true,
		"processing": false, 
		"pagingType": "simple_numbers", 
		"language": {
			"search": "Search student: ",
			"emptyTable": "No student to show.",
		}
	});
	


	
	
	
}); //jQuery(document).ready(function)