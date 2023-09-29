<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Timetable_admin
Role: Controller
Description: Timetable_admin Class controls access to all time table pages and functions from the admin's end
Model: timetable_model
Author: Nwankwo Ikemefuna
Date Created: 15th June, 2018
*/


class Timetable_admin extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->load->model('timetable_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		$this->module_restricted_admin(school_id, mod_timetable); //student management module
		$this->activation_restricted_admin(school_id); 

		//module-level scripts
		$this->admin_module_scripts = array('s_timetable');
	}



	/* ====== Lesson Periods ====== */

	public function select_class_lesson_periods() { //select class in modal in header
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$class_id = $this->input->post('class_id', TRUE);
		if ($this->form_validation->run())  {
			redirect(site_url('timetable_admin/lesson_periods/'.$class_id));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}


	public function lesson_periods($class_id) {
		//check class exists in this school
		$this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
		$class_details = $this->common_model->get_class_details($class_id);
		$class = $class_details->class;
		$section_id = $class_details->section_id;
		$page_title = 'Lesson Periods: ' . $class;
		$this->admin_header($page_title, $page_title);	
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
		$this->load->view('admin/timetable/lesson_periods', $data);
        $this->admin_footer();
	}


	public function new_lesson_period_ajax() { 
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$this->form_validation->set_rules('period_type', 'Period Type', 'trim|required');	
		//check period type
		$period_type = $this->input->post('period_type', TRUE); 
		switch ($period_type) {
			case 'Subject':	
				$this->form_validation->set_rules('subject_id', 'Subject', 'trim|required');
				$this->form_validation->set_rules('break_type', 'Break Type', 'trim');
				$this->form_validation->set_rules('other_activity', 'Other Activity', 'trim');
			break;
			case 'Break':	
				$this->form_validation->set_rules('subject_id', 'Subject', 'trim');
				$this->form_validation->set_rules('break_type', 'Break Type', 'trim|required');
				$this->form_validation->set_rules('other_activity', 'Other Activity', 'trim');
			break;
			case 'Activity':	
				$this->form_validation->set_rules('subject_id', 'Subject', 'trim');
				$this->form_validation->set_rules('break_type', 'Break Type', 'trim');
				$this->form_validation->set_rules('other_activity', 'Other Activity', 'trim|required');
			break;
		}						
		$this->form_validation->set_rules('day', 'Day', 'trim|required');
		$this->form_validation->set_rules('start_time', 'Starting period', 'trim|required');
		$this->form_validation->set_rules('end_time', 'Ending period', 'trim|required');
		if ($this->form_validation->run())  {
			//check if this schedule already exists		
			$query = $this->timetable_model->check_lesson_period_exists();
			if ($query == 0) {
				$this->timetable_model->new_lesson_period();
				echo 1;
			} else {
				echo 'The specified lesson period already exists.';
			}	
		} else {
			echo validation_errors();
		}
	}


	public function edit_lesson_period($id) { 
		//check lesson period exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'lesson_periods', 'admin');
		$d = $this->timetable_model->get_lesson_period_details($id);
		$period_type = $d->period_type;
		//check if period type is subject or activity
		if ($period_type == 'Subject') {
			$subject = $this->common_model->get_subject_details($d->subject_id)->subject;
		} else {
			$subject = $d->activity;
		}
		$class_details = $this->common_model->get_class_details($d->class_id);
		$section_id = $class_details->section_id;
		$page_title = 'Edit Lesson Period: ' . $subject;
		$this->admin_header($page_title, $page_title);	
		$data['d'] = $d;
		$data['subject'] = $subject;
		$data['subjects_option'] = $this->common_model->subjects_option_by_section(school_id, $section_id); 
		$this->load->view('admin/timetable/edit_lesson_period', $data);
		$this->admin_footer();
	}
 

	public function edit_lesson_period_ajax($id) { 
		//check lesson period exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'lesson_periods', 'admin');
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		//check period type
		$period_type = $this->input->post('period_type', TRUE); 
		switch ($period_type) {
			case 'Subject':	
				$this->form_validation->set_rules('subject_id', 'Subject', 'trim|required');
				$this->form_validation->set_rules('break_type', 'Break Type', 'trim');
				$this->form_validation->set_rules('other_activity', 'Other Activity', 'trim');
			break;
			case 'Break':	
				$this->form_validation->set_rules('subject_id', 'Subject', 'trim');
				$this->form_validation->set_rules('break_type', 'Break Type', 'trim|required');
				$this->form_validation->set_rules('other_activity', 'Other Activity', 'trim');
			break;
			case 'Activity':	
				$this->form_validation->set_rules('subject_id', 'Subject', 'trim');
				$this->form_validation->set_rules('break_type', 'Break Type', 'trim');
				$this->form_validation->set_rules('other_activity', 'Other Activity', 'trim|required');
			break;
		}				
		$this->form_validation->set_rules('day', 'Day', 'trim|required');
		$this->form_validation->set_rules('start_time', 'Starting period', 'trim|required');
		$this->form_validation->set_rules('end_time', 'Ending period', 'trim|required');
		
		if ($this->form_validation->run())  {
			
			//check if this period already exists
			$class_id = $this->input->post('class_id', TRUE); 	
			$subject_id = ucwords($this->input->post('subject_id', TRUE));
			$day = $this->input->post('day', TRUE); 	
			$start_time = $this->input->post('start_time', TRUE); 	
			$end_time = $this->input->post('end_time', TRUE); 	
			$d = $this->timetable_model->get_lesson_period_details($id);
			$query = $this->timetable_model->check_lesson_period_exists();
			
			if ( $query == 0 || ($query > 0 && ($d->class_id == $class_id && $d->subject_id == $subject_id && $d->day == $day && $d->start_time == $start_time && $d->end_time == $end_time)) ) {	
				$this->timetable_model->edit_lesson_period($id);
				echo 1;
			} else {
				echo 'The specified lesson period already exists.';
			}	

		} else {
			echo validation_errors();
		}
	}


	public function duplicate_lesson_period($class_id) { 
		//check class exists in this school
		$this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$new_class_id = $this->input->post('class_id', TRUE); 	
		$new_class = $this->common_model->get_class_details($new_class_id)->class; 	
		$new_slug = $this->common_model->get_class_details($new_class_id)->slug; 	
		if ($this->form_validation->run())  {
			//check if lesson period already exists for the target class
			$query = $this->timetable_model->check_lesson_period_exists_on_duplicate($class_id);
			if ($query == 0) {
				$this->timetable_model->duplicate_lesson_period($class_id);
			} else {
				$this->session->set_flashdata('status_msg_error', "Lesson periods already exist for {$new_class} class.");
				redirect($this->agent->referrer());
			}	
		} else {
			$this->session->set_flashdata('status_msg_error', "Validation errors encountered!");
			redirect($this->agent->referrer());
		}
	}


	public function delete_lesson_period($id) { 
		//check lesson period exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'lesson_periods', 'admin');
		$this->timetable_model->delete_lesson_period($id);
		$this->session->set_flashdata('status_msg', 'Lesson period deleted successfully.');
		redirect($this->agent->referrer());
	}


	public function delete_day_periods($class_id, $day) { 
		//check class exists in this school
		$this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
		$this->timetable_model->delete_day_periods($class_id, $day);
		$this->session->set_flashdata('status_msg', "Lesson periods for {$day} deleted successfully.");
		redirect($this->agent->referrer());
	}
	
	
	public function clear_lesson_periods($class_id) { 
		//check if demo user
		$this->demo_action_restricted_admin();
		//check class exists in this school
		$this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
		$class = $this->common_model->get_class_details($class_id)->class;
		$this->timetable_model->clear_lesson_periods($class_id);
		$this->session->set_flashdata('status_msg', "Lesson periods for {$class} cleared successfully.");
		redirect($this->agent->referrer());
	}





	/* ====== Test Schedules ====== */

	public function select_class_test_schedules() { //select class in modal in header
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$class_id = $this->input->post('class_id', TRUE);
		if ($this->form_validation->run())  {
			redirect(site_url('timetable_admin/class_test_schedules/'.$class_id));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}


	public function class_test_schedules($class_id) {
		//check class exists in this school
		$this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
		$class_details = $this->common_model->get_class_details($class_id);
		$class = $class_details->class;
		$page_title = 'Test Schedules: ' . $class; 
		$this->admin_header($page_title, $page_title);
		$data['total_schedules'] = count($this->timetable_model->get_test_schedules()); 
		$data['test_schedules'] = $this->timetable_model->get_test_schedules(); 
		$data['start_date'] = $this->timetable_model->get_test_start_date(); 
		$data['end_date'] = $this->timetable_model->get_test_end_date(); 
		$data['class_id'] = $class_id;
		$data['class'] = $class;
		$this->load->view('admin/timetable/class_test_schedules', $data);
		$this->admin_footer();
	}

	
	public function test_schedules() {
		$inner_page_title = 'Test Schedules (' .count($this->timetable_model->get_test_schedules()). ')'; 
		$this->admin_header('Test Schedules', $inner_page_title);
		
		// Create template of preferences
		$prefs['template'] = custom_calendar_template(); //the custom calendar template
		$prefs['show_next_prev'] = true; //show next and previous links
		$prefs['next_prev_url'] = base_url('timetable_admin/test_schedules'); //url for calendar pagination
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
		$this->load->view('admin/timetable/test_schedules', $data);
		$this->admin_footer();
	}
	
	
	public function the_test_schedules($month, $year) {
		$user = 'admin';
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


	public function new_test_schedule_ajax() { 
		$this->form_validation->set_rules('date', 'Date', 'trim|required');
		$this->form_validation->set_rules('time', 'Time', 'trim|required');
		$this->form_validation->set_rules('class_id[]', 'Class', 'trim|required');
		$this->form_validation->set_rules('subject_id', 'Subject', 'trim|required');
		if ($this->form_validation->run())  {
			//check if this schedule already exists		
			$query = $this->timetable_model->check_test_schedule_exists();
			if ($query == 0) {
				$this->timetable_model->new_test_schedule();
				echo 1;
			} else {
				echo 'The specified test schedule already exists.';
			}	
		} else {
			echo validation_errors();
		}
	}
	
	
	public function delete_test_schedule($id) { 
		//check test schedule exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'test_schedules', 'admin');
		$this->timetable_model->delete_test_schedule($id);
		$this->session->set_flashdata('status_msg', 'Test schedule deleted successfully.');
		redirect($this->agent->referrer());
	}
	
	
	public function clear_test_schedules() { 
		//check if demo user
		$this->demo_action_restricted_admin();
		$this->timetable_model->clear_test_schedules();
		$this->session->set_flashdata('status_msg', 'Test schedules cleared successfully.');
		redirect($this->agent->referrer());
	}
	




	
	
	/* ====== Exam Schedules ====== */

	public function select_class_exam_schedules() { //select class in modal in header
		$this->form_validation->set_rules('class_id', 'Class', 'trim|required');
		$class_id = $this->input->post('class_id', TRUE);
		if ($this->form_validation->run())  {
			redirect(site_url('timetable_admin/class_exam_schedules/'.$class_id));
		} else {
			$this->session->set_flashdata('status_msg_error', 'Something went wrong!');
			redirect($this->agent->referrer());
		}
	}


	public function class_exam_schedules($class_id) {
		//check class exists in this school
		$this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
		$class_details = $this->common_model->get_class_details($class_id);
		$class = $class_details->class;
		$page_title = 'Exam Schedules: ' . $class; 
		$this->admin_header($page_title, $page_title);
		$data['total_schedules'] = count($this->timetable_model->get_exam_schedules()); 
		$data['exam_schedules'] = $this->timetable_model->get_exam_schedules(); 
		$data['start_date'] = $this->timetable_model->get_exam_start_date(); 
		$data['end_date'] = $this->timetable_model->get_exam_end_date(); 
		$data['class_id'] = $class_id;
		$data['class'] = $class;
		$this->load->view('admin/timetable/class_exam_schedules', $data);
		$this->admin_footer();
	}


	public function exam_schedules() {
		$inner_page_title = 'Exam Schedules (' .count($this->timetable_model->get_exam_schedules()). ')'; 
		$this->admin_header('Exam Schedules', $inner_page_title);
		
		// Create template of preferences
		$prefs['template'] = custom_calendar_template(); //the custom calendar template
		$prefs['show_next_prev'] = true; //show next and previous links
		$prefs['next_prev_url'] = base_url('timetable_admin/exam_schedules'); //url for calendar pagination
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
		$this->load->view('admin/timetable/exam_schedules', $data);
		$this->admin_footer();
	}
	
	
	public function the_exam_schedules($month, $year) {
		$user = 'admin';
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


	public function new_exam_schedule_ajax() { 
		$this->form_validation->set_rules('date', 'Date', 'trim|required');
		$this->form_validation->set_rules('time', 'Time', 'trim|required');
		$this->form_validation->set_rules('class_id[]', 'Class', 'trim|required');
		$this->form_validation->set_rules('subject_id', 'Subject', 'trim|required');
		if ($this->form_validation->run())  {
			//check if this schedule already exists		
			$query = $this->timetable_model->check_exam_schedule_exists();
			if ($query == 0) {
				$this->timetable_model->new_exam_schedule();
				echo 1;
			} else {
				echo 'The specified exam schedule already exists.';
			}	
		} else {
			echo validation_errors();
		}
	}
	
	
	public function delete_exam_schedule($id) { 
		//check exam schedule exists in this school
		$this->check_school_data_exists(school_id, $id, 'id', 'exam_schedules', 'admin');
		$this->timetable_model->delete_exam_schedule($id);
		$this->session->set_flashdata('status_msg', 'exam schedule deleted successfully.');
		redirect($this->agent->referrer());
	}
	
	
	public function clear_exam_schedules() { 
		//check if demo user
		$this->demo_action_restricted_admin();
		$this->timetable_model->clear_exam_schedules();
		$this->session->set_flashdata('status_msg', 'exam schedules cleared successfully.');
		redirect($this->agent->referrer());
	}
	



	
	
	
}