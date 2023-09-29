<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== */
/*
Name: All_borrowed_books_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of all borrowed books in admin's dashboard
Controller: School_library_admin
Author: Nwankwo Ikemefuna
Date Created: 14th June, 2018
*/


class All_borrowed_books_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('school_library_model');
	}

	var $table = 'borrowed_books';
	var $column_order = array(null, 'id', 'book_id', 'copies_borrowed', 'borrower_id', 'date_due', 'date_borrowed'); //set column field database for datatable orderable
	var $column_search = array('id', 'book_id', 'copies_borrowed', 'borrower_id', 'date_due', 'date_borrowed'); //set column field database for datatable searchable 
	var $order = array('date_borrowed' => 'desc'); 

	
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
	
	
	public function book_details($bb_id) {
		$y = $this->school_library_model->get_borrowed_book_details($bb_id);
		$book_id = $y->book_id;
		$b = $this->school_library_model->get_book_details_by_id($book_id);
		$book_details = 'Author: ' . $b->author . '<br />';
		$book_details .= 'ISBN Number: ' . $b->book_no . '<br />';
		$book_details .= 'Edition: ' . $b->edition;
		return $book_details;
	}


	public function borrower_name($bb_id) {
		$y = $this->school_library_model->get_borrowed_book_details($bb_id);
		$borrower = $y->borrower;
		$borrower_id = $y->borrower_id;
		//check if borrower is student or staff and retrieve their name from the appropriate table
		if ($borrower == 'Student') {
			$borrower_name = $this->common_model->get_student_fullname($borrower_id);
		} elseif ($borrower == 'Staff') {
			$borrower_name = $this->common_model->get_staff_details_by_id($borrower_id)->name;
		} else {
			$borrower_name = NULL;
		}
		return $borrower_name;
	}


	public function borrower_identification($bb_id) {
		$y = $this->school_library_model->get_borrowed_book_details($bb_id);
		$borrower = $y->borrower;
		$borrower_id = $y->borrower_id;
		//check if borrower is student or staff and retrieve their means of ID from the appropriate table
		if ($borrower == 'Student') { //get student's admission ID
			$borrower_identification = $this->common_model->get_student_details_by_id($borrower_id)->admission_id;
		} elseif ($borrower == 'Staff') { //get staff's email
			$borrower_identification = $this->common_model->get_staff_details_by_id($borrower_id)->email;
		} else {
			$borrower_identification = NULL;
		}
		return $borrower_identification;
	}


	public function borrower_designation($bb_id) {
		$y = $this->school_library_model->get_borrowed_book_details($bb_id);
		$borrower = $y->borrower;
		$borrower_id = $y->borrower_id;
		//check if borrower is student or staff and retrieve their designation from the appropriate table
		if ($borrower == 'Student') { //get student's class
			$class_id = $this->common_model->get_student_details_by_id($borrower_id)->class_id;
			$class = $this->common_model->get_class_details($class_id)->class;
			$borrower_designation = $class;
		} elseif ($borrower == 'Staff') { //get staff's email
			$borrower_designation = $this->common_model->get_staff_details_by_id($borrower_id)->designation;
		} else {
			$borrower_designation = NULL;
		}
		return $borrower_designation;
	}
	
	
	public function check_borrower_is_staff($bb_id) {
		$y = $this->school_library_model->get_borrowed_book_details($bb_id);
		if ($y->borrower == 'Staff') {
			return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#message'.$bb_id.'" href="#"> <i class="fa fa-envelope" style="color: green"></i> &nbsp; Message Staff </a></p>';
		} else {
			return NULL;
		}
	}
	
	
	public function actions($bb_id) {
		$y = $this->school_library_model->get_borrowed_book_details($bb_id);
		return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('school_library_admin/edit_book/'.$y->book_id) .'"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Book </a></p>
		
		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('school_library_staff/return_book/'.$bb_id) .'"> <i class="fa fa-check" style="color: green"></i> &nbsp; Mark as Returned (All Copies) </a></p>
		
		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#return_partial'.$bb_id.'" href="#"> <i class="fa fa-adjust" style="color: green"></i> &nbsp; Mark as Returned (Partial Copies) </a></p>'
		
		. $this->check_borrower_is_staff($bb_id);
	}
	
	
	public function options($bb_id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$bb_id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($bb_id) {
		$y = $this->school_library_model->get_borrowed_book_details($bb_id);
		$book_id = $y->book_id;
		$book_name = $this->school_library_model->get_book_details_by_id($book_id)->book_name;
		return '<div class="modal fade" id="options'.$bb_id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: '  . $book_name . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						. $this->actions($bb_id) .
					'</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function return_book_partial_copies_form($bb_id) {
		$y = $this->school_library_model->get_borrowed_book_details($bb_id);
		//list the possible copies to return i.e. from 1 to the number of copies borrowed
		$copies_borrowed = $y->copies_borrowed;
		$copies_option = "";
		for ($copy = 1; $copy <= $copies_borrowed; $copy++) {
			$copies_option .= '<option value="' .$copy. '">' .$copy. '</option>';
		} 
		$copies_option = $copies_option;
		return form_open('school_library_admin/return_book_partial_copies/'.$bb_id).
			'<div class="m-b-10">
				<label>Copies Returned (borrowed: ' . $copies_borrowed . ')</label> <br />
				<select class="form-control w-100" name="copies_returned" required>'
					. $copies_option .
				'</select>
			</div>
			<div class="m-t-10">
				<button class="btn btn-primary">Submit</button>
			</div>'
		. form_close();
	}


	public function modal_return_book_partial_copies($bb_id) {
		$y = $this->school_library_model->get_borrowed_book_details($bb_id);
		$book_id = $y->book_id;
		$book_name = $this->school_library_model->get_book_details_by_id($book_id)->book_name;
		return '<div class="modal fade" id="return_partial'.$bb_id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form-sm">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Return Book: ' . $book_name . '</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								. $this->return_book_partial_copies_form($bb_id) .
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function message_staff_form($bb_id) {
		return form_open('school_library_admin/message_staff/'.$bb_id). 
			'<div>
				<textarea class="t200 w-100 m-b-20" name="message" placeholder="Your message" required></textarea>
			</div>
			<div>
				<button class="btn btn-primary"> <i class="fa fa-arrow-circle-right"></i> Send Message</button>
			</div>'
		. form_close();
	}
	
	
	public function modal_message_staff($bb_id) {
		$y = $this->school_library_model->get_borrowed_book_details($bb_id);
		$borrower = $y->borrower;
		$borrower_id = $y->borrower_id;
		if ($borrower == 'Staff') { 
			$staff_name = $this->common_model->get_staff_details_by_id($borrower_id)->name;
		} else {
			$staff_name = NULL;
		}
		return '<div class="modal fade" id="message'.$bb_id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Message: ' . $staff_name . '</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								. $this->message_staff_form($bb_id) .
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function modals($bb_id) {
		return 	$this->modal_options($bb_id) . 
				$this->modal_return_book_partial_copies($bb_id) .
				$this->modal_message_staff($bb_id);
	}
	
	
	
	
}