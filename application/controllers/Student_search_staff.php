<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Student_search_staff
Role: Controller
Description: staff Class controls access to student search functions
Model: Ajax-based Model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class Student_search_staff extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->staff_restricted(); //allow only logged in users to access this class
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
		//get school id
		$this->school_id = $this->staff_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants

		//module-level scripts
		$this->staff_module_scripts = array(); //pre-loaded in footer
	}



	/* ========== Search Student ========== */
	public function search_student_ajax() {  
		$this->load->model('ajax/staff/students/search_student_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = $this->common_model->get_student_fullname($y->id);
			$row[] = $y->reg_id; 
			$row[] = $y->admission_id; 
			$row[] = $this->common_model->get_class_details($y->class_id)->class;
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
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





}
	
