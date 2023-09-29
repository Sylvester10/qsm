<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Weekly_reports_admin
Role: Controller
Description: Weekly_reports_admin Class controls access to all Procurement Requisition pages and functions from the admin's end
Model: weekly_reports_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class Weekly_reports_admin extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->admin_level_1_restricted(); //only admins with level 1 clearance can access this module
		$this->load->model('weekly_reports_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_admin(school_id, mod_weekly_reports); //weekly reports module
		$this->activation_restricted_admin(school_id); 

		//module-level scripts
		$this->admin_module_scripts = array('s_weekly_reports');
	}
	
	
	
	
	/* ========== Select Archived Reports ========== */
	public function select_archive_weekly_reports() { 
		$this->form_validation->set_rules('session', 'Session', 'trim|required');
		$this->form_validation->set_rules('term', 'Term', 'trim|required');
		$session = $this->input->post('session', TRUE);
		$term = $this->input->post('term', TRUE);
		if ($this->form_validation->run())  {
			//redirect to the collect fees page of the selected class
			redirect(site_url('weekly_reports_admin/weekly_reports/'.$session.'/'.$term));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}


	public function weekly_reports($session, $term) {
		$this->load->model('ajax/admin/weekly_reports/weekly_reports_model_ajax', 'current_model');
		$inner_page_title = 'Staff Reports (' . $this->current_model->count_all_records($session, $term). ')'; 
		$this->admin_header('Staff Reports', $inner_page_title);	
		$data['session'] = $session;
		$data['the_session'] = get_the_session($session);
		$data['term'] = $term;
		$this->load->view('admin/weekly_reports/weekly_reports', $data);
        $this->admin_footer();
	}
	
	
	public function weekly_reports_ajax($session, $term) {
		$this->load->model('ajax/admin/weekly_reports/weekly_reports_model_ajax', 'current_model');
		$list = $this->current_model->get_records($session, $term);
		$data = array();
		foreach ($list as $y) {
			$reporter_name = $this->common_model->get_staff_details_by_id($y->submitted_by)->name;
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->ref_id; 
			$row[] = $reporter_name;
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


	
	
	public function remark_weekly_report($report_id) { 
		//check report exists for this school
		$this->check_school_data_exists(school_id, $report_id, 'id', 'weekly_reports', 'admin');
		$this->form_validation->set_rules('remark', 'Remark', 'trim|required');
		if ($this->form_validation->run()) {		
			$this->weekly_reports_model->remark_weekly_report($report_id);
			$this->session->set_flashdata('status_msg', 'Report remarked successfully.');
		} else {
			$this->session->set_flashdata('status_msg_error', 'Error remarking report.');
		}
		redirect($this->agent->referrer());
	}
	
	
	public function delete_weekly_report($report_id) { 
		//check report exists for this school
		$this->check_school_data_exists(school_id, $report_id, 'id', 'weekly_reports', 'admin');
		$this->weekly_reports_model->delete_weekly_report($report_id);
		$this->session->set_flashdata('status_msg', 'Report deleted successfully.');
		redirect($this->agent->referrer());
	}
	
	
	public function delete_bulk_weekly_reports() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->weekly_reports_model->delete_bulk_weekly_reports();
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}





	/* ========== Report Types ========== */
	public function weekly_report_types() {
		$inner_page_title = 'Report Types (' . count($this->weekly_reports_model->get_report_types()) . ')'; 
		$this->admin_header('Report Types', $inner_page_title);	
		$data['weekly_report_types'] = $this->weekly_reports_model->get_report_types();
		$data['session'] = current_session_slug;
		$data['term'] = current_term;
		$this->load->view('admin/weekly_reports/weekly_report_types', $data);
        $this->admin_footer();
	}


	public function add_new_report_type_ajax() {	
		$this->form_validation->set_rules('type', 'Report Type', 'trim|required|callback_check_report_type_exists');
        if ($this->form_validation->run())  {	
			$this->weekly_reports_model->add_new_report_type();
			echo 1;
		} else { 
			echo validation_errors();
		}
    }


    public function edit_report_type($report_type_id) {	
    	$this->form_validation->set_rules('type', 'Report Type', 'trim|required');
		$type = ucwords($this->input->post('type', TRUE));
    	$y = $this->weekly_reports_model->get_report_type_details($report_type_id);
		if ($this->form_validation->run())  {	
        	$query = $this->weekly_reports_model->check_report_type_exists($type);
        	//ensure report type does not already exist, or it is same as current type
			if ( $query == 0 || ($query > 0 && $y->type == $type) ) {
				$this->weekly_reports_model->edit_report_type($report_type_id);
				$this->session->set_flashdata('status_msg', 'Report type updated succesfully.');
			} else {
				$this->form_validation->set_message('status_msg_error', "{$type} already exists.");
			}
		} else { 
			$this->session->set_flashdata('status_msg_error', 'Error updating report type.');
		}
		redirect($this->agent->referrer());
    }

	
	public function check_report_type_exists() {
		//callback function to check if report type already exists for the selected section
		$type = $this->input->post('type', TRUE); 
		$query = $this->weekly_reports_model->check_report_type_exists($type);
		if ($query == 0) {
			return TRUE;
		} else {
			$this->form_validation->set_message('check_report_type_exists', "{$type} already exists.");
			return FALSE;
		}
	}


	public function delete_report_type($report_type_id) { 
		//check report exists for this school
		$this->check_school_data_exists(school_id, $report_type_id, 'id', 'weekly_report_types', 'admin');
		$this->weekly_reports_model->delete_report_type($report_type_id);
		$this->session->set_flashdata('status_msg', 'Report type deleted successfully.');
		redirect($this->agent->referrer());
	}

	

	
	
	

}