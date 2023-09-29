<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Suspended_students_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of suspended students in admin's dashboard
Controller: Admin
Author: Nwankwo Ikemefuna
Date Created: 7th June, 2018
*/


class Suspended_students_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('students_admin_model');
	}


	var $table = 'students';
	var $column_order = array(null, 'id', 'reg_id', 'admission_id', 'last_name', 'first_name', 'other_name', 'date_of_birth', 'sex', 'blood_group', 'place_of_birth', 'nationality', 'state_of_origin', 'local_gov', 'home_town', 'home_address', 'present_school', 'present_school_address', 'present_class', 'class_id', 'passport_photo', 'pass_reset_code', 'date_registered'); //set column field database for datatable orderable
	var $column_search = array('id', 'reg_id', 'admission_id', 'last_name', 'first_name', 'other_name', 'date_of_birth', 'sex', 'blood_group', 'place_of_birth', 'nationality', 'state_of_origin', 'local_gov', 'home_town', 'home_address', 'present_school', 'present_school_address', 'present_class', 'class_id', 'passport_photo', 'pass_reset_code', 'date_registered'); //set column field database for datatable searchable 
	var $order = array('date_suspended' => 'desc'); 

	
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
	

	function get_records() {
		$this->the_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where(array('school_id' => school_id, 'suspended' => 'true'));
	    $query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records() {
		$this->the_query();
		$this->db->where(array('school_id' => school_id, 'suspended' => 'true'));
	    $query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records() {
		$this->db->where(array('school_id' => school_id, 'suspended' => 'true'));
	    $this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	
	public function student_bio_info($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		return  'Sex: ' . $y->sex . '<br />
				Blood Group: ' . $y->blood_group . '<br />
				Date of Birth: ' . x_date($y->date_of_birth);
	}
	
	
	public function student_place_info($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		return  'Place of Birth: ' . $y->place_of_birth . '<br />
				Nationality: ' . $y->nationality . '<br />
				State of Origin: ' . $y->state_of_origin . '<br />
				LGA of Origin: ' . $y->local_gov . '<br />
				Home Town: ' . $y->home_town . '<br />
				Home Address: ' . $y->home_address;
	}
	
	
	public function student_previous_school_info($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		return  'Former School: ' . $y->present_school . '<br />
				Former School Address: ' . $y->present_school_address . '<br />
				Last Class in Former School: ' . $y->present_class;
	}
	
	
	public function first_parent_info($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		$parent_id = $y->parent_id;
		$p = $this->common_model->get_parent_details_by_id($parent_id);
		return  'Name: ' . $p->name . '<br />
				Sex: ' . $p->sex . '<br />
				Relationship to Student: ' . $p->relationship . '<br />
				Mobile No: ' . $p->phone . '<br />
				Email Address: ' . $p->email;
	}
	
	
	public function second_parent_info($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		$parent_id = $y->parent_id;
		$p = $this->common_model->get_parent_details_by_id($parent_id);
		return 	'Name: ' . $p->sec_parent_name . '<br />
				Sex: ' . $p->sec_parent_sex . '<br />
				Relationship to Student: ' . $p->sec_parent_relationship . '<br />
				Mobile No: ' . $p->sec_parent_phone . '<br />
				Email Address: ' . $p->sec_parent_email;
	}
	
	
	public function actions($id) {
		return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('students_admin/unsuspend_student/'.$id) .'"> <i class="fa fa-circle" style="color: green"></i> &nbsp; Unsuspend Student </a></p>

		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('students_admin/student_profile/'.$id) .'"> <i class="fa fa-user" style="color: green"></i> &nbsp; View Profile</a></p>
		
		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('students_admin/edit_student/'.$id) .'"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Student </a></p>

		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('students_admin/associate_parent/'.$id) .'"> <i class="fa fa-plug" style="color: green"></i> &nbsp; Associate Parent </a></p>

		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('incidents/new_incident/'.$id) .'"> <i class="fa fa-history" style="color: green"></i> &nbsp; Record Incident </a></p>
		
		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#message'.$id.'"> <i class="fa fa-envelope" style="color: green"></i> &nbsp; Message Parent</a></p>
		
		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#revoke'.$id.'"> <i class="fa fa-times" style="color: red"></i> &nbsp; Revoke Admission </a></p>
		
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
		return	'<div class="modal fade" id="revoke'.$id.'" role="dialog">
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
				$this->modal_message_parent($id) .
				$this->modal_revoke_student($id) .
				$this->modal_delete_confirm($id);
	}
	
	
	
	
}