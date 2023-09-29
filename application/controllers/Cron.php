<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Data_management
Role: Controller
Description: Cron Class manages scheduled tasks
Model: Cron
Author: Nwankwo Ikemefuna
Date Created: 23rd August, 2018
*/



class Cron extends MY_Controller { 
	public function __construct() {
		parent::__construct();
		$this->load->model('cron_model');
		$this->load->model('coupon_model'); 
		//enable/disable email alert for developer
		$this->notify_developer = true;
		$this->developer_email = developer_email; 
		//backup email
		$this->backup_email = developer_email;
		
		//initial school records (this could change)
		$this->initial_school_records = 27;
		$this->initial_school_records += 3; //small window incase school added few extra records and abandons account

		//command line only
		//$this->cli_only(); 
	}



	private function cli_only() {
		if ( $this->input->is_cli_request() ) {
			return TRUE;
		} else {
			show_error('Direct URI access not allowed!');
		}
	}
	
	
	private function email_user_db($email, $subject, $message, $cron_name, $title) {
		if ( email_user_default($email, $subject, $message) ) {
			//save cron job to database: email successful
			$status = 'Successful';
			$this->cron_model->save_cron_job_db($cron_name, $title, $status);
		} else {
			//save cron job to database: email failed
			$status = 'Failed';
			$this->cron_model->save_cron_job_db($cron_name, $title, $status);
		}
	}


	private function email_user_school_data($email, $subject, $message, $cron_name, $title, $school_id) {
		if ( email_user_default($email, $subject, $message) ) {
			//save cron job to database: email successful
			$status = 'Successful';
			$this->cron_model->save_cron_job_school_data($cron_name, $title, $school_id, $status);
		} else {
			//save cron job to database: email failed
			$status = 'Failed';
			$this->cron_model->save_cron_job_school_data($cron_name, $title, $school_id, $status);
		}
	}
	
	
	
	/*  Database Backup */
	//When: everyday
	//Frequency: 2 times daily
	//Total: indefinite
	public function backup_database() { 

		$prefs = array(
			'tables'        => array(),   					// All tables
			'ignore'        => array(),                     // List of tables to omit from the backup
			'format'        => 'zip',                       // gzip, zip, txt
			'filename'      => 'qsm_backup.sql',            // File name - NEEDED ONLY WITH ZIP FILES
			'add_drop'      => TRUE,                        // Whether to add DROP TABLE statements to backup file
			'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
			'newline'       => "\n"                         // Newline character used in backup file
		);
		
		$backup = $this->dbutil->backup($prefs);
		
		//name this backup using today's date
		$date = date('d-m-Y'); 
		$backup_name = $date . '-backup.zip';
		
		$file_path = 'assets/data/database_backups/' . $backup_name;
		
		//write file to server  
		write_file($file_path, $backup_name);
		
		//Update backup info
		$this->cron_model->update_database_backup_info();
		
		//email developer
		$download_link = base_url($file_path);
		$anchor_link = email_call2action_blue($download_link, 'Download Backup');	
		$subject = 'Database Backup Successful';
		$message = 	'Automatic database backup succeeded via CRON. <br />
					Download the latest backup file below. <br /><br />'
					. $anchor_link; 

		//get cron name
		$cron_name = $this->router->fetch_method();

		//send mail to backup email
		$title = 'Database Backup';
		$this->email_user_db($this->backup_email, $subject, $message, $cron_name, $title);

		//update cron daemon
		$interval = '12 hours';
		$this->cron_model->update_cron_daemon($cron_name, $interval);
	} 



	/* Signup without Email Confirmation: */
	//When: 24 hrs after signup and email is unconfirmed
	//Frequency: 24 hrs, 48 hours, 72 hours 
	//Total: 3   
	public function confirm_email() {

		//get schools who are yet to confirm email
		$schools = $this->cron_model->get_unconfirmed_email_accounts();
		foreach ($schools as $s) {
			
			$school_id = $s->id;
			$y = $this->common_model->get_school_info($school_id);
			$chief_admin_details = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
			$name = $chief_admin_details->name;
			$email = $chief_admin_details->email;
			
			$confirm_url = base_url('email_confirmation/'.$school_id.'/'.$y->confirm_code);
			$anchor_link = email_call2action_blue($confirm_url, 'Confirm Email');	
			
			$subject = 'Confirm your Email';
			$message = cron_confirm_email_message($name, $anchor_link);

			//get cron name
			$cron_name = $this->router->fetch_method();
			
			//email chief admin
			$title = 'Email Confirmation';
			$this->email_user_school_data($email, $subject, $message, $cron_name, $title, $school_id);

			//update cron daemon
			$interval = '24 hours';
			$this->cron_model->update_cron_daemon($cron_name, $interval);
			
		}

	}
	
	
	
