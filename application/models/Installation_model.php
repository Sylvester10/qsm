<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Installation_model
Role: Model
Description: Controls the DB processes of software installation
Controllers: Installation, PayPal
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
Date Modified: 21st January, 2018
*/


class Installation_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('cron_model');
	}
	
		
		
	/* ===== Installation ===== */
	public function free_trial_install() { 
		$school_name = ucwords($this->input->post('school_name', TRUE)); 
		$school_location = ucwords($this->input->post('school_location', TRUE)); 
		$country = ucwords($this->input->post('country', TRUE)); 
		$currency_code = ucwords($this->input->post('currency', TRUE)); 
		$official_mail = strtolower($this->input->post('official_mail', TRUE)); 
		$telephone_line = $this->input->post('telephone_line', TRUE); 
		$school_website = $this->input->post('school_website', TRUE); 
		//prepend http:// to website url if not provided
		$school_website = prep_url($school_website);
		$school_motto = ucfirst($this->input->post('school_motto', TRUE)); 
		$referrer = $this->input->post('referrer', TRUE); 

		$plan_id = $this->input->post('plan_id', TRUE); 
		$mode = $this->input->post('mode', TRUE); 
		$year_installed = date('Y');

		//activation code 
		$activation_code = $this->generate_activation_code($plan_id);
		
		$data = array (
			'school_name' 			=> 		$school_name,
			'school_location' 		=> 		$school_location,
			'country' 				=> 		$country,
			'currency_code' 		=> 		$currency_code,
			'official_mail' 		=> 		$official_mail,
			'telephone_line' 		=> 		$telephone_line,
			'school_website' 		=> 		$school_website,
			'school_motto' 			=> 		$school_motto,
			'referrer' 				=> 		$referrer,
			'year_installed' 		=> 		$year_installed,
			'plan_id' 				=> 		$plan_id,
			'mode' 					=> 		$mode,
			'activated' 			=> 		'false', //Account yet to be activated
			'activation_code' 		=> 		$activation_code,
			'show_activation_code' 	=> 		'false',
			'confirmed' 			=> 		'false',
		);
		$insert_data = $this->db->insert('school_info', $data);
		$school_id = $this->db->insert_id($insert_data);

		//insert chief admin data
		$this->chief_admin_info($school_id);

		//create instance of term info
		$this->term_info($school_id);

		//create instance of report evaluation
		$this->report_evaluation($school_id);

		//create instance of mid-term report evaluation
		$this->mid_term_report_evaluation($school_id);
		
		//create instance of report behavioural aptitudes
		$this->report_aptitudes($school_id);

		//send confirmation email to chief admin
		$this->send_confirmation($school_id);

		//email chief admin
		$this->cron_model->email_chief_admin_on_free_trial($school_id);

		//email vendor admins
		$this->notify_vendor_admins($school_id);
	}


	


	/* ===== Buy & Install (with PayPal) ===== */
	public function buy_install_insert($data = array()) { 
		//called from Intallation controller prior to payment verification	
		$insert_data = $this->db->insert('school_info', $data);
		$school_id = $this->db->insert_id($insert_data);
		
		//insert chief admin data
		$this->chief_admin_info($school_id);

		//create instance of term info
		$this->term_info($school_id);

		//create instance of report evaluation
		$this->report_evaluation($school_id);

		//create instance of mid-term report evaluation
		$this->mid_term_report_evaluation($school_id);
		
		//create instance of report behavioural aptitudes
		$this->report_aptitudes($school_id);
		
		return $school_id;
	}
	
	
	public function buy_install_update($school_id) { 
		//called from PayPal controller after payment verification
		$data = array (
			'activated' => 'true',
			'date_activated' => date('Y-m-d H:i:s'),
			'show_activation_code' 	=> 		'true',
		);
		$this->db->where('id', $school_id);
		$this->db->update('school_info', $data);

		//send confirmation email to chief admin
		$this->send_confirmation($school_id);

		//email chief admin
		$this->cron_model->email_chief_admin_on_buy($school_id);	

		//email vendor admins
		$this->notify_vendor_admins($school_id);
	}


	public function buy_install_delete($school_id) {
		//called from PayPal controller on transaction failure
		
		//get all tables
		$tables = $this->db->list_tables();
		foreach ($tables as $table) {	
			$fields = $this->db->list_fields($table);
			//check if table has school ID column
			$has_school_id = $this->db->field_exists('school_id', $table);
			if ($has_school_id) { //yes sir
				//delete every record associated with this school....
				$this->db->delete($table, array('school_id' => $school_id));
				//we hate to see you go, but we don't really...
				$this->db->delete('school_info', array('id' => $school_id));
			} 
		}
	}




	/* ===== Buy & Install (other) ===== */
	public function buy_other_install() { 
		$school_name = ucwords($this->input->post('school_name', TRUE)); 
		$school_location = ucwords($this->input->post('school_location', TRUE)); 
		$country = ucwords($this->input->post('country', TRUE)); 
		$currency_code = ucwords($this->input->post('currency', TRUE)); 
		$official_mail = strtolower($this->input->post('official_mail', TRUE)); 
		$telephone_line = $this->input->post('telephone_line', TRUE); 
		$school_website = $this->input->post('school_website', TRUE); 
		//prepend http:// to website url if not provided
		$school_website = prep_url($school_website);
		$school_motto = ucfirst($this->input->post('school_motto', TRUE)); 
		$referrer = $this->input->post('referrer', TRUE); 

		$plan_id = $this->input->post('plan_id', TRUE); 
		$mode = $this->input->post('mode', TRUE); 
		$year_installed = date('Y');

		//activation code 
		$activation_code = $this->generate_activation_code($plan_id);
		
		$data = array (
			'school_name' 			=> 		$school_name,
			'school_location' 		=> 		$school_location,
			'country' 				=> 		$country,
			'currency_code' 		=> 		$currency_code,
			'official_mail' 		=> 		$official_mail,
			'telephone_line' 		=> 		$telephone_line,
			'school_website' 		=> 		$school_website,
			'school_motto' 			=> 		$school_motto,
			'referrer' 				=> 		$referrer,
			'year_installed' 		=> 		$year_installed,
			'plan_id' 				=> 		$plan_id,
			'mode' 					=> 		$mode,
			'activated' 			=> 		'false', //Account yet to be activated
			'activation_code' 		=> 		$activation_code,
			'show_activation_code' 	=> 		'false',
			'confirmed' 			=> 		'false',
		);
		$insert_data = $this->db->insert('school_info', $data);
		$school_id = $this->db->insert_id($insert_data);

		//insert chief admin data
		$this->chief_admin_info($school_id);

		//create instance of term info
		$this->term_info($school_id);

		//create instance of report evaluation
		$this->report_evaluation($school_id);

		//create instance of mid-term report evaluation
		$this->mid_term_report_evaluation($school_id);
		
		//create instance of report behavioural aptitudes
		$this->report_aptitudes($school_id);

		//send confirmation email to chief admin
		$this->send_confirmation($school_id);

		//email chief admin
		$this->cron_model->email_chief_admin_on_buy_other($school_id);

		//email vendor admins
		$this->notify_vendor_admins($school_id);
	}
	




	/* ===== Callbacks ===== */
	private function chief_admin_info($school_id) { 
		$admin_name = ucwords($this->input->post('admin_name', TRUE)); 
		$admin_email = strtolower($this->input->post('admin_email', TRUE)); 
		$admin_phone = $this->input->post('admin_phone', TRUE); 
		$password = hash('ripemd128', $this->input->post('password', TRUE));

		//insert admin data
		$data = array (
			'school_id' => $school_id,
			'name' => $admin_name,
			'email' => $admin_email,
			'phone' => $admin_phone,
			'password' => $password,
			'designation' => 'Chief Admin',
			'level' => 1,
			'chief_admin' => 'true',
			'demo_user' => 'false',
		);
		$admin_insert = $this->db->insert('admins', $data);
		$chief_admin_id = $this->db->insert_id($admin_insert);

		//update chief admin ID against school info
		$this->update_chief_admin_id($school_id, $chief_admin_id);
	}


	private function update_chief_admin_id($school_id, $chief_admin_id) { 
		$data = array (
			'chief_admin_id' => $chief_admin_id,
		);
		$this->db->where('id', $school_id);
		$this->db->update('school_info', $data);
	}


	private function term_info($school_id) { 
		$current_year = date('Y');
		$session = ($current_year - 1) . '/' . $current_year; //eg if year is 2018, session would be 2017/2018
		$data = array (
			'school_id' => $school_id,
			'session' => $session,
			'term' => '1st',
			'term_start_date' => default_calendar_date(),
			'current_term_fees_due_date' => default_calendar_date(),
			'next_term' => '2nd',
			'next_term_start_date' => default_calendar_date(),
			'next_term_fees_due_date' => default_calendar_date(),
		);
		return $this->db->insert('term_info', $data);
	}


	private function report_evaluation($school_id) { 
		//create multi-dimensional array...
		$array = array (
			//array(range[0], letter grade[1], GP[2], evaluation[3], head teacher's comment[4])
			array('0-9', 	'G', 	0,	'Very Poor', 	'Very poor result.'),
			array('10-19', 	'G', 	0,	'Very Poor', 	'Very poor result.'),
			array('20-29', 	'G', 	0,	'Very Poor', 	'Very poor result.'),
			array('30-39', 	'F', 	0,	'Poor', 		'Poor result. Pay more attention to your studies.'),
			array('40-49', 	'E', 	1,	'Satisfactory', 'Satisfactory result. Pay more attention to your studies.'),
			array('50-59', 	'D', 	2,	'Average', 		'Average result. Put more effort.'),
			array('60-69', 	'C', 	3,	'Average', 		'Average result. Put more effort.'),
			array('70-79', 	'B', 	4,	'Good', 		'Good result. There is still room for improvement.'),
			array('80-89', 	'A', 	5,	'Very Good', 	'Very Good result. Keep it up.'),
			array('90-100', 'A+', 	5,	'Excellent', 	'Excellent result! Keep it up.'),
		);
		$data = array();
		foreach ($array as $i) {
			$row = array();
			$row['school_id'] = $school_id;
			$row['range'] = $i[0];
			$row['grade'] = $i[1];
			$row['gp'] = $i[2];
			$row['evaluation'] = $i[3];
			$row['head_teacher_comment'] = $i[4];
			$data[] = $row;
		}
		return $this->db->insert_batch('report_evaluation', $data);
	}


	private function mid_term_report_evaluation($school_id) { 
		//create multi-dimensional array...
		$array = array (
			//array(range[0], letter grade[1], evaluation[2])
			array('0-9', 	'G', 	'Very Poor'),
			array('10-19', 	'G', 	'Very Poor'),
			array('20-29', 	'G', 	'Very Poor'),
			array('30-39', 	'F', 	'Poor'),
			array('40-49', 	'E', 	'Satisfactory'),
			array('50-59', 	'D', 	'Average'),
			array('60-69', 	'C', 	'Average'),
			array('70-79', 	'B', 	'Good'),
			array('80-89', 	'A', 	'Very Good'),
			array('90-100', 'A+', 	'Excellent'),
		);
		$data = array();
		foreach ($array as $i) {
			$row = array();
			$row['school_id'] = $school_id;
			$row['range'] = $i[0];
			$row['grade'] = $i[1];
			$row['evaluation'] = $i[2];
			$data[] = $row;
		}
		return $this->db->insert_batch('mid_term_report_evaluation', $data);
	}
	
	
	private function report_aptitudes($school_id) { 
		$aptitudes = array (
			'Attentiveness' => 'Affective',
			'Completion of Homework' => 'Affective', 
			'Curiousity' => 'Affective', 
			'Honesty' => 'Affective',
			'Neatness' => 'Affective',
			'Obedience' => 'Affective',
			'Politeness' => 'Affective',
			'Punctuality' => 'Affective',
			'Self-control' => 'Affective',
			'Sociability' => 'Affective',
			'Handwriting' => 'Psychomotor',
			'Maniapultive Skill' => 'Psychomotor',
			'Painting/Drawing' => 'Psychomotor',
			'Games/Sports' => 'Psychomotor',
			'Crafts' => 'Psychomotor',
		);
		$data = array();
		foreach ($aptitudes as $aptitude => $domain) {
			$row = array();
			$row['school_id'] = $school_id;
			$row['aptitude'] = $aptitude;
			$row['domain'] = $domain;
			$data[] = $row;
		}
		return $this->db->insert_batch('aptitudes', $data);
	}


	public function generate_activation_code($plan_id) { 
		//get software initials
		$software_initials = strtoupper(software_initials);
		//generate random 9-digit code
		$code = mt_rand(111111111, 999999999);
		//Add a zero to plan ID
		$plan_id = 0 . $plan_id; //eg 02 
		//append hyphen + A (for Activate) to new plan ID
		$plan_id = $plan_id . '-A'; //eg 02-A
		//concat software initials, plan ID and generated code
		$activation_code = $software_initials . $plan_id . $code; // eg QSM02-A320526854
		return $activation_code;
	}


	private function send_confirmation($school_id) {
    	$y = $this->common_model->get_school_info($school_id);
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
					Your installation of ' . software_name . ' for your school: ' . $school_name . ' was successful. 
					<br />
					Click on the button below to confirm your account. <br />' 
					. $anchor_link . 
					'<br /><br />
					If you encounter any problem, do not hesitate to reach us on ' . software_support_mail .
					'<br /><br />
					The ' . software_team;
		return email_user_default($email, $subject, $message); 
    } 
	

	private function notify_vendor_admins($school_id) {
    	$y = $this->common_model->get_school_info($school_id);
		$school_name = $y->school_name;
		$mode = $y->mode;
		$plan = $this->common_model->get_plan_details($y->plan_id)->plan;
		$country = $y->country;
		$activated = ($y->activated == 'false') ? 'No' : 'Yes';
		$redirect_url = base_url('school_account/schools');
		$subject = 'New School Registration';
		$level = 1;
		$admins = $this->common_model->get_super_admins_by_level($level);
		$message = 'Hi Admin, 
					<br />
					A new school has just signed up! <br />
					<b>Registration Details:</b><br /> 
					School Name: ' . $school_name . '<br />
					Plan: ' . $plan . '<br /> 
					Mode: ' . $mode . '<br />
					Country: ' . $country . '<br />
					Activated: ' . $activated . '<br /><br />
					Visit your <a href="' . $redirect_url . '">super admin dashboard</a> to see more details about this registration.';
		return email_multiple_default($admins, $subject, $message); 
    } 





    /* ===== Email Confirmation ===== */

    public function validate_confirm_code($school_id, $confirm_code) {
		$code = $this->common_model->get_school_info($school_id)->confirm_code;
        if ($code == $confirm_code) {
			return TRUE;
		} else {
			return FALSE;
		}
    }


    public function check_confirm_status($school_id) { 	
		$confirmed = $this->common_model->get_school_info($school_id)->confirmed;
        if ($confirmed != 'true') {
			return TRUE;
		} else {
			$this->session->set_flashdata('login_msg_error', 'Account already confirmed. Please login.');
			redirect(site_url('login'));
		}
	}


    public function check_school_id_exists($school_id) { 	
		$school_id_exists = $this->db->get_where('school_info', array('id' => $school_id))->row();
		return ($school_id_exists) ? TRUE : redirect(site_url('error404'));
	}


	public function resend_confirmation($school_id) {
    	$y = $this->common_model->get_school_info($school_id);
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
			'confirm_code' => NULL		 
		);		 
        $this->db->where('id', $school_id);			
		$this->db->update('school_info', $data);
		
		//notify chief admin
		$this->cron_model->email_chief_admin_on_email_confirmation($school_id);
	}



    
}