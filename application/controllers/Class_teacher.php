<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Class_teacher
Role: Controller
Description: Controls access to all students pages and functions from the class teacher's end
Model: Class_teacher_model
Author: Nwankwo Ikemefuna
Date Created: 24th June, 2018
*/


class Class_teacher extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->staff_restricted(); //allow only logged in users to access this class
		$this->load->model('class_teacher_model');
		$this->staff_role_restricted('Class Teacher'); //only staff with this role can access this module
		$this->check_staff_is_class_teacher(); //allow only class teachers who are currently assigned to a class to access this module
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
		$this->class_details = $this->common_model->get_class_details_by_teacher($this->staff_details->id);
		//get school id
		$this->school_id = $this->staff_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_staff(school_id, mod_student_management); //student management module
		$this->activation_restricted_staff(school_id); 

		//module-level scripts
		$this->staff_module_scripts = array('s_class_teacher');
	}




	/* ========== Single Class ========== */
	public function single_class() {
		$page_title = 'Class: ' . $this->class_details->class;
		$this->staff_header($page_title, $page_title);	
		$data['y'] = $this->class_details;
		$this->load->view('staff/students/class_teacher/single_class', $data);
        $this->staff_footer();
	}
	
	
	public function single_class_ajax() {
		$this->load->model('ajax/staff/students/single_class_model_ajax', 'current_model');
		$class_id = $this->class_details->id;
		$list = $this->current_model->get_records($class_id);
		$data = array();
		foreach ($list as $y) {
			$row = array();	
			$mid_term_reports_controller = 'student_mid_term_reports_class_teacher';
			$end_term_reports_controller = 'student_reports_class_teacher';
			$row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id, $mid_term_reports_controller, $end_term_reports_controller);
			$row[] = $this->common_model->student_passport($y->id);
			$row[] = $y->admission_id; 
			$row[] = $this->common_model->get_student_fullname($y->id);
			$row[] = $y->sex; 
			$row[] = $this->current_model->student_attendance_info($y->id);
			$row[] = $this->current_model->suspension_status($y->id);
			$data[] = $row;
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records($class_id),
			"recordsFiltered" => $this->current_model->count_filtered_records($class_id),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}





	/* ========== Attendance ========== */
	public function attendance() {
		$page_title = 'Attendance: ' . $this->class_details->class;
		$this->staff_header($page_title, $page_title);	
		$data['y'] = $this->class_details;
		$data['session'] = current_session_slug;
		$data['the_session'] = current_session;
		$data['term'] = current_term;
		$data['level'] = $this->class_details->level;
		$data['class_id'] = $this->class_details->id;
		$data['students'] = $this->common_model->get_students_list_by_class($this->class_details->id);
		$this->load->view('staff/attendance/attendance', $data);
        $this->staff_footer();
	}


	public function mark_attendance() {
		$this->form_validation->set_rules('date', 'Date', 'trim|required');
		$this->form_validation->set_rules('student_id[]', 'Student ID', 'trim');

		$student_id = $this->input->post('student_id', TRUE);
		$status = $this->input->post('status', TRUE);
		$date = $this->input->post('date', TRUE);
		$date = x_date($date);	

		if ($this->form_validation->run()) {

			for ($i = 0; $i < count($student_id); $i++) {
				
				//check if an item is selected, ignore otherwise
				if ( isset($status[$i]) ) {
					$id = $student_id[$i];
					$d_status = $status[$i];

					//check if student was marked absent or absent
					if ($d_status == 'Present') {

						//check if this student has been marked present for the selected date
						$query_present = $this->class_teacher_model->check_attendance_marked($id, 'Present');
						if ($query_present == 0) { //student has not been marked present for the selected date
							$this->class_teacher_model->mark_student_present($id);  //mark present
						}

					} else { //Absent

						//check if this student has been marked absent for the selected date
						$query_absent = $this->class_teacher_model->check_attendance_marked($id, 'Absent');
						if ($query_absent == 0) { //student has not been marked absent for the selected date
							$this->class_teacher_model->mark_student_absent($id);  //mark absent
						}

					}

				} 

			}
			$this->session->set_flashdata('status_msg', "Attendance marked successfully.");
			redirect('class_teacher/attendance');

		} else {
			$this->attendance(); //reload page with validation errors
		}	
	}


	public function attendance_details($id) {
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'students', 'staff');
		//check student is in this teacher's class
		$this->class_teacher_model->check_student_class($id);
		$page_title = 'Attendance: ' . $this->common_model->get_student_fullname($id);
		$this->staff_header($page_title, $page_title);
		$session = current_session_slug;
		$term = current_term;
		$class_id = $this->class_details->id;
		$data['student_name'] = $this->common_model->get_student_fullname($id);
		$data['id'] = $id;
		$data['class'] = $this->common_model->get_class_details($this->class_details->id)->class;
		$data['attendance'] = $this->class_teacher_model->get_student_attendance($id);
		$data['att_present'] = $this->common_model->get_attendance_present($session, $term, $class_id, $id);
		$data['att_absent'] = $this->common_model->get_attendance_absent($session, $term, $class_id, $id);
		$data['att_total'] = $this->common_model->get_attendance_total($session, $term, $class_id, $id);
		$this->load->view('staff/attendance/attendance_details', $data);
        $this->staff_footer();
	}


	public function check_attendance_ajax($student_id) { 
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'staff');
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
			$query = $this->class_teacher_model->check_attendance($student_id, $date);

			if ($query) {

				$att_id = $query->id;
				$session = get_the_session($query->session);
				$term = $query->term;
				$term = $query->term;
				$class = $this->common_model->get_class_details($query->class_id)->class;
				$date = x_date_full($query->date);
				$status = ($query->status == 'Present') ? '<b class="text-success">Present</b>' : '<b class="text-danger">Absent</b>';

				$json_data = array(
					'att_exists' => 'true',
					'att_id' => $att_id,
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


	public function delete_attendance($att_id) { 
		//check student id exists in this school
		$this->check_school_data_exists(school_id, $att_id, 'id', 'attendance', 'staff');
		$student_id = $this->class_teacher_model->get_attendance_details($att_id)->student_id;
		//check student is in this teacher's class
		$this->class_teacher_model->check_student_class($student_id);
		$this->class_teacher_model->delete_attendance($att_id);
		$this->session->set_flashdata('status_msg', 'Attendance data deleted successfully.');
		redirect($this->agent->referrer());
	}
	

	
	
}