<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Single_class_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of students in a given class in admin's dashboard
Controller: Admin
Author: Nwankwo Ikemefuna
Date Created: 5th June, 2018
*/


class Single_class_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('students_admin_model');
	}


	var $table = 'students';
	var $column_order = array(null, 'id', 'reg_id', 'admission_id', 'last_name', 'first_name', 'other_name', 'date_of_birth', 'sex', 'blood_group', 'place_of_birth', 'nationality', 'state_of_origin', 'local_gov', 'home_town', 'home_address', 'present_school', 'present_school_address', 'present_class', 'class_id', 'passport_photo', 'pass_reset_code', 'date_registered'); //set column field database for datatable orderable
	var $column_search = array('id', 'reg_id', 'admission_id', 'last_name', 'first_name', 'other_name', 'date_of_birth', 'sex', 'blood_group', 'place_of_birth', 'nationality', 'state_of_origin', 'local_gov', 'home_town', 'home_address', 'present_school', 'present_school_address', 'present_class', 'class_id', 'passport_photo', 'pass_reset_code', 'date_registered'); //set column field database for datatable searchable 
	var $order = array('admission_id' => 'asc'); 

	
	private function the_query() {		
		$this->db->from($this->table);
		$i = 0;	
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}
				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}		
		if(isset($_POST['order'])) { // here order processing 
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if(isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	

	function get_records($class_id) {
		$this->the_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where(array('school_id' => school_id, 'class_id' => $class_id, 'revoked' => 'false', 'graduated' => 'false'));
	    $query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records($class_id) {
		$this->the_query();
		$this->db->where(array('school_id' => school_id, 'class_id' => $class_id, 'revoked' => 'false', 'graduated' => 'false'));
	    $query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records($class_id) {
		$this->db->where(array('school_id' => school_id, 'class_id' => $class_id, 'revoked' => 'false', 'graduated' => 'false'));
	    $this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	
	public function suspension_status($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		if ($y->suspended == 'false') {
			return '<b class="text-success">No</b>';
		} else {
			return '<b class="text-danger">Yes</b>';
		}
	}
	
	
	public function student_attendance_info($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		$session = current_session_slug;
		$term = current_term;
		$class_id = $y->class_id;
		$att_present = $this->common_model->get_attendance_present($session, $term, $class_id, $id);
		$att_absent = $this->common_model->get_attendance_absent($session, $term, $class_id, $id);
		$att_total = $this->common_model->get_attendance_total($session, $term, $class_id, $id);
		return  'No of times present: ' . $att_present . '<br />
				No of times absent: ' . $att_absent . '<br />
				Number of times school held: ' . $att_total;
	}
	
	
	public function actions($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		$session = current_session_slug;
		$term = current_term;
		$class_id = $y->class_id;

		if (school_id == kad_school_id) { 

			//mid-term
			$mid_term_produce_report_url = 'kad_student_mid_term_reports_admin/produce_report/'.$session.'/'.$term.'/'.$class_id.'/'.$id;
			//end-of-term
			$end_term_produce_report_url = 'kad_student_reports_admin/produce_report/'.$session.'/'.$term.'/'.$class_id.'/'.$id;
			$mid_term_target_grade = '';
			$end_term_target_grade = '';

		} else {

			//mid-term
			$mid_term_target_grade_url = 'student_mid_term_reports_admin/target_grade/'.$session.'/'.$term.'/'.$class_id.'/'.$id;
			$mid_term_produce_report_url = 'student_mid_term_reports_admin/produce_report/'.$session.'/'.$term.'/'.$class_id.'/'.$id;
			$mid_term_target_grade = '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($mid_term_target_grade_url) .'"> <i class="fa fa-line-chart" style="color: green"></i> &nbsp; Produce Mid-term Target Grade </a></p>';

			//end-of-term
			$end_term_target_grade_url = 'student_reports_admin/target_grade/'.$session.'/'.$term.'/'.$class_id.'/'.$id;
			$end_term_produce_report_url = 'student_reports_admin/produce_report/'.$session.'/'.$term.'/'.$class_id.'/'.$id;
			$end_term_target_grade = '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($end_term_target_grade_url) .'"> <i class="fa fa-line-chart" style="color: green"></i> &nbsp; Produce End-of-term Target Grade </a></p>';

		}
		
		return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('students_admin/student_profile/'.$id) .'"> <i class="fa fa-user" style="color: green"></i> &nbsp; View Profile</a></p>

		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('students_admin/edit_student/'.$id) .'"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Student </a></p>

		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('students_admin/associate_parent/'.$id) .'"> <i class="fa fa-plug" style="color: green"></i> &nbsp; Associate Parent </a></p>
		
		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#promote'.$id.'"> <i class="fa fa-send" style="color: green"></i> &nbsp; Promote Student </a></p>
		
		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('students_admin/graduate_student/'.$id) .'"> <i class="fa fa-sign-out" style="color: green"></i> &nbsp; Graduate Student </a></p>'

		. $mid_term_target_grade . 
		
		'<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($mid_term_produce_report_url) .'"> <i class="fa fa-line-chart" style="color: green"></i> &nbsp; Produce Mid-term Report </a></p>' 
		
		. $end_term_target_grade . 
		
		'<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($end_term_produce_report_url) .'"> <i class="fa fa-line-chart" style="color: green"></i> &nbsp; Produce End-of-term Report </a></p>

		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('incidents/new_incident/'.$id) .'"> <i class="fa fa-history" style="color: green"></i> &nbsp; Record Incident </a></p>

		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#message'.$id.'"> <i class="fa fa-envelope" style="color: green"></i> &nbsp; Message Parent</a></p>
		
		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#delete'.$id.'"> <i class="fa fa-trash" style="color: red"></i> &nbsp; Delete Student</a></p>';
	}
	
	
	public function options($id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		return '<div class="modal fade" id="options'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: ' . $this->common_model->get_student_fullname($id) . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						.$this->actions($id).
					'</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function promote_student_form($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		return form_open('students_admin/promote_student/'.$id). 
			'<label>New Class</label> <br />
			<select class="form-control w-100 selectpicker" name="class_id" required>
				<option value="">Select Class</option>'
				. $this->common_model->classes_option_by_section_group(school_id) .
			'</select>
			<div class="m-t-10">
				<label>Date</label> <br />
				<input type="date" class="form-control w-100" name="last_promoted" required />
			</div>
			<div class="m-t-20">
				<button class="btn btn-primary">Promote</button>
			</div>'
		.form_close();
	}
	
	
	public function modal_promote_student($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		return	'<div class="modal fade" id="promote'.$id.'" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-form-sm">
						<div class="modal-header">
							<div class="pull-right">
								<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
							</div>
							<h4 class="modal-title"> <i class="fa fa-send"></i> Promote: ' . $this->common_model->get_student_fullname($id) . '</h4>
						</div><!--/.modal-header-->
						<div class="modal-body">'
							. $this->promote_student_form($id) .
						'</div>
					</div>
				</div>
			</div>';
	}
	
	
	public function message_parent_form($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		return form_open('students_admin/message_parent/'.$id). 
			'<div>
				<textarea class="t200 w-100 m-b-20" name="message" placeholder="Your message" required></textarea>
			</div>
			<div>
				<button class="btn btn-primary"> <i class="fa fa-arrow-circle-right"></i> Send Message</button>
			</div>'
		.form_close();
	}
	
	
	public function modal_message_parent($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		$parent_id = $y->parent_id;
		$parent_name = $this->common_model->get_parent_details_by_id($parent_id)->name;
		return '<div class="modal fade" id="message'.$id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Message: ' . $parent_name . '</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								. $this->message_parent_form($id) .
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function modal_delete_confirm($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		return '<div class="modal fade" id="delete'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Delete: ' . $this->common_model->get_student_fullname($id) . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						Are you sure you want to permanently delete ' . $this->common_model->get_student_fullname($id) . '?
					</div>
					<div class="modal-footer">
						<a class="btn btn-sm btn-danger" role="button" href="' .base_url('students_admin/delete_student/'.$id). '"> Yes, Delete </a>
						<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function modals($id) {
		return 	$this->modal_options($id) . 
				$this->modal_promote_student($id) .
				$this->modal_message_parent($id) .
				$this->modal_delete_confirm($id);
	}
	
	
	
	
}