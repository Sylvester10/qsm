<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Super_admin
Role: Controller
Description: Cron_jobs Class controls access to cron jobs in super admin panel
Model: Cron_jobs_model
Author: Nwankwo Ikemefuna
Date Created: 30th December, 2018
*/



class Cron_jobs extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->super_admin_restricted(); //allow only logged in super admins to access this class
		$this->load->model('cron_jobs_model');
		$this->load->model('cron_model');
		$this->load->model('coupon_model');
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);

		//total cron jobs (across all daemons)
		$this->total_cron_jobs_all = $this->cron_jobs_model->count_all_cron_jobs();

		$this->developer_email = developer_email; 
		//backup email
		$this->backup_email = developer_email;

		//initial school records (this could change)
		$this->initial_school_records = 27;
		$this->initial_school_records += 3; //small window incase school added few extra records and abandons account

		//module-level scripts
		$this->super_admin_module_scripts = array('s_cron_jobs');
	}




	public function index() { 
		$this->super_admin_header('Cron Daemons', 'Cron Daemons');
		$data['cron_daemons'] = $this->cron_jobs_model->get_cron_daemons();
		$this->load->view('super_admin/cron_jobs/cron_daemons', $data);
		$this->super_admin_footer();
	}


	public function test_run_daemon($name) {
		$daemon_details = $this->cron_jobs_model->get_cron_daemon_by_name($name);
		$inner_page_title = 'Test Run Daemon: ' . $daemon_details->title;
		$this->super_admin_header('Test Run Daemon', $inner_page_title);
		$total_rows = $this->cron_daemons_query($name, 'total_schools');
		//config for pagination
        $config = array();
		$per_page = 25;  //number of items to be displayed per page
        $uri_segment = 4;  //pagination segment id: cron_jobs/test_run/{name}/pagination_id
		$config["base_url"] = base_url('cron_jobs/test_run/'.$name);
        $config["total_rows"] = $total_rows;
        $config["per_page"] = $per_page;
		$config["uri_segment"] = $uri_segment;
		$config['cur_tag_open'] = '<a class="pagination-active-page" href="#!">';	//disable click event of current link
        $config['cur_tag_close'] = '</a>';
        $config['first_link'] = 'First';
        $config['next_link'] = '&raquo;';	// >>
        $config['prev_link'] = '&laquo;';	// <<
		$config['last_link'] = 'Last';
		$config['display_pages'] = TRUE; //show pagination link digits
		$config['num_links'] = 3; //number of digit links
        $this->pagination->initialize($config);
		$offset = $this->uri->segment($uri_segment) ? $this->uri->segment($uri_segment) : 0;
		$data["schools"] = $this->cron_daemons_query($name, 'schools', $config["per_page"], $offset);
		$data["total_schools"] = $total_rows;
		$data["offset"] = $offset;
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
        $data['d'] = $daemon_details;
		$data['name'] = $name;
		$data['cron_daemons'] = $this->cron_jobs_model->get_cron_daemons();
		$data['email_subject'] = $this->cron_daemons_query($name, 'email_subject');
		$data['email_message'] = $this->cron_daemons_query($name, 'email_message');
		$data['records_compare_daemons'] = $this->school_data_records_compare_daemons();
        $this->load->view('super_admin/cron_jobs/test_run_daemon', $data);
		$this->super_admin_footer();
	}


	private function cron_daemons_query($name, $item, $limit = NULL, $offset = NULL) {
		switch ($name) {

			case 'backup_database':
				$download_link = '#!';
				$anchor_link = email_call2action_blue($download_link, 'Download Backup');	
				$subject = 'Database Backup Successful';
				$message = 	'Automatic database backup succeeded via CRON. <br />
							Download the latest backup file below. <br /><br />'
							. $anchor_link; 
				$data = array(
					'schools' => NULL,
					'total_schools' => NULL,
					'email_subject' => $subject,
					'email_message' => $message,
				);
			break;

			case 'confirm_email':
				$subject = 'Confirm your Email';
				$anchor_link = email_call2action_blue('#!', 'Confirm Email');	
				$message = cron_confirm_email_message('Admin Name', $anchor_link);
				//get schools who are yet to confirm email
				$data = array(
					'schools' => $this->cron_jobs_model->get_unconfirmed_email_accounts($limit, $offset),
					'total_schools' => count($this->cron_model->get_unconfirmed_email_accounts()),
					'email_subject' => $subject,
					'email_message' => $message,
				);
			break;

			case 'get_started_message':
				$dash_link = '#!';
				$anchor_link = email_call2action_blue($dash_link, 'Get Started');	
				$subject = 'Getting Started with ' . software_name . '!';
				$message = cron_get_started_message('Admin Name', $dash_link, $anchor_link);
				//get schools who are have confirmed account but have not done basic setup
				$data = array(
					'schools' => $this->cron_jobs_model->get_passive_confirmed_accounts($limit, $offset),
					'total_schools' => count($this->cron_model->get_passive_confirmed_accounts()),
					'email_subject' => $subject,
					'email_message' => $message,
				);
			break;

			case 'free_trial_no_activity':
				$login_url = '#!';
				$anchor_link = email_call2action_blue($login_url, 'Login Now');	
				$subject = software_name . ' Misses You!';
				$expiration_date = get_expiration_date(default_calendar_date());
				$message = cron_free_trial_no_activity_message('Admin Name', 'Developer Test School', $expiration_date, 'adminemail@qsm.com', $login_url, $anchor_link);
				//get schools who signed up for free trial but haven't been using account
				$data = array(
					'schools' => $this->cron_jobs_model->get_passive_free_trial_accounts($limit, $offset),
					'total_schools' => count($this->cron_model->get_passive_free_trial_accounts()),
					'email_subject' => $subject,
					'email_message' => $message,
				);
			break;

			case 'paid_subscription_no_activity':
				$login_url = '#!';
				$anchor_link = email_call2action_blue($login_url, 'Login Now');	
				$subject = 'Need Help Setting Up?';
				$date_activated = x_date_full(default_calendar_date());
				$message = cron_paid_subscription_no_activity_message('Admin Name', 'Developer Test School', $date_activated, 'adminemail@qsm.com', $login_url, $anchor_link);
				//get schools who have been activated but haven't been using account
				$data = array(
					'schools' => $this->cron_jobs_model->get_passive_activated_accounts($limit, $offset),
					'total_schools' => count($this->cron_model->get_passive_activated_accounts()),
					'email_subject' => $subject,
					'email_message' => $message,
				);
			break;

			case 'expiring_free_trial':
				$discount_url = '#!';
				$anchor_link = email_call2action_blue($discount_url, 'Get 10% Off');	
				$subject = 'Get a 10% Discount on ' . software_name;
				$expiration_date = get_expiration_date(default_calendar_date());
				//get Free Trial Coupon
				$coupon = $this->coupon_model->get_free_trial_coupon_by_name('Free Trial');
				$coupon_code = ($coupon) ? $coupon->code : NULL;	
				$message = cron_expiring_free_trial_message('Admin Name', $expiration_date, $coupon_code, $discount_url, $anchor_link);
				//get schools whose free trial account are about to expire
				$data = array(
					'schools' => $this->cron_jobs_model->get_expiring_free_trial_accounts($limit, $offset),
					'total_schools' => count($this->cron_model->get_expiring_free_trial_accounts()),
					'email_subject' => $subject,
					'email_message' => $message,
				);
			break;

			case 'expired_free_trial':
				$discount_url = '#!';
				$anchor_link = email_call2action_blue($discount_url, 'Get 10% Off');	
				$subject = 'Get a 10% Discount on ' . software_name;
				$today = default_calendar_date();
				$expiration_date = x_date_full($today);
				//extend period = expiration date + 14 days
				$extend_date = strtotime("+14 days", strtotime(default_calendar_date()));
				$extend_date = date('Y/m/d', $extend_date);	
				$extend_date = x_date_full($extend_date);
				//get Free Trial Coupon
				$coupon = $this->coupon_model->get_free_trial_coupon_by_name('Free Trial');
				$coupon_code = ($coupon) ? $coupon->code : NULL;	
				$message = cron_expired_free_trial_message('Admin Name', $expiration_date, $extend_date, $coupon_code, $discount_url, $anchor_link);
				//get schools whose free trial account has expired for over 1 day
				$data = array(
					'schools' => $this->cron_jobs_model->get_expired_free_trial_accounts($limit, $offset),
					'total_schools' => count($this->cron_model->get_expired_free_trial_accounts()),
					'email_subject' => $subject,
					'email_message' => $message,
				);
			break;

			case 'expired_free_trial_monthly':
				$activate_url = '#!';
				$anchor_link = email_call2action_blue($activate_url, 'Activate Now');	
				$subject = 'Don\'t Miss our Amazing New Features!';
				$today = default_calendar_date();
				$expiration_date = x_date_full($today);
				$message = cron_expired_free_trial_monthly_message('Admin Name', $expiration_date, $anchor_link);
				//get schools whose free trial account has expired for over 1 month
				$data = array(
					'schools' => $this->cron_jobs_model->get_expired_free_trial_monthly_accounts($limit, $offset),
					'total_schools' => count($this->cron_model->get_expired_free_trial_monthly_accounts()),
					'email_subject' => $subject,
					'email_message' => $message,
				);
			break;

		}
		return $data[$item];
	}


	private function school_data_records_compare_daemons() {
		$daemons = [
			'get_started_message',
			'free_trial_no_activity',
			'paid_subscription_no_activity',
		];
		return $daemons;
	}




	/* ========== Manage Cron Jobs: DB ========== */
	public function cron_jobs_db($name) {
		$this->load->model('ajax/super_admin/cron_jobs/cron_jobs_db_model_ajax', 'current_model');
		$total_jobs = $this->current_model->count_all_records($name);
		$y = $this->cron_jobs_model->get_cron_daemon_by_name($name);
		$page_title = 'Cron Jobs: ' . $y->title . ' (' . $total_jobs . ')';
		$this->super_admin_header($page_title, $page_title);
		$data['cron_name'] = $name;
		$data['cron_title'] = $y->title;
		$data['total_jobs'] = $total_jobs;
		$this->load->view('super_admin/cron_jobs/cron_jobs_db', $data);
		$this->super_admin_footer();
	}


	public function cron_jobs_db_ajax($name) {
		$this->load->model('ajax/super_admin/cron_jobs/cron_jobs_db_model_ajax', 'current_model');
		$list = $this->current_model->get_records($name);
		$data = array();
		foreach ($list as $y) {
			$title = '<a href="'. base_url('cron_jobs/test_run_daemon/'.$name) .'" target="_blank">' . $y->title . '</a>';
			$status = ($y->status == 'Successful') ? '<b class="text-success">Successful</b>' : '<b class="text-danger">Failed</b>';
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $title; 
			$row[] = $status; 
			$row[] = x_date_time($y->time) . ' (' . time_ago($y->time) . ')';
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($name),
			"recordsFiltered" => $this->current_model->count_filtered_records($name),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}


	public function delete_cron_job_db($cj_id) { 
		$this->cron_jobs_model->delete_cron_job_db($cj_id);
		$this->session->set_flashdata('status_msg', 'Cron job deleted successfully.');
		redirect($this->agent->referrer());
	}


	public function bulk_actions_cron_jobs_db() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->cron_jobs_model->bulk_actions_cron_jobs_db();
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}
	
	
	public function clear_cron_jobs_db($name) { 
		$this->cron_jobs_model->clear_cron_jobs_db($name);
		$this->session->set_flashdata('status_msg', 'Cron jobs cleared successfully.');
		redirect($this->agent->referrer());
	}



	/* ========== Manage Cron Jobs: School Data ========== */
	public function cron_jobs_school_data($name) { 
		$this->load->model('ajax/super_admin/cron_jobs/cron_jobs_school_data_model_ajax', 'current_model');
		$total_jobs = $this->current_model->count_all_records($name);
		$y = $this->cron_jobs_model->get_cron_daemon_by_name($name);
		$page_title = 'Cron Jobs: ' . $y->title . ' (' . $total_jobs . ')';
		$this->super_admin_header($page_title, $page_title);
		$data['cron_name'] = $name;
		$data['cron_title'] = $y->title;
		$data['total_jobs'] = $total_jobs;
		$this->load->view('super_admin/cron_jobs/cron_jobs_school_data', $data);
		$this->super_admin_footer();
	}


	public function cron_jobs_school_data_ajax($name) {
		$this->load->model('ajax/super_admin/cron_jobs/cron_jobs_school_data_model_ajax', 'current_model');
		$list = $this->current_model->get_records($name);
		$data = array();
		foreach ($list as $y) {
			
			//school info
			$school_id = $y->school_id;
			$s = $this->common_model->get_school_info($school_id);
			$school_name = $s->school_name;
			$chief_admin_details = $this->common_model->get_admin_details_by_id($s->chief_admin_id);
			$chief_admin_name = $chief_admin_details->name;
			$chief_admin_email = $chief_admin_details->email;

			$title = '<a href="'. base_url('cron_jobs/test_run_daemon/'.$name) .'" target="_blank">' . $y->title . '</a>';
			$status = ($y->status == 'Successful') ? '<b class="text-success">Successful</b>' : '<b class="text-danger">Failed</b>';
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $title; 
			$row[] = $school_name; 
			$row[] = $chief_admin_name . ' (' . $chief_admin_email . ')';
			$row[] = $status; 
			$row[] = x_date_time($y->time) . ' (' . time_ago($y->time) . ')';
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($name),
			"recordsFiltered" => $this->current_model->count_filtered_records($name),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}


	public function delete_cron_job_school_data($cj_id) { 
		$this->cron_jobs_model->delete_cron_job_school_data($cj_id);
		$this->session->set_flashdata('status_msg', 'Cron job deleted successfully.');
		redirect($this->agent->referrer());
	}


	public function bulk_actions_cron_jobs_school_data() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->cron_jobs_model->bulk_actions_cron_jobs_school_data();
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}
	
	
	public function clear_cron_jobs_school_data($name) { 
		$this->cron_jobs_model->clear_cron_jobs_school_data($name);
		$this->session->set_flashdata('status_msg', 'Cron jobs cleared successfully.');
		redirect($this->agent->referrer());
	}



	//Clear All Cron Jobs (across all daemons)
	public function clear_all_cron_jobs() { 
		$this->cron_jobs_model->clear_all_cron_jobs();
		$this->session->set_flashdata('status_msg', 'All Cron jobs cleared successfully.');
		redirect($this->agent->referrer());
	}

}