<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Search_student_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of all students in admin's dashboard
Controller: Students_admin
Author: Nwankwo Ikemefuna
Date Created: 15th June, 2018
*/


class Search_student_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('admin_model');
		
	}


	var $table = 'students';
	var $column_search = array('id', 'reg_id', 'admission_id', 'last_name', 'first_name', 'other_name', 'date_of_birth', 'sex', 'blood_group', 'place_of_birth', 'nationality', 'state_of_origin', 'local_gov', 'home_town', 'home_address', 'present_school', 'present_school_address', 'present_class', 'class_id', 'passport_photo', 'pass_reset_code', 'date_registered'); //set column field database for datatable searchable 
	var $order = array('date_registered' => 'desc'); 

	
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
	}
	

	function get_records() {
		$this->the_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where(array('school_id' => school_id));
		$query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records() {
		$this->the_query();
		$this->db->where(array('school_id' => school_id));
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records() {
		$this->db->where(array('school_id' => school_id));
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	
	public function actions($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		$class = $this->common_model->get_class_details($y->class_id)->class;
		$slug = get_slug($class);
		return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('students_admin/student_profile/'.$id) .'"> <i class="fa fa-user" style="color: green"></i> &nbsp; View Profile</a></p>

		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('students_admin/edit_student/'.$id) .'"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Student </a></p>

		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('students_admin/associate_parent/'.$id) .'"> <i class="fa fa-plug" style="color: green"></i> &nbsp; Associate Parent </a></p>
		
		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('students_admin/single_class/'.$slug) .'"> <i class="fa fa-eye" style="color: green"></i> &nbsp; View Student\'s Class </a></p>

		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('incidents/new_incident/'.$id) .'"> <i class="fa fa-history" style="color: green"></i> &nbsp; Record Incident </a></p>
		
		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#s_message'.$id.'"> <i class="fa fa-envelope" style="color: green"></i> &nbsp; Message Parent</a></p>
		
		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#s_suspend'.$id.'"> <i class="fa fa-ban" style="color: red"></i> &nbsp; Suspend Student </a></p>
		
		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#s_revoke'.$id.'"> <i class="fa fa-times" style="color: red"></i> &nbsp; Revoke Admission </a></p>';
	}
	
	
	public function options($id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#s_options'.$id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		return '<div class="modal fade" id="s_options'.$id.'" role="dialog">
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
		return '<div class="modal fade" id="s_message'.$id.'" role="dialog">
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
	
	
	public function suspension_duration_form($id) {
		return form_open('students_admin/suspend_student/'.$id). 
			'<div>
				<label>Reason for Suspension</label> <br />
				<textarea class="t100 w-100" name="suspension_info" required></textarea>
			</div>
			<div class="m-t-10">
				<label>Suspension Duration</label> <br />
				<input class="form-control w-100" name="suspension_duration" required />
			</div>
			<div class="m-t-10">
				<button class="btn btn-primary">Suspend</button>
			</div>'
		.form_close();
	}
	
	
	public function modal_suspend_student($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		return	'<div class="modal fade" id="s_suspend'.$id.'" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-form-sm">
						<div class="modal-header">
							<div class="pull-right">
								<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
							</div>
							<h4 class="modal-title"> <i class="fa fa-ban"></i> Suspend: ' . $this->common_model->get_student_fullname($id) . '</h4>
						</div><!--/.modal-header-->
						<div class="modal-body">'
							. $this->suspension_duration_form($id) .
						'</div>
					</div>
				</div>
			</div>';
	}
	
	
	public function revoke_student_form($id) {
		return form_open('students_admin/revoke_student_admission/'.$id). 
			'<div>
				<label>Reason for Revoke</label> <br />
				<textarea class="t100 w-100" name="revoke_info" required></textarea>
			</div>
			<div class="m-t-10">
				<button class="btn btn-primary">Revoke</button>
			</div>'
		.form_close();
	}
	
	
	public function modal_revoke_student($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		return	'<div class="modal fade" id="s_revoke'.$id.'" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-form-sm">
						<div class="modal-header">
							<div class="pull-right">
								<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
							</div>
							<h4 class="modal-title"> <i class="fa fa-times"></i> Revoke: ' . $this->common_model->get_student_fullname($id) . '</h4>
						</div><!--/.modal-header-->
						<div class="modal-body">'
							. $this->revoke_student_form($id) .
						'</div>
					</div>
				</div>
			</div>';
	}

	
	
	public function modals($id) {
		return 	$this->modal_options($id) . 
				$this->modal_message_parent($id) .
				$this->modal_suspend_student($id) .
				$this->modal_revoke_student($id);
	}
	
	
	
	
}