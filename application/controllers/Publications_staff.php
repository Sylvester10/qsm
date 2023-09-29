<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Publication_management
Role: Controller
Description: Publication_management Class controls access to all publications from the staff's end
Model: Publications_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class Publications_staff extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->staff_restricted(); //allow only logged in users to access this class
		$this->staff_role_restricted('Publication Manager'); //only staff with this role can access this module
		$this->load->model('publications_model');
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
		//get school id
		$this->school_id = $this->staff_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_staff(school_id, mod_publication_management); //publication management module
		$this->activation_restricted_staff(school_id); 

		//module-level scripts
		$this->staff_module_scripts = array('s_publications'); 
	}

	
	
	
	/* ====== General Announcement ====== */
	public function general_announcement() {
		$this->staff_header('General Announcement', 'General Announcement');	
		$data['general_announcement'] = $this->publications_model->get_general_announcement();
		$this->load->view('staff/publications/general_announcement', $data);
        $this->staff_footer();
	}


	public function create_general_announcement_ajax() {	
		$this->form_validation->set_rules('announcement', 'Announcement', 'trim|required');
		if ($this->form_validation->run())  {		
			$this->publications_model->create_general_announcement();
			echo 1;	
		} else { 
			echo validation_errors();
		}
    }
	
	
	public function update_general_announcement_ajax() {	
		$this->form_validation->set_rules('announcement', 'Announcement', 'trim|required');
		if ($this->form_validation->run())  {		
			$this->publications_model->update_general_announcement();
			echo 1;	
		} else { 
			echo validation_errors();
		}
    }


    public function publish_general_announcement() { 
		$this->publications_model->publish_general_announcement();
		$this->session->set_flashdata('status_msg', 'Announcement published successfully.');
		redirect($this->agent->referrer());
	}


	public function unpublish_general_announcement() { 
		//check if demo user
		$this->demo_action_restricted_staff();
		$this->publications_model->unpublish_general_announcement();
		$this->session->set_flashdata('status_msg', 'Announcement unpublished successfully.');
		redirect($this->agent->referrer());
	}


	public function delete_general_announcement($id) { 
		//check if demo user
		$this->demo_action_restricted_staff();
		//check announcement exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'general_announcement', 'staff');
		$this->publications_model->delete_general_announcement($id);
		$this->session->set_flashdata('status_msg', 'General announcement deleted successfully.');
		redirect($this->agent->referrer());
	}




    /* ====== Staff Announcement ====== */
	public function staff_announcement() {
		$this->staff_header('Staff Announcement', 'Staff Announcement');	
		$data['staff_announcement'] = $this->publications_model->get_staff_announcement();
		$this->load->view('staff/publications/staff_announcement', $data);
        $this->staff_footer();
	}


	public function create_staff_announcement_ajax() {	
		$this->form_validation->set_rules('announcement', 'Announcement', 'trim|required');
		if ($this->form_validation->run())  {		
			$this->publications_model->create_staff_announcement();
			echo 1;	
		} else { 
			echo validation_errors();
		}
    }
	
	
	public function update_staff_announcement_ajax() {	
		$this->form_validation->set_rules('announcement', 'Announcement', 'trim|required');
		if ($this->form_validation->run())  {		
			$this->publications_model->update_staff_announcement();
			echo 1;	
		} else { 
			echo validation_errors();
		}
    }


    public function publish_staff_announcement() { 
		$this->publications_model->publish_staff_announcement();
		$this->session->set_flashdata('status_msg', 'Announcement published successfully.');
		redirect($this->agent->referrer());
	}


	public function unpublish_staff_announcement() { 
		//check if demo user
		$this->demo_action_restricted_staff();
		$this->publications_model->unpublish_staff_announcement();
		$this->session->set_flashdata('status_msg', 'Announcement unpublished successfully.');
		redirect($this->agent->referrer());
	}


	public function delete_staff_announcement($id) { 
		//check if demo user
		$this->demo_action_restricted_staff();
		//check announcement exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'staff_announcement', 'staff');
		$this->publications_model->delete_staff_announcement($id);
		$this->session->set_flashdata('status_msg', 'Staff announcement deleted successfully.');
		redirect($this->agent->referrer());
	}
	
	
	
	
	
	/* ====== News ====== */
	public function news() {
		$inner_page_title = 'News (' . $this->publications_model->count_news() . ')';
		$this->staff_header('News', $inner_page_title);
		//config for pagination
        $config = array();
		$per_page = 5;  //number of items to be displayed per page
        $uri_segment = 3;  //pagination segment id
		$config["base_url"] = base_url('publications_staff/news');
        $config["total_rows"] = $this->publications_model->count_news();
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
		$data["news"] = $this->publications_model->get_news($config["per_page"], $page);
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
		$data['total_records'] = $this->publications_model->count_news();
		$data['total_published'] = $this->publications_model->count_published_news();
		$data['total_unpublished'] = $this->publications_model->count_unpublished_news();
		$this->load->view('staff/publications/news', $data);
		$this->staff_footer();
	}
	
	
	public function single_news($id, $slug) {
		//check post id and slug exist for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'news', 'staff');
		$this->check_school_data_exists(school_id, $slug, 'slug', 'news', 'staff');
		$news_details = $this->publications_model->get_news_details($id);
		$title = $news_details->title;
		$this->staff_header($title, $title);
		$data['y'] = $news_details;
		$this->load->view('staff/publications/single_news', $data);
		$this->staff_footer();
	}
	
	
	public function create_news($error = array('error' => '')) { 
		$this->staff_header('Create News', 'Create News');
		$this->load->view('staff/publications/create_news', $error);
		$this->staff_footer();
	}
	
	
	public function create_news_action() {	
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[120]');
		$this->form_validation->set_rules('body', 'Body', 'trim|required');
        
		//config for file uploads
        $config['upload_path']          = './assets/uploads/news'; //path to save the files
        $config['allowed_types']        = 'jpg|JPG|jpeg|JPEG|png|PNG';  //extensions which are allowed
        $config['max_size']             = 1024 * 2; //filesize cannot exceed 2MB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
		
		$this->load->library('upload', $config);
		
		if ($this->form_validation->run())  {	
			
			if ( $_FILES['featured_image']['name'] == "" ) { //file is not selected
				$this->session->set_flashdata('status_msg_error', 'No file selected.');
				redirect(site_url('publications_staff/news'));
				
			} elseif ( ( ! $this->upload->do_upload('featured_image')) && ($_FILES['featured_image']['name'] != "") ) { 	
				//upload does not happen when file is selected
				$error = array('error' => $this->upload->display_errors());
				$this->create_news($error); //reload page with upload errors
				
			} else { //file is selected, upload happens, everyone is happy
				$posted_by = 'Staff';
				$poster_id = $this->staff_details->id;
				$this->publications_model->create_news($posted_by, $poster_id);
				$this->session->set_flashdata('status_msg', 'News article created and published successfully.');
				redirect(site_url('publications_staff/news')); 
			}
			
		} else { 
			$this->create_news(); //validation fails, reload page with validation errors
		}
    }
	
	
	public function edit_news($id, $error = array('error' => '')) { 
		//check post id exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'news', 'staff');
		$this->staff_header('Edit News', 'Edit News');
		$data['y'] = $this->publications_model->get_news_details($id);	
		$data['upload_error'] = $error;
		$this->load->view('staff/publications/edit_news', $data);
		$this->staff_footer();
	}
	
	
	public function edit_news_action($id, $error = array('error' => '')) {	
		//check post id exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'news', 'staff');
		$this->form_validation->set_rules('title', 'Title', 'trim|required|max_length[120]');
		$this->form_validation->set_rules('body', 'Body', 'trim|required');
        
		//config for file uploads
        $config['upload_path']          = './assets/uploads/news'; //path to save the files
        $config['allowed_types']        = 'jpg|JPG|jpeg|JPEG|png|PNG';  //extensions which are allowed
        $config['max_size']             = 1024 * 2; //filesize cannot exceed 2MB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
		
		$this->load->library('upload', $config);
		
		$y = $this->publications_model->get_news_details($id);	
		if ($this->form_validation->run())  {	
			
			if ( $_FILES['featured_image']['name'] == "" ) { //file is not selected
			
				$featured_image = $y->featured_image; //old featured image
				$thumbnail = $y->featured_image_thumb; //old thumbnail
				$this->publications_model->edit_news($id, $featured_image, $thumbnail);
				$this->session->set_flashdata('status_msg', 'News article updated successfully.');
				redirect(site_url('publications_staff/edit_news/'.$id)); 
				
			} elseif ( ( ! $this->upload->do_upload('featured_image')) && ($_FILES['featured_image']['name'] != "") ) { 	
				//upload does not happen when file is selected
				$error = array('error' => $this->upload->display_errors());
				$this->edit_news($id, $error); //reload page with upload errors
				
			} else { //file is selected, upload happens, everyone is happy
				
				//delete old featured image and thumbnail from server
				$this->publications_model->delete_news_featured_image($id);
				
				$featured_image = $this->upload->data('file_name');
				//generate thumbnail of the image with dimension 85x75
				$thumbnail = generate_image_thumb($featured_image, '85', '75');		
				$this->publications_model->edit_news($id, $featured_image, $thumbnail);
				$this->session->set_flashdata('status_msg', 'News article updated successfully.');
				redirect(site_url('publications_staff/edit_news/'.$id)); 
			}
			
		} else { 
			$this->edit_news($id, $error); //validation fails, reload page with validation errors
		}
    }
	
	
	public function publish_news($id) { 
		//check post id exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'news', 'staff');
		$this->publications_model->publish_news($id);
		$this->session->set_flashdata('status_msg', 'News article published successfully.');
		redirect(site_url('publications_staff/news'));
	}
	
	
	public function unpublish_news($id) { 
		//check post id exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'news', 'staff');
		$this->publications_model->unpublish_news($id);
		$this->session->set_flashdata('status_msg', 'News article unpublished successfully.');
		redirect(site_url('publications_staff/news'));
	}
	
	
	public function delete_news($id) { 
		//check post id exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'news', 'staff');
		$this->publications_model->delete_news($id);
		$this->session->set_flashdata('status_msg', 'News article deleted successfully.');
		redirect(site_url('publications_staff/news'));
	}
	
	
	public function bulk_actions_news() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->publications_model->bulk_actions_news();
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect(site_url('publications_staff/news'));
	}
	
	
	

	
	/* ====== Newsletters ====== */
	public function newsletters() {
		$inner_page_title = 'News (' . $this->publications_model->count_newsletters() . ')';
		$this->staff_header('Newsletters', $inner_page_title);	
		//config for pagination
        $config = array();
		$per_page = 5;  //number of items to be displayed per page
        $uri_segment = 3;  //pagination segment id
		$config["base_url"] = base_url('publications_staff/newsletters');
        $config["total_rows"] = $this->publications_model->count_newsletters();
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
		$data["newsletters"] = $this->publications_model->get_newsletters($config["per_page"], $page);
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
		$data['total_records'] = $this->publications_model->count_newsletters();
		$data['total_published'] = $this->publications_model->count_published_newsletters();
		$data['total_unpublished'] = $this->publications_model->count_unpublished_newsletters();
		$this->load->view('staff/publications/newsletters', $data);
		$this->staff_footer();
	}
	
	
	public function create_newsletter($error = array('error' => '')) { 
		$this->staff_header('Create Newsletter', 'Create Newsletter');
		$this->load->view('staff/publications/create_newsletter', $error);
		$this->staff_footer();
	}
	
	
	public function create_newsletter_action() {	
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
        
		//config for file uploads
        $config['upload_path']          = './assets/uploads/newsletters'; //path to save the files
        $config['allowed_types']        = 'pdf|PDF';  //extensions which are allowed
        $config['max_size']             = 1024 * 5; //filesize cannot exceed 5MB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
		
		$this->load->library('upload', $config);
		
		if ($this->form_validation->run())  {	
			
			if ( $_FILES['the_file']['name'] == "" ) { //file is not selected
				$this->session->set_flashdata('status_msg_error', 'No file selected.');
				redirect(site_url('publications_staff/newsletters'));
				
			} elseif ( ( ! $this->upload->do_upload('the_file')) && ($_FILES['the_file']['name'] != "") ) { 	
				//upload does not happen when file is selected
				$error = array('error' => $this->upload->display_errors());
				$this->create_newsletter($error); //reload page with upload errors
				
			} else { //file is selected, upload happens, everyone is happy
				$the_file_name = $this->upload->data('file_name');
				$posted_by = 'Staff';
				$poster_id = $this->staff_details->id;
				$this->publications_model->create_newsletter($the_file_name, $posted_by, $poster_id);
				//check which button was clicked
				$submit_type = $this->input->post('submit_type', TRUE);
				$published = ($submit_type == 'create_publish') ? 'created and published' : 'created'; 	
				$this->session->set_flashdata('status_msg', "Newsletter {$published} successfully.");
				redirect(site_url('publications_staff/newsletters')); 
			}
			
		} else { 
			$this->create_newsletter(); //validation fails, reload page with validation errors
		}
    }
	
	
	public function publish_newsletter($id) { 
		//check newsletter exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'newsletters', 'staff');
		$this->publications_model->publish_newsletter($id);
		$this->session->set_flashdata('status_msg', 'Newsletter published successfully.');
		redirect(site_url('publications_staff/newsletters'));
	}
	
	
	public function unpublish_newsletter($id) { 
		//check newsletter exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'newsletters', 'staff');
		$this->publications_model->unpublish_newsletter($id);
		$this->session->set_flashdata('status_msg', 'Newsletter unpublished successfully.');
		redirect(site_url('publications_staff/newsletters'));
	}
	
	
	public function delete_newsletter($id) { 
		//check newsletter exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'newsletters', 'staff');
		$this->publications_model->delete_newsletter($id);
		$this->session->set_flashdata('status_msg', 'Newsletter deleted successfully.');
		redirect(site_url('publications_staff/newsletters'));
	}
	
	
	public function bulk_actions_newsletters() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->publications_model->bulk_actions_newsletters();
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect(site_url('publications_staff/newsletters'));
	}
	
	
	
	
	/* ====== Term Dates ====== */
	public function term_dates() {
		$inner_page_title = 'Term Dates (' . count($this->publications_model->get_term_dates()). ')'; 
		$this->staff_header('Term Dates', $inner_page_title);
		$data['term_dates'] = $this->publications_model->get_term_dates();
		$data['total_records'] = $this->publications_model->count_term_dates();
		$data['total_published'] = $this->publications_model->count_published_term_dates();
		$data['total_unpublished'] = $this->publications_model->count_unpublished_term_dates();
		$this->load->view('staff/publications/term_dates', $data);
        $this->staff_footer();
	}
	
	
	public function term_dates_ajax() {
		$this->load->model('ajax/staff/publications/term_dates_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->activity; 
			$row[] = $this->current_model->check_term_date($y->id);
			$row[] = $this->current_model->published_status($y->id);
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


	public function term_dates_keep_for_student() {
		$inner_page_title = 'Term Dates (' . $this->publications_model->count_term_dates() . ')'; 
		$this->staff_header('Term Dates', $inner_page_title);
		//config for pagination
        $config = array();
		$per_page = 5;  //number of items to be displayed per page
        $uri_segment = 3;  //pagination segment id
		$config["base_url"] = base_url('publications_staff/term_dates');
        $config["total_rows"] = $this->publications_model->count_term_dates();
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
		$data["term_dates"] = $this->publications_model->get_term_dates($config["per_page"], $page);
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
		$data['total_records'] = $this->publications_model->count_term_dates();
		$data['total_published'] = $this->publications_model->count_published_term_dates();
		$data['total_unpublished'] = $this->publications_model->count_unpublished_term_dates();
		$this->load->view('staff/publications/term_dates', $data);
		$this->staff_footer();
	}

	
	
	public function create_term_date_ajax() { 
		$this->form_validation->set_rules('activity', 'Activity', 'trim|required');
		$this->form_validation->set_rules('term_date', 'Date', 'trim|required');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim');
		if ($this->form_validation->run())  {		
			$this->publications_model->create_term_date();
			echo 1;
		} else {
			echo validation_errors();
		}
	}
	
	
	public function update_term_date($id) { 
		//check term date exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'term_dates', 'staff');
		$this->form_validation->set_rules('activity', 'Activity', 'trim|required');
		$this->form_validation->set_rules('term_date', 'Date', 'trim|required');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim');
		if ($this->form_validation->run())  {		
			$this->publications_model->update_term_date($id);
			$this->session->set_flashdata('status_msg', 'Term Date updated successfully.');
			redirect(site_url('publications_staff/term_dates'));
		} else {
			$this->term_dates(); //reload page with validation errors
		}
	}
	
	
	public function publish_term_date($id) { 
		//check term date exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'term_dates', 'staff');
		$this->publications_model->publish_term_date($id);
		$this->session->set_flashdata('status_msg', 'Term Date published successfully.');
		redirect(site_url('publications_staff/term_dates'));
	}
	
	
	public function unpublish_term_date($id) { 
		//check term date exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'term_dates', 'staff');
		$this->publications_model->unpublish_term_date($id);
		$this->session->set_flashdata('status_msg', 'Term Date unpublished successfully.');
		redirect(site_url('publications_staff/term_dates'));
	}
	
	
	public function delete_term_date($id) { 
		//check term date exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'term_dates', 'staff');
		$this->publications_model->delete_term_date($id);
		$this->session->set_flashdata('status_msg', 'Term Date deleted successfully.');
		redirect(site_url('publications_staff/term_dates'));
	}
	
	
	public function bulk_actions_term_dates() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->publications_model->bulk_actions_term_dates();
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect(site_url('publications_staff/term_dates'));
	}
	
	
	public function clear_term_dates() { 
		//check if demo user
		$this->demo_action_restricted_staff();
		$this->publications_model->clear_term_dates();
		$this->session->set_flashdata('status_msg', 'Term Dates cleared successfully.');
		redirect(site_url('publications_staff/term_dates'));
	}
	
	
	
	
	/* ====== Calendar Dates ====== */
	public function calendar_dates() {
		$inner_page_title = 'Events Calendar (' . count($this->publications_model->get_calendar_events()) . ')'; 
		$this->staff_header('Events Calendar', $inner_page_title);
		
		// Create template of preferences
		$prefs['template'] = custom_calendar_template(); //the custom calendar template
		$prefs['show_next_prev'] = true; //show next and previous links
		$prefs['next_prev_url'] = base_url('publications_staff/calendar_dates'); //url for calendar pagination
		$prefs['month_type'] = 'long'; //full month name
		$prefs['day_type'] = 'short'; //3-letter day type
		$prefs['start_day'] = 'sunday'; //start calendar on sunday
		$prefs['show_other_days'] = FALSE; //Do not display days of other months that share the first or last week of the calendar month.
		
		//load calendar library with preferences
		$this->load->library('calendar', $prefs);
		
		if ($this->uri->segment(4)) { 
			$year = $this->uri->segment(3); //year URI segment
			$month = $this->uri->segment(4); //month URI segment
		} else { //first page, load current year and date
			$year = date("Y", time()); //full year eg 2018
			$month = date("m", time()); //numeric month eg 04 for April
		}
		$events = $this->calendar_events($month, $year); //get events
		$data['calendar'] = $this->calendar->generate($year, $month, $events); //generate calendar with events
		$this->load->view('staff/publications/calendar_dates', $data);
		$this->staff_footer();
	}
	
	
	public function calendar_events($month, $year) {
		//load calendar events
		$the_events = $this->publications_model->get_calendar_events();
		//create an associative array to hold the events
		$data = array();
		
		foreach ($the_events as $y) {

			//VERY IMPORTANT! 
			//Check if day is less than 10 and remove the leading 0 if true. This is because CI calendar library renders days from 1 to 9 as 1 digit, while the days are saved as 2 digits in db.
			$y->day = ($y->day < 10) ? substr($y->day, 1) : $y->day; //strip off the 1st xter i.e. 0
			$date = $y->year .'-'. $y->month .'-'. $y->day;
			//check that date is on current month and year (this is necessary to avoid duplicating same event date on all the months)
			if ($month == $y->month && $year == $y->year) {
				//day = 'link to event on this day'
				$data[$y->day] = 	'<div class="content" data-toggle="modal" data-target="#event' .$date. '">'
										. $y->day . 
									'</div>'
									. $this->publications_model->modal_calendar_event_content($y->day, $y->month, $y->year, $y->date_unix); //show details in a modal window
			} 
		}
		return $data;
	}
	
	
	public function calendar_dates_list() {
		$inner_page_title = 'Events Calendar List (' . count($this->publications_model->get_calendar_events()) . ')'; 
		$this->staff_header('Events Calendar List', $inner_page_title);
		//config for pagination
        $config = array();
		$per_page = 5;  //number of items to be displayed per page
        $uri_segment = 3;  //pagination segment id: publications_staff/calendar_dates_list/pagination_id
		$config["base_url"] = base_url('publications_staff/calendar_dates_list');
        $config["total_rows"] = count($this->publications_model->get_calendar_events());
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
		$data["calendar_list"] = $this->publications_model->get_calendar_events_list($config["per_page"], $page);
		$data["total_records"] = $config["total_rows"];
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
		$this->load->view('staff/publications/calendar_dates_list', $data);
		$this->staff_footer();
	}
	
	
	public function create_calendar_date_ajax() { 
		$this->form_validation->set_rules('calendar_date', 'Date', 'required');
		$this->form_validation->set_rules('caption', 'Event Caption', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		if ($this->form_validation->run())  {		
			$this->publications_model->create_calendar_date();
			echo 1;
		} else {
			echo validation_errors();
		}
	}
	
	
	public function edit_calendar_date($id) { 
		//check event exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'calendar_dates', 'staff');
		$this->form_validation->set_rules('calendar_date', 'Date', 'required');
		$this->form_validation->set_rules('caption', 'Event Caption', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		if ($this->form_validation->run())  {		
			$this->publications_model->edit_calendar_date($id);
			$this->session->set_flashdata('status_msg', 'Calendar event updated successfully.');
			redirect($this->agent->referrer());
		} else {
			$this->calendar_dates_list(); //reload page with validation errors
		}
	}
	
	
	public function delete_calendar_date($id) { 
		//check event exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'calendar_dates', 'staff');
		$this->publications_model->delete_calendar_date($id);
		$this->session->set_flashdata('status_msg', 'Calendar event deleted successfully.');
		redirect($this->agent->referrer());
	}


	public function bulk_actions_calendar_dates() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->publications_model->bulk_actions_calendar_dates();
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect(site_url('publications_staff/calendar_dates_list'));
	}
	
	
	public function clear_calendar_dates() { 
		//check if demo user
		$this->demo_action_restricted_staff();
		$this->publications_model->clear_calendar_dates();
		$this->session->set_flashdata('status_msg', 'Calendar dates cleared successfully.');
		redirect($this->agent->referrer());
	}
	
	
	
	
}