<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Partial_payment_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of partial payment of fees
Controllers: Fees_admin, Fees_staff
Authors: Nwankwo Ikemefuna
Date Created: 24th June, 2018
*/


class Partial_payment_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('fees_model');
	}

	var $table = 'fee_payment';
	var $column_order = array(null, 'id', 'student_id', 'class_id', 'status', 'transaction_id', 'amount_paid', 'balance', 'date_paid', 'session', 'term'); //set column field database for datatable orderable
	var $column_search = array('id', 'student_id', 'class_id', 'status', 'transaction_id', 'amount_paid', 'balance', 'date_paid', 'session', 'term'); //set column field database for datatable searchable 
	var $order = array('date_paid' => 'desc'); 

	
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
	

	function get_records($session, $term, $class_id) {
		$this->the_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$where = array(
			'school_id' => school_id,
			'status' => 'Partial Payment',
			'session' => $session,
			'term' => $term,
			'class_id' => $class_id,
		);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records($session, $term, $class_id) {
		$this->the_query();
		$where = array(
			'school_id' => school_id,
			'status' => 'Partial Payment',
			'session' => $session,
			'term' => $term,
			'class_id' => $class_id,
		);
		$this->db->where($where);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records($session, $term, $class_id) {
	    $where = array(
			'school_id' => school_id,
			'status' => 'Partial Payment',
			'session' => $session,
			'term' => $term,
			'class_id' => $class_id,
		);
		$this->db->where($where);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}


	public function fees_last_amount_paid($payment_id) {
		$f = $this->fees_model->get_fee_payment_details_by_id($payment_id);
		return ($f->last_amount_paid != NULL) ? (s_currency_symbol . number_format($f->last_amount_paid)) : NULL;
	}


	public function fees_total_amount_paid($payment_id) {
		$f = $this->fees_model->get_fee_payment_details_by_id($payment_id);
		return ($f->last_total_amount_paid != NULL) ? (s_currency_symbol . number_format($f->last_total_amount_paid)) : NULL;
	}


	public function fees_balance($payment_id) {
		$f = $this->fees_model->get_fee_payment_details_by_id($payment_id);
		return ($f->balance != NULL) ? (s_currency_symbol . number_format($f->balance)) : NULL;
	}
	
	
	public function validation_actions($payment_id) {
		if ($this->c_user == 'admin') {

			$f = $this->fees_model->get_fee_payment_details_by_id($payment_id);
			//get admin level, show validate button if level is 1
			$admin_level = $this->common_model->get_admin_details($this->session->admin_email)->level;
			if ($admin_level == 1) {
				
				if ($f->validated == 'false') {
					return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/validate_all_partial_payment/'.$payment_id) .'"> <i class="fa fa-check" style="color: green"></i> &nbsp; Validate All </a></p>';		
				} else {	
					return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/invalidate_all_partial_payment/'.$payment_id) .'"> <i class="fa fa-times" style="color: red"></i> &nbsp; Invalidate All </a></p>';
				}
			} else {
				return NULL;
			}

		} else { //not admin
			return NULL;
		}
			
	}

	
	public function actions($payment_id) {
		$f = $this->fees_model->get_fee_payment_details_by_id($payment_id);
		$session = $f->session;
		$term = $f->term;
		$class_id = $f->class_id;
		$student_id = $f->student_id;
		$uri_segment = $session.'/'.$term.'/'.$class_id.'/'.$student_id;

		return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#full'.$payment_id.'"> <i class="fa fa-check" style="color: green"></i> &nbsp; Approve Full Payment</a></p>
				
			<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#partial_sub'.$payment_id.'"> <i class="fa fa-adjust" style="color: green"></i> &nbsp; Approve Partial Payment (Subsequent)</a></p>
			
			<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/unapprove_payment/'.$uri_segment) .'"> <i class="fa fa-times" style="color: red"></i> &nbsp; Unapprove Partial Payment </a></p>

			<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/partial_payment_details/'.$payment_id) .'"> <i class="fa fa-eye" style="color: green"></i> &nbsp; View/Manage Installment </a></p>'
			
			. $this->validation_actions($payment_id) . 

			'<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#message'.$payment_id.'"> <i class="fa fa-envelope" style="color: green"></i> &nbsp; Message Parent</a></p>';
	}
	
	
	public function options($payment_id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$payment_id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($payment_id) {
		$f = $this->fees_model->get_fee_payment_details_by_id($payment_id);
		$student_id = $f->student_id;
		$student_name = $this->common_model->get_student_fullname($student_id);
		return '<div class="modal fade" id="options'.$payment_id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: ' . $student_name . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						.$this->actions($payment_id).
					'</div>
				</div>
			</div>
		</div>';
	} 


	public function full_payment_form($payment_id) {
		$today = date('Y-m-d'); 
		$f = $this->fees_model->get_fee_payment_details_by_id($payment_id);
		$session = $f->session;
		$term = $f->term;
		$class_id = $f->class_id;
		$student_id = $f->student_id;
		$uri_segment = $session.'/'.$term.'/'.$class_id.'/'.$student_id;
		$amount_payable = $this->fees_model->get_amount_payable_by_student($session, $term, $class_id, $student_id);

		return form_open($this->c_controller.'/approve_full_payment/'.$uri_segment). 
			'<p>Amount Payable: ' . $amount_payable . '</p>
			<div class="m-t-10">
				<label>Transaction/Payment ID <small>(Deposit slip number, POS transaction code, etc)</small> </label> <br />
				<input type="text" class="form-control w-100" name="transaction_id" required />
			</div>
			<div class="m-t-10">
				<label>Date Paid</label> <br />
				<input type="date" class="form-control w-100" name="date_paid" max="' . $today . '" required />
			</div>
			<div class="m-t-10">
				<button class="btn btn-primary">Approve</button>
			</div>'
		. form_close();
	}
	
	
	public function modal_approve_full_payment($payment_id) {
		$f = $this->fees_model->get_fee_payment_details_by_id($payment_id);
		$student_id = $f->student_id;
		$student_name = $this->common_model->get_student_fullname($student_id);
		return	'<div class="modal fade" id="full'.$payment_id.'" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-form-sm">
						<div class="modal-header">
							<div class="pull-right">
								<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
							</div>
							<h4 class="modal-title"> <i class="fa fa-check"></i> Full Payment: ' . $student_name . '</h4>
						</div><!--/.modal-header-->
						<div class="modal-body">'
							. $this->full_payment_form($payment_id) .
						'</div>
					</div>
				</div>
			</div>';
	}


	public function subsequent_partial_payment_form($payment_id) {
		$today = date('Y-m-d'); 
		$f = $this->fees_model->get_fee_payment_details_by_id($payment_id);
		$session = $f->session;
		$term = $f->term;
		$class_id = $f->class_id;
		$student_id = $f->student_id;
		$uri_segment = $session.'/'.$term.'/'.$class_id.'/'.$student_id;
		$amount_payable = $this->fees_model->get_amount_payable_by_student($session, $term, $class_id, $student_id);

		return form_open($this->c_controller.'/approve_subsequent_partial_payment/'.$uri_segment). 
			'<p>Amount Payable: ' . $amount_payable . '</p>
			<div class="m-t-10">
				<label>Transaction/Payment ID <small>(Deposit slip number, POS transaction code, etc)</small> </label> <br />
				<input type="text" class="form-control w-100" name="transaction_id" required />
			</div>
			<p class="m-t-10">Previous Balance: ' . $this->fees_balance($payment_id) . '</p> 
			<div class="m-t-10">
				<label>Amount Paid</label> <br />
				<input type="number" class="form-control w-100" name="fees_amount_paid" required />
			</div>
			<div class="m-t-10">
				<label>Date Paid</label> <br />
				<input type="date" class="form-control w-100" name="date_paid" max="' . $today . '" required />
			</div>
			<div class="m-t-10">
				<button class="btn btn-primary">Approve</button>
			</div>'
		. form_close();
	}
	
	
	public function modal_approve_subsequent_partial_payment($payment_id) {
		$f = $this->fees_model->get_fee_payment_details_by_id($payment_id);
		$student_id = $f->student_id;
		$student_name = $this->common_model->get_student_fullname($student_id);
		return	'<div class="modal fade" id="partial_sub'.$payment_id.'" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-form-sm">
						<div class="modal-header">
							<div class="pull-right">
								<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
							</div>
							<h4 class="modal-title"> <i class="fa fa-adjust"></i> Partial Payment: ' . $student_name . '</h4>
						</div><!--/.modal-header-->
						<div class="modal-body">'
							. $this->subsequent_partial_payment_form($payment_id) .
						'</div>
					</div>
				</div>
			</div>';
	}
	
	
	public function message_parent_form($payment_id) {
		$f = $this->fees_model->get_fee_payment_details_by_id($payment_id);
		$student_id = $f->student_id;
		$controller = 'students_'.$this->c_user; 
		return form_open($controller.'/message_parent/'.$student_id). 
			'<div>
				<textarea class="t200 w-100 m-b-20" name="message" placeholder="Your message" required></textarea>
			</div>
			<div>
				<button class="btn btn-primary"> <i class="fa fa-arrow-circle-right"></i> Send Message</button>
			</div>'
		. form_close();
	}
	
	
	public function modal_message_parent($payment_id) {
		$f = $this->fees_model->get_fee_payment_details_by_id($payment_id);
		$student_id = $f->student_id;
		$y = $this->common_model->get_student_details_by_id($student_id);
		$parent_id = $y->parent_id;
		$parent_name = $this->common_model->get_parent_details_by_id($parent_id)->name;
		return '<div class="modal fade" id="message'. $student_id .'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Message: ' . $parent_name . '</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								. $this->message_parent_form($payment_id) .
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function modals($payment_id) {
		return 	$this->modal_options($payment_id) . 
				$this->modal_approve_full_payment($payment_id) . 
				$this->modal_approve_subsequent_partial_payment($payment_id) . 
				$this->modal_message_parent($payment_id);
	}
	
	
	
	
}