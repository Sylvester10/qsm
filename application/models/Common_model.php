<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== */
/*
Name: Common_model
Role: Model
Description: Controls DB queries shared by other models and controllers
Controller: All
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class Common_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}



	//Session Control
	public function admin_restricted() {
		//check admin's session
		if ($this->session->admin_loggedin) {
			return TRUE;
		} else {
			$this->session->set_flashdata('login_msg_error', 'Your session has expired. Please login again.');
			redirect(site_url('login'));
		}
	}



	/* ===== Last Login ===== */
	public function update_last_login($user_id, $table) {
		$data = array (
			'last_login' => date('Y-m-d H:i:s'), //curent timestamp	 
		);		 
        $this->db->where('id', $user_id);			
		return $this->db->update($table, $data);
	}


	public function get_last_login_stats($period, $period_type, $table) {
		$period_type = strtoupper($period_type);
		$where = 	"last_login IS NOT NULL AND 
					last_login > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$period} {$period_type})";
	    $this->db->where($where);
		$query = $this->db->get($table)->num_rows();
		return $query;	
	}
	




	/* =================== Plan ====================== */
	public function get_plan_details($plan_id) { //Lite Plan: id = 1
		return $this->db->get_where('plans', array('id' => $plan_id))->row();
	}


	public function get_plans() { 
		$this->db->order_by('id', 'asc');
		return $this->db->get('plans')->result();
	}


	public function get_plan_price_by_location($plan_id) {
		$p = $this->get_plan_details($plan_id);
		$price_naira = number_format($p->price_naira, 2);
		$price_dollar = number_format($p->price_dollar, 2);
		$location = ip_info_safe("Visitor", "Country");
		if ($location == 'Nigeria') {
			$price = '&#8358;' . $price_naira . ' /yr.'; //Nigerian Naira currency
		} else {
			$price = '&#36;' . $price_dollar . ' /yr.'; //Dollar currency
		}
		return $price; 
	}


	public function get_plan_price_digit_by_location($plan_id) { //just price digit without currency symbol
		//required where currency and digit do not have the same styling, etc
		$p = $this->get_plan_details($plan_id);
		$price_naira = number_format($p->price_naira, 2);
		$price_dollar = number_format($p->price_dollar, 2);
		$location = ip_info_safe("Visitor", "Country");
		if ($location == 'Nigeria') {
			$price = $price_naira; //Nigerian Naira currency
		} else {
			$price = $price_dollar; //Dollar currency 
		}
		return $price; 
	}


	public function get_plan_price_digit_by_location_no_format($plan_id) { //just price, no currency, no format: for online pay
		$p = $this->get_plan_details($plan_id);
		$price_naira = $p->price_naira;
		$price_dollar = $p->price_dollar;
		$location = ip_info_safe("Visitor", "Country");
		if ($location == 'Nigeria') {
			$price = $price_naira; //Nigerian Naira currency
		} else {
			$price = $price_dollar; //Dollar currency 
		}
		return $price; 
	}


	public function get_currency_by_location() { //just currency symbol without price digit 
		$location = ip_info_safe("Visitor", "Country");
		if ($location == 'Nigeria') {
			$currency = '&#8358;'; //Nigerian Naira currency
		} else {
			$currency = '&#36;'; //Dollar currency
		}
		return $currency; 
	}
	
	
	public function get_currency_letter_by_location() { //3-letter currency
		$location = ip_info_safe("Visitor", "Country");
		if ($location == 'Nigeria') {
			$currency = 'NGN'; //Nigerian Naira currency
		} else {
			$currency = 'USD'; //Dollar currency
		}
		return $currency; 
	}


	public function get_modules() { 
		$this->db->order_by('plan_id', 'asc');
		return $this->db->get('modules');
	}


	public function get_lite_plan_modules() { //Lite Plan: id = 1
		$this->db->order_by('order_level', 'asc');
		$this->db->where('plan_id', 1);
		return $this->db->get('modules');
	}


	public function get_pro_plan_modules() { //Pro Plan: id = 2
		$this->db->order_by('order_level', 'asc');
		$this->db->where('plan_id', 2);
		$this->db->or_where('plan_id', 1); //include Lite plan modules
		return $this->db->get('modules');
	}


	public function get_pro_plus_plan_modules() { //Pro Plus Plan: id = 3
		$this->db->order_by('order_level', 'asc');
		$this->db->where('plan_id', 3);
		$this->db->or_where('plan_id', 2); //include Pro plan modules
		$this->db->or_where('plan_id', 1); //include Lite plan modules
		return $this->db->get('modules');
	}


	public function get_plan_modules($school_id) {
		$plan_id = $this->get_school_info($school_id)->plan_id;
		//check plan
		if ($plan_id == 1) { //Lite plan
			$query = $this->get_lite_plan_modules();
		} elseif ($plan_id == 2) { //Pro Plan
			$query = $this->get_pro_plan_modules();
		} elseif ($plan_id == 3) { //Pro Plus Plan
			$query = $this->get_pro_plus_plan_modules();
		} 
		return $query;
	}
	
	
	
	/* =================== Settings ====================== */
	public function get_school_info($school_id) { 
		return $this->db->get_where('school_info', array('id' => $school_id))->row();
	}


	public function get_expired_free_trial_account($school_id) {
		$free_trial_period = free_trial_period;
		//query clause: where activated is false, mode is Free Trial, and date installed is less than (today - free_trial_period days)
		$where = 	"id = '$school_id' AND 
					activated = 'false' AND 
					mode = 'Free Trial' AND 
					date_installed < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$free_trial_period} DAY)";
	    $this->db->where($where);
		$query = $this->db->get('school_info')->num_rows();
		return ($query > 0) ? TRUE : FALSE;	
	}


	public function get_about_expiring_annual_subscription($school_id) {
		//used to notify user about expiration of subscription some days leading to the d-day
		$annual_subscription_period = annual_subscription_period;
		$renewal_countdown_period = renewal_countdown_period;
		$about_expiring_period = $annual_subscription_period - $renewal_countdown_period;
		//query clause: where activated is true and date activated is less than (today - about_expiring_period days)
		$where = 	"id = '$school_id' AND 
					activated = 'true' AND 
					date_activated < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$about_expiring_period} DAY)";
	    $this->db->where($where);
		$query = $this->db->get('school_info')->num_rows();
		return ($query > 0) ? TRUE : FALSE;		
	}


	public function get_expired_annual_subscription($school_id) {
		$annual_subscription_period = annual_subscription_period;
		//query clause: where activated is true and date activated is less than (today - annual_subscription_period days)
		$where = 	"id = '$school_id' AND 
					activated = 'true' AND 
					date_activated < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$annual_subscription_period} DAY)";
	    $this->db->where($where);
		$query = $this->db->get('school_info')->num_rows();
		return ($query > 0) ? TRUE : FALSE;
	}


	public function get_school_data_records($school_id) {
		//get all tables
		$tables = $this->db->list_tables();
		$total_rows = 0;
		foreach ($tables as $table) {	
			$fields = $this->db->list_fields($table);
			//check if table has school ID column
			$has_school_id = $this->db->field_exists('school_id', $table);
			if ($has_school_id) { //yes sir
				$total_rows += $this->db->get_where($table, array('school_id' => $school_id))->num_rows();
			} 
		}
		$total_rows += 1; //plus school info table record
		return $total_rows;
    } 


	public function module_restricted($school_id, $module) {
		$query = $this->get_plan_modules($school_id)->result();
		$modules = array();
		foreach ($query as $m) {
			$modules[] = $m->module;
		}
		return ( in_array($module, $modules) ) ? TRUE : FALSE;
	}
	
	
	public function get_term_info($school_id) { 
		return $this->db->get_where('term_info', array('school_id' => $school_id))->row();
	}


	public function get_aptitude_details($id) { 
		return $this->db->get_where('aptitudes', array('id' => $id))->row();
	}


	public function get_aptitudes($school_id) { 
		$this->db->order_by('aptitude', 'asc'); //order alphabetically
		return $this->db->get_where('aptitudes', array('school_id' => $school_id))->result();
	}
	
	
	public function get_report_evaluation_by_range($school_id, $range) { 
		return $this->db->get_where('report_evaluation', array('school_id' => $school_id, 'range' => $range))->row();
	}


	public function get_report_evaluation($school_id) { 
		$this->db->order_by('range', 'desc'); //order by range
		return $this->db->get_where('report_evaluation', array('school_id' => $school_id))->result();
	}


	public function get_mid_term_report_evaluation_by_range($school_id, $range) { 
		return $this->db->get_where('mid_term_report_evaluation', array('school_id' => $school_id, 'range' => $range))->row();
	}


	public function get_mid_term_report_evaluation($school_id) { 
		$this->db->order_by('range', 'desc'); //order by range
		return $this->db->get_where('mid_term_report_evaluation', array('school_id' => $school_id))->result();
	}


	
	
	/* =================== Admins ====================== */
	public function get_admin_details($email) { //get admin info	
		return $this->db->get_where('admins', array('email' => $email))->row();
	}
	
	
	public function get_admin_details_by_id($id) { //get admin info	
		return $this->db->get_where('admins', array('id' => $id))->row();
	}
	
	
	public function get_admins($school_id) { //get admins
		return $this->db->get_where('admins', array('school_id' => $school_id))->result();
	}


	public function get_admins_by_level($school_id, $level) { //get admins by admin level
		return $this->db->get_where('admins', array('school_id' => $school_id, 'level' => $level))->result();
	}
	
	
	
	
	/* =================== Staff ====================== */
	public function get_staff_details($email) { //get client info	
		return $this->db->get_where('staff', array('email' => $email))->row();
	}
	
	
	public function get_staff_details_by_id($id) { //get admin info	
		return $this->db->get_where('staff', array('id' => $id))->row();
	}
	
	
	public function get_staff($school_id) { //get all staff
		return $this->db->get_where('staff', array('school_id' => $school_id))->result();
	}


	public function get_staff_role($role) {
		$staff_role = $this->get_staff_details($this->session->staff_email)->role;
		//explode the staff roles into an array
		$roles = explode(", ", $staff_role);
		//check if the role that has access to current module/action is in the array of staff roles
		if ( in_array($role, $roles) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}


	public function staff_passport($id) {
		$y = $this->get_staff_details_by_id($id);
		if ($y->passport != NULL) {
			return '<div class="text-center"><img class="user_passport" src="' .base_url('assets/uploads/photos/staff/'.$y->passport). '" /></div>';
		} else {
			//check student sex
			if ($y->sex == 'Male') { //male staff, show male staff avatar
				return '<div class="text-center"><img class="user_passport" src="' . male_staff_avatar . '" /></div>';
			} elseif ($y->sex == 'Female') { //female staff, show female staff avatar
				return '<div class="text-center"><img class="user_passport" src="' . female_staff_avatar . '" /></div>';
			} else {
				return '<div class="text-center"><img class="user_passport" src="' . user_avatar . '" /></div>';
			}
		}
	}
	
	
	public function get_staff_fullname($id) { 
		$y = $this->get_staff_details_by_id($id);
		$name = $y->name;
		return $name;
	}

	
	
	
	/* ===================Class Teachers ====================== */
	public function get_class_teacher_name($class_id) { //get teacher name
		$y = $this->get_class_details($class_id);
		if ($y->class_teacher_id == NULL) {
			return '<span class="text-danger">Unassigned</span>';
		} else {
			$class_teacher_id = $y->class_teacher_id;
			$title = $this->get_staff_details_by_id($class_teacher_id)->title;
			$name = $this->get_staff_details_by_id($class_teacher_id)->name;
			$class_teacher = ($title != NULL) ? "{$title} {$name}" : $name; 
			return $class_teacher;
		}
	}
	
	
	public function get_teachers($school_id) { //get all class teachers
		$this->db->order_by('name', 'asc');
		return $this->db->get_where('staff', array('school_id' => $school_id, 'designation' => 'Class Teacher'))->result();
	}


	public function get_staff_by_role($school_id, $role) { //get all class teachers
		$this->db->order_by('name', 'asc');
		$staff = $this->db->get_where('staff', array('school_id' => $school_id))->result();
		$staff_idx = array();
		foreach ($staff as $s) {
			$staff_roles = explode(", ", $s->role);
			if (in_array($role, $staff_roles)) {
				$staff_idx[] = $s->id;
			}
		}
		return $staff_idx;
	}


	public function get_assigned_class($staff_id) { //get teacher's assigned class
		$query = $this->db->get_where('classes', array('class_teacher_id' => $staff_id));
		return ($query->num_rows() > 0) ? $query->row()->class : '<span class="text-danger">Unassigned</span>';
	}





	/* ===================Subject Teachers ====================== */
	public function get_subject_teacher_details_by_id($id) { 
		return $this->db->get_where('subject_teachers', array('id' => $id))->row();
	}


	public function get_subject_teacher_details_by_staff_id($staff_id) { 
		return $this->db->get_where('subject_teachers', array('staff_id' => $staff_id))->row();
	}
	

	public function get_subject_teachers($school_id) { //get all subject teachers
		$this->db->order_by('id', 'desc');
		return $this->db->get_where('subject_teachers', array('school_id' => $school_id))->result();
	}


	public function get_subject_teacher_classes_array($staff_id) { 
		$subject_teacher = $this->get_subject_teacher_details_by_staff_id($staff_id);
		if ($subject_teacher) {
			$classes_assigned = $subject_teacher->classes_assigned;
			//ensure at least 1 class is assigned this teacher
	 		if ($classes_assigned != NULL || $classes_assigned != '') {
	 			//split classes assigned into individual classes
				$classes_array = explode(", ", $classes_assigned);
				return $classes_array;
			} 
		}
	}


	public function subject_teacher_classes_option($staff_id) { //classes
		//check if staff is a subject teacher 
		$subject_teacher = $this->get_subject_teacher_details_by_staff_id($staff_id);
		if ($subject_teacher) {
	 		$classes_assigned = $subject_teacher->classes_assigned;
	 		//ensure at least 1 class is assigned this teacher
	 		if ($classes_assigned != NULL || $classes_assigned != '') {
	 			//split classes assigned into individual classes
				$classes_array = explode(", ", $classes_assigned);
				$the_classes = "";
				foreach ($classes_array as $class_id) {
					$class_details = $this->common_model->get_class_details($class_id);
					$class = $class_details->class;
					$the_classes .= '<option value="'. $class_id . '">' . $class . '</option>';
				} 
				return $the_classes;
			} 
		}
	}


	public function get_subject_by_section($subject_id, $section_id) { 
		return $this->db->get_where('subjects', array('id' => $subject_id, 'section_id' => $section_id))->row();
	}


	public function get_subject_teacher_subjects_array_by_section($staff_id, $section_id) { 
		$subject_teacher = $this->get_subject_teacher_details_by_staff_id($staff_id);
		$subjects_assigned = $subject_teacher->subjects_assigned;
		//ensure at least 1 class is assigned this teacher
 		if ($subjects_assigned != NULL || $subjects_assigned != '') {
 			//split subjects assigned into individual subjects
			$subjects_array = explode(", ", $subjects_assigned);
			$filtered_subjects_array = array();
			foreach ($subjects_array as $subject_id) {
				//check if subject is under current section
				$query = $this->get_subject_by_section($subject_id, $section_id);
				if ($query) {
					$filtered_subjects_array[] = $subject_id;
				}
			}
			return $filtered_subjects_array;
		}
	}


	public function subject_teacher_subjects_option_by_section($staff_id, $section_id) { 
		$subject_teacher = $this->get_subject_teacher_details_by_staff_id($staff_id);
		$subjects_assigned = $subject_teacher->subjects_assigned;
		//ensure at least 1 class is assigned this teacher
 		if ($subjects_assigned != NULL || $subjects_assigned != '') {
 			//split subjects assigned into individual subjects
			$subjects_array = explode(", ", $subjects_assigned);
			$the_subjects = "";
			foreach ($subjects_array as $subject_id) {
				//check if subject is in the list of subjects for the selected section
				$query = $this->get_subject_by_section($subject_id, $section_id);
				if ($query) {
					$subject_details = $this->common_model->get_subject_details($subject_id);
					$subject = $subject_details->subject;
					$the_subjects .= '<option value="'. $subject_id . '">' . $subject . '</option>';
				} else {
					$the_subjects = NULL;
				}
			} 
			return $the_subjects;
		}
	}

	
	
	
	
	/* =================== Students ====================== */
	public function get_student_details($admission_id) { //get student info by admission id	
		return $this->db->get_where('students', array('admission_id' => $admission_id))->row();
	}
	
	
	public function get_student_details_by_reg_id($reg_id) { //get student info by reg id	
		return $this->db->get_where('students', array('reg_id' => $reg_id))->row();
	}


	public function get_student_details_by_id($id) { //get student info	by record id
		return $this->db->get_where('students', array('id' => $id))->row();
	}


	public function get_student_fullname($id) { //get student info	by record id
		$y = $this->common_model->get_student_details_by_id($id);
		$fullname = $y->last_name . ' ' . $y->first_name . ' ' . $y->other_name;
		return $fullname;
	}


	public function get_suspension_status($student_id) {
		$y = $this->get_student_details_by_id($student_id);
	    return ($y->suspended == 'false') ? '<b class="text-success">No</b>' : '<b class="text-danger">Yes</b>';
	}


	public function get_graduated_students_list($school_id) {
		$this->db->order_by('date_graduated', 'desc');
		return $this->db->get_where('students', array('school_id' => $school_id, 'graduated' => 'true'))->result();	
	}
	
	
	public function get_suspended_students_list($school_id) {
		$this->db->order_by('last_name', 'asc');
		return $this->db->get_where('students', array('school_id' => $school_id, 'suspended' => 'true'))->result();	
	}
	
	
	public function get_revoked_students_list($school_id) {
		$this->db->order_by('last_name', 'asc');
		return $this->db->get_where('students', array('school_id' => $school_id, 'revoked' => 'true'))->result();	
	}


	public function get_students($school_id) { //all students
		$this->db->order_by('last_name', 'asc');
		return $this->db->get_where('students', array('school_id' => $school_id))->result();	
	}
	
	
	public function get_students_list($school_id) { //not suspended or revoked or graduated
		$this->db->order_by('last_name', 'asc');
		return $this->db->get_where('students', array('school_id' => $school_id, 'suspended' => 'false', 'revoked' => 'false', 'graduated' => 'false'))->result();	
	}


	public function get_students_list_with_suspended($school_id) { //suspended but not revoked or graduated
		//suitable for single class because suspended students technically are still part of a class and can have results, attendance processed for them.
		$this->db->order_by('last_name', 'asc');
		return $this->db->get_where('students', array('school_id' => $school_id, 'revoked' => 'false', 'graduated' => 'false'))->result();	
	}


	public function get_all_students_list($school_id) { //suspended or revoked but not graduated
		$this->db->order_by('last_name', 'asc');
		return $this->db->get_where('students', array('school_id' => $school_id, 'graduated' => 'false'))->result();	
	}


	public function get_students_list_by_class($class_id) { //not revoked, not graduated
		$this->db->order_by('last_name', 'asc');
		return $this->db->get_where('students', array('class_id' => $class_id, 'revoked' => 'false', 'graduated' => 'false'))->result();	
	}


	public function student_passport($id) {
		$y = $this->get_student_details_by_id($id);
		if ($y->passport != NULL) {
			return '<div class="text-center"><img class="user_passport" src="' .base_url('assets/uploads/photos/students/'.$y->passport). '" /></div>';
		} else {
			//check student sex
			if ($y->sex == 'Male') { //male student, show male student avatar
				return '<div class="text-center"><img class="user_passport" src="' . male_student_avatar . '" /></div>';
			} elseif ($y->sex == 'Female') { //female student, show female student avatar
				return '<div class="text-center"><img class="user_passport" src="' . female_student_avatar . '" /></div>';
			} else {
				return '<div class="text-center"><img class="user_passport" src="' . user_avatar . '" /></div>';
			}
		}
	}


	public function student_passport_alt($id) {
		$y = $this->get_student_details_by_id($id);
		if ($y->passport != NULL) {
			return '<img class="user_passport" src="' .base_url('assets/uploads/photos/students/'.$y->passport). '" />';
		} else {
			//check student sex
			if ($y->sex == 'Male') { //male student, show male student avatar
				return '<img class="user_passport" src="' . male_student_avatar . '" />';
			} elseif ($y->sex == 'Female') { //female student, show female student avatar
				return '<img class="user_passport" src="' . female_student_avatar . '" />';
			} else {
				return '<img class="user_passport" src="' . user_avatar . '" />';
			}
		}
	}


	public function get_attendance_present($session, $term, $class_id, $student_id) {
		$y = $this->common_model->get_student_details_by_id($student_id);
		$where = array(
			'student_id' => $student_id,
			'class_id' => $class_id,
			'session' => $session,
			'term' => $term,
			'status' => 'Present',
		);
		$query = $this->db->get_where('attendance', $where)->result();
		return count($query);
    } 


    public function get_attendance_absent($session, $term, $class_id, $student_id) {
		$y = $this->common_model->get_student_details_by_id($student_id);
		$where = array(
			'student_id' => $student_id,
			'class_id' => $class_id,
			'session' => $session,
			'term' => $term,
			'status' => 'Absent',
		);
		$query = $this->db->get_where('attendance', $where)->result();
		return count($query);
    } 


    public function get_attendance_total($session, $term, $class_id, $student_id) {
		$att_present = $this->get_attendance_present($session, $term, $class_id, $student_id);
		$att_absent = $this->get_attendance_absent($session, $term, $class_id, $student_id);
		$att_total = $att_present + $att_absent;
		return $att_total;
    } 

	


	
	/* =================== Parents ====================== */
	public function get_parent_details($email) { //parent details by email
		return $this->db->get_where('parents', array('email' => $email))->row();
	}


	public function get_parent_details_by_id($parent_id) { //parent details by ID
		return $this->db->get_where('parents', array('id' => $parent_id))->row();
	}


	public function get_parents($school_id) { //parents
		return $this->db->get_where('parents', array('school_id' => $school_id))->result();
	}


	public function get_parent_children($parent_id) { 
		return $this->db->get_where('students', array('parent_id' => $parent_id))->result();
	}


	public function parent_children_option_select($parent_id) { 
		$children = $this->get_parent_children($parent_id);
		$the_children ="";
		foreach ($children as $c) {
			$child_name = $this->get_student_fullname($c->id);
			$the_children .= '<option value="'. $c->id . '">' . $child_name . '</option>';
		} 
		return $the_children;
	}
	
	
	public function get_parent_email_notif_details($parent_id) { 
		return $this->db->get_where('parent_email_notifs', array('parent_id' => $parent_id))->row();
	}
	
	
	
	
	/* =================== Classes  ====================== */
	public function get_class_details($id) { //get class info
		return $this->db->get_where('classes', array('id' => $id))->row();
	}


	public function get_class_details_by_class($school_id, $class) { //get class info
		return $this->db->get_where('classes', array('school_id' => $school_id, 'class' => $class))->row();
	}
	
	
	public function get_class_details_by_slug($school_id, $slug) { //get class info
		return $this->db->get_where('classes', array('school_id' => $school_id, 'slug' => $slug))->row();
	}


	public function get_class_details_by_teacher($staff_id) { //get class info by class teacher's id
		return $this->db->get_where('classes', array('class_teacher_id' => $staff_id))->row();
	}
	
	
	public function get_class_population($class_id) { //count number of students in a class
		return $this->db->get_where('students', array('class_id' => $class_id, 'revoked' => 'false', 'graduated' => 'false'))->num_rows();
	}
	
	
	public function get_classes($school_id) { //classes
		$this->db->order_by('order_level', 'asc');
		return $this->db->get_where('classes', array('school_id' => $school_id))->result();
	}
	
	
	public function classes_option($school_id) { //classes in select box
		$classes = $this->get_classes($school_id);
		$the_classes = "";
		foreach ($classes as $c) {
			$the_classes .= '<option value="'. $c->id . '">' . $c->class . '</option>';
		} 
		return $the_classes;
	}
	
	
	public function get_classes_by_section($school_id, $section_id) { 
		$this->db->order_by('order_level', 'asc');
		return $this->db->get_where('classes', array('school_id' => $school_id, 'section_id' => $section_id))->result();
	}
	
	
	public function classes_option_by_section($school_id, $section_id) {
		$classes = $this->get_classes_by_section($school_id, $section_id);
		$the_classes = "";
		foreach ($classes as $c) {
			$the_classes .= '<option value="' . $c->id . '">' . $c->class . '</option>';
		} 
		return $the_classes;
	}


	public function classes_option_by_section_group($school_id) { //classes in select box, grouped by section	
		$sections = $this->get_sections($school_id);
		$the_classes_group = "";
		foreach ($sections as $s) {
			$the_classes_group .= 	'<optgroup label="' . $s->section . ' Section">'
									. $this->classes_option_by_section($school_id, $s->id) .
								'</optgroup>';
		} 
		return $the_classes_group;
	}


	
	/* =================== Sections ====================== */
	public function get_section_details($id) { 
		return $this->db->get_where('sections', array('id' => $id))->row();
	}
	
	
	public function get_section_details_by_section($section) { 
		return $this->db->get_where('sections', array('section' => $section))->row();
	}


	public function get_sections($school_id) { //get sections
		$this->db->order_by('level', 'asc');
		return $this->db->get_where('sections', array('school_id' => $school_id))->result();
	}


	public function count_section_classes($school_id, $section_id) { //count number of classes in a section
		return $this->db->get_where('classes', array('school_id' => $school_id, 'section_id' => $section_id))->num_rows();
	}
	
	
	public function count_section_subjects($school_id, $section_id) { //count number of subjects in a section
		return $this->db->get_where('subjects', array('school_id' => $school_id, 'section_id' => $section_id))->num_rows();
	}
	
	
	
	
	
	/* =================== Subjects ====================== */
	public function get_subject_details($subject_id) { //subject details
		return $this->db->get_where('subjects', array('id' => $subject_id))->row();
	}


	public function get_subjects($school_id) { //subjects
		$this->db->order_by('subject', 'asc');
		return $this->db->get_where('subjects', array('school_id' => $school_id))->result();
	}


	public function get_ungrouped_subject($school_id) { //ungrouped subjects
		$this->db->order_by('subject', 'asc');
		return $this->db->get_where('subjects', array('school_id' => $school_id, 'subject_group_id' => NULL))->result();
	}


	public function subjects_option($school_id) {
		$subjects = $this->get_subjects($school_id);
		$the_subjects = "";
		foreach ($subjects as $s) {
			$the_subjects .= '<option value="' . $s->id . '">' . $s->subject . '</option>';
		} 
		return $the_subjects;
	}


	public function get_subjects_by_subject_group($school_id, $subject_group_id) { 
		$this->db->order_by('subject', 'asc');
		return $this->db->get_where('subjects', array('school_id' => $school_id, 'subject_group_id' => $subject_group_id))->result();
	}


	public function subjects_option_by_subject_group($school_id, $subject_group_id) {
		$subjects = $this->get_subjects_by_subject_group($school_id, $subject_group_id);
		$the_subjects = "";
		foreach ($subjects as $s) {
			$the_subjects .= '<option value="' . $s->id . '">' . $s->subject . '</option>';
		} 
		return $the_subjects;
	}
	
	
	public function group_subjects_option_by_subject_group($school_id) { //subjects in select box, grouped by section	
		$subject_groups = $this->get_subject_groups($school_id);
		$the_subjects_group = "";
		foreach ($subject_groups as $s) {
			$the_subjects_group .= 	'<optgroup label="' . $s->subject_group . ' Group">'
										. $this->subjects_option_by_subject_group($school_id, $s->id) .
									'</optgroup>';
		} 
		return $the_subjects_group;
	}
	
	
	
	public function get_subjects_by_section($school_id, $section_id) { 
		$this->db->order_by('subject', 'asc');
		return $this->db->get_where('subjects', array('school_id' => $school_id, 'section_id' => $section_id))->result();
	}
	
	
	public function subjects_option_by_section($school_id, $section_id) {
		$subjects = $this->get_subjects_by_section($school_id, $section_id);
		$the_subjects = "";
		foreach ($subjects as $s) {
			$the_subjects .= '<option value="' . $s->id . '">' . $s->subject . '</option>';
		} 
		return $the_subjects;
	}
	
	
	public function subjects_option_by_section_group($school_id) { //subjects in select box, grouped by section	
		$sections = $this->get_sections($school_id);
		$the_sections_group = "";
		foreach ($sections as $s) {
			$the_sections_group .= 	'<optgroup label="' . $s->section . ' Section">'
										. $this->subjects_option_by_section($school_id, $s->id) .
									'</optgroup>';
		} 
		return $the_sections_group;
	}



	/* =================== Subjects Groups====================== */
	public function get_subject_group_details($subject_group_id) {
		return $this->db->get_where('subject_groups', array('id' => $subject_group_id))->row();
	}


	public function get_subject_groups($school_id) { 
		$this->db->order_by('subject_group', 'asc');
		return $this->db->get_where('subject_groups', array('school_id' => $school_id))->result();
	}


	public function subject_group_options($school_id, $selected_sg_id = NULL) {
		$subject_groups = $this->get_subject_groups($school_id);
		$the_subject_groups = "";
		foreach ($subject_groups as $s) {
			$selected = ($s->id == $selected_sg_id) ? 'selected' : NULL;
			$the_subject_groups .= '<option ' . $selected . ' value="' . $s->id . '">' . $s->subject_group . '</option>';
		} 
		return $the_subject_groups;
	}


	

	
	
	/* =================== Notifications ====================== */
	public function notify_user($school_id, $user_id, $subject, $message, $table) {
		$data = array (
			'school_id' => $school_id,
			'user_id' => $user_id,
			'subject' => $subject,
			'message' => $message,
		);		 
        return $this->db->insert($table, $data);
    } 






    /* =================== Super/Vendor Admins ====================== */
    public function get_super_admin_details($email) { 
		return $this->db->get_where('super_admins', array('email' => $email))->row();
	}
	
	
	public function get_super_admin_details_by_id($id) { 
		return $this->db->get_where('super_admins', array('id' => $id))->row();
	}


    public function get_super_admins() { //get admins
		return $this->db->get_where('super_admins')->result();
	}


	public function get_super_admins_by_level($level) { //get admins by admin level
		return $this->db->get_where('super_admins', array('level' => $level))->result();
	}

	
	
	
	
	
}