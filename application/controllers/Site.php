<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Site
Role: Controller
Description: Site Class controls pages such as login/logout, password set, password recovery/reset for all users
Model: Site_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class Site extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('site_model');

		//module-level scripts
        $this->site_module_scripts = array('s_admin_acc', 's_staff_acc', 's_student_acc', 's_parent_acc');
	}

   
    

    
	/* ===== Shared: Admin/Staff ===== */

	public function admin_staff_login() { //route: login
		$this->site_header('Admin/Staff Login');
		$this->load->view('site/shared/admin_staff/login');
		$this->site_footer();
	}
	
	
	public function admin_staff_recover_password() { //route: recover_password
		$this->site_header('Recover Password');
		$this->load->view('site/shared/admin_staff/recover_password');
		$this->site_footer();
	}




	/* ===== Shared: Student/Parent ===== */

	public function student_parent_login() { //route: user_login
		$this->site_header('Student/Parent Login');
		$this->load->view('site/shared/student_parent/login');
		$this->site_footer();
	}
	
	
	public function student_parent_recover_password() { //route: user_recover_password
		$this->site_header('Recover Password');
		$this->load->view('site/shared/student_parent/recover_password');
		$this->site_footer();
	}
	
	
	
}