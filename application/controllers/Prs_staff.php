<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Prs_staff
Role: Controller
Description: Prs_staff Class controls access to all Procurement Requisition System pages and functions from the staff's end
Model: Prs_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class Prs_staff extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->staff_restricted(); //allow only logged in users to access this class
		$this->staff_role_restricted('Academic Administrator'); //only staff with this role can access this module
		$this->load->model('prs_staff_model');
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
		//get school id
		$this->school_id = $this->staff_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_staff(school_id, mod_requisitions); //requisitions module
		$this->activation_restricted_staff(school_id); 

		//module-level scripts
		$this->staff_module_scripts = array('s_prs');
	}



	/* ========== Select Archived Requests ========== */
	public function select_archive_requests() { //select archived data in modal in header
		$this->form_validation->set_rules('session', 'Session', 'trim|required');
		$this->form_validation->set_rules('term', 'Term', 'trim|required');
		$redirect_method = $this->input->post('redirect_method', TRUE);
		$session = $this->input->post('session', TRUE);
		$term = $this->input->post('term', TRUE);
		$class_id = $this->input->post('class_id', TRUE);
		$uri_segment = $redirect_method.'/'.$session.'/'.$term;
		if ($this->form_validation->run())  {
			//redirect to the appropriate page of the selected class
			redirect(site_url('prs_staff/'.$uri_segment));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}
	
	
	
	public function requests($session, $term) { 
		$the_session = get_the_session($session);
		$inner_page_title = "Requests: {$term} Term, {$the_session} Session ({$this->prs_staff_model->count_all_term_requests($session, $term)})"; 
		$this->staff_header('Requests', $inner_page_title);
		$data = $this->prs_staff_model->request_data($session, $term);
		$this->load->view('staff/prs/requests', $data);
		$this->staff_footer();
	}
	
	
	public function requests_ajax($session, $term) {
		$this->load->model('ajax/staff/prs/requests_model_ajax', 'current_model');
		$list = $this->current_model->get_records($session, $term);
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->ref_id;
			$row[] = $this->prs_staff_model->request_info($y->id);
			$row[] = s_currency_symbol . number_format($y->amount_digits, 0);
			$row[] = 'You';
			$row[] = $this->prs_staff_model->account_info($y->id);
			$row[] = $this->prs_staff_model->approval_info($y->id);
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($session, $term),
			"recordsFiltered" => $this->current_model->count_filtered_records($session, $term),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	
	public function pending_requests($session, $term) { 
		$the_session = get_the_session($session);
		$inner_page_title = "Pending Requests: {$term} Term, {$the_session} Session ({$this->prs_staff_model->count_all_pending_term_requests($session, $term)})"; 
		$this->staff_header('Pending Requests', $inner_page_title);
		$data = $this->prs_staff_model->request_data($session, $term);
		$this->load->view('staff/prs/pending_requests', $data);
		$this->staff_footer();
	}
	
	
	public function pending_requests_ajax($session, $term) {
		$this->load->model('ajax/staff/prs/pending_requests_model_ajax', 'current_model');
		$list = $this->current_model->get_records($session, $term);
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->ref_id;
			$row[] = $this->prs_staff_model->request_info($y->id);
			$row[] = s_currency_symbol . number_format($y->amount_digits, 0);
			$row[] = 'You';
			$row[] = $this->prs_staff_model->account_info($y->id);
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($session, $term),
			"recordsFiltered" => $this->current_model->count_filtered_records($session, $term),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}


	public function approved_requests($session, $term) { 
		$the_session = get_the_session($session);
		$inner_page_title = "Approved Requests: {$term} Term, {$the_session} Session ({$this->prs_staff_model->count_all_approved_term_requests($session, $term)})"; 
		$this->staff_header('Approved Requests', $inner_page_title);
		$data = $this->prs_staff_model->request_data($session, $term);
		$this->load->view('staff/prs/approved_requests', $data);
		$this->staff_footer();
	}
	
	
	public function approved_requests_ajax($session, $term) {
		$this->load->model('ajax/staff/prs/approved_requests_model_ajax', 'current_model');
		$list = $this->current_model->get_records($session, $term);
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->ref_id;
			$row[] = $this->prs_staff_model->request_info($y->id);
			$row[] = s_currency_symbol . number_format($y->amount_digits, 0);
			$row[] = 'You';
			$row[] = $this->prs_staff_model->account_info($y->id);
			$row[] = $this->prs_staff_model->approval_info($y->id);
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($session, $term),
			"recordsFiltered" => $this->current_model->count_filtered_records($session, $term),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}


	public function declined_requests($session, $term) { 
		$the_session = get_the_session($session);
		$inner_page_title = "Declined Requests: {$term} Term, {$the_session} Session ({$this->prs_staff_model->count_all_declined_term_requests($session, $term)})"; 
		$this->staff_header('Declined Requests', $inner_page_title);
		$data = $this->prs_staff_model->request_data($session, $term);
		$this->load->view('staff/prs/declined_requests', $data);
		$this->staff_footer();
	}
	
	
	public function declined_requests_ajax($session, $term) {
		$this->load->model('ajax/staff/prs/declined_requests_model_ajax', 'current_model');
		$list = $this->current_model->get_records($session, $term);
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->ref_id;
			$row[] = $this->prs_staff_model->request_info($y->id);
			$row[] = s_currency_symbol . number_format($y->amount_digits, 0);
			$row[] = 'You';
			$row[] = $this->prs_staff_model->account_info($y->id);
			$row[] = $this->prs_staff_model->approval_info($y->id);
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($session, $term),
			"recordsFiltered" => $this->current_model->count_filtered_records($session, $term),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	
	public function initiate_request($error = array('error' => '')) { 
		$this->staff_header('Initiate Request', 'Initiate Request');
		$this->load->view('staff/prs/initiate_request', $error);
		$this->staff_footer();
	}
	
	
	public function initiate_request_action() {	
		$this->form_validation->set_rules('purpose', 'Purpose of request', 'trim|required');
        $this->form_validation->set_rules('items', 'Items of request', 'trim|required');
        $this->form_validation->set_rules('items_info', 'Reason for items', 'trim|required');
        $this->form_validation->set_rules('urgency', 'Urgency', 'trim|required');
        $this->form_validation->set_rules('amount_digits', 'Amount (in digits)', 'trim|required|is_natural');
        $this->form_validation->set_rules('amount_words', 'Amount (in words)', 'trim|required');
        $this->form_validation->set_rules('acc_name', 'Account Name', 'trim|required');
        $this->form_validation->set_rules('acc_number', 'Account Number', 'trim|required|is_natural');
        $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
		
		//config for file uploads
        $config['upload_path']          = './assets/uploads/request_contents'; //path to save the files
        $config['allowed_types']        = 'jpg|JPG|png|PNG|jpeg|JPEG|zip|ZIP|pdf|PDF|doc|docx|xls|xlsx|ppt|pptx';  //extensions which are allowed
        $config['max_size']             = 1024 * 10; //filesize cannot exceed 10MB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
		
		$this->load->library('upload', $config);
		
		if ($this->form_validation->run())  {	
			
			if ( $_FILES['content']['name'] == "" ) { //file is not selected
				$content = NULL;
				$this->prs_staff_model->insert_request($content);
				$this->session->set_flashdata('status_msg', 'Request submitted successfully. Awaiting approval.');
				//redirect to pending requests page of current session and term
				redirect(site_url('prs_staff/pending_requests/'.current_session_slug.'/'.current_term));
				
			} elseif ( ( ! $this->upload->do_upload('content')) && ($_FILES['content']['name'] != "") ) { //upload does not happen when file is selected
				$error = array('error' => $this->upload->display_errors());
				$this->initiate_request($error); //reload page with upload errors
				
			} else { //file is selected, upload happens, everyone is happy
				$content = $this->upload->data('file_name');
				$this->prs_staff_model->insert_request($content);
				$this->session->set_flashdata('status_msg', 'Request submitted successfully. Awaiting approval.');
				//redirect to pending requests page of current session and term
				redirect(site_url('prs_staff/pending_requests/'.current_session_slug.'/'.current_term));
			}
			
		} else { 
			$this->initiate_request(); //validation fails, reload page with validation errors
		}
    }
	
	
	public function edit_request($id, $error = array('error' => '')) { 
		//check request exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'financial_requisitions', 'staff');
		$y = $this->prs_staff_model->get_request_details($id);
		$inner_page_title = 'Edit Request: ' . $y->purpose;
		$this->staff_header('Edit Request', $inner_page_title);
		$data['y'] = $y;
		$data['upload_error'] = $error;
		$this->load->view('staff/prs/edit_request', $data);
		$this->staff_footer();
	}
	
	
	public function edit_request_action($id, $error = array('error' => '')) {	
		//check request exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'financial_requisitions', 'staff');
		$this->form_validation->set_rules('purpose', 'Purpose of request', 'trim|required');
        $this->form_validation->set_rules('items', 'Items of request', 'trim|required');
        $this->form_validation->set_rules('items_info', 'Reason for items', 'trim|required');
        $this->form_validation->set_rules('urgency', 'Urgency', 'trim|required');
        $this->form_validation->set_rules('amount_digits', 'Amount (in digits)', 'trim|required|is_natural');
        $this->form_validation->set_rules('acc_name', 'Account Name', 'trim|required');
        $this->form_validation->set_rules('acc_number', 'Account Number', 'trim|required|is_natural|exact_length[10]');
        $this->form_validation->set_rules('bank_name', 'Bank Name', 'trim|required');
		
		//config for file uploads
        $config['upload_path']          = './assets/uploads/request_contents'; //path to save the files
        $config['allowed_types']        = 'jpg|JPG|png|PNG|jpeg|JPEG|zip|ZIP|pdf|PDF|doc|docx|xls|xlsx|ppt|pptx';  //extensions which are allowed
        $config['max_size']             = 1024 * 10; //filesize cannot exceed 10MB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
		
		$this->load->library('upload', $config);
		
		if ($this->form_validation->run())  {	
			
			$y = $this->prs_staff_model->get_request_details($id);
			$session = $y->session;
			$term = $y->term;
			
			if ( $_FILES['content']['name'] == "" ) { //file is not selected
				
				if ($y->status == 'pending') {
					$content = $y->content; //old content file 
					$this->prs_staff_model->edit_request($id, $content);
					$this->session->set_flashdata('status_msg', 'Request updated successfully. Awaiting approval.');
				} else { //request was actioned (by staff) while it was being edited (by staff)
					$this->session->set_flashdata('status_msg_error', 'This request has been actioned. Your changes were not saved.');
				}
				redirect(site_url('prs_staff/requests/'.$session.'/'.$term));
				
			} elseif ( ( ! $this->upload->do_upload('content')) && ($_FILES['content']['name'] != "") ) { 
				//upload does not happen when file is selected
				$error = array('error' => $this->upload->display_errors());
				$this->edit_request($id, $error); //reload page with upload errors
				
			} else { //file is selected, upload happens, everyone is happy
				
				if ($y->status == 'pending') {
					$this->prs_staff_model->delete_request_file($id); //delete old content file from server
					$content = $this->upload->data('file_name'); //new content file
					$this->prs_staff_model->edit_request($id, $content);
					$this->session->set_flashdata('status_msg', 'Request updated successfully. Awaiting approval.');
				} else { //request was actioned (by admin) while it was being edited (by staff)
					$this->session->set_flashdata('status_msg_error', 'This request has been actioned. Your changes were not saved.');
				}
				redirect(site_url('prs_staff/requests/'.$session.'/'.$term));
			}
			
		} else { 
			$this->edit_request($id); //validation fails, reload page with validation errors
		}
    }
	
	
	public function delete_request_content($id) { 
		//check request exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'financial_requisitions', 'staff');
		$y = $this->prs_staff_model->get_request_details($id);
		if ($y->status == 'pending') {
			$this->prs_staff_model->delete_request_content($id);
			$this->session->set_flashdata('status_msg', 'Request content deleted successfully.');
		} else { //request was actioned (by staff) while it was being edited (by staff)
			$this->session->set_flashdata('status_msg_error', 'This request has been actioned. Deleting content is no longer possible.');
		}
		redirect($this->agent->referrer());
	}
	
	
	
	
	
}