<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Staff_import_model
Role: Model
Description: Controls the DB processes of staff imports
Controller: Staff_import
Author: Nwankwo Ikemefuna
Date Created: 13th August, 2018
*/

class Staff_import_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}



	
	/* ===== staff Import: Temporary ===== */
	public function insert_imported_staff($data = array()) { 
		return $this->db->insert_batch('imported_staff', $data);
	}


	public function check_imported_staff() {
		$imported_staff = $this->get_imported_staff();
		$total_imported_staff = count($imported_staff);
		//check if there is unactioned import
		if ($total_imported_staff == 0) {
			return TRUE;
		} else {
			$imports = ($total_imported_staff == 1) ? 'import' : 'imports';
			$this->session->set_flashdata('status_msg_error', "You have {$total_imported_staff} uncompleted {$imports}.");
			//redirect to import staff page
			redirect('staff_import/import_staff');
		}
	}


	public function delete_imported_staff_data() { 
		return $this->db->delete('imported_staff', array('school_id' => school_id));
	}


	public function delete_imported_file($file_path) { 
		return unlink($file_path);
	}


	public function get_imported_staff_details_by_id($id) { 
		return $this->db->get_where('imported_staff', array('id' => $id))->row();
	}


	public function get_imported_staff_fullname($id) { 
		$y = $this->get_imported_staff_details_by_id($id);
		$fullname = $y->title . ' ' . $y->name;
		return $fullname;
	}


	public function get_imported_staff() { 
		return $this->db->get_where('imported_staff', array('school_id' => school_id))->result();
	}


	public function edit_staff($id) { 
		$data = array(
			'title' => ucfirst($this->input->post('title', TRUE)),
			'name' => ucwords($this->input->post('name', TRUE)),
			'email' => strtolower($this->input->post('email', TRUE)),
			'phone' => $this->input->post('phone', TRUE),
			'nationality' => ucwords($this->input->post('nationality', TRUE)),
			'state_of_origin' => ucwords($this->input->post('state_of_origin', TRUE)),
			'local_gov' => ucwords($this->input->post('local_gov', TRUE)),
			'designation' => ucwords($this->input->post('designation', TRUE)),
			'date_of_birth' => $this->input->post('date_of_birth', TRUE),
			'sex' => ucfirst($this->input->post('sex', TRUE)),
			'home_address' => ucwords($this->input->post('home_address', TRUE)),
			'religion' => ucwords($this->input->post('religion', TRUE)),
			'qualification' => ucwords($this->input->post('qualification', TRUE)),
			'employment_date' => $this->input->post('employment_date', TRUE),
			'name_of_kin' => ucwords($this->input->post('name_of_kin', TRUE)),
			'email_of_kin' => strtolower($this->input->post('email_of_kin', TRUE)),
			'mobile_of_kin' => $this->input->post('mobile_of_kin', TRUE),
			'address_of_kin' => ucwords($this->input->post('address_of_kin', TRUE)),
			'acc_number' => $this->input->post('acc_number', TRUE),
			'bank_name' => ucwords($this->input->post('bank_name', TRUE)),
			'additional_info' => ucfirst($this->input->post('additional_info', TRUE)),
		);
		$this->db->where('id', $id);	
		return $this->db->update('imported_staff', $data);	
	}


	public function delete_staff($id) {
		return $this->db->delete('imported_staff', array('id' => $id));
    } 
	
	
	public function bulk_actions_staff() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		foreach ($row_id as $id) {
			switch ($bulk_action_type) {
				case 'delete':
					$this->delete_staff($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} staff deleted successfully.");
				break;
			}
		} 
	}



	/* ===== staff Import: Permanent ===== */
	public function insert_staff_record($data = array()) { //complete import
		return $this->db->insert_batch('staff', $data);
	}


	public function cancel_import() { 
		return $this->db->delete('imported_staff', array('school_id' => school_id));
    } 


    public function get_required_field($field) {
		$imported_staff = $this->get_imported_staff();
		$count = 0;
	    foreach ($imported_staff as $s) {
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
		$total_imported_staff = count($this->get_imported_staff());
		//check if all cells under this column were filled
		if ($required_fields == $total_imported_staff) {
			return TRUE;
		} else {
			$empty_fields = $total_imported_staff - $required_fields;
			$cells = ($empty_fields == 1) ? 'cell' : 'cells';
			$this->session->set_flashdata('status_msg_error', "Error! {$empty_fields} {$cells} in the {$title} column is missing data.");
			//redirect to imported staff page
			redirect('staff_import/imported_staff');
		}
	}


	public function required_field_title_message($field, $title) {
		$required_fields = $this->get_required_field($field);
		$total_imported_staff = count($this->get_imported_staff());
		//check if all cells under this column were filled
		if ($required_fields == $total_imported_staff) {
			return '<i class="fa fa-check text-success text-bold m-l-10" title="OK"></i>';
		} else {
			$empty_fields = $total_imported_staff - $required_fields;
			$cells = ($empty_fields == 1) ? 'cell' : 'cells';
			$message = "{$empty_fields} {$cells} in the {$title} column is missing data.";
			return '<i class="fa fa-times text-danger text-bold m-l-10" title="' . $message . '"></i>';
		}
	}


	public function get_total_valid_emails() {
		//ensure email does not already exist in staff table (entire, not just for school)
		$staff = $this->db->get_where('staff')->result();
		$emails = "";
		foreach ($staff as $s) {
			$emails .= $s->email . ", "; 
		}
		//get the emails as an array
		$emails = explode(", ", $emails);

		$imported_staff = $this->get_imported_staff();
		$count = 0;
	    foreach ($imported_staff as $s) {
	    	$imported_email = $s->email;
	    	//check if the email imported is not in the list of already existing emails
			if ( ! in_array($imported_email, $emails) ) {
				//increment count
				$count++;
			} else {
				$count = $count;
			}
	    }
	    return $count;
	}


	public function validate_emails() {
		//check if email is ok (not existing)
		$valid_emails = $this->get_total_valid_emails();
		$imported_staff = $this->get_imported_staff();
		$total_imported_staff = count($imported_staff);
		//check if total valid emails tallies with number of imported staff
		if ($valid_emails == $total_imported_staff) {
			return TRUE;
		} else {
			$total_existing = $total_imported_staff - $valid_emails;
			$emails = ($total_existing == 1) ? 'email' : 'emails';
			$exists = ($total_existing == 1) ? 'exists' : 'exist';
			$this->session->set_flashdata('status_msg_error', "Error! {$total_existing} {$emails} already {$exists}.");
			//redirect to imported staff page
			redirect('staff_import/imported_staff');
		}
	}


	public function email_no_conflict_message() {
		//check if email is ok (not existing)
		$valid_emails = $this->get_total_valid_emails();
		$imported_staff = $this->get_imported_staff();
		$total_imported_staff = count($imported_staff);
		//check if total valid emails tallies with number of imported staff
		if ($valid_emails == $total_imported_staff) {
			return '<i class="fa fa-check text-success text-bold m-l-10" title="No conflict"></i>';
		} else {
			$total_existing = $total_imported_staff - $valid_emails;
			$emails = ($total_existing == 1) ? 'email' : 'emails';
			$exists = ($total_existing == 1) ? 'exists' : 'exist';
			$message = "{$total_existing} {$emails} already {$exists}.";
			return '<i class="fa fa-times text-danger text-bold m-l-10" title="' . $message . '"></i>';
		}
	}


	public function flag_conflicting_email($email) {
		$query = $this->db->get_where('staff', array('email' => $email)); //all staff, not just for school
		if ($query->num_rows() > 0) {
			$assigned_to = $query->row()->name;
			$message = "Conflict found! email already exists.";
			return '<div class="pull-right badge" style="background: #a94442; cursor: pointer" title="' . $message . '">C</div>';
		}
    } 


    public function validate_duplicate_email() {
		$staff = $this->db->get_where('imported_staff', array('school_id' => school_id))->result();
		$emails = "";
		foreach ($staff as $s) {
			$emails .= $s->email . ", "; 
		}
		//get the emails as an array
		$emails = explode(", ", $emails);
		$total_emails = count($emails);
		$total_unique_emails = count(array_unique($emails));
		//check for duplicates
		if ($total_unique_emails == $total_emails) {
			return TRUE;
		} else {
			$this->session->set_flashdata('status_msg_error', "Error! Duplicate emails found.");
			//redirect to imported staff page
			redirect('staff_import/imported_staff');
		}
	}


	public function email_no_duplicate_message() {
		$staff = $this->db->get_where('imported_staff', array('school_id' => school_id))->result();
		$emails = "";
		foreach ($staff as $s) {
			$emails .= $s->email . ", "; 
		}
		//get the emails as an array
		$emails = explode(", ", $emails);
		$total_emails = count($emails);
		$total_unique_emails = count(array_unique($emails));
		//check for duplicates
		if ($total_unique_emails == $total_emails) {
			return '<i class="fa fa-check text-success text-bold m-l-10" title="No duplicate"></i>';
		} else {
			$message = "Duplicate emails found";
			return '<i class="fa fa-times text-danger text-bold m-l-10" title="' . $message . '"></i>';
		}
	}

	
	public function flag_duplicate_email($email) {
    	$query = $this->db->get_where('imported_staff', array('school_id' => school_id, 'email' => $email)); //all imported staff, not just for school
		if ($query->num_rows() > 1) {
			$message = "Duplicate found!";
			return '<div class="pull-right badge" style="background: #a94442; cursor: pointer" title="' . $message . '">D</div>';
		}
    } 

	

}