<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Upgrade_rave
Role: Controller
Description: Upgrade_rave handles account upgrade
Model: Plan_model
Author: Nwankwo Ikemefuna
Date Created: 21st August, 2018
*/


class Upgrade extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->load->model('plan_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		
		$this->paypal_currency_code = $this->config->item('paypal_lib_currency_code'); 
		$this->paypal_logo = $this->config->item('paypal_lib_logo'); 

		//module-level scripts
		$this->admin_module_scripts = array('s_activate_upgrade');
	}
	
	
	
	public function index() { 
		$this->admin_header('Upgrade Account', 'Upgrade Account');
		$plan_id = school_plan_id;
		$plan = $this->common_model->get_plan_details($plan_id)->plan;
		$price = $this->common_model->get_plan_price_by_location($plan_id);
		$plans = $this->common_model->get_plans();
		$data['plan_id'] = $plan_id;
		$data['plan'] = $plan;
		$data['price'] = $price;
		$data['plans'] = $plans;
		$data['upgrade_request'] = $this->plan_model->get_upgrade_request();
		$data['upgrade_plans'] = $this->plan_model->get_upgrade_plans();
		$data['currency_code'] = $this->common_model->get_currency_by_location();
		$data['currency'] = $this->common_model->get_currency_letter_by_location(); //either NGN or USD depending on user's location
		$data['paypal_logo'] = $this->paypal_logo;
		$data['location'] = ip_info_safe("Visitor", "Country");
		$this->load->view('admin/plan/upgrade', $data);
		$this->admin_footer();
	}


	private function payment_data() {
		
		//create payment request parameters and values
		$upgrade_plan_id = $this->input->post('plan_id', TRUE); 
		$plan = $this->common_model->get_plan_details($upgrade_plan_id)->plan;

		$amount = $this->plan_model->get_additional_amount($upgrade_plan_id);
		$currency = $this->common_model->get_currency_letter_by_location(); //either NGN or USD depending on user's location

		$first_name = get_firstname($this->admin_details->name);
		$email = $this->admin_details->email;
		$phone = $this->admin_details->phone;

		//create array of data to be shared
		$data['upgrade_plan_id'] = $upgrade_plan_id;
		$data['plan'] = $plan;
		$data['amount'] = $amount;
		$data['currency'] = $currency;
		$data['email'] = $email;
		$data['first_name'] = $first_name;
		$data['phone'] = $phone;
		$data['school_id'] = school_id;
		$data['school_name'] = school_name;
		return $data;
	}
	
	
	public function initiate_upgrade() {
			
		$this->form_validation->set_rules('plan_id', 'Plan', 'trim|required');
		
		if ($this->form_validation->run() == FALSE) {

			$errors = array(
				'validation_status' => 0,
				'validation_errors' => validation_errors()
			);
			echo json_encode($errors);

		} else {

			//get payment data and json_encode to javascript for AJAXing
			$p_data = $this->payment_data();
			$upgrade_plan_id = $p_data['upgrade_plan_id'];

			$currency_code = $this->common_model->get_currency_by_location();

			//create array of data to be sent to database
			$insert_data = array(
				'school_id' => school_id,
				'current_plan_id' => school_plan_id,
				'upgrade_plan_id' => $upgrade_plan_id,
				'upgrade_amount' => $p_data['amount'],
				'currency_code' => $currency_code,
				'upgrade_code' => $this->plan_model->generate_upgrade_code($upgrade_plan_id),
				'show_upgrade_code' => 'false', //Important, else users can upgrade themselves without paying
			);

			//check if upgrade has been initiated earlier
			$upgrade_request = $this->plan_model->get_upgrade_request();
			if ( ! $upgrade_request) { //no, do insert
				$this->plan_model->initiate_upgrade($insert_data);
			} else { //yes, do update
				$update_data = array(
					'upgrade_plan_id' => $upgrade_plan_id,
				);
				$this->plan_model->update_plan_on_upgrade($update_data);
			}
		
			//create array of data to be jscon_encoded
			$json_data = array(
				"upgrade_plan_id" => $upgrade_plan_id,
				"school_id" => school_id,
				"validation_status" => 1,
			);
			echo json_encode($json_data);
			
		} 
			
	}

	
	public function pay_with_paypal($upgrade_plan_id) {
        // Set variables for paypal form
        $returnURL = base_url('upgrade/success');
        $cancelURL = base_url('upgrade/cancel'); 
        $notifyURL = base_url('paypal/ipn_upgrade'); //IPN listener URL
        
        //get user details
        $school_id = school_id;
        $item_name = 'Account Upgrade';
		$amount = $this->plan_model->get_additional_dollar_amount($upgrade_plan_id);
		$currency_code = $this->paypal_currency_code;
        
        //Cross Site Request Forgery parameters
		$csrf = array(
			'name' => $this->security->get_csrf_token_name(),
			'hash' => $this->security->get_csrf_hash()
		);
		$csrf_name = $csrf['name'];
		$csrf_value = $csrf['hash'];
		
        // Add fields to paypal form
        $this->paypal_lib->add_field($csrf_name, $csrf_value); 
        $this->paypal_lib->add_field('return', $returnURL); //NOTE: if set, overrides default return URL specified in PayPal profile
        $this->paypal_lib->add_field('cancel_return', $cancelURL);
        $this->paypal_lib->add_field('notify_url', $notifyURL); //NOTE: if set, overrides default notify URL specified in PayPal profile
        $this->paypal_lib->add_field('item_name', $item_name);
        $this->paypal_lib->add_field('amount',  $amount);
        $this->paypal_lib->add_field('currency_code',  $currency_code);
        $this->paypal_lib->add_field('custom', $school_id); //custom field
        $this->paypal_lib->add_field('item_number', $upgrade_plan_id);
         
        // Render paypal form
        $this->paypal_lib->paypal_auto_form();
    }
	
	
	public function success() {
		$this->admin_header('Transaction Status', 'Transaction Status');
		// Get the transaction data using PDT-specific variables
        $paypal_return_data 	= $this->input->get();
        $data['payment_for']    = $paypal_return_data['item_name'];
        $data['txn_id']         = $paypal_return_data["tx"]; //transaction ID
        $data['amount']    		= $paypal_return_data["amt"]; //amount
        $data['currency_code']  = $paypal_return_data["cc"]; //currency code
        $data['status']         = $paypal_return_data["st"]; //payment status
		$data['return_url'] 	= 'plan/account_info';
		$this->load->view('admin/plan/paypal/success', $data);
		$this->admin_footer();
    }
	
	
	public function cancel() {
		$this->admin_header('Transaction Canceled', 'Transaction Canceled');
		$data['action_url'] = 'upgrade';
		$this->load->view('admin/plan/paypal/cancel', $data);
		$this->admin_footer();
	}






	/* =============== Upgrade using other modes of payment ================== */
	public function initiate_upgrade_other() {	
		$this->form_validation->set_rules('plan_id', 'Plan', 'trim|required');
		if ($this->form_validation->run()) {
			$this->plan_model->initiate_upgrade_other();
			$this->session->set_flashdata('status_msg', 'Account upgrade initiated successfully. Make payment and receive upgrade code to complete upgrade.');
			redirect(site_url('upgrade'));
		} else {
			$this->session->set_flashdata('status_msg_error', 'An error occurred. Please try again');
			redirect(site_url('upgrade'));
		}
	}


	public function change_upgrade_plan_other() {	
		$this->form_validation->set_rules('plan_id', 'Plan', 'trim|required');
		if ($this->form_validation->run()) {
			$this->plan_model->change_upgrade_plan_other();
			$this->session->set_flashdata('status_msg', 'Account upgrade plan changed successfully. Make payment and receive upgrade code to complete upgrade.');
			redirect(site_url('upgrade'));
		} else {
			$this->session->set_flashdata('status_msg_error', 'An error occured. Please try again');
			redirect(site_url('upgrade'));
		}
	}


	public function upgrade_school_account_other($upgrade_plan_id) { 
		//ensure payment has been made i.e. user has been sent upgrade code
		$this->plan_model->check_upgrade_code();
		$this->plan_model->upgrade_school_account_other(school_id, $upgrade_plan_id);
		$this->session->set_flashdata('status_msg', 'Account upgraded successfully.');
		redirect(site_url('plan/account_info'));
	}




}
	
