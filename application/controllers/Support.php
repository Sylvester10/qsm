<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Support
Role: Controller
Description: Support Class controls access to all videos pages and functions
Model: Support_model
Author: Sylvester Nmakwe
Date Created: 29th August, 2018
*/



class Support extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->super_admin_restricted(); //allow only logged in super admins to access this class
		$this->load->model('support_model');
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);

		//module-level scripts
		$this->super_admin_module_scripts = array('s_super_admin');
	}






	/* ========== Support Videos ========== */
	public function support_videos() { 
		$this->load->model('ajax/super_admin/support/support_videos_model_ajax', 'current_model');
		$inner_page_title = 'Videos (' . $this->current_model->count_all_records() . ')';
		$this->super_admin_header('Videos', $inner_page_title);
		$this->load->view('super_admin/support/videos');
		$this->super_admin_footer();
	}


	public function support_videos_ajax() {
		$this->load->model('ajax/super_admin/support/support_videos_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->title; 
			$row[] = $y->description; 
			$row[] = $y->url;
			$row[] = $y->category;
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



	public function add_new_video_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('url', 'Video Url', 'trim|required|is_unique[support_videos.url]',
			array(
				'is_unique' => 'This video url already exists'
			)
		);
		$this->form_validation->set_rules('category', 'Category', 'trim|required');
		if ($this->form_validation->run())  {		
			$this->support_model->add_new_video();
			echo 1;	
		} else { 
			echo validation_errors();
		}	
	}


	public function edit_video($id) {
		//set validation rules
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('url', 'Video Url', 'trim|required');
		$this->form_validation->set_rules('category', 'Category', 'trim|required');
		if ($this->form_validation->run())  {	
			$this->support_model->edit_video($id);
			$this->session->set_flashdata('status_msg', 'Video updated successfully.');
			redirect(site_url('support/support_videos'));
		} else { 
			$this->support_videos();
		}
	}




	public function delete_video($id) { 
		$this->support_model->delete_video($id);
		$this->session->set_flashdata('status_msg', 'Video deleted successfully.');
		redirect(site_url('support/support_videos'));
	}




	
}