<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: School_staff_model
Role: Model
Description: Controls the DB processes of staff from the admin's end
Controller: School_staff
Author: Nwankwo Ikemefuna
Date Created: 4th June, 2018
*/


class School_staff_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	
	
	
	/* ====== Staff Registration ====== */		
	public function staff_registration($passport_photo, $passport) {
		//if nationality is Nigeria, collect state and lga values
		if ( s_country == 'Nigeria' ) {
			$state_of_origin = ucwords($this->input->post('state_of_origin', TRUE));
			$local_gov = ucwords($this->input->post('local_gov', TRUE));
		} else {
			$state_of_origin = NULL;
			$local_gov = NULL;
		}	
		$data = array(
			'school_id' => school_id,
			'title' => ucwords($this->input->post('title', TRUE)),
			'name' => ucwords($this->input->post('name', TRUE)),
			'email' => strtolower($this->input->post('email', TRUE)),
			'phone' => $this->input->post('phone', TRUE), 
			'nationality' => ucwords($this->input->post('nationality', TRUE)),
			'state_of_origin' => $state_of_origin,
			'local_gov' => $local_gov,
			'designation' => ucwords($this->input->post('designation', TRUE)),
			'acc_number' => $this->input->post('acc_number', TRUE),
			'bank_name' => ucwords($this->input->post('bank_name', TRUE)),
			'date_of_birth' => ucwords($this->input->post('date_of_birth', TRUE)),
			'sex' => $this->input->post('sex', TRUE),
			'home_address' => ucwords($this->input->post('home_address', TRUE)),
			'religion' => ucwords($this->input->post('religion', TRUE)),
			'qualification' => ucwords($this->input->post('qualification', TRUE)),
			'employment_date' => $this->input->post('employment_date', TRUE),
			'name_of_kin' => ucwords($this->input->post('name_of_kin', TRUE)),
			'email_of_kin' => strtolower($this->input->post('email_of_kin', TRUE)),
			'mobile_of_kin' => $this->input->post('mobile_of_kin', TRUE),
			'address_of_kin' => ucwords($this->input->post('address_of_kin', TRUE)),
			'additional_info' => ucfirst($this->input->post('additional_info', TRUE)),
			'passport_photo' => $passport_photo,
			'passport' => $passport,
			'demo_user' => 'false',
		);
		$this->db->insert('staff', $data);
		
		//notify staff of their registration so they can set their password
		$this->notify_staff();
	}

	
	public function notify_staff() {
		$email = strtolower($this->input->post('email', TRUE));
		$name = ucwords($this->input->post('name', TRUE));
		$subject = 'You are now a staff!';
		$pass_set_url = base_url('set_password_staff');
		$message = 'Hi ' . get_firstname($name) . ', <br />
					You have been added as a staff by ' .$this->admin_details->name. ' on ' .school_name. '. <br />
					To begin using your account, you must set your password. <br />'
					. email_call2action_blue($pass_set_url, 'Set my password') . '<br /> <br />
					If you received this message in error, please ignore.';
		return email_user($email, $subject, $message); //email staff
	}
	

	/* ===== Edit Staff ===== */
	public function edit_staff($id, $passport_photo, $passport) { 
		//if nationality is Nigeria, collect state and lga values
		if ( s_country == 'Nigeria' ) {
			$state_of_origin = ucwords($this->input->post('state_of_origin', TRUE));
			$local_gov = ucwords($this->input->post('local_gov', TRUE));
		} else {
			$state_of_origin = NULL;
			$local_gov = NULL;
		}	
		$data = array(
			'title' => ucwords($this->input->post('title', TRUE)),
			'name' => ucwords($this->input->post('name', TRUE)),
			'phone' => $this->input->post('phone', TRUE),
			'nationality' => ucwords($this->input->post('nationality', TRUE)),
			'state_of_origin' => $state_of_origin,
			'local_gov' => $local_gov,
			'designation' => ucwords($this->input->post('designation', TRUE)),
			'acc_number' => $this->input->post('acc_number', TRUE),
			'bank_name' => ucwords($this->input->post('bank_name', TRUE)),
			'date_of_birth' => ucwords($this->input->post('date_of_birth', TRUE)),
			'sex' => ucfirst($this->input->post('sex', TRUE)),
			'home_address' => ucwords($this->input->post('home_address', TRUE)),
			'religion' => ucwords($this->input->post('religion', TRUE)),
			'qualification' => ucwords($this->input->post('qualification', TRUE)),
			'employment_date' => $this->input->post('employment_date', TRUE),
			'name_of_kin' => ucwords($this->input->post('name_of_kin', TRUE)),
			'email_of_kin' => strtolower($this->input->post('email_of_kin', TRUE)),
			'mobile_of_kin' => $this->input->post('mobile_of_kin', TRUE),
			'address_of_kin' => ucwords($this->input->post('address_of_kin', TRUE)),
			'additional_info' => ucfirst($this->input->post('additional_info', TRUE)),
			'passport_photo' => $passport_photo,
			'passport' => $passport,
		);
		$this->db->where('id', $id);	
		return $this->db->update('staff', $data);	
	}
	


	/* ===== Staff Role ===== */
	public function update_staff_role($id) { 
		$roles = implode(", ", $this->input->post('role', TRUE));
		$data = array(
			'role' => $roles,
		);
		$this->db->where('id', $id);	
		$this->db->update('staff', $data);	

		//check if subject teacher is one of the roles selected
		$role_array = explode(", ", $roles);
		$subject_teacher = 'Subject Teacher';
		if ( in_array($subject_teacher, $role_array) ) {
			//insert into subject teachers table
			$this->make_subject_teacher($id);
		}	
	}


	private function make_subject_teacher($id) {
		//check if staff is already a subject teacher
		$query = $this->common_model->get_subject_teacher_details_by_staff_id($id);
		if ( ! $query ) { //not a subject teacher yet
	    	$data = array(
				'school_id' => school_id,
				'staff_id' => $id,
			);
	    	return $this->db->insert('subject_teachers', $data);	
	    } else {
	    	return FALSE;
	    } 
    }


    public function subject_teacher_assignment($id) { 
		$classes_assigned = implode(", ", $this->input->post('classes_assigned', TRUE));
		$subjects_assigned = implode(", ", $this->input->post('subjects_assigned', TRUE));
		$data = array(
			'classes_assigned' => $classes_assigned,
			'subjects_assigned' => $subjects_assigned,
		);
		$this->db->where('id', $id);	
		$this->db->update('subject_teachers', $data);
	}


	public function get_assigned_classes($id) {
		$s = $this->common_model->get_subject_teacher_details_by_id($id);
		$classes_assigned = $s->classes_assigned;
		if ($classes_assigned != NULL) {
			//class IDs are saved as 1, 2, 3, ...
			//explode class IDs into array of individual IDs
			$class_idx = explode(", ", $classes_assigned);

			$classes = array();
			foreach ($class_idx as $key => $class_id) {
				$class_details = $this->common_model->get_class_details($class_id);
				//concatenate the classes and separate with comma
				$classes[] = $class_details->class; 
			}
			$classes = implode(", ", $classes);
			return $classes;
		} else {
			return NULL;
		}
	}


	public function get_assigned_subjects($id) {
		$s = $this->common_model->get_subject_teacher_details_by_id($id);
		$subjects_assigned = $s->subjects_assigned;
		if ($subjects_assigned != NULL) {
			//class IDs are saved as 1, 2, 3, ...
			//explode class IDs into array of individual IDs
			$subject_idx = explode(", ", $subjects_assigned);

			$subjects = array();
			foreach ($subject_idx as $key => $subject_id) {
				$subject_details = $this->common_model->get_subject_details($subject_id);
				//concatenate the classes and separate with comma
				$subjects[] = $subject_details->subject; 
			}
			$subjects = implode(", ", $subjects);
			return $subjects;
		} else {
			return NULL;
		}
	}


    public function remove_subject_teacher($id) {
    	$s = $this->common_model->get_subject_teacher_details_by_id($id);
		$staff_id = $s->staff_id;
		$y = $this->common_model->get_staff_details_by_id($staff_id);
		//get staff roles
		$roles = $y->role;

		//explode roles into array and remove Subject Teacher index
		$role_array = explode(", ", $roles);
		$subject_teacher = 'Subject Teacher';
		if ( in_array($subject_teacher, $role_array) ) {
			$new_role_array = array_diff($role_array, [$subject_teacher]); //remove element from array
			$updated_staff_roles = implode(", ", $new_role_array);
		} else {
			$updated_staff_roles = $roles; 
		}

		//update staff role
		$data = array(
			'role' => $updated_staff_roles,
		);
		$this->db->where('id', $staff_id);	
		$this->db->update('staff', $data);	

		//delete subject teacher
		$this->db->delete('subject_teachers', array('id' => $id));
    } 

	
	
	
	public function message_staff($id) {
		$message = nl2br(ucfirst($this->input->post('message', TRUE))); 
		$subject = 'Message from Admin';
		$y = $this->common_model->get_staff_details_by_id($id);
		$this->common_model->notify_user(school_id, $id, $subject, $message, 'staff_notifs'); //send notif to staff
		return email_user($y->email, $subject, $message); //email staff
    } 
	

	public function delete_staff_passport($id) {
		$y = $this->common_model->get_staff_details_by_id($id);
		if ($y->passport_photo != NULL && $y->passport != NULL) {
			unlink('./assets/uploads/photos/staff/'.$y->passport_photo); //delete the passport photo
			unlink('./assets/uploads/photos/staff/'.$y->passport); //delete the passport
		}
    } 
	
	
	public function delete_staff($id) {
		$y = $this->common_model->get_staff_details_by_id($id);
		$this->delete_staff_passport($id); //remove image files from server
		return $this->db->delete('staff', array('id' => $id));
    } 
	

	
	/* ========== Attendance ========== */
	public function check_attendance($id, $date) {
    	$where = array(
			'staff_id' => $id,
			'date' => $date,
		);
    	return $this->db->get_where('staff_attendance', $where)->row();	 
    }
	
	
	public function mark_staff_present($id) {
		//check if this staff has been marked absent for today
		$query = $this->check_attendance_marked($id, 'Absent');
		if ($query == 0) { //no attendance data for the selected date
			$this->insert_attendance($id, 'Present');  //insert attendance data
		} else { //staff has been marked Absent for the selected date 
			$this->update_attendance($id, 'Present'); //update staff attendance to Present
		}
    } 


    public function mark_staff_absent($id) {
		//check if this staff has been marked present for today
		$query = $this->check_attendance_marked($id, 'Present');
		if ($query == 0) { //no attendance data for the selected date
			$this->insert_attendance($id, 'Absent');  //insert attendance data
		} else { //staff has been marked Present for the selected date 
			$this->update_attendance($id, 'Absent'); //update staff attendance to Absent
		}
    } 


    public function insert_attendance($id, $status) {
		$data = array(
			'school_id' => school_id,
			'staff_id' => $id,
			'date' => $this->input->post('date', TRUE),
			'status' => $status,
			'session' => current_session_slug,
			'term' => current_term,
		);
		return $this->db->insert('staff_attendance', $data);	
    }


    public function update_attendance($id, $status) {
		$where = array(
			'staff_id' => $id,
			'date' => $this->input->post('date', TRUE),
			'session' => current_session_slug,
			'term' => current_term,
		);
		$data = array(
			'status' => $status,
		);
		$this->db->where($where);
		return $this->db->update('staff_attendance', $data);	
    }
	

	public function check_attendance_marked($id, $status) {
    	//check if staff has been marked present/absent for the selected date
		$where = array(
			'staff_id' => $id,
			'date' => $this->input->post('date', TRUE),
			'status' => $status,
			'session' => current_session_slug,
			'term' => current_term,
		);
		return $this->db->get_where('staff_attendance', $where)->num_rows();	
    }


    public function get_staff_attendance($id) {
    	$where = array(
			'staff_id' => $id,
			'session' => current_session_slug,
			'term' => current_term,
		);
		$this->db->order_by('date', 'desc');
		return $this->db->get_where('staff_attendance', $where)->result();	
    }


    public function get_staff_attendance_present($id) {
		$where = array(
			'staff_id' => $id,
			'session' => current_session_slug,
			'term' => current_term,
			'status' => 'Present',
		);
		return $this->db->get_where('staff_attendance', $where)->num_rows();
    } 


    public function get_staff_attendance_absent($id) {
		$where = array(
			'staff_id' => $id,
			'session' => current_session_slug,
			'term' => current_term,
			'status' => 'Absent',
		);
		return $this->db->get_where('staff_attendance', $where)->num_rows();
    } 


    public function get_staff_attendance_total($id) {
		$att_present = $this->get_staff_attendance_present($id);
		$att_absent = $this->get_staff_attendance_absent($id);
		$att_total = $att_present + $att_absent;
		return $att_total;
    } 


    public function delete_attendance($id) {
		return $this->db->delete('staff_attendance', array('id' => $id));
    } 
	


	
}