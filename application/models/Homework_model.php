<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== */
/*
Name: Homework_admin_model
Role: Model
Description: Controls the DB processes of home work from the admin/staff/student/parent end
Controller: Homework_admin, Homework_class_teacher, Homework_subject_teacher, Homework_student, Homework_parent
Authors: Nwankwo Ikemefuna
Date Created: 22nd June, 2018
*/


class Homework_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	
	


	public function get_homework_details($homework_id) {
		return $this->db->get_where('homework', array('id' => $homework_id))->row();	
	}

	
	public function get_homework($class_id) {
		return $this->db->get_where('homework', array('class_id' => $class_id))->result();	
	}


	public function get_school_homework2() {
		$this->db->distinct();
		$this->db->where('school_id', school_id);
		return $this->db->get('homework')->result();	
	}


	public function get_school_homework() {
		$this->db->select('*');
		$this->db->from('homework');
		$this->db->where('school_id', school_id);
	 	$this->db->distinct('session');
		$query = $this->db->get();
		return $query->result();
	}


	public function get_homework_list($class_id, $limit, $offset) {		
		$this->db->limit($limit, $offset); //limit to be used as per_page, offset to be used as pagination segment
		$this->db->order_by("date_added", "desc"); 
		$this->db->where('class_id', $class_id);
		$query = $this->db->get_where('homework');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
            return false;
    }
	

	public function new_homework($material) { 
		$class_id = $this->input->post('class_id', TRUE); 
		$subject_id = $this->input->post('subject_id', TRUE); 
		$submission_date = $this->input->post('submission_date', TRUE); 
		$homework = ucfirst($this->input->post('homework', TRUE));
		$snippet = mb_strimwidth(strip_tags($homework), 0, 300, "...");
		$the_homework = nl2br($homework); 
		$additional_message = ucfirst($this->input->post('additional_message', TRUE)); 
		$data = array(
			'school_id' => school_id,
			'class_id' => $class_id,
			'subject_id' => $subject_id,
			'submission_date' => $submission_date,
			'snippet' => $snippet,
			'the_homework' => $the_homework,
			'additional_message' => $additional_message,
			'session' => current_session_slug,
			'term' => current_term,
			'material' => $material,
		);
		return $this->db->insert('homework', $data);
	}


	public function edit_homework($homework_id, $material) { 
		$subject_id = $this->input->post('subject_id', TRUE); 
		$submission_date = $this->input->post('submission_date', TRUE); 
		$homework = ucfirst($this->input->post('homework', TRUE));
		$snippet = mb_strimwidth(strip_tags($homework), 0, 300, "...");
		$the_homework = nl2br($homework); 
		$additional_message = ucfirst($this->input->post('additional_message', TRUE)); 
		$data = array(
			'subject_id' => $subject_id,
			'submission_date' => $submission_date,
			'snippet' => $snippet,
			'the_homework' => $the_homework,
			'additional_message' => $additional_message,
			'material' => $material,
		);
		$this->db->where('id', $homework_id);
		return $this->db->update('homework', $data);
	}


	public function delete_homework_material_file($homework_id) {
		$y = $this->get_homework_details($homework_id);
		unlink('./assets/uploads/homework/'.$y->material); 
    } 


    public function delete_homework_material($homework_id) {
    	$y = $this->get_homework_details($homework_id);
		unlink('./assets/uploads/homework/'.$y->material); 
    	$data = array(
			'material' => NULL
		);
		$this->db->where('id', $homework_id);
		$this->db->update('homework', $data);
    }


	public function delete_homework($homework_id) {
		//delete material
		$this->delete_homework_material_file($homework_id);
		return $this->db->delete('homework', array('id' => $homework_id));
    } 





    public function clear_homework($session, $term) {
    	$where = array(
    		'school_id' => school_id,
    		'session' => $session,
    		'term' => $term,
    	);
    	$term_homework = $this->db->get_where('homework', $where)->result();
		foreach ($term_homework as $h) {
			$homework_id = $h->id;
			//delete homework
			$this->delete_homework($homework_id);
		}
		return $this->db->affected_rows();
    } 


    public function bulk_actions_homework() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		foreach ($row_id as $homework_id) {
			switch ($bulk_action_type) {
				case 'delete':
					$this->delete_homework($homework_id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} homework deleted successfully.");
				break;
			}
		} 
	}


	

	
}