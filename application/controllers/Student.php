<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Student
Role: Controller
Description: Student Class controls access to all student pages and functions
Model: Student_model
Author: Nwankwo Ikemefuna
Date Created: 25th August, 2018
*/


class Student extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->student_restricted(); //allow only logged in users to access this class
		$this->load->model('student_model');
		$this->load->model('fees_model_student');
		$this->student_details = $this->common_model->get_student_details_by_reg_id($this->session->student_reg_id);
		//get school id
		$this->school_id = $this->student_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		//ensure user login and current module are covered by current plan
		$this->login_module_restricted(school_id, mod_student_login, mod_core, 'student'); 
		//ensure school account is activated
		$this->activation_restricted_student(school_id); 

		//module-level scripts
		$this->student_module_scripts = array('s_student');
	}


	
	/* ====== Dashboard ====== */
	
	public function index() { //student dashboard, routed as student
		$this->student_header('Student', 'Dashboard');
		$class_id = $this->student_details->class_id;
		$class_teacher = $this->common_model->get_class_teacher_name($class_id);
		$session = current_session_slug;
		$term = current_term;
		$current_term_fees_due_date = $this->common_model->get_term_info(school_id)->current_term_fees_due_date;
		$current_term_fees_due_date = x_date_full($current_term_fees_due_date);
		$data['student_name'] = $this->common_model->get_student_fullname($this->student_details->id);
		$data['class'] = $this->common_model->get_class_details($this->student_details->class_id)->class;
		$data['class_teacher'] = $class_teacher;
		$data['y'] = $this->student_details;
		$data['total_fees'] = $this->fees_model_student->get_total_fees_payable();
		$data['payment_status'] = $this->fees_model_student->payment_status();
		$data['amount_paid'] = $this->fees_model_student->fees_amount_paid();
		$data['balance'] = $this->fees_model_student->fees_balance();
		$data['date_paid'] = $this->fees_model_student->get_fees_date_paid();
		$data['transaction_id'] = $this->fees_model_student->get_fee_transaction_id();
		$data['current_term_fees_due_date'] = $current_term_fees_due_date;
		$this->load->view('student/dashboard/dashboard', $data); 
		$this->student_footer();
	}


	public function restricted_access() { //restricted access page
		$this->student_header('Error: Restricted Access', 'Error: Restricted Access');
		$this->load->view('shared/errors/restricted_access');
		$this->student_footer();
	}


	public function send_quick_mail_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			echo validation_errors();
		} else {
			$this->student_model->send_quick_mail();
			echo 1;	//indicator of success, will be used to check status in javascript
		}
	}



	/* ====== Attendance ====== */
	public function attendance() { 
		$this->student_header('Attendance', 'My Attendance');
		$session = current_session_slug;
		$term = current_term;
		$class_id = $this->student_details->class_id;
		$student_id = $this->student_details->id;
		$data['attendance'] = $this->student_model->get_student_attendance($session, $term, $class_id, $student_id);
		$data['att_present'] = $this->common_model->get_attendance_present($session, $term, $class_id, $student_id);
		$data['att_absent'] = $this->common_model->get_attendance_absent($session, $term, $class_id, $student_id);
		$data['att_total'] = $this->common_model->get_attendance_total($session, $term, $class_id, $student_id);
		$this->load->view('student/attendance/attendance', $data); 
		$this->student_footer();
	}


	public function check_attendance_ajax() { 
		//set validation rules
		$this->form_validation->set_rules('date', 'Date', 'trim|required');

		if ($this->form_validation->run() == FALSE) {

			$errors = array(
				'validation_status' => 0,
				'validation_errors' => validation_errors(),
			);
			echo json_encode($errors);

		} else {

			$date = $this->input->post('date', TRUE);
			$query = $this->student_model->check_attendance($date);
			
			if ($query) {

				$session = get_the_session($query->session);
				$term = $query->term;
				$term = $query->term;
				$class = $this->common_model->get_class_details($query->class_id)->class;
				$date = x_date_full($query->date);
				$status = ($query->status == 'Present') ? '<b class="text-success">Present</b>' : '<b class="text-danger">Absent</b>';

				$json_data = array(
					'att_exists' => 'true',
					'session' => $session,
					'term' => $term,
					'class' => $class,
					'date' => $date,
					'status' => $status,
				);
				echo json_encode($json_data);

			} else {

				$json_data = array(
					'att_exists' => 'false',
					'message' => "No attendance data for selected date",
				);
				echo json_encode($json_data);

			}
		}
	}



	/* ====== Student Class ====== */
	public function student_class() {
		$class_id = $this->student_details->class_id;
		$class_details = $this->common_model->get_class_details($class_id);
		$page_title = 'My Class: ' . $class_details->class;
		$this->student_header($page_title, $page_title);	
		$data['y'] = $class_details;
		$this->load->view('student/class/student_class', $data);
        $this->student_footer();
	}
	
	
	public function student_class_ajax() {
		$this->load->model('ajax/student/students/student_class_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		$count = 1;
		foreach ($list as $y) {
			$row = array();	
			$row[] = $count;
			$row[] = $this->common_model->student_passport($y->id);
			$row[] = $this->common_model->get_student_fullname($y->id);
			$data[] = $row;
			$count++;
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



	/* ====== Fee Info ====== */
	public function school_fees() {
		$this->student_header('School Fees', 'My School Fees');
		$class_id = $this->student_details->class_id;
		$session = current_session_slug;
		$term = current_term;
		$current_term_fees_due_date = $this->common_model->get_term_info(school_id)->current_term_fees_due_date;
		$current_term_fees_due_date = x_date_full($current_term_fees_due_date);
		$payment_details = $this->fees_model_student->get_payment_details();
		$data['total_fees'] = $this->fees_model_student->get_total_fees_payable();
		$data['payment_status'] = $this->fees_model_student->payment_status();
		$data['amount_paid'] = $this->fees_model_student->fees_amount_paid();
		$data['balance'] = $this->fees_model_student->fees_balance();
		$data['date_paid'] = $this->fees_model_student->get_fees_date_paid();
		$data['transaction_id'] = $this->fees_model_student->get_fee_transaction_id();
		$data['last_installment'] = $this->fees_model_student->get_fee_last_installment();
		$data['single_installment_details'] = $this->fees_model_student->get_installment_details();
		$data['current_term_fees_due_date'] = $current_term_fees_due_date;
		$this->load->view('student/fees/school_fees', $data); 
		$this->student_footer();
	}





	/* ====== Profile ====== */
	public function profile() {
		$this->student_header('Profile', 'My Profile');	
		$parent_id = $this->student_details->parent_id;
		$parent_details = $this->common_model->get_parent_details_by_id($parent_id);
		$data['student_name'] = $this->common_model->get_student_fullname($this->student_details->id);
		$data['class'] = $this->common_model->get_class_details($this->student_details->class_id)->class;
		$data['y'] = $this->student_details;
		$data['p'] = $parent_details;
		$this->load->view('student/profile/profile', $data);
        $this->student_footer();
	}
	



	/* ====== School Info ====== */
	public function school_info() {
		$this->student_header('School Info', 'School Info');	
		$data['y'] = $this->common_model->get_school_info(school_id);
		$this->load->view('student/info/school_info', $data);
        $this->student_footer();
	}
	
	
	
	
}