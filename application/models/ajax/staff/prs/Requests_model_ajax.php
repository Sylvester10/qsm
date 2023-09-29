<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Requests_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of requests raised by staff 
Controller: Prs_staff
Author: Nwankwo Ikemefuna
Date Created: 25th April, 2018
*/


/* Important : Queries to get staff-initiated requests must included 'raised_by' => 'Staff' to ensure staff ID does not conflict with admin ID */ 


class Requests_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('prs_staff_model');
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
	}

	var $table = 'financial_requisitions';
	var $column_order = array(null, 'id', 'ref_id', 'purpose', 'items', 'items_info', 'urgency', 'amount_digits', 'amount_words', 'raised_by', 'status', 'acc_name', 'acc_number', 'bank_name', 'remark'); //set column field database for datatable orderable
	var $column_search = array('id', 'ref_id', 'purpose', 'items', 'items_info', 'urgency', 'amount_digits', 'amount_words', 'raised_by', 'status', 'acc_name', 'acc_number', 'bank_name', 'remark'); //set column field database for datatable searchable 
	var $order = array('date_raised' => 'desc'); 

	
	private function the_query() {		
		$this->db->from($this->table);
		$i = 0;	
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}		
		if(isset($_POST['order'])) { // here order processing 
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if(isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	
	function get_records($session, $term) {
		$this->the_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where(array('school_id' => school_id, 'session' => $session, 'term' => $term, 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'));
	    $query = $this->db->get();
		return $query->result();
	}

	
	function count_filtered_records($session, $term) {
		$this->the_query();
		$this->db->where(array('school_id' => school_id, 'session' => $session, 'term' => $term, 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'));
	    $query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records($session, $term) {
		$this->db->where(array('school_id' => school_id, 'session' => $session, 'term' => $term, 'initiator_id' => $this->staff_details->id, 'raised_by' => 'Staff'));
	    $this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	
	public function check_date($date) {
		return ($date != NULL) ? x_date($date) : '';
	}
	
	
	public function approve_status($id) {
		$y = $this->prs_staff_model->get_request_details($id);
		if ($y->status == 'pending') {
			return '<b class="text-primary">Pending</b>';
		} elseif ($y->status == 'approved') {
			return '<b class="text-success">Approved</b>';
		} elseif ($y->status == 'declined') {
			return '<b class="text-danger">Declined</b>';
		}
	}
	
	
	public function check_amount_approved($id) {
		$y = $this->prs_staff_model->get_request_details($id);
		if ($y->status != 'approved') {
			return '<div class="f-s-17 text-danger text-center"><b>-</b></div>';
		} else {
			return s_currency_symbol . number_format($y->amount_approved, 0);
		}
	}


	public function request_edit_action($id) {
		$y = $this->prs_staff_model->get_request_details($id);
		//show edit button if request has not been action i.e. request is pending
		if ($y->status == 'pending') {
			return '<p><a type="button" href="' .base_url('prs_staff/edit_request/'.$id). '" class="btn btn-default btn-sm btn-block action-btn clickable"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Request </a></p>';
		} else {
			return NULL;
		}
	}


	public function request_content_action($id) {
		$y = $this->prs_staff_model->get_request_details($id);
		if ($y->content != NULL) {
			return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn" data-toggle="modal" data-target="#download_content'.$id.'"> <i class="fa fa-download" style="color: green"></i> &nbsp; Download Content </a></p>';
		} else {
			return NULL;
		}
	}


	public function request_remark_view($id) {
		$y = $this->prs_staff_model->get_request_details($id);
		if ($y->remark != NULL) {
			return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#view_remark' .$id. '"> <i class="fa fa-eye" style="color: green"></i> &nbsp; View Remark </a></p>';
		} else {
			return NULL;
		}
	}
	
	
	public function actions($id) {
		return 	$this->request_edit_action($id)
		
				. $this->request_content_action($id)

				. $this->request_remark_view($id);
	}
	
	
	public function options($id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($id) {
		$y = $this->prs_staff_model->get_request_details($id);
		return '<div class="modal fade" id="options'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: ' .$y->ref_id. ' (' .$y->purpose. ')</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						.$this->actions($id).
					'</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function check_content($id) {
		$y = $this->prs_staff_model->get_request_details($id);
		$download_path = base_url('assets/uploads/request_contents/'.$y->content);
		if ($y->content != NULL) {
			return '<p style="word-break: break-all;">' .$y->content. '</p>
			<p class="m-t-10"><a class="btn btn-primary btn-sm" href="' .$download_path. '" target="_blank">Download</a></p>';
		} else {
			return '<h4 class="text-danger"> No file uploaded for this request.</h4>'; 
		}
	}
	
	
	public function modal_download_content($id) {
		$y = $this->prs_staff_model->get_request_details($id);
		return '<div class="modal fade" id="download_content'.$id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form-sm">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Download: ' .$y->ref_id. ' (' .$y->purpose. ')</h4>
							</div><!--/.modal-header-->
							<div class="modal-body text-center">'
								.$this->check_content($id).
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function check_remark($id) {
		$y = $this->prs_staff_model->get_request_details($id);
		if ($y->remark != NULL) {
			return $y->remark;
		} else {
			return '<h4 class="text-danger text-center"> No remark for this request.</h4>'; 
		}
	}
	
	
	public function modal_view_remark($id) {
		$y = $this->prs_staff_model->get_request_details($id);
		return '<div class="modal fade" id="view_remark'.$id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form-sm">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Remark: ' .$y->ref_id. ' (' .$y->purpose. ')</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								.$this->check_remark($id).
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function modals($id) {
		return 	$this->modal_options($id) . 
				$this->modal_download_content($id) .
				$this->modal_view_remark($id);
	}
	
	
	
}