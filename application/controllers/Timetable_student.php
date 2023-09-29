<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Timetable_student
Role: Controller
Description: Timetable_student Class controls access to all time table pages and functions from the student's end
Model: timetable_model
Author: Nwankwo Ikemefuna
Date Created: 15th June, 2018
*/


class Timetable_student extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->student_restricted(); //allow only logged in users to access this class
		$this->load->model('timetable_model');
		$this->student_details = $this->common_model->get_student_details_by_reg_id($this->session->student_reg_id);
		//get school id
		$this->school_id = $this->student_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		//ensure user login and current module are covered by current plan
		$this->login_module_restricted(school_id, mod_student_login, mod_timetable, 'student'); 
		//ensure school account is activated
		$this->activation_restricted_student(school_id); 

		//module-level scripts
		$this->student_module_scripts = array();
	}



	/* ====== Lesson Periods ====== */

	public function lesson_periods() {
		$class_id = $this->student_details->class_id;
		$class_details = $this->common_model->get_class_details($class_id);
		$class = $class_details->class;
		$section_id = $class_details->section_id;
		$page_title = 'Lesson Periods: ' . $class;
		$this->student_header($page_title, $page_title);	
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
		$this->load->view('student/timetable/lesson_periods', $data);
        $this->student_footer();
	}





	/* ====== Test Schedules ====== */

	public function test_schedules() {
		$class_id = $this->student_details->class_id;
		$class_details = $this->common_model->get_class_details($class_id);
		$class = $class_details->class;
		$page_title = 'Test Schedules: ' . $class; 
		$this->student_header($page_title, $page_title);
		$data['total_schedules'] = count($this->timetable_model->get_test_schedules()); 
		$data['test_schedules'] = $this->timetable_model->get_test_schedules(); 
		$data['start_date'] = $this->timetable_model->get_test_start_date(); 
		$data['end_date'] = $this->timetable_model->get_test_end_date(); 
		$data['class_id'] = $class_id;
		$data['class'] = $class;
		$this->load->view('student/timetable/test_schedules', $data);
		$this->student_footer();
	}


	
	
	/* ====== Exam Schedules ====== */

	public function exam_schedules() {
		$class_id = $this->student_details->class_id;
		$class_details = $this->common_model->get_class_details($class_id);
		$class = $class_details->class;
		$page_title = 'Exam Schedules: ' . $class; 
		$this->student_header($page_title, $page_title);
		$data['total_schedules'] = count($this->timetable_model->get_exam_schedules()); 
		$data['exam_schedules'] = $this->timetable_model->get_exam_schedules(); 
		$data['start_date'] = $this->timetable_model->get_exam_start_date(); 
		$data['end_date'] = $this->timetable_model->get_exam_end_date(); 
		$data['class_id'] = $class_id;
		$data['class'] = $class;
		$this->load->view('student/timetable/exam_schedules', $data);
		$this->student_footer();
	}
	
	
	
	
}