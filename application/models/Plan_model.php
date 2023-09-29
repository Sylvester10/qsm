<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Plan_model
Role: Model
Description: Controls the DB processes of plans
Controller: Plan
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/

class Plan_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
	}
	
	
	
	
	public function activate_school_account($school_id) {
		$data = array(
			'mode' => 'Paid',
			'activated' => 'true',
			'date_activated' => date('Y-m-d H:i:s'),
			'show_activation_code' => 'true',
		);
		$this->db->where('id', $school_id);
		$this->db->update('school_info', $data);	
 
		//email chief admin
		$this->email_chief_admin_on_activation();

		//remove from coupon list (if exists)
		$this->coupon_model->delete_school_free_trial_coupon($school_id);
    } 


    public function check_activation_code() {
		//ensure payment has been made i.e. user has been sent activation code
		$show_activation_code = show_activation_code;
		if ($show_activation_code == 'true') { 
			return TRUE;
		} else {
			$this->session->set_flashdata('status_msg_error', 'Payment not confirmed.');
			//redirect to activate page
			redirect(site_url('activate'));
		}
	}


    private function email_chief_admin_on_activation() {
    	$plan = school_plan;
		$email = chief_admin_email;
		$name = chief_admin_name;
		$first_name = get_firstname($name);

		$subject = 'Payment Confirmation';
		$message = 'Hi ' . $first_name . ', <br />
					Your payment for ' . $plan . ' plan via PayPal was successful. Your school account has been activated.
					<br /><br />
					If you encounter any problem, do not hesitate to reach us on ' . software_support_mail .
					'<br />
					We greatly appreciate your patronage, and promise not to disappoint you in the course of our partnership.
					<br /><br />
					The ' . software_team;
		return email_user_default($email, $subject, $message); 
    } 
	
	
	public function switch_plan() {
		$data = array(
			'plan_id' => $this->input->post('plan_id', TRUE)
		);
		$school_id = school_id;
		$this->db->where('id', $school_id);
		return $this->db->update('school_info', $data);	
    } 
	
	
	public function check_status_on_plan_change() {
		//check if account has not been activated, if true, allow plan change, else redirect to upgrade page
		$status = school_account_activation_status;
		$plan_id = school_plan_id;
		if ($status != 'true') { 
			return TRUE;
		} else {
			if ($plan_id == 3) { //highest plan
				$this->session->set_flashdata('status_msg_error', 'Your account has been activated. Plan change not necessary.');
			} else {
				$this->session->set_flashdata('status_msg_error', 'Your account has been activated. If you wish to upgrade to a higher plan, you may do so here.');
			} 
			//redirect to account info page
			redirect(site_url('plan/account_info'));
		}
	}


	public function activate_coupon() {
		$coupon_code = $this->input->post('coupon_code', TRUE);
		$coupon_code = strtoupper($coupon_code);
		//get coupon ID
		$coupon_details = $this->coupon_model->get_free_trial_coupon_details_by_code($coupon_code);
		$coupon_id = $coupon_details->id;
		$data = array(
			'school_id' => school_id,
			'coupon_id' => $coupon_id,
		);
		return $this->db->insert('coupon_users_free_trial', $data);	
    } 


	public function get_upgrade_plans() { 
		$this->db->order_by('id', 'asc');
		$plan_id = school_plan_id;
		//remove school's current plan as well as the least plan from query
		$where = array(
			'id !=' => $plan_id,
		);
		$this->db->where($where);
		return $this->db->get('plans')->result();
	}


	public function get_additional_amount($new_plan_id) {
		if (school_account_activation_status == 'true') {
			$plan_id = school_plan_id;
			//get plan annual amount 
			$current_plan_amount = $this->common_model->get_plan_price_digit_by_location_no_format($plan_id);	
			$new_plan_amount = $this->common_model->get_plan_price_digit_by_location_no_format($new_plan_id);
			$additional_amount = $new_plan_amount - $current_plan_amount;
		} else {
			$additional_amount = NULL;
		}
		return $additional_amount;
	}
	
	
	public function get_additional_dollar_amount($new_plan_id) {
		if (school_account_activation_status == 'true') {
			$plan_id = school_plan_id;
			//get plan annual amount 
			$current_plan_amount = $this->common_model->get_plan_details($plan_id)->price_dollar;	
			$new_plan_amount = $this->common_model->get_plan_details($new_plan_id)->price_dollar;
			$additional_amount = $new_plan_amount - $current_plan_amount;
		} else {
			$additional_amount = NULL;
		}
		return $additional_amount;
	}


	public function get_upgrade_request() { 
		$this->db->order_by('date_initiated', 'desc'); //get most recent
		$where = array(
			'school_id' => school_id,
		);
		$this->db->where($where);
		return $this->db->get('account_upgrades')->row();
	}


	public function initiate_upgrade($data = array()) { 
		//initiate upgrade using credit card payment
		return $this->db->insert('account_upgrades', $data);
	}


	public function update_plan_on_upgrade($data = array()) { 
		//initiate upgrade using credit card payment when upgrade has already been initiated
		$school_id = school_id;
		$this->db->where('school_id', $school_id);
		return $this->db->update('account_upgrades', $data);
	}


	public function upgrade_school_account($school_id, $upgrade_plan_id) {
		$data = array(
			'plan_id' => $upgrade_plan_id,
			'last_upgrade' => date('Y-m-d H:i:s'),
		);
		$this->db->where('id', $school_id);
		$this->db->update('school_info', $data);

		//delete from account upgrades
		$this->delete_account_upgrade_request();

		//email chief admin
		$this->email_chief_admin_on_upgrade();
    } 


	public function initiate_upgrade_other() { 
		//initiate upgrade using other modes of payment
		$upgrade_plan_id = $this->input->post('plan_id', TRUE);
		//get upgrade amount
		$upgrade_amount = $this->plan_model->get_additional_amount($upgrade_plan_id);
		$currency_code = $this->common_model->get_currency_by_location();
		$data = array(
			'school_id' => school_id,
			'current_plan_id' => school_plan_id,
			'upgrade_plan_id' => $upgrade_plan_id,
			'upgrade_amount' => $upgrade_amount,
			'currency_code' => $currency_code,
			'upgrade_code' => $this->generate_upgrade_code($upgrade_plan_id),
			'show_upgrade_code' => 'false', //Important, else users can upgrade themselves without paying
		);
		$this->db->insert('account_upgrades', $data);

		//email chief admin
		$this->email_chief_admin_on_upgrade_other();
	}


	public function change_upgrade_plan_other() { 
		$upgrade_plan_id = $this->input->post('plan_id', TRUE);
		$data = array(
			'upgrade_plan_id' => $upgrade_plan_id,
		);
		$school_id = school_id;
		$this->db->where('school_id', $school_id);
		$this->db->update('account_upgrades', $data);
	}


	public function upgrade_school_account_other($upgrade_plan_id) {
		$data = array(
			'plan_id' => $upgrade_plan_id,
			'last_upgrade' => date('Y-m-d H:i:s'),
		);
		$school_id = school_id;
		$this->db->where('id', $school_id);
		$this->db->update('school_info', $data);

		//delete from account upgrades
		$this->delete_account_upgrade_request();
    } 


    public function check_upgrade_code() {
		//ensure payment has been made i.e. user has been sent upgrade code
		$upgrade_request = $this->plan_model->get_upgrade_request();
		$show_upgrade_code = $upgrade_request->show_upgrade_code;
		if ($show_upgrade_code == 'true') { 
			return TRUE;
		} else {
			$this->session->set_flashdata('status_msg_error', 'Payment not confirmed.');
			//redirect to upgrade page
			redirect(site_url('upgrade')); //change later 
		}
	}


	public function generate_upgrade_code($plan_id) { 
		//get software initials
		$software_initials = strtoupper(software_initials);
		//generate random 9-digit code
		$code = mt_rand(111111111, 999999999);
		//Add a zero to plan ID
		$plan_id = 0 . $plan_id; //eg 02 
		//append hyphen + letter U (for upgrade) to new plan ID
		$plan_id = $plan_id . '-U'; //eg 02-U
		//concat software initials, plan ID and generated code
		$upgrade_code = $software_initials . $plan_id . $code; // eg QSM02-U320526854
		return $upgrade_code;
	}


	public function delete_account_upgrade_request($school_id) { 
		return $this->db->delete('account_upgrades', array('school_id' => $school_id));
	}


	private function email_chief_admin_on_upgrade() {
    	$email = chief_admin_email;
		$name = chief_admin_name;
		$first_name = get_firstname($name);

		$subject = 'Payment Confirmation';
		$message = 'Hi ' . $first_name . ', <br />
					Your payment for plan upgrade via PayPal was successful. Your school account has been upgraded.
					<br /><br />
					If you encounter any problem, do not hesitate to reach us on ' . software_support_mail .
					'<br />
					We greatly appreciate your patronage, and promise not to disappoint you in the course of our partnership.
					<br /><br />
					The ' . software_team;
		return email_user_default($email, $subject, $message); 
    } 


    private function email_chief_admin_on_upgrade_other() {
    	$email = chief_admin_email;
		$name = chief_admin_name;
		$first_name = get_firstname($name);

		$subject = 'Welcome!';
		$message = 'Hi ' . $first_name . ', 
					<br />
					Your request for account upgrade was successful.
					<br />
					We urge you to expedite your account upgrade by making payment now in order to enjoy the full benefits of this software.
					You will receive your upgrade code as soon as we can confirm your payment.
					<br /><br />
					If you encounter any problem, do not hesitate to reach us on ' . software_support_mail .
					'<br />
					We greatly appreciate your patronage, and promise not to disappoint you in the course of our partnership.
					<br /><br />
					The ' . software_team;
		return email_user_default($email, $subject, $message); 
	}
	

}