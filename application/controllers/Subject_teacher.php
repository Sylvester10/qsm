<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Subject_teacher
Role: Controller
Description: Controls access to all students pages and functions from the class teacher's end
Model: 
Author: Nwankwo Ikemefuna
Date Created: 30th October, 2018
*/


class Subject_teacher extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->staff_restricted(); //allow only logged in users to access this class
		$this->load->model('subject_teacher_model');
		$this->staff_role_restricted('Subject Teacher'); //only staff with this role can access this module
		$this->check_staff_is_subject_teacher(); //allow only class teachers who are currently assigned to a class to access this module
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
		//get school id
		$this->school_id = $this->staff_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_staff(school_id, mod_student_management); //student management module
		$this->activation_restricted_staff(school_id); 

		//module-level scripts
		$this->staff_module_scripts = array('s_subject_teacher');
	}




	public function view_single_class() { //select class in modal in header
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$class_id = $this->input->post('class_id', TRUE);
		if ($this->form_validation->run())  {
			//redirect to the single class page of the selected class
			redirect(site_url('subject_teacher/single_class/'.$class_id));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}



	/* ========== Single Class ========== */
	public function single_class($class_id) {
		//ensure this class is one of those assigned to this subject teacher
		$this->subject_teacher_model->check_class_assigned($class_id);
		$class_details = $this->common_model->get_class_details($class_id);
		$page_title = 'Class: ' . $class_details->class;
		$this->staff_header($page_title, $page_title);	
		$data['y'] = $class_details;
		$this->load->view('staff/students/subject_teacher/single_class', $data);
        $this->staff_footer();
	}
	
	
	public function single_class_ajax($class_id) {
		$this->load->model('ajax/staff/students/single_class_model_ajax', 'current_model');
		$list = $this->current_model->get_records($class_id);
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$mid_term_reports_controller = 'student_mid_term_reports_subject_teacher';
			$end_term_reports_controller = 'student_reports_subject_teacher';
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id, $mid_term_reports_controller, $end_term_reports_controller);
			$row[] = $this->common_model->student_passport($y->id);
			$row[] = $y->admission_id; 
			$row[] = $this->common_model->get_student_fullname($y->id);
			$row[] = $y->sex; 
			$row[] = $this->current_model->student_attendance_info($y->id);
			$row[] = $this->current_model->suspension_status($y->id);
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($class_id),
			"recordsFiltered" => $this->current_model->count_filtered_records($class_id),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	
	
}