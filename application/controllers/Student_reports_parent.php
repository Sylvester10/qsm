<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Student_reports_parent
Role: Controller
Description: Controls access to all student reports pages and functions from the parent's end
Model: Students_report_model
Author: Nwankwo Ikemefuna
Date Created: 7th September, 2018
Date Modified: 1st February, 2019
*/


class Student_reports_parent extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('students_report_model');
		$this->load->model('school_parent_model');
		$this->parent_restricted(); //allow only logged in users to access this class
		$this->parent_details = $this->common_model->get_parent_details($this->session->parent_email);
		$this->school_id = $this->parent_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		//ensure user login and current module are covered by current plan
		$this->login_module_restricted(school_id, mod_parent_login, mod_student_reports, 'parent'); 
		//ensure school account is activated
		$this->activation_restricted_parent(school_id); 

		//module-level scripts
		$this->parent_module_scripts = array('s_student_reports');
	}




	public function check_result($student_id) {
		//check student is parent's child
		$this->school_parent_model->check_child($student_id);
		$this->parent_header('Check End-of-Term Result', 'Check End-of-Term Result');
		$student_name = $this->common_model->get_student_fullname($student_id);
		$data['child_id'] = $student_id;
		$data['child_name'] = $student_name;
		$data['child_fname'] = $this->common_model->get_student_details_by_id($student_id)->first_name;
		$data['result_sessions'] = $this->students_report_model->get_student_result_sessions($student_id);
		$data['current_class_id'] = $this->common_model->get_student_details_by_id($student_id)->class_id;
		$this->load->view('parent/reports/end_term/check_result', $data);
        $this->parent_footer();
	}


	public function check_result_ajax($student_id) { 
		//check student is parent's child
		$this->school_parent_model->check_child($student_id);
		//set validation rules
		$this->form_validation->set_rules('session', 'Session', 'trim|required');
		$this->form_validation->set_rules('term', 'term', 'trim|required');
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$errors = array(
				'validation_status' => 0,
				'validation_errors' => validation_errors(),
			);
			echo json_encode($errors);
		
		} else {

			$session = $this->input->post('session', TRUE);
			$term = $this->input->post('term', TRUE);
			$class_id = $this->input->post('class_id', TRUE);
			
			//get report template
			$class_details = $this->common_model->get_class_details($class_id);	
			$section_id = $class_details->section_id;
			$template_id = $this->common_model->get_section_details($section_id)->template_id;

			//check if result exists for selected session, term and class. Also check if result has been approved
			$result_exists = $this->check_result_exists($session, $term, $class_id, $student_id);
			$query = $this->students_report_model->get_result_details($session, $term, $class_id, $student_id);

			if ($result_exists && $query->status == 'Approved') { //result exists and has been approved
				$json_data = array(
					'response' => 1,
					'template_id' => $template_id,
				);
				echo json_encode($json_data);

			} elseif ($result_exists && $query->status != 'Approved') { //result exists but has not been approved or is declined

				$json_data = array(
					'response' => 0,
					'message' => "Result pending approval. Check back later.",
				);
				echo json_encode($json_data);

			} else {

				 //result doesn't exist
				$json_data = array(
					'response' => 0,
					'message' => "No result found for selected session, term and class.",
				);
				echo json_encode($json_data);
			}
			
		}
	}


	private function check_result_exists($session, $term, $class_id, $student_id) { 
		$query = $this->students_report_model->get_result_details($session, $term, $class_id, $student_id);
		if ($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}


	private function report_card_restricted($session, $term, $class_id, $student_id) { 
		$result_exists = $this->check_result_exists($session, $term, $class_id, $student_id);
		$query = $this->students_report_model->get_result_details($session, $term, $class_id, $student_id);	
    	if ($result_exists && $query->status == 'Approved') {
			return TRUE;
		} elseif ($result_exists && $query->status != 'Approved') {
			$this->session->set_flashdata('status_msg_error', 'Result pending approval. Check back later.');
			redirect(site_url($this->c_controller.'/check_result'));
		} else {
			$this->session->set_flashdata('status_msg_error', 'No result found for selected session, term and class.');
			redirect(site_url($this->c_controller.'/check_result'));
		}
	}



	/* ========== Report Card ========== */
	public function report_card($template_id, $session, $term, $class_id, $student_id) {
		//validate report template
        $this->students_report_model->check_report_template($template_id, $session, $term, $class_id, $student_id, 'school_parent');

		//check student is parent's child
		$this->school_parent_model->check_child($student_id);
		//ensure all is well
		$this->report_card_restricted($session, $term, $class_id, $student_id);
		$page_title = 'End-of-Term Report Card: ' . $this->common_model->get_student_fullname($student_id);
		$this->parent_header($page_title, $page_title);
		//call report data
		$data = $this->students_report_model->report_card_data($session, $term, $class_id, $student_id);

		$template_folder = 'template_'.$template_id;
		$data['template_id'] = $template_id;
		$data['controller'] = $this->c_controller;
		$this->load->view('shared/students_report/end_term/real/'.$template_folder.'/report_card', $data);
        $this->parent_footer();
	}



	/* ===== Print Report Card ===== */
	public function print_report($template_id, $session, $term, $class_id, $student_id) {
		//validate report template
        $this->students_report_model->check_report_template($template_id, $session, $term, $class_id, $student_id, 'school_parent');
		
		//check student is parent's child
		$this->school_parent_model->check_child($student_id);
		//ensure all is well
		$this->report_card_restricted($session, $term, $class_id, $student_id);
		$page_title = 'Print End-of-Term Report Card: ' . $this->common_model->get_student_fullname($student_id);
		$this->print_header($page_title, $page_title);
		//call report data
		$data = $this->students_report_model->report_card_data($session, $term, $class_id, $student_id);

		$template_folder = 'template_'.$template_id;
		$data['template_id'] = $template_id;
		$data['controller'] = $this->c_controller;
		$this->load->view('shared/students_report/end_term/real/'.$template_folder.'/print_report', $data);
        $this->print_footer();
	}
	
	
	
	
}