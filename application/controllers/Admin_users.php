<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Admin_users
Role: Controller
Description: Admin_users Class controls access to all admin users pages and functions
Model: Admin_users_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class Admin_users extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->admin_level_1_restricted(); //only admins with level 1 clearance can access this page
		$this->load->model('admin_users_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants

		//module-level scripts
		$this->admin_module_scripts = array('s_admin_users');
	}



	
	public function index() {
		$inner_page_title = 'Admins (' .count($this->common_model->get_admins(school_id)). ')'; 
		$this->admin_header('Admins', $inner_page_title);
		$this->load->view('admin/admin_users/admins');
        $this->admin_footer();
	}
	
	
	public function admins_ajax() {
		$this->load->model('ajax/admin/admin_users/admin_users_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->name . $this->admin_users_model->flag_chief_admin($y->id); 
			$row[] = $y->email; 
			$row[] = $y->phone; 
			$row[] = $y->designation; 
			$row[] = $this->admin_users_model->get_section_assigned($y->id); 
			$row[] = $this->admin_users_model->get_admin_level($y->id); 
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




	/* ========== New Admin ========== */
	public function new_admin() {
		$this->admin_header('New Admin', 'New Admin');
		$data['sections'] = $this->common_model->get_sections(school_id);				
		$this->load->view('admin/admin_users/new_admin', $data);
        $this->admin_footer();
	}
	
	
	public function add_new_admin_ajax() {	
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[admins.email]', 
			array('is_unique' => 'This email address is already registered as an admin.')
		);
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required|is_natural');
		$this->form_validation->set_rules('designation', 'Designation', 'trim|required');
		$this->form_validation->set_rules('level', 'Designation', 'required|is_natural');
		$this->form_validation->set_rules('section_assigned[]', 'Section(s) Assigned', 'trim|required');
        if ($this->form_validation->run())  {		
			$this->admin_users_model->add_new_admin();
			echo 1;	
		} else { 
			echo validation_errors();
		}
    }
	
	
	public function edit_admin($id) {
		//check admin exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'admins', 'admin');
		$admin_details = $this->common_model->get_admin_details_by_id($id);
		$page_title = 'Edit Admin: ' . $admin_details->name; 
		$this->admin_header($page_title, $page_title);
		$data['admin_level'] = $this->admin_users_model->get_admin_level($id);
		$data['y'] = $admin_details;
		$data['sections'] = $this->common_model->get_sections(school_id);
		$data['section_assigned'] = $this->admin_users_model->get_section_assigned($id); 				
		$this->load->view('admin/admin_users/edit_admin', $data);
        $this->admin_footer();
	}
	
	
	public function edit_admin_ajax($id) {	
		//check admin exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'admins', 'admin');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required|is_natural');
		$this->form_validation->set_rules('designation', 'Designation', 'trim|required');
		$this->form_validation->set_rules('level', 'Designation', 'required|is_natural');
        $this->form_validation->set_rules('section_assigned[]', 'Section(s) Assigned', 'trim');
        if ($this->form_validation->run())  {		
			$this->admin_users_model->edit_admin($id);
			echo 1;	
		} else { 
			echo validation_errors();
		}
    }
	
	
	public function message_admin($id) { 
		//check admin exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'admins', 'admin');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		$y = $this->common_model->get_admin_details_by_id($id);
		if ($this->form_validation->run())  {		
			$this->admin_users_model->message_admin($id);
			$this->session->set_flashdata('status_msg', "Message successfully sent to {$y->name}.");
		} else {
			$this->session->set_flashdata('status_msg_error', 'Error sending message to admin.');
		}
		redirect($this->agent->referrer());
	}
	
	
	public function delete_admin($id) { 
		//check if demo user
		$this->demo_action_restricted_admin();
		//check admin exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'admins', 'admin');
		//ensure user is not a correspondence chief admin i.e. admin who created school account
		$this->admin_users_model->check_is_chief_admin($id);
		$this->admin_users_model->delete_admin($id);
		$this->session->set_flashdata('status_msg', 'Admin deleted successfully.');
		redirect($this->agent->referrer());
	}
	
	
	public function delete_bulk_admins() { 
		//check if demo user
		$this->demo_action_restricted_admin();
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->admin_users_model->delete_bulk_admins();
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}

	

	
	
}