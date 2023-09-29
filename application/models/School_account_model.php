<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: School_account_model
Role: Model
Description: Controls the DB processes of school accounts in super admin dashboard
Controller: School_account
Author: Nwankwo Ikemefuna
Date Created: 16th August, 2018
*/

class School_account_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);
	}





	public function get_school_info($school_id) { 
		return $this->db->get_where('school_info', array('id' => $school_id))->row();
	}
	
	
	

	/* ===== Stats ===== */
	public function school_stats_data() { 
		//schools
		$data['total_lite_users'] = count($this->get_schools_by_plan_id(1));
		$data['total_pro_users'] = count($this->get_schools_by_plan_id(2));
		$data['total_pro_plus_users'] = count($this->get_schools_by_plan_id(3));
		$data['total_free_trial_schools'] = count($this->get_free_trial_schools());
		$data['total_expired_free_trial_schools'] = count($this->get_expired_free_trial_schools());
		$data['total_expired_annual_subscription_schools'] = count($this->get_expired_annual_subscription_schools());
		$data['total_activated_schools'] = count($this->get_all_activated_schools());
		$data['total_schools'] = count($this->get_all_schools());
		//users
		$data['total_admins'] = count($this->get_all_admins());
		$data['total_staff'] = count($this->get_all_staff());
		$data['total_students'] = count($this->get_all_students());
		$data['total_parents'] = count($this->get_all_parents());
		$data['total_chief_admins'] = count($this->school_account_model->get_all_chief_admins());
		return $data;
	}


	public function get_schools_by_plan_id($plan_id) { 
		return $this->db->get_where('school_info', array('plan_id' => $plan_id))->result();
	}


	public function get_schools_by_mode($mode) { 
		return $this->db->get_where('school_info', array('mode' => $mode))->result();
	}


	public function get_all_activated_schools() { 
		$annual_subscription_period = annual_subscription_period;
		//query clause: where activated is true and date activated is greater than (today - annual_subscription_period days)
		$where = 	"activated = 'true' AND 
					date_activated > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$annual_subscription_period} DAY)";
	    $this->db->where($where);
		return $this->db->get('school_info')->result();
	}


	public function get_free_trial_schools() {
		$free_trial_period = free_trial_period;
		//query clause: where activated is false, mode is Free Trial, and date installed is  not less than (today - free_trial_period days)
	    $where = 	"activated = 'false' AND 
	    			mode = 'Free Trial' AND 
	    			NOT (date_installed < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$free_trial_period} DAY) )";	
		$this->db->where($where);
		return $this->db->get('school_info')->result();		
	}


	public function get_expired_free_trial_schools() {
		$free_trial_period = free_trial_period;
		//query clause: where activated is false, mode is Free Trial, and date installed is less than (today - free_trial_period days)
	    $where = 	"activated = 'false' AND 
	    			mode = 'Free Trial' AND 
	    			date_installed < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$free_trial_period} DAY)";	
		$this->db->where($where);
		return $this->db->get('school_info')->result();		
	}


	public function get_expired_annual_subscription_schools() { 
		$annual_subscription_period = annual_subscription_period;
		//query clause: where activated is true and date activated is less than (today - annual_subscription_period days)
		$where = 	"activated = 'true' AND 
					date_activated < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$annual_subscription_period} DAY)";
	    $this->db->where($where);
		return $this->db->get('school_info')->result();
	}


	public function get_all_schools() { 
		return $this->db->get_where('school_info')->result();
	}



	/* ========== All School Users ========= */
	public function get_all_admins() { 
		return $this->db->get_where('admins')->result();
	}


	public function get_all_staff() {
		return $this->db->get_where('staff')->result();
	}


	public function get_all_students() { 
		return $this->db->get_where('students')->result();
	}


	public function get_all_parents() { 
		return $this->db->get_where('parents')->result();
	}


	public function get_all_chief_admins() { //get all school admins (who registered account)
		return $this->db->get_where('admins', array('chief_admin' => 'true'))->result();
	}



	public function school_users($school_id) {
		$total_admins = count($this->common_model->get_admins($school_id));
		$total_staff = count($this->common_model->get_staff($school_id));
		$total_students = count($this->common_model->get_students($school_id));
		$total_parents = count($this->common_model->get_parents($school_id));
		return  	'Admins: ' . $total_admins . '<br />
					Staff: ' . $total_staff . '<br />
					Students: ' . $total_students . '<br />
					Parents: ' . $total_parents;
	}



	
	

	/* ===== Email Confirmation===== */	
	public function resend_email_confirmation($school_id) {
    	$y = $this->get_school_info($school_id);
		$school_name = $y->school_name;
		$chief_admin_details = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
		$email = $chief_admin_details->email;
		$name = $chief_admin_details->name;

		//generate confirm code and update it against school's record
    	$confirm_code = hash('ripemd128', mt_rand(100000000000, 999999999999));
		$data = array (
			'confirm_code' => $confirm_code		 
		);		 
        $this->db->where('id', $school_id);			
		$this->db->update('school_info', $data);

		//send confirmation email to chief admin
		$confirm_url = base_url('email_confirmation/'. $school_id .'/'. $confirm_code);
		$anchor_link = email_call2action_blue($confirm_url, 'Confirm Account');	

		$subject = 'Account Confirmation';
		$message = 'Hi ' . $name . ', 
					<br />
					You requested for resend of confirmation for your school account. Click on the button below to confirm your account. <br />' 
					. $anchor_link . 
					'<br /><br />
					If you encounter any problem, do not hesitate to reach us on ' . software_support_mail .
					'<br /><br />
					The ' . software_team;
		return email_user_default($email, $subject, $message); 
    } 


    public function confirm_school_account($school_id) {
		$data = array (
			'confirmed' => 'true',		 
			'confirm_code' => NULL,		 
		);		 
        $this->db->where('id', $school_id);			
		$this->db->update('school_info', $data);
	}



	/* ===== Account Activation ===== */	
	public function activate_school_account($school_id) {
		$data = array(
			'mode' => 'Paid',
			'activated' => 'true',
			'date_activated' => date('Y-m-d H:i:s'),
			'show_activation_code' => 'true'
		);
		$this->db->where('id', $school_id);
		$this->db->update('school_info', $data);	

		//remove from coupon list (if exists)
		$this->coupon_model->delete_school_free_trial_coupon($school_id);
    } 


    public function deactivate_school_account($school_id) {
		$data = array(
			'activated' => 'false',
			'date_activated' => NULL,
			'show_activation_code' => 'false',
		);
		$this->db->where('id', $school_id);
		return $this->db->update('school_info', $data);	
    } 


    public function send_activation_code($school_id) {
		$data = array(
			'show_activation_code' => 'true',
		);
		$this->db->where('id', $school_id);
		$this->db->update('school_info', $data);	

		//email activation code to chief admin
		$this->email_activation_code($school_id);
    } 


    private function email_activation_code($school_id) {
    	//get chief admin info
		$y = $this->school_account_model->get_school_info($school_id);
		$activation_code = $y->activation_code;
		$plan = $this->common_model->get_plan_details($y->plan_id)->plan;
		$chief_admin_details = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
		$email = $chief_admin_details->email;
		$name = $chief_admin_details->name;
		
		$redirect_url = '<a href="' . base_url('activate') . '>dashboard</a>';
		$subject = 'Account Activation Code';
		$message = 'Hi ' . $name . ', 
					<br />
					Your payment for ' . $plan . ' plan has been confirmed. 
					<br />
					Your activation code is: ' . $activation_code . ' 
					<br />
					Proceed to your ' . $redirect_url . ' to activate your account.
					<br /><br />
					If you encounter any problem, do not hesitate to reach us on ' . software_support_mail .
					'<br />
					We greatly appreciate your patronage, and promise not to disappoint you in the course of our partnership.
					<br /><br />
					The ' . software_team;
		return email_user_default($email, $subject, $message); 
    } 
	
	

	/* ===== Account Upgrade ===== */	
	public function send_upgrade_code($school_id) {
		$data = array(
			'show_upgrade_code' => 'true',
		);
		$this->db->where('id', $school_id);
		$this->db->update('account_upgrades', $data);	

		//email upgrade code to chief admin
		$this->email_upgrade_code($school_id);
    } 


    private function email_upgrade_code($school_id) { //uncompleted
    	//get chief admin info
		$y = $this->school_account_model->get_school_info($school_id);
		$upgrade_code = $y->upgrade_code;
		$plan = $this->common_model->get_plan_details($y->plan_id)->plan;
		$chief_admin_details = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
		$email = $chief_admin_details->email;
		$name = $chief_admin_details->name;
		
		$redirect_url = '<a href="' . base_url('activate') . '>dashboard</a>';
		$subject = 'Account Activation Code';
		$message = 'Hi ' . $name . ', 
					<br />
					Your payment for ' . $plan . ' plan has been confirmed. 
					<br />
					Your activation code is: ' . $activation_code . ' 
					<br />
					Proceed to your ' . $redirect_url . ' to upgrade your account.
					<br /><br />
					If you encounter any problem, do not hesitate to reach us on ' . software_support_mail .
					'<br />
					We greatly appreciate your patronage, and promise not to disappoint you in the course of our partnership.
					<br /><br />
					The ' . software_team;
		return email_user_default($email, $subject, $message); 
    } 




    /* ===== Misc. Actions ===== */	
    public function check_extend_date_not_past($extend_date) {
		//remove hyphen from date so that we can get date as a numerical value 
		$extend_date = str_replace("-", "", $extend_date); //now yyyymmdd
		//get today's date in the same yyyymmdd format
		$today = date('Ymd');
		//check if today is less than or equals extend date 
		return ($today <= $extend_date) ? TRUE : FALSE;
    } 


	public function extend_free_trial_period($school_id) {
		$y = $this->school_account_model->get_school_info($school_id);
		$extend_date = $this->input->post('extend_date', TRUE);	
		//get difference (in days) between date installed and extend date (TRUE for absolute result)
		$date_installed = new DateTime($y->date_installed);
		$extend_date = new DateTime($extend_date);
		$difference = $date_installed->diff($extend_date);
		//diff in days
		$diff_days = $difference->days;
		//subtract free trial period days from diff days
		$days = $diff_days - free_trial_period;
		//add diff days value to date installed
		//first, check if days is less than 0. If so, get absolute value and subtract from date installed
		if ($days < 0) {
			$days = $days * -1; //get absolute value of days
			$new_date_installed = date('Y-m-d H:i:s', strtotime($y->date_installed. " - {$days} days"));
		} else { //days is >= 0, add to date installed
			$new_date_installed = date('Y-m-d H:i:s', strtotime($y->date_installed. " + {$days} days"));
		}
		$data = array(
			'date_installed' => $new_date_installed,
		);
		$this->db->where('id', $school_id);
		$this->db->update('school_info', $data);	
    } 


    public function extend_subscription_period($school_id) {
		$y = $this->school_account_model->get_school_info($school_id);
		$extend_date = $this->input->post('extend_date', TRUE);	
		//get difference (in days) between date installed and extend date (TRUE for absolute result)
		$date_activated = new DateTime($y->date_activated);
		$extend_date = new DateTime($extend_date);
		$difference = $date_activated->diff($extend_date);
		//diff in days
		$diff_days = $difference->days;
		//subtract annual subscription period days from diff days
		$days = $diff_days - annual_subscription_period;
		//add diff days value to date installed
		//first, check if days is less than 0. If so, get absolute value and subtract from date installed
		if ($days < 0) {
			$days = $days * -1; //get absolute value of days
			$new_date_activated = date('Y-m-d H:i:s', strtotime($y->date_activated. " - {$days} days"));
		} else { //days is >= 0, add to date installed
			$new_date_activated = date('Y-m-d H:i:s', strtotime($y->date_activated. " + {$days} days"));
		}
		$data = array(
			'date_activated' => $new_date_activated,
		);
		$this->db->where('id', $school_id);
		$this->db->update('school_info', $data);	
    } 


	public function message_chief_admin($school_id) {
		$message = nl2br(ucfirst($this->input->post('message', TRUE))); 
		$subject = 'Message from Admin';
		$y = $this->school_account_model->get_school_info($school_id);
		$email = $this->common_model->get_admin_details_by_id($y->chief_admin_id)->email;
		return email_user_default($email, $subject, $message); 
    } 


    public function check_activation_status($school_id) {
    	//used to check that school is not activated during delete/erase
		$y = $this->school_account_model->get_school_info($school_id);
		if ($y->activated != 'true') { //school is not activated, we're good to clean the board...
			return TRUE;
		} else { //ooops! that's probably a bad idea...
			$this->session->set_flashdata('status_msg_error', "{$y->school_name} is currently activated on a plan. If you are sure about this, de-activate the account and retry.");
			redirect($this->agent->referrer());
		}
    } 
	
	
	public function get_school_data_records($school_id) {
		$total_rows = $this->common_model->get_school_data_records($school_id);
		return number_format($total_rows);
    } 
	
	
	public function erase_school_data($school_id) {
		//get all tables
		$tables = $this->db->list_tables();
		foreach ($tables as $table) {	
			$fields = $this->db->list_fields($table);
			//get tables to be excluded
			$exclude_tables = $this->exclude_tables();
			//check if table has school ID column
			$has_school_id = $this->db->field_exists('school_id', $table);
			//ensure table is not in the list of excluded tables before deleting
			if ( $has_school_id && (! in_array($table, $exclude_tables)) ) { //yes sir
				//delete every record associated with this school....		
				$this->delete_school_data($table, $school_id);
			} 
		}
    } 
	
	
	public function delete_school_account($school_id) {
		//get all tables
		$tables = $this->db->list_tables();
		foreach ($tables as $table) {	
			$fields = $this->db->list_fields($table);
			//check if table has school ID column
			$has_school_id = $this->db->field_exists('school_id', $table);
			if ($has_school_id) { //yes sir
				//delete every record associated with this school....
				$this->delete_school_data($table, $school_id);
				//we hate to see you go, but we don't really...
				$this->db->delete('school_info', array('id' => $school_id));
			} 
		}
    } 
	
	
	private function delete_school_data($table, $school_id) {
		return $this->db->delete($table, array('school_id' => $school_id));
    } 
	
	
	private function exclude_tables() {
		//get array of tables to be excluded 
		$exclude_tables = array(
			'admins',
			'aptitudes',
			'term_info',
			'report_settings',
			'report_evaluation',
		);
		return $exclude_tables;
    } 




}