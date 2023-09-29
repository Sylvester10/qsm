<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== */
/*
Name: School_library_model
Role: Model
Description: Controls the DB processes of school library books from the admin's end
Controllers: School_library, School_library_Staff
Author: Sylvester Nmakwe, Nwankwo Ikemefuna
Date Created: 12th June, 2018
*/

class School_library_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	
	
	
	public function get_book_details_by_id($book_id) { 
		return $this->db->get_where('school_library', array('id' => $book_id))->row();
	}
	
	
	public function get_all_books() {
		$this->db->where('school_id', school_id);
		return $this->db->get('school_library')->result();	
	}
	
	
	public function get_books_copies() {
		//sum up the total copies for each book
		$this->db->select_sum('total_copies');
		$this->db->where('school_id', school_id);
		$total_copies = $this->db->get('school_library')->row()->total_copies;
		return ($total_copies > 0) ? $total_copies : 0;
	}
	
	
	public function get_available_books_copies() {
		//sum up the available copies for each book
		$this->db->select_sum('copies_available');
		$this->db->where('school_id', school_id);
		$copies_available = $this->db->get('school_library')->row()->copies_available;
		return ($copies_available > 0) ? $copies_available : 0;
	}
	
	
	
	/* ===== Add New Books ===== */
	public function add_new_book() { 
		$book_name = ucwords($this->input->post('book_name', TRUE)); 
		$author = ucwords($this->input->post('author', TRUE));
		$book_no = strtoupper($this->input->post('book_no', TRUE));
		$edition = ucwords($this->input->post('edition', TRUE)); 
		$total_copies = ucwords($this->input->post('total_copies', TRUE));
        $data = array (
			'school_id' => school_id,
			'book_name' => $book_name,
			'author' => $author,
			'book_no' => $book_no,
			'edition' => $edition,
			'total_copies' => $total_copies,
			'copies_available' => $total_copies,
		);
		$this->db->insert('school_library', $data);
	}
	

	public function edit_book($book_id) { 
		$book_name = ucwords($this->input->post('book_name', TRUE)); 
		$author = ucwords($this->input->post('author', TRUE));
		$book_no = strtoupper($this->input->post('book_no', TRUE));
		$edition = ucwords($this->input->post('edition', TRUE)); 
		$extra_copies = $this->input->post('extra_copies', TRUE);
		
		$y = $this->get_book_details_by_id($book_id);
		//check if extra copies were added. Add extra copies to total copies. Also update copies available
		if ($extra_copies != '') { 
			$total_copies = $y->total_copies + $extra_copies;
			$copies_available = $y->copies_available + $extra_copies;
		} else {
			$total_copies = $y->total_copies;
			$copies_available = $y->copies_available;
		}
        $data = array (
			'book_name' => $book_name,
			'author' => $author,
			'book_no' => $book_no,
			'edition' => $edition,
			'total_copies' => $total_copies,
			'copies_available' => $copies_available,
		);
		$this->db->where('id', $book_id);
		$this->db->update('school_library', $data);
	}


	public function check_date_due_not_past($book_id) {
		$date_due = $this->input->post('date_due', TRUE);
		//remove hyphen from date so that we can get date as a numerical value 
		$date_due = str_replace("-", "", $date_due); //now yyyymmdd
		//get today's date in the same yyyymmdd format
		$today = date('Ymd');
		//check if today is less than or equals date due 
		return ($today <= $date_due) ? TRUE : FALSE;
    } 
	
	
	public function lend_book_student($book_id) { //lend book to student
		$reg_id = strtoupper($this->input->post('reg_id', TRUE));
		$s = $this->common_model->get_student_details_by_reg_id($reg_id);
		$student_id = $s->id;
		$copies_borrowed = $this->input->post('copies_borrowed', TRUE);
		$date_due = $this->input->post('date_due', TRUE);
		//remove hyphen from date so that we can get date as a numerical value for making comparisons later in the program
		$date_due_unix = str_replace("-", "", $date_due); //now yyyymmdd
		//get book details
		$data = array (
			'school_id' => school_id,
			'book_id' => $book_id,
			'borrower' => 'Student',
			'borrower_id' => $student_id,
			'copies_borrowed' => $copies_borrowed,
			'date_due' => $date_due,
			'date_due_unix' => $date_due_unix,
		);
		$this->db->insert('borrowed_books', $data);
		//update copies available
		$this->update_copies_available($book_id, $copies_borrowed);
	}
	
	
	public function lend_book_staff($book_id) { //lend book to staff
		$email = strtolower($this->input->post('email', TRUE));
		$s = $this->common_model->get_staff_details($email);
		$staff_id = $s->id;
		$copies_borrowed = $this->input->post('copies_borrowed', TRUE);
		$date_due = $this->input->post('date_due', TRUE);
		//remove hyphen from date so that we can get date as a numerical value for making comparisons later in the program
		$date_due_unix = str_replace("-", "", $date_due); //now yyyymmdd
		//get book details
		$y = $this->get_book_details_by_id($book_id);
        $data = array (
			'school_id' => school_id,
			'book_id' => $book_id,
			'borrower' => 'Staff',
			'borrower_id' => $staff_id,
			'copies_borrowed' => $copies_borrowed,
			'date_due' => $date_due,
			'date_due_unix' => $date_due_unix,
		);
		$this->db->insert('borrowed_books', $data);
		//update copies available
		$this->update_copies_available($book_id, $copies_borrowed);
	}
	
	
	public function update_copies_available($book_id, $copies_borrowed) { //update available copies to reflect lending
		$y = $this->get_book_details_by_id($book_id);
		//available copies is copies available minus copies borrowed
		$copies_available = $y->copies_available - $copies_borrowed;
        $data = array (
			'copies_available' => $copies_available,
		);
		$this->db->where('id', $book_id);
		$this->db->update('school_library', $data);
	}
	
	
	public function delete_book($book_id) {
		return $this->db->delete('school_library', array('id' => $book_id));
    } 
	
	
	public function bulk_actions_books() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		$books = ($selected_rows == 1) ? 'book' : 'books';
		foreach ($row_id as $book_id) {
			switch ($bulk_action_type) {
				case 'return_book':
					$this->return_book($book_id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$books} returned successfully.");
				break;
				case 'delete':
					$y = $this->get_book_details_by_id($book_id);
					$copies_available = $y->copies_available;
					$total_copies = $y->total_copies;
					//ensure no copy of book is borrowed output
					if ($copies_available == $total_copies) { //book is not borrowed out
						$this->delete_book($book_id);
						$this->session->set_flashdata('status_msg', "Books deleted successfully."); 
					} else {
						$this->session->set_flashdata('status_msg_error', 'Some copies of the selected books were lent out. Please recover copies before deleting books.');
					}
				break;
			}
		} 
	}
	
	
	
	
	/* =================== Borrowed Books =========================*/
	
	public function get_borrowed_book_details($bb_id) { 
		return $this->db->get_where('borrowed_books', array('id' => $bb_id))->row();
	}
	
	
	public function get_all_borrowers() {
		$this->db->where('school_id', school_id);
		return $this->db->get('borrowed_books')->result();
	}
	
	
	public function get_borrowed_books_copies() {
		//sum up the borrowed copies for each borrower
		$this->db->select_sum('copies_borrowed');
		$this->db->where('school_id', school_id);
		$copies_borrowed = $this->db->get('borrowed_books')->row()->copies_borrowed;
		return ($copies_borrowed > 0) ? $copies_borrowed : 0;
	}


	public function get_due_books() {
		$today = date('Ymd'); //today's date in the format yyyymmdd (to conform with the format of date_due_unix)
		$where = array (
			'school_id' => school_id,
			'date_due_unix <' => $today  
		);
		$this->db->where($where);
		$query = $this->db->get('borrowed_books')->result();
		$total_due_books = count($query);
		return ($total_due_books > 0) ? $total_due_books : NULL;
	}


	public function get_total_due_books() {
		//sum up copies borrowed of books that are due for return
		$today = date('Ymd'); //today's date in the format yyyymmdd (to conform with the format of date_due_unix)
		$where = array (
			'school_id' => school_id,  
			'date_due_unix <' => $today  
		);
		$this->db->where($where);
		$this->db->select_sum('copies_borrowed');
		$total_copies_borrowed = $this->db->get('borrowed_books')->row()->copies_borrowed;
		return ($total_copies_borrowed > 0) ? $total_copies_borrowed : NULL;
	}


	public function check_date_due($bb_id) {
		$y = $this->get_borrowed_book_details($bb_id);
		$date_due = $y->date_due_unix; //saved in the format yyyymmdd
		//get today's date in the same yyyymmdd format
		$today = date('Ymd');
		//check if today equals date due 
		if ($today == $date_due) {
			return TRUE; //it's date due
		} else {
			return FALSE; //book is not due for return yet
		}
    } 


    public function flag_book_due($bb_id) {
		$y = $this->get_borrowed_book_details($bb_id);
		$date_due = x_date($y->date_due); 
		$is_due = $this->check_date_due($bb_id);
		if ($is_due) {
			return '<div class="pull-right badge" style="background: rgba(243,156,18,.88);"> Due Today</div>';
		}
    } 


    public function check_date_overdue($bb_id) {
		$y = $this->get_borrowed_book_details($bb_id);
		$date_due = $y->date_due_unix; //saved in the format yyyymmdd
		//get today's date in the same yyyymmdd format
		$today = date('Ymd');
		//check if today is greater than date due 
		if ($today > $date_due) {
			return TRUE; //it's beyond date due
		} else {
			return FALSE; //book is not due for return yet
		}
    } 


    public function flag_book_overdue($bb_id) {
		$y = $this->get_borrowed_book_details($bb_id);
		$date_due = x_date($y->date_due); 
		$is_overdue = $this->check_date_overdue($bb_id);
		if ($is_overdue) {
			return '<div class="pull-right badge" style="background: #a94442;"> Overdue </div>';
		}
    } 


    public function get_date_due($bb_id) {
		$y = $this->get_borrowed_book_details($bb_id);
		$date_due = x_date($y->date_due); 
		$is_due = $this->check_date_overdue($bb_id);
		if ($is_due) {
			return '<b class="text-danger">' . $date_due . '</b>';
		} else {
			return $date_due;
		}
    } 
	
	
	public function return_book($bb_id) { 
		//update copies available in school library table 
		//Action: add copies borrowed to copies available
		$y = $this->get_borrowed_book_details($bb_id);
		$book_id = $y->book_id;
		$copies_borrowed = $y->copies_borrowed;
        $this->update_copies_available_on_return($bb_id, $book_id, $copies_borrowed);	
		//delete record from borrowed books
		$this->db->delete('borrowed_books', array('id' => $bb_id));
	}
	
	
	public function return_book_partial_copies($bb_id) { 
		$y = $this->get_borrowed_book_details($bb_id);
		$copies_borrowed = $y->copies_borrowed;
		$copies_returned = $this->input->post('copies_returned', TRUE);
		//update copies borrowed
		//Action: subtract copies returned from copies borrowed
		$copies_remaining = $copies_borrowed - $copies_returned;
		$data = array (
			'copies_borrowed' => $copies_remaining
		);
		$this->db->where('id', $bb_id);
		$this->db->update('borrowed_books', $data);
		
		//update copies available in school library table 
		//Action: add copies returned to copies available
		$book_id = $y->book_id;
		$this->update_copies_available_on_return($bb_id, $book_id, $copies_returned);
	}
	
	
	public function update_copies_available_on_return($bb_id, $book_id, $copies_returned) { 
		$copies_available = $this->get_book_details_by_id($book_id)->copies_available;
		$data = array (
			'copies_available' => $copies_available + $copies_returned
		);
		$this->db->where('id', $book_id);
		$this->db->update('school_library', $data);
	}
	
	
	public function message_staff($bb_id) {
		$message = nl2br(ucfirst($this->input->post('message', TRUE))); 
		$subject = 'Message from School Library';
		$y = $this->get_borrowed_book_details($bb_id);
		$staff_id = $y->borrower_id;
		$email = $this->common_model->get_staff_details_by_id($staff_id)->email;
		return email_user($email, $subject, $message); //email staff
    } 
	
	
	

	
}