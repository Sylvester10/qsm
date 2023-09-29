<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Timetable_staff
Role: Controller
Description: Timetable_staff Class controls access to all time table pages and functions from the staff's end
Model: timetable_model
Author: Nwankwo Ikemefuna
Date Created: 15th June, 2018
*/


class Timetable_staff extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->staff_restricted(); //allow only logged in users to access this class
		$this->load->model('teacher_model');
		$this->load->model('timetable_model');
		$this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
		//get school id
		$this->school_id = $this->staff_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_staff(school_id, mod_timetable); 
		$this->activation_restricted_staff(school_id); 

		//module-level scripts
		$this->staff_module_scripts = array();
	}



	/* ====== Lesson Periods ====== */

	public function select_class_lesson_periods() { //select class in modal in header
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$class_id = $this->input->post('class_id', TRUE);
		if ($this->form_validation->run())  {
			//redirect to the attendance page of the selected class
			redirect(site_url('timetable_staff/lesson_periods/'.$class_id));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}


	public function lesson_periods($class_id) {
		//check class exists in this school
		$this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
		$class_details = $this->common_model->get_class_details($class_id);
		$class = $class_details->class;
		$section_id = $class_details->section_id;
		$page_title = 'Lesson Periods: ' . $class;
		$this->staff_header($page_title, $page_title);	
		$data['y'] = $class_details;
		$data['class_id'] = $class_id;
		$data['class'] = $class;
		$data['subjects_option'] = $this->common_model->subjects_option_by_section(school_id, $section_id); 
		$data['monday_periods'] = $this->timetable_model->get_lesson_periods($class_id, 'Monday');
		$data['tuesday_periods'] = $this->timetable_model->get_lesson_periods($class_id, 'Tuesday');
		$data['wednesday_periods'] = $this->timetable_model->get_lesson_periods($class_id, 'Wednesday');
		$data['thursday_periods'] = $this->timetable_model->get_lesson_periods($class_id, 'Thursday');
		$data['friday_periods'] = $this->timetable_model->get_lesson_periods($class_id, 'Friday');
		$data['classes_option'] = $this->common_model->classes_option_by_section_group(school_id); 
		$this->load->view('staff/timetable/lesson_periods', $data);
        $this->staff_footer();
	}





	/* ====== Test Schedules ====== */

	public function select_class_test_schedules() { //select class in modal in header
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$class_id = $this->input->post('class_id', TRUE);
		if ($this->form_validation->run())  {
			redirect(site_url('timetable_staff/class_test_schedules/'.$class_id));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}


	public function class_test_schedules($class_id) {
		//check class exists in this school
		$this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
		$class_details = $this->common_model->get_class_details($class_id);
		$class = $class_details->class;
		$page_title = 'Test Schedules: ' . $class; 
		$this->staff_header($page_title, $page_title);
		$data['total_schedules'] = count($this->timetable_model->get_test_schedules()); 
		$data['test_schedules'] = $this->timetable_model->get_test_schedules(); 
		$data['start_date'] = $this->timetable_model->get_test_start_date(); 
		$data['end_date'] = $this->timetable_model->get_test_end_date(); 
		$data['class_id'] = $class_id;
		$data['class'] = $class;
		$this->load->view('staff/timetable/class_test_schedules', $data);
		$this->staff_footer();
	}

	
	public function test_schedules() {
		$inner_page_title = 'Test Schedules (' .count($this->timetable_model->get_test_schedules()). ')'; 
		$this->staff_header('Test Schedules', $inner_page_title);
		
		// Create template of preferences
		$prefs['template'] = custom_calendar_template(); //the custom calendar template
		$prefs['show_next_prev'] = true; //show next and previous links
		$prefs['next_prev_url'] = base_url('timetable_staff/test_schedules'); //url for calendar pagination
		$prefs['month_type'] = 'long'; //full month name
		$prefs['day_type'] = 'short'; //3-letter day type
		$prefs['start_day'] = 'sunday'; //start calendar on sunday
		$prefs['show_other_days'] = FALSE; //Do not display days of other months that share the first or last week of the calendar month.
		
		//load calendar library with preferences
		$this->load->library('calendar', $prefs);
		
		if ($this->uri->segment(4)) { 
			$year = $this->uri->segment(3); //year URI segment
			$month = $this->uri->segment(4); //month URI segment
		} else { //first page, load current year and date
			$year = date("Y", time()); //full year eg 2018
			$month = date("m", time()); //numeric month eg 04 for April
		}
		$schedules = $this->the_test_schedules($month, $year); //get schedules
		$data['test_schedules'] = $this->calendar->generate($year, $month, $schedules); //generate calendar with events
		$data['classes_option'] = $this->common_model->classes_option_by_section_group(school_id); 
		$data['subjects_option'] = $this->common_model->subjects_option_by_section_group(school_id); 
		$data['start_date'] = $this->timetable_model->get_test_start_date(); 
		$data['end_date'] = $this->timetable_model->get_test_end_date(); 
		$data['total_schedules'] = count($this->timetable_model->get_test_schedules()); 
		$this->load->view('staff/timetable/test_schedules', $data);
		$this->staff_footer();
	}
	
	
	public function the_test_schedules($month, $year) {
		$user = 'staff';
		//load calendar events
		$the_schedules = $this->timetable_model->get_test_schedules();
		//create an associative array to hold the events
		$data = array();
		foreach ($the_schedules as $y) { 
			//VERY IMPORTANT! 
			//Check if day is less than 10 and remove the leading 0 if true. This is because CI calendar library renders days from 1 to 9 as 1 digit, while the days are saved as 2 digits in db.
			$y->day = ($y->day < 10) ? substr($y->day, 1) : $y->day; //strip off the 1st xter i.e. 0
			//check that date is on current month and year (this is necessary to avoid duplicating same event date on all the months)
			if ($month == $y->month && $year == $y->year) {
				//day = 'link to event on this day'
				$data[$y->day] = 	'<div class="content" data-toggle="modal" data-target="#schedule' .$y->id. '">'
										. $y->day . 
									'</div>'
									. $this->timetable_model->modal_calendar_test_schedule_content($y->id, $y->date_unix, $y->day, $y->month, $y->year, $user); //show details in a modal window
			} 
		}
		return $data;
	}

	
	
	
	
	/* ====== Exam Schedules ====== */

	public function select_class_exam_schedules() { //select class in modal in header
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$class_id = $this->input->post('class_id', TRUE);
		if ($this->form_validation->run())  {
			redirect(site_url('timetable_staff/class_exam_schedules/'.$class_id));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}


	public function class_exam_schedules($class_id) {
		//check class exists in this school
		$this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
		$class_details = $this->common_model->get_class_details($class_id);
		$class = $class_details->class;
		$page_title = 'Exam Schedules: ' . $class; 
		$this->staff_header($page_title, $page_title);
		$data['total_schedules'] = count($this->timetable_model->get_exam_schedules()); 
		$data['exam_schedules'] = $this->timetable_model->get_exam_schedules(); 
		$data['start_date'] = $this->timetable_model->get_exam_start_date(); 
		$data['end_date'] = $this->timetable_model->get_exam_end_date(); 
		$data['class_id'] = $class_id;
		$data['class'] = $class;
		$this->load->view('staff/timetable/class_exam_schedules', $data);
		$this->staff_footer();
	}


	public function exam_schedules() {
		$inner_page_title = 'Exam Schedules (' .count($this->timetable_model->get_exam_schedules()). ')'; 
		$this->staff_header('Exam Schedules', $inner_page_title);
		
		// Create template of preferences
		$prefs['template'] = custom_calendar_template(); //the custom calendar template
		$prefs['show_next_prev'] = true; //show next and previous links
		$prefs['next_prev_url'] = base_url('timetable_staff/exam_schedules'); //url for calendar pagination
		$prefs['month_type'] = 'long'; //full month name
		$prefs['day_type'] = 'short'; //3-letter day type
		$prefs['start_day'] = 'sunday'; //start calendar on sunday
		$prefs['show_other_days'] = FALSE; //Do not display days of other months that share the first or last week of the calendar month.
		
		//load calendar library with preferences
		$this->load->library('calendar', $prefs);
		
		if ($this->uri->segment(4)) { 
			$year = $this->uri->segment(3); //year URI segment
			$month = $this->uri->segment(4); //month URI segment
		} else { //first page, load current year and date
			$year = date("Y", time()); //full year eg 2018
			$month = date("m", time()); //numeric month eg 04 for April
		}
		$schedules = $this->the_exam_schedules($month, $year); //get schedules
		$data['exam_schedules'] = $this->calendar->generate($year, $month, $schedules); //generate calendar with events
		$data['classes_option'] = $this->common_model->classes_option_by_section_group(school_id); 
		$data['subjects_option'] = $this->common_model->subjects_option_by_section_group(school_id); 
		$data['start_date'] = $this->timetable_model->get_exam_start_date(); 
		$data['end_date'] = $this->timetable_model->get_exam_end_date(); 
		$data['total_schedules'] = count($this->timetable_model->get_exam_schedules()); 
		$this->load->view('staff/timetable/exam_schedules', $data);
		$this->staff_footer();
	}
	
	
	public function the_exam_schedules($month, $year) {
		$user = 'staff';
		//load calendar events
		$the_schedules = $this->timetable_model->get_exam_schedules();
		//create an associative array to hold the events
		$data = array();
		foreach ($the_schedules as $y) { 
			//VERY IMPORTANT! 
			//Check if day is less than 10 and remove the leading 0 if true. This is because CI calendar library renders days from 1 to 9 as 1 digit, while the days are saved as 2 digits in db.
			$y->day = ($y->day < 10) ? substr($y->day, 1) : $y->day; //strip off the 1st xter i.e. 0
			//check that date is on current month and year (this is necessary to avoid duplicating same event date on all the months)
			if ($month == $y->month && $year == $y->year) {
				//day = 'link to event on this day'
				$data[$y->day] = 	'<div class="content" data-toggle="modal" data-target="#schedule' .$y->id. '">'
										. $y->day . 
									'</div>'
									. $this->timetable_model->modal_calendar_exam_schedule_content($y->id, $y->date_unix, $y->day, $y->month, $y->year, $user); //show details in a modal window
			} 
		}
		return $data;
	}

	
	
	
	
	
}