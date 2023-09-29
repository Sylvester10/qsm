<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Homework_student
Role: Controller
Description: Homework_student Class controls student attendance functions in the students panel
Model: Homework_model
Author: Nwankwo Ikemefuna
Date Created: 27th August, 2018
*/



class Homework_student extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->student_restricted(); //allow only logged in users to access this class	
		$this->load->model('homework_model');
		$this->student_details = $this->common_model->get_student_details_by_reg_id($this->session->student_reg_id);
		//get school id
		$this->school_id = $this->student_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		//ensure user login and current module are covered by current plan
		$this->login_module_restricted(school_id, mod_student_login, mod_homework, 'student'); 
		//ensure school account is activated
		$this->activation_restricted_student(school_id); 

		//module-level scripts
		$this->student_module_scripts = array();
	}





	public function homework() {
		$this->student_header('Homework', 'My Homework');	
		$class_id = $this->student_details->class_id;
		$class = $this->common_model->get_class_details($class_id)->class;
		//config for pagination
        $config = array();
		$per_page = 5;  //number of items to be displayed per page
        $uri_segment = 3;  //pagination segment id
		$config["base_url"] = base_url('homework_student/homework');
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
		$this->load->view('student/homework/homework', $data);
		$this->student_footer();
	}
	

	
	public function view_homework($homework_id) { 
		//check homework exists in this school
		$this->check_school_data_exists(school_id, $homework_id, 'id', 'homework', 'student');
		$y = $this->homework_model->get_homework_details($homework_id);	
		$subject = $this->common_model->get_subject_details($y->subject_id)->subject;
		$page_title = 'Homework: ' . $subject;
		$this->student_header($page_title, $page_title);
		$data['y'] = $y;
		$data['subject'] = $subject;
		$this->load->view('student/homework/view_homework', $data); 
		$this->student_footer();
	}
	








}



