<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Student_model
Role: Model
Description: Controls the DB processes of the student
Controller: Student
Author: Nwankwo Ikemefuna
Date Created: 28th August, 2018
*/

class Student_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->student_details = $this->common_model->get_student_details_by_reg_id($this->session->student_reg_id);
	}
	
	
	

	/* ===== Dashboard ===== */
	public function send_quick_mail() {	
		$student_name = $this->common_model->get_student_fullname($this->student_details->id);
		//get class teacher email
		$class_teacher_id = $this->common_model->get_class_details($this->student_details->class_id)->class_teacher_id;
		$class_teacher_email = $this->common_model->get_staff_details_by_id($class_teacher_id)->email;
		$email = $class_teacher_email;
		$subject = ucwords($this->input->post('subject', TRUE));
		$message = nl2br(ucfirst($this->input->post('message', TRUE)));
		$message = $message. '<p>Sent from student dashboard by <b>' . $student_name . '</b></p>';
		return email_user($email, $subject, $message);
	}


	


	/* ===== Attendance ===== */
	private function where_array($session, $term, $class_id, $student_id) { 
		$where = array(
			'student_id' => $student_id,
			'class_id' => $class_id,
			'session' => $session,
			'term' => $term,
		);
		return $where;
	}


	public function get_recent_attendance($limit) {
    	$this->db->limit($limit);
    	$this->db->order_by('date', 'DESC');
    	$where = array(
    		'student_id' => $this->student_details->id,
    		'class_id' => $this->student_details->class_id,
			'session' => current_session_slug,
			'term' => current_term,
		);
    	return $this->db->get_where('attendance', $where)->result();	
    }


	public function get_student_attendance($session, $term, $class_id, $student_id) {
    	$where = $this->where_array($session, $term, $class_id, $student_id);
		$this->db->order_by('date', 'desc');
		return $this->db->get_where('attendance', $where)->result();	
    }
	
	
	public function check_attendance($date) {
    	$where = array(
			'student_id' => $this->student_details->id,
			'date' => $date,
		);
    	return $this->db->get_where('attendance', $where)->row();	
    }




	
	
	/* ===== Checks ===== */
    public function check_student_class($class_id) {
		//ensure student is in selected class
		$student_class_id = $this->student_detail->class_id;
		if ($student_class_id == $class_id) {
			return TRUE;
		} else {
			redirect(site_url('student/restricted_access')); 
		}
	}


    public function check_session_and_term($session, $term) {
		//ensure session and term are same as current session and term
		//this is required for methods which only allows student to access current session and term data
		if ($session == current_session_slug && $term == current_term) {
			return TRUE;
		} else {
			redirect(site_url('student/restricted_access')); //redirect to restricted access page
		}
	}




}