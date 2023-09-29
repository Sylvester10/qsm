<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: School_library_admin
Role: Controller
Description: School_library_admin Class controls access to all library books pages and functions from the admin's end
Model: School_library_model
Author: Nwankwo Ikemefuna
Date Created: 14th June, 2018
*/


class School_library_admin extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->load->model('school_library_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_admin(school_id, mod_school_library); //school library module
		$this->activation_restricted_admin(school_id); 

		//module-level scripts
		$this->admin_module_scripts = array('s_school_library');
	}



	public function index() {
		$inner_page_title = 'All Books (' .count($this->school_library_model->get_all_books()). ')'; 
		$this->admin_header('All Books', $inner_page_title);	
		//$data['total_borrowed_books'] = count($this->school_library_model->get_all_borrowed_books());
		$total_unique_books = count($this->school_library_model->get_all_books());
		$total_books_copies = $this->school_library_model->get_books_copies();
		$total_available_copies = $this->school_library_model->get_available_books_copies();
		$total_borrowed_copies = $total_books_copies - $total_available_copies;
		$data['total_unique_books'] = $total_unique_books;
		$data['total_books_copies'] = $total_books_copies;
		$data['total_available_copies'] = $total_available_copies;
		$data['total_borrowed_copies'] = $total_borrowed_copies;
		$data['total_borrowers'] = count($this->school_library_model->get_all_borrowers());
		$data['due_books'] = $this->school_library_model->get_due_books();
		$data['total_due_books'] = $this->school_library_model->get_total_due_books();
		$this->load->view('admin/school_library/all_books', $data);
        $this->admin_footer();
	}
	
	
	public function all_books_ajax() {
		$this->load->model('ajax/admin/school_library/all_books_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->book_name; 
			$row[] = $y->author;
			$row[] = $y->book_no;
			$row[] = $y->edition;
			$row[] = $this->current_model->copies_borrowed($y->id);
			$row[] = $y->copies_available;
			$row[] = $y->total_copies;
			$row[] = x_date($y->date_added);
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records(),
			"recordsFiltered" => $this->current_model->count_filtered_records(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	


	/* ====== New Book ====== */
	public function new_book() {
		$this->admin_header('New Book', 'New Book');	
		$this->load->view('admin/school_library/new_book');
        $this->admin_footer();
	}


	public function add_new_book_ajax() { 
		$this->form_validation->set_rules('book_name', 'Book Title', 'trim|required');
		$this->form_validation->set_rules('author', 'Author', 'trim|required');
		$this->form_validation->set_rules('book_no', 'ISBN Number', 'trim');
		$this->form_validation->set_rules('edition', 'Edition', 'trim');
		$this->form_validation->set_rules('total_copies', 'Copies', 'trim|required|is_natural');
		
		if ($this->form_validation->run())  {	
			$this->school_library_model->add_new_book(); //insert the data into db
			echo 1;	
		} else { 
			echo validation_errors();
		}
	}

	
	/* ====== Edit Book ====== */
	public function edit_book($book_id) {
		//check book exists for this school
		$this->check_school_data_exists(school_id, $book_id, 'id', 'school_library', 'admin');	
		$book_details = $this->school_library_model->get_book_details_by_id($book_id);
		$page_title = 'Edit Book: ' . $book_details->book_name;
		$this->admin_header($page_title, $page_title);
		$data['y'] = $book_details;
		$this->load->view('admin/school_library/edit_book', $data);
        $this->admin_footer();
	}
	
	
	public function edit_book_ajax($book_id) { 
		//check book exists for this school
		$this->check_school_data_exists(school_id, $book_id, 'id', 'school_library', 'admin');	
		$this->form_validation->set_rules('book_name', 'Book Title', 'trim|required');
		$this->form_validation->set_rules('author', 'Author', 'trim|required');
		$this->form_validation->set_rules('book_no', 'ISBN Number', 'trim');
		$this->form_validation->set_rules('edition', 'Edition', 'trim');
		$this->form_validation->set_rules('extra_copies', 'Extra Copies', 'trim|is_natural');	
		if ($this->form_validation->run())  {	
			$this->school_library_model->edit_book($book_id); //update book details
			echo 1;		
		} else { 
			echo validation_errors();
		}
	}
	
	
	public function lend_book_student($book_id) { 
		//check book exists for this school
		$this->check_school_data_exists(school_id, $book_id, 'id', 'school_library', 'admin');	
		$this->form_validation->set_rules('reg_id', 'Student\'s Reg ID', 'trim|required');
		$this->form_validation->set_rules('copies_borrowed', 'Copies Borrowed', 'trim|required|is_natural');
		$this->form_validation->set_rules('date_due', 'Date Due', 'trim|required');
		
		if ($this->form_validation->run())  {	
		
			$y = $this->school_library_model->get_book_details_by_id($book_id);
			$copies_available = $y->copies_available;
			$copies_borrowed = $this->input->post('copies_borrowed', TRUE);
			$reg_id = strtoupper($this->input->post('reg_id', TRUE));
				
			//ensure student id exists
			$q_student = $this->common_model->get_student_details_by_reg_id($reg_id);
			
			if ( ! $q_student ) { //student doesn't exist
				$this->session->set_flashdata('status_msg_error', 'No student with the specified Registration ID was found.');

			} elseif ($q_student && $q_student->school_id != school_id) { //student exists in table but not registered under this school
				$this->session->set_flashdata('status_msg_error', 'The student with the specified Registration ID is not registered under this school.');
				
			} elseif ($q_student && $q_student->revoked == 'true') { //student's admission has been revoked
				$this->session->set_flashdata('status_msg_error', 'The student with the specified Registration ID is currently revoked.');
				
			} elseif ($q_student && $q_student->suspended == 'true') { //student is currently suspended
				$this->session->set_flashdata('status_msg_error', 'The student with the specified Registration ID is currently suspended.');

			} elseif ($q_student && $q_student->graduated == 'true') { //student has graduated
				$this->session->set_flashdata('status_msg_error', 'The student with the specified Registration ID has been graduated.');
				
			} else { //all is well, everyone is happy
				
				//ensure copies borrowed is not greater than copies available
				if ($copies_borrowed <= $copies_available) {

					//ensure date due is not in the past
					$valid_date_due = $this->school_library_model->check_date_due_not_past($book_id);
					if ($valid_date_due) {
						//lend the book
						$this->school_library_model->lend_book_student($book_id);
						$student_id = $q_student->id;
						$borrower = $this->common_model->get_student_fullname($student_id);
						$s_copies = ($copies_borrowed == 1) ? 'copy' : 'copies';
						$this->session->set_flashdata('status_msg', "{$copies_borrowed} {$s_copies} of {$y->book_name} lent to {$borrower} successfully.");
					} else {
						$this->session->set_flashdata('status_msg_error', 'Date due cannot be in the past.');
					}
				} else {
					$this->session->set_flashdata('status_msg_error', 'Copies to be lent cannot exceed available copies.');
				}
				
			}
			
		} else {
			$this->session->set_flashdata('status_msg_error', 'Error lending book to student.');
		}
		redirect($this->agent->referrer());
	}
	
	
	public function lend_book_staff($book_id) { 
		//check book exists for this school
		$this->check_school_data_exists(school_id, $book_id, 'id', 'school_library', 'admin');	
		$this->form_validation->set_rules('email', 'Staff\'s Email', 'trim|required');
		$this->form_validation->set_rules('copies_borrowed', 'Copies Borrowed', 'trim|required|is_natural');
		$this->form_validation->set_rules('date_due', 'Date Due', 'trim|required');

		if ($this->form_validation->run())  {	
		
			$y = $this->school_library_model->get_book_details_by_id($book_id);
			$copies_available = $y->copies_available;
			$copies_borrowed = $this->input->post('copies_borrowed', TRUE);
			$email = strtolower($this->input->post('email', TRUE));
			
			//ensure staff email exists
			$q_staff = $this->common_model->get_staff_details($email);
			
			if ( ! $q_staff ) { //staff doesn't exist
				$this->session->set_flashdata('status_msg_error', 'No staff with the specified email address was found.');

			} elseif ($q_staff && $q_staff->school_id != school_id) { //staff exists in table but not registered under this school
				$this->session->set_flashdata('status_msg_error', 'The staff with the specified email is not registered under this school.');
				
			} else { //all is well, everyone is happy
			
				//ensure copies borrowed is not greater than copies available
				if ($copies_borrowed <= $copies_available) {
					//ensure date due is not in the past
					$valid_date_due = $this->school_library_model->check_date_due_not_past($book_id);
					if ($valid_date_due) {
						//lend the book
						$this->school_library_model->lend_book_staff($book_id);
						$borrower = $this->common_model->get_staff_details($email)->name;
						$s_copies = ($copies_borrowed == 1) ? 'copy' : 'copies';
						$this->session->set_flashdata('status_msg', "{$copies_borrowed} {$s_copies} of {$y->book_name} lent to {$borrower} successfully.");
					} else {
						$this->session->set_flashdata('status_msg_error', 'Date due cannot be in the past.');
					}
				} else {
					$this->session->set_flashdata('status_msg_error', 'Copies to be lent cannot exceed available copies.');
				}
			
			}
			
		} else {
			$this->session->set_flashdata('status_msg_error', 'Error lending book to staff.');
		}
		redirect($this->agent->referrer());
	}
	
	
	public function delete_book($book_id) { 
		//check book exists for this school
		$this->check_school_data_exists(school_id, $book_id, 'id', 'school_library', 'admin');	
		$y = $this->school_library_model->get_book_details_by_id($book_id);
		$copies_available = $y->copies_available;
		$total_copies = $y->total_copies;
		//ensure no copy of book is borrowed output
		if ($copies_available == $total_copies) { //book is not borrowed out
			$this->school_library_model->delete_book($book_id);
			$this->session->set_flashdata('status_msg', 'Book deleted successfully.');
		} else { //at least 1 copy was borrowed
			$this->session->set_flashdata('status_msg_error', 'Some copies of this book were lent out. Please recover copies before deleting book.');
		}
		redirect($this->agent->referrer());
	}
	
	
	public function bulk_actions_books() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->school_library_model->bulk_actions_books();
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}
	
	
	
	
	
	/* =================== Borrowed Books =========================*/
	public function borrowed_books() {
		$total_borrowed_copies = $this->school_library_model->get_borrowed_books_copies();
		$inner_page_title = 'Borrowed Books (' . $total_borrowed_copies . ')'; 
		$this->admin_header('Borrowed Books', $inner_page_title);	
		$total_unique_books = count($this->school_library_model->get_all_books());
		$total_books_copies = $this->school_library_model->get_books_copies();
		$total_available_copies = $this->school_library_model->get_available_books_copies();
		$data['total_unique_books'] = $total_unique_books;
		$data['total_books_copies'] = $total_books_copies;
		$data['total_available_copies'] = $total_available_copies;
		$data['total_borrowed_copies'] = $total_borrowed_copies;
		$data['total_borrowers'] = count($this->school_library_model->get_all_borrowers());
		$data['due_books'] = $this->school_library_model->get_due_books();
		$data['total_due_books'] = $this->school_library_model->get_total_due_books();
		$this->load->view('admin/school_library/borrowed_books', $data);
        $this->admin_footer();
	}
	
	
	public function borrowed_books_ajax() {
		$this->load->model('ajax/admin/school_library/all_borrowed_books_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$book_id = $y->book_id;
			$book_details = $this->school_library_model->get_book_details_by_id($book_id);
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $book_details->book_name . $this->school_library_model->flag_book_due($y->id) . $this->school_library_model->flag_book_overdue($y->id);
			$row[] = $this->current_model->book_details($y->id);
			$row[] = $y->borrower; //Student or Staff
			$row[] = $this->current_model->borrower_name($y->id);
			$row[] = $this->current_model->borrower_identification($y->id);
			$row[] = $this->current_model->borrower_designation($y->id);
			$row[] = $y->copies_borrowed;
			$row[] = x_date($y->date_borrowed);
			$row[] = $this->school_library_model->get_date_due($y->id);
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records(),
			"recordsFiltered" => $this->current_model->count_filtered_records(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	
	public function return_book($bb_id) { 
		//check book exists for this school
		$this->check_school_data_exists(school_id, $bb_id, 'id', 'borrowed_books', 'admin');	
		$y = $this->school_library_model->get_borrowed_book_details($bb_id);
		$book_id = $y->book_id;
		$book_name = $this->school_library_model->get_book_details_by_id($book_id)->book_name;
		$copies_borrowed = $y->copies_borrowed;
		$this->school_library_model->return_book($bb_id);
		$s_copies = ($copies_returned == 1) ? 'copy' : 'copies';
		$this->session->set_flashdata('status_msg', "All {$copies_borrowed} {$s_copies} of {$book_name} returned successfully.");
		redirect($this->agent->referrer());
	}
	
	
	public function return_book_partial_copies($bb_id) { 
		$this->form_validation->set_rules('copies_returned', 'Copies Returned', 'trim|required');
		$copies_returned = $this->input->post('copies_returned', TRUE);
		$y = $this->school_library_model->get_borrowed_book_details($bb_id);
		$book_id = $y->book_id;
		$book_name = $this->school_library_model->get_book_details_by_id($book_id)->book_name;
		$copies_borrowed = $y->copies_borrowed;
		if ($this->form_validation->run())  {
			//check if all copies borrowed were returned
			if ($copies_returned == $copies_borrowed) { //borrower is returning all copies borrowed
				$this->school_library_model->return_book($bb_id);
				$this->session->set_flashdata('status_msg', "All {$copies_borrowed} copies of {$book_name} returned successfully.");
			} else {
				$s_copies = ($copies_returned == 1) ? 'copy' : 'copies';
				$this->school_library_model->return_book_partial_copies($bb_id);
				$this->session->set_flashdata('status_msg', "{$copies_returned} {$s_copies} of {$book_name} returned successfully.");
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Error marking book as returned.');
		}
		redirect($this->agent->referrer());
	}
	
	
	public function message_staff($bb_id) { 
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		$y = $this->school_library_model->get_borrowed_book_details($bb_id);
		$borrower_id = $y->borrower_id;
		$staff_name = $this->common_model->get_staff_details_by_id($borrower_id);
		if ($this->form_validation->run())  {		
			$this->school_library_model->message_staff($bb_id);
			$this->session->set_flashdata('status_msg', "Message successfully sent to {$staff_name}.");
		} else {
			$this->session->set_flashdata('status_msg_error', 'Error sending message to staff.');
		}
		redirect($this->agent->referrer());
	}
	
	
	


	
}