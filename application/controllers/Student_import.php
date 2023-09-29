<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Student_import
Role: Controller
Description: Admin Class controls access to all student import pages and fucntions
Model: Student_import_model
Author: Nwankwo Ikemefuna
Date Created: 12th August, 2018
*/



class Student_import extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->load->model('student_import_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_admin(school_id, mod_student_import); //student import module
		$this->activation_restricted_admin(school_id); 

		//module-level scripts
		$this->admin_module_scripts = array('s_student_management');
	}




	/* ================ Student Import ================== */
	public function import_students($error = array('error' => '')) {
		$this->admin_header('Import Students', 'Import Students');
		$data['upload_error'] = $error;
		$data['total_imported_students'] = count($this->student_import_model->get_imported_students());
		$this->load->view('admin/students/import/import_students', $data);
		$this->admin_footer();
    }


	public function import_students_action() {
		//ensure there is no previous uncompleted import
		$this->student_import_model->check_imported_students();	
    	//config for file uploads
        $config['upload_path']          = './assets/uploads/imports/students'; //path to save the files
        $config['allowed_types']        = 'xls|xlsx';  //extensions which are allowed
        $config['max_size']             = 1024 * 5; //image size cannot exceed 5MB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
		$this->load->library('upload', $config);

		if ( $_FILES['excel_file']['name'] == "" ) { //file is not selected
			$this->session->set_flashdata('status_msg_error', "No file selected!");
			$this->import_students(); //reload page
			
		} elseif ( ( ! $this->upload->do_upload('excel_file')) && ($_FILES['excel_file']['name'] != "") ) { 	
			//upload does not happen when file is selected
			$error = array('error' => $this->upload->display_errors());
			$this->import_students($error); //reload page with upload errors
			
		} else { //file is selected, upload happens, everyone is happy

			$path = $_FILES["excel_file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);

			foreach ($object->getWorksheetIterator() as $worksheet) {
			    $highestRow = $worksheet->getHighestRow(); //last row with data
			    $highestColumn = $worksheet->getHighestColumn(); //last column with data

			    //start loop from 2nd row. Row 1 is title row
			    for ($row=2; $row<=$highestRow; $row++) {

			    	//Column Var      =   		Cell value 														Column ID
					$admission_id 				= $worksheet->getCellByColumnAndRow(0, $row)->getValue();		//A
					$last_name 					= $worksheet->getCellByColumnAndRow(1, $row)->getValue();		//B
					$first_name 				= $worksheet->getCellByColumnAndRow(2, $row)->getValue();		//C
					$other_name 				= $worksheet->getCellByColumnAndRow(3, $row)->getValue();		//D
					$class_id 					= $worksheet->getCellByColumnAndRow(4, $row)->getValue();		//E
					$date_of_birth 				= $worksheet->getCellByColumnAndRow(5, $row)->getValue();		//F
					$sex 						= $worksheet->getCellByColumnAndRow(6, $row)->getValue();		//G
					$blood_group 				= $worksheet->getCellByColumnAndRow(7, $row)->getValue();		//H
					$place_of_birth 			= $worksheet->getCellByColumnAndRow(8, $row)->getValue();		//I
					$nationality 				= $worksheet->getCellByColumnAndRow(9, $row)->getValue();		//J
					$state_of_origin 			= $worksheet->getCellByColumnAndRow(10, $row)->getValue();		//K
					$local_gov					= $worksheet->getCellByColumnAndRow(11, $row)->getValue();		//L
					$home_town 					= $worksheet->getCellByColumnAndRow(12, $row)->getValue();		//M
					$home_address 				= $worksheet->getCellByColumnAndRow(13, $row)->getValue();		//N
					$religion 					= $worksheet->getCellByColumnAndRow(14, $row)->getValue();		//O
					$present_school 			= $worksheet->getCellByColumnAndRow(15, $row)->getValue();		//P
					$present_school_address 	= $worksheet->getCellByColumnAndRow(16, $row)->getValue();		//Q
					$present_class 				= $worksheet->getCellByColumnAndRow(17, $row)->getValue();		//R
					$parent_name 				= $worksheet->getCellByColumnAndRow(18, $row)->getValue();		//S
					$parent_sex 				= $worksheet->getCellByColumnAndRow(19, $row)->getValue();		//T
					$parent_relationship 		= $worksheet->getCellByColumnAndRow(20, $row)->getValue();		//U
					$parent_phone 				= $worksheet->getCellByColumnAndRow(21, $row)->getValue();		//V
					$parent_email 				= $worksheet->getCellByColumnAndRow(22, $row)->getValue();		//W
					$sec_parent_name			= $worksheet->getCellByColumnAndRow(23, $row)->getValue();		//X
					$sec_parent_sex 			= $worksheet->getCellByColumnAndRow(24, $row)->getValue();		//Y
					$sec_parent_relationship 	= $worksheet->getCellByColumnAndRow(25, $row)->getValue();		//Z
					$sec_parent_phone 			= $worksheet->getCellByColumnAndRow(26, $row)->getValue();		//AA
					$sec_parent_email 			= $worksheet->getCellByColumnAndRow(27, $row)->getValue();		//AB
					$admission_date 			= $worksheet->getCellByColumnAndRow(28, $row)->getValue();		//AC
					$additional_info 			= $worksheet->getCellByColumnAndRow(29, $row)->getValue();		//AD
					
					//check if parent email is empty; return null if true
					$parent_email = ($parent_email != '') ? strtolower($parent_email) : NULL;
					$sec_parent_email = ($sec_parent_email != '') ? strtolower($sec_parent_email) : NULL;
					
					$data[] = array(
						'school_id' 						=> school_id,
						'admission_id' 						=> strtoupper($admission_id),
						'last_name' 						=> ucwords($last_name),
						'first_name' 						=> ucwords($first_name),
						'other_name' 						=> ucwords($other_name),
						'class_id' 							=> $class_id,
						'date_of_birth' 					=> $date_of_birth,
						'sex' 								=> ucfirst($sex),
						'blood_group' 						=> ucwords($blood_group),
						'place_of_birth' 					=> ucwords($place_of_birth),
						'nationality' 						=> ucwords($nationality),
						'state_of_origin' 					=> ucwords($state_of_origin),
						'local_gov' 						=> ucwords($local_gov),
						'home_town' 						=> ucwords($home_town),
						'home_address' 						=> ucwords($home_address),
						'religion' 							=> ucwords($religion),
						'present_school' 					=> ucwords($present_school),
						'present_school_address' 			=> ucwords($present_school_address),
						'present_class' 					=> ucwords($present_class),
						'parent_name' 						=> ucwords($parent_name),
						'parent_sex' 						=> ucfirst($parent_sex),
						'parent_relationship' 				=> ucwords($parent_relationship),
						'parent_phone' 						=> $parent_phone,
						'parent_email'					 	=> $parent_email,
						'sec_parent_name' 					=> ucwords($sec_parent_name),
						'sec_parent_sex' 					=> ucfirst($sec_parent_sex),
						'sec_parent_relationship' 			=> ucwords($sec_parent_relationship),
						'sec_parent_phone' 					=> $sec_parent_phone,
						'sec_parent_email' 					=> $sec_parent_email,
						'admission_date' 					=> $admission_date,
						'additional_info' 					=> ucfirst($additional_info),
					);
    			}
   			}
   			if ( ! empty($data) ) { //ensure row contains data
			//insert into temporary students table
				$this->student_import_model->insert_imported_students($data);
			}
   			//delete the excel file from server
   			$file_name = $this->upload->data('file_name');
   			$file_path = $config['upload_path'] . '/' . $file_name;
   			$this->student_import_model->delete_imported_file($file_path);
			$highestRow = $highestRow - 1; //total rows with content - 1 (title row)
			$students = ($highestRow == 1) ? 'student' : 'students';
			$this->session->set_flashdata('status_msg', "{$highestRow} {$students} imported successfully. Review imported data before continuing.");
			//redirect to imported students page
			redirect('student_import/imported_students');
  		} 
	}


	public function imported_students() {
		$inner_page_title = 'Imported Students (' .count($this->student_import_model->get_imported_students()). ')'; 
		$this->admin_header('Imported Students', $inner_page_title);	
		$data['total_imported_students'] = count($this->student_import_model->get_imported_students());
		$data['valid_admission_idx'] = $this->student_import_model->get_total_valid_admission_idx();
		$data['valid_class_idx'] = $this->student_import_model->get_total_valid_class_idx();
		$this->load->view('admin/students/import/imported_students', $data);
        $this->admin_footer();
	}


	public function complete_import() { //transfer imported students to permanent students table
		//validate required fields
		$this->student_import_model->validate_required_field('admission_id', 'Admission ID');	
		$this->student_import_model->validate_required_field('last_name', 'Last Name');	
		$this->student_import_model->validate_required_field('first_name', 'First Name');	
		$this->student_import_model->validate_required_field('class_id', 'Class ID');	
		$this->student_import_model->validate_required_field('sex', 'Sex');	
		$this->student_import_model->validate_required_field('date_of_birth', 'Date of Birth');	
		$this->student_import_model->validate_required_field('nationality', 'Nationality');	
		$this->student_import_model->validate_required_field('parent_name', 'First Parent Name');	
		//check if admission ID does not already exist in students table
		$this->student_import_model->validate_admission_idx();	
		//check if admission ID is not a duplicate in imported students table
		$this->student_import_model->validate_duplicate_admission_id();	
		//check if class ID is valid
		$this->student_import_model->validate_class_idx();	
		//check if parent email is valid
		$this->student_import_model->validate_parent_emails();	

		$imported_students = $this->student_import_model->get_imported_students();
		foreach ($imported_students as $s) {

			//student data
			$student_data = array(
				'school_id' 						=> school_id,
				'admission_id' 						=> $s->admission_id,
				'last_name' 						=> $s->last_name,
				'first_name' 						=> $s->first_name,
				'other_name' 						=> $s->other_name,
				'class_id' 							=> $s->class_id,
				'date_of_birth' 					=> $s->date_of_birth,
				'sex' 								=> $s->sex,
				'blood_group' 						=> $s->blood_group,
				'place_of_birth' 					=> $s->place_of_birth,
				'nationality' 						=> $s->nationality,
				'state_of_origin' 					=> $s->state_of_origin,
				'local_gov' 						=> $s->local_gov,
				'home_town' 						=> $s->home_town,
				'home_address' 						=> $s->home_address,
				'religion' 							=> $s->religion,
				'present_school' 					=> $s->present_school,
				'present_school_address' 			=> $s->present_school_address,
				'present_class' 					=> $s->present_class,
				'admission_date' 					=> $s->admission_date,
				'additional_info' 					=> $s->additional_info,
				'suspended' 						=> 'false',
				'revoked' 							=> 'false',
				'graduated' 						=> 'false',
				'demo_user' 						=> 'false',
			);

			//parent data
			$parent_data = array(
				'school_id' 						=> school_id,
				'name' 								=> $s->parent_name,
				'sex' 								=> $s->parent_sex,
				'relationship' 						=> $s->parent_relationship,
				'phone' 							=> $s->parent_phone,
				'email'					 			=> $s->parent_email,
				'sec_parent_name' 					=> $s->sec_parent_name,
				'sec_parent_sex' 					=> $s->sec_parent_sex,
				'sec_parent_relationship' 			=> $s->sec_parent_relationship,
				'sec_parent_phone' 					=> $s->sec_parent_phone,
				'sec_parent_email' 					=> $s->sec_parent_email,
				'demo_user' 						=> 'false',
			);
			//insert student data
			$this->student_import_model->insert_student_data($student_data, $parent_data, $s->parent_email);
		}

		//delete imported data
		$this->student_import_model->delete_imported_students_data();
		$total_imported_students = count($imported_students);
		$imports = ($total_imported_students == 1) ? 'import' : 'imports';
		$this->session->set_flashdata('status_msg', "{$total_imported_students} {$imports} completed successfully.");
		//redirect to all students page
		redirect('students_admin/students');
	}


	public function imported_students_ajax() {
		$this->load->model('ajax/admin/students/imported_students_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->admission_id . $this->student_import_model->flag_conflicting_admission_id($y->admission_id) . $this->student_import_model->flag_duplicate_admission_id($y->admission_id);
			$row[] = $y->last_name;
			$row[] = $y->first_name;
			$row[] = $y->other_name;
			$row[] = $y->class_id . $this->student_import_model->flag_invalid_class_id($y->class_id);
			$row[] = $this->student_import_model->get_imported_student_class($y->id);
			$row[] = $y->sex;
			$row[] = $y->date_of_birth;
			$row[] = $y->blood_group;
			$row[] = $y->place_of_birth;
			$row[] = $y->nationality;
			$row[] = $y->state_of_origin;
			$row[] = $y->local_gov;
			$row[] = $y->home_town;
			$row[] = $y->home_address;
			$row[] = $y->religion;
			$row[] = $y->present_school;
			$row[] = $y->present_school_address;
			$row[] = $y->present_class;
			$row[] = $y->parent_name;
			$row[] = $y->parent_sex;
			$row[] = $y->parent_relationship;
			$row[] = $y->parent_phone;
			$row[] = $y->parent_email . $this->student_import_model->flag_conflicting_parent_email($y->parent_email);
			$row[] = $y->sec_parent_name;
			$row[] = $y->sec_parent_sex;
			$row[] = $y->sec_parent_relationship;
			$row[] = $y->sec_parent_phone;
			$row[] = $y->sec_parent_email;
			$row[] = $y->admission_date;
			$row[] = $y->additional_info;
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


	/* ========== Edit Student ========== */
	public function edit_student($id) {
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'imported_students', 'admin');
		$y = $this->student_import_model->get_imported_student_details_by_id($id);
		$page_title = 'Edit Student: '  . $this->student_import_model->get_imported_student_fullname($id);
		$this->admin_header($page_title, $page_title);
		$data['y'] = $y;
		$data['classes_option'] = $this->common_model->classes_option_by_section_group(school_id); 
		$data['current_class'] = $this->student_import_model->get_imported_student_class($id);
		$this->load->view('admin/students/import/edit_imported_student', $data);
        $this->admin_footer();
	}
	

	public function edit_student_action($id) { 
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'imported_students', 'admin');
		//form validation rules 
		$this->form_validation->set_rules('admission_id', 'Admission ID', 'trim|required');
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
		$this->form_validation->set_rules('parent_name', 'Parent Name', 'trim|required');
		$this->form_validation->set_rules('parent_sex', 'Parent Sex', 'trim');
		$this->form_validation->set_rules('parent_relationship', 'Relationship', 'trim');
		$this->form_validation->set_rules('parent_phone', 'Parent Mobile', 'trim');
		$this->form_validation->set_rules('parent_email', 'Parent Email', 'trim|valid_email');
		$this->form_validation->set_rules('sec_parent_name', 'Second Parent Name', 'trim');
		$this->form_validation->set_rules('sec_parent_sex', 'Second Parent Sex', 'trim');
		$this->form_validation->set_rules('sec_parent_relationship', 'Second Relationship', 'trim');
		$this->form_validation->set_rules('sec_parent_phone', 'Second Parent Mobile', 'trim');
		$this->form_validation->set_rules('sec_parent_email', 'Second Parent Email', 'trim|valid_email');
		$this->form_validation->set_rules('admission_date', 'Admission Date', 'trim');
		$this->form_validation->set_rules('additional_info', 'Additional Infomation', 'trim');

		$student_name = $this->student_import_model->get_imported_student_fullname($id);
		if ($this->form_validation->run())  {	
			$this->student_import_model->edit_student($id); //update student info
			$this->session->set_flashdata('status_msg', "{$student_name} updated successfully!");
			redirect(site_url('student_import/imported_students')); 
		} else { 
			$this->edit_student($id); //validation fails, reload page with validation errors
		}
	}


	public function delete_student($id) { 
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'imported_students', 'admin');
		$this->student_import_model->delete_student($id);
		$this->session->set_flashdata('status_msg', 'Student deleted successfully.');
		redirect($this->agent->referrer());
	}


	public function cancel_import() { 
		$this->student_import_model->cancel_import();
		$this->session->set_flashdata('status_msg', 'Import cancelled successfully.');
		redirect($this->agent->referrer());
	}
	
	
	public function bulk_actions_students() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->student_import_model->bulk_actions_students(); 
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}





}

