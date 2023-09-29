<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Admin
Role: Controller
Description: Admin Class controls access to all admin pages and functions
Model: Admin_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class Admin extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->load->model('admin_model');
		$this->load->model('admin_users_model');
		$this->load->model('students_admin_model');
		$this->load->model('prs_admin_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants

		//module-level scripts
		$this->admin_module_scripts = array('s_admin');
	}



	
	/* ====== Dashboard ====== */
	
	public function index() { //admin dashboard, routed as dashboard
		$this->admin_header('Admin', 'Dashboard');
		$data['total_students'] = count($this->common_model->get_all_students_list(school_id));
		$data['total_parents'] = count($this->common_model->get_parents(school_id));
		$data['total_staff'] = count($this->common_model->get_staff(school_id));
		$data['total_admins'] = count($this->common_model->get_admins(school_id));
		$data['total_classes'] = count($this->common_model->get_classes(school_id));
		$data['total_sections'] = count($this->common_model->get_sections(school_id));
		$data['recent_request'] = $this->prs_admin_model->get_recent_request();
		$data['recent_notifs'] = $this->admin_model->get_recent_notifs();
		$data['plans'] = $this->common_model->get_plans();
		$data['modules'] = $this->common_model->get_modules()->result();
		$this->load->view('admin/dashboard/dashboard', $data); 
		$this->admin_footer();
	}


	public function restricted_access() { //restricted access page
		$this->admin_header('Error: Restricted Access', 'Error: Restricted Access');
		$this->load->view('shared/errors/restricted_access');
		$this->admin_footer();
	}


	public function restricted_plan() { //restricted plan page
		$this->admin_header('Error: Restricted Plan', 'Error: Restricted Plan');
		$this->load->view('admin/errors/restricted_plan');
		$this->admin_footer();
	}


	public function activation_failure() { //restricted plan page
		$this->admin_header('Error: Activation Failure', 'Error: Activation Failure');
		$this->load->view('admin/errors/activation_failure');
		$this->admin_footer();
	}



	
	
	public function send_quick_mail_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			echo validation_errors();
		} else {
			$this->admin_model->send_quick_mail();
			echo 1;	//indicator of success, will be used to check status in javascript
		}
	}
	
	
	public function send_bulk_mail_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('mailing_list', 'Mailing List', 'trim|required');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		
		if ($this->form_validation->run() == FALSE) {
			echo validation_errors();
		} else {
			$mailing_list = $this->input->post('mailing_list', TRUE);
			switch ($mailing_list) {
				case 'parents':
					$mail_list = $this->common_model->get_parents();	
					$this->admin_model->send_bulk_mail_parents($mail_list);
					echo 1;
				break;
				case 'staff':
					$mail_list = $this->common_model->get_staff();	
					$this->admin_model->send_bulk_mail($mail_list);
					echo 1;
				break;
				case 'admins':
					$mail_list = $this->common_model->get_admins();	
					$this->admin_model->send_bulk_mail($mail_list);
					echo 1;
				break;
			}
		}
	}




	
	/* ====== Profile ====== */
	public function profile($error = array('error' => '')) {
		$this->admin_header('Profile', 'Profile');	
		$data['y'] = $this->admin_details;
		$data['upload_error'] = $error;
		$data['section_assigned'] = $this->admin_users_model->get_section_assigned($this->admin_details->id);
		$this->load->view('admin/profile/profile', $data);
        $this->admin_footer();
	}
	
	
	public function edit_profile_ajax() {	
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required|is_natural');
		$this->form_validation->set_rules('acc_number', 'Account Number', 'trim|is_natural');
		$this->form_validation->set_rules('bank_name', 'Bank Name', 'trim');
		if ( $this->input->post('change_password') ) { //if change password box is selected, require password fields
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
			$this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|matches[password]', 
				array('matches' => 'Passwords do not match')
			);
		} else {
			$this->form_validation->set_rules('password', 'Password', 'trim|min_length[6]');
			$this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|matches[password]', 
				array('matches' => 'Passwords do not match')
			);
		}
		
        if ($this->form_validation->run())  {		
        	
			//check if demo user	
			$demo_action_allowed = $this->demo_action_restricted_admin_ajax();
			if ($demo_action_allowed)	{
				$this->admin_model->update_profile();
				echo 1;
			} else {
				echo $this->demo_action_restricted_msg();
			}	

		} else { 
			echo validation_errors();
		}
    }
	
	
	public function update_profile_photo($error = array('error' => '')) { 
		//config for file uploads
        $config['upload_path']          = './assets/uploads/photos/admins'; //path to save the files
        $config['allowed_types']        = 'jpg|JPG|png|PNG';  //extensions which are allowed
        $config['max_size']             = 1024; //image size cannot exceed 1MB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
			    						
		$this->load->library('upload', $config);
		
		if ( $_FILES['profile_photo']['name'] == "" ) { //file is not selected
			$this->session->set_flashdata('status_msg_error', "No file selected!");
			$this->profile(); //reload page
			
		} elseif ( ( ! $this->upload->do_upload('profile_photo')) && ($_FILES['profile_photo']['name'] != "") ) { 	
			//upload does not happen when file is selected
			$error = array('error' => $this->upload->display_errors());
			$this->profile($error); //reload page with upload errors
			
		} else { //file is selected, upload happens, everyone is happy
			//delete the old school logo and favicon
			$this->admin_model->delete_old_profile_photo();
		
			$profile_photo = $this->upload->data('file_name');
			//generate a 200x200 image for use as thumbnail
			$photo_thumb = generate_image_thumb($profile_photo, '200', '200');	
			$this->admin_model->update_profile_photo($profile_photo, $photo_thumb);
			$this->session->set_flashdata('status_msg', "Profile photo updated successfully!");
			redirect($this->agent->referrer());
		}
	}
	
	
	public function reset_profile_photo() {  //reset photo to app's default
		$this->admin_model->reset_profile_photo();
		$this->session->set_flashdata('status_msg', 'Profile photo removed successfully.');
		redirect($this->agent->referrer());
	}




	/* ========== Signature =========== */
	public function signature($error = array('error' => '')) {
		$this->admin_header('Signature', 'Signature');	
		$data['y'] = $this->admin_details;
		$data['upload_error'] = $error;
		$this->load->view('admin/profile/signature', $data);
        $this->admin_footer();
	}


	public function update_signature($error = array('error' => '')) { 
		//config for file uploads
        $config['upload_path']          = './assets/uploads/signature/admins'; //path to save the files
        $config['allowed_types']        = 'jpeg|JPEG|jpg|JPG|png|PNG';  //extensions which are allowed
        $config['max_size']             = 64; //image size cannot exceed 64KB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
	    $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
	    $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
			    						
		$this->load->library('upload', $config);
		
		if ( $_FILES['signature']['name'] == "" ) { //file is not selected
			$this->session->set_flashdata('status_msg_error', "No file selected!");
			redirect(site_url('admin/signature'));
			
		} elseif ( ( ! $this->upload->do_upload('signature')) && ($_FILES['signature']['name'] != "") ) { 	
			//upload does not happen when file is selected
			$error = array('error' => $this->upload->display_errors());
			$this->signature($error); //reload page with upload errors
			
		} else { //file is selected, upload happens, everyone is happy
			//delete the old signature file
			$this->admin_model->delete_old_signature();
			$signature = $this->upload->data('file_name');
			$this->admin_model->update_signature($signature);
			$this->session->set_flashdata('status_msg', "Signature updated successfully!");
			redirect(site_url('admin/signature'));
		}
	}
	
	
	
	
	
	
	
	/* ====== Notifications ====== */
	public function notifications() {
		//update notifs to seen
		$this->admin_model->update_read_notifs();
		$inner_page_title = 'Notifications (' .$this->admin_model->count_all_notifs(). ')'; 
		$this->admin_header('Notifications', $inner_page_title);	
		$this->load->view('admin/profile/notifications');
        $this->admin_footer();
	}
	
	
	public function notifications_ajax() {
		$email = $this->admin_details->email;
		$this->load->model('ajax/admin/profile/notifs_model_ajax', 'current_model');
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
		$this->check_school_data_exists(school_id, $id, 'id', 'admin_notifs', 'admin');
		$y = $this->admin_model->get_notif_details($id);
		//ensure this notif belongs to this user
		$this->restricted_user_data($y->user_id, $this->admin_details->id, 'admin');
		$this->admin_model->delete_notif($id);
		$this->session->set_flashdata('status_msg', 'Notification deleted successfully.');
		redirect(site_url('admin/notifications'));
	}
	
	
	public function delete_bulk_notifs() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$notifs = ($selected_rows == 1) ? 'notification' : 'notifications';
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				delete_bulk_items('id', 'admin_notifs');
				$this->session->set_flashdata('status_msg', "{$selected_rows} {$notifs} deleted successfully.");
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect(site_url('admin/notifications'));
	}
	
	
	

	
	/* ====== Contact Vendor ====== */
	public function contact_vendor() {
		$this->admin_header('Contact Vendor', 'Contact Vendor');	
		$this->load->view('admin/software_vendor/contact_vendor');
        $this->admin_footer();
	}
	
	
	/* ===== Contact Form ===== */
	public function contact_vendor_ajax() { 
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		if ($this->form_validation->run())  {	
			$this->admin_model->contact_vendor(); //insert the data into db
			echo 1;
		} else { 
			echo validation_errors();
		}
	}

	
	
}