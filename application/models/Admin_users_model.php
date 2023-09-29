<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Admin_users_model
Role: Model
Description: Controls the DB processes of admin users
Controller: Admin_users
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/

class Admin_users_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
	}
	


	public function add_new_admin() { 
		$name = ucwords($this->input->post('name', TRUE)); 
		$email = strtolower($this->input->post('email', TRUE)); 
		$phone = $this->input->post('phone', TRUE); 
		$designation = ucwords($this->input->post('designation', TRUE)); 
		$level = $this->input->post('level', TRUE); 
		$section_assigned = implode(", ", $this->input->post('section_assigned', TRUE));
		$data = array (
			'school_id' => school_id,
			'name' => $name,
			'email' => $email,
			'phone' => $phone,
			'designation' => $designation,
			'level' => $level,
			'section_assigned' => $section_assigned,
			'demo_user' => 'false',
		);
		$this->db->insert('admins', $data);
		
		//notify admin of their registration so they can set their password
		$this->notify_admin($email, $name);
	}
	
	
	public function notify_admin($email, $name) {
		$subject = 'You are now an admin!';
		$pass_set_url = base_url('set_password_admin');
		$message = 'Hi ' . get_firstname($name) . ', <br />
					You have been added as an admin by ' .$this->admin_details->name. ' on ' .school_name. '. <br />
					To begin using your account, you must set your password. <br />'
					. email_call2action_blue($pass_set_url, 'Set my password') . '<br /> <br />
					If you received this message in error, please ignore.';
		return email_user($email, $subject, $message); //email admin
	}
	
	
	public function edit_admin($id) { 
		$y = $this->common_model->get_admin_details_by_id($id);
		$name = ucwords($this->input->post('name', TRUE)); 
		$phone = $this->input->post('phone', TRUE); 
		$designation = ucwords($this->input->post('designation', TRUE)); 
		$level = $this->input->post('level', TRUE);
		if ($this->input->post('section_assigned', TRUE) != NULL) { 
			$section_assigned = implode(", ", $this->input->post('section_assigned', TRUE));
		} else {
			$section_assigned = $y->section_assigned;
		}
		$data = array (
			'name' => $name,
			'phone' => $phone,
			'designation' => $designation,
			'level' => $level,
			'section_assigned' => $section_assigned,
		);
		$this->db->where('id', $id);
		return $this->db->update('admins', $data);
	}
	
	
	public function get_admin_level($id) { 
		$y = $this->common_model->get_admin_details_by_id($id);
		switch ($y->level) {
			case '1': 
				$admin_level = '1 (Chief Admin)';
			break;
			case '2': 
				$admin_level = '2 (Staff/Surrogate Admin)';
			break;
			default: 
				$admin_level = '';
			break;
		}
		return $admin_level;
	}


	public function get_section_assigned($id) {
		$y = $this->common_model->get_admin_details_by_id($id);
		$section_assigned = $y->section_assigned;
		if ($section_assigned != NULL) {
			//section IDs are saved as 1, 2, 3, ...
			//explode section IDs into array of individual IDs
			$section_idx = explode(", ", $section_assigned);

			$sections = [];
			foreach ($section_idx as $key => $section_id) {
				$section_details = $this->common_model->get_section_details($section_id);
				//concatenate the classes and separate with comma
				$sections[] = $section_details->section; 
			}
			$sections = implode(", ", $sections);
			return $sections;
		} else {
			return NULL;
		}
	}


	public function flag_chief_admin($id) {
		//Flag chief administrator who registered school account
		$software_team = software_team;
		$title = "Created school account. Every correspondence between your school and {$software_team} will be directed to this user.";
		if (chief_admin_id == $id) {
			return '<div class="pull-right badge" style="background: #26B99A; cursor: pointer" title="' . $title . '">C</div>';
		}
    } 
	
	
	public function message_admin($id) {
		$message = nl2br(ucfirst($this->input->post('message', TRUE))); 
		$subject = 'Message from Admin';
		$y = $this->common_model->get_admin_details_by_id($id);
		$this->common_model->notify_user(school_id, $id, $subject, $message, 'admin_notifs'); //send notif to admin
		return email_user($y->email, $subject, $message); //email admin
    } 
	
	
	public function delete_admin_passport($id) {
		$y = $this->common_model->get_admin_details_by_id($id);
		if ($y->passport_photo != NULL && $y->passport != NULL) {
			unlink('./assets/uploads/photos/admins/'.$y->photo); //delete the passport photo
			unlink('./assets/uploads/photos/admins/'.$y->photo_thumb); //delete the thumbnail
		}
    } 
	
	
	public function delete_admin($id) {
		$y = $this->common_model->get_admin_details_by_id($id);
		$this->delete_admin_passport($id); //remove image files from server
		return $this->db->delete('admins', array('id' => $id));
    }


    public function delete_bulk_admins() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		$admins = ($selected_rows == 1) ? 'Admin' : 'Admins';
		foreach ($row_id as $id) {
			$school_info = $this->common_model->get_school_info(school_id);
    		$chief_admin_id = $school_info->chief_admin_id;
			if ($id != $chief_admin_id) { //we goodq
				$this->delete_admin($id);
				$this->session->set_flashdata('status_msg', "{$admins} deleted successfully."); 
			} else {
				$this->session->set_flashdata('status_msg_error', 'You cannot delete a correspondence chief administrator.');
			}
		} 
	}


    public function check_is_chief_admin($id) {
    	$y = $this->common_model->get_admin_details_by_id($id);
		$school_info = $this->common_model->get_school_info(school_id);
    	$chief_admin_id = $school_info->chief_admin_id;
    	if ($id != $chief_admin_id) { //we good
			return TRUE;
		} else { //ooops! that's probably a bad idea...
			$this->session->set_flashdata('status_msg_error', "You cannot delete a correspondence chief administrator.");
			redirect($this->agent->referrer());
		}
    }  



	


}