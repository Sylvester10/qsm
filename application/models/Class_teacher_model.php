<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: Class_teacher_model
Role: Model
Description: Controls the DB processes of students from the staff's end
Controller: Class_eacher
Author: Nwankwo Ikemefuna
Date Created: 24th June, 2018
*/


class Class_teacher_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
		$this->class_details = $this->common_model->get_class_details_by_teacher($this->staff_details->id);
	}

	
	
	
	/* ========== Attendance ========== */

	public function get_attendance_details($att_id) {
		return $this->db->get_where('attendance', array('id' => $att_id))->row();
    } 


	public function check_attendance($student_id, $date) {
    	$where = array(
			'student_id' => $student_id,
			'date' => $date,
		);
    	return $this->db->get_where('attendance', $where)->row();	
    }


    public function get_recent_attendance($student_id, $limit) {
    	$this->db->limit($limit);
    	$this->db->order_by('date', 'DESC');
    	$where = array(
    		'student_id' => $student_id,
    		'class_id' => $this->class_details->id,
			'session' => current_session_slug,
			'term' => current_term,
		);
    	return $this->db->get_where('attendance', $where)->result();	
    }
	
	
	public function mark_student_present($id) {
		//check if this student has been marked absent for today
		$query = $this->check_attendance_marked($id, 'Absent');
		if ($query == 0) { //no attendance data for the selected date
			return $this->insert_attendance($id, 'Present');  //insert attendance data
		} else { //student has been marked Absent for the selected date 
			return $this->update_attendance($id, 'Present'); //update student's attendance to Present
		}
    } 


    public function mark_student_absent($id) {
		//check if this student has been marked present for today
		$query = $this->check_attendance_marked($id, 'Present');
		if ($query == 0) { //no attendance data for the selected date
			$this->insert_attendance($id, 'Absent');  //insert attendance data
		} else { //student has been marked Present for the selected date 
			$this->update_attendance($id, 'Absent'); //update student's attendance to Absent
		}

		//notify parent about child's absence
		$this->notify_parent_on_absent($id);
    } 


    private function notify_parent_on_absent($id) {
    	$date = $this->input->post('date', TRUE);
    	$date = x_date_full($date);
		$student_name = $this->common_model->get_student_fullname($id);
		$y = $this->common_model->get_student_details_by_id($id);
		$parent_id = $y->parent_id;
		$parent_name = $this->common_model->get_parent_details_by_id($parent_id)->name;
		$parent_email = $this->common_model->get_parent_details_by_id($parent_id)->email;
		//get parent's child absence email config
		$child_absence = $this->common_model->get_parent_email_notif_details($parent_id)->child_absence;
		$subject = 'Child Attendance Notice';
		$message = 'Hi ' . $parent_name . ', <br />
					This is to inform you that your child: <b>' . $student_name . '</b> was absent from school on ' . $date . '.
					<br />
					If you are not aware of this, please contact the school immediately.
					<p>You received this mail because your child/ward is registered as a pupil in ' . school_name . '.</p>';
		//send email to parent if email is provided and child absence is enabled by parent
		if ($parent_email != NULL && $child_absence == 'true') {
			email_user($parent_email, $subject, $message); 
		}
	}


    public function insert_attendance($id, $status) {
		$y = $this->common_model->get_student_details_by_id($id);
		$data = array(
			'school_id' => school_id,
			'student_id' => $id,
			'class_id' => $this->class_details->id,
			'session' => current_session_slug,
			'term' => current_term,
			'date' => $this->input->post('date', TRUE),
			'status' => $status,
		);
		return $this->db->insert('attendance', $data);	
    }


    public function update_attendance($id, $status) {
		$y = $this->common_model->get_student_details_by_id($id);
		$where = array(
			'student_id' => $id,
			'class_id' => $this->class_details->id,
			'session' => current_session_slug,
			'term' => current_term,
			'date' => $this->input->post('date', TRUE),
		);
		$data = array(
			'status' => $status,
		);
		$this->db->where($where);
		return $this->db->update('attendance', $data);	
    }
	

	public function check_attendance_marked($id, $status) {
    	//check if student has been marked present/absent for the selected date
		$y = $this->common_model->get_student_details_by_id($id);
		$where = array(
			'student_id' => $id,
			'class_id' => $this->class_details->id,
			'session' => current_session_slug,
			'term' => current_term,
			'date' => $this->input->post('date', TRUE),
			'status' => $status,
		);
		return $this->db->get_where('attendance', $where)->num_rows();	
    }


    public function get_student_attendance($id) {
    	$y = $this->common_model->get_student_details_by_id($id);
		$where = array(
			'student_id' => $id,
			'class_id' => $this->class_details->id,
			'session' => current_session_slug,
			'term' => current_term,
		);
		$this->db->order_by('date', 'desc');
		return $this->db->get_where('attendance', $where)->result();	
    }


    public function delete_attendance($att_id) {
		return $this->db->delete('attendance', array('id' => $att_id));
    } 





    /* ========== Conditional redirects ========== */

    public function check_class_assigned($class_id) {
		//ensure selected class is the one assigned to this teacher
		$teacher_class_id = $this->class_details->id;
		$class = $this->common_model->get_class_details($class_id)->class;
		if ($class_id == $teacher_class_id) {
			return TRUE;
		} else {
			$this->session->set_flashdata('status_msg_error', "The class you tried to access {$class} is not assigned to you.");
			redirect(site_url('class_teacher/single_class')); //redirect to single class page
		}
	}


    public function check_student_class($student_id) {
		//ensure student is in teacher's class
		$student_class_id = $this->common_model->get_student_details_by_id($student_id)->class_id;
		$teacher_class_id = $this->class_details->id;
		$teacher_class = $this->common_model->get_class_details($this->class_details->id)->class;
		if ($student_class_id == $teacher_class_id) {
			return TRUE;
		} else {
			$this->session->set_flashdata('status_msg_error', "The student you tried to access is not in {$teacher_class}");
			redirect(site_url('class_teacher/single_class')); //redirect to single class page
		}
	}


	public function check_session_and_term($session, $term) {
		//ensure session and term are same as current session and term
		//this is required for methods which only allows class teacher to access current session and term data
		if ($session == current_session_slug && $term == current_term) {
			return TRUE;
		} else {
			redirect(site_url('staff/restricted_access')); //redirect to restricted access page
		}
	}



	
	
}