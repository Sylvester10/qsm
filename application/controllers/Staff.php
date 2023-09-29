<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Staff
Role: Controller
Description: Staff Class controls access to all staff pages and functions
Model: Staff_model
Author: Nwankwo Ikemefuna
Date Created: 25th April, 2018
*/


class Staff extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('staff_model');
		$this->staff_restricted(); //allow only logged in users to access this class
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
		$this->school_id = $this->staff_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		//ensure school account is activated
		$this->activation_restricted_staff(school_id); 

		//module-level scripts
		$this->staff_module_scripts = array('s_staff');
	}
	
	
	/* ====== Dashboard ====== */
	
	public function index() { //staff dashboard, routed as dashboard
		$this->staff_header('Staff', 'Dashboard');
		$data['total_students'] = count($this->common_model->get_all_students_list(school_id));
		$data['total_staff'] = count($this->common_model->get_staff(school_id));
		$data['total_classes'] = count($this->common_model->get_classes(school_id));
		$data['total_sections'] = count($this->common_model->get_sections(school_id));
		$data['recent_notifs'] = $this->staff_model->get_recent_notifs();
		$this->load->view('staff/dashboard/dashboard', $data);
		$this->staff_footer();
	}


	public function restricted_access() { //restricted access page
		$this->staff_header('Error: Restricted Access', 'Error: Restricted Access');
		$this->load->view('shared/errors/restricted_access');
		$this->staff_footer();
	}


	public function send_quick_mail_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			echo validation_errors();
		} else {
			$this->staff_model->send_quick_mail();
			echo 1;	//indicator of success, will be used to check status in javascript
		}
	}
	

	
	
	
	/* ====== Notifications ====== */
	public function notifications() {
		//update notifs to seen
		$this->staff_model->update_read_notifs();
		$inner_page_title = 'Notifications (' .$this->staff_model->count_all_notifs(). ')'; 
		$this->staff_header('Notifications', $inner_page_title);	
		$this->load->view('staff/profile/notifications');
        $this->staff_footer();
	}
	
	
	public function notifications_ajax() {
		$this->load->model('ajax/staff/profile/notifs_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$row[] = 	'<div class="row table-border-top">
							<div class="col-md-1">'
								.checkbox_bulk_action($y->id).
							'</div>
							<div class="col-md-8">
								<small>' .time_ago($y->date). '</small>
								<h3 style="margin-top: -5px">' .$y->subject. '</h3>
								<div class="m-t-10">' .$y->message. '</div>
							</div>
							<div class="col-md-2 col-md-offset-1">'
								.$this->current_model->options($y->id) . $this->current_model->modals($y->id). 
							'</div>
						</div>'; //bundle all columns into one column
			$data[] = $row; 
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records(),
			"recordsFiltered" => $this->current_model->count_filtered_records(),
			"data" => $data,
		);
		echo json_encode($output);
	}
	
	
	public function delete_notif($id) { 
		//check notif exists for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'staff_notifs', 'staff');
		$y = $this->staff_model->get_notif_details($id);
		//ensure this notif belongs to this user
		$this->restricted_user_data($y->user_id, $this->staff_details->id, 'staff');
		$this->staff_model->delete_notif($id);
		$this->session->set_flashdata('status_msg', 'Notification deleted successfully.');
		redirect(site_url('staff/notifications'));
	}
	
	
	public function delete_bulk_notifs() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$notifs = ($selected_rows == 1) ? 'notification' : 'notifications';
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				delete_bulk_items('id', 'staff_notifs');
				$this->session->set_flashdata('status_msg', "{$selected_rows} {$notifs} deleted successfully.");
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect(site_url('staff/notifications'));
	}
	
	
	
	
	/* ====== Profile ====== */
	public function profile() {
		$this->staff_header('Profile', 'Profile');	
		$data['y'] = $this->staff_details;
		$this->load->view('staff/profile/profile', $data);
        $this->staff_footer();
	}
	
	
	public function edit_profile($error = array('error' => '')) {
		$this->staff_header('Edit Profile', 'Edit Profile');	
		$data['y'] = $this->staff_details;
		$data['upload_error'] = $error;
		$this->load->view('staff/profile/edit_profile', $data);
        $this->staff_footer();
	}
	
	
	public function edit_profile_action($error = array('error' => '')) { 
		//check if demo user
		$this->demo_action_restricted_staff();
		// validation rules
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('name', 'Name', 'trim|min_length[2]|max_length[500]|required');
		$this->form_validation->set_rules('phone', 'Mobile', 'trim|required|is_natural');
		$this->form_validation->set_rules('nationality', 'Nationality', 'trim|required');
		$this->form_validation->set_rules('state_of_origin', 'State of Origin', 'trim');
		$this->form_validation->set_rules('local_gov', 'L.G.A', 'trim'); 
		$this->form_validation->set_rules('acc_number', 'Account Number', 'trim|is_natural');
		$this->form_validation->set_rules('bank_name', 'Bank Name', 'trim');
		$this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required');
		$this->form_validation->set_rules('sex', 'Sex', 'required');
		$this->form_validation->set_rules('home_address', 'Home Address', 'trim|min_length[2]|max_length[500]');
		$this->form_validation->set_rules('religion', 'Religion', 'trim');
		$this->form_validation->set_rules('name_of_kin', 'Name of Next of Kin', 'trim');
		$this->form_validation->set_rules('email_of_kin', 'Email of Next of Kin', 'trim|valid_email');
		$this->form_validation->set_rules('mobile_of_kin', 'Mobile of Next of Kin', 'trim|is_natural');
		$this->form_validation->set_rules('address_of_kin', 'Mobile of Next of Kin', 'trim');
		
		//config for file uploads
        $config['upload_path']          = './assets/uploads/photos/staff'; //path to save the files
        $config['allowed_types']        = 'jpg|JPG|jpeg|JPEG';  //extensions which are allowed
        $config['max_size']             = 64; //filesize cannot exceed 64KB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
		
		$this->load->library('upload', $config);
		
		$y = $this->staff_details;
		
		if ($this->form_validation->run())  {	
			
			if ( $_FILES['passport_photo']['name'] == "" ) { //file is not selected, update with old passport
				$passport_photo = $y->passport_photo; //old passport photo
				$passport = $y->passport; //old passport
				$this->staff_model->edit_profile($passport_photo, $passport); 
				$this->session->set_flashdata('status_msg', 'profile updated successfully!');
				redirect('staff/profile');
				
			} elseif ( ( ! $this->upload->do_upload('passport_photo')) && ($_FILES['passport_photo']['name'] != "") ) { 	
				//upload does not happen when file is selected
				$error = array('error' => $this->upload->display_errors());
				$this->edit_profile($error); //reload page with upload errors
				
			} else { //file is selected, upload happens, everyone is happy
			
				//delete old passport photo and passport from server
				$this->staff_model->delete_staff_passport($id);
				$passport_photo = $this->upload->data('file_name');
				$passport = generate_image_thumb($passport_photo, '100', '100');	
				$this->staff_model->edit_profile($passport_photo, $passport); 
				$this->session->set_flashdata('status_msg', 'profile updated successfully!');
				redirect('staff/profile');
			}
			
		} else { 
			$this->edit_profile(); //validation fails, reload page with validation errors
		}
	}
	
	
	public function change_password_ajax() {	
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|matches[password]', 
			array('matches' => 'Passwords do not match')
		);	
		$y = $this->staff_details;
		$old_password = $y->password;
		$new_password = hash('ripemd128', $this->input->post('password', TRUE));
        if ($this->form_validation->run())  {	

        	//check if demo user	
			$demo_action_allowed = $this->demo_action_restricted_staff_ajax();
			if ($demo_action_allowed) {
					//check if old password and new password are the same
				if ($new_password == $old_password) {
					echo 'No changes made!';
				} else {
					$this->staff_model->change_password();
					echo 1;	
				}
			} else {
				echo $this->demo_action_restricted_msg();
			}	
		
		} else { 
			echo validation_errors();
		}
    }



    /* ========== Signature =========== */
    public function signature($error = array('error' => '')) {
		$this->staff_header('Signature', 'Signature');	
		$data['y'] = $this->staff_details;
		$data['upload_error'] = $error;
		$this->load->view('staff/profile/signature', $data);
        $this->staff_footer();
	}


	public function update_signature($error = array('error' => '')) { 
		//config for file uploads
        $config['upload_path']          = './assets/uploads/signature/staff'; //path to save the files
        $config['allowed_types']        = 'jpeg|JPEG|jpg|JPG|png|PNG';  //extensions which are allowed
        $config['max_size']             = 64; //image size cannot exceed 64KB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
			    						
		$this->load->library('upload', $config);
		
		if ( $_FILES['signature']['name'] == "" ) { //file is not selected
			$this->session->set_flashdata('status_msg_error', "No file selected!");
			redirect(site_url('staff/signature'));
			
		} elseif ( ( ! $this->upload->do_upload('signature')) && ($_FILES['signature']['name'] != "") ) { 	
			//upload does not happen when file is selected
			$error = array('error' => $this->upload->display_errors());
			$this->signature($error); //reload page with upload errors
			
		} else { //file is selected, upload happens, everyone is happy
			//delete the old signature file
			$this->staff_model->delete_old_signature();
			$signature = $this->upload->data('file_name');
			$this->staff_model->update_signature($signature);
			$this->session->set_flashdata('status_msg', "Signature updated successfully!");
			redirect(site_url('staff/signature'));
		}
	}
	
	
	

	/* ====== School Info ====== */
	public function school_info() {
		$this->staff_header('School Info', 'School Info');	
		$data['y'] = $this->common_model->get_school_info(school_id);
		$this->load->view('staff/info/school_info', $data);
        $this->staff_footer();
	}
	
	
	
	
}