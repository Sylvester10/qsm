<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: Students_staff_model
Role: Model
Description: Controls the DB processes of students from the staff's end
Controller: Students_staff
Author: Nwankwo Ikemefuna
Date Created: 15th June, 2018
*/


class Students_staff_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	
	
	public function message_parent($id) {
		$message = nl2br(ucfirst($this->input->post('message', TRUE))); 
		$subject = 'Message from Admin';
		$y = $this->common_model->get_student_details_by_id($id);
		$parent_id = $y->parent_id;
		$parent_email = $this->common_model->get_parent_details_by_id($parent_id)->email;
		return email_user($parent_email, $subject, $message); //email parent
    } 
	
	
	
	
}