<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Timetable_parent
Role: Controller
Description: Timetable_parent Class controls access to all time table pages and functions from the parent's end
Model: timetable_model
Author: Nwankwo Ikemefuna
Date Created: 6th September, 2018
*/


class Timetable_parent extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->parent_restricted(); //allow only logged in users to access this class
		$this->load->model('timetable_model');
		$this->parent_details = $this->common_model->get_parent_details($this->session->parent_email);
		//get school id
		$this->school_id = $this->parent_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		//ensure user login and current module are covered by current plan
		$this->login_module_restricted(school_id, mod_parent_login, mod_timetable, 'parent'); 
		//ensure school account is activated
		$this->activation_restricted_parent(school_id); 

		//module-level scripts
		$this->parent_module_scripts = array();
	}



	/* ====== Lesson Periods ====== */

	public function lesson_periods($class_id) {
		$class_details = $this->common_model->get_class_details($class_id);
		$class = $class_details->class;
		$section_id = $class_details->section_id;
		$page_title = 'Lesson Periods: ' . $class;
		$this->parent_header($page_title, $page_title);	
		$data['y'] = $class_details;
		$data['class_id'] = $class_id;
		$data['class'] = $class;
		$data['subjects_option'] = $this->common_model->subjects_option_by_section(school_id, $section_id); 
		$data['monday_periods'] = $this->timetable_model->get_lesson_periods($class_id, 'Monday');
		$data['tuesday_periods'] = $this->timetable_model->get_lesson_periods($class_id, 'Tuesday');
		$data['wednesday_periods'] = $this->timetable_model->get_lesson_periods($class_id, 'Wednesday');
		$data['thursday_periods'] = $this->timetable_model->get_lesson_periods($class_id, 'Thursday');
		$data['friday_periods'] = $this->timetable_model->get_lesson_periods($class_id, 'Friday');
		$data['classes_option'] = $this->common_model->classes_option_by_section_group(school_id); 
		$this->load->view('parent/timetable/lesson_periods', $data);
        $this->parent_footer();
	}





	/* ====== Test Schedules ====== */

	public function test_schedules($class_id) {
		$class_details = $this->common_model->get_class_details($class_id);
		$class = $class_details->class;
		$page_title = 'Test Schedules: ' . $class; 
		$this->parent_header($page_title, $page_title);
		$data['total_schedules'] = count($this->timetable_model->get_test_schedules()); 
		$data['test_schedules'] = $this->timetable_model->get_test_schedules(); 
		$data['start_date'] = $this->timetable_model->get_test_start_date(); 
		$data['end_date'] = $this->timetable_model->get_test_end_date(); 
		$data['class_id'] = $class_id;
		$data['class'] = $class;
		$this->load->view('parent/timetable/test_schedules', $data);
		$this->parent_footer();
	}


	
	
	/* ====== Exam Schedules ====== */

	public function exam_schedules($class_id) {
		$class_details = $this->common_model->get_class_details($class_id);
		$class = $class_details->class;
		$page_title = 'Exam Schedules: ' . $class; 
		$this->parent_header($page_title, $page_title);
		$data['total_schedules'] = count($this->timetable_model->get_exam_schedules()); 
		$data['exam_schedules'] = $this->timetable_model->get_exam_schedules(); 
		$data['start_date'] = $this->timetable_model->get_exam_start_date(); 
		$data['end_date'] = $this->timetable_model->get_exam_end_date(); 
		$data['class_id'] = $class_id;
		$data['class'] = $class;
		$this->load->view('parent/timetable/exam_schedules', $data);
		$this->parent_footer();
	}
	
	
	
	
}