<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: School_users
Role: Controller
Description: School_users Class controls access to school users page and functions
Model: School_users_model
Author: Nwankwo Ikemefuna
Date Created: 17th November, 2018
*/


class School_users extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->super_admin_restricted(); //allow only logged in super admins to access this class
		//$this->load->model('school_users_model');
		$this->load->model('admin_users_model');
		$this->load->model('school_staff_model');
		$this->load->model('students_admin_model');
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);

		//module-level scripts
		$this->super_admin_module_scripts = array('s_school_accounts');
	}




	/* =========== Admins ============== */
	public function admins($school_id) {
		$total_users = count($this->common_model->get_admins($school_id));
		$inner_page_title = 'Admins (' . $total_users . ')'; 
		$this->super_admin_header('Admins', $inner_page_title);
		$data['school_id'] = $school_id;
		$data['y'] = $this->common_model->get_school_info($school_id);
		$data['total_users'] = $total_users;
		$this->load->view('super_admin/school_users/admins', $data);
        $this->super_admin_footer();
	}
	
	
	public function admins_ajax($school_id) {
		$this->load->model('ajax/super_admin/school_users/admin_users_model_ajax', 'current_model');
		$list = $this->current_model->get_records($school_id);
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->name;
			$row[] = $y->email; 
			$row[] = $y->phone; 
			$row[] = $this->admin_users_model->get_admin_level($y->id); 
			$row[] = get_last_login_ago($y->last_login); 
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($school_id),
			"recordsFiltered" => $this->current_model->count_filtered_records($school_id),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	
	public function super_user_login_admin($user_id) {
		$user_details = $this->common_model->get_admin_details_by_id($user_id);
		$email = $user_details->email;
		if ($email != NULL || $email != '') {
			$login_data = array(
				'admin_email' => $email, 
				'admin_loggedin' => TRUE,
			);
			$this->session->set_userdata($login_data);
			redirect('admin');
		} else {
			$this->session->set_flashdata('status_msg_error', 'Email not found!');
			redirect($this->agent->referrer());
		}
    }





    /* =========== Staff ============== */
	public function staff($school_id) {
		$total_users = count($this->common_model->get_staff($school_id));
		$inner_page_title = 'Staff (' . $total_users . ')'; 
		$this->super_admin_header('Staff', $inner_page_title);
		$data['school_id'] = $school_id;
		$data['y'] = $this->common_model->get_school_info($school_id);
		$data['total_users'] = $total_users;
		$this->load->view('super_admin/school_users/staff', $data);
        $this->super_admin_footer();
	}
	
	
	public function staff_ajax($school_id) {
		$this->load->model('ajax/super_admin/school_users/staff_users_model_ajax', 'current_model');
		$list = $this->current_model->get_records($school_id);
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->name;
			$row[] = $y->email; 
			$row[] = $y->phone; 
			$row[] = $y->role; 
			$row[] = get_last_login_ago($y->last_login); 
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($school_id),
			"recordsFiltered" => $this->current_model->count_filtered_records($school_id),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	
	public function super_user_login_staff($user_id) {
		$user_details = $this->common_model->get_staff_details_by_id($user_id);
		$email = $user_details->email;
		if ($email != NULL || $email != '') {
			$login_data = array(
				'staff_email' => $email, 
				'staff_loggedin' => TRUE,
			);
			$this->session->set_userdata($login_data);
			redirect('staff');
		} else {
			$this->session->set_flashdata('status_msg_error', 'Email not found!');
			redirect($this->agent->referrer());
		}
    }






    /* =========== Student ============== */
	public function students($school_id) {
		$total_users = count($this->common_model->get_students($school_id));
		$inner_page_title = 'Students (' . $total_users . ')'; 
		$this->super_admin_header('Students', $inner_page_title);
		$data['school_id'] = $school_id;
		$data['y'] = $this->common_model->get_school_info($school_id);
		$data['total_users'] = $total_users;
		$this->load->view('super_admin/school_users/students', $data);
        $this->super_admin_footer();
	}
	
	
	public function students_ajax($school_id) {
		$this->load->model('ajax/super_admin/school_users/student_users_model_ajax', 'current_model');
		$list = $this->current_model->get_records($school_id);
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $this->common_model->get_student_fullname($y->id);
			$row[] = $y->sex; 
			$row[] = $y->reg_id;
			$row[] = $y->admission_id;
			$row[] = @$this->common_model->get_class_details($y->class_id)->class;
			$row[] = ($y->graduated == 'true') ? 'Yes' : 'No';
			$row[] = ($y->revoked == 'true') ? 'Yes' : 'No';
			$row[] = $y->pass_reset_code;
			$row[] = get_last_login_ago($y->last_login); 
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($school_id),
			"recordsFiltered" => $this->current_model->count_filtered_records($school_id),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	
	public function super_user_login_student($user_id) {
		$user_details = $this->common_model->get_student_details_by_id($user_id);
		$student_reg_id = $user_details->reg_id;
		if ($student_reg_id != NULL || $student_reg_id != '') {
			$login_data = array(
				'student_reg_id' => $student_reg_id, 
				'student_loggedin' => TRUE,
			);
			$this->session->set_userdata($login_data);
			redirect('student');
		} else {
			$this->session->set_flashdata('status_msg_error', 'Reg ID not found!');
			redirect($this->agent->referrer());
		}
    }






    /* =========== Parent ============== */
	public function parents($school_id) {
		$total_users = count($this->common_model->get_parents($school_id));
		$inner_page_title = 'Parents (' . $total_users . ')'; 
		$this->super_admin_header('Parents', $inner_page_title);
		$data['school_id'] = $school_id;
		$data['y'] = $this->common_model->get_school_info($school_id);
		$data['total_users'] = $total_users;
		$this->load->view('super_admin/school_users/parents', $data);
        $this->super_admin_footer();
	}
	
	
	public function parents_ajax($school_id) {
		$this->load->model('ajax/super_admin/school_users/parent_users_model_ajax', 'current_model');
		$list = $this->current_model->get_records($school_id);
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->name;
			$row[] = $y->email; 
			$row[] = $y->phone; 
			$row[] = count($this->common_model->get_parent_children($y->id));
			$row[] = get_last_login_ago($y->last_login); 
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($school_id),
			"recordsFiltered" => $this->current_model->count_filtered_records($school_id),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	
	public function super_user_login_parent($user_id) {
		$user_details = $this->common_model->get_parent_details_by_id($user_id);
		$email = $user_details->email;
		if ($email != NULL || $email != '') {
			$login_data = array(
				'parent_email' => $email, 
				'parent_loggedin' => TRUE,
			);
			$this->session->set_userdata($login_data);
			redirect('school_parent');
		} else {
			$this->session->set_flashdata('status_msg_error', 'Email not found!');
			redirect($this->agent->referrer());
		}
    }




}