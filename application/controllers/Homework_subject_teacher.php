<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Homework_subject_teacher
Role: Controller
Description: Fees Class controls access to all homework pages and functions from the subject teacher's end
Model: homework_model
Author: Nwankwo Ikemefuna
Date Created: 22nd June, 2018
*/


class Homework_subject_teacher extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->staff_restricted(); //allow only logged in users to access this class
		$this->load->model('homework_model');
		$this->staff_role_restricted('Subject Teacher'); //only staff with this role can access this module
		$this->check_staff_is_subject_teacher(); //allow only subject teachers who are currently assigned to at least class and subject to access this module
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
		//get school id
		$this->school_id = $this->staff_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_staff(school_id, mod_homework); //student management module
		$this->activation_restricted_staff(school_id);

		//module-level scripts
		$this->staff_module_scripts = array(); 
	}




	public function select_class_new_homework() { //select class in modal in header
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$class_id = $this->input->post('class_id', TRUE);
		if ($this->form_validation->run())  {
			//redirect to the homework page of the selected class
			redirect(site_url('homework_subject_teacher/new_homework/'.$class_id));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}


	public function select_class_all_homework() { //select class in modal in header
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$class_id = $this->input->post('class_id', TRUE);
		if ($this->form_validation->run())  {
			//redirect to the homework page of the selected class
			redirect(site_url('homework_subject_teacher/homework/'.$class_id));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}



	public function homework($class_id) {
		$class_details = $this->common_model->get_class_details($class_id);
		$class = $class_details->class;
		$inner_page_title = 'Homework: ' . $class . ' (' . count($this->homework_model->get_homework($class_id)) . ')'; 
		$this->staff_header('Homework: ' . $class, $inner_page_title);	
		//config for pagination
        $config = array();
		$per_page = 5;  //number of items to be displayed per page
        $uri_segment = 4;  //pagination segment id
		$config["base_url"] = base_url('homework_subject_teacher/homework/'.$class_id);
        $config["total_rows"] = count($this->homework_model->get_homework($class_id));
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
		$data["homework"] = $this->homework_model->get_homework_list($class_id, $config["per_page"], $page);
		$data["total_records"] = $config["total_rows"];
		$str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
        $data['class_id'] = $class_id;
		$this->load->view('staff/homework/subject_teacher/homework', $data);
		$this->staff_footer();
	}



    /* ====== View Homework ====== */
	public function view_homework($homework_id) {
		//check class exists in this school
		$this->check_school_data_exists(school_id, $homework_id, 'id', 'homework', 'staff');
		//check class exists in teacher's class
		//$this->restrict_homework($homework_id, $class_id);
		$y = $this->homework_model->get_homework_details($homework_id);	
		$subject = $this->common_model->get_subject_details($y->subject_id)->subject;
		$page_title = 'Homework: ' . $subject;
		$this->staff_header($page_title, $page_title);
		$data['y'] = $y;
		$data['class_id'] = $y->class_id;	
		$data['class'] = $this->common_model->get_class_details($y->class_id)->class;	
		$data['subject'] = $subject;	
		$this->load->view('staff/homework/subject_teacher/view_homework', $data);
        $this->staff_footer();
	}



	/* ====== New Homework ====== */
	public function new_homework($class_id, $error = array('error' => '')) {
		$class_details = $this->common_model->get_class_details($class_id);
		$class = $class_details->class;
		$section_id = $class_details->section_id;
		//check class exists in this school
		$this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
		$this->staff_header('New Homework', 'New Homework');
		$data['class_id'] = $class_id;
		$data['class'] = $class;
		$data['subjects_option'] = $this->common_model->subject_teacher_subjects_option_by_section($this->staff_details->id, $section_id);
		$data['upload_error'] = $error;
		$this->load->view('staff/homework/subject_teacher/new_homework', $data);
        $this->staff_footer();
	}


	public function new_homework_action($class_id) {
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
        $this->form_validation->set_rules('subject_id', 'Subject', 'trim|required');
		$this->form_validation->set_rules('submission_date', 'Date of Submission', 'trim|required');
		$this->form_validation->set_rules('homework', 'Homework', 'trim|required');
		$this->form_validation->set_rules('additional_message', 'Additional Message', 'trim');
		
		//config for file uploads
        $config['upload_path']          = './assets/uploads/homework'; //path to save the files
        $config['allowed_types']        = 'jpg|JPG|jpeg|JPEG|png|PNG|pdf|PDF|doc|docx|zip|ZIP';  //extensions which are allowed
        $config['max_size']             = 1024 * 5; //filesize cannot exceed 5MB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
		
		$this->load->library('upload', $config);

		if ($this->form_validation->run())  {	
			
			if ( $_FILES['material']['name'] == "" ) { //file is not selected
				$material = NULL;
				$this->homework_model->new_homework($material);
				$this->session->set_flashdata('status_msg', 'Homework submitted successfully.');
				redirect(site_url('homework_subject_teacher/homework/'.$class_id));  
				
			} elseif ( ( ! $this->upload->do_upload('material')) && ($_FILES['material']['name'] != "") ) { //upload does not happen when file is selected
				$error = array('error' => $this->upload->display_errors());
				$this->new_homework($class_id, $error); //reload page with upload errors
				
			} else { //file is selected, upload happens, everyone is happy
				$material = $this->upload->data('file_name');
				$this->homework_model->new_homework($material);
				$this->session->set_flashdata('status_msg', 'Homework submitted successfully.');
				redirect(site_url('homework_subject_teacher/homework/'.$class_id)); 
			}
			
		} else { 
			$this->new_homework($class_id); //validation fails, reload page with validation errors
		}
    }




    /* ====== Edit Homework ====== */
	public function edit_homework($homework_id, $error = array('error' => '')) {
		//check class exists in this school
		$this->check_school_data_exists(school_id, $homework_id, 'id', 'homework', 'staff');
		//check class exists in teacher's class
		//$this->restrict_homework($homework_id, $class_id);
		$y = $this->homework_model->get_homework_details($homework_id);	
		$subject = $this->common_model->get_subject_details($y->subject_id)->subject;
		$page_title = 'Edit Homework: ' . $subject;
		$this->staff_header($page_title, $page_title);
		$section_id = $this->common_model->get_class_details($y->class_id)->section_id;	
		$data['y'] = $y;
		$data['class'] = $this->common_model->get_class_details($y->class_id)->class;	
		$data['subject'] = $subject;	
		$data['subjects_option'] = $this->common_model->subjects_option_by_section(school_id, $section_id);
		$data['upload_error'] = $error;
		$this->load->view('staff/homework/subject_teacher/edit_homework', $data);
        $this->staff_footer();
	}


    public function edit_homework_action($homework_id) {
    	//check class exists in this school
		$this->check_school_data_exists(school_id, $homework_id, 'id', 'homework', 'staff');
    	//check class exists in teacher's class
		//$this->restrict_homework($homework_id, $class_id);
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
        $this->form_validation->set_rules('subject_id', 'Subject', 'trim|required');
		$this->form_validation->set_rules('submission_date', 'Date of Submission', 'trim|required');
		$this->form_validation->set_rules('homework', 'Homework', 'trim|required');
		$this->form_validation->set_rules('additional_message', 'Additional Message', 'trim');
		
		//config for file uploads
        $config['upload_path']          = './assets/uploads/homework'; //path to save the files
        $config['allowed_types']        = 'jpg|JPG|jpeg|JPEG|png|PNG|pdf|PDF|doc|docx|zip|ZIP';  //extensions which are allowed
        $config['max_size']             = 1024 * 5; //filesize cannot exceed 5MB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
		
		$this->load->library('upload', $config);

		$y = $this->homework_model->get_homework_details($homework_id);	
		$slug = $this->common_model->get_class_details($y->class_id)->slug;
		$old_material = $y->material;
		
		if ($this->form_validation->run())  {	
			
			if ( $_FILES['material']['name'] == "" ) { //file is not selected
				$material = $old_material;
				$this->homework_model->edit_homework($homework_id, $material);
				$this->session->set_flashdata('status_msg', 'Homework updated successfully.');
				redirect(site_url('homework_subject_teacher/view_homework/'.$homework_id)); 
				
			} elseif ( ( ! $this->upload->do_upload('material')) && ($_FILES['material']['name'] != "") ) { //upload does not happen when file is selected
				$error = array('error' => $this->upload->display_errors());
				$this->edit_homework($homework_id, $error); //reload page with upload errors
				
			} else { //file is selected, upload happens, everyone is happy
				$material = $this->upload->data('file_name');
				//delete old material
				$this->homework_model->delete_homework_material_file($homework_id);
				//update homework
				$this->homework_model->edit_homework($homework_id, $material);
				$this->session->set_flashdata('status_msg', 'Homework updated successfully.');
				redirect(site_url('homework_subject_teacher/view_homework/'.$homework_id)); 
			}
			
		} else { 
			$this->edit_homework($homework_id); //validation fails, reload page with validation errors
		}
    }


     public function delete_homework_material($homework_id) { 
		//check class exists in this school
		$this->check_school_data_exists(school_id, $homework_id, 'id', 'homework', 'staff');
		//check class exists in teacher's class
		//$this->restrict_homework($homework_id, $class_id);
		$this->homework_model->delete_homework_material($homework_id);
		$this->session->set_flashdata('status_msg', 'Homework material deleted successfully.');
		redirect($this->agent->referrer());
	}


   	public function delete_homework($homework_id) { 
		//check class exists in this school
		$this->check_school_data_exists(school_id, $homework_id, 'id', 'homework', 'staff');
		//check class exists in teacher's class
		//$this->restrict_homework($homework_id, $class_id);
		$this->homework_model->delete_homework($homework_id);
		$this->session->set_flashdata('status_msg', 'Homework deleted successfully.');
		redirect($this->agent->referrer());
	}

	
	public function bulk_actions_homework() { 
		//check if demo user
		$this->demo_action_restricted_staff();
		
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->homework_model->bulk_actions_homework();
			} else {
				$this->session->set_flashdata('status_msg_error', 'No homework selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}



	private function restrict_homework($homework_id, $class_id) {
		//if homework ID exists, ensure homework is for teacher's class 
		$homework_class_id = $this->homework_model->get_homework_details($homework_id)->class_id;
		$teacher_class = $this->common_model->get_class_details($class_id)->class;
		if ($homework_class_id == $class_id) {
			return TRUE;
		} else {
			$this->session->set_flashdata('status_msg_error', "The homework you tried to access was not created for {$teacher_class}");
			redirect(site_url('homework_subject_teacher/homework')); //redirect to all homeworks page
		}
	}

	
	
	
}