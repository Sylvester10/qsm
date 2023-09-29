<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Data_management_model
Role: Model
Description: Controls the DB processes of data management
Controller: Data_management
Author: Nwankwo Ikemefuna
Date Created: 23rd August, 2018
*/

class Data_management_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);
	}
	
	
	

	public function get_database_backup_info() {	
		return $this->db->get_where('db_backup_info', array('id' => 1))->row();
	}
	
	
	public function update_database_backup_info() {	
		$data = array(
			'last_backup_by' => $this->super_admin_details->id,
			'last_backup_date' => date('Y-m-d H:i:s'),
		);
		$this->db->where('id', 1);
		return $this->db->update('db_backup_info', $data);
	}
	


}