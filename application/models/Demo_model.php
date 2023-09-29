<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Demo_model
Role: Model
Description: Controls the DB processes of the demo instance 
Controller: Demo
Author: Nwankwo Ikemefuna
Date Created: 11th November, 2018
*/


class Demo_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	

	
	/* ===== Admin ===== */
	public function get_admin_demo_role($role) { 
		$this->db->order_by('rand()');	
		$this->db->limit('1');	
		$where = array(
			'demo_user' => 'true',
			'demo_role' => $role,
		);
		$admin_details = $this->db->get_where('admins', $where)->row();
		return $admin_details;
	}

	
	public function check_admin_email_exists($email) { 	
		$where = array(
			'demo_user' => 'true',
			'email' => $email,
		);	
		$email_exists = $this->db->get_where('admins', $where)->row();
		return $email_exists ? TRUE : FALSE;  
	}
	

	
	
	
	/* ===== Staff ===== */
	public function get_staff_demo_role($role) { 
		$this->db->order_by('rand()');	
		$this->db->limit('1');	
		$where = array(
			'demo_user' => 'true',
			'demo_role' => $role,
		);
		$staff_details = $this->db->get_where('staff', $where)->row();
		return $staff_details;
	}
	
	
	public function check_staff_email_exists($email) { 	
		$where = array(
			'demo_user' => 'true',
			'email' => $email,
		);	
		$email_exists = $this->db->get_where('staff', $where)->row();
		return $email_exists ? TRUE : FALSE;   
	}

	
	
	
	
	/* ===== Student ===== */
	public function get_student_demo_role($role) { 
		$this->db->order_by('rand()');	
		$this->db->limit('1');	
		$where = array(
			'demo_user' => 'true',
			'demo_role' => $role,
		);
		$student_details = $this->db->get_where('students', $where)->row();
		return $student_details;
	}


	public function check_student_reg_id_exists($reg_id) { 	 
		$where = array(
			'demo_user' => 'true',
			'reg_id' => $reg_id,
		);	
		$reg_id_exists = $this->db->get_where('students', $where)->row();
		return $reg_id_exists ? TRUE : FALSE;  
	}
	
	
	


	/* ===== Parent ===== */
	public function get_parent_demo_role($role) { 
		$this->db->order_by('rand()');	
		$this->db->limit('1');	
		$where = array(
			'demo_user' => 'true',
			'demo_role' => $role,
		);
		$parent_details = $this->db->get_where('parents', $where)->row();
		return $parent_details;
	}


	public function check_parent_email_exists($email) { 
		$where = array(
			'demo_user' => 'true',
			'email' => $email,
		);	
		$email_exists = $this->db->get_where('parents', $where)->row();
		return $email_exists ? TRUE : FALSE;  
	}
	
	
	
}