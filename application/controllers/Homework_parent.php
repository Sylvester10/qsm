<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Homework_parent
Role: Controller
Description: Homework_parent Class controls parent attendance functions in the parents panel
Model: Homework_model
Author: Nwankwo Ikemefuna
Date Created: 27th August, 2018
*/



class Homework_parent extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->parent_restricted(); //allow only logged in users to access this class	
		$this->load->model('homework_model');
		$this->parent_details = $this->common_model->get_parent_details($this->session->parent_email);
		//get school id
		$this->school_id = $this->parent_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		//ensure user login and current module are covered by current plan
		$this->login_module_restricted(school_id, mod_parent_login, mod_homework, 'parent'); 
		//ensure school account is activated
		$this->activation_restricted_parent(school_id); 

		//module-level scripts
		$this->parent_module_scripts = array();
	}





	public function homework($class_id) {
		$class = $this->common_model->get_class_details($class_id)->class;
		$page_title = 'Homework: ' . $class;
		$this->parent_header($page_title, $page_title);	
		//config for pagination
        $config = array();
		$per_page = 5;  //number of items to be displayed per page
        $uri_segment = 4;  //pagination segment id
		$config["base_url"] = base_url('homework_parent/homework/'.$class_id);
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
        $data['class'] = $class;
		$this->load->view('parent/homework/homework', $data);
		$this->parent_footer();
	}
	

	
	public function view_homework($homework_id) { 
		//check homework exists in this school
		$this->check_school_data_exists(school_id, $homework_id, 'id', 'homework', 'parent');
		$y = $this->homework_model->get_homework_details($homework_id);	
		$subject = $this->common_model->get_subject_details($y->subject_id)->subject;
		$page_title = 'Homework: ' . $subject;
		$this->parent_header($page_title, $page_title);
		$data['y'] = $y;
		$data['subject'] = $subject;
		$data['class'] = $this->common_model->get_class_details($y->class_id)->class;
		$this->load->view('parent/homework/view_homework', $data); 
		$this->parent_footer();
	}
	








}



