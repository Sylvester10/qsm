<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: School_staff
Role: Controller
Description: Admin Class controls access to all staff pages and functions from the admin's end
Model: School_staff_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class School_staff extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->load->model('school_staff_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_admin(school_id, mod_staff_management); //staff management module
		$this->activation_restricted_admin(school_id); 

		//module-level scripts
		$this->admin_module_scripts = array('s_staff_management');
	}
	
	
	
	/* ========== All Staff ========== */
	public function index() {
		$inner_page_title = 'Staff (' .count($this->common_model->get_staff(school_id)). ')'; 
		$this->admin_header('Staff', $inner_page_title);	
		$this->load->view('admin/staff/staff');
        $this->admin_footer();
	}
	
	
	public function staff_ajax() {
		$this->load->model('ajax/admin/staff/staff_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $this->common_model->staff_passport($y->id);
			$row[] = $y->title . ' ' . $y->name;
			$row[] = $this->current_model->staff_bio_info($y->id); 
			$row[] = $this->current_model->staff_place_info($y->id); 
			$row[] = $this->current_model->staff_employment_info($y->id);
			$row[] = $this->current_model->staff_contact_info($y->id);
			$row[] = $this->current_model->next_of_kin_info($y->id);
			$row[] = $this->current_model->staff_acc_info($y->id);
			$row[] = $y->religion;
			$row[] = $y->additional_info;
			$row[] = $y->role;
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





	/* ========== Class Teachers ========== */
	public function class_teachers() {
		$class_teachers = $this->common_model->get_staff_by_role(school_id, 'Class Teacher');
		$total_teachers = count($class_teachers);
		$inner_page_title = 'Class Teachers (' . $total_teachers . ')'; 
		$this->load->model('ajax/admin/staff/class_teachers_model_ajax', 'current_model');
		$this->admin_header('Class Teachers', $inner_page_title);
		$this->load->view('admin/staff/class_teachers');
        $this->admin_footer();
	}
	
	
	public function class_teachers_ajax() {
		$this->load->model('ajax/admin/staff/class_teachers_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$class_teacher_id = $y->id;
			$row = array();	
			$row[] = checkbox_bulk_action($class_teacher_id);
			$row[] = $this->current_model->options($class_teacher_id) . $this->current_model->modals($class_teacher_id);
			$row[] = $this->common_model->staff_passport($class_teacher_id);
			$row[] = $y->title . ' ' . $y->name;
			$row[] = $this->common_model->get_assigned_class($class_teacher_id); 
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




	/* ========== Subject Teachers ========== */
	public function subject_teachers() {
		$inner_page_title = 'Subject Teachers (' .count($this->common_model->get_subject_teachers(school_id)). ')'; 
		$this->admin_header('Subject Teachers', $inner_page_title);	
		$this->load->view('admin/staff/subject_teachers');
        $this->admin_footer();
	}
	
	
	public function subject_teachers_ajax() {
		$this->load->model('ajax/admin/staff/subject_teachers_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$staff_id = $y->staff_id;
			$staff_details = $this->common_model->get_staff_details_by_id($staff_id);
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $this->common_model->staff_passport($staff_id);
			$row[] = $staff_details->title . ' ' . $staff_details->name;
			$row[] = $this->school_staff_model->get_assigned_classes($y->id);
			$row[] = $this->school_staff_model->get_assigned_subjects($y->id);
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


	public function subject_teacher_assignment($id) {
		//check staff exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'subject_teachers', 'admin');
		$inner_page_title = 'Subject Teacher Assignment'; 
		$this->admin_header($inner_page_title, $inner_page_title);	
		$s = $this->common_model->get_subject_teacher_details_by_id($id);
		$staff_id = $s->staff_id;
		$staff_details = $this->common_model->get_staff_details_by_id($staff_id);
		$data['y'] = $staff_details;
		$data['subject_teacher_id'] = $id;
		$data['assigned_classes'] = $this->school_staff_model->get_assigned_classes($id);
		$data['assigned_subjects'] = $this->school_staff_model->get_assigned_subjects($id);
		$data['classes_option'] = $this->common_model->classes_option_by_section_group(school_id);
		$data['subjects_option'] = $this->common_model->subjects_option_by_section_group(school_id);
		$this->load->view('admin/staff/subject_teacher_assignment', $data);
        $this->admin_footer();
	}


	public function subject_teacher_assignment_ajax($id) {
		//check staff exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'subject_teachers', 'admin');
		$this->form_validation->set_rules('classes_assigned[]', 'Class(es) Assigned', 'trim|required');
		$this->form_validation->set_rules('subjects_assigned[]', 'Subject(s) Assigned', 'trim|required');
		if ($this->form_validation->run())  {		
			$this->school_staff_model->subject_teacher_assignment($id);
			echo 1;	
		} else { 
			echo validation_errors();
		}
    }


	public function remove_subject_teacher($id) { 
		//check if demo user
		$this->demo_action_restricted_admin();
		//check staff exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'subject_teachers', 'admin');
		$this->school_staff_model->remove_subject_teacher($id);
		$this->session->set_flashdata('status_msg', 'Subject teacher removed successfully.');
		redirect($this->agent->referrer());
	}
	
	
	public function delete_bulk_subject_teachers() { 
		//check if demo user
		$this->demo_action_restricted_admin();
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE));
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				delete_bulk_items('id', 'subject_teachers');
				$this->session->set_flashdata('status_msg', "{$selected_rows} subject teachers removed successfully.");
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}
	



	/* ========== Staff Registration ========== */
	public function new_staff($error = array('error' => '')) {
		$this->admin_header('New Staff', 'New Staff');
		$this->load->view('admin/staff/new_staff', $error);
        $this->admin_footer();
	}
	
	
	public function staff_registration_action($error = array('error' => '')) { 
		// validation rules
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('name', 'Name', 'trim|min_length[2]|max_length[500]|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[staff.email]', 
			array('is_unique' => 'This email address is already registered as a staff.')
		);
		$this->form_validation->set_rules('phone', 'Mobile', 'trim|required|is_natural');
		$this->form_validation->set_rules('nationality', 'Nationality', 'trim|required');
		$this->form_validation->set_rules('state_of_origin', 'State of Origin', 'trim');
		$this->form_validation->set_rules('local_gov', 'L.G.A', 'trim'); 
		$this->form_validation->set_rules('designation', 'Designation', 'trim|required');
		$this->form_validation->set_rules('acc_number', 'Account Number', 'trim|is_natural');
		$this->form_validation->set_rules('bank_name', 'Bank Name', 'trim');
		$this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required');
		$this->form_validation->set_rules('sex', 'Sex', 'required');
		$this->form_validation->set_rules('home_address', 'Home Address', 'trim|min_length[2]|max_length[500]');
		$this->form_validation->set_rules('religion', 'Religion', 'trim');
		$this->form_validation->set_rules('qualification', 'Qualification', 'trim');
		$this->form_validation->set_rules('employment_date', 'Date of Employment', 'trim');
		$this->form_validation->set_rules('name_of_kin', 'Name of Next of Kin', 'trim');
		$this->form_validation->set_rules('email_of_kin', 'Email of Next of Kin', 'trim|valid_email');
		$this->form_validation->set_rules('mobile_of_kin', 'Mobile of Next of Kin', 'trim|is_natural');
		$this->form_validation->set_rules('address_of_kin', 'Mobile of Next of Kin', 'trim');
		$this->form_validation->set_rules('additional_info', 'Additional Info', 'trim');
		
		//config for file uploads
        $config['upload_path']          = './assets/uploads/photos/staff'; //path to save the files
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
				$this->school_staff_model->staff_registration($passport_photo, $passport); //insert the data into db
				$this->session->set_flashdata('status_msg', 'New staff added successfully!');
				redirect('school_staff/new_staff');
				
			} elseif ( ( ! $this->upload->do_upload('passport_photo')) && ($_FILES['passport_photo']['name'] != "") ) { 	
				//upload does not happen when file is selected
				$error = array('error' => $this->upload->display_errors());
				$this->new_staff($error); //reload page with upload errors
				
			} else { //file is selected, upload happens, everyone is happy
				//generate a square-sized 100x100 passport
				$passport_photo = $this->upload->data('file_name');
				$passport = generate_image_thumb($passport_photo, '100', '100');
				$this->school_staff_model->staff_registration($passport_photo, $passport); //insert the data into db
				$this->session->set_flashdata('status_msg', 'New staff added successfully!');
				redirect('school_staff/new_staff');
			}
			
		} else { 
			$this->new_staff($error); //validation fails, reload page with validation errors
		}
	}
	
	


	/* ========== Staff Edit ========== */
	public function edit_staff($id, $error = array('error' => '')) {
		//check staff id exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'staff', 'admin');
		$staff_details = $this->common_model->get_staff_details_by_id($id);
		$page_title = 'Edit Staff: '  . $staff_details->name;
		$this->admin_header($page_title, $page_title);
		$data['y'] = $staff_details;
		$data['classes'] = $this->common_model->get_classes(school_id);
		$data['upload_error'] = $error;
		$this->load->view('admin/staff/edit_staff', $data);
        $this->admin_footer();
	}


	public function edit_staff_action($id, $error = array('error' => '')) { 
		// validation rules
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('name', 'Name', 'trim|min_length[2]|max_length[500]|required');
		$this->form_validation->set_rules('phone', 'Mobile', 'trim|required|is_natural');
		$this->form_validation->set_rules('nationality', 'Nationality', 'trim|required');
		$this->form_validation->set_rules('state_of_origin', 'State of Origin', 'trim');
		$this->form_validation->set_rules('local_gov', 'L.G.A', 'trim'); 
		$this->form_validation->set_rules('designation', 'Designation', 'trim|required');
		$this->form_validation->set_rules('acc_number', 'Account Number', 'trim|is_natural');
		$this->form_validation->set_rules('bank_name', 'Bank Name', 'trim');
		$this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required');
		$this->form_validation->set_rules('sex', 'Sex', 'required');
		$this->form_validation->set_rules('home_address', 'Home Address', 'trim|min_length[2]|max_length[500]');
		$this->form_validation->set_rules('qualification', 'Qualification', 'trim');
		$this->form_validation->set_rules('religion', 'Religion', 'trim');
		$this->form_validation->set_rules('employment_date', 'Date of Employment', 'trim');
		$this->form_validation->set_rules('name_of_kin', 'Name of Next of Kin', 'trim');
		$this->form_validation->set_rules('email_of_kin', 'Email of Next of Kin', 'trim|valid_email');
		$this->form_validation->set_rules('mobile_of_kin', 'Mobile of Next of Kin', 'trim|is_natural');
		$this->form_validation->set_rules('address_of_kin', 'Mobile of Next of Kin', 'trim');
		$this->form_validation->set_rules('additional_info', 'Additional Info', 'trim');
		
		//config for file uploads
        $config['upload_path']          = './assets/uploads/photos/staff'; //path to save the files
        $config['allowed_types']        = 'jpg|JPG|jpeg|JPEG';  //extensions which are allowed
        $config['max_size']             = 64; //filesize cannot exceed 64KB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
		
		$this->load->library('upload', $config);
		
		$y = $this->common_model->get_staff_details_by_id($id);
		
		if ($this->form_validation->run())  {	
			
			if ( $_FILES['passport_photo']['name'] == "" ) { //file is not selected, update with old passport
				$passport_photo = $y->passport_photo; //old passport photo
				$passport = $y->passport; //old passport
				$this->school_staff_model->edit_staff($id, $passport_photo, $passport); //update student info
				$this->session->set_flashdata('status_msg', "{$y->name} updated successfully!");
				redirect('school_staff/edit_staff/'.$id);
				
			} elseif ( ( ! $this->upload->do_upload('passport_photo')) && ($_FILES['passport_photo']['name'] != "") ) { 	
				//upload does not happen when file is selected
				$error = array('error' => $this->upload->display_errors());
				$this->edit_staff($id, $error); //reload page with upload errors
				
			} else { //file is selected, upload happens, everyone is happy
			
				//delete old passport photo and passport from server
				$this->school_staff_model->delete_staff_passport($id);
				$passport_photo = $this->upload->data('file_name');
				$passport = generate_image_thumb($passport_photo, '100', '100');	
				$this->school_staff_model->edit_staff($id, $passport_photo, $passport);
				$this->session->set_flashdata('status_msg', "{$y->name} updated successfully!");
				redirect('school_staff/edit_staff/'.$id);
			}
			
		} else { 
			$this->edit_staff($id); //validation fails, reload page with validation errors
		}
	}
	
	
	
	
	/* ========== Staff Profile ========== */
	public function staff_profile($id) {
		//check staff id exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'staff', 'admin');
		$staff_details = $this->common_model->get_staff_details_by_id($id);
		$page_title = 'Staff Profile: '  . $staff_details->name;
		$this->admin_header($page_title, $page_title);
		$data['y'] = $staff_details;
		$this->load->view('admin/staff/staff_profile', $data);
        $this->admin_footer();
	}
	



	/* ========== Staff Role ========== */
	public function staff_role($id) {
		//check staff id exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'staff', 'admin');
		$staff_details = $this->common_model->get_staff_details_by_id($id);
		$page_title = 'Staff Role: '  . $staff_details->name;
		$this->admin_header($page_title, $page_title);
		$data['y'] = $staff_details;
		$this->load->view('admin/staff/staff_role', $data);
        $this->admin_footer();
	}


	public function staff_role_ajax($id) {
		//check staff id exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'staff', 'admin');	
		$this->form_validation->set_rules('role[]', 'Role(s)', 'trim|required');
		if ($this->form_validation->run())  {		

			//check if demo user	
			$demo_action_allowed = $this->demo_action_restricted_admin_ajax();
			if ($demo_action_allowed)	{
				$this->school_staff_model->update_staff_role($id);
				echo 1;
			} else {
				echo $this->demo_action_restricted_msg();
			}	

		} else { 
			echo validation_errors();
		}
    }
	



	/* ========== Staff Actions ========== */

	public function message_staff($id) { 
		//check staff id exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'staff', 'admin');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		$y = $this->common_model->get_staff_details_by_id($id);
		if ($this->form_validation->run())  {		
			$this->school_staff_model->message_staff($id);
			$this->session->set_flashdata('status_msg', "Message successfully sent to {$y->name}.");
		} else {
			$this->session->set_flashdata('status_msg_error', 'Error sending message to staff.');
		}
		redirect($this->agent->referrer());
	}
	
	
	public function delete_staff($id) { 
		//check if demo user
		$this->demo_action_restricted_admin();
		//check staff id exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'staff', 'admin');
		$this->school_staff_model->delete_staff($id);
		$this->session->set_flashdata('status_msg', 'Staff member deleted successfully.');
		redirect($this->agent->referrer());
	}
	
	
	public function delete_bulk_staff() { 
		//check if demo user
		$this->demo_action_restricted_admin();
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE));
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				delete_bulk_items('id', 'staff');
				$this->session->set_flashdata('status_msg', "{$selected_rows} staff members deleted successfully.");
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}

	
	
	/* ====== Staff Attendance ====== */
	
	public function attendance() {
		$page_title = 'Staff Attendance';
		$this->admin_header($page_title, 'Staff Attendance');	
		$data['staff'] = $this->common_model->get_staff(school_id);
		$this->load->view('admin/staff/attendance', $data);
        $this->admin_footer();
	}
	

	public function mark_attendance() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$row_id = $this->input->post('check_bulk_action', TRUE);
		$action_type = $this->input->post('attendance_action', TRUE); 	
		$date = $this->input->post('date', TRUE);
		$date = x_date($date);	
		$this->form_validation->set_rules('date', 'Date', 'trim|required');

		if ($this->form_validation->run()) {

			if ($selected_rows > 0) { //at least 1 staff is selected	
				foreach ($row_id as $id) {
					//check which button was clicked
					switch ($action_type) {

						case 'mark_present':
							//check if this staff has been marked present for the selected date
							$query_present = $this->school_staff_model->check_attendance_marked($id, 'Present');
							if ($query_present == 0) { //staff has not been marked present for the selected date
								$this->school_staff_model->mark_staff_present($id);  //mark present
								$this->session->set_flashdata('status_msg', "Staff successfully marked present for {$date}.");
							} else { //staff has been marked present for the selected date
								$this->session->set_flashdata('status_msg_error', "One or more selected staff have already been marked present for {$date}.
									");
							}
						break;

						case 'mark_absent':
							//check if this staff has been marked absent for the selected date
							$query_absent = $this->school_staff_model->check_attendance_marked($id, 'Absent');
							if ($query_absent == 0) { //staff has not been marked absent for the selected date
								$this->school_staff_model->mark_staff_absent($id);  //mark absent
								$this->session->set_flashdata('status_msg', "Staff successfully marked absent for {$date}.");
							} else { //staff has been marked absent for the selected date
								$this->session->set_flashdata('status_msg_error', "One or more selected staff have already been marked absent for {$date}.
									");
							}
						break;

					}
				}

			} else { // no staff selected
				$this->session->set_flashdata('status_msg_error', 'No staff selected.');
			}
			redirect($this->agent->referrer());

		} else {
			$this->attendance(); //reload page with validation errors
		}	
	}
	


	public function attendance_details($id) {
		//check staff id exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'staff', 'admin');
		$y = $this->common_model->get_staff_details_by_id($id);	
		$page_title = 'Staff Attendance: ' . $y->name;
		$this->admin_header($page_title, $page_title);
		$data['id'] = $id;
		$data['staff_name'] = $y->name; 
		$data['staff_details'] = $this->common_model->get_staff_details_by_id($y->id);	
		$data['attendance'] = $this->school_staff_model->get_staff_attendance($id);
		$data['att_present'] = $this->school_staff_model->get_staff_attendance_present($y->id);
		$data['att_absent'] = $this->school_staff_model->get_staff_attendance_absent($y->id);
		$data['att_total'] = $this->school_staff_model->get_staff_attendance_total($y->id);
		$this->load->view('admin/staff/attendance_details', $data);
        $this->admin_footer();
	}
	
	
	public function check_attendance_ajax($id) { 
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'staff', 'admin');
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
			$query = $this->school_staff_model->check_attendance($id, $date);

			if ($query) {

				$session = get_the_session($query->session);
				$term = $query->term;
				$date = x_date_full($query->date);
				$status = ($query->status == 'Present') ? '<b class="text-success">Present</b>' : '<b class="text-danger">Absent</b>';

				$json_data = array(
					'att_exists' => 'true',
					'session' => $session,
					'term' => $term,
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



	public function delete_attendance($id) { 
		//check staff id exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'staff', 'admin');
		$this->school_staff_model->delete_attendance($id);
		$this->session->set_flashdata('status_msg', 'Attendance data deleted successfully.');
		redirect($this->agent->referrer());
	}

	

	
}