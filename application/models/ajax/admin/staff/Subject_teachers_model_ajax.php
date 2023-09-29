<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Subject_teachers_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of subject teachers members in admin dashboard
Controller: School_staff
Author: Nwankwo Ikemefuna
Date Created: 29th October, 2018
*/


class Subject_teachers_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('admin_model');
	}

	var $table = 'subject_teachers';
	var $column_order = array(null, 'id', 'school_id', 'staff_id'); //set column field database for datatable orderable
	var $column_search = array('id', 'school_id', 'staff_id'); //set column field database for datatable searchable 
	var $order = array('id' => 'desc');

	
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
		$s = $this->common_model->get_subject_teacher_details_by_id($id);
		$staff_id = $s->staff_id;
		return '<p><a type="button" href="' . base_url('school_staff/staff_profile/'.$staff_id) .'" class="btn btn-default btn-sm btn-block action-btn clickable"> <i class="fa fa-user" style="color: green"></i> &nbsp; View Profile </a></p>

		<p><a type="button" href="' . base_url('school_staff/subject_teacher_assignment/'.$id) .'" class="btn btn-default btn-sm btn-block action-btn clickable"> <i class="fa fa-tasks" style="color: green"></i> &nbsp;  Assign Classes & Subjects </a></p>

		<p><a type="button" href="' . base_url('school_staff/staff_role/'.$staff_id) .'" class="btn btn-default btn-sm btn-block action-btn clickable"> <i class="fa fa-tasks" style="color: green"></i> &nbsp; Manage Role </a></p>

		<p><a type="button" href="' . base_url('school_staff/edit_staff/'.$staff_id) .'" class="btn btn-default btn-sm btn-block action-btn clickable"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Teacher </a></p>
		
		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#message'.$id.'"> <i class="fa fa-envelope" style="color: green"></i> &nbsp; Message Teacher </a></p>

		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#remove'.$id.'"> <i class="fa fa-trash" style="color: red"></i> &nbsp; Remove (as Subject Teacher) </a></p>';
	}
	
	
	public function options($id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($id) {
		$s = $this->common_model->get_subject_teacher_details_by_id($id);
		$staff_id = $s->staff_id;
		$y = $this->common_model->get_staff_details_by_id($staff_id);
		return '<div class="modal fade" id="options'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: ' .$y->name. '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						.$this->actions($id).
					'</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function message_staff_form($id) {
		$s = $this->common_model->get_subject_teacher_details_by_id($id);
		$staff_id = $s->staff_id;
		return form_open('school_staff/message_staff/'.$staff_id). 
			'<div>
				<textarea class="t200 w-100 m-b-20" name="message" placeholder="Your message" required></textarea>
			</div>
			<div>
				<button class="btn btn-primary"> <i class="fa fa-arrow-circle-right"></i> Send Message</button>
			</div>'
		. form_close();
	}
	
	
	public function modal_message_staff($id) {
		$s = $this->common_model->get_subject_teacher_details_by_id($id);
		$staff_id = $s->staff_id;
		$y = $this->common_model->get_staff_details_by_id($staff_id);
		return '<div class="modal fade" id="message'.$id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Message: ' .$y->name. '</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								.$this->message_staff_form($id).
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function modal_remove_confirm($id) {
		$s = $this->common_model->get_subject_teacher_details_by_id($id);
		$staff_id = $s->staff_id;
		$y = $this->common_model->get_staff_details_by_id($staff_id);
		return '<div class="modal fade" id="remove'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Delete: ' .$y->name. '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						Are you sure you want to remove ' .$y->name. ' as Subject Teacher?
					</div>
					<div class="modal-footer">
						<a class="btn btn-sm btn-danger" role="button" href="' .base_url('school_staff/remove_subject_teacher/'.$id). '"> Yes, Remove </a>
						<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function modals($id) {
		return 	$this->modal_options($id) . 
				$this->modal_message_staff($id) .
				$this->modal_remove_confirm($id);
	}
	
	
	
	
}