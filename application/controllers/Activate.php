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


class Activate extends MY_Controller {
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
		$this->admin_module_scripts = array('s_plan');
	}
	
	
	
	public function index() { 
		$this->admin_header('Activate Account', 'Activate Account');
		//get payment data and pass to view
		$data = $this->payment_data(); 
		$data['using_free_trial_coupon'] = $this->coupon_model->get_school_coupon_status(school_id);
		$data['school_info'] = $this->common_model->get_school_info(school_id);
		$this->load->view('admin/plan/activate', $data);
		$this->admin_footer();
	}
	
	
	private function payment_data() {
		
		//create payment request parameters and values
		$plan_id = school_plan_id;
		$plan = $this->common_model->get_plan_details($plan_id)->plan;

		$using_free_trial_coupon = $this->coupon_model->get_school_coupon_status(school_id);
		//check if school is using coupon to determine price
		if ($using_free_trial_coupon) {
			$coupon_id = $using_free_trial_coupon->coupon_id;
			$coupon_details = $this->coupon_model->get_free_trial_coupon_details($coupon_id);
			$discount = $coupon_details->discount;
			$valid_until = $coupon_details->valid_until;
			$amount = $this->coupon_model->get_plan_discounted_price_digit_by_location($plan_id, $discount);
			$price = $this->coupon_model->get_plan_discounted_price_by_location($plan_id, $discount);
		} else {
			$amount = $this->common_model->get_plan_price_digit_by_location_no_format($plan_id);
			$price = $this->common_model->get_plan_price_by_location($plan_id);
		}
		$currency = $this->common_model->get_currency_letter_by_location(); //either NGN or USD depending on user's location
		$first_name = get_firstname($this->admin_details->name);
		$email = $this->admin_details->email;
		$phone = $this->admin_details->phone;

		$plans = $this->common_model->get_plans();

		//create array of data to be sent to view
		$data['plans'] = $plans;
		$data['plan_id'] = $plan_id;
		$data['plan'] = $plan;
		$data['price'] = $price;
		$data['original_price'] = $this->common_model->get_plan_price_by_location($plan_id);
		$data['amount'] = $amount;
		$data['currency'] = $currency;
		$data['email'] = $email;
		$data['first_name'] = $first_name;
		$data['phone'] = $phone;
		$data['school_id'] = school_id;
		$data['school_name'] = school_name;
		$data['paypal_logo'] = $this->paypal_logo;
		$data['location'] = ip_info_safe("Visitor", "Country");
		return $data;
	}
	
	
	public function pay_with_paypal() {
        // Set variables for paypal form
        $returnURL = base_url('activate/success');
        $cancelURL = base_url('activate/cancel'); 
        $notifyURL = base_url('paypal/ipn_activate'); //IPN listener URL
        
        //get user details
        $school_id = school_id;
        $item_name = 'Account Activation';
		$plan_id = school_plan_id;
		$amount = $this->common_model->get_plan_details($plan_id)->price_dollar;
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
		$data['action_url'] = 'activate';
		$this->load->view('admin/plan/paypal/cancel', $data);
		$this->admin_footer();
	}

	
	
	
	

	/* =============== Activation using other modes of payment ================== */
	public function activate_school_account_other() { 
		//ensure payment has been made i.e. user has been sent activation code
		$this->plan_model->check_activation_code();
		$this->plan_model->activate_school_account(school_id);
		$this->session->set_flashdata('status_msg', 'Account activated successfully.');
		redirect(site_url('plan/account_info'));
	}




	/* ========== Activate Free Trial Coupon ========== */
	public function activate_coupon_ajax() { 
		$this->form_validation->set_rules('coupon_code', 'Coupon Code', 'trim|required');
		if ($this->form_validation->run())  {	
			$coupon_code = $this->input->post('coupon_code', TRUE);
			$coupon_code = strtoupper($coupon_code);
			//check if coupon code exists
			$query = $this->coupon_model->get_free_trial_coupon_details_by_code($coupon_code);

			if ($query) { //coupon code exists
				//check if coupon is still valid
				$coupon_details = $this->coupon_model->get_free_trial_coupon_details_by_code($coupon_code);
				$valid_until = $coupon_details->valid_until;
				//get current date (& time)
				$now = date('Y-m-d H:i:s');

				if ($valid_until >= $now) { //coupon is still valid
					$this->plan_model->activate_coupon();
					echo 1;
				} else { //coupon has expired
					echo "Sorry, this coupon code has expired!";
				}
			} else { //coupon code does not exists
				echo "Invalid coupon code!";
			}

		} else { 
			echo validation_errors();
		}
	}






}
	
