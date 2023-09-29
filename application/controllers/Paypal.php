<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Paypal
Role: Controller
Description: Handles paypal payment processing
Model: Site_model
Author: Nwankwo Ikemefuna
Date Created: 25th September, 2018
*/


class Paypal extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('paypal_model');
		$this->load->model('plan_model');
		$this->load->model('installation_model');
		$this->sandbox = $this->config->item('sandbox'); 
		$this->paypal_host = $this->config->item('paypal_host'); 
		$this->paypal_url = $this->config->item('paypal_url'); 
		$this->business_email = $this->config->item('business');
	}


	public function ipn() { //Default IPN listener
		// STEP 1: Read POST data

		// reading posted data from directly from $_POST causes serialization 
		// issues with array data in POST
		// reading raw POST data from input stream instead. 
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
		  $keyval = explode ('=', $keyval);
		  if (count($keyval) == 2)
			 $myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
		   $get_magic_quotes_exists = true;
		} 
		foreach ($myPost as $key => $value) {        
		   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
				$value = urlencode(stripslashes($value)); 
		   } else {
				$value = urlencode($value);
		   }
		   $req .= "&$key=$value";
		}
 
		// STEP 2: Post IPN data back to paypal to validate

		$ch = curl_init($this->paypal_url);
		
		$headers = array(
			'POST /cgi-bin/webscr HTTP/1.1',
			'Host: ' . $this->paypal_host,
			'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
			'Content-Length: ' . strlen($req),
			'User-Agent: PayPal-IPN-VerificationScript',
			'Connection: Close'
		);
		
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		if( !($res = curl_exec($ch)) ) {
			curl_close($ch);
			exit;
		}
		curl_close($ch);

		// STEP 3: Inspect IPN validation result and act accordingly

		if (strcmp ($res, "VERIFIED") == 0) {
			
			// assign posted variables to local variables
			$item_name = $_POST['item_name'];
			$item_number = $_POST['item_number'];
			$payment_status = $_POST['payment_status'];
			$payment_amount = $_POST['mc_gross'];
			$payment_currency = $_POST['mc_currency'];
			$txn_id = $_POST['txn_id'];
			$receiver_email = urldecode($_POST['receiver_email']); 
			$payer_email = $_POST['payer_email'];
			$school_id = $_POST['custom'];
			
			// further checks
			if($payment_status == 'Completed') {
				
				//email developer
				$message = 'IPN verified successfully!';
				$this->email_developer($message);
				
				// Insert the transaction data in the database
				$this->paypal_model->insert_transaction_details($_POST);

			} else {
				
				//email developer
				$message = 'Payment could not be verified!';
				$this->email_developer($message);  
				
			}

		} else if (strcmp ($res, "INVALID") == 0) {
			
			//email developer
			$message = 'IPN Invalid!';
			$this->email_developer($message);
			
		}
	}
	
	
	public function ipn_buy() {
		// STEP 1: Read POST data

		// reading posted data from directly from $_POST causes serialization 
		// issues with array data in POST
		// reading raw POST data from input stream instead. 
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
		  $keyval = explode ('=', $keyval);
		  if (count($keyval) == 2)
			 $myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
		   $get_magic_quotes_exists = true;
		} 
		foreach ($myPost as $key => $value) {        
		   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
				$value = urlencode(stripslashes($value)); 
		   } else {
				$value = urlencode($value);
		   }
		   $req .= "&$key=$value";
		}

		// STEP 2: Post IPN data back to paypal to validate

		$ch = curl_init($this->paypal_url);
		
		$headers = array(
			'POST /cgi-bin/webscr HTTP/1.1',
			'Host: ' . $this->paypal_host,
			'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
			'Content-Length: ' . strlen($req),
			'User-Agent: PayPal-IPN-VerificationScript',
			'Connection: Close'
		);
		
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		if( !($res = curl_exec($ch)) ) {
			curl_close($ch);
			exit;
		}
		curl_close($ch);

		// STEP 3: Inspect IPN validation result and act accordingly

		if (strcmp ($res, "VERIFIED") == 0) {
			
			// assign posted variables to local variables
			$item_name = $_POST['item_name'];
			$payment_status = $_POST['payment_status'];
			$payment_amount = $_POST['mc_gross'];
			$payment_currency = $_POST['mc_currency'];
			$txn_id = $_POST['txn_id'];
			$receiver_email = urldecode($_POST['receiver_email']); 
			$payer_email = $_POST['payer_email'];
			$school_id = $_POST['custom'];
			
			// further checks
			if ($payment_status == 'Completed') {
				
				//approve payment and activate school account
				$this->installation_model->buy_install_update($school_id);	

				// Insert the transaction data in the database
				$this->paypal_model->insert_transaction_details($_POST);
				
				//email receipt to payer
				$this->paypal_model->email_receipt($_POST);	 
				
				//email developer
				$message = 'IPN verified successfully!';
				$this->paypal_model->email_developer($message);

			} else {
				
				//delete school data
				$this->installation_model->buy_install_delete($school_id);	
				
				//email developer
				$message = 'Payment could not be verified!';
				$this->paypal_model->email_developer($message);
				
			}

		} else if (strcmp ($res, "INVALID") == 0) {
			
			//delete school data
			$this->installation_model->buy_install_delete($school_id);	
			
			//email developer
			$message = 'IPN Invalid!';
			$this->paypal_model->email_developer($message);
			
		}
	}
	
	
	public function ipn_activate() {
		// STEP 1: Read POST data

		// reading posted data from directly from $_POST causes serialization 
		// issues with array data in POST
		// reading raw POST data from input stream instead. 
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
		  $keyval = explode ('=', $keyval);
		  if (count($keyval) == 2)
			 $myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
		   $get_magic_quotes_exists = true;
		} 
		foreach ($myPost as $key => $value) {        
		   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
				$value = urlencode(stripslashes($value)); 
		   } else {
				$value = urlencode($value);
		   }
		   $req .= "&$key=$value";
		}

		// STEP 2: Post IPN data back to paypal to validate

		$ch = curl_init($this->paypal_url);
		
		$headers = array(
			'POST /cgi-bin/webscr HTTP/1.1',
			'Host: ' . $this->paypal_host,
			'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
			'Content-Length: ' . strlen($req),
			'User-Agent: PayPal-IPN-VerificationScript',
			'Connection: Close'
		);
		
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		if( !($res = curl_exec($ch)) ) {
			curl_close($ch);
			exit;
		}
		curl_close($ch);

		// STEP 3: Inspect IPN validation result and act accordingly

		if (strcmp ($res, "VERIFIED") == 0) {
			
			// assign posted variables to local variables
			$item_name = $_POST['item_name'];
			$payment_status = $_POST['payment_status'];
			$payment_amount = $_POST['mc_gross'];
			$payment_currency = $_POST['mc_currency'];
			$txn_id = $_POST['txn_id'];
			$receiver_email = urldecode($_POST['receiver_email']); 
			$payer_email = $_POST['payer_email'];
			$school_id = $_POST['custom'];
			
			// further checks
			if ($payment_status == 'Completed') {
				
				//approve payment and activate school account
				$this->plan_model->activate_school_account($school_id);	

				// Insert the transaction data in the database
				$this->paypal_model->insert_transaction_details($_POST);
				
				//email receipt to payer
				$this->paypal_model->email_receipt($_POST);

				//email developer
				$message = 'IPN verified successfully!';
				$this->paypal_model->email_developer($message);

			} else {
				
				//email developer
				$message = 'Payment could not be verified!';
				$this->paypal_model->email_developer($message);
				
			}

		} else if (strcmp ($res, "INVALID") == 0) {
			
			//email developer
			$message = 'IPN Invalid!';
			$this->paypal_model->email_developer($message);
			
		}
	}
	
	
	public function ipn_upgrade() {
		// STEP 1: Read POST data

		// reading posted data from directly from $_POST causes serialization 
		// issues with array data in POST
		// reading raw POST data from input stream instead. 
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
		  $keyval = explode ('=', $keyval);
		  if (count($keyval) == 2)
			 $myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
		   $get_magic_quotes_exists = true;
		} 
		foreach ($myPost as $key => $value) {        
		   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
				$value = urlencode(stripslashes($value)); 
		   } else {
				$value = urlencode($value);
		   }
		   $req .= "&$key=$value";
		}

		// STEP 2: Post IPN data back to paypal to validate

		$ch = curl_init($this->paypal_url);
		
		$headers = array(
			'POST /cgi-bin/webscr HTTP/1.1',
			'Host: ' . $this->paypal_host,
			'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
			'Content-Length: ' . strlen($req),
			'User-Agent: PayPal-IPN-VerificationScript',
			'Connection: Close'
		);
		
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		if( !($res = curl_exec($ch)) ) {
			curl_close($ch);
			exit;
		}
		curl_close($ch);

		// STEP 3: Inspect IPN validation result and act accordingly

		if (strcmp ($res, "VERIFIED") == 0) {
			
			// assign posted variables to local variables
			$item_name = $_POST['item_name'];
			$payment_status = $_POST['payment_status'];
			$payment_amount = $_POST['mc_gross'];
			$payment_currency = $_POST['mc_currency'];
			$txn_id = $_POST['txn_id'];
			$receiver_email = urldecode($_POST['receiver_email']); 
			$payer_email = $_POST['payer_email'];
			$school_id = $_POST['custom'];
			$upgrade_plan_id = $_POST['item_number'];
			
			// further checks 
			if ($payment_status == 'Completed') {
				
				//approve payment and upgrade school account
				$this->plan_model->upgrade_school_account($school_id, $upgrade_plan_id);	

				// Insert the transaction data in the database
				$this->paypal_model->insert_transaction_details($_POST);
				
				//email receipt to payer
				$this->paypal_model->email_receipt($_POST);

				//email developer
				$message = 'IPN verified successfully!';
				$this->paypal_model->email_developer($message);

			} else { 
			
				//delete upgrade request data
				$this->plan_model->delete_account_upgrade_request($school_id);		
				
				//email developer
				$message = 'Payment could not be verified!';
				$this->paypal_model->email_developer($message);
				
			}

		} else if (strcmp ($res, "INVALID") == 0) {
			
			//delete upgrade request data
			$this->plan_model->delete_account_upgrade_request($school_id);		
			
			//email developer
			$message = 'IPN Invalid!';
			$this->paypal_model->email_developer($message);
			
		}
	}
	
	
	
}