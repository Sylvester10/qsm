<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Cron_model
Role: Model
Description: Controls the DB processes of scheduled tasks (cron jobs)
Controller: Cron
Author: Nwankwo Ikemefuna
Date Created: 1st October, 2018
*/

class Cron_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('school_account_model');
		//enable/disable email alert for developer
		$this->notify_developer = true;
		$this->developer_email = developer_email;
	}
	
	
	
	private function email_developer($subject, $message) {
		//email developer
		if ($this->notify_developer == true) {
			return email_user_default($this->developer_email, $subject, $message);
		}	
	}
	

	
	
	/* ======== User Triggered =========== */
	
	
	
	/* Welcome Message: Upon Signup */
	public function signup_welcome_message($school_id, $additional_message) {
		$y = $this->common_model->get_school_info($school_id);
		$school_name = $y->school_name;
		$date_installed = $y->date_installed;
		$plan = $this->common_model->get_plan_details($y->plan_id)->plan;
		$chief_admin_details = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
		$email = $chief_admin_details->email;
		$name = $chief_admin_details->name;
		$login_url = base_url('login');

		$subject = 'Welcome to ' . software_name . '!';
		$confirm_status = '<span style="color: red">Unconfirmed</span>';
		$message = cron_welcome_message($name, $school_name, $confirm_status, $plan, $email, $login_url, $additional_message);
					
		//email chief admin
		email_user_default($email, $subject, $message);
	}
	
	
	private function email_confirmation_link($school_id) {
		$y = $this->common_model->get_school_info($school_id);
		$confirm_url = base_url('site/email_confirmation/'. $school_id .'/'. $y->confirm_code);
		$anchor_link = email_call2action_blue($confirm_url, 'Confirm Email');	
		return 	'<p>
					Confirm your email to begin using your account.'
					. $anchor_link . 
				'</p>';
    }  
	
	
	public function email_chief_admin_on_free_trial($school_id) {
		$y = $this->common_model->get_school_info($school_id);
		$expiration_date = get_expiration_date($y->date_installed);
		$additional_message = 'You are currently on a ' . free_trial_period . '-day FREE trial which will expire on  ' . $expiration_date . '.';
		$additional_message .= $this->email_confirmation_link($school_id);
		//call welcome message
    	$this->signup_welcome_message($school_id, $additional_message);
    }  
	
	
	public function email_chief_admin_on_buy($school_id) {
		$confirm_url = base_url('site/email_confirmation/'. $school_id .'/'. $y->confirm_code);
		$anchor_link = email_call2action_blue($confirm_url, 'Confirm Email');	
		$additional_message = 'Your payment via PayPal was successful. Your school account has been activated.';
		$additional_message .= $this->email_confirmation_link($school_id);
		//call welcome message
    	$this->signup_welcome_message($school_id, $additional_message);
    }  
	
	
	public function email_chief_admin_on_buy_other($school_id) {
		$y = $this->common_model->get_school_info($school_id);
		$confirm_url = base_url('site/email_confirmation/'. $school_id .'/'. $y->confirm_code);
		$anchor_link = email_call2action_blue($confirm_url, 'Confirm Email');	
		$additional_message = 'We urge you to expedite your account activation by making payment now in order to enjoy the full benefits of this software. You account will be activated as soon as we can confirm your payment.';
		$additional_message .= $this->email_confirmation_link($school_id);
		//call welcome message
    	$this->signup_welcome_message($school_id, $additional_message);
    } 
	
	
	
	/* Welcome Message: Upon Email Confirmation */
	public function email_chief_admin_on_email_confirmation($school_id) {
		$y = $this->common_model->get_school_info($school_id);
		$school_name = $y->school_name;
		$date_installed = $y->date_installed;
		$plan = $this->common_model->get_plan_details($y->plan_id)->plan;
		$chief_admin_details = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
		$email = $chief_admin_details->email;
		$name = $chief_admin_details->name;
		$login_url = base_url('login');

		$subject = 'Email Confirmation Successful';
		$confirm_status = '<span style="color: green">Confirmed</span>';
		$additional_message = 'Your email has been confirmed. You may now login to your account.';
		$message = cron_welcome_message($name, $school_name, $confirm_status, $plan, $email, $login_url, $additional_message);
					
		//email chief admin
		email_user_default($email, $subject, $message);
	}
	
	
	
	
	
	/* ======== Scheduled (Auto Triggered) =========== */
	
	
	/*  Database Backup */
	//When: everyday
	//Frequency: 2 times daily
	//Total: indefinite
	public function get_database_backup_info() {	
		return $this->db->get_where('db_backup_info', array('id' => 1))->row();
	}
	 
	
	public function update_database_backup_info() {	
		$data = array(
			'last_backup_date' => date('Y-m-d H:i:s'),
		);
		$this->db->where('id', 1);
		return $this->db->update('db_backup_info', $data);
	}


	
	
	
	
	
	/* Signup without Email Confirmation: */
	//When: 24 hrs after signup and email is unconfirmed
	//Frequency: 24 hrs, 48 hours, 72 hours 
	//Total: 3   
	public function get_unconfirmed_email_accounts() {
		$where = 	"confirmed = 'false' AND 
					date_installed BETWEEN 
						DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 73 HOUR)
						AND
						DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 24 HOUR)";
	    $this->db->where($where);
	    $this->db->order_by('date_installed', 'desc');
		$query = $this->db->get('school_info')->result();
		return $query;	
	}
	


	
	/* Getting started with Quick School Manager */
	//When: 24 hrs after a user signs up and has been confirmed
	//Frequency: Day 1, Day 7, Day 14, Day 21
	//Total: 4
	public function get_passive_confirmed_accounts() {
		$where = 	"confirmed = 'true' AND 
					date_installed BETWEEN 
						DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 22 DAY)
						AND
						DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 DAY)";
	    $this->db->where($where);
	    $this->db->order_by('date_installed', 'desc');
		$query = $this->db->get('school_info')->result();
		return $query;	
	}
	
	
	
	
	/* FREE TRIAL ACCOUNT WITHOUT ACTIVITY */
	//When: 3 days after signing up, A week later, Another week later (if there is still no activity)
	//Frequency: Day 3, Day 10, Day 17
	//Total: 3
	public function get_passive_free_trial_accounts() {
		$where = 	"mode = 'Free Trial' AND 
					activated = 'false' AND 
					date_installed BETWEEN 
						DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 18 DAY)
						AND
						DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 3 DAY)";
	    $this->db->where($where);
	    $this->db->order_by('date_installed', 'desc');
		$query = $this->db->get('school_info')->result();
		return $query;	
	}
	
	
	
	
	
	/* PAID SUBSCRIPTION ACCOUNT WITHOUT ACTIVITY */
	//When: 3 days after signing up, A week later, Another week later (if there is still no activity)
	//Frequency: Day 3, Day 10, Day 17
	//Total: 3
	public function get_passive_activated_accounts() {
		$where = 	"activated = 'true' AND 
					date_installed BETWEEN 
						DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 18 DAY)
						AND
						DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 3 DAY)";
	    $this->db->where($where);
	    $this->db->order_by('date_installed', 'desc');
		$query = $this->db->get('school_info')->result();
		return $query;	
	}
	
	
	
	
	/* EXPIRING FREE TRIAL ACCOUNTS */
	//When: 10 days to expiry, 8 days later.
	//Frequency: Day 20, Day 28
	//Total: 2
	public function get_expiring_free_trial_accounts() {
		$where = 	"mode = 'Free Trial' AND 
					activated = 'false' AND 
					date_installed BETWEEN 
						DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 29 DAY)
						AND
						DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 20 DAY)";
	    $this->db->where($where);
	    $this->db->order_by('date_installed', 'desc');
		$query = $this->db->get('school_info')->result();
		return $query;	
	}
	
	
	
	
	/* EXPIRED FREE TRIAL ACCOUNTS */
	//When: Day 1, Day 7, Day 14 after expiration, etc
	//Frequency: Day 31, Day Day 38, Day 45
	//Total: 3
	public function get_expired_free_trial_accounts() {
		$free_trial_period = free_trial_period;
		$start_date = $free_trial_period + 1;
		$end_date = $free_trial_period + 14;
		$where = 	"mode = 'Free Trial' AND 
					activated = 'false' AND 
					date_installed BETWEEN 
						DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$end_date} DAY)
						AND
						DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$start_date} DAY)";
	    $this->db->where($where);
	    $this->db->order_by('date_installed', 'desc');
		$query = $this->db->get('school_info')->result();
		return $query;	
	}




	/* EXPIRED FREE TRIAL ACCOUNTS: Greater than 1 Month */
	//When: Month 1, Month 2, Month 3 after expiration, etc
	//Frequency: Every month for 1 year
	//Total: 12
	public function get_expired_free_trial_monthly_accounts() {
		$free_trial_period = free_trial_period;
		$start_date = $free_trial_period + 31;
		$end_date = $free_trial_period + 366;
		$where = 	"mode = 'Free Trial' AND 
					activated = 'false' AND 
					date_installed BETWEEN 
						DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$end_date} DAY)
						AND
						DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$start_date} DAY)";
	    $this->db->where($where);
	    $this->db->order_by('date_installed', 'desc');
		$query = $this->db->get('school_info')->result();
		return $query;	
	}


	
	
	/* Cron status info */
	public function update_cron_daemon($name, $interval) {
		$last_run = date('Y-m-d H:i:s');
		//to get next run, add interval to last run
		$next_run = strtotime("+{$interval}", strtotime($last_run));
		$next_run = date('Y-m-d H:i:s', $next_run);
		$data = array(
			'last_run' => date('Y-m-d H:i:s'),
			'next_run' => $next_run,
		);
		$this->db->where('name', $name);
		return $this->db->update('cron_daemons', $data);
	}

	
	public function save_cron_job_db($name, $title, $status) {	
		$data = array(
			'name' => $name,
			'title' => $title,
			'status' => $status,
		);
		return $this->db->insert('cron_jobs_db', $data);
	}


	public function save_cron_job_school_data($name, $title, $school_id, $status) {
		$data = array(
			'name' => $name,
			'title' => $title,
			'school_id' => $school_id,
			'status' => $status,
		);
		return $this->db->insert('cron_jobs_school_data', $data);
	}




}