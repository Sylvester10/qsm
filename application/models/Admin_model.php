<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Admin_model
Role: Model
Description: Controls the DB processes of the admin
Controller: Admin
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/

class Admin_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
	}
	
	
	
	/* ===== Header ===== */
	public function get_unread_notifs() { 
		$this->db->order_by("date", "DESC"); 
		$this->db->limit("3"); 
		return $this->db->get_where('admin_notifs', array('user_id' => $this->admin_details->id, 'read' => 'false'))->result();
	}
	
	
	public function count_unread_notifs() { 
		$this->db->order_by("date", "DESC"); 
		return $this->db->get_where('admin_notifs', array('user_id' => $this->admin_details->id, 'read' => 'false'))->num_rows();
	}
	
	
	
	
	/* ===== Dashboard ===== */
	public function get_recent_notifs() { 
		$this->db->order_by("date", "DESC"); 
		$this->db->limit("5"); 
		return $this->db->get_where('admin_notifs', array('user_id' => $this->admin_details->id))->result();
	}
	
	
	public function send_quick_mail() {	
		$email = $this->input->post('email', TRUE);	
		$subject = ucwords($this->input->post('subject', TRUE));
		$message = nl2br(ucfirst($this->input->post('message', TRUE)));
		return email_user($email, $subject, $message);
	}
	
	
	public function send_bulk_mail($mail_list) {
		$subject = ucwords($this->input->post('subject', TRUE));
		$message = nl2br(ucfirst($this->input->post('message', TRUE)));
		return email_multiple($mail_list, $subject, $message);
	}
	
	
	public function send_bulk_mail_parents($mail_list) {
		$subject = ucwords($this->input->post('subject', TRUE));
		$message = nl2br(ucfirst($this->input->post('message', TRUE)));
		return email_multiple($mail_list, $subject, $message);
	}


	
	
	/* ===== Profile ===== */
	public function update_profile() { 
		$name = ucwords($this->input->post('name', TRUE)); 
		$phone = $this->input->post('phone', TRUE); 
		$acc_number = $this->input->post('acc_number', TRUE); 
		$bank_name = $this->input->post('bank_name', TRUE); 
		$current_password = $this->admin_details->password;                                         
		if( $this->input->post('password', TRUE) == '' ) {
		   $password = $current_password; //user does not change password, set password as old password
		} else {
		   $password = hash('ripemd128', $this->input->post('password', TRUE));
		}
		$data = array (
			'name' => $name,
			'phone' => $phone,
			'acc_number' => $acc_number,
			'bank_name' => $bank_name,
			'password' => $password
		);
		$this->db->where('email', $this->admin_details->email);
		$this->db->update('admins', $data);
	}
	
	
	public function update_profile_photo($profile_photo, $thumbnail) { 
		$data = array (
			'photo' => $profile_photo,
			'photo_thumb' => $thumbnail,
		);
		$email = $this->session->admin_email;
		$this->db->where('email', $email);
		return $this->db->update('admins', $data);
	}
	
	
	public function delete_old_profile_photo() {
		$y = $this->admin_details;
		unlink('./assets/uploads/photos/admins/'.$y->photo); //delete the profile photo
		unlink('./assets/uploads/photos/admins/'.$y->photo_thumb); //delete the thumbnail
    } 
	
	
	public function reset_profile_photo() { //remove profile photo
		$this->delete_old_profile_photo(); //delete the photo and thumbnail
		$data = array (
			'photo' => NULL,
			'photo_thumb' => NULL,
		);
		$email = $this->session->admin_email;
		$this->db->where('email', $email);
		return $this->db->update('admins', $data);
    } 





    /* ========== Signature ========== */
    public function update_signature($signature) { 
		$data = array (
			'signature' => $signature,
		);
		$email = $this->session->admin_email;
		$this->db->where('email', $email);
		return $this->db->update('admins', $data);
	}
	
	
	public function delete_old_signature() {
		$y = $this->admin_details;
		unlink('./assets/uploads/signature/admins/'.$y->signature); //delete the signature
    } 
	

	
	
	
	/* ===== Notifications ===== */
	public function get_notif_details($id)	{ 
		return $this->db->get_where('admin_notifs', array('id' => $id))->row();
	}
	
	
	public function count_all_notifs() { 
		return $this->db->get_where('admin_notifs', array('user_id' => $this->admin_details->id))->num_rows();
	}
	
	
	public function update_read_notifs() {
		$data = array (
			'read' => 'true'
		);		 
        $this->db->where('user_id', $this->admin_details->id);
		return $this->db->update('admin_notifs', $data);
    }

	
	public function delete_notif($id) {
		return $this->db->delete('admin_notifs', array('id' => $id));
    } 
	
	
	
	/* ====== Contact Vendor ====== */
	public function contact_vendor() { 
		$school_name = school_name;
		$school_id = school_id;
		$name = $this->admin_details->name;
		$email = $this->admin_details->email;
		$message = ucfirst($this->input->post('message', TRUE)); 
		$subject = generate_snippet($message, 50);
		$message = nl2br($message);
        $data = array (
			'name' => $name,
			'email' => $email,
			'subject' => $subject,
			'message' => $message,
			'sent_from' => 'Admin Dashboard',
			'school_id' => $school_id,
		);
		$this->db->insert('vendor_contact_messages', $data);

		//email privileged vendor admins (with level 1)
		$this->notify_vendor_admins($name, $email, $message);	
	}


	private function notify_vendor_admins($name, $email, $message) {
		$software_name = software_name;
		$school_name = school_name;
		$school_id = school_id;
		$subject = 'New Contact Message';
		$level = 1;
		$admins = $this->common_model->get_super_admins_by_level($level);
		$message = 	"Hi admin, <br />
					You have a new contact message from {$software_name}. <br />
					<b>Contact Details:</b><br /> 
					Name: {$name} <br />
					Email: {$email} <br />
					Sent from: Admin dashboard <br />
					School: {$school_name} <br />
					School ID: {$school_id} <br /><br /> 
					{$message}";
		email_multiple_default($admins, $subject, $message); //email admins 
	}
	
	

	
}