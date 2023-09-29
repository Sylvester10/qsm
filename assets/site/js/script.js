jQuery(document).ready(function ($) {
 "use strict";
	
	//auto-close flashdata alert boxes
	$(".alert-dismissable").delay(30000).fadeOut('slow', function() {
		$(this).alert('close');
	});
	
	//Allow only numbers in digit-only fields
	$('.phone-num').keyup(function () { 
		this.value = this.value.replace(/[^0-9\+]/g,'');
	});
	
	//Allow only numbers in captcha fields
	$('#captcha').keyup(function () { 
		this.value = this.value.replace(/[^0-9]/g, '');
	});


}); 