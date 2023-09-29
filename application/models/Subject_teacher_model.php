<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: Subject_teacher_model
Role: Model
Description: Controls the DB processes of students from the staff's end
Controller: Subject_eacher
Author: Nwankwo Ikemefuna
Date Created: 30th October, 2018
*/


class Subject_teacher_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
	}

	
	
	
	/* ========== Checks ========== */
	public function check_class_assigned($class_id) {
		//ensure selected class is the one assigned to this teacher
		$staff_id = $this->staff_details->id;
		$subject_teacher = $this->common_model->get_subject_teacher_details_by_staff_id($staff_id);
		$classes_assigned = $subject_teacher->classes_assigned;
		$classes_array = explode(", ", $classes_assigned);
		if ( in_array($class_id, $classes_array) ) {
			return TRUE;
		} else {
			redirect(site_url('staff/restricted_access'));
		}
	}


	public function check_student_class($class_id, $student_id) {
		//ensure student is in teacher's class
		$student_class_id = $this->common_model->get_student_details_by_id($student_id)->class_id;
		$class = $this->common_model->get_class_details($class_id)->class;
		if ($student_class_id == $class_id) {
			return TRUE;
		} else {
			$this->session->set_flashdata('status_msg_error', "The student you tried to access is not in {$class}");
			redirect(site_url('subject_teacher/single_class/'.$class_id)); //redirect to single class page
		}
	}


	public function check_session_and_term($session, $term) {
		//ensure session and term are same as current session and term
		//this is required for methods which only allows subject teacher to access current session and term data
		if ($session == current_session_slug && $term == current_term) {
			return TRUE;
		} else {
			redirect(site_url('staff/restricted_access')); //redirect to restricted access page
		}
	}

    


	
	
}