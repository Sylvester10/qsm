<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: Parents_model
Role: Model
Description: Controls the DB processes of parents from the admin's end
Controller: Parents
Author: Nwankwo Ikemefuna
Date Created: 5th September, 2018
*/


class Parents_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}




	public function new_parent() { 
		$data = array(
			'school_id' => school_id,
			'name' => ucwords($this->input->post('parent_name', TRUE)),
			'sex' => ucwords($this->input->post('parent_sex', TRUE)),
			'relationship' => ucwords($this->input->post('parent_relationship', TRUE)),
			'phone' => $this->input->post('parent_phone', TRUE),
			'email' => strtolower($this->input->post('parent_email', TRUE)),
			'sec_parent_name' => ucwords($this->input->post('sec_parent_name', TRUE)),
			'sec_parent_sex' => ucwords($this->input->post('sec_parent_sex', TRUE)),
			'sec_parent_relationship' => ucwords($this->input->post('sec_parent_relationship', TRUE)),
			'sec_parent_phone' => $this->input->post('sec_parent_phone', TRUE),
			'sec_parent_email' => strtolower($this->input->post('sec_parent_email', TRUE)),
		);
		$this->db->insert('parents', $data);
	}
	
	
		
	public function edit_parent($id) { 
		$data = array(
			'name' => ucwords($this->input->post('parent_name', TRUE)),
			'sex' => ucwords($this->input->post('parent_sex', TRUE)),
			'relationship' => ucwords($this->input->post('parent_relationship', TRUE)),
			'phone' => $this->input->post('parent_phone', TRUE),
			'email' => strtolower($this->input->post('parent_email', TRUE)),
			'sec_parent_name' => ucwords($this->input->post('sec_parent_name', TRUE)),
			'sec_parent_sex' => ucwords($this->input->post('sec_parent_sex', TRUE)),
			'sec_parent_relationship' => ucwords($this->input->post('sec_parent_relationship', TRUE)),
			'sec_parent_phone' => $this->input->post('sec_parent_phone', TRUE),
			'sec_parent_email' => strtolower($this->input->post('sec_parent_email', TRUE)),
		);
		$this->db->where('id', $id);
		$this->db->update('parents', $data);
	}


	public function message_parent($id) {
		$message = nl2br(ucfirst($this->input->post('message', TRUE))); 
		$subject = 'Message from Admin';
		$parent_email = $this->common_model->get_parent_details_by_id($id)->email;
		return email_user($parent_email, $subject, $message); //email parent
    }


    /* ====== Associate Parent  ====== */
	public function get_matched_students($id) {
		$y = $this->common_model->get_parent_details_by_id($id);
		$name = $y->name;
		$names = explode(' ', $name);
		$len_names = count($names);
		switch ($len_names) {
			case 1:
				$name1 = $names[0];
				$name2 = $names[0];
				$name3 = $names[0];
			break;
			case 2:
				$name1 = $names[1];
				$name2 = $names[1];
				$name3 = $names[1];
			break;
			case 3:
				$name1 = $names[1];
				$name2 = $names[2];
				$name3 = $names[2];
			break;
			case 4:
				$name1 = $names[1];
				$name2 = $names[2];
				$name3 = $names[3];
			break;
			default:
				$name1 = $names[1];
				$name2 = $names[2];
				$name3 = $names[3];
			break;
		}
		//get parents whose name matches student's lastname
		$this->db->where('school_id', school_id);
		//first parent
        $this->db->like('last_name', $name1, 'both'); //wildcard %name%
        $this->db->or_like('last_name', $name2, 'both'); //wildcard %name%
        $this->db->or_like('last_name', $name3, 'both'); //wildcard %name%
        $this->db->limit(10);
       	$this->db->order_by('rand()');
		return $this->db->get('students')->result();
    }


	public function associate_student_search($keyword) {
		$this->db->where('school_id', school_id);
		//first parent
        $this->db->like('last_name', $keyword, 'both'); //wildcard %name%
        $this->db->or_like('first_name', $keyword, 'both'); //wildcard %name%
        $this->db->or_like('other_name', $keyword, 'both'); //wildcard %name%
        $this->db->or_like('reg_id', $keyword, 'both'); //wildcard %reg_id%
        $this->db->or_like('admission_id', $keyword, 'both'); //wildcard %admission_id%
        $this->db->limit(100);
       	$this->db->order_by('rand()');
		return $this->db->get('students')->result_array();
    }


    public function associate_student($parent_id, $student_id) { 
		$data = array(
			'parent_id' => $parent_id
		);
		$this->db->where('id', $student_id);
		return $this->db->update('students', $data);
	} 


    public function delete_parent($id) {
		return $this->db->delete('parents', array('id' => $id));
    } 
	
	
	public function bulk_actions_parents() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		$date_today = date('d M, Y');
		foreach ($row_id as $id) {
			switch ($bulk_action_type) {
				case 'delete':
					$this->delete_parent($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} parents deleted successfully.");
				break;
			}
		} 
	}




}