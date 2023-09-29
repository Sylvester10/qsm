<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	
	function cron_message_footer() {
		return '<br />
			<b style="font-size: 18px">Want to learn more?</b> <br />
			<a href="https://quickschoolmanager.wixanswers.com/en/getting-started">Checkout our ' . software_initials . ' guides</a> which will help you get up and running quickly. Our guides show you how to setup your school and do things like create classes, upload students and assign teachers to classes.
			
			<br /><br />
			
			<b style="font-size: 18px">Need any help?</b> <br />
			We understand that getting up to speed with a new software can sometimes be a challenge. We are here to help you. E-mail us on <a href="mailto:' . software_support_mail . '">' . software_support_mail . '</a> or chat with us live on <a href="' . base_url() . '">' . base_url() . '</a>.
			<h3> ' . software_team . '</h3>';
	}
	
	
	function cron_welcome_message($name, $school_name, $confirm_status, $plan, $email, $login_url, $additional_message) { 
		return
			'Hi ' . $name . ', 
			<br />
			We are extremely excited to have you onboard. Here are your account details:
			
			<div style="border: 1px solid rgb(46, 117, 182); padding: 15px">	
				School: <b>' . $school_name . '</b> <br />
				Account Status: ' . $confirm_status . '<br /> 
				Plan: ' . $plan . '<br />
				Login Email: ' . $email . '<br />
				Login URL: ' . $login_url . '	
			</div>
					  
			<br />'
			. $additional_message . 
			'<br /><br />' .
			
			cron_message_footer();	
	}
	
	
	function cron_confirm_email_message($name, $anchor_link) {
		return 
			'Hi ' . $name . ', 
			<h3>We\'d love to see you confirm your e-mail!</h3>
			We noticed that since you signed up on ' . software_name . ', you have not confirmed your e-mail address. Confirming your e-mail is the only way of ensuring that you get to enjoy the many benefits of ' . software_name . '.
			Please click on the button below to confirm your e-mail.
			
			<p>' . $anchor_link . '</p>
			
			We look forward to hearing from you.
			<h3> ' . software_team . '</h3>';		
	}
	
	
	function cron_get_started_message($name, $dash_link, $anchor_link) { 
		return
			'Hi ' . $name . ', 
			<br />
			We\'re here to teach you how to use ' . software_name . ' with quick, simple tips—starting with these 3 things to try out now: 
			
			<p>
				<center>
					<a href="' . $dash_link . '">
						<img src="' . qsm_admin_dash . '" style="width: 250px; height: auto" />
					</a>
				</center>
			</p>
			
			<p>					
				<b style="font-size: 18px">Configure Term Settings</b> <br />
				Specifying details about the current school term is the starting point for setting up ' . software_initials . '. 
				<a href="https://quickschoolmanager.wixanswers.com/en/article/configuring-term-settings">Click here to find out how</a>
			</p>
			 
			<p>					
				<b style="font-size: 18px">Adding Sections</b> <br />
				With Sections, you can categorise your school into Nursery, Primary, Junior Secondary etc.
				<a href="https://quickschoolmanager.wixanswers.com/en/article/adding-sections">Click here to find out how</a>
			</p>
			
			<p>					
				<b style="font-size: 18px">Adding Classes</b> <br />
				Add your classes and assign class teachers.  
				<a href="https://quickschoolmanager.wixanswers.com/en/article/add-a-new-class">Click here to find out how</a>
			</p>
			
			<p>					
				<b style="font-size: 18px">Adding Subjects</b> <br />
				Subjects can be assigned to sections. 
				<a href="https://quickschoolmanager.wixanswers.com/en/article/adding-subjects">Click here to find out how</a>
			</p>
			
			<p>' . $anchor_link . '</p>
			
			<b style="font-size: 18px">Need any help?</b> <br />
			We understand that getting up to speed with a new software can sometimes be a challenge. We are here to help you. E-mail us on <a href="mailto:' . software_support_mail . '">' . software_support_mail . '</a> or chat with us live on <a href="' . base_url() . '">' . base_url() . '</a>.
			<h3> ' . software_team . '</h3>';
	}
	
	
	function cron_free_trial_no_activity_message($name, $school_name, $expiration_date, $email, $login_url, $anchor_link) { 
		return
			'Hi ' . $name . ', 
			<br />
			<h3>We miss you!</h3>
			
			We notice that you’ve hardly used your ' . software_name . ' FREE Trial since you set it up. Your 30 day FREE Trial expires on ' . $expiration_date . '.
			
			<br />
			We decided to reach out to you since we don’t want your FREE Trial to end without you having the opportunity to test drive ' . software_name . '.
			
			<p>Your account details are:</p>
			
			<div style="border: 1px solid rgb(46, 117, 182); padding: 15px">	
				School: <b>' . $school_name . '</b> <br />
				Login Email: ' . $email . '<br />
				Login URL: ' . $login_url . '<br />'
				. $anchor_link . 
			'</div>' .

			cron_message_footer();	
	}
	
	
	function cron_paid_subscription_no_activity_message($name, $school_name, $date_activated, $email, $login_url, $anchor_link) { 
		return
			'Hi ' . $name . ', 
			<br />
			<h3>Do you need our help to get up and running?</h3> 
			
			Your ' . software_name . ' subscription begun running on ' . $date_activated . '. It is important to us that you get the best possible value from your subscription so we would like to know if you need our help to set things up.
			
			<p>Your account details are:</p>
			
			<div style="border: 1px solid rgb(46, 117, 182); padding: 15px">	
				School: <b>' . $school_name . '</b> <br />
				Login Email: ' . $email . '<br />
				Login URL: ' . $login_url . '<br />'
				. $anchor_link . 
			'</div>' .
			
			cron_message_footer();	
	}
	
	
	function cron_expiring_free_trial_message($name, $expiration_date, $coupon_code, $discount_url, $anchor_link) { 
		return
			'Hi ' . $name . ', 
			<br />
			<h3>Would you like a 10% discount?</h3>
			
			Everyone loves a bargain. That\'s why we\'re offering you a 10% discount on your preferred ' . software_name . ' plan if you are willing to pay for it before ' . $expiration_date . '.
			
			<br />
			If you\'re interested in taking up this offer, use the code below to activate a 10% discount.
			
			<p>Discount Code: ' . $coupon_code . '</p>

			<p>' . $anchor_link . '</p>
			
			Alternatively you may reach out to us on <a href="mailto:' . software_support_mail . '">' . software_support_mail . '</a> or chat with us live on <a href="' . base_url() . '">' . base_url() . '</a>.
			You can also chat with us on WhatsApp or give us a call on +234 8035004519.
			
			<p>We look forward to hearing from you.</p>

			<h3> ' . software_team . '.</h3>';
	}


	function cron_expired_free_trial_message($name, $expiration_date, $extend_date, $coupon_code, $discount_url, $anchor_link) { 
		return
			'Hi ' . $name . ', 
			<br />
			<h3>Would you like a 10% discount?</h3>
			
			Your free trial expired on ' . $expiration_date . ', however, we don\'t want to let you go. That\'s why we\'re offering you a 10% discount on your preferred ' . software_name . ' plan if you are willing to pay for it before ' . $extend_date . '.
			
			<br />
			If you\'re interested in taking up this offer, use the code below to activate a 10% discount.
			<p></p>

			<p>Discount Code: ' . $coupon_code . '</p>

			<p>' . $anchor_link . '</p>
			
			Alternatively you may reach out to us on <a href="mailto:' . software_support_mail . '">' . software_support_mail . '</a> or chat with us live on <a href="' . base_url() . '">' . base_url() . '</a>.
			You can also chat with us on WhatsApp or give us a call on +234 8035004519.
			
			<p>We look forward to hearing from you.</p>

			<h3> ' . software_team . '</h3>';
	}


	function cron_expired_free_trial_monthly_message($name, $expiration_date, $anchor_link) { 
		return
			'Hi ' . $name . ', 
			<br />
			<h3>Don\'t miss our amazing new features!</h3>
			
			Your free trial expired on ' . $expiration_date . ', however, we still don\'t want to let you go. You should activate your account now to start enjoying the benefits of this system. We are constantly adding new features in order to make the software better and ensure you get the best out of ' . software_name . '.
			
			<p>' . $anchor_link . '</p>
			
			If you have any issue whatsoever, you can reach out to us on <a href="mailto:' . software_support_mail . '">' . software_support_mail . '</a> or chat with us live on <a href="' . base_url() . '">' . base_url() . '</a>.
			You can also chat with us on WhatsApp or give us a call on +234 8035004519.
			
			<p>We look forward to hearing from you.</p>

			<h3> ' . software_team . '</h3>';
	}
	