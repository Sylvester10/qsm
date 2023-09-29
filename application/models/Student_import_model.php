<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Student_import_model
Role: Model
Description: Controls the DB processes of student imports
Controller: Data_imports
Author: Nwankwo Ikemefuna
Date Created: 13th August, 2018
*/

class Student_import_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}



	
	/* ===== Student Import: Temporary ===== */
	public function insert_imported_students($data = array()) { 
		return $this->db->insert_batch('imported_students', $data);
	}


	public function check_imported_students() {
		$imported_students = $this->get_imported_students();
		$total_imported_students = count($imported_students);
		//check if there is unactioned import
		if ($total_imported_students == 0) {
			return TRUE;
		} else {
			$imports = ($total_imported_students == 1) ? 'import' : 'imports';
			$this->session->set_flashdata('status_msg_error', "You have {$total_imported_students} uncompleted {$imports}.");
			//redirect to import students page
			redirect('student_import/import_students');
		}
	}


	public function delete_imported_students_data() { 
		return $this->db->delete('imported_students', array('school_id' => school_id));
	}


	public function delete_imported_file($file_path) { 
		return unlink($file_path);
	}


	public function get_imported_student_details_by_id($id) { 
		return $this->db->get_where('imported_students', array('id' => $id))->row();
	}


	public function get_imported_student_fullname($id) { 
		$y = $this->get_imported_student_details_by_id($id);
		$fullname = $y->last_name . ' ' . $y->first_name . ' ' . $y->other_name;
		return $fullname;
	}


	public function get_imported_students() { 
		return $this->db->get_where('imported_students', array('school_id' => school_id))->result();
	}


	public function get_imported_student_class($id) {
		$y = $this->get_imported_student_details_by_id($id);
		$class_id = $y->class_id;
		//check if class exists
		$class_details = $this->common_model->get_class_details($class_id);
		return  $class_details ? $class_details->class : NULL;
	}


	public function edit_student($id) { 
		//if nationality is Nigeria, collect state and lga values
		if ( s_country == 'Nigeria' ) {
			$state_of_origin = ucwords($this->input->post('state_of_origin', TRUE));
			$local_gov = ucwords($this->input->post('local_gov', TRUE));
		} else {
			$state_of_origin = NULL;
			$local_gov = NULL;
		}		
		
		$data = array(
			'admission_id' => strtoupper($this->input->post('admission_id', TRUE)),
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
			'parent_name' => ucwords($this->input->post('parent_name', TRUE)),
			'parent_sex' => ucwords($this->input->post('parent_sex', TRUE)),
			'parent_relationship' => ucwords($this->input->post('parent_relationship', TRUE)), 
			'parent_phone' => $this->input->post('parent_phone', TRUE),
			'parent_email' => strtolower($this->input->post('parent_email', TRUE)),
			'sec_parent_name' => ucwords($this->input->post('sec_parent_name', TRUE)),
			'sec_parent_sex' => ucwords($this->input->post('sec_parent_sex', TRUE)),
			'sec_parent_relationship' => ucwords($this->input->post('sec_parent_relationship', TRUE)),
			'sec_parent_phone' => $this->input->post('sec_parent_phone', TRUE),
			'sec_parent_email' => strtolower($this->input->post('sec_parent_email', TRUE)),
			'additional_info' => ucfirst($this->input->post('additional_info', TRUE)),
			'admission_date' => $this->input->post('admission_date', TRUE),
		);
		$this->db->where('id', $id);	
		return $this->db->update('imported_students', $data);	
	}


	public function delete_student($id) {
		return $this->db->delete('imported_students', array('id' => $id));
    } 
	
	
	public function bulk_actions_students() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		$students = ($selected_rows == 1) ? 'student' : 'students';
		foreach ($row_id as $id) {
			switch ($bulk_action_type) {
				case 'delete':
					$this->delete_student($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$students} deleted successfully.");
				break;
			}
		} 
	}



	/* ===== Student Import: Permanent ===== */
	public function insert_student_data($student_data, $parent_data, $parent_email) { //complete import
		$insert = $this->db->insert('students', $student_data);
		$student_id = $this->db->insert_id($insert);

		//insert parent details
		if ($parent_email != '') { //parent email is supplied
			//check if parent email already exists (in the case where parent has more than 1 child in this school)
			$query_parent = $this->common_model->get_parent_details($parent_email);
			if ( ! $query_parent) { //email doesn't exists, insert the parent details and retrieve insert ID
				$parent_id = $this->insert_parent_details($parent_data); 
			} else { //email exists, get parent ID
				$parent_id = $query_parent->id;
			}
		} else { //parent email is not supplied, insert parent details
			$parent_id = $this->insert_parent_details($parent_data); 
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


	public function insert_parent_details($parent_data) { //complete import
		$insert = $this->db->insert('parents', $parent_data);
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
	
	
	public function generate_reg_id($student_id) {
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


	public function cancel_import() { 
		return $this->db->delete('imported_students', array('school_id' => school_id));
    } 


    public function get_required_field($field) {
		$imported_students = $this->get_imported_students();
		$count = 0;
	    foreach ($imported_students as $s) {
	    	//get required fields
	    	if ($s->$field != '') {
				//increment count
				$count++;
			} else {
				$count = $count;
			}
	    }
	    return $count;
	}


	public function validate_required_field($field, $title) {
		$required_fields = $this->get_required_field($field);
		$total_imported_students = count($this->get_imported_students());
		//check if all cells under this column were filled
		if ($required_fields == $total_imported_students) {
			return TRUE;
		} else {
			$empty_fields = $total_imported_students - $required_fields;
			$cells = ($empty_fields == 1) ? 'cell' : 'cells';
			$this->session->set_flashdata('status_msg_error', "Error! {$empty_fields} {$cells} in the {$title} column is missing data.");
			//redirect to imported students page
			redirect('student_import/imported_students');
		}
	}


	public function required_field_title_message($field, $title) {
		$required_fields = $this->get_required_field($field);
		$total_imported_students = count($this->get_imported_students());
		//check if all cells under this column were filled
		if ($required_fields == $total_imported_students) {
			return '<i class="fa fa-check text-success text-bold m-l-10" title="OK"></i>';
		} else {
			$empty_fields = $total_imported_students - $required_fields;
			$cells = ($empty_fields == 1) ? 'cell' : 'cells';
			$message = "{$empty_fields} {$cells} in the {$title} column is missing data.";
			return '<i class="fa fa-times text-danger text-bold m-l-10" title="' . $message . '"></i>';
		}
	}


	public function get_total_valid_admission_idx() {
		//ensure admission ID does not already exist in students table
		$students = $this->db->get_where('students', array('school_id' => school_id))->result();
		$admission_idx = "";
		foreach ($students as $s) {
			$admission_idx .= $s->admission_id . ", "; 
		}
		//get the admission IDs as an array
		$admission_idx = explode(", ", $admission_idx);

		$imported_students = $this->get_imported_students();
		$count = 0;
	    foreach ($imported_students as $s) {
	    	$imported_admission_id = $s->admission_id;
	    	//check if the admission ID imported is not in the list of already existing admission IDs
			if ( ! in_array($imported_admission_id, $admission_idx) ) {
				//increment count
				$count++;
			} else {
				$count = $count;
			}
	    }
	    return $count;
	}


	public function validate_admission_idx() {
		//check if admission ID is ok (not existing)
		$valid_admission_idx = $this->get_total_valid_admission_idx();
		$imported_students = $this->get_imported_students();
		$total_imported_students = count($imported_students);
		//check if total valid admission IDs tallies with number of imported students
		if ($valid_admission_idx == $total_imported_students) {
			return TRUE;
		} else {
			$total_existing = $total_imported_students - $valid_admission_idx;
			$admission_idx = ($total_existing == 1) ? 'Admission ID' : 'Admission IDs';
			$exists = ($total_existing == 1) ? 'exists' : 'exist';
			$this->session->set_flashdata('status_msg_error', "Error! {$total_existing} {$admission_idx} already {$exists}.");
			//redirect to imported students page
			redirect('student_import/imported_students');
		}
	}


	public function admission_id_no_conflict_message() {
		//check if admission ID is ok (not existing)
		$valid_admission_idx = $this->get_total_valid_admission_idx();
		$imported_students = $this->get_imported_students();
		$total_imported_students = count($imported_students);
		//check if total valid admission IDs tallies with number of imported students
		if ($valid_admission_idx == $total_imported_students) {
			return '<i class="fa fa-check text-success text-bold m-l-10" title="No conflict"></i>';
		} else {
			$total_existing = $total_imported_students - $valid_admission_idx;
			$admission_idx = ($total_existing == 1) ? 'Admission ID' : 'Admission IDs';
			$exists = ($total_existing == 1) ? 'exists' : 'exist';
			$message = "{$total_existing} {$admission_idx} already {$exists}.";
			return '<i class="fa fa-times text-danger text-bold m-l-10" title="' . $message . '"></i>';
		}
	}
	
	
	public function flag_conflicting_admission_id($admission_id) {
		$query = $this->db->get_where('students', array('school_id' => school_id, 'admission_id' => $admission_id));
		if ($query->num_rows() > 0) {
			$assigned_to = $query->row()->last_name . ' ' . $query->row()->first_name . ' ' . $query->row()->other_name;
			$message = "Conflic found! Admission ID already assigned to {$assigned_to}";
			return '<div class="pull-right badge" style="background: #a94442; cursor: pointer" title="' . $message . '">C</div>';
		}
    } 
	
	
	public function validate_duplicate_admission_id() {
		$students = $this->db->get_where('imported_students', array('school_id' => school_id))->result();
		$admission_idx = "";
		foreach ($students as $s) {
			$admission_idx .= $s->admission_id . ", "; 
		}
		//get the admission IDs as an array
		$admission_idx = explode(", ", $admission_idx);
		$total_admission_idx = count($admission_idx);
		$total_unique_admission_idx = count(array_unique($admission_idx));
		//check for duplicates
		if ($total_unique_admission_idx == $total_admission_idx) {
			return TRUE;
		} else {
			$this->session->set_flashdata('status_msg_error', "Error! Duplicate admission IDs found.");
			//redirect to imported student page
			redirect('student_import/imported_student');
		}
	}


	public function admission_id_no_duplicate_message() {
		$students = $this->db->get_where('imported_students', array('school_id' => school_id))->result();
		$admission_idx = "";
		foreach ($students as $s) {
			$admission_idx .= $s->admission_id . ", "; 
		}
		//get the admission IDs as an array
		$admission_idx = explode(", ", $admission_idx);
		$total_admission_idx = count($admission_idx);
		$total_unique_admission_idx = count(array_unique($admission_idx));
		//check for duplicates
		if ($total_unique_admission_idx == $total_admission_idx) {
			return '<i class="fa fa-check text-success text-bold m-l-10" title="No duplicate"></i>';
		} else {
			$message = "Duplicate admission IDs found";
			return '<i class="fa fa-times text-danger text-bold m-l-10" title="' . $message . '"></i>';
		}
	}
	
	
	public function flag_duplicate_admission_id($admission_id) {
    	//ensure no duplicate admission ID in this school
		$query = $this->db->get_where('imported_students', array('school_id' => school_id, 'admission_id' => $admission_id)); 
		if ($query->num_rows() > 1) {
			$message = "Duplicate found!";
			return '<div class="pull-right badge" style="background: #a94442; cursor: pointer" title="' . $message . '">D</div>';
		}
    } 


	public function get_total_valid_class_idx() {
		//check if class ID is valid
		$classes = $this->common_model->get_classes(school_id);
		$class_idx = "";
		foreach ($classes as $c) {
			$class_idx .= $c->id . ", "; 
		}
		//get the IDs as an array
		$class_idx = explode(", ", $class_idx);

		$imported_students = $this->get_imported_students();
		$count = 0;
	    foreach ($imported_students as $s) {
	    	$imported_class_id = $s->class_id;
	    	//check if the class ID imported is in the list of class IDs
			if ( in_array($imported_class_id, $class_idx) ) {
				//increment count
				$count++;
			} else {
				$count = $count;
			}
	    }
	    return $count;
	}


	public function validate_class_idx() {
		//check if class ID is valid
		$valid_class_idx = $this->get_total_valid_class_idx();
		$imported_students = $this->get_imported_students();
		$total_imported_students = count($imported_students);
		//check if total valid class IDs tallies with number of imported students
		if ($valid_class_idx == $total_imported_students) {
			return TRUE;
		} else {
			$unmatched = $total_imported_students - $valid_class_idx;
			$class_idx = ($unmatched == 1) ? 'class ID' : 'class IDs';
			$this->session->set_flashdata('status_msg_error', "Error! {$unmatched} {$class_idx} could not be matched to an existing class.");
			//redirect to imported students page
			redirect('student_import/imported_students');
		}
	}


	public function valid_class_id_title_message() {
		//check if class ID is valid
		$valid_class_idx = $this->get_total_valid_class_idx();
		$imported_students = $this->get_imported_students();
		$total_imported_students = count($imported_students);
		//check if total valid class IDs tallies with number of imported students
		if ($valid_class_idx == $total_imported_students) {
			return '<i class="fa fa-check text-success text-bold m-l-10" title="OK"></i>';
		} else {
			$unmatched = $total_imported_students - $valid_class_idx;
			$class_idx = ($unmatched == 1) ? 'class ID' : 'class IDs';
			$message = "{$unmatched} {$class_idx} could not be matched.";
			return '<i class="fa fa-times text-danger text-bold m-l-10" title="' . $message . '"></i>';
		}
	}


	public function flag_invalid_class_id($class_id) {
		$query = $this->db->get_where('classes', array('school_id' => school_id, 'id' => $class_id));
		if ($query->num_rows() == 0) {
			$message = "This Class ID could not be matched to an existing class.";
			return '<div class="pull-right badge" style="background: #a94442; cursor: pointer" title="' . $message . '">U</div>';
		}
    } 
	
	
	private function get_parent_emails_not_null() { 
		$where = array(
			'school_id' => school_id, 
			'parent_email !=' => NULL,
		);
		return $this->db->get_where('imported_students', $where)->result();
	}


    public function get_total_valid_parent_emails() {
		//ensure parent email does not conflict with parent email from a different school
		//get parents from other schools
		$parents = $this->db->get_where('parents', array('school_id !=' => school_id))->result();
		$parent_emails = "";
		foreach ($parents as $p) {
			$parent_emails .= $p->email . ", "; 
		}
		//get the emails as an array
		$parent_emails = explode(", ", $parent_emails);

		$imported_students = $this->get_parent_emails_not_null();
		$count = 0;
	    foreach ($imported_students as $s) {
	    	$imported_parent_email = $s->parent_email;
	    	//check that the imported parent email is not in the list of parent emails from other schools
			if ( ! in_array($imported_parent_email, $parent_emails) ) {
				//increment count
				$count++;
			} else {
				$count = $count;
			}
	    }
	    return $count;
	}


	public function validate_parent_emails() {
		$valid_parent_emails = $this->get_total_valid_parent_emails();
		$imported_students = $this->get_parent_emails_not_null();
		$total_imported_students = count($imported_students);
		//check if total valid parent emails tallies with number of imported students
		if ($valid_parent_emails == $total_imported_students) {
			return TRUE;
		} else {
			$total_existing = $total_imported_students - $valid_parent_emails;
			$parent_emails = ($total_existing == 1) ? 'email' : 'emails';
			$belongs = ($total_existing == 1) ? 'belongs to a parent' : 'belong to parents';
			$this->session->set_flashdata('status_msg_error', "Error! {$total_existing} {$parent_emails} {$belongs} from another school.");
			//redirect to imported students page
			redirect('student_import/imported_students');
		}
	}


	public function parent_email_no_conflict_message() {
		//check if admission ID is ok (not existing)
		$valid_parent_emails = $this->get_total_valid_parent_emails();
		$imported_students = $this->get_parent_emails_not_null();
		$total_imported_students = count($imported_students);
		//check if total valid admission IDs tallies with number of imported students
		if ($valid_parent_emails == $total_imported_students) {
			return '<i class="fa fa-check text-success text-bold m-l-10" title="No conflict"></i>';
		} else {
			$total_existing = $total_imported_students - $valid_parent_emails;
			$parent_emails = ($total_existing == 1) ? 'email' : 'emails';
			$belongs = ($total_existing == 1) ? 'belongs to a parent' : 'belong to parents';
			$message = "{$total_existing} {$parent_emails} {$belongs} from another school.";
			return '<i class="fa fa-times text-danger text-bold m-l-10" title="' . $message . '"></i>';
		}
	}
	
	
	public function flag_conflicting_parent_email($parent_email) {
		$query = $this->db->get_where('parents', array('school_id !=' => school_id, 'email' => $parent_email))->num_rows();
		if ($query > 0) {
			$message = "Conflic found! This email belongs to a parent from another school.";
			return '<div class="pull-right badge" style="background: #a94442; cursor: pointer" title="' . $message . '">C</div>';
		}
    } 



}