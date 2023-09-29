<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Super_admin
Role: Controller
Description: Super_admin Class controls access to all super admin pages and functions
Model: Super_admin_model
Author: Nwankwo Ikemefuna
Date Created: 16th August, 2018
*/



class Super_admin extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->super_admin_restricted(); //allow only logged in super admins to access this class
		$this->load->model('super_admin_model');
		$this->load->model('school_account_model');
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);

		//module-level scripts
		$this->super_admin_module_scripts = array('s_super_admin');
	}




	/* ====== Dashboard ====== */
	
	public function index() { 
		$this->super_admin_header('Super Admin', 'Dashboard');
		$data = $this->school_account_model->school_stats_data();
		$data['modules'] = $this->common_model->get_modules()->result();
		$this->load->view('super_admin/dashboard/dashboard', $data);
		$this->super_admin_footer();
	}


	public function send_quick_mail_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			echo validation_errors();
		} else {
			$this->super_admin_model->send_quick_mail();
			echo 1;
		}
	}
	
	
	public function send_bulk_mail_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('mailing_list', 'Mailing List', 'trim|required');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		
		if ($this->form_validation->run() == FALSE) {
			echo validation_errors();
		} else {
			$mailing_list = $this->input->post('mailing_list', TRUE);
			switch ($mailing_list) {
				case 'chief_admins':
					$mail_list = $this->super_admin_model->get_all_chief_admins();	
					$this->super_admin_model->send_bulk_mail($mail_list);
					echo 1;
				break;
			}
		}
	}




	/* ========== All Plans ========== */
	public function plans() { 
		$this->super_admin_header('Plans & Features', 'Plans & Features');
		$data['modules'] = $this->common_model->get_modules()->result();
		$this->load->view('super_admin/plan/plans', $data);
		$this->super_admin_footer();
	}



	/* ========== Account Manager ========== */
	public function account_manager() { 
		$this->super_admin_header('Account Manager', 'Account Manager');
		$data['y'] = $this->super_admin_model->get_account_manager_details();
		$this->load->view('super_admin/account_manager/account_manager', $data);
		$this->super_admin_footer();
	}


	public function update_override_password_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('super_admin_password', 'Super Admin Password', 'trim|required');
		$this->form_validation->set_rules('password', 'Override Password', 'trim|required');
		$this->form_validation->set_rules('c_password', 'Confirm Override Password', 'trim|required|matches[password]');
		$this->form_validation->set_rules('override_login', 'Override Login', 'trim|required');

		$super_admin_password = $this->input->post('super_admin_password', TRUE);
		$super_admin_password = hash('ripemd128', $super_admin_password);
		if ($this->form_validation->run() == FALSE) {
			echo validation_errors();
		} else {
			//check if super admin password is correct
			if ($super_admin_password == $this->super_admin_details->password) {
				$this->super_admin_model->update_override_password();
				echo 1;
			} else {
				echo "Incorrect Super Admin password supplied.";
			}
		}
	}






}