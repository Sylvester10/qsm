<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Demo
Role: Controller
Description: Controls access to Demo login for admin, staff, student and parent
Model: Demo_model
Author: Nwankwo Ikemefuna
Date Created: 11th November, 2018
*/


class Demo extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('demo_model');

		//module-level scripts
        $this->site_module_scripts = array('s_demo');
	}

    

	/* ===== General: Admin/Staff ===== */
	public function login() {
		$this->site_header('Demo Login: Admin/Staff');
		$this->load->view('site/demo/admin_staff/login');
		$this->site_footer();
	}
	
	

	/* ===== Admin ===== */
	public function admin_role_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('admin_role', 'Admin Role', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$errors = array(
				'validation_status' => 0,
				'validation_errors' => validation_errors(),
			);
			echo json_encode($errors);

		} else {

			$role = $this->input->post('admin_role', TRUE);
			$query = $this->demo_model->get_admin_demo_role($role);
			
			if ($query) {

				$email = $query->email;
				$d_name = $query->name;

				$json_data = array(
					'found' => 'true',
					'email' => $email,
					'd_name' => $d_name,
				);
				echo json_encode($json_data);

			} else {

				$json_data = array(
					'found' => 'false',
					'message' => "Admin account not found!",
				);
				echo json_encode($json_data);

			}
		}
	}


	public function admin_login_ajax() {
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		
		$email = $this->input->post('email', TRUE);	
		$email_exists = $this->demo_model->check_admin_email_exists($email);
		
		if ($this->form_validation->run())  {	
						
			if ($email_exists) {

				//create login session
				$login_data = array(
					'admin_email' => $email, 
					'admin_loggedin' => TRUE,
					'demo_admin_loggedin' => TRUE,
				);
				$this->session->set_userdata($login_data);

				//success message
				echo 1;
				
			} else {
				//email not found
				echo 'Login failed! Email not recognized!';
			}
			
		} else { //form validation is not successful
			echo validation_errors();
		}
    }
	
	
	public function admin_logout() {
		$data = array(
			'admin_email', 
			'admin_loggedin', 
			'demo_admin_loggedin', 
			'demo_super_user_admin'
		);
		$this->session->unset_userdata($data);
		redirect(site_url('demo/login'));
	}
	
	


	
	/* ===== Staff ===== */
	public function staff_role_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('staff_role', 'Staff Role', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$errors = array(
				'validation_status' => 0,
				'validation_errors' => validation_errors(),
			);
			echo json_encode($errors);

		} else {

			$role = $this->input->post('staff_role', TRUE);
			$query = $this->demo_model->get_staff_demo_role($role);
			
			if ($query) {

				$email = $query->email;
				$d_name = $query->name;

				$json_data = array(
					'found' => 'true',
					'email' => $email,
					'd_name' => $d_name,
				);
				echo json_encode($json_data);

			} else {

				$json_data = array(
					'found' => 'false',
					'message' => "Staff account not found!",
				);
				echo json_encode($json_data);

			}
		}
	}


	public function staff_login_ajax() {
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		
		$email = $this->input->post('email', TRUE);
		$email_exists = $this->demo_model->check_staff_email_exists($email);
				
		if ($this->form_validation->run())  {

			if ($email_exists) {

				//create session data	
				$login_data = array(
					'staff_email' => $email, 
					'staff_loggedin' => TRUE,
					'demo_staff_loggedin' => TRUE,
				);
				$this->session->set_userdata($login_data);

				//success message
				echo 1;
				
			} else {
				//email not found
				echo 'Login failed! Email not recognized!';
			}
			
		} else { //form validation is not successful
			echo validation_errors();
		}
    }
	
	
	public function staff_logout() {
		$data = array(
			'staff_email', 
			'staff_loggedin', 
			'demo_staff_loggedin', 
			'demo_super_user_staff'
		);
		$this->session->unset_userdata($data);
		redirect(site_url('demo/login'));
	}







	/* ===== General: Student/Parent ===== */
	public function user_login() {
		$this->site_header('Demo Login: Student/Parent');
		$this->load->view('site/demo/student_parent/login');
		$this->site_footer();
	}
	
	


	/* ===== Student ===== */
	public function student_role_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('student_role', 'Student', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$errors = array(
				'validation_status' => 0,
				'validation_errors' => validation_errors(),
			);
			echo json_encode($errors);

		} else {

			$role = $this->input->post('student_role', TRUE);
			$query = $this->demo_model->get_student_demo_role($role);
			
			if ($query) {

				$reg_id = $query->reg_id;
				$student_id = $query->id;
				$d_name = $this->common_model->get_student_fullname($student_id);

				$json_data = array(
					'found' => 'true',
					'reg_id' => $reg_id,
					'd_name' => $d_name,
				);
				echo json_encode($json_data);

			} else {

				$json_data = array(
					'found' => 'false',
					'message' => "Student account not found!",
				);
				echo json_encode($json_data);

			}
		}
	}


	public function student_login_ajax() {
		$this->form_validation->set_rules('reg_id', 'Registration ID', 'trim|required');
		
		$reg_id = $this->input->post('reg_id', TRUE);	
		$reg_id_exists = $this->demo_model->check_student_reg_id_exists($reg_id);
		
		if ($this->form_validation->run())  {	

			if ($reg_id_exists) {
				
				//create session data		
				$login_data = array(
					'student_reg_id' => $reg_id, 
					'student_loggedin' => TRUE,
					'demo_student_loggedin' => TRUE,
				);
				$this->session->set_userdata($login_data);

				//success message
				echo 1;

			} else {
				//reg ID not found
				echo 'Login failed! Registration ID not recognized!';
			}
			
		} else { //form validation is not successful
			echo validation_errors();
		}
    }
   
	
	public function student_logout() {
		$data = array(
			'student_reg_id', 
			'student_loggedin', 
			'demo_student_loggedin', 
			'demo_super_user_student'
		);
		$this->session->unset_userdata($data);
		redirect(site_url('demo/user_login'));
	}





	/* ===== Parent ===== */
	public function parent_role_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('parent_role', 'Number of Children', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$errors = array(
				'validation_status' => 0,
				'validation_errors' => validation_errors(),
			);
			echo json_encode($errors);

		} else {

			$role = $this->input->post('parent_role', TRUE);
			$query = $this->demo_model->get_parent_demo_role($role);
			
			if ($query) {

				$email = $query->email;
				$d_name = $query->name;

				$json_data = array(
					'found' => 'true',
					'email' => $email,
					'd_name' => $d_name,
				);
				echo json_encode($json_data);

			} else {

				$json_data = array(
					'found' => 'false',
					'message' => "Parent account not found!",
				);
				echo json_encode($json_data);

			}
		}
	}


	public function parent_login_ajax() {
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		
		$email = $this->input->post('email', TRUE);
		$email_exists = $this->demo_model->check_parent_email_exists($email);
		
		if ($this->form_validation->run())  {

			if ($email_exists) {

				//create session data
				$login_data = array(
					'parent_email' => $email, 
					'parent_loggedin' => TRUE,
					'demo_parent_loggedin' => TRUE,
				);
				$this->session->set_userdata($login_data);

				//success message
				echo 1;
				
			} else {
				//email not found
				echo 'Login failed! Email not recognized.';
			}
			
		} else { //form validation is not successful
			echo validation_errors();
		}
    }
	
	
	public function parent_logout() {
		$data = array(
			'parent_email', 
			'parent_loggedin', 
			'demo_parent_loggedin', 
			'demo_super_user_parent'
		);
		$this->session->unset_userdata($data);
		redirect(site_url('demo/user_login'));
	}
	
	
	
	
	
}