	/* Getting started with Quick School Manager */
	//When: 24 hrs after a user signs up and has been confirmed
	//Frequency: Day 1, Day 7, Day 14, Day 21
	//Total: 4
	public function get_started_message() {

		//get schools who are have confirmed account but have not done basic setup
		$schools = $this->cron_model->get_passive_confirmed_accounts();
		foreach ($schools as $s) {
			
			$school_id = $s->id;
			$y = $this->common_model->get_school_info($school_id);
			$chief_admin_details = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
			$name = $chief_admin_details->name;
			$email = $chief_admin_details->email;
			
			$dash_link = base_url('admin');
			$anchor_link = email_call2action_blue($dash_link, 'Get Started');	

			$subject = 'Getting Started with ' . software_name . '!';
			$message = cron_get_started_message($name, $dash_link, $anchor_link);
			
			//check the data records
			$school_records = $this->common_model->get_school_data_records($school_id);
			//compare current school records with initial records (records inserted during registration)
			if ($school_records <= $this->initial_school_records) {
				//get cron name
				$cron_name = $this->router->fetch_method();

				//email chief admin
				$title = 'Getting Started with ' . software_name;
				$this->email_user_school_data($email, $subject, $message, $cron_name, $title, $school_id);

				//update cron daemon
				$interval = '7 days';
				$this->cron_model->update_cron_daemon($cron_name, $interval);
			}

		}

	}



	

	/* FREE TRIAL ACCOUNT WITHOUT ACTIVITY */
	//When: 3 days after signing up, A week later, Another week later (if there is still no activity)
	//Frequency: Day 3, Day 10, Day 17
	//Total: 3
	public function free_trial_no_activity() {

		//get schools who signed up for free trial but haven't been using account
		$schools = $this->cron_model->get_passive_free_trial_accounts();
		foreach ($schools as $s) {
			
			$school_id = $s->id;
			$y = $this->common_model->get_school_info($school_id);
			$school_name = $y->school_name;
			$chief_admin_details = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
			$name = $chief_admin_details->name;
			$email = $chief_admin_details->email;
			$expiration_date = get_expiration_date($y->date_installed);
			
			$login_url = base_url('login');
			$anchor_link = email_call2action_blue($login_url, 'Login Now');		

			$subject = software_name . ' Misses You!';
			$message = cron_free_trial_no_activity_message($name, $school_name, $expiration_date, $email, $login_url, $anchor_link);
			
			//check the data records
			$school_records = $this->common_model->get_school_data_records($school_id);
			//compare current school records with initial records (records inserted during registration)

			if ( $school_records <= $this->initial_school_records) {
				//get cron name
				$cron_name = $this->router->fetch_method();

				//email chief admin
				$title = 'Free Trial No Activity';
				$this->email_user_school_data($email, $subject, $message, $cron_name, $title, $school_id);

				//update cron daemon
				$interval = '7 days';
				$this->cron_model->update_cron_daemon($cron_name, $interval);
			}

		}

	}
	
	
	
	
	/* PAID SUBSCRIPTION ACCOUNT WITHOUT ACTIVITY */
	//When: 3 days after signing up, A week later, Another week later (if there is still no activity)
	//Frequency: Day 3, Day 10, Day 17
	//Total: 3
	public function paid_subscription_no_activity() {

		//get schools who have been activated but haven't been using account
		$schools = $this->cron_model->get_passive_activated_accounts();
		foreach ($schools as $s) {
			
			$school_id = $s->id;
			$y = $this->common_model->get_school_info($school_id);
			$school_name = $y->school_name;
			$chief_admin_details = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
			$name = $chief_admin_details->name;
			$email = $chief_admin_details->email;
			$date_activated = x_date_full($y->date_activated);
			
			$login_url = base_url('login');
			$anchor_link = email_call2action_blue($login_url, 'Login Now');		

			$subject = 'Need Help Setting Up?';
			$message = cron_paid_subscription_no_activity_message($name, $school_name, $date_activated, $email, $login_url, $anchor_link);
			
			//check the data records
			$school_records = $this->common_model->get_school_data_records($school_id);
			//compare current school records with initial records (records inserted during registration)
			if ($school_records <= $this->initial_school_records) {	
				//get cron name
				$cron_name = $this->router->fetch_method();

				//email chief admin
				$title = 'Paid Subscription No Activity';
				$this->email_user_school_data($email, $subject, $message, $cron_name, $title, $school_id);

				//update cron daemon
				$interval = '7 days';
				$this->cron_model->update_cron_daemon($cron_name, $interval);
			}

		}

	}
	
	
	
