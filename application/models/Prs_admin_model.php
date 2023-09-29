<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Prs_admin_model
Role: Model
Description: Controls the DB processes of Procurement Requisition System from the admin's end
Controller: Prs_admin
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/

class Prs_admin_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
	}



	public function request_data($session, $term)	{ 
		//this term
		$data['all_term_requests'] = $this->count_all_term_requests($session, $term);
		$data['pending_term_requests'] = $this->count_all_pending_term_requests($session, $term);
		$data['approved_term_requests'] = $this->count_all_approved_term_requests($session, $term);
		$data['declined_term_requests'] = $this->count_all_declined_term_requests($session, $term);
		$data['total_term_amount_raised'] = $this->total_term_amount_raised($session, $term);
		$data['total_term_amount_approved'] = $this->total_term_amount_approved($session, $term);
		//this session
		$data['all_session_requests'] = $this->count_all_session_requests($session);
		$data['pending_session_requests'] = $this->count_all_pending_session_requests($session);
		$data['approved_session_requests'] = $this->count_all_approved_session_requests($session);
		$data['declined_session_requests'] = $this->count_all_declined_session_requests($session);
		$data['total_session_amount_raised'] = $this->total_session_amount_raised($session);
		$data['total_session_amount_approved'] = $this->total_session_amount_approved($session);
		//all time
		$data['all_requests'] = $this->count_all_requests();
		$data['pending_requests'] = $this->count_all_pending_requests();
		$data['approved_requests'] = $this->count_all_approved_requests();
		$data['declined_requests'] = $this->count_all_declined_requests();
		$data['total_amount_raised'] = $this->total_amount_raised();
		$data['total_amount_approved'] = $this->total_amount_approved();
		//other data
		$data['session'] = $session;
		$data['the_session'] = get_the_session($session);
		$data['term'] = $term;
		return $data;
	}



	public function get_request_details($id)	{ 
		return $this->db->get_where('financial_requisitions', array('id' => $id))->row();
	}



	/* ========== Selected Term ============== */

	public function count_all_term_requests($session, $term) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'term' => $term))->num_rows();
	}


	public function count_all_pending_term_requests($session, $term) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'term' => $term, 'status' => 'pending'))->num_rows();
	}


	public function count_all_approved_term_requests($session, $term) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'term' => $term, 'status' => 'approved'))->num_rows();
	}


	public function count_all_declined_term_requests($session, $term) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'term' => $term, 'status' => 'declined'))->num_rows();
	}


	public function total_term_amount_raised($session, $term) { 
		$this->db->select_sum('amount_digits');
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'term' => $term))->row()->amount_digits;
	}


	public function total_term_amount_approved($session, $term) { 
		$this->db->select_sum('amount_approved');
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'term' => $term, 'status' => 'approved'))->row()->amount_approved;
	}



	/* ========== Selected Session ============== */

	public function count_all_session_requests($session) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session))->num_rows();
	}


	public function count_all_pending_session_requests($session) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'status' => 'pending'))->num_rows();
	}


	public function count_all_approved_session_requests($session) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'status' => 'approved'))->num_rows();
	}


	public function count_all_declined_session_requests($session) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'status' => 'declined'))->num_rows();
	}


	public function total_session_amount_raised($session) { 
		$this->db->select_sum('amount_digits');
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session))->row()->amount_digits;
	}


	public function total_session_amount_approved($session) { 
		$this->db->select_sum('amount_approved');
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'status' => 'approved'))->row()->amount_approved;
	}



	/* ========== All time ============== */
	
	public function count_all_requests() { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id))->num_rows();
	}


	public function count_all_pending_requests() { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'status' => 'pending'))->num_rows();
	}


	public function count_all_approved_requests() { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'status' => 'approved'))->num_rows();
	}
	
	
	public function count_all_declined_requests() { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'status' => 'declined'))->num_rows();
	}


	public function total_amount_raised() { 
		$this->db->select_sum('amount_digits');
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id))->row()->amount_digits;
	}


	public function total_amount_approved() { 
		$this->db->select_sum('amount_approved');
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'status' => 'approved'))->row()->amount_approved;
	}





	public function get_recent_request() { 
		$this->db->order_by("date_raised", "DESC"); 
		$this->db->limit("1"); 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id))->result();
	}


	public function get_initiator($id) {
		$y = $this->get_request_details($id);
		if ($y->raised_by == 'Staff') {
			//initiator is staff, get staff name
			$initiator = $this->common_model->get_staff_details_by_id($y->initiator_id)->name;
		} else {
			//initiator is admin, get admin name
			$initiator = $this->common_model->get_admin_details_by_id($y->initiator_id)->name;
		}
		return $initiator;
	}


	public function get_initiator_email($id) {
		$y = $this->get_request_details($id);
		if ($y->raised_by == 'Staff') {
			//initiator is staff, get staff email
			$email = $this->common_model->get_staff_details_by_id($y->initiator_id)->email;
		} else {
			//initiator is admin, get admin email
			$email = $this->common_model->get_admin_details_by_id($y->initiator_id)->email;
		}
		return $email;
	}
	
	
	public function approve_status($id) {
		$y = $this->get_request_details($id);
		if ($y->status == 'pending') {
			return '<b class="text-primary">Pending</b>';
		} elseif ($y->status == 'approved') {
			return '<b class="text-success">Approved</b>';
		} elseif ($y->status == 'declined') {
			return '<b class="text-danger">Declined</b>';
		}
	}
	
	
	public function check_date($date) {
		return ($date != NULL) ? x_date($date) : '';
	}
	
	
	public function request_info($id) {
		$y = $this->get_request_details($id);
		$content_info = ($y->content != NULL) ? '<b class="text-success">Yes</b>' : 'No';
		$remark_info = ($y->remark != NULL) ? '<b class="text-success">Yes</b>' : 'No';
		return  '<b class="f-s-17">' . $y->purpose . '</b>
				<p>' . $y->items_info . '</p>
				Urgency: ' . $y->urgency . '<br />
				Additional content file: ' . $content_info . '<br />
				Remarked: ' . $remark_info . '<br />
				Date raised: ' . x_date($y->date_raised);
	}
	
	
	public function approval_info($id) {
		$y = $this->get_request_details($id);
		$approved_by = ($y->approved_by != NULL) ? $this->common_model->get_admin_details_by_id($y->approved_by)->name : NULL;
		$amount_approved = ($y->amount_approved != NULL) ? (s_currency_symbol . number_format($y->amount_approved)) : '';
		return  'Status: ' . $this->approve_status($id) . '<br />
				Amount approved: ' . $amount_approved . '<br />
				Approved by: ' . $approved_by . '<br />
				Date approved: ' . $this->check_date($y->date_approved);
	}
	
	
	public function account_info($id) {
		$y = $this->get_request_details($id);
		return  'Account Name: ' . $y->acc_name . '<br />
				Account No: ' . $y->acc_number . '<br />
				Bank Name: ' . $y->bank_name;
	}
	
	
	public function insert_request($content) { 
		$purpose = ucwords($this->input->post('purpose', TRUE)); 
		$items = ucfirst($this->input->post('items', TRUE)); 
		$items_info = ucfirst($this->input->post('items_info', TRUE)); 
		$urgency = ucfirst($this->input->post('urgency', TRUE)); 
		$amount_digits = $this->input->post('amount_digits', TRUE); 
		$amount_words = $this->input->post('amount_words', TRUE); 
		$acc_name = ucwords($this->input->post('acc_name', TRUE)); 
		$acc_number = $this->input->post('acc_number', TRUE); 
		$bank_name = $this->input->post('bank_name', TRUE); 
		
		$data = array (
			'school_id' => school_id,
			'purpose' => $purpose,
			'items' => $items,
			'items_info' => $items_info,
			'urgency' => $urgency,
			'amount_digits' => $amount_digits,
			'amount_words' => $amount_words, 
			'content' => $content,
			'raised_by' => 'Admin',
			'initiator_id' => $this->admin_details->id,
			'acc_name' => $acc_name,
			'acc_number' => $acc_number,
			'bank_name' => $bank_name,
			'status' => 'pending',
			'session' => get_session_slug(current_session),
			'term' => current_term,
		);
		$insert = $this->db->insert('financial_requisitions', $data);
		$id = $this->db->insert_id($insert);
		
		//generate and update ref ID
		$ref_id = generate_ref_id($id);
		$update_data = array (
			'ref_id' => $ref_id
		);
		$this->db->where('id', $id);	
		return $this->db->update('financial_requisitions', $update_data);	
	}


	public function edit_request($id, $content) { 
		$purpose = ucwords($this->input->post('purpose', TRUE)); 
		$items = ucfirst($this->input->post('items', TRUE)); 
		$items_info = ucfirst($this->input->post('items_info', TRUE)); 
		$urgency = ucfirst($this->input->post('urgency', TRUE)); 
		$amount_digits = $this->input->post('amount_digits', TRUE); 
		$acc_name = ucwords($this->input->post('acc_name', TRUE)); 
		$acc_number = $this->input->post('acc_number', TRUE); 
		$bank_name = $this->input->post('bank_name', TRUE); 
		
		$data = array (
			'purpose' => $purpose,
			'items' => $items,
			'items_info' => $items_info,
			'urgency' => $urgency,
			'amount_digits' => $amount_digits,
			'acc_name' => $acc_name,
			'acc_number' => $acc_number,
			'bank_name' => $bank_name,
			'content' => $content,
		);
		$this->db->where('id', $id);	
		$this->db->update('financial_requisitions', $data);
	}
	
	
	public function approve_request($id) {
		$y = $this->get_request_details($id);
		$data = array (
			'status' => 'approved',
			'amount_approved' => $y->amount_digits,
			'approved_by' => $this->admin_details->id,
			'date_approved' => default_calendar_date(),
		);		 
		$this->db->where('id', $id);
		$this->db->update('financial_requisitions', $data);
		
		//notify initiator by mail and via dashboard notifications
		$subject = 'Request Approval Status';
		$action = 'approved';
		return $this->notify_initiator($id, $subject, $action); 
    } 	
	
	
	public function edit_approve_request($id) {
		$amount_digits = $this->input->post('amount_digits', TRUE); 
		$data = array (
			'status' => 'approved',
			'amount_approved' => $amount_digits,
			'approved_by' => $this->admin_details->id,
			'date_approved' => default_calendar_date(),
		);		 
        $this->db->where('id', $id);			
		$this->db->update('financial_requisitions', $data);
		
		//notify initiator by mail and via dashboard notifications
		$subject = 'Request Approval Status';
		$action = 'approved';
		return $this->notify_initiator($id, $subject, $action); 
    } 
	
	
	public function unapprove_request($id) {
		$data = array (
			'status' => 'pending',
			'amount_approved' => NULL,
			'approved_by' => NULL,
			'date_approved' => NULL
		);		 
        $this->db->where('id', $id);			
		$this->db->update('financial_requisitions', $data);
		
		//notify initiator by mail and via dashboard notifications
		$subject = 'Request Approval Status';
		$action = 'unapproved';
		return $this->notify_initiator($id, $subject, $action); 
    } 
	
	
	public function decline_request($id) {
		$data = array (
			'status' => 'declined',
			'amount_approved' => NULL,
			'approved_by' => NULL,
			'date_approved' => NULL
		);		 
        $this->db->where('id', $id);			
		$this->db->update('financial_requisitions', $data);
		
		//notify initiator by mail and via dashboard notifications
		$subject = 'Request Approval Status';
		$action = 'declined';
		return $this->notify_initiator($id, $subject, $action); 
    } 
	
	
	public function remark_request($id) {
		$remark = nl2br(ucfirst($this->input->post('remark', TRUE))); 
		$data = array (
			'remark' => $remark
		);		 
        $this->db->where('id', $id);			
		$this->db->update('financial_requisitions', $data);
		
		//notify initiator by mail and via dashboard notifications
		$subject = 'Request Approval Status';
		$action = 'remarked';
		return $this->notify_initiator($id, $subject, $action); 
    } 
	
	
	public function notify_initiator($id, $subject, $action) {
		$y = $this->get_request_details($id);
		$initiator = $this->get_initiator($id);
		$email = $this->get_initiator_email($id);
		//send email to request initiator
		$message = 'Hi ' . get_firstname($initiator). ', <br />
					Your request for ' .$y->purpose. ' with Ref ID ' .$y->ref_id. ' has been ' . $action . '. <br />
					Login to your dashboard to see more details.';
		email_user($email, $subject, $message);
		
		//insert into notifications
		$notif_message = 	'Hi ' .get_firstname($y->raised_by). ', <br /> 
							Your request for ' .$y->purpose. ' with Ref ID ' .$y->ref_id. ' has been ' . $action;
		$this->notify_user_check($id, $subject, $notif_message); //notify initiator (staff or admin)
	}
	
	
	public function notify_user_check($id, $subject, $message) {
		$y = $this->get_request_details($id);
		$initiator_id = $y->initiator_id;
		$email = $this->get_initiator_email($id);
		if ($y->raised_by == 'Staff') {
			//initiator is staff, notify staff
			$this->common_model->notify_user(school_id, $initiator_id, $subject, $message, 'staff_notifs'); //send notif to staff
		} else {
			//initiator is admin, notify admin
			$this->common_model->notify_user(school_id, $initiator_id, $subject, $message, 'admin_notifs'); //send notif to admin
		}
	}	


	public function delete_request_file($id) {
		$y = $this->get_request_details($id);
		return unlink('./assets/uploads/request_contents/'.$y->content); //delete the file from server
    } 
	
	
	public function delete_request_content($id) {
		$this->delete_request_file($id); //delete the file
		$data = array(
			'content' => NULL //set content field to null
		);
		$this->db->where('id', $id);
		return $this->db->update('financial_requisitions', $data);
    } 
	
	
	public function delete_request($id) {
		$this->delete_request_file($id); //delete the file
		return $this->db->delete('financial_requisitions', array('id' => $id));
    } 
	
	
	public function bulk_actions_requests() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		$requests = ($selected_rows == 1) ? 'request' : 'requests';
		foreach ($row_id as $id) {
			switch ($bulk_action_type) {
				case 'approve':
					$this->approve_request($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$requests} approved successfully.");
				break;
				case 'unapprove':
					$this->unapprove_request($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$requests} unapproved successfully.");
				break;
				case 'decline':
					$this->decline_request($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$requests} declined successfully.");
				break;
				case 'delete':
					$this->delete_request($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$requests} deleted successfully.");
				break;
			}
		} 
	}
	
	
	
	
}
	