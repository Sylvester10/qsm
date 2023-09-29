<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Students_admin
Role: Controller
Description: Students_admin Class controls access to all students pages and functions from the admin's end
Model: Students_admin_model
Author: Nwankwo Ikemefuna
Date Created: 4th June, 2018
*/


class Students_admin extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->load->model('students_admin_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_admin(school_id, mod_student_management); //student management module
		$this->activation_restricted_admin(school_id); 

		//module-level scripts
		$this->admin_module_scripts = array('s_student_management');
	}




    /* ========== All Students ========== */
	public function students() {
		$inner_page_title = 'Students (' .count($this->common_model->get_all_students_list(school_id)). ')'; 
		$this->admin_header('Students', $inner_page_title);	
		$data['total_suspended_students'] = count($this->common_model->get_suspended_students_list(school_id));
		$data['total_revoked_students'] = count($this->common_model->get_revoked_students_list(school_id));
		$data['total_students'] = count($this->common_model->get_all_students_list(school_id));
		$data['sections'] = $this->common_model->get_sections(school_id);
		$this->load->view('admin/students/students', $data);
        $this->admin_footer();
	}
	
	
	public function all_students_ajax() {
		$this->load->model('ajax/admin/students/all_students_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $this->common_model->student_passport($y->id);
			$row[] = $y->reg_id; 
			$row[] = $y->admission_id; 
			$row[] = $this->common_model->get_student_fullname($y->id);
			$row[] = $this->common_model->get_class_details($y->class_id)->class;
			$row[] = $this->current_model->student_bio_info($y->id);
			$row[] = $this->current_model->student_place_info($y->id);
			$row[] = $y->religion;
			$row[] = $this->current_model->student_previous_school_info($y->id);
			$row[] = $this->current_model->first_parent_info($y->id);
			$row[] = $this->current_model->second_parent_info($y->id);
			$row[] = $this->current_model->suspension_status($y->id);
			$row[] = $this->current_model->revoke_status($y->id);
			$row[] = $y->pass_reset_code;
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
	
	


	/* ========== Suspended Students ========== */
	public function suspended_students() {
		$inner_page_title = 'Suspended Students (' .count($this->common_model->get_suspended_students_list(school_id)). ')'; 
		$this->admin_header('Suspended Students', $inner_page_title);	
		$data['total_suspended_students'] = count($this->common_model->get_suspended_students_list(school_id));
		$data['total_students'] = count($this->common_model->get_all_students_list(school_id));
		$data['sections'] = $this->common_model->get_sections(school_id);
		$this->load->view('admin/students/suspended_students', $data);
        $this->admin_footer();
	}
	
	
	public function suspended_students_ajax() {
		$this->load->model('ajax/admin/students/suspended_students_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $this->common_model->student_passport($y->id);
			$row[] = $y->reg_id; 
			$row[] = $y->admission_id; 
			$row[] = $this->common_model->get_student_fullname($y->id);
			$row[] = $y->suspension_info; 
			$row[] = $y->suspension_duration; 
			$row[] = x_date($y->date_suspended); 
			$row[] = $this->common_model->get_class_details($y->class_id)->class;
			$row[] = $this->current_model->student_bio_info($y->id);
			$row[] = $this->current_model->student_place_info($y->id);
			$row[] = $y->religion;
			$row[] = $this->current_model->student_previous_school_info($y->id);
			$row[] = $this->current_model->first_parent_info($y->id);
			$row[] = $this->current_model->second_parent_info($y->id);
			$row[] = $y->pass_reset_code;
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
	
	



	/* ========== Revoked Students ========== */
	public function revoked_students() {
		$inner_page_title = 'Revoked Students (' .count($this->common_model->get_revoked_students_list(school_id)). ')'; 
		$this->admin_header('Revoked Students', $inner_page_title);	
		$data['total_revoked_students'] = count($this->common_model->get_revoked_students_list(school_id));
		$data['total_students'] = count($this->common_model->get_all_students_list(school_id));
		$data['sections'] = $this->common_model->get_sections(school_id);
		$this->load->view('admin/students/revoked_students', $data);
        $this->admin_footer();
	}
	
	
	public function revoked_students_ajax() {
		$this->load->model('ajax/admin/students/revoked_students_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $this->common_model->student_passport($y->id);
			$row[] = $y->reg_id; 
			$row[] = $y->admission_id; 
			$row[] = $this->common_model->get_student_fullname($y->id);
			$row[] = $y->revoke_info; 
			$row[] = x_date($y->date_revoked); 
			$row[] = $this->common_model->get_class_details($y->class_id)->class;
			$row[] = $this->current_model->student_bio_info($y->id);
			$row[] = $this->current_model->student_place_info($y->id);
			$row[] = $y->religion;
			$row[] = $this->current_model->student_previous_school_info($y->id);
			$row[] = $this->current_model->first_parent_info($y->id);
			$row[] = $this->current_model->second_parent_info($y->id);
			$row[] = $y->pass_reset_code;
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





	/* ========== Graduated Students ========== */
	public function graduated_students() {
		$inner_page_title = 'Graduated Students (' .count($this->common_model->get_graduated_students_list(school_id)). ')'; 
		$this->admin_header('Graduated Students', $inner_page_title);	
		$data['total_graduated_students'] = count($this->common_model->get_graduated_students_list(school_id));
		$data['total_students'] = count($this->common_model->get_all_students_list(school_id));
		$data['sections'] = $this->common_model->get_sections(school_id);
		$this->load->view('admin/students/graduated_students', $data);
        $this->admin_footer();
	}
	
	
	public function graduated_students_ajax() {
		$this->load->model('ajax/admin/students/graduated_students_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $this->common_model->student_passport($y->id);
			$row[] = $y->reg_id; 
			$row[] = $y->admission_id; 
			$row[] = $this->common_model->get_student_fullname($y->id);
			$row[] = $this->common_model->get_class_details($y->graduated_from)->class;
			$row[] = x_date($y->date_graduated); 
			$row[] = $this->current_model->student_bio_info($y->id);
			$row[] = $y->pass_reset_code;
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
	
	



	/* ========== Single Class ========== */
	public function view_single_class() { //select class in modal in header
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$class_id = $this->input->post('class_id', TRUE);
		$slug = $this->common_model->get_class_details($class_id)->slug;
		if ($this->form_validation->run())  {
			//redirect to the single class page of the selected class
			redirect(site_url('students_admin/single_class/'.$slug));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}


	public function single_class($slug) {
		//check slug (class) exists in this school
		$this->check_school_data_exists(school_id, $slug, 'slug', 'classes', 'admin');
		$class_details = $this->common_model->get_class_details_by_slug(school_id, $slug);
		$page_title = 'Class: ' . $class_details->class;
		$this->admin_header($page_title, $page_title);	
		$data['y'] = $class_details;
		$data['session'] = current_session_slug;
		$data['term'] = current_term;
		$data['slug'] = $slug;
		$data['classes'] = $this->common_model->get_classes(school_id);
		$this->load->view('admin/students/single_class', $data);
        $this->admin_footer();
	}
	
	
	public function single_class_ajax($slug) {
		//check slug (class) exists in this school
		$this->check_school_data_exists(school_id, $slug, 'slug', 'classes', 'admin');
		$this->load->model('ajax/admin/students/single_class_model_ajax', 'current_model');
		$class_id = $this->common_model->get_class_details_by_slug(school_id, $slug)->id;
		$list = $this->current_model->get_records($class_id);
		$data = array();
		foreach ($list as $y) {
			$last_promoted = ($y->last_promoted != NULL) ? x_date($y->last_promoted) : NULL; 
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $this->common_model->student_passport($y->id);
			$row[] = $y->reg_id; 
			$row[] = $y->admission_id; 
			$row[] = $this->common_model->get_student_fullname($y->id);
			$row[] = $y->sex; 
			$row[] = $this->current_model->student_attendance_info($y->id);
			$row[] = $last_promoted;
			$row[] = $this->current_model->suspension_status($y->id);
			$row[] = $y->pass_reset_code;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($class_id),
			"recordsFiltered" => $this->current_model->count_filtered_records($class_id),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	
	
	
	
	
	
	/* ========== Student Registration ========== */
	public function student_registration($error = array('error' => '')) {
		$this->admin_header('Student Registration', 'Student Registration');
		$data['classes_option'] = $this->common_model->classes_option_by_section_group(school_id); 
		$data['upload_error'] = $error;
		$this->load->view('admin/students/student_registration', $data);
        $this->admin_footer();
	}


	public function student_registration_action($error = array('error' => '')) { 
		//form validation rules and file upload config
		$this->form_validation->set_rules('admission_id', 'Admission ID', 'trim|required|callback_check_admissionID_no_conflict');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[2]|max_length[200]');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[2]|max_length[200]');
		$this->form_validation->set_rules('other_name', 'Other Name', 'trim');
		$this->form_validation->set_rules('class_id', 'Current Class', 'trim|required');
		$this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required');
		$this->form_validation->set_rules('sex', 'Sex', 'required');
		$this->form_validation->set_rules('blood_group', 'Blood Group', 'trim');
		$this->form_validation->set_rules('place_of_birth', 'Place of Birth', 'trim');
		$this->form_validation->set_rules('nationality', 'Nationality', 'trim|required');
		$this->form_validation->set_rules('state_of_origin', 'State of Origin', 'trim');
		$this->form_validation->set_rules('local_gov', 'L.G.A', 'trim'); 
		$this->form_validation->set_rules('home_town', 'Home Town', 'trim');
		$this->form_validation->set_rules('home_address', 'Home Address', 'trim');
		$this->form_validation->set_rules('religion', 'Religion', 'trim');
		$this->form_validation->set_rules('present_school', 'Former School', 'trim');
		$this->form_validation->set_rules('present_school_address', 'Former School Address', 'trim');
		$this->form_validation->set_rules('present_class', 'Class in Former School', 'trim');
		$this->form_validation->set_rules('admission_date', 'Admission Date', 'trim');
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
		$this->form_validation->set_rules('additional_info', 'Additional Information', 'trim');
		
		//config for file uploads
        $config['upload_path']          = './assets/uploads/photos/students'; //path to save the files
        $config['allowed_types']        = 'jpg|JPG|jpeg|JPEG';  //extensions which are allowed
        $config['max_size']             = 64; //filesize cannot exceed 64KB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
		
		$this->load->library('upload', $config);
		
		if ($this->form_validation->run())  {	
			
			if ( $_FILES['passport_photo']['name'] == "" ) { //file is not selected
				$passport_photo = NULL;
				$passport = NULL;
				$this->students_admin_model->student_registration($passport_photo, $passport); //insert the data into db
				$this->session->set_flashdata('status_msg', 'Registration Successful!');
				redirect(site_url('students_admin/student_registration')); 
				
			} elseif ( ( ! $this->upload->do_upload('passport_photo')) && ($_FILES['passport_photo']['name'] != "") ) { 	
				//upload does not happen when file is selected
				$error = array('error' => $this->upload->display_errors());
				$this->student_registration($error); //reload page with upload errors
				
			} else { //file is selected, upload happens, everyone is happy
				//generate a square-sized 100x100 passport
				$passport_photo = $this->upload->data('file_name');
				$passport = generate_image_thumb($passport_photo, '100', '100');		
				$this->students_admin_model->student_registration($passport_photo, $passport); //insert the data into db
				$this->session->set_flashdata('status_msg', 'Registration Successful!');
				redirect(site_url('students_admin/student_registration')); 
			}
			
		} else { 
			$this->student_registration($error); //validation fails, reload page with validation errors
		}
	}
	
	



	/* ========== Edit Student ========== */
	public function edit_student($id, $error = array('error' => '')) {
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'students', 'admin');
		$y = $this->common_model->get_student_details_by_id($id);
		$page_title = 'Edit Student: '  . $this->common_model->get_student_fullname($y->id);
		$this->admin_header($page_title, $page_title);
		$data['y'] = $y;
		$data['classes_option'] = $this->common_model->classes_option_by_section_group(school_id); 
		$data['current_class'] = $this->common_model->get_class_details($y->class_id)->class;
		$data['upload_error'] = $error;
		$this->load->view('admin/students/edit_student', $data);
        $this->admin_footer();
	}
	

	public function edit_student_action($id, $error = array('error' => '')) { 
		//check if demo user
		$this->demo_action_restricted_admin();
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'students', 'admin');
		//check if entered admission ID is student's
		$current_admission_id = $this->common_model->get_student_details_by_id($id)->admission_id;
		$new_admission_id = $this->input->post('admission_id', TRUE);
		//form validation rules and file upload config
		if ($new_admission_id == $current_admission_id) { //it's student's, make field required only
			$this->form_validation->set_rules('admission_id', 'Admission ID', 'trim|required');
		} else { //it's not student's, make field required and ensure no conflict
			$this->form_validation->set_rules('admission_id', 'Admission ID', 'trim|required|callback_check_admissionID_no_conflict');
		}
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|min_length[2]|max_length[200]');
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|min_length[2]|max_length[200]');
		$this->form_validation->set_rules('other_name', 'Other Name', 'trim');
		$this->form_validation->set_rules('class_id', 'Current Class', 'trim|required');
		$this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required');
		$this->form_validation->set_rules('sex', 'Sex', 'required');
		$this->form_validation->set_rules('blood_group', 'Blood Group', 'trim');
		$this->form_validation->set_rules('place_of_birth', 'Place of Birth', 'trim');
		$this->form_validation->set_rules('nationality', 'Nationality', 'trim|required');
		$this->form_validation->set_rules('state_of_origin', 'State of Origin', 'trim');
		$this->form_validation->set_rules('local_gov', 'L.G.A', 'trim'); 
		$this->form_validation->set_rules('home_town', 'Home Town', 'trim');
		$this->form_validation->set_rules('home_address', 'Home Address', 'trim');
		$this->form_validation->set_rules('religion', 'Religion', 'trim');
		$this->form_validation->set_rules('present_school', 'Former School', 'trim');
		$this->form_validation->set_rules('present_school_address', 'Former School Address', 'trim');
		$this->form_validation->set_rules('present_class', 'Class in Former School', 'trim');
		$this->form_validation->set_rules('admission_date', 'Admission Date', 'trim');
		$this->form_validation->set_rules('additional_info', 'Additional Information', 'trim');
		
		$y = $this->common_model->get_student_details_by_id($id);
		if ($this->form_validation->run())  {	
			
			if ( $_FILES['passport_photo']['name'] == "" ) { //file is not selected, update with old passport
				$passport_photo = $y->passport_photo; //old passport photo
				$passport = $y->passport; //old passport
				$this->students_admin_model->edit_student($id, $passport_photo, $passport); //update student info
				$this->session->set_flashdata('status_msg', "{$y->first_name} updated successfully!");
				redirect(site_url('students_admin/edit_student/'.$id)); 
				
			} elseif ( ( ! $this->upload->do_upload('passport_photo')) && ($_FILES['passport_photo']['name'] != "") ) { 	
				//upload does not happen when file is selected
				$error = array('error' => $this->upload->display_errors());
				$this->edit_student($id, $error); //reload page with upload errors
				
			} else { //file is selected, upload happens, everyone is happy
			
				//delete old passport photo and passport from server
				$this->students_admin_model->delete_student_passport($id);
				$passport_photo = $this->upload->data('file_name');
				$passport = generate_image_thumb($passport_photo, '100', '100');	
				$this->students_admin_model->edit_student($id, $passport_photo, $passport); //update student info
				$this->session->set_flashdata('status_msg', "{$y->first_name} updated successfully!");
				redirect(site_url('students_admin/edit_student/'.$id)); 
			}
			
		} else { 
			$this->edit_student($id, $error); //validation fails, reload page with validation errors
		}
	}
	
	
	public function check_admissionID_no_conflict() { //check admission ID does not already exist when adding/editing student
		$admission_id = $this->input->post('admission_id', TRUE);
		$query = $this->common_model->get_student_details($admission_id);
		if( ! $query) { //does not exist
			return TRUE;
		} else {
			$assigned_to = $query->last_name . ' ' . $query->first_name . ' ' . $query->other_name;
			$this->form_validation->set_message('check_admissionID_no_conflict', "Admission ID already assigned to another student - {$assigned_to}");
			return FALSE;
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
	public function associate_parent($id) {
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'students', 'admin');
		$y = $this->common_model->get_student_details_by_id($id);
		$page_title = 'Associate Parent: '  . $this->common_model->get_student_fullname($y->id);
		$this->admin_header($page_title, $page_title);
		$parent_id = $y->parent_id;
		$p = $this->common_model->get_parent_details_by_id($parent_id);
		$first_parent_name = $p->name;
		$second_parent_name = $p->sec_parent_name;
		$data['y'] = $y;
		$data['student_id'] = $y->id;
		$data['first_parent_name'] = $first_parent_name;
		$data['second_parent_name'] = $second_parent_name;
		$data['student_name'] = $this->common_model->get_student_fullname($id);
		$data['current_class'] = $this->common_model->get_class_details($y->class_id)->class;
		$data['matched_parents'] = $this->students_admin_model->get_matched_parents($id);
		$this->load->view('admin/students/associate_parent', $data);
        $this->admin_footer();
	}


	public function associate_parent_search_ajax() {
        $keyword = $this->input->post('keyword');
        $data = $this->students_admin_model->associate_parent_search($keyword);       
        echo json_encode($data);
    }


    public function associate_parent_action($student_id, $parent_id) { 
		$this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'admin');
		$this->check_school_data_exists(school_id, $parent_id, 'id', 'parents', 'admin');
		$this->students_admin_model->associate_parent($student_id, $parent_id);
		$this->session->set_flashdata('status_msg', 'Parent associated to student successfully.');
		redirect($this->agent->referrer());
	}





	/* ====== Student Profile ====== */
	public function student_profile($student_id) {
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'admin');
		$student_name = $this->common_model->get_student_fullname($student_id);
		$page_title = 'Profile: ' . $student_name;
		$this->admin_header($page_title, $page_title);	
		$student_details = $this->common_model->get_student_details_by_id($student_id);
		$parent_id = $student_details->parent_id;
		$parent_details = $this->common_model->get_parent_details_by_id($parent_id);
		$data['student_name'] = $student_name;
		$data['class'] = $this->common_model->get_class_details($student_details->class_id)->class;
		$data['y'] = $student_details;
		$data['p'] = $parent_details;
		$this->load->view('admin/students/student_profile', $data);
        $this->admin_footer();
	}





	/* ========== Attendance ========== */
	public function select_class_attendance() { //select class in modal in header: current term
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$session = current_session_slug;
		$term = current_term;
		$class_id = $this->input->post('class_id', TRUE);
		$slug = $this->common_model->get_class_details($class_id)->slug;
		if ($this->form_validation->run())  {
			//redirect to the attendance page of the selected class
			redirect(site_url('students_admin/attendance/'.$session.'/'.$term.'/'.$slug));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}


	public function select_archived_class_attendance() { //select class in modal in header: archive
		$this->form_validation->set_rules('session', 'Session', 'trim|required');
		$this->form_validation->set_rules('term', 'Term', 'trim|required');
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$session = $this->input->post('session', TRUE);
		$term = $this->input->post('term', TRUE);
		$class_id = $this->input->post('class_id', TRUE);
		$slug = $this->common_model->get_class_details($class_id)->slug;
		if ($this->form_validation->run())  {
			//redirect to the attendance page of the selected class
			redirect(site_url('students_admin/attendance/'.$session.'/'.$term.'/'.$slug));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}


	public function attendance($session, $term, $slug) {
		//check slug (class) exists in this school
		$this->check_school_data_exists(school_id, $slug, 'slug', 'classes', 'admin');
		$class_details = $this->common_model->get_class_details_by_slug(school_id, $slug);
		$page_title = 'Attendance: ' . $class_details->class;
		$this->admin_header($page_title, $page_title);	
		$data['session'] = $session;
		$data['the_session'] = get_the_session($session);
		$data['term'] = $term;
		$data['class_id'] = $class_details->id;
		$data['level'] = $class_details->level;
		$data['slug'] = $slug;
		$data['y'] = $class_details;
		$data['students'] = $this->common_model->get_students_list_by_class($class_details->id);
		$this->load->view('admin/students/attendance/attendance', $data);
        $this->admin_footer();
	}


	public function mark_attendance($session, $term, $class_id) {
		//check class exists in this school
		$this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
		$slug = $this->common_model->get_class_details($class_id)->slug;
		
		$this->form_validation->set_rules('date', 'Date', 'trim|required');
		$this->form_validation->set_rules('student_id[]', 'Student ID', 'trim');

		$student_id = $this->input->post('student_id', TRUE);
		$status = $this->input->post('status', TRUE);
		$date = $this->input->post('date', TRUE);
		$date = x_date($date);	

		if ($this->form_validation->run()) {

			for ($i = 0; $i < count($student_id); $i++) {
				
				//check if an item is selected, ignore otherwise
				if ( isset($status[$i]) ) {
					$id = $student_id[$i];
					$d_status = $status[$i];

					//check if student was marked absent or absent
					if ($d_status == 'Present') {

						//check if this student has been marked present for the selected date
						$query_present = $this->students_admin_model->check_attendance_marked($session, $term, $class_id, $id, 'Present');
						if ($query_present == 0) { //student has not been marked present for the selected date
							$this->students_admin_model->mark_student_present($session, $term, $class_id, $id);  //mark present
						}

					} else { //Absent

						//check if this student has been marked absent for the selected date
						$query_absent = $this->students_admin_model->check_attendance_marked($session, $term, $class_id, $id, 'Absent');
						if ($query_absent == 0) { //student has not been marked absent for the selected date
							$this->students_admin_model->mark_student_absent($session, $term, $class_id, $id);  //mark absent
						}

					}

				} 

			}
			$this->session->set_flashdata('status_msg', "Attendance marked successfully.");
			redirect('students_admin/attendance/'.$session.'/'.$term.'/'.$slug);

		} else {
			$this->attendance($session, $term, $slug); //reload page with validation errors
		}	
	}


	public function attendance_details($session, $term, $slug, $id) {
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'students', 'admin');
		$page_title = 'Attendance: ' . $this->common_model->get_student_fullname($id);
		$this->admin_header($page_title, $page_title);
		$class_details = $this->common_model->get_class_details_by_slug(school_id, $slug);
		$class_id = $class_details->id;
		$data['student_name'] = $this->common_model->get_student_fullname($id);
		$data['session'] = $session;
		$data['the_session'] = get_the_session($session);
		$data['term'] = $term;
		$data['class_id'] = $class_id;
		$data['class'] = $class_details->class;
		$data['slug'] = $slug;
		$data['id'] = $id;
		$data['attendance'] = $this->students_admin_model->get_student_attendance($session, $term, $class_id, $id);
		$data['att_present'] = $this->common_model->get_attendance_present($session, $term, $class_id, $id);
		$data['att_absent'] = $this->common_model->get_attendance_absent($session, $term, $class_id, $id);
		$data['att_total'] = $this->common_model->get_attendance_total($session, $term, $class_id, $id);
		$this->load->view('admin/students/attendance/attendance_details', $data);
        $this->admin_footer();
	}


	public function check_attendance_ajax($student_id) { 
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'admin');
		//set validation rules
		$this->form_validation->set_rules('date', 'Date', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$errors = array(
				'validation_status' => 0,
				'validation_errors' => validation_errors(),
			);
			echo json_encode($errors);

		} else {

			$date = $this->input->post('date', TRUE);
			$query = $this->students_admin_model->check_attendance($student_id, $date);

			if ($query) {

				$att_id = $query->id;
				$session = get_the_session($query->session);
				$term = $query->term;
				$class = $this->common_model->get_class_details($query->class_id)->class;
				$date = x_date_full($query->date);
				$status = ($query->status == 'Present') ? '<b class="text-success">Present</b>' : '<b class="text-danger">Absent</b>';

				$json_data = array(
					'att_exists' => 'true',
					'att_id' => $att_id,
					'session' => $session,
					'term' => $term,
					'class' => $class,
					'date' => $date,
					'status' => $status,
				);
				echo json_encode($json_data);

			} else {

				$json_data = array(
					'att_exists' => 'false',
					'message' => "No attendance data for selected date",
				);
				echo json_encode($json_data);

			}
		}
	}


	public function delete_attendance($att_id) { 
		//check att id exists in this school
		$this->check_school_data_exists(school_id, $att_id, 'id', 'attendance', 'admin');
		$this->students_admin_model->delete_attendance($att_id);
		$this->session->set_flashdata('status_msg', 'Attendance data deleted successfully.');
		redirect($this->agent->referrer());
	}




	
	
	

	

	/* ========== Student Actions ========== */

	public function message_parent($id) { 
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'students', 'admin');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		$y = $this->common_model->get_student_details_by_id($id);
		$parent_id = $y->parent_id;
		$parent_name = $this->common_model->get_parent_details_by_id($parent_id)->name;
		if ($this->form_validation->run())  {		
			$this->students_admin_model->message_parent($id);
			$this->session->set_flashdata('status_msg', "Message successfully sent to {$parent_name}.");
		} else {
			$this->session->set_flashdata('status_msg_error', 'Error sending message to parent.');
		}
		redirect($this->agent->referrer());
	}
	
	
	public function promote_student($id) { 
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'students', 'admin');
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$this->form_validation->set_rules('last_promoted', 'Date', 'trim|required');
		$y = $this->common_model->get_student_details_by_id($id);
		$current_class_id = $y->class_id;
		$new_class_id = $this->input->post('class_id', TRUE);
		$new_class = $this->common_model->get_class_details($new_class_id)->class;
		if ($this->form_validation->run()) {
			//check if the current class is different from the new class
			if ($current_class_id != $new_class_id) {
				$this->students_admin_model->promote_student($id);
				$this->session->set_flashdata('status_msg', "{$y->first_name} promoted to {$new_class} successfully.");
			} else {
				$this->session->set_flashdata('status_msg_error', "{$y->first_name} cannot be promoted to current class.");
			}
		} else {
			$this->session->set_flashdata('status_msg_error', "Error promoting student.");
		}
		redirect($this->agent->referrer());
	}
	
	
	public function suspend_student($id) {
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'students', 'admin');
		$y = $this->common_model->get_student_details_by_id($id);
		$this->form_validation->set_rules('suspension_info', 'Reason for Suspension', 'trim|required');
		$this->form_validation->set_rules('suspension_duration', 'Suspension Duration', 'trim|required');
		$duration = $this->input->post('suspension_duration', TRUE);
		if ($this->form_validation->run()) {
			$this->students_admin_model->suspend_student($id);
			$this->session->set_flashdata('status_msg', "{$y->first_name} suspended successfully for {$duration}.");
		} else {
			$this->session->set_flashdata('status_msg_error', "Error suspending student.");
		}
		redirect($this->agent->referrer());
	}
	
	
	public function unsuspend_student($id) { 
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'students', 'admin');
		$y = $this->common_model->get_student_details_by_id($id);
		$this->students_admin_model->unsuspend_student($id);
		$this->session->set_flashdata('status_msg', "{$y->first_name} unsuspended successfully.");
		redirect($this->agent->referrer());
	}
	
	
	public function revoke_student_admission($id) {
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'students', 'admin');
		$y = $this->common_model->get_student_details_by_id($id);
		$this->form_validation->set_rules('revoke_info', 'Reason for Revoke', 'trim|required');
		if ($this->form_validation->run()) {
			$this->students_admin_model->revoke_student_admission($id);
			$this->session->set_flashdata('status_msg', "{$y->first_name} revoked successfully.");
		} else {
			$this->session->set_flashdata('status_msg_error', "Error revoking student.");
		}
		redirect($this->agent->referrer());
	}
	
	
	public function unrevoke_student_admission($id) { 
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'students', 'admin');
		$y = $this->common_model->get_student_details_by_id($id);
		$this->students_admin_model->unrevoke_student_admission($id);
		$this->session->set_flashdata('status_msg', "{$y->first_name} unrevoked successfully.");
		redirect($this->agent->referrer());
	}


	public function graduate_student($id) { 
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'students', 'admin');
		$y = $this->common_model->get_student_details_by_id($id);
		$this->students_admin_model->graduate_student($id);
		$this->session->set_flashdata('status_msg', "{$y->first_name} graduated successfully.");
		redirect($this->agent->referrer());
	}


	public function re_admit_graduated_student($id) { 
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'students', 'admin');
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$y = $this->common_model->get_student_details_by_id($id);
		$class_id = $this->input->post('class_id', TRUE);
		$class = $this->common_model->get_class_details($class_id)->class;
		if ($this->form_validation->run()) {
			$this->students_admin_model->re_admit_graduated_student($id);
			$this->session->set_flashdata('status_msg', "{$y->first_name} re-admitted to {$class} successfully.");
		} else {
			$this->session->set_flashdata('status_msg_error', "Error re-admitting student.");
		}
		redirect($this->agent->referrer());
	}
	
	
	public function delete_student($id) { 
		//check if demo user
		$this->demo_action_restricted_admin();
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'students', 'admin');
		$this->students_admin_model->delete_student($id);
		$this->session->set_flashdata('status_msg', 'Student deleted successfully.');
		redirect($this->agent->referrer());
	}
	
	
	public function bulk_actions_students() { 
		//check if demo user
		$this->demo_action_restricted_admin();
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->students_admin_model->bulk_actions_students(); 
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}
	

	
	
}