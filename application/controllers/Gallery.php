<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Gallery
Role: Controller
Description: Controls access to gallery pages
Model: Gallery_model
Author: Nwankwo Ikemefuna
Date Created: 23rd of December, 2018
*/


class Gallery extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->super_admin_restricted(); //allow only logged in users to access this class
		$this->load->model('gallery_model');
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);

        //module-level scripts
        $this->super_admin_module_scripts = array();
	}


	public function screenshots() {
		$total_screenshots = $this->gallery_model->count_screenshots();
		$inner_page_title = 'Screenshots (' . $total_screenshots . ')'; 
		$this->super_admin_header('Screenshots', $inner_page_title);
		//config for pagination
        $config = array();
		$per_page = 12;  //number of items to be displayed per page
        $uri_segment = 3;  //pagination segment id: gallery/screenshots/pagination_id
		$config["base_url"] = base_url('gallery/screenshots');
        $config["total_rows"] = $total_screenshots;
        $config["per_page"] = $per_page;
		$config["uri_segment"] = $uri_segment;
		$config['cur_tag_open'] = '<a class="pagination-active-page" href="#!">';	//disable click event of current link
        $config['cur_tag_close'] = '</a>';
        $config['first_link'] = 'First';
        $config['next_link'] = '&raquo;';	// >>
        $config['prev_link'] = '&laquo;';	// <<
		$config['last_link'] = 'Last';
		$config['display_pages'] = TRUE; //show pagination link digits
		$config['num_links'] = 3; //number of digit links
        $this->pagination->initialize($config);
		$page = $this->uri->segment($uri_segment) ? $this->uri->segment($uri_segment) : 0;
		$data["screenshots"] = $this->gallery_model->get_screenshots($config["per_page"], $page);
		$data["total_records"] = $config["total_rows"];
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
        $data['total_records'] = $this->gallery_model->count_screenshots();
        $data['total_published'] = $this->gallery_model->count_published_screenshots();
		$data['total_unpublished'] = $this->gallery_model->count_unpublished_screenshots();
		$this->load->view('super_admin/gallery/screenshots', $data);
		$this->super_admin_footer();
	}


	public function published_screenshots() {
		$total_screenshots = $this->gallery_model->count_published_screenshots();
		$inner_page_title = 'Published Screenshots (' . $total_screenshots . ')'; 
		$this->super_admin_header('Published Screenshots', $inner_page_title);
		//config for pagination
        $config = array();
		$per_page = 12;  //number of items to be displayed per page
        $uri_segment = 3;  //pagination segment id: gallery/published_screenshots/pagination_id
		$config["base_url"] = base_url('gallery/published_screenshots');
        $config["total_rows"] = $total_screenshots;
        $config["per_page"] = $per_page;
		$config["uri_segment"] = $uri_segment;
		$config['cur_tag_open'] = '<a class="pagination-active-page" href="#!">';	//disable click event of current link
        $config['cur_tag_close'] = '</a>';
        $config['first_link'] = 'First';
        $config['next_link'] = '&raquo;';	// >>
        $config['prev_link'] = '&laquo;';	// <<
		$config['last_link'] = 'Last';
		$config['display_pages'] = TRUE; //show pagination link digits
		$config['num_links'] = 3; //number of digit links
        $this->pagination->initialize($config);
		$page = $this->uri->segment($uri_segment) ? $this->uri->segment($uri_segment) : 0;
		$data["screenshots"] = $this->gallery_model->get_published_screenshots($config["per_page"], $page);
		$data["total_records"] = $config["total_rows"];
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
        $data['total_records'] = $this->gallery_model->count_screenshots();
        $data['total_published'] = $this->gallery_model->count_published_screenshots();
		$data['total_unpublished'] = $this->gallery_model->count_unpublished_screenshots();
		$this->load->view('super_admin/gallery/published_screenshots', $data);
		$this->super_admin_footer();
	}




	public function upload_screenshot_ajax() {
   		if ( ! empty($_FILES) ) {

			//config for file uploads
	        $config['upload_path']          = './assets/uploads/screenshots'; //path to save the files
	        $config['allowed_types']        = 'jpg|JPG|jpeg|JPEG|png|PNG';  //extensions which are allowed
	        $config['max_size']             = 1024; //filesize cannot exceed 1MB
	        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
		    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
		    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
			
			$this->load->library('upload', $config);

			// File upload
			if ($this->upload->do_upload('file')) {
				// Get data about the file
				$screenshot = $this->upload->data('file_name');
				$this->gallery_model->upload_screenshot($screenshot);
			}
     	}
   	}


	public function update_screenshot_gallery() {
		$this->session->set_flashdata('status_msg', 'Screenshot gallery updated successfully.');
		redirect($this->agent->referrer());
	}


	public function publish_screenshot($screenshot_id) {
		//check screenshot exists
		$this->check_data_exists($screenshot_id, 'id', 'screenshots', 'gallery/screenshots'); 
		$this->gallery_model->publish_screenshot($screenshot_id);
		$this->session->set_flashdata('status_msg', 'Screenshot published successfully.');
		redirect($this->agent->referrer());
	}
	

	public function unpublish_screenshot($screenshot_id) { 
		//check screenshot exists
		$this->check_data_exists($screenshot_id, 'id', 'screenshots', 'gallery/screenshots');
		$this->gallery_model->unpublish_screenshot($screenshot_id);
		$this->session->set_flashdata('status_msg', 'Screenshot unpublished successfully.');
		redirect($this->agent->referrer());
	}


	public function delete_screenshot($screenshot_id) {
		//check screenshot exists
		$this->check_data_exists($screenshot_id, 'id', 'screenshots', 'gallery/screenshots');
		$this->gallery_model->delete_screenshot($screenshot_id);
		$this->session->set_flashdata('status_msg', 'Screenshot deleted successfully.');
		redirect('gallery/screenshots');
	}


	public function bulk_actions_screenshots() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->gallery_model->bulk_actions_screenshots();
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect('gallery/screenshots');
	}
	





}