<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: Message_model
Role: Model
Description: Controls the DB processes of contacts in super admin dashboard
Controller: Message
Author: Nwankwo Ikemefuna
Date Created: 16th August, 2018
*/


class Message_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}


	/* ===== Contact Us (from website) ===== */
	public function contact_us() { 
		$name = ucwords($this->input->post('name', TRUE)); 
		$email = strtolower($this->input->post('email', TRUE));
		$message = ucfirst($this->input->post('message', TRUE)); 
		$subject = generate_snippet($message, 50);
		$message = nl2br($message);
        $data = array (
			'name' => $name,
			'email' => $email,
			'subject' => $subject,
			'message' => $message,
			'sent_from' => 'Website', 
		);
		$this->db->insert('vendor_contact_messages', $data);

		//email privileged vendor admins (with level 1)
		$this->notify_vendor_admins($name, $email, $message);	
	}


	private function notify_vendor_admins($name, $email, $message) {
		$software_name = software_name;
		$subject = 'New Contact Message';
		$level = 1;
		$admins = $this->common_model->get_super_admins_by_level($level);
		$message = 	"Hi admin, <br />
					You have a new contact message from {$software_name}. <br />
					<b>Contact Details:</b><br /> 
					Name: {$name} <br />
					Email: {$email} <br /><br /> 
					Sent from: Website <br />
					{$message}";
		email_multiple_default($admins, $subject, $message); //email admins 
	}




	/* ===== Super Admin Actions ===== */
	public function get_message_details($msg_id) { 
		return $this->db->get_where('vendor_contact_messages', array('id' => $msg_id))->row();
	}


	public function get_messages($limit, $offset) {		
		$this->db->limit($limit, $offset); //limit to be used as per_page, offset to be used as pagination segment
		$this->db->order_by("date_sent", "DESC"); 
		$query = $this->db->get('vendor_contact_messages');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }


    public function count_messages() { 
		return $this->db->get_where('vendor_contact_messages')->num_rows();
	}


	public function get_latest_message() { 
		$this->db->order_by("date_sent", "DESC"); 
		$this->db->limit(1); //just one
		return $this->db->get_where('vendor_contact_messages')->row();
	}


	public function reply_message($msg_id) {
		$message = nl2br(ucfirst($this->input->post('message', TRUE))); 
		$y = $this->get_message_details($msg_id);
		$subject = 'RE: ' . $y->subject;
		$email = $y->email;
		return email_user_default($email, $subject, $message);
    } 


	public function delete_message($msg_id) {
		return $this->db->delete('vendor_contact_messages', array('id' => $msg_id));
    } 
	
	
	public function bulk_actions_messages() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);
		$row_id = $this->input->post('check_bulk_action', TRUE);		
		foreach ($row_id as $msg_id) {
			$this->delete_message($msg_id);
			$this->session->set_flashdata('status_msg', "{$selected_rows} messages deleted successfully.");
		} 
	}




	
}