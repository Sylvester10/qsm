<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Student_search_admin
Role: Controller
Description: Admin Class controls access to student search functions
Model: Ajax-based Model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class Student_search_admin extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants

		//module-level scripts 
		$this->admin_module_scripts = array(); //pre-loaded in footer
	}



	/* ========== Search Student ========== */
	public function search_student_ajax() {  
		$this->load->model('ajax/admin/students/search_student_model_ajax', 'current_model');
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
	
