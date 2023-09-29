<?php
defined('BASEPATH') or die('Direct access not allowed');

/* ===== Documentation ===== 
Name: Message
Role: Controller
Description: Controls access to contact pages and functions in super admin panel
Model: Message_model
Author: Nwankwo Ikemefuna
Date Created: 16th August, 2018
*/


class Message extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->super_admin_restricted(); //allow only logged in users to access this class
		$this->load->model('message_model');
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);

        //module-level scripts
        $this->super_admin_module_scripts = array();
	}



	public function contact_messages() {
		$total_messages = $this->message_model->count_messages();
		$inner_page_title = 'Contact Messages (' . $total_messages . ')';
		$this->super_admin_header('Contact Messages', $inner_page_title);
		//config for pagination
        $config = array();
		$per_page = 25;  //number of items to be displayed per page
        $uri_segment = 3;  //pagination segment id
		$config["base_url"] = base_url('message/contact_messages');
        $config["total_rows"] = $total_messages;
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
		$data["messages"] = $this->message_model->get_messages($per_page, $page);
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
		$data['total_records'] = $total_messages;
		$data["latest_message"] = $this->message_model->get_latest_message();
		$this->load->view('super_admin/messages/contact_messages', $data);
		$this->super_admin_footer();
	}


	public function single_message($msg_id) {
			//check message exists
		$this->check_data_exists($msg_id, 'id', 'vendor_contact_messages', 'super_admin');
		$total_messages = $this->message_model->count_messages();
		$inner_page_title = 'Contact Messages (' . $total_messages . ')';
		$this->super_admin_header('Contact Messages', $inner_page_title);
		//config for pagination
        $config = array();
		$per_page = 25;  //number of items to be displayed per page
        $uri_segment = 4;  //pagination segment id
		$config["base_url"] = base_url('message/single_message/'.$msg_id);
        $config["total_rows"] = $total_messages;
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
		$data["messages"] = $this->message_model->get_messages($per_page, $page);
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
		$data['total_records'] = $total_messages;
		$data["message"] = $this->message_model->get_message_details($msg_id);
		$this->load->view('super_admin/messages/single_message', $data);
		$this->super_admin_footer();
	}



	public function reply_message($msg_id) { 
		//check message exists
		$this->check_data_exists($msg_id, 'id', 'vendor_contact_messages', 'super_admin'); 
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		$sender = $this->message_model->get_message_details($msg_id)->name;
		if ($this->form_validation->run())  {		
			$this->message_model->reply_message($sender);
			$this->session->set_flashdata('status_msg', "Reply successfully sent to {$sender}.");
		} else {
			$this->session->set_flashdata('status_msg_error', 'Error replying message.');
		}
		redirect($this->agent->referrer());
	}


	public function delete_message($msg_id) {
		$this->check_data_exists($msg_id, 'id', 'vendor_contact_messages', 'super_admin'); 
		$this->message_model->delete_message($msg_id);
		$this->session->set_flashdata('status_msg', 'Message deleted successfully.');
		redirect('message/contact_messages');
	}


	public function bulk_actions_messages() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->message_model->bulk_actions_messages();
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect('message/contact_messages');
	}



}