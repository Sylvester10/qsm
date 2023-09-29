<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Incidents
Role: Controller
Description: Incidents Class controls access to incident pages and functions from the admin's end
Model: Incidents_model
Author: Nwankwo Ikemefuna
Date Created: 18th June, 2018
*/


class Incidents extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->load->model('incidents_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_admin(school_id, mod_incidents); //incidents module
		$this->activation_restricted_admin(school_id); 

		//module-level scripts
		$this->admin_module_scripts = array('s_incidents');
	}

	
	

	/* ========== All Incidents ========== */
	public function index() {
		$inner_page_title = 'Incidents (' .count($this->incidents_model->get_all_incidents()). ')'; 
		$this->admin_header('Incidents', $inner_page_title);	
		$this->load->view('admin/incidents/all_incidents');
        $this->admin_footer();
	}
	
	
	public function all_incidents_ajax() {
		$this->load->model('ajax/admin/incidents/all_incidents_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$student_id = $y->student_id;
			$admission_id = $this->common_model->get_student_details_by_id($student_id)->admission_id;
			$student_name = $this->common_model->get_student_fullname($student_id);
			$class = $this->common_model->get_class_details($y->class_id)->class;
			$the_session = get_the_session($y->session);
			$evidence_count = count($this->incidents_model->get_evidence($y->id));
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $admission_id; 
			$row[] = $student_name;
			$row[] = $class; 
			$row[] = $y->caption; 
			$row[] = x_date($y->incident_date);
			$row[] = $the_session;
			$row[] = $y->term;
			$row[] = $evidence_count;
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




	/* ========== Student Incidents ========== */
	public function student_incidents($student_id) {
		//check student exists in this school
		$this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'admin');
		$student_name = $this->common_model->get_student_fullname($student_id);
		$y = $this->common_model->get_student_details_by_id($student_id);
		$admission_id = $y->admission_id;
		$class_id = $y->class_id;
		$class = $this->common_model->get_class_details($class_id)->class;
		$data['student_id'] = $student_id;
		$data['student_name'] = $student_name;
		$data['class'] = $class;
		$data['admission_id'] = $admission_id;
		$page_title = "Incidents: {$student_name}";
		$inner_page_title = $page_title . ' (' .count($this->incidents_model->get_student_incidents($student_id)). ')'; 
		$this->admin_header($page_title, $inner_page_title);	
		$this->load->view('admin/incidents/student_incidents', $data);
        $this->admin_footer();
	}
	
	
	public function student_incidents_ajax($student_id) {
		//check student exists in this school
		$this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'admin');
		$this->load->model('ajax/admin/incidents/student_incidents_model_ajax', 'current_model');
		$list = $this->current_model->get_records($student_id);
		$data = array();
		foreach ($list as $y) {
			$student_id = $y->student_id;
			$class = $this->common_model->get_class_details($y->class_id)->class;
			$the_session = get_the_session($y->session);
			$evidence_count = count($this->incidents_model->get_evidence($y->id));
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$row[] = $class; 
			$row[] = $y->caption; 
			$row[] = x_date($y->incident_date);
			$row[] = $the_session;
			$row[] = $y->term;
			$row[] = $evidence_count;
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($student_id),
			"recordsFiltered" => $this->current_model->count_filtered_records($student_id),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	



	/* ========== New Incident ========== */
	public function new_incident($student_id) {
		//check student exists in this school
		$this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'admin');
		$y = $this->common_model->get_student_details_by_id($student_id);
		$student_name = $this->common_model->get_student_fullname($student_id);
		$admission_id = $y->admission_id;
		$class_id = $y->class_id;
		$class = $this->common_model->get_class_details($class_id)->class;
		$page_title = "Record Incident: {$student_name}";
		$this->admin_header($page_title, $page_title);
		$data['student_id'] = $student_id;
		$data['student_name'] = $student_name;
		$data['class'] = $class;
		$data['admission_id'] = $admission_id;
		$this->load->view('admin/incidents/new_incident', $data);
        $this->admin_footer();
	}


	public function new_incident_ajax($student_id) { 
		//check student exists in this school
		$this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'admin');
		$this->form_validation->set_rules('caption', 'Caption', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('incident_date', 'Date of Incident', 'trim|required');
		$this->form_validation->set_rules('actions_taken', 'Actions Taken', 'trim|required');
		if ($this->form_validation->run())  {	
			$this->incidents_model->new_incident($student_id); //insert the data into db
			echo 1;
		} else { 
			echo validation_errors();
		}
	}




	/* ========== Edit Incident ========== */
	public function edit_incident($incident_id) {
		//check incident exists in this school
		$this->check_school_data_exists(school_id, $incident_id, 'id', 'incidents', 'admin');
		$incident_details = $this->incidents_model->get_incident_details($incident_id);
		$class_id = $incident_details->class_id;
		$class = $this->common_model->get_class_details($class_id)->class;
		$student_id = $incident_details->student_id;
		$y = $this->common_model->get_student_details_by_id($student_id);
		$admission_id = $y->admission_id;
		$student_name = $this->common_model->get_student_fullname($student_id);
		$page_title = 'Edit Incident: ' . $incident_details->caption;
		$this->admin_header($page_title, $page_title);
		$data['student_id'] = $student_id;
		$data['student_name'] = $student_name;
		$data['class'] = $class;
		$data['admission_id'] = $admission_id;
		$data['y'] = $incident_details;
		$this->load->view('admin/incidents/edit_incident', $data);
        $this->admin_footer();
	}


	public function edit_incident_ajax($incident_id) { 
		//check incident exists in this school
		$this->check_school_data_exists(school_id, $incident_id, 'id', 'incidents', 'admin');
		$this->form_validation->set_rules('caption', 'Caption', 'trim|required');
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		$this->form_validation->set_rules('incident_date', 'Date of Incident', 'trim|required');
		$this->form_validation->set_rules('actions_taken', 'Actions Taken', 'trim|required');
		if ($this->form_validation->run())  {	
			$this->incidents_model->edit_incident($incident_id); //insert the data into db
			echo 1;
		} else { 
			echo validation_errors();
		}
	}




	/* ========== Incident Actions ========== */

	public function delete_incident($incident_id) { 
		//check incident exists in this school
		$this->check_school_data_exists(school_id, $incident_id, 'id', 'incidents', 'admin');
		$this->incidents_model->delete_incident($incident_id);
		$this->session->set_flashdata('status_msg', 'Incident deleted successfully.');
		redirect($this->agent->referrer());
	}
	
	
	public function bulk_actions_incidents() { 
		//check if demo user
		$this->demo_action_restricted_admin();

		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->incidents_model->bulk_actions_incidents(); 
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}
	






	/* ========== Incident Evidences ========== */

	public function evidence($incident_id) {
		//check incident exists in this school
		$this->check_school_data_exists(school_id, $incident_id, 'id', 'incidents', 'admin');
		$incident_details = $this->incidents_model->get_incident_details($incident_id);
		$student_id = $incident_details->student_id;
		$student_name = $this->common_model->get_student_fullname($student_id);
		$admission_id = $this->common_model->get_student_details_by_id($student_id)->admission_id;
		$class = $this->common_model->get_class_details($incident_details->class_id)->class;
		$the_session = get_the_session($incident_details->session);
		$page_title = 'Evidence: ' . $incident_details->caption;
		$this->admin_header($page_title, $page_title);
		$data['y'] = $incident_details;
		$data['student_name'] = $student_name;
		$data['student_id'] = $student_id;
		$data['admission_id'] = $admission_id;
		$data['class'] = $class;
		$data['the_session'] = $the_session;
		$data['total_evidence'] = count($this->incidents_model->get_evidence($incident_id));
		$this->load->view('admin/incidents/evidence', $data);
        $this->admin_footer();
	}


	public function evidence_ajax($incident_id) {
		//check incident exists in this school
		$this->check_school_data_exists(school_id, $incident_id, 'id', 'incidents', 'admin');
		$this->load->model('ajax/admin/incidents/evidence_model_ajax', 'current_model');
		$list = $this->current_model->get_records($incident_id);
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = checkbox_bulk_action($y->id);
			$row[] = $y->evidence; 
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($incident_id),
			"recordsFiltered" => $this->current_model->count_filtered_records($incident_id),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}
	



	/* ========== Upload Evidence ========== */
	public function upload_evidence($incident_id) {
		//check incident exists in this school
		$this->check_school_data_exists(school_id, $incident_id, 'id', 'incidents', 'admin');
        // If file upload form submitted
        if ( ! empty($_FILES['evidence']['name']) ) {
            $filesCount = count($_FILES['evidence']['name']);
            for($i = 0; $i < $filesCount; $i++) {
                $_FILES['file']['name']     = $_FILES['evidence']['name'][$i];
                $_FILES['file']['type']     = $_FILES['evidence']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['evidence']['tmp_name'][$i];
                $_FILES['file']['error']     = $_FILES['evidence']['error'][$i];
                $_FILES['file']['size']     = $_FILES['evidence']['size'][$i];
                
                //config for file uploads
		        $config['upload_path']          = './assets/uploads/incidents/'; //path to save the files
		        $config['allowed_types']        = 'jpg|JPG|jpeg|JPEG|png|PNG|doc|docx|pdf|PDF';  //extensions which are allowed
		        $config['max_size']             = 1024 * 2; //filesize cannot exceed 2MB
		        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
			    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
			    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
				$this->load->library('upload', $config);
                $this->upload->initialize($config);
                
                // Upload file to server
                if($this->upload->do_upload('file')) {
                    // Uploaded file data
                    $file_data = $this->upload->data();
                    $upload_data[$i]['school_id'] = school_id;
                    $upload_data[$i]['evidence'] = $file_data['file_name'];
                    $upload_data[$i]['incident_id'] = $incident_id;
                }
            }
            
            if ( ! empty($upload_data) ) {
                // Insert files data into the database
                $insert = $this->incidents_model->upload_evidence($upload_data);
                
                // Upload status message
                if ($insert) {
                	$this->session->set_flashdata('status_msg', 'Evidence uploaded successfully');
                } else {
                	$this->session->set_flashdata('status_msg_error', 'Some problem occurred, please try again.');
                }    

            } else {
            	$this->session->set_flashdata('status_msg_error', 'Error uploading file(s), please try again.');
			}
			redirect($this->agent->referrer());

        } else {
        	$this->session->set_flashdata('status_msg_error', 'No file selected.');
        	redirect($this->agent->referrer());
        }
    }



    /* ========== Incident Actions ========== */

	public function delete_evidence($evidence_id) { 
		//check incident exists in this school
		$this->check_school_data_exists(school_id, $evidence_id, 'id', 'incident_evidence', 'admin');
		$this->incidents_model->delete_evidence($evidence_id);
		$this->session->set_flashdata('status_msg', 'File deleted successfully.');
		redirect($this->agent->referrer());
	}
	
	
	public function bulk_actions_evidence() { 
		//check if demo user
		$this->demo_action_restricted_admin();
		
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->incidents_model->bulk_actions_evidence(); 
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}
	

	
	
}