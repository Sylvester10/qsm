<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: School_parent
Role: Controller
Description: School_parent Class controls access to all parent pages and functions
Model: School_parent_model
Author: Nwankwo Ikemefuna
Date Created: 6th September, 2018
*/


class School_parent extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('school_parent_model');
		$this->load->model('fees_model_parent');
		$this->parent_restricted(); //allow only logged in users to access this class
		$this->parent_details = $this->common_model->get_parent_details($this->session->parent_email);
		$this->school_id = $this->parent_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		//ensure user login and current module are covered by current plan
		$this->login_module_restricted(school_id, mod_parent_login, mod_core, 'parent'); 
		//ensure school account is activated
		$this->activation_restricted_student(school_id); 

		//module-level scripts
		$this->parent_module_scripts = array('s_parent');
	}
	
	
	/* ====== Dashboard ====== */
	
	public function index() { //parent dashboard, routed as dashboard
		$this->parent_header('Parent', 'Dashboard');
		$data['y'] = $this->parent_details;
		$data['children'] = $this->common_model->get_parent_children($this->parent_details->id);
		$this->load->view('parent/dashboard/dashboard', $data);
		$this->parent_footer();
	}


	public function restricted_access() { //restricted access page
		$this->parent_header('Error: Restricted Access', 'Error: Restricted Access');
		$this->load->view('shared/errors/restricted_access');
		$this->parent_footer();
	}


	public function send_quick_mail_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			echo validation_errors();
		} else {
			$this->school_parent_model->send_quick_mail();
			echo 1;	//indicator of success, will be used to check status in javascript
		}
	}






	public function select_child() { 
		$this->form_validation->set_rules('student_id', 'Child', 'trim|required');
		$redirect_method = $this->input->post('redirect_method', TRUE);
		$redirect_type = $this->input->post('redirect_type', TRUE);
		$student_id = $this->input->post('student_id', TRUE);
		$student_class_id = $this->common_model->get_student_details_by_id($student_id)->class_id;
		//check if redirect type is student or class
		if ($redirect_type == 'student') {
			$uri_segment = $redirect_method.'/'.$student_id;
		} else { //class
			$uri_segment = $redirect_method.'/'.$student_class_id;
		}
		if ($this->form_validation->run())  {
			//redirect to the appropriate page of the selected class
			redirect(site_url($uri_segment));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}





	/* ====== Child Profile ====== */
	public function child_profile($student_id) {
		//check student is parent's child
		$this->school_parent_model->check_child($student_id);
		$student_name = $this->common_model->get_student_fullname($student_id);
		$page_title = 'Profile: ' . $student_name;
		$this->parent_header($page_title, $page_title);	
		$student_details = $this->common_model->get_student_details_by_id($student_id);
		$data['student_name'] = $student_name;
		$data['class'] = $this->common_model->get_class_details($student_details->class_id)->class;
		$data['y'] = $student_details;
		$data['p'] = $this->parent_details;
		$this->load->view('parent/children/child_profile', $data);
        $this->parent_footer();
	}




	/* ====== Child's Class ====== */
	public function children_class() {
		$page_title = 'My Children\'s Class';
		$this->parent_header($page_title, $page_title);	
		$data['children'] = $this->common_model->get_parent_children($this->parent_details->id);
		$this->load->view('parent/children/children_class', $data);
        $this->parent_footer();
	}
	
	


	/* ====== Attendance ====== */
	public function child_attendance($student_id) { 
		//check student is parent's child
		$this->school_parent_model->check_child($student_id);
		$student_name = $this->common_model->get_student_fullname($student_id);
		$page_title = 'Attendance: ' . $student_name;
		$this->parent_header($page_title, $page_title);	
		$session = current_session_slug;
		$term = current_term;
		$student_details = $this->common_model->get_student_details_by_id($student_id);
		$class_id = $student_details->class_id;
		$data['class_id'] = $class_id;
		$data['child_id'] = $student_id;
		$data['child_name'] = $student_name;
		$data['attendance'] = $this->school_parent_model->get_student_attendance($session, $term, $class_id, $student_id);
		$data['att_present'] = $this->common_model->get_attendance_present($session, $term, $class_id, $student_id);
		$data['att_absent'] = $this->common_model->get_attendance_absent($session, $term, $class_id, $student_id);
		$data['att_total'] = $this->common_model->get_attendance_total($session, $term, $class_id, $student_id);
		$this->load->view('parent/children/child_attendance', $data); 
		$this->parent_footer();
	}


	public function check_attendance_ajax($student_id) { 
		//check student is parent's child
		$this->school_parent_model->check_child($student_id);
		//set validation rules
		$this->form_validation->set_rules('date', 'Date', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$errors = array(
				'validation_status' => 0,
				'validation_errors' => validation_errors(),
			);
			echo json_encode($errors);

		} else {

			$date = $this->input->post('date', TRUE);
			$query = $this->school_parent_model->check_attendance($student_id, $date);
			
			if ($query) {

				$session = get_the_session($query->session);
				$term = $query->term;
				$term = $query->term;
				$class = $this->common_model->get_class_details($query->class_id)->class;
				$date = x_date_full($query->date);
				$status = ($query->status == 'Present') ? '<b class="text-success">Present</b>' : '<b class="text-danger">Absent</b>';

				$json_data = array(
					'att_exists' => 'true',
					'session' => $session,
					'term' => $term,
					'class' => $class,
					'date' => $date,
					'status' => $status,
				);
				echo json_encode($json_data);

			} else {

				$json_data = array(
					'att_exists' => 'false',
					'message' => "No attendance data for selected date",
				);
				echo json_encode($json_data);

			}
		}
	}




	/* ====== Fee Info ====== */
	public function school_fees() {
		$this->parent_header('Children\'s School Fees', 'My Children\'s School Fees');
		$current_term_fees_due_date = $this->common_model->get_term_info(school_id)->current_term_fees_due_date;
		$current_term_fees_due_date = x_date_full($current_term_fees_due_date);
		$data['children'] = $this->common_model->get_parent_children($this->parent_details->id);
		$data['current_term_fees_due_date'] = $current_term_fees_due_date;
		$this->load->view('parent/fees/school_fees', $data); 
		$this->parent_footer();
	}
	
	
	
	
	/* ===== Email Notifications ===== */
	public function email_notifications() {
		$this->parent_header('Email Notifications', 'Email Notifications');
		$data['y'] = $this->school_parent_model->get_email_notif_details();
		$this->load->view('parent/settings/email_notifications', $data); 
		$this->parent_footer();
	}


	public function update_email_notifications_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('child_absence', 'Child Absence', 'trim');
		$this->form_validation->set_rules('newsletters', 'Newsletters', 'trim');
		if ($this->form_validation->run() == FALSE) {
			echo validation_errors();
		} else {
			$this->school_parent_model->update_email_notifications();
			echo 1;
		}
	}

	



	/* ====== Profile ====== */
	public function profile() {
		$this->parent_header('Profile', 'Profile');	
		$children_count = count($this->common_model->get_parent_children($this->parent_details->id));
		$data['child_inflect'] = ($children_count == 1) ? 'Child' : 'Children';
		$data['p'] = $this->parent_details;
		$this->load->view('parent/profile/profile', $data);
        $this->parent_footer();
	}
	



	/* ====== School Info ====== */
	public function school_info() {
		$this->parent_header('School Info', 'School Info');	
		$data['y'] = $this->common_model->get_school_info(school_id);
		$this->load->view('parent/info/school_info', $data);
        $this->parent_footer();
	}
	





}