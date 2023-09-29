<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Timetable_model
Role: Model
Description: Controls the DB processes of timetable schedules from admin/staff panel
Controller: Timetable_admin, Timetable_staff
Author: Nwankwo Ikemefuna
Date Created: 31st May, 2018
*/

class Timetable_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}





	public function generate_date_unix($date) {
		//break date into arrays of day, month and year. Note that "/" must be specified as separator in datepicker initialization and date format must be set as yyyy/mm/dd
		$x_date = explode('/', $date);
		$year = $x_date[0]; //year is array index 0 
		$month = $x_date[1]; //month is array index 1
		$day = $x_date[2]; //day is array index 2
		//date unix: required to order the events chronologically when viewed as a list (in the order yyyymmdd)
		$date_unix = $year.$month.$day;
		return $date_unix;
	}


	public function adjust_schedule_time($time) {
		//This function checks whether time is below 10, then prepends 0 to the hour digit to make it 2 digits.
		//This is necessary in ordering the schedules chronologically by time. 
		$x_time = explode(':', $time); //break the time into hour and minute parts. Colon is the separator
		$hour = $x_time[0]; //hour is array index 0 
		$minute = $x_time[1]; //hour is array index 0 
		if ($hour < 10) {
			$the_time = '0'.$hour . ':' . $minute; //eg 7:23 PM becomes 07:23 PM
		} else {
			$the_time = $hour . ':' . $minute; //eg 10:23 PM remains 10:23 PM
		}
		return $the_time;
	}


	public function get_schedule_classes($class_idx) {
		//class IDs are saved as 1, 2, 3, ...
		//explode class IDs into array of individual IDs
		$class_id = explode(", ", $class_idx);

		$classes = "";
		foreach ($class_id as $key => $value) {
			$query = $this->common_model->get_class_details($value);
			//concatenate the classes and separate with comma
			$classes .= $query->class . ", "; 
		}
		$classes = substr(trim($classes), 0, -1); //remove last comma
		return $classes;
	}
	





	/* ===== Lesson Periods ===== */

	public function get_lesson_period_details($id) {
		return $this->db->get_where('lesson_periods', array('id' => $id))->row();
	}


	public function get_lesson_periods($class_id, $day)	{ 
		$this->db->order_by('start_time_meridian', 'asc'); //order by meridian first
		$this->db->order_by('start_time', 'asc'); //order by starting period chronologically
		$this->db->where(array('class_id' => $class_id, 'day' => $day));
		return $this->db->get('lesson_periods')->result();
	}


	public function get_lesson_period_details_by_class($class_id) {
		return $this->db->get_where('lesson_periods', array('class_id' => $class_id))->row();
	}


	public function get_lesson_periods_by_class($class_id)	{ 
		$this->db->where('class_id', $class_id);
		return $this->db->get('lesson_periods');
	}


	public function new_lesson_period() { 
		$class_id = $this->input->post('class_id', TRUE); 
		$period_type = $this->input->post('period_type', TRUE); 
		switch ($period_type) {
			case 'Subject':	
				$subject_id = $this->input->post('subject_id', TRUE);
				$activity = NULL;
			break;
			case 'Break':	
				$subject_id = NULL;
				$activity = $this->input->post('break_type', TRUE);
			break;
			case 'Other Activity':	
				$subject_id = NULL;
				$activity = $this->input->post('other_activity', TRUE);
			break;
		}
		$day = $this->input->post('day', TRUE); 	
		$start_time = $this->adjust_schedule_time($this->input->post('start_time', TRUE));
		$start_time_meridian = $this->get_start_time_meridian($start_time);
		$end_time = $this->adjust_schedule_time($this->input->post('end_time', TRUE)); 	
		$data = array (
			'school_id' => school_id,
			'class_id' => $class_id,
			'period_type' => $period_type,
			'subject_id' => $subject_id,
			'activity' => $activity,
			'day' => $day, 
			'start_time_meridian' => $start_time_meridian, 
			'start_time' => $start_time, 
			'end_time' => $end_time, 
		);
		return $this->db->insert('lesson_periods', $data);
	}


	public function edit_lesson_period($id) { 
		$period_type = $this->input->post('period_type', TRUE); 
		switch ($period_type) {
			case 'Subject':	
				$subject_id = $this->input->post('subject_id', TRUE);
				$activity = NULL;
			break;
			case 'Break':	
				$subject_id = NULL;
				$activity = $this->input->post('break_type', TRUE);
			break;
			case 'Other Activity':	
				$subject_id = NULL;
				$activity = $this->input->post('other_activity', TRUE);
			break;
		}
		$day = $this->input->post('day', TRUE); 	
		$start_time = $this->adjust_schedule_time($this->input->post('start_time', TRUE)); 	
		$start_time_meridian = $this->get_start_time_meridian($start_time);
		$end_time = $this->adjust_schedule_time($this->input->post('end_time', TRUE)); 	
		$data = array (
			'period_type' => $period_type,
			'subject_id' => $subject_id,
			'activity' => $activity,
			'day' => $day, 
			'start_time_meridian' => $start_time_meridian, 
			'start_time' => $start_time, 
			'end_time' => $end_time, 
		);
		$this->db->where('id', $id);
		return $this->db->update('lesson_periods', $data);
	}
	
	
	private function get_start_time_meridian($start_time) {
		//extract meridian from start time to be used for ordering chronologically
		//Time is posted as 08:00 AM or 08:00 PM
		$start_time_array = explode(" ", $start_time);
		$meridian = $start_time_array[1]; 
		return $meridian;
	}


	public function check_lesson_period_exists() {
		$where = array(
			'school_id' => school_id,	
			'class_id' => $this->input->post('class_id', TRUE),	
			'subject_id' => ucwords($this->input->post('subject_id', TRUE)),
			'day' => $this->input->post('day', TRUE),
			'start_time' => $this->adjust_schedule_time($this->input->post('start_time', TRUE)), 	
			'end_time' => $this->adjust_schedule_time($this->input->post('end_time', TRUE)),	
		);
		return $this->db->get_where('lesson_periods', $where)->num_rows();
	}


	public function check_lesson_period_exists_on_duplicate($class_id) {
		//get details of class being duplicated
		$y = $this->get_lesson_period_details_by_class($class_id);
		$where = array(
			'class_id' => $this->input->post('class_id', TRUE), //class duplicating to
			'subject_id' => $y->subject_id,
			'day' => $y->day,
			'start_time' => $y->start_time, 	
			'end_time' => $y->end_time,	
		);
		return $this->db->get_where('lesson_periods', $where)->num_rows();
	}


	public function duplicate_lesson_period($class_id) {
    	$new_class_id = $this->input->post('class_id', TRUE); 	
		$new_class = $this->common_model->get_class_details($new_class_id)->class; 	
		$new_slug = $this->common_model->get_class_details($new_class_id)->slug; 
    	//get the lesson periods for the class being duplicated
		$the_periods = $this->get_lesson_periods_by_class($class_id)->result();
		$data = array();
		foreach ($the_periods as $y) {
			$row = array();
			$row['school_id'] = school_id;
			$row['class_id'] = $new_class_id;
			$row['subject_id'] = $y->subject_id;
			$row['day'] = $y->day;
			$row['start_time'] = $y->start_time; 
			$row['end_time'] = $y->end_time;
			$data[] = $row;
		}
        return $this->db->insert_batch('lesson_periods', $data);
    }


	function modal_delete_day_periods($class_id, $day, $modal_id) { //delete all the periods in a particular day and class
		$class = $this->common_model->get_class_details($class_id)->class;
		return '<div class="modal fade" id="'.$modal_id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Delete: ' . $class . ' (' . $day . ')</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						Are you sure you want to permanently delete all the ' . $day . ' periods for ' . $class . '? 
					</div>
					<div class="modal-footer">
						<a class="btn btn-sm btn-danger" role="button" href="' .base_url('timetable_admin/delete_day_periods/'.$class_id.'/'.$day). '"> Yes, Delete All</a>
						<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>';
	} 


	public function delete_lesson_period($id) {
		return $this->db->delete('lesson_periods', array('id' => $id));
    } 


    public function delete_day_periods($class_id, $day) {
    	return $this->db->delete('lesson_periods', array('class_id' =>  $class_id, 'day' => $day));
    } 
	
	
	public function clear_lesson_periods($class_id) {
		$this->db->where('class_id', $class_id);
		return $this->db->delete('lesson_periods');
    } 





    /* ===== Test  Schedules ===== */

	public function get_test_schedule_details($id) {
		return $this->db->get_where('test_schedules', array('id' => $id))->row();
	}


	public function get_test_schedules() { 
		return $this->db->get_where('test_schedules', array('school_id' => school_id))->result();
	}


	public function get_test_start_date() {
		if (count($this->get_test_schedules()) > 0) {
			$this->db->select_min('date_unix'); //lowest date added
			$date_unix = $this->db->get_where('test_schedules', array('school_id' => school_id))->row()->date_unix;
			//get row details
			$query = $this->db->get_where('test_schedules', array('date_unix' => $date_unix))->row();
			$day = $query->day;
			$month = $query->month;
			$year = $query->year;
			$date = $year . '/' . $month . '/' . $day; //concatenate year, month and day into a date in the format yyyy/mm/dd
			$start_date = x_date_full($date);
			return $start_date;
		} else {
			return '<span class="text-danger">No schedule found</span>';
		}
	}


	public function get_test_end_date() {
		if (count($this->get_test_schedules()) > 0) {
			$this->db->select_max('date_unix'); //highest date added
			$date_unix = $this->db->get_where('test_schedules', array('school_id' => school_id))->row()->date_unix;
			//get row details
			$query = $this->db->get_where('test_schedules', array('date_unix' => $date_unix))->row();
			$day = $query->day;
			$month = $query->month;
			$year = $query->year;
			$date = $year . '/' . $month . '/' . $day; //concatenate year, month and day into a date in the format yyyy/mm/dd
			$end_date = x_date_full($date);
			return $end_date;
		} else {
			return '<span class="text-danger">No schedule found</span>';
		}
	}


	private function get_test_schedules_by_date($date_unix) {
		$this->db->order_by('time', 'asc');
		return $this->db->get_where('test_schedules', array('school_id' => school_id, 'date_unix' => $date_unix))->result();
	}


	private function test_schedules_same_day($date_unix, $user) {
		$same_day_schedules = $this->get_test_schedules_by_date($date_unix);
		$schedules = "";
		foreach ($same_day_schedules as $sd) {
			//if user is admin, show action column
			if ($user == 'admin') {
				$action_column = 	'<td>
										<a class="btn btn-danger btn-sm" href="' . base_url('timetable_admin/delete_test_schedule/'.$sd->id). '" title="Delete"><i class="fa fa-trash"></i></a>
									</td>';
			} else {
				$action_column = NULL;
			}
			$subject = $this->common_model->get_subject_details($sd->subject_id)->subject;
			$classes = $this->get_schedule_classes($sd->class_id);
			$schedules .= 	'<tr>
								<td>' . $sd->time . '</td>
								<td>' . $subject . '</td>
								<td>' . $classes . '</td>'
								. $action_column .
							'</tr>';
		}
		return $schedules;
	}
 

	public function test_schedules_table($date_unix, $user) {
		//if user is admin, show action column
		if ($user == 'admin') {
			$action_column = 	'<th class="w-15"> Action </th>';
		} else {
			$action_column = NULL;
		}
		return '<div class="table-scroll">
					<table class="table schedule-table table-bordered cell-text-middle" style="text-align: left">
						<thead>
							<tr>
								<th class="w-20"> Time </th>
								<th class="w-25"> Subject </th>
								<th class="w-35"> Class(es) </th>'
								. $action_column . 
							'</tr>
						</thead>
						<tbody>'
							. $this->test_schedules_same_day($date_unix, $user) .
						'</tbody>
					</table>
				</div>';
	}


	public function modal_calendar_test_schedule_content($id, $date_unix, $day, $month, $year, $user) {
		$date = $year .'-'. $month .'-'. $day;  // yyyy-mm-dd to conform with mysql date format
		return  '<div class="modal fade" id="schedule'.$id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form modal-schedule-content">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title text-left text-bold"> Test Schedules: ' . x_date_full($date) . '</h4>
							</div><!--/.modal-header--> 
							<div class="modal-body text-left" style="margin-top: -15px;">
								<p>'
									. $this->test_schedules_table($date_unix, $user) .
								'</p>
							</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function new_test_schedule() { 
		$date = $this->input->post('date', TRUE); 	
		$date_unix = $this->generate_date_unix($date);
		$time = $this->adjust_schedule_time($this->input->post('time', TRUE)); 	
		$class_id = implode(", ", $this->input->post('class_id', TRUE));
		$subject_id = $this->input->post('subject_id', TRUE);

		$x_date = explode('/', $date);
		$year = $x_date[0]; //year is array index 0 
		$month = $x_date[1]; //month is array index 1
		$day = $x_date[2]; //day is array index 2
		$data = array (
			'school_id' => school_id,
			'class_id' => $class_id,
			'subject_id' => $subject_id,
			'year' => $year, 
			'month' => $month, 
			'day' => $day, 
			'time' => $time, 
			'date_unix' => $date_unix, 
		);
		return $this->db->insert('test_schedules', $data);
	}


	public function check_test_schedule_exists() {
		$date = $this->input->post('date', TRUE); 	
		$date_unix = $this->generate_date_unix($date);
		$time = $this->adjust_schedule_time($this->input->post('time', TRUE)); 	
		$class_id = implode(", ", $this->input->post('class_id', TRUE));
		$subject_id = ucwords($this->input->post('subject_id', TRUE));
		$array = array(
			'school_id' => school_id,
			'class_id' => $class_id, 
			'subject_id' => $subject_id, 
			'date_unix' => $date_unix, 
			'time' => $time, 
		);
		return $this->db->get_where('test_schedules', $array)->num_rows();
	}


	public function delete_test_schedule($id) {
		return $this->db->delete('test_schedules', array('id' => $id));
    } 
	
	
	public function clear_test_schedules() {
		return $this->db->delete('test_schedules', array('school_id' => school_id));
    } 




	
	
	/* ===== Exam  Schedules ===== */

	public function get_exam_schedule_details($id) {
		return $this->db->get_where('exam_schedules', array('id' => $id))->row();
	}


	public function get_exam_schedules() { 
		return $this->db->get_where('exam_schedules', array('school_id' => school_id))->result();
	}


	public function get_exam_start_date() {
		if (count($this->get_exam_schedules()) > 0) {
			$this->db->select_min('date_unix'); //lowest date added
			$date_unix = $this->db->get_where('exam_schedules', array('school_id' => school_id))->row()->date_unix;
			//get row details
			$query = $this->db->get_where('exam_schedules', array('date_unix' => $date_unix))->row();
			$day = $query->day;
			$month = $query->month;
			$year = $query->year;
			$date = $year . '/' . $month . '/' . $day; //concatenate year, month and day into a date in the format yyyy/mm/dd
			$start_date = x_date_full($date);
			return $start_date;
		} else {
			return '<span class="text-danger">No schedule found</span>';
		}
	}


	public function get_exam_end_date() {
		if (count($this->get_exam_schedules()) > 0) {
			$this->db->select_max('date_unix'); //highest date added
			$date_unix = $this->db->get_where('exam_schedules', array('school_id' => school_id))->row()->date_unix;
			//get row details
			$query = $this->db->get_where('exam_schedules', array('date_unix' => $date_unix))->row();
			$day = $query->day;
			$month = $query->month;
			$year = $query->year;
			$date = $year . '/' . $month . '/' . $day; //concatenate year, month and day into a date in the format yyyy/mm/dd
			$end_date = x_date_full($date);
			return $end_date;
		} else {
			return '<span class="text-danger">No schedule found</span>';
		}
	}


	private function get_exam_schedules_by_date($date_unix) {
		$this->db->order_by('time', 'asc');
		return $this->db->get_where('exam_schedules', array('school_id' => school_id, 'date_unix' => $date_unix))->result();
	}


	private function exam_schedules_same_day($date_unix, $user) {
		$same_day_schedules = $this->get_exam_schedules_by_date($date_unix);
		$schedules = "";
		foreach ($same_day_schedules as $sd) {
			//if user is admin, show action column
			if ($user == 'admin') {
				$action_column = 	'<td>
										<a class="btn btn-danger btn-sm" href="' . base_url('timetable_admin/delete_exam_schedule/'.$sd->id). '" title="Delete"><i class="fa fa-trash"></i></a>
									</td>';
			} else {
				$action_column = NULL;
			}
			$subject = $this->common_model->get_subject_details($sd->subject_id)->subject;
			$classes = $this->get_schedule_classes($sd->class_id);
			$schedules .= 	'<tr>
								<td>' . $sd->time . '</td>
								<td>' . $subject . '</td>
								<td>' . $classes . '</td>'
								. $action_column .
							'</tr>';
		}
		return $schedules;
	}


	public function exam_schedules_table($date_unix, $user) {
		//if user is admin, show action column
		if ($user == 'admin') {
			$action_column = 	'<th class="w-15"> Action </th>';
		} else {
			$action_column = NULL;
		}
		return '<div class="table-scroll">
					<table class="table schedule-table table-bordered cell-text-middle" style="text-align: left">
						<thead>
							<tr>
								<th class="w-20"> Time </th>
								<th class="w-25"> Subject </th>
								<th class="w-35"> Class(es) </th>'
								. $action_column . 
							'</tr>
						</thead>
						<tbody>'
							. $this->exam_schedules_same_day($date_unix, $user) .
						'</tbody>
					</table>
				</div>';
	}


	public function modal_calendar_exam_schedule_content($id, $date_unix, $day, $month, $year, $user) {
		$date = $year .'-'. $month .'-'. $day;  // yyyy-mm-dd to conform with mysql date format
		return  '<div class="modal fade" id="schedule'.$id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form modal-schedule-content">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title text-left text-bold"> Exam Schedules: ' . x_date_full($date) . '</h4>
							</div><!--/.modal-header--> 
							<div class="modal-body text-left" style="margin-top: -15px;">
								<p>'
									. $this->exam_schedules_table($date_unix, $user) .
								'</p>
							</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function new_exam_schedule() { 
		$date = $this->input->post('date', TRUE); 	
		$date_unix = $this->generate_date_unix($date);
		$time = $this->adjust_schedule_time($this->input->post('time', TRUE)); 	
		$class_id = implode(", ", $this->input->post('class_id', TRUE));
		$subject_id = $this->input->post('subject_id', TRUE);

		$x_date = explode('/', $date);
		$year = $x_date[0]; //year is array index 0 
		$month = $x_date[1]; //month is array index 1
		$day = $x_date[2]; //day is array index 2
		$data = array (
			'school_id' => school_id,
			'class_id' => $class_id,
			'subject_id' => $subject_id,
			'year' => $year, 
			'month' => $month, 
			'day' => $day, 
			'time' => $time, 
			'date_unix' => $date_unix, 
		);
		return $this->db->insert('exam_schedules', $data);
	}


	public function check_exam_schedule_exists() {
		$date = $this->input->post('date', TRUE); 	
		$date_unix = $this->generate_date_unix($date);
		$time = $this->adjust_schedule_time($this->input->post('time', TRUE)); 	
		$class_id = implode(", ", $this->input->post('class_id', TRUE)); 	
		$subject_id = ucwords($this->input->post('subject_id', TRUE));
		$array = array(
			'school_id' => school_id,
			'class_id' => $class_id, 
			'subject_id' => $subject_id, 
			'date_unix' => $date_unix, 
			'time' => $time, 
		);
		return $this->db->get_where('exam_schedules', $array)->num_rows();
	}


	public function delete_exam_schedule($id) {
		return $this->db->delete('exam_schedules', array('id' => $id));
    } 
	
	
	public function clear_exam_schedules() {
		return $this->db->delete('exam_schedules', array('school_id' => school_id));
    } 



	
}