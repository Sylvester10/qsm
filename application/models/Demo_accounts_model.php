<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Demo_accounts_model
Role: Model
Description: Controls the DB processes of demo account users
Controller: Demo_Accounts
Author: Nwankwo Ikemefuna
Date Created: 11th November, 2018
*/

class Demo_accounts_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}


	
	/* =========== Admins ============== */
	public function get_demo_admins() { 
		return $this->db->get_where('admins', array('demo_user' => 'true'))->result();
	}


	public function edit_admin($user_id) {
		$demo_role = $this->input->post('demo_role', TRUE);
		$data = array(
			'demo_role' => $demo_role,
		);
		$this->db->where('id', $user_id);
		return $this->db->update('admins', $data);	
    }




	/* =========== Staff ============== */
	public function get_demo_staff() { 
		return $this->db->get_where('staff', array('demo_user' => 'true'))->result();
	}
	
	
	public function edit_staff($user_id) {
		$demo_role = $this->input->post('demo_role', TRUE);
		$data = array(
			'demo_role' => $demo_role,
		);
		$this->db->where('id', $user_id);
		return $this->db->update('staff', $data);	
    }


	

	/* =========== Students ============== */
	public function get_demo_students() { 
		return $this->db->get_where('students', array('demo_user' => 'true'))->result();
	}
	
	
	public function edit_student($user_id) {
		$demo_role = $this->input->post('demo_role', TRUE);
		$data = array(
			'demo_role' => $demo_role,
		);
		$this->db->where('id', $user_id);
		return $this->db->update('students', $data);	
    }




	/* =========== Parents ============== */
	public function get_demo_parents() { 
		return $this->db->get_where('parents', array('demo_user' => 'true'))->result();
	}
	
	
	public function edit_parent($user_id) {
		$demo_role = $this->input->post('demo_role', TRUE);
		$data = array(
			'demo_role' => $demo_role,
		);
		$this->db->where('id', $user_id);
		return $this->db->update('parents', $data);	
    }





}