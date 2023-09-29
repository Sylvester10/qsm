<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Demo_accounts
Role: Controller
Description: Demo_accounts Class controls access to demo users page and functions
Model: Demo_accounts_model
Author: Nwankwo Ikemefuna
Date Created: 11th November, 2018
*/


class Demo_accounts extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->super_admin_restricted(); //allow only logged in super admins to access this class
		$this->load->model('demo_accounts_model');
		$this->load->model('admin_users_model');
		$this->load->model('school_staff_model');
		$this->load->model('students_admin_model');
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);

		//module-level scripts
		$this->super_admin_module_scripts = array('s_demo_accounts');
	}



	/* =========== Admins ============== */
	public function admins() {
		$inner_page_title = 'Demo Admins (' .count($this->demo_accounts_model->get_demo_admins()). ')'; 
		$this->super_admin_header('Demo Admins', $inner_page_title);
		$this->load->view('super_admin/demo_users/admins');
        $this->super_admin_footer();
	}
	
	
	public function admins_ajax() {
		$this->load->model('ajax/super_admin/demo_accounts/admin_users_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->name;
			$row[] = $y->email;
			$row[] = humanize($y->demo_role); 
			$row[] = $this->admin_users_model->get_section_assigned($y->id); 
			$row[] = $this->admin_users_model->get_admin_level($y->id); 
			$row[] = get_last_login_ago($y->last_login); 
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
	
	
	public function super_user_login_admin($user_id) {
		$user_details = $this->common_model->get_admin_details_by_id($user_id);
		$email = $user_details->email;
		$login_data = array(
			'admin_email' => $email, 
			'admin_loggedin' => TRUE,
			'demo_admin_loggedin' => TRUE,
			'demo_super_user_admin' => TRUE, //logged in as super user
		);
		$this->session->set_userdata($login_data);
		redirect('admin');	
    }
	
	
	public function edit_admin($user_id) {	
		$this->form_validation->set_rules('demo_role', 'Demo Role', 'trim|required');
		if ($this->form_validation->run()) {	
			$this->demo_accounts_model->edit_admin($user_id);
			$this->session->set_flashdata('status_msg', 'Demo Admin edited successfully.');
		} else { 
			$this->session->set_flashdata('status_msg_error', 'Error editing Admin!');
		}
		redirect($this->agent->referrer());
    }
	
	
	public function delete_admin($user_id) { 
		$this->admin_users_model->delete_admin($user_id);
		$this->session->set_flashdata('status_msg', 'Demo Admin deleted successfully.');
		redirect($this->agent->referrer());
	}





	/* =========== Staff ============== */
	public function staff() {
		$inner_page_title = 'Demo Staff (' .count($this->demo_accounts_model->get_demo_staff()). ')'; 
		$this->super_admin_header('Demo Staff', $inner_page_title);
		$this->load->view('super_admin/demo_users/staff');
        $this->super_admin_footer();
	}
	
	
	public function staff_ajax() {
		$this->load->model('ajax/super_admin/demo_accounts/staff_users_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->name;
			$row[] = $y->email;
			$row[] = $y->designation;
			$row[] = $y->role;
			$row[] = humanize($y->demo_role); 
			$row[] = get_last_login_ago($y->last_login); 
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
	
	
	public function super_user_login_staff($user_id) {
		$user_details = $this->common_model->get_staff_details_by_id($user_id);
		$email = $user_details->email;
		$login_data = array(
			'staff_email' => $email, 
			'staff_loggedin' => TRUE,
			'demo_staff_loggedin' => TRUE,
			'demo_super_user_staff' => TRUE, //logged in as super user
		);
		$this->session->set_userdata($login_data);
		redirect('staff');
    }
	
	
	public function edit_staff($user_id) {	
		$this->form_validation->set_rules('demo_role', 'Demo Role', 'trim|required');
		if ($this->form_validation->run()) {	
			$this->demo_accounts_model->edit_staff($user_id);
			$this->session->set_flashdata('status_msg', 'Demo Staff edited successfully.');
		} else { 
			$this->session->set_flashdata('status_msg_error', 'Error editing Staff!');
		}
		redirect($this->agent->referrer());
    }
	
	
	public function delete_staff($user_id) { 
		$this->school_staff_model->delete_staff($user_id);
		$this->session->set_flashdata('status_msg', 'Demo Staff deleted successfully.');
		redirect($this->agent->referrer());
	}




	/* =========== Students ============== */
	public function students() {
		$inner_page_title = 'Demo Students (' .count($this->demo_accounts_model->get_demo_students()). ')'; 
		$this->super_admin_header('Demo Students', $inner_page_title);
		$this->load->view('super_admin/demo_users/students');
        $this->super_admin_footer();
	}
	
	
	public function students_ajax() {
		$this->load->model('ajax/super_admin/demo_accounts/student_users_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $this->common_model->get_student_fullname($y->id);
			$row[] = $y->reg_id;
			$row[] = $this->common_model->get_class_details($y->class_id)->class;
			$row[] = $y->sex; 
			$row[] = humanize($y->demo_role); 
			$row[] = get_last_login_ago($y->last_login); 
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
	
	
	public function super_user_login_student($user_id) {
		$user_details = $this->common_model->get_student_details_by_id($user_id);
		$reg_id = $user_details->reg_id;
		$login_data = array(
			'student_reg_id' => $reg_id, 
			'student_loggedin' => TRUE,
			'demo_student_loggedin' => TRUE,
			'demo_super_user_student' => TRUE, //logged in as super user
		);
		$this->session->set_userdata($login_data);
		redirect('student');	
    }
	
	
	public function edit_student($user_id) {	
		$this->form_validation->set_rules('demo_role', 'Demo Role', 'trim|required');
		if ($this->form_validation->run()) {	
			$this->demo_accounts_model->edit_student($user_id);
			$this->session->set_flashdata('status_msg', 'Demo Student edited successfully.');
		} else { 
			$this->session->set_flashdata('status_msg_error', 'Error editing Student!');
		}
		redirect($this->agent->referrer());
    }
	
	
	public function delete_student($user_id) { 
		$this->students_admin_model->delete_student($user_id);
		$this->session->set_flashdata('status_msg', 'Demo Student deleted successfully.');
		redirect($this->agent->referrer());
	}





	/* =========== Parents ============== */
	public function parents() {
		$inner_page_title = 'Demo Parents (' .count($this->demo_accounts_model->get_demo_parents()). ')'; 
		$this->super_admin_header('Demo Parents', $inner_page_title);
		$this->load->view('super_admin/demo_users/parents');
        $this->super_admin_footer();
	}
	
	
	public function parents_ajax() {
		$this->load->model('ajax/super_admin/demo_accounts/parent_users_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$demo_role = ($y->demo_role != NULL) ? ( ($y->demo_role == 1) ? '1 Child' : ($y->demo_role . ' Children') ): NULL;
			$row = array();	
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->name;
			$row[] = $y->email;
			$row[] = count($this->common_model->get_parent_children($y->id));
			$row[] = $demo_role;
			$row[] = get_last_login_ago($y->last_login); 
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
	
	
	public function super_user_login_parent($user_id) {
		$user_details = $this->common_model->get_parent_details_by_id($user_id);
		$email = $user_details->email;
		$login_data = array(
			'parent_email' => $email, 
			'parent_loggedin' => TRUE,
			'demo_parent_loggedin' => TRUE,
			'demo_super_user_parent' => TRUE, //logged in as super user
		);
		$this->session->set_userdata($login_data);
		redirect('school_parent');	
    }
	
	
	public function edit_parent($user_id) {	
		$this->form_validation->set_rules('demo_role', 'Demo Role', 'trim|required');
		if ($this->form_validation->run()) {	
			$this->demo_accounts_model->edit_parent($user_id);
			$this->session->set_flashdata('status_msg', 'Demo Parent edited successfully.');
		} else { 
			$this->session->set_flashdata('status_msg_error', 'Error editing Parent!');
		}
		redirect($this->agent->referrer());
    }
	
	



}