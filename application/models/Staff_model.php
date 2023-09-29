<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Staff_model
Role: Model
Description: Controls the DB processes of the staff
Controller: Staff
Author: Nwankwo Ikemefuna
Date Created: 25th April, 2018
*/


class Staff_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
	}
	
	
	
	/* ===== Header ===== */
	public function get_unread_notifs() { 
		$this->db->order_by("date", "DESC"); 
		$this->db->limit("3"); 
		return $this->db->get_where('staff_notifs', array('user_id' => $this->staff_details->id, 'read' => 'false'))->result();
	}
	
	
	public function count_unread_notifs() { 
		$this->db->order_by("date", "DESC"); 
		return $this->db->get_where('staff_notifs', array('user_id' => $this->staff_details->id, 'read' => 'false'))->num_rows();
	}
	
	
	
	
	/* ===== Dashboard ===== */
	public function get_recent_notifs() { 
		$this->db->order_by("date", "DESC"); 
		$this->db->limit("5"); 
		return $this->db->get_where('staff_notifs', array('user_id' => $this->staff_details->id))->result();
	}
	
	
	public function send_quick_mail() {	
		$email = $this->input->post('email', TRUE);	
		$subject = ucwords($this->input->post('subject', TRUE));
		$message = nl2br(ucfirst($this->input->post('message', TRUE)));
		return email_user($email, $subject, $message);
	}
	
	
	
	
	/* ===== Notifications ===== */
	public function get_notif_details($id)	{ 
		return $this->db->get_where('staff_notifs', array('id' => $id))->row();
	}
	
	
	public function count_all_notifs() { 
		return $this->db->get_where('staff_notifs', array('user_id' => $this->staff_details->id))->num_rows();
	}
	
	
	public function update_read_notifs() {
		$data = array (
			'read' => 'true'
		);		 
        $this->db->where('user_id', $this->staff_details->id);
		return $this->db->update('staff_notifs', $data);
    }

	
	public function delete_notif($id) {
		return $this->db->delete('staff_notifs', array('id' => $id));
    } 


	
	
	
	/* ===== Profile ===== */
	public function edit_profile($passport_photo, $passport) { 
		//if nationality is Nigeria, collect state and lga values
		if ( s_country == 'Nigeria' ) {
			$state_of_origin = ucwords($this->input->post('state_of_origin', TRUE));
			$local_gov = ucwords($this->input->post('local_gov', TRUE));
		} else {
			$state_of_origin = NULL;
			$local_gov = NULL;
		}	
		$data = array(
			'title' => ucwords($this->input->post('title', TRUE)),
			'name' => ucwords($this->input->post('name', TRUE)),
			'phone' => $this->input->post('phone', TRUE),
			'nationality' => ucwords($this->input->post('nationality', TRUE)),
			'state_of_origin' => $state_of_origin,
			'local_gov' => $local_gov,
			'acc_number' => $this->input->post('acc_number', TRUE),
			'bank_name' => ucwords($this->input->post('bank_name', TRUE)),
			'date_of_birth' => ucwords($this->input->post('date_of_birth', TRUE)),
			'sex' => ucfirst($this->input->post('sex', TRUE)),
			'home_address' => ucwords($this->input->post('home_address', TRUE)),
			'religion' => ucwords($this->input->post('religion', TRUE)),
			'name_of_kin' => ucwords($this->input->post('name_of_kin', TRUE)),
			'email_of_kin' => strtolower($this->input->post('email_of_kin', TRUE)),
			'mobile_of_kin' => $this->input->post('mobile_of_kin', TRUE),
			'address_of_kin' => ucwords($this->input->post('address_of_kin', TRUE)),
			'passport_photo' => $passport_photo,
			'passport' => $passport,
		);
		$this->db->where('email', $this->staff_details->email);
		$this->db->update('staff', $data);	
	}
	
	
	public function change_password() { 
		$password = hash('ripemd128', $this->input->post('password', TRUE));
		$data = array (
			'password' => $password
		);
		$this->db->where('email', $this->staff_details->email);
		$this->db->update('staff', $data);
	}




	 /* ========== Signature ========== */
    public function update_signature($signature) { 
		$data = array (
			'signature' => $signature,
		);
		$email = $this->session->staff_email;
		$this->db->where('email', $email);
		return $this->db->update('staff', $data);
	}
	
	
	public function delete_old_signature() {
		$y = $this->staff_details;
		unlink('./assets/uploads/signature/staff/'.$y->signature); //delete the signature
    } 
	
	
	
	
	
	
}