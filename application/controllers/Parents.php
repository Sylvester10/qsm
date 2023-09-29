<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Parents
Role: Controller
Description: Students_admin Class controls access to all parent pages and functions from the admin's end
Model: Parents_model
Author: Nwankwo Ikemefuna
Date Created: 5th September, 2018
*/


class Parents extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->load->model('parents_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_admin(school_id, mod_student_management); //student management module
		$this->activation_restricted_admin(school_id); 

		//module-level scripts
        $this->admin_module_scripts = array('s_parent_management');
	}




    /* ========== All Students ========== */
	public function index() {
		$inner_page_title = 'Parents (' .count($this->common_model->get_parents(school_id)). ')'; 
		$this->admin_header('Parents', $inner_page_title);	
		$this->load->view('admin/parents/all_parents');
        $this->admin_footer();
	}
	
	
	public function all_parents_ajax() {
		$this->load->model('ajax/admin/parents/all_parents_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();
			$row[] = checkbox_bulk_action($y->id);	
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->name;
			$row[] = count($this->common_model->get_parent_children($y->id));
			$row[] = $this->current_model->get_children($y->id);
			$row[] = $y->sex;
			$row[] = $y->relationship;
			$row[] = $y->phone;
			$row[] = $y->email;
			$row[] = $y->sec_parent_name;
			$row[] = $y->sec_parent_sex;
			$row[] = $y->sec_parent_relationship;
			$row[] = $y->sec_parent_phone;
			$row[] = $y->sec_parent_email;	
			$row[] = x_date($y->date_registered);
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



	public function new_parent() {
		$page_title = 'New Parent';
		$this->admin_header($page_title, $page_title);
		$this->load->view('admin/parents/new_parent');
        $this->admin_footer();
	}
	

	public function new_parent_action() { 
		$this->form_validation->set_rules('parent_name', 'Parent Name', 'trim|required');
		$this->form_validation->set_rules('parent_sex', 'Parent Sex', 'trim');
		$this->form_validation->set_rules('parent_relationship', 'Relationship', 'trim');
		$this->form_validation->set_rules('parent_phone', 'Parent Mobile', 'trim');
		$this->form_validation->set_rules('parent_email', 'Parent Email', 'trim|valid_email|callback_check_parent_email_no_conflict');
		$this->form_validation->set_rules('sec_parent_name', 'Second Parent Name', 'trim');
		$this->form_validation->set_rules('sec_parent_sex', 'Second Parent Sex', 'trim');
		$this->form_validation->set_rules('sec_parent_relationship', 'Second Relationship', 'trim');
		$this->form_validation->set_rules('sec_parent_phone', 'Second Parent Mobile', 'trim');
		$this->form_validation->set_rules('sec_parent_email', 'Second Parent Email', 'trim|valid_email');
		if ($this->form_validation->run())  {			
			$this->parents_model->new_parent();
			$this->session->set_flashdata('status_msg', 'Parent added successfully');
			redirect(site_url('parents/new_parent')); 		
		} else { 
			$this->new_parent(); //validation fails, reload page with validation errors
		}
	}


	public function edit_parent($id) {
		//check parent id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'parents', 'admin');
		$y = $this->common_model->get_parent_details_by_id($id);
		$page_title = 'Edit Parent: '  . $y->name;
		$this->admin_header($page_title, $page_title);
		$data['y'] = $y;
		$data['children'] = $this->common_model->get_parent_children($id);
		$this->load->view('admin/parents/edit_parent', $data);
        $this->admin_footer();
	}
	

	public function edit_parent_action($id) { 
		//check parent id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'parents', 'admin');
		$this->form_validation->set_rules('parent_name', 'Parent Name', 'trim|required');
		$this->form_validation->set_rules('parent_sex', 'Parent Sex', 'trim');
		$this->form_validation->set_rules('parent_relationship', 'Relationship', 'trim');
		$this->form_validation->set_rules('parent_phone', 'Parent Mobile', 'trim');
		$this->form_validation->set_rules('parent_email', 'Parent Email', 'trim|valid_email|callback_check_parent_email_no_conflict');
		$this->form_validation->set_rules('sec_parent_name', 'Second Parent Name', 'trim');
		$this->form_validation->set_rules('sec_parent_sex', 'Second Parent Sex', 'trim');
		$this->form_validation->set_rules('sec_parent_relationship', 'Second Relationship', 'trim');
		$this->form_validation->set_rules('sec_parent_phone', 'Second Parent Mobile', 'trim');
		$this->form_validation->set_rules('sec_parent_email', 'Second Parent Email', 'trim|valid_email');
		
		$y = $this->common_model->get_parent_details_by_id($id);
		if ($this->form_validation->run())  {			
			$this->parents_model->edit_parent($id);
			$this->session->set_flashdata('status_msg', "{$y->name} updated successfully!");
			redirect(site_url('parents/edit_parent/'.$id)); 		
		} else { 
			$this->edit_parent($id); //validation fails, reload page with validation errors
		}
	}


	public function check_parent_email_no_conflict() { //ensure parent email does not conflict with parent email from a different school
		$parent_email = $this->input->post('parent_email', TRUE);
		if ($parent_email != '') {
			$query = $this->common_model->get_parent_details($parent_email);
			if ( ! $query || ($query && $query->school_id == school_id) ) { 
				return TRUE;
			} else {
				$this->form_validation->set_message('check_parent_email_no_conflict', "Parent Email address belongs to a parent from another school.");
				return FALSE;
			}
		} else {
			return TRUE;
		}
	}


	/* ====== Associate Parent  ====== */
	public function associate_student($id) {
		//check parent id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'parents', 'admin');
		$y = $this->common_model->get_parent_details_by_id($id);
		$page_title = 'Associate Student: '  . $y->name;
		$this->admin_header($page_title, $page_title);
		$data['y'] = $y;
		$data['parent_id'] = $y->id;
		$data['children'] = $this->common_model->get_parent_children($id);
		$data['matched_students'] = $this->parents_model->get_matched_students($id);
		$this->load->view('admin/parents/associate_student', $data);
        $this->admin_footer();
	}


	public function associate_student_search_ajax() {
        $keyword = $this->input->post('keyword');
        $data = $this->parents_model->associate_student_search($keyword);       
        echo json_encode($data);
    }


    public function associate_student_action($parent_id, $student_id) { 
		$this->check_school_data_exists(school_id, $parent_id, 'id', 'parents', 'admin');
		$this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'admin');
		$this->parents_model->associate_student($parent_id, $student_id);
		$this->session->set_flashdata('status_msg', 'Student associated to parent successfully.');
		redirect($this->agent->referrer());
	}


	public function message_parent($id) { 
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'parents', 'admin');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		$y = $this->common_model->get_parent_details_by_id($id);
		if ($this->form_validation->run())  {		
			$this->parents_model->message_parent($id);
			$this->session->set_flashdata('status_msg', "Message successfully sent to {$y->name}.");
		} else {
			$this->session->set_flashdata('status_msg_error', 'Error sending message to parent.');
		}
		redirect($this->agent->referrer());
	}


	public function delete_parent($id) { 
		//check if demo user
		$this->demo_action_restricted_admin();
		//check parent id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'parents', 'admin');
		$this->parents_model->delete_parent($id);
		$this->session->set_flashdata('status_msg', 'Parent deleted successfully.');
		redirect($this->agent->referrer());
	}
	
	
	public function bulk_actions_parents() { 
		//check if demo user
		$this->demo_action_restricted_admin();
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->parents_model->bulk_actions_parents(); 
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}




}