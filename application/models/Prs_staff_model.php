<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Prs_staff_model
Role: Model
Description: Controls the DB processes of Procurement Requisition System from the staff's end
Controller: Prs_staff
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


/* Important : Queries to get staff-initiated requests must included 'raised_by' => 'Staff' to ensure staff ID does not conflict with admin ID */ 

class Prs_staff_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
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
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'term' => $term, 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->num_rows();
	}


	public function count_all_pending_term_requests($session, $term) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'term' => $term, 'status' => 'pending', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->num_rows();
	}


	public function count_all_approved_term_requests($session, $term) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'term' => $term, 'status' => 'approved', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->num_rows();
	}


	public function count_all_declined_term_requests($session, $term) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'term' => $term, 'status' => 'declined', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->num_rows();
	}


	public function total_term_amount_raised($session, $term) { 
		$this->db->select_sum('amount_digits');
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'term' => $term, 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->row()->amount_digits;
	}


	public function total_term_amount_approved($session, $term) { 
		$this->db->select_sum('amount_approved');
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'term' => $term, 'status' => 'approved', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->row()->amount_approved;
	}



	/* ========== Selected Session ============== */

	public function count_all_session_requests($session) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->num_rows();
	}


	public function count_all_pending_session_requests($session) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'status' => 'pending', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->num_rows();
	}


	public function count_all_approved_session_requests($session) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'status' => 'approved', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->num_rows();
	}


	public function count_all_declined_session_requests($session) { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'status' => 'declined', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->num_rows();
	}


	public function total_session_amount_raised($session) { 
		$this->db->select_sum('amount_digits');
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->row()->amount_digits;
	}


	public function total_session_amount_approved($session) { 
		$this->db->select_sum('amount_approved');
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'session' => $session, 'status' => 'approved', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->row()->amount_approved;
	}



	/* ========== All time ============== */
	
	public function count_all_requests() { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->num_rows();
	}


	public function count_all_pending_requests() { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'status' => 'pending', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->num_rows();
	}


	public function count_all_approved_requests() { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'status' => 'approved', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->num_rows();
	}
	
	
	public function count_all_declined_requests() { 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'status' => 'declined', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->num_rows();
	}


	public function total_amount_raised() { 
		$this->db->select_sum('amount_digits');
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->row()->amount_digits;
	}


	public function total_amount_approved() { 
		$this->db->select_sum('amount_approved');
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'status' => 'approved', 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->row()->amount_approved;
	}





	public function get_recent_request() { 
		$this->db->order_by("date_raised", "DESC"); 
		$this->db->limit("1"); 
		return $this->db->get_where('financial_requisitions', array('school_id' => school_id, 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'))->result();
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
			'raised_by' => 'Staff',
			'initiator_id' => $this->staff_details->id,
			'acc_name' => $acc_name,
			'acc_number' => $acc_number,
			'bank_name' => $bank_name,
			'status' => 'pending',
			'session' => current_session,
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
		$this->db->update('financial_requisitions', $update_data);	
		
		//notify admins by mail
		return $this->notify_admins_new_request($id);
	}
	
	
	private function notify_admins_new_request($id) {
		//notify admins by mail
		$y = $this->get_request_details($id);
		$initiator = $this->staff_details->name;
		//get privileged admins (with level 1)
		$level = 1;
		$admins = $this->common_model->get_admins_by_level(school_id, $level);
		$redirect_url = base_url('prs_admin/pending_requests');
		$subject = 'New Request';
		$message = 'Hi admin, <br />
					You have a new request submission. <br /> 
					<b>Request Details:</b><br /> 
					Ref ID: ' .$y->ref_id. '<br />
					Initiated by: ' .$initiator. '<br />
					Purpose: ' .$y->purpose. '<br />
					Amount: ' .s_currency_symbol . number_format($y->amount_digits). '<br />
					Urgency: ' .$y->urgency. '<br />
					Login to your <a href="' .$redirect_url. '">admin dashboard</a> to see more details and action this request.';
		email_multiple($admins, $subject, $message); //email admins	
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
		
		//notify admins by mail
		return $this->notify_admins_request_edit($id);
	}
	
	
	private function notify_admins_request_edit($id) {
		//notify admins by mail
		$y = $this->get_request_details($id);
		//get privileged admins (with level 1)
		$level = 1;
		$admins = $this->common_model->get_admins_by_level(school_id, $level);
		$redirect_url = base_url('prs_admin/pending_requests');
		$subject = 'Request Edited';
		$message = 'Hi admin, <br />
					The request for ' .$y->purpose. ' with Ref ID ' .$y->ref_id. ' was edited by the initiator: ' .$this->staff_details->name. '.<br />
					Login to your <a href="' .$redirect_url. '">admin dashboard</a> to see more details.';
		email_multiple($admins, $subject, $message); //email admins
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
	
	
	
	
	
}
