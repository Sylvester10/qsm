<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: Incidents_model
Role: Model
Description: Controls the DB processes of incidents from the admin's end
Controller: Incidents
Author: Nwankwo Ikemefuna
Date Created: 18th June, 2018
*/


class Incidents_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}




	public function get_incident_details($incident_id) {
		return $this->db->get_where('incidents', array('id' => $incident_id))->row();
	}


	public function get_all_incidents() {	
		return $this->db->get_where('incidents', array('school_id' => school_id))->result();
	}


	public function get_student_incidents($student_id) {	
		return $this->db->get_where('incidents', array('student_id' => $student_id))->result();
	}
	

	
	/* ===== New Incident ===== */
	public function new_incident($student_id) { 
		$y = $this->common_model->get_student_details_by_id($student_id);
		$class_id = $y->class_id;
		$data = array(
			'school_id' => school_id,
			'student_id' => $student_id,
			'class_id' => $class_id,
			'caption' => ucwords($this->input->post('caption', TRUE)),
			'description' => nl2br(ucfirst($this->input->post('description', TRUE))),
			'incident_date' => $this->input->post('incident_date', TRUE),
			'actions_taken' => nl2br(ucfirst($this->input->post('actions_taken', TRUE))),
			'session' => current_session_slug,
			'term' => current_term,
		);
		return $this->db->insert('incidents', $data);
	}



	/* ===== Edit Incident ===== */
	public function edit_incident($incident_id) { 
		$data = array(
			'caption' => ucwords($this->input->post('caption', TRUE)),
			'description' => nl2br(ucfirst($this->input->post('description', TRUE))),
			'incident_date' => $this->input->post('incident_date', TRUE),
			'actions_taken' => nl2br(ucfirst($this->input->post('actions_taken', TRUE))),
		);
		$this->db->where('id', $incident_id);
		return $this->db->update('incidents', $data);
	}


	public function delete_evidence_by_incident_id($incident_id) {
		$evidences = $this->get_evidence($incident_id);
		foreach ($evidences as $y) {
			unlink('./assets/uploads/incidents/'.$y->evidence); //delete all files associated with this incident ID 
			$this->db->delete('incident_evidence', array('incident_id' => $incident_id)); //delete all rows associated with this  incident ID in evidence table
		}
    } 
	

	public function delete_incident($incident_id) {
		$this->delete_evidence_by_incident_id($incident_id); //remove evidence files from server
		return $this->db->delete('incidents', array('id' => $incident_id));
    } 
	
	
	public function bulk_actions_incidents() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		$incidents = ($selected_rows == 1) ? 'incident' : 'incidents';
		foreach ($row_id as $incident_id) {
			switch ($bulk_action_type) {
				case 'delete':
					$this->delete_incident($incident_id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$incidents} deleted successfully.");
				break;
			}
		} 
	}




	/* ========== Evidence ========== */

	public function get_evidence_details($evidence_id) {
		return $this->db->get_where('incident_evidence', array('id' => $evidence_id))->row();
	}


	public function get_evidence_details_by_incident_id($incident_id) {
		return $this->db->get_where('incident_evidence', array('incident_id' => $incident_id))->row();
	}


	public function get_evidence($incident_id) {
		return $this->db->get_where('incident_evidence', array('incident_id' => $incident_id))->result();
	}


	public function upload_evidence($data = array()) {
        $insert = $this->db->insert_batch('incident_evidence', $data);
        return $insert? TRUE : FALSE;
    }


    public function delete_evidence($evidence_id) {
		$y = $this->get_evidence_details($evidence_id);
		unlink('./assets/uploads/incidents/'.$y->evidence); //delete the evidence from server
		return $this->db->delete('incident_evidence', array('id' => $evidence_id));
    } 


    public function bulk_actions_evidence() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		$files = ($selected_rows == 1) ? 'file' : 'files';
		foreach ($row_id as $evidence_id) {
			switch ($bulk_action_type) {
				case 'delete':
					$this->delete_evidence($evidence_id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$files} deleted successfully.");
				break;
			}
		} 
	}

	


	
	
}