	/* EXPIRING FREE TRIAL ACCOUNTS */
	//When: 10 days to expiry, 8 days later.
	//Frequency: Day 20, Day 28
	//Total: 2
	public function expiring_free_trial() {

		//get Free Trial Coupon
		$coupon = $this->coupon_model->get_free_trial_coupon_by_name('Free Trial');
		$coupon_code = ($coupon) ? $coupon->code : NULL;

		//get schools whose free trial account are about to expire
		$schools = $this->cron_model->get_expiring_free_trial_accounts();
		foreach ($schools as $s) {
			
			$school_id = $s->id;
			$y = $this->common_model->get_school_info($school_id);
			$school_name = $y->school_name;
			$chief_admin_details = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
			$name = $chief_admin_details->name;
			$email = $chief_admin_details->email;
			$expiration_date = get_expiration_date($y->date_installed);
			
			$discount_url = base_url('activate');
			$anchor_link = email_call2action_blue($discount_url, 'Get 10% Off');		

			$subject = 'Get a 10% Discount on ' . software_name;
			$message = cron_expiring_free_trial_message($name, $expiration_date, $coupon_code, $discount_url, $anchor_link);

			//get cron name
			$cron_name = $this->router->fetch_method();
				
			//email chief admin
			$title = 'About to Expire Free Trial Accounts';
			$this->email_user_school_data($email, $subject, $message, $cron_name, $title, $school_id);

			//update cron daemon
			$interval = '8 days';
			$this->cron_model->update_cron_daemon($cron_name, $interval);

		}

	}



	/* EXPIRED FREE TRIAL ACCOUNTS */
	//When: Day 1, Day 7, Day 14 after expiration, etc
	//Frequency: Day 31, Day Day 38, Day 45
	//Total: 3
	public function expired_free_trial() {

		//get Free Trial Coupon
		$coupon = $this->coupon_model->get_free_trial_coupon_by_name('Free Trial');
		$coupon_code = ($coupon) ? $coupon->code : NULL;

		//get schools whose free trial account has expired for over 1 day
		$schools = $this->cron_model->get_expired_free_trial_accounts();
		foreach ($schools as $s) {
			
			$school_id = $s->id;
			$y = $this->common_model->get_school_info($school_id);
			$school_name = $y->school_name;
			$chief_admin_details = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
			$name = $chief_admin_details->name;
			$email = $chief_admin_details->email;
			$expiration_date = get_expiration_date($y->date_installed);
			//extend period = expiration date + 14 days (ie 30+14 = 44)
			$free_trial_period = free_trial_period;
			$extend_period = $free_trial_period + 14;
			$extend_date = strtotime("+{$extend_period} days", strtotime($y->date_installed));
			$extend_date = date('Y/m/d', $extend_date);	
			$extend_date = x_date_full($extend_date);

			$discount_url = base_url('activate');
			$anchor_link = email_call2action_blue($discount_url, 'Get 10% Off');		

			$subject = 'Get a 10% Discount on ' . software_name;
			$message = cron_expired_free_trial_message($name, $expiration_date, $extend_date, $coupon_code, $discount_url, $anchor_link);

			//get cron name
			$cron_name = $this->router->fetch_method();
				
			//email chief admin
			$title = 'Expired Free Trial Accounts';
			$this->email_user_school_data($email, $subject, $message, $cron_name, $title, $school_id);

			//update cron daemon
			$interval = '7 days';
			$this->cron_model->update_cron_daemon($cron_name, $interval);
			
		}

	}


	/* EXPIRED FREE TRIAL ACCOUNTS: Greater than 1 Month */
	//When: Month 1, Month 2, Month 3 after expiration, etc
	//Frequency: Every month for 1 year
	//Total: 12
	public function expired_free_trial_monthly() {

		//get schools whose free trial account has expired for over 1 month
		$schools = $this->cron_model->get_expired_free_trial_monthly_accounts();
		foreach ($schools as $s) {
			
			$school_id = $s->id;
			$y = $this->common_model->get_school_info($school_id);
			$school_name = $y->school_name;
			$chief_admin_details = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
			$name = $chief_admin_details->name;
			$email = $chief_admin_details->email;
			$expiration_date = get_expiration_date($y->date_installed);
			
			$activate_url = base_url('active');
			$anchor_link = email_call2action_blue($activate_url, 'Activate Now');		

			$subject = 'Don\'t Miss our Amazing New Features!';
			$message = cron_expired_free_trial_monthly_message($name, $expiration_date, $anchor_link);

			//get cron name
			$cron_name = $this->router->fetch_method();
				
			//email chief admin
			$title = 'Expired Free Trial Accounts: Monthly';
			$this->email_user_school_data($email, $subject, $message, $cron_name, $title, $school_id);

			//update cron daemon
			$interval = '30 days';
			$this->cron_model->update_cron_daemon($cron_name, $interval);
			
		}

	}
	
	
	
	
}