<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Staff_import
Role: Controller
Description: Admin Class controls access to all staff import pages and fucntions
Model: Staff_import_model
Author: Nwankwo Ikemefuna
Date Created: 12th August, 2018
*/



class Staff_import extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->load->model('staff_import_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		//$this->module_restricted_admin(school_id, mod_staff_import); //staff import module
		$this->activation_restricted_admin(school_id);

		//module-level scripts
		$this->admin_module_scripts = array('s_staff_management'); 
	}

 


	/* ================ staff Import ================== */
	public function import_staff($error = array('error' => '')) {
		$this->admin_header('Import staff', 'Import staff');
		$data['upload_error'] = $error;
		$data['total_imported_staff'] = count($this->staff_import_model->get_imported_staff());
		$this->load->view('admin/staff/import/import_staff', $data);
		$this->admin_footer();
    }


	public function import_staff_action() {
		//ensure there is no previous uncompleted import
		$this->staff_import_model->check_imported_staff();	
    	//config for file uploads
        $config['upload_path']          = './assets/uploads/imports/staff'; //path to save the files
        $config['allowed_types']        = 'xls|xlsx';  //extensions which are allowed
        $config['max_size']             = 1024 * 5; //image size cannot exceed 5MB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
		$this->load->library('upload', $config);

		if ( $_FILES['excel_file']['name'] == "" ) { //file is not selected
			$this->session->set_flashdata('status_msg_error', "No file selected!");
			$this->import_staff(); //reload page
			
		} elseif ( ( ! $this->upload->do_upload('excel_file')) && ($_FILES['excel_file']['name'] != "") ) { 	
			//upload does not happen when file is selected
			$error = array('error' => $this->upload->display_errors());
			$this->import_staff($error); //reload page with upload errors
			
		} else { //file is selected, upload happens, everyone is happy

			$path = $_FILES["excel_file"]["tmp_name"];
			$object = PHPExcel_IOFactory::load($path);

			foreach ($object->getWorksheetIterator() as $worksheet) {
			    $highestRow = $worksheet->getHighestRow(); //last row with data
			    $highestColumn = $worksheet->getHighestColumn(); //last column with data

			    //start loop from 2nd row. Row 1 is title row
			    for ($row=2; $row<=$highestRow; $row++) {

			    	//Column Var      =   		Cell value 														Column ID
					$title						= $worksheet->getCellByColumnAndRow(0, $row)->getValue();		//A
					$name 						= $worksheet->getCellByColumnAndRow(1, $row)->getValue();		//B
					$email 						= $worksheet->getCellByColumnAndRow(2, $row)->getValue();		//C
					$phone 						= $worksheet->getCellByColumnAndRow(3, $row)->getValue();		//D
					$nationality 				= $worksheet->getCellByColumnAndRow(4, $row)->getValue();		//E
					$state_of_origin 			= $worksheet->getCellByColumnAndRow(5, $row)->getValue();		//F
					$local_gov 					= $worksheet->getCellByColumnAndRow(6, $row)->getValue();		//G
					$designation 				= $worksheet->getCellByColumnAndRow(7, $row)->getValue();		//H
					$date_of_birth 				= $worksheet->getCellByColumnAndRow(8, $row)->getValue();		//I
					$sex 						= $worksheet->getCellByColumnAndRow(9, $row)->getValue();		//J
					$home_address 				= $worksheet->getCellByColumnAndRow(10, $row)->getValue();		//K
					$religion 					= $worksheet->getCellByColumnAndRow(11, $row)->getValue();		//L
					$qualification	 			= $worksheet->getCellByColumnAndRow(12, $row)->getValue();		//M
					$employment_date 			= $worksheet->getCellByColumnAndRow(13, $row)->getValue();		//N
					$name_of_kin	 			= $worksheet->getCellByColumnAndRow(14, $row)->getValue();		//O
					$email_of_kin				= $worksheet->getCellByColumnAndRow(15, $row)->getValue();		//P
					$mobile_of_kin 				= $worksheet->getCellByColumnAndRow(16, $row)->getValue();		//Q
					$address_of_kin 			= $worksheet->getCellByColumnAndRow(17, $row)->getValue();		//R
					$acc_number 				= $worksheet->getCellByColumnAndRow(18, $row)->getValue();		//S
					$bank_name 					= $worksheet->getCellByColumnAndRow(19, $row)->getValue();		//T
					$additional_info 			= $worksheet->getCellByColumnAndRow(20, $row)->getValue();		//U
					
					$data[] = array(
						'school_id' 						=> school_id,
						'title' 							=> ucfirst($title),
						'name' 								=> ucwords($name),
						'email' 							=> strtolower($email),
						'phone' 							=> $phone,
						'nationality' 						=> ucfirst($nationality),
						'state_of_origin' 					=> ucfirst($state_of_origin),
						'local_gov' 						=> ucfirst($local_gov),
						'designation' 						=> ucwords($designation),
						'date_of_birth' 					=> $date_of_birth,
						'sex' 								=> ucfirst($sex),
						'home_address' 						=> ucwords($home_address),
						'religion' 							=> ucwords($religion),
						'qualification' 					=> ucwords($qualification),
						'employment_date' 					=> $employment_date,
						'name_of_kin' 						=> ucwords($name_of_kin),
						'email_of_kin' 						=> strtolower($email_of_kin),
						'mobile_of_kin' 					=> $mobile_of_kin,
						'address_of_kin' 					=> ucwords($address_of_kin),
						'acc_number' 						=> $acc_number,
						'bank_name' 						=> ucwords($bank_name),
						'additional_info' 					=> ucfirst($additional_info),
					);
    			}
   			}
   			if ( ! empty($data) ) { //ensure row contains data
			//insert into temporary staff table
				$this->staff_import_model->insert_imported_staff($data);
			}
   			//delete the excel file from server
   			$file_name = $this->upload->data('file_name');
   			$file_path = $config['upload_path'] . '/' . $file_name;
   			$this->staff_import_model->delete_imported_file($file_path);
			$highestRow = $highestRow - 1; //total rows with content - 1 (title row)
			$this->session->set_flashdata('status_msg', "{$highestRow} staff imported successfully. Review imported data before continuing.");
			//redirect to imported staff page
			redirect('staff_import/imported_staff');
  		} 
	}


	public function imported_staff() {
		$inner_page_title = 'Imported staff (' .count($this->staff_import_model->get_imported_staff()). ')'; 
		$this->admin_header('Imported staff', $inner_page_title);	
		$data['total_imported_staff'] = count($this->staff_import_model->get_imported_staff());
		$data['valid_emails'] = $this->staff_import_model->get_total_valid_emails();
		$this->load->view('admin/staff/import/imported_staff', $data);
        $this->admin_footer();
	}


	public function complete_import() { //transfer imported staff to permanent staff table
		//validate required fields
		$this->staff_import_model->validate_required_field('name', 'Name');	
		$this->staff_import_model->validate_required_field('email', 'Email');	
		$this->staff_import_model->validate_required_field('phone', 'Phone No.');	
		$this->staff_import_model->validate_required_field('designation', 'Designation');	
		$this->staff_import_model->validate_required_field('date_of_birth', 'Date of Birth');	
		$this->staff_import_model->validate_required_field('sex', 'Sex');	
		//check if email does not already exist in staff table
		$this->staff_import_model->validate_emails();	
		//check if email is not a duplicate in imported staff table
		$this->staff_import_model->validate_duplicate_email();	
			
		$imported_staff = $this->staff_import_model->get_imported_staff();
	    foreach ($imported_staff as $s) {
			$data[] = array(
				'school_id' 						=> school_id,
				'title' 							=> $s->title,
				'name' 								=> $s->name,
				'email' 							=> $s->email,
				'phone' 							=> $s->phone,
				'nationality' 						=> $s->nationality,
				'state_of_origin' 					=> $s->state_of_origin,
				'local_gov' 						=> $s->local_gov,
				'designation' 						=> $s->designation,
				'date_of_birth' 					=> $s->date_of_birth,
				'sex' 								=> $s->sex,
				'home_address' 						=> $s->home_address,
				'religion' 							=> $s->religion,
				'qualification' 					=> $s->qualification,
				'employment_date' 					=> $s->employment_date,
				'name_of_kin' 						=> $s->name_of_kin,
				'email_of_kin' 						=> $s->email_of_kin,
				'mobile_of_kin' 					=> $s->mobile_of_kin,
				'address_of_kin' 					=> $s->address_of_kin,
				'acc_number' 						=> $s->acc_number,
				'bank_name' 						=> $s->bank_name,
				'additional_info' 					=> $s->additional_info,
				'demo_user' 						=> 'false',
			);
		}

		if ( ! empty($data) ) {
			//insert into staff table
			$this->staff_import_model->insert_staff_record($data);
			//delete imported data
			$this->staff_import_model->delete_imported_staff_data($data);
			$total_imported_staff = count($imported_staff);
			$imports = ($total_imported_staff == 1) ? 'import' : 'imports';
			$this->session->set_flashdata('status_msg', "{$total_imported_staff} {$imports} completed successfully.");
			//redirect to all staff page
			redirect('school_staff');
		}
	}


	public function imported_staff_ajax() {
		$this->load->model('ajax/admin/staff/imported_staff_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $y->title;
			$row[] = $y->name;
			$row[] = $y->email . $this->staff_import_model->flag_conflicting_email($y->email) . $this->staff_import_model->flag_duplicate_email($y->email);
			$row[] = $y->phone;
			$row[] = $y->nationality;
			$row[] = $y->state_of_origin;
			$row[] = $y->local_gov;
			$row[] = $y->designation;
			$row[] = $y->sex;
			$row[] = $y->date_of_birth;
			$row[] = $y->home_address;
			$row[] = $y->religion;
			$row[] = $y->qualification;
			$row[] = $y->employment_date;
			$row[] = $y->name_of_kin;
			$row[] = $y->email_of_kin;
			$row[] = $y->mobile_of_kin;
			$row[] = $y->address_of_kin;
			$row[] = $y->acc_number;
			$row[] = $y->bank_name;
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


	
	/* ========== Edit Imported Staff ========== */
	public function edit_staff($id) {
		//check staff id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'imported_staff', 'admin');
		$y = $this->staff_import_model->get_imported_staff_details_by_id($id);
		$page_title = 'Edit staff: '  . $this->staff_import_model->get_imported_staff_fullname($id);
		$this->admin_header($page_title, $page_title);
		$data['y'] = $y;
		$this->load->view('admin/staff/import/edit_imported_staff', $data);
        $this->admin_footer();
	}
	

	public function edit_staff_action($id) { 
		//check staff id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'imported_staff', 'admin');
		//form validation rules 
		$this->form_validation->set_rules('title', 'Title', 'trim');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|min_length[2]|max_length[200]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('phone', 'Mobile No.', 'trim|required');
		$this->form_validation->set_rules('nationality', 'Nationality', 'trim|required');
		$this->form_validation->set_rules('state_of_origin', 'State of Origin', 'trim');
		$this->form_validation->set_rules('local_gov', 'L.G.A', 'trim'); 
		$this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required');
		$this->form_validation->set_rules('sex', 'Sex', 'required');
		$this->form_validation->set_rules('designation', 'Designation', 'trim|required');
		$this->form_validation->set_rules('home_address', 'Home Address', 'trim');
		$this->form_validation->set_rules('religion', 'Religion', 'trim');
		$this->form_validation->set_rules('qualification', 'Qualification', 'trim');
		$this->form_validation->set_rules('employment_date', 'Employment Date', 'trim');
		$this->form_validation->set_rules('name_of_kin', 'Next of Kin', 'trim');
		$this->form_validation->set_rules('email_of_kin', 'Next of Kin Email', 'trim');
		$this->form_validation->set_rules('mobile_of_kin', 'Next of Kin Mobile No.', 'trim');
		$this->form_validation->set_rules('acc_number', 'Account Number', 'trim');
		$this->form_validation->set_rules('bank_name', 'Bank Name', 'trim');
		$this->form_validation->set_rules('address_of_kin', 'Mobile of Next of Kin', 'trim');
		$this->form_validation->set_rules('additional_info', 'Additional Info', 'trim');
		
		$staff_name = $this->staff_import_model->get_imported_staff_fullname($id);
		if ($this->form_validation->run())  {	
			$this->staff_import_model->edit_staff($id); //update staff info
			$this->session->set_flashdata('status_msg', "{$staff_name} updated successfully!");
			redirect(site_url('staff_import/imported_staff')); 
		} else { 
			$this->edit_staff($id); //validation fails, reload page with validation errors
		}
	}


	public function delete_staff($id) { 
		//check staff id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'imported_staff', 'admin');
		$this->staff_import_model->delete_staff($id);
		$this->session->set_flashdata('status_msg', 'Staff deleted successfully.');
		redirect($this->agent->referrer());
	}


	public function cancel_import() { 
		$this->staff_import_model->cancel_import();
		$this->session->set_flashdata('status_msg', 'Import cancelled successfully.');
		redirect($this->agent->referrer());
	}
	
	
	public function bulk_actions_staff() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->staff_import_model->bulk_actions_staff(); 
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}





}

