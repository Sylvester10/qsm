<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Dev_test
Role: Controller
Description: Dev_test Class controls access to all test pages by the developer in super admin panel
Model: Dev_test_model
Author: Nwankwo Ikemefuna
Date Created: 28th December, 2018
*/



class Dev_test extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->super_admin_restricted(); //allow only logged in super admins to access this class
		$this->load->model('dev_test_model');
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);
		//enable testing
		$this->enable_testing = true;
		$this->enable_testing();

		//module-level scripts
		$this->super_admin_module_scripts = array();
	}



	private function enable_testing() {
		if ( ! $this->enable_testing) {
			show_error('Access denied!');
		}
	}


	public function index() { 
		$this->super_admin_header('Developer Tests', 'Developer Tests');
		$this->load->view('super_admin/developer/test');
		$this->super_admin_footer();
	}



}