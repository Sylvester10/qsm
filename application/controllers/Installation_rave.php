<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Installation_rave
Role: Controller
Description: Buy handles account creation payment using the Flutterwave Rave gateway system. 
Model: Installation_model
Author: Nwankwo Ikemefuna
Date Created: 20th August, 2018
*/

//CURRENTLY NOT IN USE. Kept for reference purposes


class Installation_rave extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('installation_model');
		//rave parameters
		require "application/core/constants/developer/Rave.php";
	}
	
	
	
	/* ===== Buy and Install ===== */
	public function buy_application($plan_id = NULL) {
		//check plan ID exists
		$this->login_header('Buy & Install Software');
		$query = $this->common_model->get_plan_details($plan_id);
		$plan_id = ($plan_id == NULL) ? 1 : $plan_id; //defaults to 1 if plan ID is null
		$plan_id = ($query) ? $plan_id : 1; //defaults to 1 if plan ID segment in URL in not a valid plan ID eg 4
		$plan = $this->common_model->get_plan_details($plan_id)->plan;
		$plans = $this->common_model->get_plans();
		$price = $this->common_model->get_plan_price_by_location($plan_id);
		$captcha_code = mt_rand(111111, 999999);
		$data['plans'] = $plans;
		$data['plan_id'] = $plan_id;
		$data['plan'] = $plan;
		$data['price'] = $price;
		$data['captcha_code'] = $captcha_code; //6-digit code for captcha test
		$data['script_url'] = script_url;
		$this->load->view('site/install/buy_rave', $data);
		$this->login_footer();
	}
	
	
	private function payment_data() {
		
		//create payment request parameters and values
		$plan_id = $this->input->post('plan_id', TRUE); 
		$plan = $this->common_model->get_plan_details($plan_id)->plan;
		$amount = $this->common_model->get_plan_price_digit_by_location_no_format($plan_id);
		$price = $this->common_model->get_plan_price_by_location($plan_id);
		$currency = $this->common_model->get_currency_letter_by_location(); //either NGN or USD depending on user's location
		
		$admin_name = $this->input->post('admin_name', TRUE); 
		$first_name = get_firstname($admin_name);
		$email = $this->input->post('admin_email', TRUE); 
		$phone = $this->input->post('admin_phone', TRUE); 
		
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

		//create array of data
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
		return $data;
	}
	
	
	public function buy_application_ajax() {	
		
		if ($_POST) {
			
			$login_url = '<a href="' . base_url('login') . '">login</a>';		
			
			//school info
			$this->form_validation->set_rules('plan_id', 'Plan', 'trim|required');
			$this->form_validation->set_rules('mode', 'Mode', 'trim|required');
			$this->form_validation->set_rules('school_name', 'School Name', 'trim|required');
			$this->form_validation->set_rules('school_location', 'School Location', 'trim|required');
			$this->form_validation->set_rules('country', 'Country', 'trim|required');
			$this->form_validation->set_rules('currency', 'Currency', 'trim|required');
			
			$school_email = $this->input->post('official_mail', TRUE); 
			$this->form_validation->set_rules('official_mail', 'Official Email Address', 'trim|required|valid_email|is_unique[school_info.official_mail]',
				array('is_unique' => "This email address [{$school_email}] is already registered with this application. If it's you, proceed to {$login_url}.")
			);
		
			$this->form_validation->set_rules('telephone_line', 'Official Telephone Line', 'trim|required|max_length[20]');
			$this->form_validation->set_rules('school_website', 'School Website', 'trim|regex_match[#^http://|https://|Http://|Https://#]',
				array('regex_match' => 'Website URL must be prefixed with http:// or https://')
			);	
			//admin info 
			$this->form_validation->set_rules('admin_name', 'Admin Name', 'trim|required');
			
			$admin_email = $this->input->post('admin_email', TRUE); 
			$this->form_validation->set_rules('admin_email', 'Admin Email Address', 'trim|required|valid_email|is_unique[admins.email]',
				array('is_unique' => "This email address [{$admin_email}] is already registered with this application. If it's you, proceed to {$login_url} and continue with your payment, or use a different email address.")
			);
			$this->form_validation->set_rules('admin_phone', 'Admin Phone Number', 'trim|required|max_length[20]');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
			$this->form_validation->set_rules('c_password', 'Password', 'required|matches[password]', 
				array('matches' => 'Passwords do not match')
			);
			$this->form_validation->set_rules('referrer', 'How did you hear about us', 'trim|required');
			$this->form_validation->set_rules('captcha', 'Captcha', 'trim');
			$this->form_validation->set_rules('c_captcha', 'Captcha Code', 'required|exact_length[6]|matches[captcha]', 
				array('matches' => 'Incorrect captcha code')
			);
	
			if ($this->form_validation->run() == FALSE) {
				$errors = array(
					'validation_status' => 0,
					'validation_errors' => validation_errors()
				);
				echo json_encode($errors);
				
			} else {
				
				$school_name = ucwords($this->input->post('school_name', TRUE)); 
				$school_location = ucwords($this->input->post('school_location', TRUE)); 
				$country = ucwords($this->input->post('country', TRUE)); 
				$currency_code = ucwords($this->input->post('currency', TRUE)); 
				$official_mail = strtolower($this->input->post('official_mail', TRUE)); 
				$telephone_line = $this->input->post('telephone_line', TRUE); 
				$school_website = $this->input->post('school_website', TRUE); 
				$referrer = $this->input->post('referrer', TRUE); 

				$plan_id = $this->input->post('plan_id', TRUE); 
				$mode = 'Paid';
				$year_installed = date('Y');

				//activation code 
				$activation_code = $this->installation_model->generate_activation_code($plan_id);
				
				$insert_data = array (
					'school_name' 			=> 		$school_name,
					'school_location' 		=> 		$school_location,
					'country' 				=> 		$country,
					'currency_code' 		=> 		$currency_code,
					'official_mail' 		=> 		$official_mail,
					'telephone_line' 		=> 		$telephone_line,
					'school_website' 		=> 		$school_website,
					'referrer' 				=> 		$referrer,
					'year_installed' 		=> 		$year_installed,
					'plan_id' 				=> 		$plan_id,
					'mode' 					=> 		$mode,
					'activated' 			=> 		'false', 
					'activation_code' 		=> 		$activation_code,
					'show_activation_code' 	=> 		'false',
					'confirmed' 			=> 		'false',
				);
				//insert school data
				$school_id = $this->installation_model->insert_school_data_on_buy($insert_data);
				
				//get payment data and json_encode to javascript for AJAXing
				$p_data = $this->payment_data();
					 
				//create array of data to be jscon_encoded
				$json_data = array(
					"school_id" => $school_id,
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
					"validation_status" => 1,
				);
				echo json_encode($json_data);
					
			}
			
		} else { //not $_POST
			echo "false"; 
		}
    } 

	
	public function complete_payment($school_id) {
		
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

				//delete school data
				$this->installation_model->delete_school_data($school_id);	
				echo "Transaction Failed!"; 

			} elseif (strtolower($status) == 'successful') { 
				
				$verify_payment = $this->verify_payment($tranx_ref); //check rave response
				
				if( ! $verify_payment['status']) {
					
					//delete school data
					$this->installation_model->delete_school_data($school_id);	
					echo "Transaction Failed! Payment could not be verified";
					
				} else {
						
					$this->installation_model->update_school_data_on_buy($school_id);		
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




	/* ===== Buy and Install: With Verification Code ===== */
	public function buy_other($plan_id = NULL) {
		//check plan ID exists
		$this->login_header('Buy & Install Software');
		$query = $this->common_model->get_plan_details($plan_id);
		$plan_id = ($plan_id == NULL) ? 1 : $plan_id; //defaults to 1 if plan ID is null
		$plan_id = ($query) ? $plan_id : 1; //defaults to 1 if plan ID segment in URL in not a valid plan ID eg 4
		$plan = $this->common_model->get_plan_details($plan_id)->plan;
		$plans = $this->common_model->get_plans();
		$price = $this->common_model->get_plan_price_by_location($plan_id);
		$captcha_code = mt_rand(111111, 999999);
		$data['plans'] = $plans;
		$data['plan_id'] = $plan_id;
		$data['plan'] = $plan;
		$data['price'] = $price;
		$data['captcha_code'] = $captcha_code; //6-digit code for captcha test
		$data['script_url'] = script_url;
		$this->load->view('site/install/buy_other', $data);
		$this->login_footer();
	}


	public function buy_other_ajax() {	
		$login_url = '<a href="' . base_url('login') . '">login</a>';
		
		//school info
        $this->form_validation->set_rules('plan_id', 'Plan', 'trim|required');
        $this->form_validation->set_rules('mode', 'Mode', 'trim|required');
        $this->form_validation->set_rules('school_name', 'School Name', 'trim|required');
        $this->form_validation->set_rules('school_location', 'School Location', 'trim|required');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        $this->form_validation->set_rules('currency', 'Currency', 'trim|required');
        
		$school_email = $this->input->post('official_mail', TRUE); 
		$this->form_validation->set_rules('official_mail', 'Official Email Address', 'trim|required|valid_email|is_unique[school_info.official_mail]',
			array('is_unique' => "This email address [{$school_email}] is already registered with this application. If it's you, proceed to {$login_url}.")
		);
			
		$this->form_validation->set_rules('telephone_line', 'Official Telephone Line', 'trim|required|max_length[20]');
		$this->form_validation->set_rules('school_website', 'School Website', 'trim|regex_match[#^http://|https://|Http://|Https://#]',
			array('regex_match' => 'Website URL must be prefixed with http:// or https://')
		);	
		//admin info 
		$this->form_validation->set_rules('admin_name', 'Admin Name', 'trim|required');
		
		$admin_email = $this->input->post('admin_email', TRUE); 
		$this->form_validation->set_rules('admin_email', 'Admin Email Address', 'trim|required|valid_email|is_unique[admins.email]',
			array('is_unique' => "This email address [{$admin_email}] is already registered with this application. If it's you, proceed to {$login_url} and continue with your payment, or use a different email address.")
		);
		$this->form_validation->set_rules('admin_phone', 'Admin Phone Number', 'trim|required|max_length[20]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('c_password', 'Password', 'required|matches[password]', 
			array('matches' => 'Passwords do not match')
		);
		$this->form_validation->set_rules('referrer', 'How did you hear about us', 'trim|required');
		$this->form_validation->set_rules('captcha', 'Captcha', 'trim');
		$this->form_validation->set_rules('c_captcha', 'Captcha Code', 'required|exact_length[6]|matches[captcha]', 
			array('matches' => 'Incorrect captcha code')
		);

		if ($this->form_validation->run())  {		
			$this->installation_model->buy_other();
			echo 1;
		} else { 
			echo validation_errors();
		}
    } 
	





}
	
