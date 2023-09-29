<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: Students_dmin_model
Role: Model
Description: Controls the DB processes of students from the admin's end
Controller: Students_admin
Author: Nwankwo Ikemefuna
Date Created: 4th June, 2018
*/


class Students_admin_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	
	
		
	/* ===== Student Registration ===== */
	public function student_registration($passport_photo, $passport) { 
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
			'admission_id' => ucwords($this->input->post('admission_id', TRUE)),
			'last_name' => ucwords($this->input->post('last_name', TRUE)),
			'first_name' => ucwords($this->input->post('first_name', TRUE)),
			'other_name' => ucwords($this->input->post('other_name', TRUE)),
			'date_of_birth' => $this->input->post('date_of_birth', TRUE),
			'sex' => ucfirst($this->input->post('sex', TRUE)),
			'blood_group' => ucwords($this->input->post('blood_group', TRUE)),
			'place_of_birth' => ucwords($this->input->post('place_of_birth', TRUE)),
			'nationality' => ucwords($this->input->post('nationality', TRUE)),
			'state_of_origin' => $state_of_origin,
			'local_gov' => $local_gov,
			'home_town' => ucwords($this->input->post('home_town', TRUE)),
			'home_address' => ucwords($this->input->post('home_address', TRUE)),
			'religion' => ucwords($this->input->post('religion', TRUE)),
			'present_school' => ucwords($this->input->post('present_school', TRUE)),
			'present_school_address' => ucwords($this->input->post('present_school_address', TRUE)),
			'present_class' => ucwords($this->input->post('present_class', TRUE)),
			'admission_date' => ucwords($this->input->post('admission_date', TRUE)),
			'additional_info' => ucwords($this->input->post('additional_info', TRUE)),
			'class_id' => ucwords($this->input->post('class_id', TRUE)),
			'passport_photo' => $passport_photo,
			'passport' => $passport,
			'suspended' => 'false',
			'revoked' => 'false',
			'graduated' => 'false',
			'demo_user' => 'false',
		);
		$insert = $this->db->insert('students', $data);
		$student_id = $this->db->insert_id($insert);

		//insert parent details
		$parent_email = strtolower($this->input->post('parent_email', TRUE));
		if ($parent_email != '') { //parent email is supplied
			//check if parent email already exists (in the case where parent has more than 1 child in this school)
			$query_parent = $this->common_model->get_parent_details($parent_email);
			if ( ! $query_parent) { //email doesn't exists, insert the parent details and retrieve insert ID
				$parent_id = $this->insert_parent_details(); 
			} else { //email exists, get parent ID
				$parent_id = $query_parent->id;
			}
		} else { //parent email is not supplied, insert parent details
			$parent_id = $this->insert_parent_details(); 
		}
		
		//generate and update student Registration ID and password reset code
		$reg_id = $this->generate_reg_id($student_id);
		$pass_reset_code = $this->generate_pass_reset_code();
		$update_data = array (
			'reg_id' => $reg_id,
			'pass_reset_code' => $pass_reset_code,
			'parent_id' => $parent_id,
		);
		$this->db->where('id', $student_id);	
		return $this->db->update('students', $update_data);	
	}


	private function insert_parent_details() { 
		$parent_relationship = $this->input->post('parent_relationship', TRUE);
		if ($parent_relationship == "other") {
			$parent_relationship = $this->input->post('parent_relationship_other', TRUE);
		} else {
			$parent_relationship = $parent_relationship;	
		}

		$sec_parent_relationship = $this->input->post('sec_parent_relationship', TRUE);
		if ($sec_parent_relationship == "other") {
			$sec_parent_relationship = $this->input->post('sec_parent_relationship', TRUE);
		} else {
			$sec_parent_relationship = $sec_parent_relationship;
		}
		$data = array(
			'school_id' => school_id,
			'name' => ucwords($this->input->post('parent_name', TRUE)),
			'sex' => ucwords($this->input->post('parent_sex', TRUE)),
			'relationship' => ucwords($parent_relationship),
			'phone' => $this->input->post('parent_phone', TRUE),
			'email' => strtolower($this->input->post('parent_email', TRUE)),
			'sec_parent_name' => ucwords($this->input->post('sec_parent_name', TRUE)),
			'sec_parent_sex' => ucwords($this->input->post('sec_parent_sex', TRUE)),
			'sec_parent_relationship' => ucwords($sec_parent_relationship),
			'sec_parent_phone' => $this->input->post('sec_parent_phone', TRUE),
			'sec_parent_email' => strtolower($this->input->post('sec_parent_email', TRUE)),
		);
		$insert = $this->db->insert('parents', $data);
		$parent_id = $this->db->insert_id($insert);

		//insert parent email notifications
		$this->insert_parent_email_notifications($parent_id); 

		return $parent_id;
	}
 

	private function insert_parent_email_notifications($parent_id) {
    	$data = array(
    		'school_id' => school_id,
			'parent_id' => $parent_id,
			'child_absence' => 'true',
			'newsletters' => 'true',
		);
    	return $this->db->insert('parent_email_notifs', $data);	
    }

	
	private function generate_reg_id($student_id) {
		//reg id format: QSM17000002
		$prefix = 'QSM';
		$school_id = school_id;
		switch ($student_id) {
			case in_array($student_id, range(1, 9)):  //1 to 9, prepend 5 zeros to id
				$reg_id = $prefix . $school_id . '00000' . $student_id;
			break;
			case in_array($student_id, range(10, 99)):  //10 to 99, prepend 4 zeros to id
				$reg_id = $prefix . $school_id . '0000' . $student_id;
			break;
			case in_array($student_id, range(100, 999)):  //100 to 999, prepend 3 zeros to id
				$reg_id = $prefix . $school_id . '000' . $student_id;
			break;
			case in_array($student_id, range(1000, 9999)):  //1000 to 9999, prepend 2 zeros to id
				$reg_id = $prefix . $school_id . '00' . $student_id;
			break;
			case in_array($student_id, range(10000, 99999)):  //10000 to 99999, prepend 1 zero to id
				$reg_id = $prefix . $school_id . '0' . $student_id;
			break;
			default: //100000+, return id without prepending zeros
				$reg_id = $prefix . $school_id . $student_id;
			break;
		}
		return $reg_id;		
	}
	
	
	private function generate_pass_reset_code() {
		//generate random 4-digit code
		$pass_reset_code = mt_rand(1111, 9999);
		return $pass_reset_code;
	}
	
	
	
	/* ===== Student Edit ===== */
	public function edit_student($id, $passport_photo, $passport) { 
		//if nationality is Nigeria, collect state and lga values
		if ( s_country == 'Nigeria' ) {
			$state_of_origin = ucwords($this->input->post('state_of_origin', TRUE));
			$local_gov = ucwords($this->input->post('local_gov', TRUE));
		} else {
			$state_of_origin = NULL;
			$local_gov = NULL;
		}	

		$data = array(
			'admission_id' => ucwords($this->input->post('admission_id', TRUE)),
			'last_name' => ucwords($this->input->post('last_name', TRUE)),
			'first_name' => ucwords($this->input->post('first_name', TRUE)),
			'other_name' => ucwords($this->input->post('other_name', TRUE)),
			'class_id' => ucwords($this->input->post('class_id', TRUE)),
			'date_of_birth' => $this->input->post('date_of_birth', TRUE),
			'sex' => ucfirst($this->input->post('sex', TRUE)),
			'blood_group' => ucwords($this->input->post('blood_group', TRUE)),
			'place_of_birth' => ucwords($this->input->post('place_of_birth', TRUE)),
			'nationality' => ucwords($this->input->post('nationality', TRUE)),
			'state_of_origin' => $state_of_origin,
			'local_gov' => $local_gov,
			'home_town' => ucwords($this->input->post('home_town', TRUE)),
			'home_address' => ucwords($this->input->post('home_address', TRUE)),
			'religion' => ucwords($this->input->post('religion', TRUE)),
			'present_school' => ucwords($this->input->post('present_school', TRUE)),
			'present_school_address' => ucwords($this->input->post('present_school_address', TRUE)),
			'present_class' => ucwords($this->input->post('present_class', TRUE)),
			'admission_date' => $this->input->post('admission_date', TRUE),
			'additional_info' => $this->input->post('additional_info', TRUE),
			'passport_photo' => $passport_photo,
			'passport' => $passport,
		);
		$this->db->where('id', $id);	
		return $this->db->update('students', $data);
	}


	/* ====== Associate Parent  ====== */
	public function get_matched_parents($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		$last_name = $y->last_name;
		//get parents whose name matches student's lastname
		$this->db->where('school_id', school_id);
		//first parent
        $this->db->like('name', $last_name, 'both'); //wildcard %name%
        $this->db->limit(10);
       	$this->db->order_by('rand()');
		return $this->db->get('parents')->result();
    }


	public function associate_parent_search($keyword) {
		$this->db->where('school_id', school_id);
		//first parent
        $this->db->like('name', $keyword, 'both'); //wildcard %name%
        $this->db->or_like('email', $keyword, 'both'); //wildcard %email%
        $this->db->limit(100);
       	$this->db->order_by('rand()');
		return $this->db->get('parents')->result_array();
    }


    public function associate_parent($student_id, $parent_id) { 
		$data = array(
			'parent_id' => $parent_id
		);
		$this->db->where('id', $student_id);
		return $this->db->update('students', $data);
	}




	

	/* ========== Attendance ========== */
	private function where_array($session, $term, $class_id, $student_id) { 
		$where = array(
			'student_id' => $student_id,
			'class_id' => $class_id,
			'session' => $session,
			'term' => $term,
		);
		return $where;
	}
	
	
	public function check_attendance($student_id, $date) {
    	$where = array(
			'student_id' => $student_id,
			'date' => $date,
		);
    	return $this->db->get_where('attendance', $where)->row();	
    }


    public function get_recent_attendance($session, $term, $class_id, $student_id, $limit) {
    	$this->db->limit($limit);
    	$this->db->order_by('date', 'DESC');
    	$where = $this->where_array($session, $term, $class_id, $student_id);
    	return $this->db->get_where('attendance', $where)->result();	
    }


	public function mark_student_present($session, $term, $class_id, $id) {
		//check if this student has been marked absent for today
		$query = $this->check_attendance_marked($session, $term, $class_id, $id, 'Absent');
		if ($query == 0) { //no attendance data for the selected date
			return $this->insert_attendance($session, $term, $class_id, $id, 'Present');  //insert attendance data
		} else { //student has been marked Absent for the selected date 
			return $this->update_attendance($session, $term, $class_id, $id, 'Present'); //update student's attendance to Present
		}
    } 


    public function mark_student_absent($session, $term, $class_id, $id) {
		//check if this student has been marked present for today
		$query = $this->check_attendance_marked($session, $term, $class_id, $id, 'Present');
		if ($query == 0) { //no attendance data for the selected date
			$this->insert_attendance($session, $term, $class_id, $id, 'Absent');  //insert attendance data
		} else { //student has been marked Present for the selected date 
			$this->update_attendance($session, $term, $class_id, $id, 'Absent'); //update student's attendance to Absent
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


    public function insert_attendance($session, $term, $class_id, $id, $status) {
		$data = array(
			'school_id' => school_id,
			'student_id' => $id,
			'class_id' => $class_id,
			'session' => $session,
			'term' => $term,
			'date' => $this->input->post('date', TRUE),
			'status' => $status,
		);
		return $this->db->insert('attendance', $data);	
    }


    public function update_attendance($session, $term, $class_id, $id, $status) {
		$y = $this->common_model->get_student_details_by_id($id);
		$where = array(
			'student_id' => $id,
			'class_id' => $class_id,
			'session' => $session,
			'term' => $term,
			'date' => $this->input->post('date', TRUE),
		);
		$data = array(
			'status' => $status,
		);
		$this->db->where($where);
		return $this->db->update('attendance', $data);	
    }
	

	public function check_attendance_marked($session, $term, $class_id, $id, $status) {
    	//check if student has been marked present/absent for the selected date
		$where = array(
			'student_id' => $id,
			'session' => $session,
			'term' => $term,
			'date' => $this->input->post('date', TRUE),
			'status' => $status,
		);
		return $this->db->get_where('attendance', $where)->num_rows();	
    }


    public function get_student_attendance($session, $term, $class_id, $id) {
    	$where = $this->where_array($session, $term, $class_id, $id);
		$this->db->order_by('date', 'desc');
		return $this->db->get_where('attendance', $where)->result();	
    }


    public function delete_attendance($att_id) {
		return $this->db->delete('attendance', array('id' => $att_id));
    } 
	
	



	
	/* ========== Other Actions ========== */
	public function message_parent($id) {
		$message = nl2br(ucfirst($this->input->post('message', TRUE))); 
		$subject = 'Message from Admin';
		$y = $this->common_model->get_student_details_by_id($id);
		$parent_id = $y->parent_id;
		$parent_email = $this->common_model->get_parent_details_by_id($parent_id)->email;
		return email_user($parent_email, $subject, $message); //email parent
    } 
	
	
	public function suspend_student($id) {
		$data = array(
			'suspended' => 'true',
			'suspension_info' => ucfirst($this->input->post('suspension_info', TRUE)),
			'suspension_duration' => ucwords($this->input->post('suspension_duration', TRUE)),
			'date_suspended' => default_calendar_date(), //today
		);
		$this->db->where('id', $id);
		return $this->db->update('students', $data);	
    } 
	
	
	public function unsuspend_student($id) {
		$data = array(
			'suspended' => 'false',
			'suspension_info' => NULL,
			'suspension_duration' => NULL,
			'date_suspended' => NULL,
		);
		$this->db->where('id', $id);
		return $this->db->update('students', $data);	
    } 
	
	
	public function revoke_student_admission($id) {
		$data = array(
			'revoked' => 'true',
			'revoke_info' => ucfirst($this->input->post('revoke_info', TRUE)),
			'date_revoked' => default_calendar_date(), //today
		);
		$this->db->where('id', $id);
		return $this->db->update('students', $data);	
    } 
	
	
	public function unrevoke_student_admission($id) {
		$data = array(
			'revoked' => 'false',
			'revoke_info' => NULL,
			'date_revoked' => NULL,
		);
		$this->db->where('id', $id);
		return $this->db->update('students', $data);	
    } 


    public function graduate_student($id) {
    	$y = $this->common_model->get_student_details_by_id($id);
		$data = array(
			'graduated' => 'true',
			'graduated_from' => $y->class_id,
			'date_graduated' => default_calendar_date(), //today
		);
		$this->db->where('id', $id);
		return $this->db->update('students', $data);	
    } 


    public function re_admit_graduated_student($id) {
    	$y = $this->common_model->get_student_details_by_id($id);
    	$class_id = $this->input->post('class_id', TRUE);
		$data = array(
			'class_id' => $class_id,
			'graduated' => 'false',
			'graduated_from' => NULL,
			'date_graduated' => NULL,
		);
		$this->db->where('id', $id);
		return $this->db->update('students', $data);	
    } 


    public function promote_student($id) {
		$data = array(
			'class_id' => $this->input->post('class_id', TRUE),
			'last_promoted' => $this->input->post('last_promoted', TRUE),
		);
		$this->db->where('id', $id);
		return $this->db->update('students', $data);	
    } 
	
	
	public function delete_student_passport($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		if ($y->passport_photo != NULL && $y->passport != NULL) {
			unlink('./assets/uploads/photos/students/'.$y->passport_photo); //delete the passport photo
			unlink('./assets/uploads/photos/students/'.$y->passport); //delete the passport
		}
    } 
	
	
	public function delete_student($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		$this->delete_student_passport($id); //remove image files from server
		return $this->db->delete('students', array('id' => $id));
    } 
	
	
	public function bulk_actions_students() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		$date_today = date('d M, Y');
		foreach ($row_id as $id) {
			switch ($bulk_action_type) {
				case 'promote':
					$this->promote_bulk_student($id);
				break;
				case 'unsuspend':
					$this->unsuspend_student($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} students unsuspend successfully.");
				break;
				case 'unrevoke':
					$this->unrevoke_student_admission($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} students unrevoked successfully.");
				break;
				case 'graduate':
					$this->graduate_student($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} students graduated successfully.");
				break;
				case 'delete':
					$this->delete_student($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} students deleted successfully.");
				break;
			}
		} 
	}
	
	
	public function promote_bulk_student($id) { 
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$y = $this->common_model->get_student_details_by_id($id);
		$current_class_id = $y->class_id;
		$new_class_id = $this->input->post('class_id', TRUE);
		$new_class = $this->common_model->get_class_details($new_class_id)->class;
		if ($this->form_validation->run()) {
			//check if the current class is different from the new class
			if ($current_class_id != $new_class_id) {
				$this->promote_student($id);
				$this->session->set_flashdata('status_msg', "{$selected_rows} students promoted to {$new_class} successfully.");
			} else {
				$this->session->set_flashdata('status_msg_error', "Students cannot be promoted to current class.");
			}
		} else {
			$this->session->set_flashdata('status_msg_error', "Error promoting students.");
		}
	}
	

	
	
}