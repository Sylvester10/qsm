<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Students_staff
Role: Controller
Description: Controls access to all stduents' pages and functions from the staff's end
Model: Students_staff_model
Author: Nwankwo Ikemefuna
Date Created: 24th June, 2018
*/


class Students_staff extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->staff_restricted(); //allow only logged in users to access any method in this class, redirect to login page otherwise
		$this->load->model('students_staff_model');
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
		
		//module-level scripts
		$this->staff_module_scripts = array();
	}

	
	

	/* ========== Student Actions ========== */

	public function message_parent($id) { 
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		$y = $this->common_model->get_student_details_by_id($id);
		$parent_id = $y->parent_id;
		$parent_name = $this->common_model->get_parent_details_by_id($parent_id)->name;
		if ($this->form_validation->run())  {		
			$this->students_staff_model->message_parent($id);
			$this->session->set_flashdata('status_msg', "Message successfully sent to {$parent_name}.");
		} else {
			$this->session->set_flashdata('status_msg_error', 'Error sending message to parent.');
		}
		redirect($this->agent->referrer());
	}
	
	

	
	
}