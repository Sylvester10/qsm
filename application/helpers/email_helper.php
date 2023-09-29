<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	
	/* ======== Email Buttons =========== */
	
	function email_call2action_red($url, $caption) {
		return '<table>
					<tr>
						<td style="background-color: #cc0821; border-color: #cc0821; border: 2px solid #cc0821; padding: 10px; text-align: center;">
							<a style="display: block; color: #ffffff; font-size: 17px; text-decoration: none; font-size: 18px;" href="' .$url. '">'
								.$caption. ' &raquo;
							</a>
						</td>
					</tr>
				</table>';
	}
	
	
	function email_call2action_blue($url, $caption) {
		return '<table>
					<tr>
						<td style="background-color: #0e67bf; border-color: #0e67bf; border: 2px solid #0e67bf; padding: 10px; text-align: center;">
							<a style="display: block; color: #ffffff; font-size: 17px; text-decoration: none; text-transform: capitalize;" href="' .$url. '">'
								.$caption.
							'</a>
						</td>
					</tr>
				</table>';
	}
	
	
	
	
	
	/* ======== Email Headers =========== */
	
	function email_header_default($subject) {
		return 	'<center>
					<a href="' . software_website . '">
						<img src="' . software_logo .'">
					</a>
					<h2><a href="' . software_website . '" style="text-decoration: none; color: #000">' . $subject . '</a></h2>
				</center>';
	}
	
	
	function email_header($subject) {
		return 	'<center>
					<a href="' . school_website . '">
						<img src="' . school_logo .'">
					</a>
					<h2><a href="' . school_website . '" style="text-decoration: none; color: #000">' . $subject . '</a></h2>
				</center>';
	}
	
	
	
	
	/* ======== Email Footers =========== */
	
	function email_footer_default() {
		return 	'<br /><br /><hr style="color: #f2f2f2"> 
				<center>'
					. software_name . '. Powered by <a href="' . software_vendor_site . '">' . software_vendor . '</a>
				</center>';
	}
	
	
	function email_footer() {
		return 	'<br /><br /><hr style="color: #f2f2f2"> 
				<center>
					<a href="' . software_website . '">' . software_name . '</a>. 
					Powered by <a href="' . software_vendor_site . '">' . software_vendor . '</a>
				</center>';
	}

   
	
	
	
	/* ======== Email Messages =========== */
	
	function email_user_default($email, $subject, $message, $attachment = NULL) {
		$CI =& get_instance(); //get instance of code igniter super object
		$from_email = software_web_mail;
		$sender_name = software_name;
		//[if mso] is hack for Microsoft Outlook, which does not support css margin and max-width properties
		$x_message = 	'<!--[if mso]>
							<div style="text-align: center">
								<table><tr><td width="650">
						<![endif]-->
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: auto; max-width: 650px; border: 2px solid #f2f2f2; padding: 15px 50px;">
							<tr>
								<td>' . email_header_default($subject) . $message . email_footer_default() . '</td>
							</tr>
						</table>
						<!--[if mso]>
								</td></tr></table>
							</div>
						<![endif]-->'; 
						
		$CI->email->from($from_email, $sender_name); 
		$CI->email->to($email);
		$CI->email->attach($attachment);
		$CI->email->subject($subject); 
		$CI->email->message($x_message);
		return @$CI->email->send();
	}	 


	function email_multiple_default($list, $subject, $message, $attachment = NULL) {
		$CI =& get_instance(); //get instance code igniter super object
		$from_email = software_web_mail;
		$sender_name = software_name;

		$email = "";
		foreach ($list as $y) {
			$email .= $y->email. ','; //covert emails to string and separate with comma
		}
		$to_email = substr(trim($email), 0, -1); //remove last comma
		$to_email = explode(",", $to_email); //break the emails into array of individual email addresses
		
		//[if mso] is hack for Microsoft Outlook, which does not support css margin and max-width properties
		$x_message = 	'<!--[if mso]>
							<div style="text-align: center">
								<table><tr><td width="650">
						<![endif]-->
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: auto; max-width: 650px; border: 2px solid #f2f2f2; padding: 15px 50px;">
							<tr>
								<td>' . email_header_default($subject) . $message. email_footer_default() . '</td>
							</tr> 
						</table>
						<!--[if mso]>
								</td></tr></table> 
							</div>
						<![endif]-->'; 
						
		$CI->email->clear(TRUE);
		$CI->email->from($from_email, $sender_name); 
		$CI->email->bcc($to_email);
		$CI->email->attach($attachment);
		$CI->email->subject($subject); 
		$CI->email->message($x_message);
		return @$CI->email->send();
	}
	
	
	function email_user($email, $subject, $message, $attachment = NULL) {
		$CI =& get_instance(); //get instance of code igniter super object
		$from_email = software_web_mail;
		$sender_name = school_name;
		//[if mso] is hack for Microsoft Outlook, which does not support css margin and max-width properties
		$x_message = 	'<!--[if mso]>
							<div style="text-align: center">
								<table><tr><td width="650">
						<![endif]-->
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: auto; max-width: 650px; border: 2px solid #f2f2f2; padding: 15px 50px;">
							<tr>
								<td>' . email_header($subject) . $message. email_footer() . '</td>
							</tr>
						</table>
						<!--[if mso]>
								</td></tr></table>
							</div>
						<![endif]-->'; 
		$CI->email->from($from_email, $sender_name); 
		$CI->email->to($email);
		$CI->email->attach($attachment);
		$CI->email->subject($subject); 
		$CI->email->message($x_message);
		return @$CI->email->send();
	}	 
	
	
	function email_multiple($list, $subject, $message, $attachment = NULL) {
		$CI =& get_instance(); //get instance code igniter super object
		$from_email = software_web_mail;
		$sender_name = school_name;

		$email = "";
		foreach ($list as $y) {
			$email .= $y->email. ','; //covert emails to string and separate with comma
		}
		$to_email = substr(trim($email), 0, -1); //remove last comma
		$to_email = explode(",", $to_email); //break the emails into array of individual email addresses
		
		//[if mso] is hack for Microsoft Outlook, which does not support css margin and max-width properties
		$x_message = 	'<!--[if mso]>
							<div style="text-align: center">
								<table><tr><td width="650">
						<![endif]-->
						<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: auto; max-width: 650px; border: 2px solid #f2f2f2; padding: 15px 50px;">
							<tr>
								<td>' . email_header($subject) . $message. email_footer() . '</td>
							</tr> 
						</table>
						<!--[if mso]>
								</td></tr></table>   
							</div>
						<![endif]-->'; 
						
		$CI->email->clear(TRUE);
		$CI->email->from($from_email, $sender_name); 
		$CI->email->bcc($to_email);
		$CI->email->attach($attachment);
		$CI->email->subject($subject); 
		$CI->email->message($x_message);
		return @$CI->email->send();
	}


	function modal_message_user($user_id, $user_name, $form_url) {
		$form = form_open($form_url.'/'.$user_id);
		$form .=  	'<div>
						<textarea class="t200 w-100 m-b-20" name="message" placeholder="Your message" required></textarea>
					</div>';
		$form .= 	'<div>
						<button class="btn btn-primary"> <i class="fa fa-send"></i> Send Message</button>
					</div>';
		$form .= form_close();

		return '<div class="modal fade" id="message'.$user_id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Message: '.$user_name.'</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								. $form .
							'</div>
						</div>
					</div>
				</div>';
	}
