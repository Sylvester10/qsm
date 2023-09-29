<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: School_account
Role: Controller
Description: School_account Class controls access to all school account pages and functions
Model: School_account_model
Author: Nwankwo Ikemefuna
Date Created: 16th August, 2018
*/



class School_account extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->super_admin_restricted(); //allow only logged in super admins to access this class
		$this->load->model('school_account_model');
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);

		//module-level scripts
		$this->super_admin_module_scripts = array('s_school_accounts');
	}




	/* ====== All schools ====== */
	
	public function schools() { 
		$inner_page_title = 'School Accounts (' . count($this->school_account_model->get_all_schools()) . ')';
		$this->super_admin_header('School Accounts', $inner_page_title);
		$data = $this->school_account_model->school_stats_data();
		$this->load->view('super_admin/schools/schools', $data);
		$this->super_admin_footer();
	}


	public function all_schools_ajax() {
		$this->load->model('ajax/super_admin/schools/all_schools_model_ajax', 'current_model'); 
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$plan = $this->common_model->get_plan_details($y->plan_id)->plan;
			$mode = ($y->activated == 'true') ? 'Paid' : $y->mode;
			$activated = ($y->activated == 'true') ? '<b class="text-success">Yes</b>' : '<b class="text-danger">No</b>';
			$school_website = ($y->school_website != NULL) ? '<a href="' . $y->school_website . '" target="_blank" title="Visit school website">' . $y->school_website . '</a>' : $y->school_website;
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->school_name;
			$row[] = $y->id;
			$row[] = $plan;
			$row[] = $mode;
			$row[] = $activated;
			$row[] = $this->school_account_model->get_school_data_records($y->id);
			$row[] = $this->school_account_model->school_users($y->id);
			$row[] = $this->current_model->chief_admin_info($y->id);
			$row[] = $y->school_location;
			$row[] = $y->country;
			$row[] = $y->official_mail;
			$row[] = $y->telephone_line;
			$row[] = $school_website;
			$row[] = $y->referrer;
			$row[] = $this->current_model->confirmation_status($y->id);
			$row[] = x_date($y->date_installed);
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records(),
			"recordsFiltered" => $this->current_model->count_filtered_records(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	
	public function activated_schools() { 
		$inner_page_title = 'Activated Schools (' . count($this->school_account_model->get_all_activated_schools()) . ')';
		$this->super_admin_header('Activated Schools', $inner_page_title);
		$data = $this->school_account_model->school_stats_data();
		$this->load->view('super_admin/schools/activated_schools', $data);
		$this->super_admin_footer();
	}


	public function activated_schools_ajax() {
		$this->load->model('ajax/super_admin/schools/activated_schools_model_ajax', 'current_model'); 
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$plan = $this->common_model->get_plan_details($y->plan_id)->plan;
			$school_website = ($y->school_website != NULL) ? '<a href="' . $y->school_website . '" target="_blank" title="Visit school website">' . $y->school_website . '</a>' : $y->school_website;
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->school_name;
			$row[] = $y->id;
			$row[] = $plan;
			$row[] = $this->school_account_model->get_school_data_records($y->id);
			$row[] = $this->school_account_model->school_users($y->id);
			$row[] = $this->current_model->chief_admin_info($y->id);
			$row[] = $y->school_location;
			$row[] = $y->country;
			$row[] = $y->official_mail;
			$row[] = $y->telephone_line;
			$row[] = $school_website;
			$row[] = $y->referrer;
			$row[] = $this->current_model->confirmation_status($y->id);
			$row[] = x_date($y->date_installed);
			$row[] = x_date($y->date_activated);
			$row[] = get_renewal_date($y->activated, $y->date_activated);
			$row[] = get_annual_subscription_remaining_time($y->date_activated);
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records(),
			"recordsFiltered" => $this->current_model->count_filtered_records(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	
	public function free_trial_schools() { 
		$inner_page_title = 'Free Trial Schools (' . count($this->school_account_model->get_free_trial_schools()) . ')';
		$this->super_admin_header('Free Trial Schools', $inner_page_title);
		$data = $this->school_account_model->school_stats_data();
		$this->load->view('super_admin/schools/free_trial_schools', $data);
		$this->super_admin_footer();
	}


	public function free_trial_schools_ajax() {
		$this->load->model('ajax/super_admin/schools/free_trial_schools_model_ajax', 'current_model'); 
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$plan = $this->common_model->get_plan_details($y->plan_id)->plan;
			$school_website = ($y->school_website != NULL) ? '<a href="' . $y->school_website . '" target="_blank" title="Visit school website">' . $y->school_website . '</a>' : $y->school_website;
			$expiration_time = get_expiration_date($y->date_installed);
			$remaining_time = get_free_trial_remaining_time($y->date_installed);
			
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->school_name;
			$row[] = $y->id;
			$row[] = $plan;
			$row[] = $this->school_account_model->get_school_data_records($y->id);
			$row[] = $this->school_account_model->school_users($y->id);
			$row[] = $this->current_model->chief_admin_info($y->id);
			$row[] = $y->school_location;
			$row[] = $y->country;
			$row[] = $y->official_mail;
			$row[] = $y->telephone_line;
			$row[] = $school_website;
			$row[] = $y->referrer;
			$row[] = $this->current_model->confirmation_status($y->id);
			$row[] = x_date($y->date_installed);
			$row[] = $expiration_time;
			$row[] = $remaining_time;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records(),
			"recordsFiltered" => $this->current_model->count_filtered_records(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	
	public function expired_free_trial_schools() { 
		$inner_page_title = 'Expired Free Trial Schools (' . count($this->school_account_model->get_expired_free_trial_schools()) . ')';
		$this->super_admin_header('Expired Free Trial Schools', $inner_page_title);
		$data = $this->school_account_model->school_stats_data();
		$this->load->view('super_admin/schools/expired_free_trial_schools', $data);
		$this->super_admin_footer();
	}


	public function expired_free_trial_schools_ajax() {
		$this->load->model('ajax/super_admin/schools/expired_free_trial_schools_model_ajax', 'current_model'); 
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$plan = $this->common_model->get_plan_details($y->plan_id)->plan;
			$school_website = ($y->school_website != NULL) ? '<a href="' . $y->school_website . '" target="_blank" title="Visit school website">' . $y->school_website . '</a>' : $y->school_website;
			$expiration_time = get_expiration_date($y->date_installed);
			
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->school_name;
			$row[] = $y->id;
			$row[] = $plan;
			$row[] = $this->school_account_model->get_school_data_records($y->id);
			$row[] = $this->school_account_model->school_users($y->id);
			$row[] = $this->current_model->chief_admin_info($y->id);
			$row[] = $y->school_location;
			$row[] = $y->country;
			$row[] = $y->official_mail;
			$row[] = $y->telephone_line;
			$row[] = $school_website;
			$row[] = $y->referrer;
			$row[] = $this->current_model->confirmation_status($y->id);
			$row[] = x_date($y->date_installed);
			$row[] = $expiration_time;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records(),
			"recordsFiltered" => $this->current_model->count_filtered_records(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}


	public function expired_annual_subscription_schools() { 
		$inner_page_title = 'Expired Annual Subscription Schools (' . count($this->school_account_model->get_expired_annual_subscription_schools()) . ')';
		$this->super_admin_header('Expired Annual Subscription Schools', $inner_page_title);
		$data = $this->school_account_model->school_stats_data();
		$this->load->view('super_admin/schools/expired_annual_subscription_schools', $data);
		$this->super_admin_footer();
	}


	public function expired_annual_subscription_schools_ajax() {
		$this->load->model('ajax/super_admin/schools/expired_annual_sub_schools_model_ajax', 'current_model'); 
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$plan = $this->common_model->get_plan_details($y->plan_id)->plan;
			$school_website = ($y->school_website != NULL) ? '<a href="' . $y->school_website . '" target="_blank" title="Visit school website">' . $y->school_website . '</a>' : $y->school_website;
			
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->school_name;
			$row[] = $y->id;
			$row[] = $plan;
			$row[] = $this->school_account_model->get_school_data_records($y->id);
			$row[] = $this->school_account_model->school_users($y->id);
			$row[] = $this->current_model->chief_admin_info($y->id);
			$row[] = $y->school_location;
			$row[] = $y->country;
			$row[] = $y->official_mail;
			$row[] = $y->telephone_line;
			$row[] = $school_website;
			$row[] = $y->referrer;
			$row[] = $this->current_model->confirmation_status($y->id);
			$row[] = x_date($y->date_installed);
			$row[] = x_date($y->date_activated);
			$row[] = get_renewal_date($y->activated, $y->date_activated);
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records(),
			"recordsFiltered" => $this->current_model->count_filtered_records(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}






	/* ===== Email Confirmation ===== */
	public function resend_email_confirmation($school_id) { 
		$y = $this->school_account_model->get_school_info($school_id);
		$this->school_account_model->resend_email_confirmation($school_id);
		$this->session->set_flashdata('status_msg', "Confirmation successfully resent to {$y->school_name}'s chief admin.");
		redirect($this->agent->referrer());
	}


	public function confirm_school_account($school_id) { 
		$y = $this->school_account_model->get_school_info($school_id);
		$this->school_account_model->confirm_school_account($school_id);
		$this->session->set_flashdata('status_msg', "{$y->school_name} confirmed successfully.");
		redirect($this->agent->referrer());
	}




	/* ===== Account Activation ===== */
	public function send_activation_code($school_id) { 
		$y = $this->school_account_model->get_school_info($school_id);
		$this->school_account_model->send_activation_code($school_id);
		$this->session->set_flashdata('status_msg', "Activation code sent to {$y->school_name} successfully.");
		redirect($this->agent->referrer());
	}


	public function activate_school_account($school_id) { 
		$y = $this->school_account_model->get_school_info($school_id);
		$this->school_account_model->activate_school_account($school_id);
		$this->session->set_flashdata('status_msg', "{$y->school_name} activated successfully.");
		redirect($this->agent->referrer());
	}




	/* ===== Account Upgrade ===== */
	public function send_upgrade_code_1222keep($school_id) { 
		$y = $this->school_account_model->get_school_info($school_id);
		$this->school_account_model->send_upgrade_code($school_id);
		$this->session->set_flashdata('status_msg', "Upgrade code sent to {$y->school_name} successfully.");
		redirect($this->agent->referrer());
	}


	public function upgrade_school_account_1222keep($school_id) { 
		$y = $this->school_account_model->get_school_info($school_id);
		$this->school_account_model->upgrade_school_account($school_id);
		$this->session->set_flashdata('status_msg', "{$y->school_name} upgraded successfully.");
		redirect($this->agent->referrer());
	}



	/* =====Misc. Actions ===== */
	public function deactivate_school_account($school_id) { 
		$y = $this->school_account_model->get_school_info($school_id);
		$this->school_account_model->deactivate_school_account($school_id);
		$this->session->set_flashdata('status_msg', "{$y->school_name} de-activated successfully.");
		redirect($this->agent->referrer());
	}


	public function extend_free_trial_period($school_id) { 
		$this->form_validation->set_rules('extend_date', 'Extend Date', 'trim|required');
		if ($this->form_validation->run())  {
			$extend_date = $this->input->post('extend_date', TRUE);	
			//ensure selected extend date is not in the past
			$valid_date = $this->school_account_model->check_extend_date_not_past($extend_date);
			if ($valid_date) {
				$this->school_account_model->extend_free_trial_period($school_id);
				$this->session->set_flashdata('status_msg', "Free Trial period extended successfully.");
			} else { 
				$this->session->set_flashdata('status_msg_error', "Extend Date cannot be in the past.");
			}
		} else {
			$this->session->set_flashdata('status_msg_error', "Form validation failed");
		}
		redirect($this->agent->referrer());
	}


	public function extend_subscription_period($school_id) { 
		$this->form_validation->set_rules('extend_date', 'Extend Date', 'trim|required');
		if ($this->form_validation->run())  {
			$extend_date = $this->input->post('extend_date', TRUE);	
			//ensure selected extend date is not in the past
			$valid_date = $this->school_account_model->check_extend_date_not_past($extend_date);
			if ($valid_date) {
				$this->school_account_model->extend_subscription_period($school_id);
				$this->session->set_flashdata('status_msg', "Subscription period extended successfully.");
			} else { 
				$this->session->set_flashdata('status_msg_error', "Extend Date cannot be in the past.");
			}
		} else {
			$this->session->set_flashdata('status_msg_error', "Form validation failed");
		}
		redirect($this->agent->referrer());
	}



	public function erase_school_data($school_id) { 
		//ensure school is not activated
		$this->school_account_model->check_activation_status($school_id);
		
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$password = $this->input->post('password', TRUE);	
		//hash the password
		$password = hash('ripemd128', $password);
		
		//get super admin password
		$super_admin_password = $this->super_admin_details->password;
		
		if ($this->form_validation->run())  {
			//compare both passwords
			if ($password == $super_admin_password) {
				//erase the data
				$this->school_account_model->erase_school_data($school_id);
				$this->session->set_flashdata('status_msg', "School data erased successfully.");
			} else { 
				$this->session->set_flashdata('status_msg_error', "Incorrect Super Admin password supplied.");
			}
		
		} else {
			$this->session->set_flashdata('status_msg_error', "Form validation failed");
		}
		redirect($this->agent->referrer());
	}
	
	
	public function delete_school_account($school_id) { 
		//ensure school is not activated
		$this->school_account_model->check_activation_status($school_id);
		
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$password = $this->input->post('password', TRUE);	
		//hash the password
		$password = hash('ripemd128', $password);
		
		//get super admin password
		$super_admin_password = $this->super_admin_details->password;
		
		if ($this->form_validation->run())  {
			//compare both passwords
			if ($password == $super_admin_password) {
				//delete the school account
				$this->school_account_model->delete_school_account($school_id);
				$this->session->set_flashdata('status_msg', "School account deleted successfully.");
			} else { 
				$this->session->set_flashdata('status_msg_error', "Incorrect Super Admin password supplied.");
			}
		
		} else {
			$this->session->set_flashdata('status_msg_error', "Form validation failed");
		}
		redirect($this->agent->referrer());
	}


	public function message_chief_admin($school_id) { 
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		$y = $this->school_account_model->get_school_info($school_id);
		$d = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
		if ($this->form_validation->run())  {		
			$this->school_account_model->message_chief_admin($school_id);
			$this->session->set_flashdata('status_msg', "Message successfully sent to {$d->name}.");
		} else {
			$this->session->set_flashdata('status_msg_error', 'Error sending message to chief admin.');
		}
		redirect($this->agent->referrer());
	}




}