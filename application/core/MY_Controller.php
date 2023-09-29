<?php


/* ===== Documentation ===== 
Name: MY_Controller
Role: Core (super) Controller
Description: MY_Controller Class is the super class that holds global info accessible to the regular controller and model classes. The headers and footers for Site, Admin and Staff are created here. Database, libraries and helpers used by the app are loaded here. This class extends the main CI controller, and at such, every other controller inherits it.
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class MY_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->database(); 
		$this->load->dbutil(); //database utility
		$this->load->library('form_validation'); 
		$this->load->library('email');
		$this->load->library('session');
		$this->load->library('pagination');
		$this->load->library('user_agent');
		$this->load->library('excel'); //phpExcel library
		$this->load->library('paypal_lib'); //PayPal
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('captcha');
		$this->load->helper('date');
		$this->load->helper('inflector');  
		$this->load->helper('file'); 
		$this->load->helper('download'); 
		$this->load->helper('app'); //custom helper, contains procedural functions used by the app 
		$this->load->helper('email'); //custom email helper, contains procedural functions used by the app 
		$this->load->helper('script');
		$this->load->helper('bespoke'); //bespoke helpers
		$this->load->helper('cron'); //custom cron helper, contains procedural functions used by the app 
		$this->load->model('common_model'); //general model for controllers
		$this->load->model('settings_model');
		$this->load->model('coupon_model'); //general coupon model
		require_once "application/core/constants/General.php"; //require general constants
		require_once "Modules.php"; //require Modules

		//get current controller class 
		$this->c_controller = $this->router->fetch_class();

		//initialize current user
		$this->c_user = '';

		//initialize module-level scripts
		$this->web_module_scripts = array();
		$this->site_module_scripts = array();
		$this->admin_module_scripts = array();
		$this->staff_module_scripts = array();
		$this->student_module_scripts = array();
		$this->parent_module_scripts = array();
		$this->super_admin_module_scripts = array();
		$this->shared_module_scripts = array();
		
		//set CSRF
		$this->set_csrf();
		
		//profiler
		//$this->check_profiler();
	}
	
	
	
	
	private function set_csrf() {
		//get array of controllers to be excluded
		$excluded_controllers = $this->csrf_exclude_controllers();
		//get current controller class and check if it's in the array of controllers to be excluded
		$current_class = $this->router->fetch_class();
		if ( ! in_array($current_class, $excluded_controllers) ) {
			$this->config->set_item('csrf_protection', TRUE); //allow CSRF
		} else {
			$this->config->set_item('csrf_protection', FALSE); //disable CSRF
		}
	}
	
	
	private function check_profiler() {
		if (ENVIRONMENT != 'production') { 
			//allow profiling on development and testing servers only
			$this->output->enable_profiler(TRUE);
		} else {
			$this->output->enable_profiler(FALSE);
		}
	}
	
	
	private function csrf_exclude_controllers() {
		//get array of controllers to be excluded
		$excluded_controllers = array(
			'paypal',
			'paypal_ipn',
		);
		return $excluded_controllers;
	}





	/* ===== Refresh Last Login ===== */
	private function refresh_last_login($user_id, $table) {
		//refresh last login every time loggedin user loads or reloads a page
		$this->common_model->update_last_login($user_id, $table);
	}





	/* ========== Demo Action =========== */
	public function demo_action_restricted_msg() {
		//response message for restricted demo action in ajax
		$message = "This action is not allowed in demo mode!";
		return $message;
	}


	/* ========== Admin::Demo Action: =========== */
	public function demo_action_restricted_admin() {
		//check if user is demo user
		$demo_user = $this->common_model->get_admin_details($this->session->admin_email)->demo_user;

		if ($demo_user != 'true') { //regular account user
			return TRUE;
		} else { //demo user
			//check if user is super user
			$super_user = $this->session->demo_super_user_admin;
			if ($super_user) {
				return TRUE;
			} else { 
				$this->session->set_flashdata('status_msg_error', 'The action you attempted is not allowed in demo mode.');
				redirect($this->agent->referrer());
			}
		}
	}


	/* ========== Admin::Demo Action Ajax =========== */
	public function demo_action_restricted_admin_ajax() {
		//check if user is demo user
		$demo_user = $this->common_model->get_admin_details($this->session->admin_email)->demo_user;

		if ($demo_user != 'true') { //regular account user
			return TRUE;
		} else { //demo user
			//check if user is super user
			$super_user = $this->session->demo_super_user_admin;
			if ($super_user) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}



	/* ========== Staff::Demo Action =========== */
	public function demo_action_restricted_staff() {
		//check if user is demo user
		$demo_user = $this->common_model->get_staff_details($this->session->staff_email)->demo_user;

		if ($demo_user != 'true') { //regular account user
			return TRUE;
		} else { //demo user
			//check if user is super user
			$super_user = $this->session->demo_super_user_admin;
			if ($super_user) {
				return TRUE;
			} else { 
				$this->session->set_flashdata('status_msg_error', 'The action you attempted is not allowed in demo mode.');
				redirect($this->agent->referrer());
			}
		}
	}


	/* ========== Staff::Demo Action: Ajax =========== */
	public function demo_action_restricted_admin_staff() {
		//check if user is demo user
		$demo_user = $this->common_model->get_staff_details($this->session->staff_email)->demo_user;

		if ($demo_user != 'true') { //regular account user
			return TRUE;
		} else { //demo user
			//check if user is super user
			$super_user = $this->session->demo_super_user_admin;
			if ($super_user) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}





	/* ===== Website layout===== */
	public function home_header($title) {
		$data['title'] = $title;
		return $this->load->view('web/layout/home_header', $data);
	}


	public function web_header($title) {
		$data['title'] = $title;
		return $this->load->view('web/layout/web_header', $data);
	}
	
	
	public function web_footer() {
		$data['captcha_code'] = mt_rand(111111, 999999);
		return $this->load->view('web/layout/web_footer', $data);
	}


	
    
	/* ===== Site layout===== */
	public function site_header($title) {
		$data['title'] = $title;
		return $this->load->view('site/layout/site_header', $data);
	}
	
	
	public function site_footer() {
		return $this->load->view('site/layout/site_footer');
	}


	private function set_user_login_request_data($user) {
		//create a session to hold the current requested page
		$login_redirect = 'login_redirect_'.$user;
		$requested_page = 'requested_page_'.$user;
		$request_data = array(
			$login_redirect => TRUE,
			$requested_page => current_url()
		);
		$this->session->set_userdata($request_data);
	}


	private function unset_user_login_request_data($user) {
		//create a session to hold the current requested page
		$login_redirect = 'login_redirect_'.$user;
		$requested_page = 'requested_page_'.$user;
		$request_data = array($login_redirect, $requested_page);
		$this->session->unset_userdata($request_data);
	}







	/* ===== Super Admin Layout===== */
	public function super_admin_header($title, $inner_page_title) {
		$super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);

		//refresh last login
		$this->refresh_last_login($super_admin_details->id, 'super_admins'); 

		//unset user login request data
		$this->unset_user_login_request_data('super_admin');

		$data['title'] = $title;
		$data['inner_page_title'] = $inner_page_title;
		$data['super_admin_details'] = $super_admin_details;
		$data['super_admin_fname'] = get_firstname($super_admin_details->name);
		return $this->load->view('super_admin/layout/header', $data);
	}
	 
	
	public function super_admin_footer() {
		return $this->load->view('super_admin/layout/footer');
	}
	
	
	public function super_admin_restricted() {
		//check admin's session
		if ($this->session->super_admin_loggedin) {
			return TRUE;
		} else {
			//create a session to hold the current requested page
			$this->set_user_login_request_data('super_admin');
			//redirect to login page
			$this->session->set_flashdata('login_msg_error', 'Your session has expired. Please login again.');
			redirect(site_url('super_admin_login'));
		}
	}


	

	/* ========== Users =========== */
	

	/* ===== Admin layout===== */
	public function admin_header($title, $inner_page_title) {
		$this->load->model('publications_super_admin_model');
		$this->load->model('publications_model');
		$this->load->model('admin_model');
		$admin_details = $this->common_model->get_admin_details($this->session->admin_email);

		//refresh last login
		$this->refresh_last_login($admin_details->id, 'admins'); 

		//unset user login request data
		$this->unset_user_login_request_data('admin');

		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants

		//automatically delete school free trial coupon on expire 
		$this->coupon_model->delete_school_free_trial_coupon_on_expire($this->school_id);
		
		$date_installed = date_installed;
		$date_activated = date_activated;
		$logout_url = ($this->session->demo_admin_loggedin) ? 'demo/admin_logout' : 'admin_logout';
		$data['title'] = $title;
		$data['inner_page_title'] = $inner_page_title;
		$data['admin_details'] = $admin_details;
		$data['logout_url'] = $logout_url;
		$data['admin_fname'] = get_firstname($admin_details->name);
		$data['expired_free_trial_account'] = $this->common_model->get_expired_free_trial_account($this->school_id);
		$data['about_expiring_annual_subscription'] = $this->common_model->get_about_expiring_annual_subscription($this->school_id);
		$data['expired_annual_subscription'] = $this->common_model->get_expired_annual_subscription($this->school_id);
		$data['free_trial_remaining_time'] = get_free_trial_remaining_time($date_installed);
		$data['subscription_remaining_time'] = get_annual_subscription_remaining_time($date_activated);
		$data['unread_notifs'] = $this->admin_model->get_unread_notifs();
		$data['total_unread_notifs'] = $this->admin_model->count_unread_notifs();
		$data['vendor_announcement'] = $this->publications_super_admin_model->get_published_announcement();
		$data['general_announcement'] = $this->publications_model->get_published_general_announcement();
		$data['staff_announcement'] = $this->publications_model->get_published_staff_announcement();
		return $this->load->view('admin/layout/header', $data);
	}
	
	
	public function admin_footer() {
		return $this->load->view('admin/layout/footer');
	}
	
	
	public function admin_restricted() {
		//check admin's session
		if ($this->session->admin_loggedin) {
			return TRUE;
		} else {
			//create a session to hold the current requested page
			$this->set_user_login_request_data('admin');
			$this->session->set_flashdata('login_msg_error', 'Your session has expired. Please login again.');
			//redirect to demo login page if demo user, else, redirect to normal login page
			$login_url = ($this->session->demo_admin_loggedin) ? 'demo/login' : 'login';
			redirect(site_url($login_url));
		}
	}


	/* ===== Restrict access to sensitive Admin modules depending on Admin's clearance level ===== */
	public function admin_level_1_restricted() { //level 1
		$admin_level = $this->common_model->get_admin_details($this->session->admin_email)->level;
		return ($admin_level == 1) ? TRUE : redirect(site_url('admin/restricted_access'));
	}


	/* ===== Restrict access to sensitive Admin modules depending on admin's assigned section ===== */
	public function admin_section_restricted($section_id) {
		$assigned_section = $this->common_model->get_admin_details($this->session->admin_email)->section_assigned;
		//explode the sections assigned into an array of individual section IDs
		$sections = explode(", ", $assigned_section);
		return ( in_array($section_id, $sections) ) ? TRUE : redirect(site_url('admin/restricted_access'));
	}






	/* ===== Staff layout===== */
	public function staff_header($title, $inner_page_title) {
		$this->load->model('staff_model');
		$this->load->model('publications_model');
		$staff_details = $this->common_model->get_staff_details($this->session->staff_email);

		//refresh last login
		$this->refresh_last_login($staff_details->id, 'staff'); 

		//unset user login request data
		$this->unset_user_login_request_data('staff');

		//get school id
		$this->school_id = $this->staff_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants

		$logout_url = ($this->session->demo_staff_loggedin) ? 'demo/staff_logout' : 'staff_logout';
		$data['title'] = $title;
		$data['inner_page_title'] = $inner_page_title;
		$data['staff_details'] = $staff_details;
		$data['staff_id'] = $staff_details->id;
		$data['logout_url'] = $logout_url;
		$data['staff_fname'] = get_firstname($staff_details->name);
		$data['unread_notifs'] = $this->staff_model->get_unread_notifs();
		$data['total_unread_notifs'] = $this->staff_model->count_unread_notifs();
		$data['general_announcement'] = $this->publications_model->get_published_general_announcement();
		$data['staff_announcement'] = $this->publications_model->get_published_staff_announcement();
		$data['student_management_module'] = $this->common_model->module_restricted($this->school_id, mod_student_management);
		$data['student_reports_module'] = $this->common_model->module_restricted($this->school_id, mod_student_reports);
		$data['homework_module'] = $this->common_model->module_restricted($this->school_id, mod_homework);
		$data['timetable_module'] = $this->common_model->module_restricted($this->school_id, mod_timetable);
		$data['requisitions_module'] = $this->common_model->module_restricted($this->school_id, mod_requisitions);
		$data['weekly_reports_module'] = $this->common_model->module_restricted($this->school_id, mod_weekly_reports);
		$data['fee_management_module'] = $this->common_model->module_restricted($this->school_id, mod_fee_management);
		$data['school_library_module'] = $this->common_model->module_restricted($this->school_id, mod_school_library);
		$data['publication_management_module'] = $this->common_model->module_restricted($this->school_id, mod_publication_management);
		return $this->load->view('staff/layout/header', $data);
	}
	
	
	public function staff_footer() {
		return $this->load->view('staff/layout/footer');
	}
	
	
	public function staff_restricted() {
		//check staff's session
		if ($this->session->staff_loggedin) {
			return TRUE;
		} else {
			//create a session to hold the current requested page
			$this->set_user_login_request_data('staff');
			$this->session->set_flashdata('login_msg_error', 'Your session has expired. Please login again.');
			//redirect to demo login page if demo user, else, redirect to normal login page
			$login_url = ($this->session->demo_staff_loggedin) ? 'demo/login' : 'login';
			redirect(site_url($login_url));
		}
	}
	
	
	/* ===== Restrict access to sensitive Staff modules depending on Staff's role ===== */
	public function staff_role_restricted($role) {
		$staff_role = $this->common_model->get_staff_details($this->session->staff_email)->role;
		//explode the staff roles into an array
		$roles = explode(", ", $staff_role);
		//check if the role that has access to current module is in the array of staff roles
		return ( in_array($role, $roles) ) ? TRUE : redirect(site_url('staff/restricted_access'));
	}
		
	

	public function check_staff_is_class_teacher() { //ensure staff is currently assigned to a class
		$staff_details = $this->common_model->get_staff_details($this->session->staff_email);
		$staff_id = $staff_details->id;
		$is_assigned = $this->common_model->get_class_details_by_teacher($staff_id);
		return ($is_assigned) ? TRUE : redirect(site_url('staff/restricted_access'));  
	}


	public function check_staff_is_subject_teacher() { //ensure staff is currently assigned to a class
		$staff_details = $this->common_model->get_staff_details($this->session->staff_email);
		$staff_id = $staff_details->id;
		$subject_teacher = $this->common_model->get_subject_teacher_details_by_staff_id($staff_id);
		//ensure subject teacher has been assigned at least a class and subject
		if ( $subject_teacher && ($subject_teacher->classes_assigned != NULL && $subject_teacher->subjects_assigned != NULL) ) {
			return TRUE;
		} else {
			redirect(site_url('staff/restricted_access'));  
		} 
	}				







	/* ===== Student Layout===== */
	public function student_header($title, $inner_page_title) {
		$this->load->model('publications_model');
		$student_details = $this->common_model->get_student_details_by_reg_id($this->session->student_reg_id);

		//refresh last login
		$this->refresh_last_login($student_details->id, 'students'); 

		//unset user login request data
		$this->unset_user_login_request_data('student');

		$logout_url = ($this->session->demo_student_loggedin) ? 'demo/student_logout' : 'student_logout';
		$data['title'] = $title;
		$data['inner_page_title'] = $inner_page_title;
		$data['student_details'] = $student_details;
		$data['logout_url'] = $logout_url;
		$data['student_fullname'] = $this->common_model->get_student_fullname($student_details->id);
		$data['student_class'] = $this->common_model->get_class_details($student_details->class_id)->class;
		$data['general_announcement'] = $this->publications_model->get_published_general_announcement();
		$data['student_reports_module'] = $this->common_model->module_restricted($this->school_id, mod_student_reports);
		$data['homework_module'] = $this->common_model->module_restricted($this->school_id, mod_homework);
		$data['timetable_module'] = $this->common_model->module_restricted($this->school_id, mod_timetable);
		$data['fee_management_module'] = $this->common_model->module_restricted($this->school_id, mod_fee_management);
		$data['publication_management_module'] = $this->common_model->module_restricted($this->school_id, mod_publication_management);
		return $this->load->view('student/layout/header', $data);
	}
	 
	
	public function student_footer() {
		return $this->load->view('student/layout/footer');
	}
	
	
	public function student_restricted() {
		//check admin's session
		if ($this->session->student_loggedin) {
			return TRUE;
		} else {
			//create a session to hold the current requested page
			$this->set_user_login_request_data('student');
			$this->session->set_flashdata('login_msg_error', 'You have been logged out. Please login again.');
			//redirect to demo login page if demo user, else, redirect to normal login page
			$login_url = ($this->session->demo_student_loggedin) ? 'demo/user_login' : 'user_login';
			redirect(site_url($login_url));
		}
	}
	
	
	
	



	/* ===== Parent layout===== */
	public function parent_header($title, $inner_page_title) {
		$this->load->model('school_parent_model');
		$this->load->model('publications_model');
		$parent_details = $this->common_model->get_parent_details($this->session->parent_email);

		//refresh last login
		$this->refresh_last_login($parent_details->id, 'parents'); 

		//unset user login request data
		$this->unset_user_login_request_data('parent');

		//get school id
		$this->school_id = $this->parent_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$children = $this->common_model->get_parent_children($parent_details->id);
		$children_count = count($children);
		//get child ID if children count == 1
		if ($children_count == 1) {
			$child_id = $this->school_parent_model->get_parent_child_details()->id;
			$child_class_id = $this->school_parent_model->get_parent_child_details()->class_id;
		} else {
			$child_id = NULL;
			$child_class_id = NULL;
		}
		$logout_url = ($this->session->demo_parent_loggedin) ? 'demo/parent_logout' : 'parent_logout';	
		$data['title'] = $title;
		$data['inner_page_title'] = $inner_page_title;
		$data['parent_details'] = $parent_details;
		$data['parent_fname'] = get_firstname($parent_details->name);
		$data['children'] = $children;
		$data['children_count'] = $children_count;
		$data['child_inflect'] = ($children_count == 1) ? 'Child' : 'Children';
		$data['child_id'] = $child_id;
		$data['child_class_id'] = $child_class_id;
		$data['logout_url'] = $logout_url;
		$data['general_announcement'] = $this->publications_model->get_published_general_announcement();
		$data['student_management_module'] = $this->common_model->module_restricted($this->school_id, mod_student_management);
		$data['student_reports_module'] = $this->common_model->module_restricted($this->school_id, mod_student_reports);
		$data['homework_module'] = $this->common_model->module_restricted($this->school_id, mod_homework);
		$data['timetable_module'] = $this->common_model->module_restricted($this->school_id, mod_timetable);
		$data['fee_management_module'] = $this->common_model->module_restricted($this->school_id, mod_fee_management);
		$data['publication_management_module'] = $this->common_model->module_restricted($this->school_id, mod_publication_management);
		return $this->load->view('parent/layout/header', $data);
	}
	
	
	public function parent_footer() {
		return $this->load->view('parent/layout/footer');
	}
	
	
	public function parent_restricted() {
		//check parent's session
		if ($this->session->parent_loggedin) {
			return TRUE;
		} else {
			//create a session to hold the current requested page
			$this->set_user_login_request_data('parent');
			$this->session->set_flashdata('login_msg_error', 'Your session has expired. Please login again.');
			//redirect to demo login page if demo user, else, redirect to normal login page
			$login_url = ($this->session->demo_admin_loggedin) ? 'demo/user_login' : 'user_login';
			redirect(site_url($login_url));
		}
	}





	


	/* ============== Module Restricted =================== */

	public function module_restricted_admin($school_id, $module) {
		$query = $this->common_model->get_plan_modules($school_id)->result();
		//get all modules as an array
		$modules = array();
		foreach ($query as $m) {
			$modules[] = $m->module;
		}
		//if module is covered by plan, return true, else redirect to restricted plan page (only admin will be redirected to restricted plan page to prompt them to updgrade plan. Modules not covered by plan will be hidden to other users except admin.)
		return ( in_array($module, $modules) ) ? TRUE : redirect(site_url('admin/restricted_plan'));
	}


	public function module_restricted_staff($school_id, $module) {
		$query = $this->common_model->get_plan_modules($school_id)->result();
		//get all modules as an array
		$modules = array();
		foreach ($query as $m) {
			$modules[] = $m->module;
		}
		//if module is covered by plan, return true, else redirect to restricted access page (only admin will be redirected to restricted plan page to prompt them to updgrade plan. Modules not covered by plan will be hidden to other users except admin.)
		return ( in_array($module, $modules) ) ? TRUE : redirect(site_url('staff/restricted_access'));
	}


	private function module_restricted_student($school_id, $module) {
		$query = $this->common_model->get_plan_modules($school_id)->result();
		//get all modules as an array
		$modules = array();
		foreach ($query as $m) {
			$modules[] = $m->module;
		}
		//if module is covered by plan, return true, else redirect to restricted access page (only admin will be redirected to restricted plan page to prompt them to updgrade plan. Modules not covered by plan will be hidden to other users except admin.)
		return ( in_array($module, $modules) ) ? TRUE : FALSE;
	}


	private function module_restricted_parent($school_id, $module) {
		$query = $this->common_model->get_plan_modules($school_id)->result();
		//get all modules as an array
		$modules = array();
		foreach ($query as $m) {
			$modules[] = $m->module;
		}
		//if module is covered by plan, return true, else redirect to restricted access page (only admin will be redirected to restricted plan page to prompt them to updgrade plan. Modules not covered by plan will be hidden to other users except admin.)
		return ( in_array($module, $modules) ) ? TRUE : FALSE;
	}


	public function login_module_restricted($school_id, $login_module, $current_module, $user) {
		$query = $this->common_model->get_plan_modules($school_id)->result();
		//get all modules as an array
		$modules = array();
		foreach ($query as $m) {
			$modules[] = $m->module;
		}
		//if module is covered by plan, return true, else redirect to restricted access page (only admin will be redirected to restricted plan page to prompt them to updgrade plan. Modules not covered by plan will be hidden to other users except admin.)

		//check user
		switch ($user) {
			case 'student':
				$current_module_restricted = $this->module_restricted_student($school_id, $current_module);
				$data = array('student_reg_id', 'student_loggedin');
				$the_user = 'Student';
				$redirect_url = 'user_login';
			break;
			case 'parent':
				$current_module_restricted = $this->module_restricted_parent($school_id, $current_module);
				$data = array('parent_email', 'parent_loggedin');
				$the_user = 'Parent';
				$redirect_url = 'user_login';
			break;
			case 'staff':
				$current_module_restricted = $this->module_restricted_staff($school_id, $current_module);
				$data = array('staff_email', 'staff_loggedin');
				$the_user = 'Staff';
				$redirect_url = 'login';
			break;
			default:
				$current_module_restricted = $this->module_restricted_admin($school_id, $current_module);
				$data = array('admin_email', 'admin_loggedin');
				$the_user = 'Admin';
				$redirect_url = 'login';
			break;
		}
		
		//if user login and current module are covered by plan, return true, else redirect to appropriate login page
		if ( in_array($login_module, $modules) && $current_module_restricted ) {

			return TRUE;

		} else { //all is not so well

			$this->session->unset_userdata($data);
			$this->session->set_flashdata('login_msg_error', "{$the_user} Login not supported.");
			redirect(site_url($redirect_url));
		}

	}






	/* ============== Activation Restricted =================== */

	public function activation_restricted_admin($school_id) {
		//Ensure account has been activated before granting access to modules
		//Allow Case 1: Account has been activated and annual subscription has not expired
		//Allow Case 2: Account has not been activated but user's mode is Free Trial and date installed < (today - free_trial_period days)
		$activated = $this->common_model->get_school_info($school_id)->activated;
		$mode = $this->common_model->get_school_info($school_id)->mode;
		$expired_free_trial = $this->common_model->get_expired_free_trial_account($school_id);
		$expired_annual_subscription = $this->common_model->get_expired_annual_subscription($school_id);
		if ( ($activated == 'true' && ! $expired_annual_subscription) || ($activated == 'false' && $mode == 'Free Trial' && ! $expired_free_trial) ) { 
			return TRUE;
		} else { 
			redirect(site_url('admin/activation_failure'));
		}
	}


	public function activation_restricted_staff($school_id) {
		//Ensure account has been activated before granting access to modules
		//Allow Case 1: Account has been activated
		//Allow Case 2: Account has not been activated but user's mode is Free Trial and date installed < (today - free_trial_period days)
		$activated = $this->common_model->get_school_info($school_id)->activated;
		$mode = $this->common_model->get_school_info($school_id)->mode;
		$expired_free_trial = $this->common_model->get_expired_free_trial_account($school_id);
		$expired_annual_subscription = $this->common_model->get_expired_annual_subscription($school_id);
		if ( ($activated == 'true' && ! $expired_annual_subscription) || ($activated == 'false' && $mode == 'Free Trial' && ! $expired_free_trial) ) { 
			return TRUE;
		} else { 
			$data = array('staff_email', 'staff_loggedin');
			$this->session->unset_userdata($data);
			$this->session->set_flashdata('login_msg_error', "Staff Login not allowed.");
			redirect(site_url('login'));
		}
	}


	public function activation_restricted_student($school_id) {
		//Ensure account has been activated before granting access to modules
		//Allow Case 1: Account has been activated
		//Allow Case 2: Account has not been activated but user's mode is Free Trial and date installed < (today - free_trial_period days)
		$activated = $this->common_model->get_school_info($school_id)->activated;
		$mode = $this->common_model->get_school_info($school_id)->mode;
		$expired_free_trial = $this->common_model->get_expired_free_trial_account($school_id);
		$expired_annual_subscription = $this->common_model->get_expired_annual_subscription($school_id);
		if ( ($activated == 'true' && ! $expired_annual_subscription) || ($activated == 'false' && $mode == 'Free Trial' && ! $expired_free_trial) ) { 
			return TRUE;
		} else { 
			$data = array('student_reg_id', 'student_loggedin');
			$this->session->unset_userdata($data);
			$this->session->set_flashdata('login_msg_error', "Student Login not allowed.");
			redirect(site_url('user_login'));
		}
	}


	public function activation_restricted_parent($school_id) {
		//Ensure account has been activated before granting access to modules
		//Allow Case 1: Account has been activated
		//Allow Case 2: Account has not been activated but user's mode is Free Trial and date installed < (today - free_trial_period days)
		$activated = $this->common_model->get_school_info($school_id)->activated;
		$mode = $this->common_model->get_school_info($school_id)->mode;
		$expired_free_trial = $this->common_model->get_expired_free_trial_account($school_id);
		$expired_annual_subscription = $this->common_model->get_expired_annual_subscription($school_id);
		if ( ($activated == 'true' && ! $expired_annual_subscription) || ($activated == 'false' && $mode == 'Free Trial' && ! $expired_free_trial) ) { 
			return TRUE;
		} else { 
			$data = array('parent_email', 'parent_loggedin');
			$this->session->unset_userdata($data);
			$this->session->set_flashdata('login_msg_error', "Parent Login not allowed.");
			redirect(site_url('user_login'));
		}
	}


	
	

	/* ===== Function to check that data exists in user's school ===== */
	public function check_school_data_exists($school_id, $data, $column, $table, $controller) { 
		$query = $this->db->get_where($table, array('school_id' => $school_id, $column => $data))->row();
		return ($query) ? TRUE : redirect(site_url($controller.'/restricted_access'));  
    }


    /* ===== Function to check that data exists ===== */
	public function check_data_exists($data, $column, $table, $redirect_url) { 
		$query = $this->db->get_where($table, array($column => $data))->row();
		return ($query) ? TRUE : redirect(site_url($redirect_url));  
    }



    /* ===== Function to check that only the creator of data can manipulate it (eg delete) ===== */
	public function restricted_user_data($creator_id, $user_id, $controller) { 
		return ($creator_id == $user_id) ? TRUE : redirect(site_url($controller.'/restricted_access'));  
    }



  	/* ===== Function to check that bespoke module is not accessed by other schools ===== */
	public function bespoke_restricted_module($school_id, $bespoke_school_id, $controller) { 
		return ($school_id == $bespoke_school_id) ? TRUE : redirect(site_url($controller.'/restricted_access'));  
    }
	


	
	
	/* ===== Print Layout ===== */
	public function print_header($title) {
		$data['title'] = $title;
		return $this->load->view('print/layout/print_header', $data);
	}
	
	
	public function print_footer() {
		return $this->load->view('print/layout/print_footer');
	}
	
	
	


	/* ===== Call-back function to check student's admission ID exists ===== */
	public function check_admissionID_exists() { 
		$admission_id = $this->input->post('admission_id', TRUE);
		$query = $this->common_model->get_student_details($admission_id);
		if($query) {
			return TRUE;
		} else {
			$this->form_validation->set_message('check_admissionID_exists', 'Admission ID not found.');
			return FALSE;
		}
	}


	
	/* ===== Call-back function for Google Recaptcha ===== */
	public function google_recaptcha($str = '') { 
		$google_url = "https://www.google.com/recaptcha/api/siteverify";
		$secret = '6LenaloUAAAAAFk68nH_aP9L-9SKyIWmcelh0ymL'; //secret key
		$ip = $_SERVER['REMOTE_ADDR'];
		$url = $google_url . "?secret=" . $secret . "&response=" . $str . "&remoteip=" . $ip;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		$res = curl_exec($curl);
		curl_close($curl);
		$res = json_decode($res, true);
		//reCaptcha success check
		if($res['success']) {
			return TRUE;
		} else {
			$this->form_validation->set_message('google_recaptcha', 'Wrong captcha response! Please ensure to check the <b>I\'m not a robot</b> box.');
			return FALSE;
		}
	}
	
	
	
}