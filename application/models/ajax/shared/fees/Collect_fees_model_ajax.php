<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Collect_fees_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of fees
Controllers: Fees_admin, Fees_staff
Authors: Nwankwo Ikemefuna
Date Created: 24th June, 2018
*/


class Collect_fees_model_ajax extends CI_Model {
	public function __construct() { 
		parent::__construct();
		$this->load->model('fees_model');
	}

	var $table = 'students'; //where fee payment status (for current term) is NULL (meaning no )
	var $column_order = array(null, 'id', 'admission_id', 'last_name', 'first_name', 'other_name', 'class_id'); //set column field database for datatable orderable
	var $column_search = array('id', 'admission_id', 'last_name', 'first_name', 'other_name', 'class_id'); //set column field database for datatable searchable 
	var $order = array('class_id' => 'asc'); 

	
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
	

	function get_records($class_id) {
		$this->the_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where(array('school_id' => school_id, 'class_id' => $class_id, 'revoked' => 'false', 'graduated' => 'false'));
		$query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records($class_id) {
		$this->the_query();
		$this->db->where(array('school_id' => school_id, 'class_id' => $class_id, 'revoked' => 'false', 'graduated' => 'false'));
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records($class_id) {
	    $this->db->where(array('school_id' => school_id, 'class_id' => $class_id, 'revoked' => 'false', 'graduated' => 'false'));
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	
	public function payment_status($session, $term, $class_id, $student_id) {
		$query = $this->fees_model->get_payment_details($session, $term, $class_id, $student_id);
		if ( ! $query ) { //no payment data found for current term for this student
			$status = NULL;;
		} else { //payment data found for current term for this student
			$payment_status = $query->status;
			switch ($payment_status) {
				case 'Full Payment':
					$status = '<b class="text-success">Full Payment</b>';
				break;
				case 'Partial Payment':
					$status = '<b class="text-primary">Partial Payment</b>';
				break;
				default:
					$status = '<b class="text-danger">No Payment</b>';
				break;
			}
		}
		return $status;
	}
	
	
	public function fees_amount_paid($session, $term, $class_id, $student_id) {
		$query = $this->fees_model->get_payment_details($session, $term, $class_id, $student_id);
		if ( ! $query ) { //no payment data found for current term for this student
			$amount_paid = NULL;
		} else { //payment data found for current term for this student
			$amount_paid = ($query->amount_paid != NULL) ? (s_currency_symbol . number_format($query->amount_paid)) : NULL;
		}
		return $amount_paid;
	}
	
	
	public function fees_balance($session, $term, $class_id, $student_id) {
		$query = $this->fees_model->get_payment_details($session, $term, $class_id, $student_id);
		if ( ! $query ) { //no payment data found for current term for this student
			$balance = NULL;
		} else { //payment data found for current term for this student
			$balance = ($query->balance == NULL || $query->balance == 'No Balance') ? NULL : (s_currency_symbol . number_format($query->balance));
		}
		return $balance;
	}
	
	
	public function get_fees_date_paid($session, $term, $class_id, $student_id) {
		$query = $this->fees_model->get_payment_details($session, $term, $class_id, $student_id);
		if ( ! $query ) { //no payment data found for current term for this student
			$date_paid = NULL;
		} else { //payment data found for current term for this student
			$date_paid = x_date($query->date_paid);
		}
		return $date_paid;
	}


	public function get_fee_transaction_id($session, $term, $class_id, $student_id) {
		$query = $this->fees_model->get_payment_details($session, $term, $class_id, $student_id);
		if ( ! $query ) { //no payment data found for current term for this student
			$transaction_id = NULL;
		} else { //payment data found for current term for this student
			$transaction_id = $query->transaction_id;
		}
		return $transaction_id;
	}

	
	public function payment_status_actions($session, $term, $class_id, $student_id) {
		$uri_segment = $session.'/'.$term.'/'.$class_id.'/'.$student_id;
		$query = $this->fees_model->get_payment_details($session, $term, $class_id, $student_id);
		if ( ! $query ) { //no payment data found for current term for this student

			return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#full'.$student_id.'"> <i class="fa fa-check" style="color: green"></i> &nbsp; Approve Full Payment</a></p>
			
			<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#partial'.$student_id.'"> <i class="fa fa-adjust" style="color: green"></i> &nbsp; Approve Partial Payment</a></p>

			<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/mark_fees_unpaid/'.$uri_segment) .'"> <i class="fa fa-times" style="color: red"></i> &nbsp; Mark as Unpaid </a></p>';

		} else { //payment data found for current term for this student

			//check payment status
			$payment_status = $query->status;
			if ($payment_status == 'Full Payment') {
				
				return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/unapprove_payment/'.$uri_segment) .'"> <i class="fa fa-times" style="color: red"></i> &nbsp; Unapprove Payment </a></p>';
				
			} elseif ($payment_status == 'Partial Payment') {
				
				return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#full'.$student_id.'"> <i class="fa fa-check" style="color: green"></i> &nbsp; Approve Full Payment</a></p>
				
				<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#partial_sub'.$student_id.'"> <i class="fa fa-adjust" style="color: green"></i> &nbsp; Approve Partial Payment (Subsequent)</a></p>
				
				<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/unapprove_payment/'.$uri_segment) .'"> <i class="fa fa-times" style="color: red"></i> &nbsp; Unapprove Partial Payment </a></p>';

			} elseif ($payment_status == 'No Payment') {

				return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#full'.$student_id.'"> <i class="fa fa-check" style="color: green"></i> &nbsp; Approve Full Payment</a></p>
			
				<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#partial'.$student_id.'"> <i class="fa fa-adjust" style="color: green"></i> &nbsp; Approve Partial Payment</a></p>';

			}
		}
	}
	
	
	public function actions($session, $term, $class_id, $student_id) {
		return $this->payment_status_actions($session, $term, $class_id, $student_id) .	
		'<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#message'.$student_id.'"> <i class="fa fa-envelope" style="color: green"></i> &nbsp; Message Parent</a></p>';
	}
	
	
	public function options($student_id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$student_id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($session, $term, $class_id, $student_id) {
		$student_name = $this->common_model->get_student_fullname($student_id);
		return '<div class="modal fade" id="options'.$student_id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: ' . $student_name . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						.$this->actions($session, $term, $class_id, $student_id).
					'</div>
				</div>
			</div>
		</div>';
	} 
	

	public function full_payment_form($session, $term, $class_id, $student_id) {
		$today = date('Y-m-d'); 
		$amount_payable = $this->fees_model->get_amount_payable_by_student($session, $term, $class_id, $student_id);
		$uri_segment = $session.'/'.$term.'/'.$class_id.'/'.$student_id;
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
	
	
	public function modal_approve_full_payment($session, $term, $class_id, $student_id) {
		$student_name = $this->common_model->get_student_fullname($student_id);
		return	'<div class="modal fade" id="full'.$student_id.'" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-form-sm">
						<div class="modal-header">
							<div class="pull-right">
								<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
							</div>
							<h4 class="modal-title"> <i class="fa fa-check"></i> Full Payment: ' . $student_name . '</h4>
						</div><!--/.modal-header-->
						<div class="modal-body">'
							. $this->full_payment_form($session, $term, $class_id, $student_id) .
						'</div>
					</div>
				</div>
			</div>';
	}
	
	
	public function partial_payment_form($session, $term, $class_id, $student_id) {
		$today = date('Y-m-d'); 
		$amount_payable = $this->fees_model->get_amount_payable_by_student($session, $term, $class_id, $student_id);
		$uri_segment = $session.'/'.$term.'/'.$class_id.'/'.$student_id;
		return form_open($this->c_controller.'/approve_partial_payment/'.$uri_segment). 
			'<p>Amount Payable: ' . $amount_payable . '</p>
			<div class="m-t-10">
				<label>Transaction/Payment ID <small>(Deposit slip number, POS transaction code, etc)</small> </label> <br />
				<input type="text" class="form-control w-100" name="transaction_id" required />
			</div>
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
	
	
	public function modal_approve_partial_payment($session, $term, $class_id, $student_id) {
		$student_name = $this->common_model->get_student_fullname($student_id);
		return	'<div class="modal fade" id="partial'.$student_id.'" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-form-sm">
						<div class="modal-header">
							<div class="pull-right">
								<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
							</div>
							<h4 class="modal-title"> <i class="fa fa-adjust"></i> Partial Payment: ' . $student_name . '</h4>
						</div><!--/.modal-header-->
						<div class="modal-body">'
							. $this->partial_payment_form($session, $term, $class_id, $student_id) .
						'</div>
					</div>
				</div>
			</div>';
	}
	
	
	public function subsequent_partial_payment_form($session, $term, $class_id, $student_id) {
		$today = date('Y-m-d'); 
		$amount_payable = $this->fees_model->get_amount_payable_by_student($session, $term, $class_id, $student_id);
		$uri_segment = $session.'/'.$term.'/'.$class_id.'/'.$student_id;
		$balance = $this->fees_balance($session, $term, $class_id, $student_id);
		return form_open($this->c_controller.'/approve_subsequent_partial_payment/'.$uri_segment). 
			'<p>Amount Payable: ' . $amount_payable . '</p>
			<div class="m-t-10">
				<label>Transaction/Payment ID <small>(Deposit slip number, POS transaction code, etc)</small> </label> <br />
				<input type="text" class="form-control w-100" name="transaction_id" required />
			</div>
			<p class="m-t-10">Previous Balance: ' . $balance . '</p> 
			<div class="m-t-10">
				<label>Amount Paid</label> <br />
				<input type="number" class="form-control w-100" name="fees_amount_paid" max="' . $balance . '" required />
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
	
	
	public function modal_approve_subsequent_partial_payment($session, $term, $class_id, $student_id) {
		$student_name = $this->common_model->get_student_fullname($student_id);
		return	'<div class="modal fade" id="partial_sub'.$student_id.'" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-form-sm">
						<div class="modal-header">
							<div class="pull-right">
								<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
							</div>
							<h4 class="modal-title"> <i class="fa fa-adjust"></i> Partial Payment: ' . $student_name . '</h4>
						</div><!--/.modal-header-->
						<div class="modal-body">'
							. $this->subsequent_partial_payment_form($session, $term, $class_id, $student_id) .
						'</div>
					</div>
				</div>
			</div>';
	}


	public function message_parent_form($student_id) {
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
	
	
	public function modal_message_parent($student_id) {
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
								. $this->message_parent_form($student_id) .
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function modals($session, $term, $class_id, $student_id) {
		return 	$this->modal_options($session, $term, $class_id, $student_id) . 
				$this->modal_approve_full_payment($session, $term, $class_id, $student_id) .
				$this->modal_approve_partial_payment($session, $term, $class_id, $student_id) .
				$this->modal_approve_subsequent_partial_payment($session, $term, $class_id, $student_id) .
				$this->modal_message_parent($student_id);
	}
	
	
	
	
}