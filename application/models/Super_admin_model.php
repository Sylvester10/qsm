<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Super_admin_model
Role: Model
Description: Controls the DB processes of the super admin
Controller: Super_dmin
Author: Nwankwo Ikemefuna
Date Created: 16th August, 2018
*/

class Super_admin_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);
	}
	
	
	


	/* ===== Dashboard ===== */
	public function send_quick_mail() {	
		$email = $this->input->post('email', TRUE);	
		$subject = ucwords($this->input->post('subject', TRUE));
		$message = nl2br(ucfirst($this->input->post('message', TRUE)));
		return email_user_default($email, $subject, $message);
	}
	
	
	public function send_bulk_mail($mail_list) {
		$subject = ucwords($this->input->post('subject', TRUE));
		$message = nl2br(ucfirst($this->input->post('message', TRUE)));
		return email_multiple_default($mail_list, $subject, $message);
	}




	/* ===== Account Manager ===== */
	public function get_account_manager_details() { 
		return $this->db->get_where('account_manager', array('id' => 1))->row();
	}


	public function update_override_password() {
		$override_password = $this->input->post('password', TRUE);
		$override_password = hash('ripemd128', $override_password);
		$override_login = $this->input->post('override_login', TRUE);
		$data = array(
			'override_password' => $override_password,
			'override_login' => $override_login,
		);
		$this->db->where('id', 1);
		return $this->db->update('account_manager', $data);	
    } 




}