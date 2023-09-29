<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Pending_requests_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of all pending requests
Controller: Prs_admin
Author: Nwankwo Ikemefuna
Date Created: 21st April, 2018
*/


class Pending_requests_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('prs_admin_model');
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
		$this->db->where(array('school_id' => school_id, 'session' => $session, 'term' => $term, 'status' => 'pending'));
		$query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records($session, $term) {
		$this->the_query();
		$this->db->where(array('school_id' => school_id, 'session' => $session, 'term' => $term, 'status' => 'pending'));
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records($session, $term) {
		$this->db->where(array('school_id' => school_id, 'session' => $session, 'term' => $term, 'status' => 'pending'));
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	
	public function check_date($date) {
		return ($date != NULL) ? x_date($date) : '';
	}


	public function request_edit_action($id) {
		$y = $this->prs_admin_model->get_request_details($id);
		//show edit button if request has not been action i.e. request is pending
		if ($y->status == 'pending') {
			return '<p><a type="button" href="' .base_url('prs_admin/edit_request/'.$id). '" class="btn btn-default btn-sm btn-block action-btn clickable"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Request </a></p>';
		} else {
			return NULL;
		}
	}


	public function request_content_action($id) {
		$y = $this->prs_admin_model->get_request_details($id);
		if ($y->content != NULL) {
			return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn" data-toggle="modal" data-target="#download_content'.$id.'"> <i class="fa fa-download" style="color: green"></i> &nbsp; Download Content </a></p>';
		} else {
			return NULL;
		}
	}
	
	
	public function request_remark_view($id) {
		$y = $this->prs_admin_model->get_request_details($id);
		if ($y->remark != NULL) {
			return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#view_remark' .$id. '"> <i class="fa fa-eye" style="color: green"></i> &nbsp; View Remark </a></p>';
		} else {
			return '';
		}
	}
	
	
	public function request_remark_action($id) {
		$y = $this->prs_admin_model->get_request_details($id);
		if ($y->remark != NULL) {
			return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#remark'.$id.'"> <i class="fa fa-commenting-o" style="color: green"></i> &nbsp; Edit Remark </a></p>';
		} else {
			return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#remark'.$id.'"> <i class="fa fa-commenting-o" style="color: green"></i> &nbsp; Add Remark </a></p>';
		}
	}


	public function request_status_actions($id) {
		return '<p><a class="btn btn-default btn-sm btn-block action-btn" href="' .base_url('prs_admin/approve_request/'.$id). '"> <i class="fa fa-check" style="color: green"></i> &nbsp; Approve </a></p>
			
			<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#edit_approve'.$id.'"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit & Approve </a></p>
			
			<p><a class="btn btn-default btn-sm btn-block action-btn" href="' .base_url('prs_admin/decline_request/'.$id). '"> <i class="fa fa-ban" style="color: red"></i> &nbsp; Decline </a></p>';
	}
	
	
	public function actions($id) {
		
		return $this->request_edit_action($id)

			. $this->request_content_action($id)

			. $this->request_status_actions($id)
		
			. $this->request_remark_action($id) 

			. $this->request_remark_view($id) .
		
			'<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#delete'.$id.'"> <i class="fa fa-trash" style="color: red"></i> &nbsp; Delete </a></p>';
	}
	
	
	public function options($id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($id) {
		$y = $this->prs_admin_model->get_request_details($id);
		return '<div class="modal fade" id="options'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: ' . $y->ref_id . ' (' . $y->purpose . ')</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						.$this->actions($id).
					'</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function edit_approve_form($id) {
		$y = $this->prs_admin_model->get_request_details($id);
		return form_open('prs_admin/edit_approve_request/'.$y->id). 
			'<div class="form-group">
				<label class="form-control-label">Amount (in digits)</label>
				<br/>
				<div class="input-group">
					<div class="input-group-addon input-with-addon"><span class="input-group-text">' .s_currency_symbol. '</span></div>
					<input type="number" name="amount_digits" value="' . $y->amount_digits . '" class="form-control" required maxlength="15"/>
					<div class="input-group-addon input-with-addon"><span class="input-group-text">.00</span></div>
				</div>
			</div>
			<div>
				<button class="btn btn-primary"> <i class="fa fa-arrow-circle-right"></i> Approve </button>
			</div>'
		.form_close();
	}
	
	
	public function modal_edit_approve_request($id) {
		$y = $this->prs_admin_model->get_request_details($id);
		return '<div class="modal fade" id="edit_approve'.$id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form-sm">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Edit & Approve: ' . $y->ref_id . ' (' . $y->purpose . ')</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								.$this->edit_approve_form($id).
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function check_content($id) {
		$y = $this->prs_admin_model->get_request_details($id);
		$download_path = base_url('assets/uploads/request_contents/'.$y->content);
		if ($y->content != NULL) {
			return '<p style="word-break: break-all;">' .$y->content. '</p>
			<p class="m-t-10"><a class="btn btn-primary btn-sm" href="' .$download_path. '" target="_blank">Download</a></p>';
		} else {
			return '<h4 class="text-danger"> No file uploaded for this request.</h4>'; 
		}
	}
	
	
	public function modal_download_content($id) {
		$y = $this->prs_admin_model->get_request_details($id);
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
	
		
	public function remark_form($id) {
		$y = $this->prs_admin_model->get_request_details($id);
		return form_open('prs_admin/remark_request/'.$y->id). 
			'<div>
				<textarea class="t200 w-100 m-b-20" name="remark" placeholder="Your remark" required>' .strip_tags($y->remark). '</textarea>
			</div>
			<div>
				<button class="btn btn-primary"> <i class="fa fa-arrow-circle-right"></i> Submit Remark</button>
			</div>'
		.form_close();
	}
	
	
	public function modal_remark_request($id) {
		$y = $this->prs_admin_model->get_request_details($id);
		return '<div class="modal fade" id="remark'.$id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Remark: ' .$y->ref_id. ' (' .$y->purpose. ')</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								.$this->remark_form($id).
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function modal_view_remark($id) {
		$y = $this->prs_admin_model->get_request_details($id);
		return '<div class="modal fade" id="view_remark'.$id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Remark: ' .$y->ref_id. ' (' .$y->purpose. ')</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								.$y->remark.
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function modal_delete_confirm($id) {
		$y = $this->prs_admin_model->get_request_details($id);
		return '<div class="modal fade" id="delete'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Delete: ' .$y->ref_id. ' (' .$y->purpose. ')</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						Are you sure you want to permanently delete this request?
					</div>
					<div class="modal-footer">
						<a class="btn btn-sm btn-danger" role="button" href="' .base_url('prs_admin/delete_request/'.$id). '"> Yes, Delete </a>
						<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function modals($id) {
		return 	$this->modal_options($id) . 
				$this->modal_download_content($id) .
				$this->modal_edit_approve_request($id) .
				$this->modal_remark_request($id) .
				$this->modal_view_remark($id) .
				$this->modal_delete_confirm($id);
	}

	
	
	
}