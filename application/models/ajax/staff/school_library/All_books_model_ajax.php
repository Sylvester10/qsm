<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== */
/*
Name: All_books_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of all books in staff's dashboard
Controller: School_library_staff
Author: Nwankwo Ikemefuna
Date Created: 14th June, 2018
*/


class All_books_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('School_library_model');
	}

	var $table = 'school_library';
	var $column_order = array(null, 'id', 'book_name', 'book_no', 'edition', 'author', 'total_copies', 'copies_available', 'date_added'); //set column field database for datatable orderable
	var $column_search = array('id', 'book_name', 'book_no', 'edition', 'author', 'total_copies', 'copies_available', 'date_added'); //set column field database for datatable searchable 
	var $order = array('date_added' => 'desc'); 

	
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
	

	function get_records() {
		$this->the_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where('school_id', school_id);
	    $query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records() {
		$this->the_query();
		$this->db->where('school_id', school_id);
	    $query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records() {
		$this->db->where('school_id', school_id);
	    $this->db->from($this->table);
		return $this->db->count_all_results();
	}
	 
	
	public function copies_borrowed($book_id) {
		$y = $this->school_library_model->get_book_details_by_id($book_id);
		$total_copies = $y->total_copies;
		$copies_available = $y->copies_available;
		$copies_borrowed = $total_copies - $copies_available;
		return $copies_borrowed;
	}
	
	
	public function actions($book_id) {
		$y = $this->school_library_model->get_book_details_by_id($book_id);
		return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('school_library_staff/edit_book/'.$book_id) .'"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Book </a></p>
		
		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#lend_book_student'.$book_id.'" href="#"> <i class="fa fa-book" style="color: green"></i> &nbsp; Lend Book to Student</a></p>
		
		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#lend_book_staff'.$book_id.'" href="#"> <i class="fa fa-book" style="color: green"></i> &nbsp; Lend Book to Staff</a></p>

		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#delete'.$book_id.'"> <i class="fa fa-trash" style="color: red"></i> &nbsp; Delete Book</a></p>';
	}
	
	
	public function options($book_id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$book_id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($book_id) {
		$y = $this->school_library_model->get_book_details_by_id($book_id);
		return '<div class="modal fade" id="options'.$book_id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: '  . $y->book_name . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						. $this->actions($book_id) .
					'</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function lend_book_form_student($book_id) {
		$y = $this->school_library_model->get_book_details_by_id($book_id);
		$copies_available = $y->copies_available;
		$copies_option = "";
		for ($copy = 1; $copy <= $copies_available; $copy++) {
			$copies_option .= '<option value="' .$copy. '">' .$copy. '</option>';
		} 
		return form_open('school_library_staff/lend_book_student/'.$book_id).
			'<div class="m-b-10">
				<label>Student\'s Registration ID <small>(Use search button above to verify Registration ID)</small></label> <br />
				<input type="text" class="form-control w-100" name="reg_id" required />
			</div>
			<div class="m-b-10">
				<label>Copies Borrowed (available: ' . $copies_available . ')</label> <br />
				<select class="form-control w-100" name="copies_borrowed" required>'
					. $copies_option .
				'</select>
			</div>
			<div class="m-b-10">
				<label>Date Due</label> <br />
				<input type="date" class="form-control w-100" name="date_due" required />
			</div>
			<div class="m-t-10">
				<button class="btn btn-primary">Submit</button>
			</div>'
		. form_close();
	}


	public function modal_lend_book_student($book_id) {
		$y = $this->school_library_model->get_book_details_by_id($book_id);
		return '<div class="modal fade" id="lend_book_student'.$book_id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form-sm">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Lend Book to Student: ' . $y->book_name . '</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								. $this->lend_book_form_student($book_id) .
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function lend_book_form_staff($book_id) {
		$y = $this->school_library_model->get_book_details_by_id($book_id);
		$copies_available = $y->copies_available;
		$copies_option = "";
		for ($copy = 1; $copy <= $copies_available; $copy++) {
			$copies_option .= '<option value="' .$copy. '">' .$copy. '</option>';
		} 
		return form_open('school_library_staff/lend_book_staff/'.$book_id).
			'<div class="m-b-10">
				<label>Staff\'s Email (Registered).</label> <br />
				<input type="email" class="form-control w-100" name="email" required />
			</div>
			<div class="m-b-10">
				<label>Copies Borrowed (available: ' . $copies_available . ')</label> <br />
				<select class="form-control w-100" name="copies_borrowed" required>'
					. $copies_option .
				'</select>
			</div>
			<div class="m-b-10">
				<label>Date Due</label> <br />
				<input type="date" class="form-control w-100" name="date_due" required />
			</div>
			<div class="m-t-10">
				<button class="btn btn-primary">Submit</button>
			</div>'
		. form_close();
	}


	public function modal_lend_book_staff($book_id) {
		$y = $this->school_library_model->get_book_details_by_id($book_id);
		return '<div class="modal fade" id="lend_book_staff'.$book_id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form-sm">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Lend Book to Staff: ' . $y->book_name . '</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								. $this->lend_book_form_staff($book_id) .
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function modal_delete_confirm($book_id) {
		$y = $this->school_library_model->get_book_details_by_id($book_id);
		return '<div class="modal fade" id="delete'.$book_id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Delete: '  . $y->book_name . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						Are you sure you want to permanently delete ' . $y->book_name . '?
					</div>
					<div class="modal-footer">
						<a class="btn btn-sm btn-danger" role="button" href="' .base_url('school_library_staff/delete_book/'.$book_id). '"> Yes, Delete </a>
						<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function modals($book_id) {
		return 	$this->modal_options($book_id) . 
				$this->modal_lend_book_student($book_id) .
				$this->modal_lend_book_staff($book_id) .
				$this->modal_delete_confirm($book_id);
	}
	
	
	
	
}