<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Weekly_reports_staff
Role: Controller
Description: Weekly_reports_staff Class controls access to all Procurement Requisition pages and functions from the admin's end
Model: weekly_reports_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class Weekly_reports_staff extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->staff_restricted(); //allow only logged in users to access this class
		$this->load->model('weekly_reports_model');
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
		//get school id
		$this->school_id = $this->staff_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_staff(school_id, mod_weekly_reports); //weekly reports module
		$this->activation_restricted_staff(school_id); 

		//module-level scripts
		$this->staff_module_scripts = array('s_weekly_reports');
	}



	/* ========== Select Archived Reports ========== */
	public function select_archive_weekly_reports() { 
		$this->form_validation->set_rules('session', 'Session', 'trim|required');
		$this->form_validation->set_rules('term', 'Term', 'trim|required');
		$session = $this->input->post('session', TRUE);
		$term = $this->input->post('term', TRUE);
		if ($this->form_validation->run())  {
			//redirect to the collect fees page of the selected class
			redirect(site_url('weekly_reports_staff/weekly_reports/'.$session.'/'.$term));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}
	

	
	public function weekly_reports($session, $term) {
		$this->load->model('ajax/staff/weekly_reports/weekly_reports_model_ajax', 'current_model');
		$inner_page_title = 'Staff Reports (' . $this->current_model->count_all_records($session, $term). ')'; 
		$this->staff_header('Staff Reports', $inner_page_title);	
		$data['session'] = $session;
		$data['the_session'] = get_the_session($session);
		$data['term'] = $term;
		$this->load->view('staff/weekly_reports/weekly_reports', $data);
        $this->staff_footer();
	}
	
	
	public function weekly_reports_ajax($session, $term) {
		$this->load->model('ajax/staff/weekly_reports/weekly_reports_model_ajax', 'current_model');
		$list = $this->current_model->get_records($session, $term);
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->ref_id; 
			$row[] = 'You'; 
			$row[] = $y->week; 
			$row[] = $y->starting_date . ' to ' . $y->ending_date; 
			$row[] = $this->current_model->report_type($y->id);
			$row[] = x_date($y->date_submitted);
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
	
	
	public function submit_weekly_report($error = array('error' => '')) { //for current term
		$this->staff_header('Submit Report', 'Submit Report');
		$data['upload_error'] = $error;
		$data['weekly_report_types'] = $this->weekly_reports_model->get_report_types();
		$this->load->view('staff/weekly_reports/submit_weekly_report', $data);
		$this->staff_footer();
	}
	
	
	public function submit_weekly_report_action() {	//for current term
		$this->form_validation->set_rules('week', 'Week', 'trim|required');
		$this->form_validation->set_rules('starting_date', 'Starting Date', 'trim|required');
		$this->form_validation->set_rules('ending_date', 'Ending Date', 'trim|required');
		$this->form_validation->set_rules('report_type_id', 'Report Type', 'trim|required');
		
		//config for file uploads
        $config['upload_path']          = './assets/uploads/weekly_reports'; //path to save the files
        $config['allowed_types']        = 'pdf|PDF|doc|docx|xls|xlsx';  //extensions which are allowed
        $config['max_size']             = 1024 * 5; //filesize cannot exceed 5MB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
		
		$this->load->library('upload', $config);

		$session = current_session_slug;
		$term = current_term;
		
		if ($this->form_validation->run())  {	
			
			if ( $_FILES['report']['name'] == "" ) { //file is not selected
				$this->session->set_flashdata('status_msg_error', 'No file selected.');
				redirect(site_url('weekly_reports_staff/weekly_reports/'.$session.'/'.$term)); 
				
			} elseif ( ( ! $this->upload->do_upload('report')) && ($_FILES['report']['name'] != "") ) { //upload does not happen when file is selected
				$error = array('error' => $this->upload->display_errors());
				$this->submit_weekly_report($error); //reload page with upload errors
				
			} else { //file is selected, upload happens, everyone is happy
				$report = $this->upload->data('file_name');
				$submitted_by = $this->staff_details->id;
				$this->weekly_reports_model->submit_weekly_report($report, $submitted_by);
				$this->session->set_flashdata('status_msg', 'Report submitted successfully.');
				redirect(site_url('weekly_reports_staff/weekly_reports/'.$session.'/'.$term)); 
			}
			
		} else { 
			$this->submit_weekly_report(); //validation fails, reload page with validation errors
		}
    }
	
	
	

}