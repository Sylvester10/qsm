<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Activate
Role: Controller
Description: Activate handles account activation payment using the Flutterwave Rave gateway system. 
Model: Plan_model
Author: Nwankwo Ikemefuna
Date Created: 19th August, 2018
*/


class Activate_rave extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->load->model('plan_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		
		//rave parameters
		require "application/core/constants/developer/Rave.php";

		//module-level scripts
		$this->admin_module_scripts = array('s_plan_rave');
	}
	
	
	
	public function index() { 
		$this->admin_header('Activate Account', 'Activate Account');
		//get payment data and pass to view
		$data = $this->payment_data(); 
		$data['plans'] = $this->common_model->get_plans();
		$this->load->view('admin/plan/activate', $data);
		$this->admin_footer();
	}
	
	
	private function payment_data() {
		
		//create payment request parameters and values
		$plan_id = school_plan_id;
		$plan = $this->common_model->get_plan_details($plan_id)->plan;
		//$amount = 100;
		$amount = $this->common_model->get_plan_price_digit_by_location_no_format($plan_id);
		$price = $this->common_model->get_plan_price_by_location($plan_id);
		$currency = $this->common_model->get_currency_letter_by_location(); //either NGN or USD depending on user's location
		$first_name = get_firstname($this->admin_details->name);
		$email = $this->admin_details->email;
		$phone = $this->admin_details->phone;
		
		//generate unique transaction reference
		//this is achieved by randomizing a string formed from time of transaction
		$tranx_time = date('dmyhis') . mt_rand(); //generates a value like 1808180625001758686925
		$tranx_ref = str_shuffle($tranx_time); //finally, shuffle the string
		
		//payment method
		$payment_method = 'card'; //card, account, both
		
		//get public and secret Rave API keys
		$public_key = public_key;
		$secret_key = secret_key;
		
		//Create an array of parameters to be used to generate integrity hash/checksum to secure payment process.
		
		/*
		//IMPORTANT: 
		//Rave will pick up the parameters sent in getpaidSetup function, sort it, compute the hash and compare it with the value of integrity_hash to make sure they match. This means that values to be hashed must match the values passed to Rave in getpaidSetup function.		
		//Ensure integrity_hash value is sent in lower case, the server computes the hash in lowercase so they need to match.
		//Ensure values are sorted and hashed using the same letter cases as what is added in getpaidSetup function e.g. if you hash customer_firstname: IKEMEFUNA, then you need to pass it to the client as customer_firstname: IKEMEFUNA.
		//For amount with decimal places, if decimal places have trailing zero's e.g. 100.000 , remove all trailing zero's.
		*/
		
		$parameters = array(
			"PBFPubKey" => $public_key,
			"amount" => $amount,
			"currency" => $currency,
			"customer_email" => $email,
			"customer_firstname" => $first_name,
			"customer_phone" => $phone,
			"payment_method" => $payment_method,
			"txref" => $tranx_ref,
		);
		
		//sort the parameters by array keys (ASCII value)
		ksort($parameters);

		//concatenate the values in the order of sorted keys
		$hash_array = array(); 
		foreach($parameters as $val) {
			$hash_array[] = $val; //same as doing $hash_array .= $val (assuming $hash_array is a string)
		}
		
		//implode hash_array into a string
		$hash_string = implode($hash_array);
		
		//concatenate hash_string with secret key
		$complete_hash = $hash_string . $secret_key;
		
		//generate the sha256 hash of the concatenated strings
		$hash = hash('sha256', $complete_hash);		
		
		//convert hash to lowercase (for the reason described above)
		$integrity_hash = strtolower($hash);

		//create array of data to be sent to view
		$data['plan_id'] = $plan_id;
		$data['plan'] = $plan;
		$data['price'] = $price;
		$data['amount'] = $amount;
		$data['currency'] = $currency;
		$data['email'] = $email;
		$data['first_name'] = $first_name;
		$data['phone'] = $phone;
		$data['tranx_ref'] = $tranx_ref;
		$data['payment_method'] = $payment_method;
		$data['integrity_hash'] = $integrity_hash;
		$data['public_key'] = public_key;
		$data['script_url'] = script_url;
		$data['verify_url'] = verify_url;
		$data['environment'] = environment;
		$data['school_id'] = school_id;
		$data['school_name'] = school_name;
		return $data;
	}

	
	public function initiate_activation() {
		
		if ($_POST) {
			
			$this->form_validation->set_rules('amount', 'Amount', 'trim');
			$this->form_validation->set_rules('currency', 'Currency', 'trim');
			$this->form_validation->set_rules('email', 'Email', 'trim');
			$this->form_validation->set_rules('first_name', 'First Name', 'trim');
			$this->form_validation->set_rules('phone', 'Phone', 'trim');
			$this->form_validation->set_rules('school_id', 'School ID', 'trim');
			
			if ($this->form_validation->run() == FALSE) {
				echo "invalid";
			} else {
			
				//get payment data and json_encode to javascript for AJAXing
				$p_data = $this->payment_data();
					 
				//create array of data to be jscon_encoded
				$json_data = array(
					"plan_id" => $p_data['plan_id'],
					"plan" => $p_data['plan'],
					"price" => $p_data['price'],
					"amount" => $p_data['amount'],
					"currency" => $p_data['currency'],
					"email" => $p_data['email'],
					"first_name" => $p_data['first_name'],
					"phone" => $p_data['phone'],
					"tranx_ref" => $p_data['tranx_ref'],
					"payment_method" => $p_data['payment_method'], 
					"integrity_hash" => $p_data['integrity_hash'],
					"public_key" => public_key, 
					"script_url" => script_url,
					"verify_url" => verify_url,
					"environment" => environment,
					"school_id" => school_id,
				);
				echo json_encode($json_data);
				
			} 
			
		} else {
			echo "false";
		}
	}
	
	
	public function complete_activation() {
		
		if ($_POST) {
			
			//get the response parameters and values
			$tranx_ref = $this->input->post('tranx_ref');
			$message = $this->input->post('msg');
			$status = $this->input->post('status');
			$rave_ref = $this->input->post('rave_ref');
			$date_updated = $this->input->post('date_updated');
			$amount = $this->input->post('amount');
		
			$verify_url = verify_url; 
			//concat transaction reference with verify url
			$verify_url .= $tranx_ref;
			
			if (strtolower($status) == 'failed') {

				$this->session->set_flashdata('status_msg_error', 'Transaction Failed!');
				echo "1"; //error chargeResponseCode

			} elseif (strtolower($status) == 'successful') { 
				
				$verify_payment = $this->verify_payment($tranx_ref); //check rave response
				
				if( ! $verify_payment['status']) {
					
					$this->session->set_flashdata('status_msg_error', 'Transaction Failed! Payment could not be verified');
					echo "1"; //error chargeResponseCode
					
				} else {
					
					//approve payment and activate school account
					$this->plan_model->activate_school_account();			
					$this->session->set_flashdata('status_msg', 'Transaction successful! Your account has been activated.');
					echo "0"; //success chargeResponseCode
						
				}
			}

		}

	}

	
	private function verify_payment($tranx_ref) {
		
		$result = array();

		$secret_key = secret_key;
		
		$post_data =  array( 
		  'txref' => $tranx_ref,
		  'SECKEY' => $secret_key,
		  'last_attempt' => '1'
		);		
		$post_data = json_encode($post_data);
		
		$verify_url = verify_url;
		
		$headers = [
		  'Content-Type: application/json',
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $verify_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);  //Post Fields
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$request = curl_exec($ch);
		$err = curl_error($ch);
		
		if ($err) {
			$response = array(
				"status" => false,
				"message" => "An error occurred"
			);
			return $response;
		}
		
		curl_close ($ch);

		$result = json_decode($request, true);
		$result = (Object)$result;
		$data = (Object)$result->data;

		if ('error' == $result->status) {
			$response = array(
				"status" => false,
				"message" => "Payment not completed"
			);
			return $response;
		}
		
		$ar = (array)$data;
		
		if ( ! empty($ar)) {
			
			if ('failed' == $data->status) {
				$response = array(
					"status" => false,
					"message" => "Failed Transaction"
				);
				return $response;
			}

			if ('successful' == $data->status && '00' == $data->vbvcode) {
				$response = array(
					"status" => true,
					"result" => $data
				);
				return $response;
			}
			
		} else {
			
			$response = array(
				"status" => false,
				"message" => "An internal error occurred"
			);
			return $response;
			
		}
		
	}






	/* =============== Activation using other modes of payment ================== */
	public function activate_school_account_other() { 
		//ensure payment has been made i.e. user has been sent activation code
		$this->plan_model->check_activation_code();
		$this->plan_model->activate_school_account();
		$this->session->set_flashdata('status_msg', 'Account activated successfully.');
		redirect(site_url('plan/account_info'));
	}






}
	
