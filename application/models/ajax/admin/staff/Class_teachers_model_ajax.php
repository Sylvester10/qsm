<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Class_teachers_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of class teachers members in admin dashboard
Controller: Admin
Author: Nwankwo Ikemefuna
Date Created: 18th June, 2018
*/


class Class_teachers_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('admin_model');
	}

	var $table = 'staff';
	var $column_order = array(null, 'id', 'title', 'name', 'email', 'phone', 'role', 'designation', 'qualification', 'employment_date', 'passport_photo'); //set column field database for datatable orderable
	var $column_search = array('id', 'title', 'name', 'email', 'phone', 'designation', 'role', 'qualification', 'employment_date', 'passport_photo'); //set column field database for datatable searchable 
	var $order = array('date_added' => 'desc');

	
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
		$this->db->where(array('school_id' => school_id, 'designation' => 'Class Teacher'));
	    $query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records() {
		$this->the_query();
		$this->db->where(array('school_id' => school_id, 'designation' => 'Class Teacher'));
	    $query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records() {
		$this->db->where(array('school_id' => school_id, 'designation' => 'Class Teacher'));
	    $this->db->from($this->table);
		return $this->db->count_all_results();
	}


	public function actions($id) {
		return '<p><a type="button" href="' . base_url('school_staff/staff_profile/'.$id) .'" class="btn btn-default btn-sm btn-block action-btn clickable"> <i class="fa fa-user" style="color: green"></i> &nbsp; View Profile </a></p>
		
		<p><a type="button" href="' . base_url('school_staff/edit_staff/'.$id) .'" class="btn btn-default btn-sm btn-block action-btn clickable"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Teacher </a></p>

		<p><a type="button" href="' . base_url('school_staff/staff_role/'.$id) .'" class="btn btn-default btn-sm btn-block action-btn clickable"> <i class="fa fa-tasks" style="color: green"></i> &nbsp; Manage Role </a></p>

		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#message'.$id.'"> <i class="fa fa-envelope" style="color: green"></i> &nbsp; Message Teacher </a></p>';
	}
	
	
	public function options($id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($id) {
		$y = $this->common_model->get_staff_details_by_id($id);
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
		$y = $this->common_model->get_staff_details_by_id($id);
		return form_open('school_staff/message_staff/'.$y->id). 
			'<div>
				<textarea class="t200 w-100 m-b-20" name="message" placeholder="Your message" required></textarea>
			</div>
			<div>
				<button class="btn btn-primary"> <i class="fa fa-arrow-circle-right"></i> Send Message</button>
			</div>'
		.form_close();
	}
	
	
	public function modal_message_staff($id) {
		$y = $this->common_model->get_staff_details_by_id($id);
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
	
	
	public function modals($id) {
		return 	$this->modal_options($id) . 
				$this->modal_message_staff($id);
	}
	
	
	
	
}