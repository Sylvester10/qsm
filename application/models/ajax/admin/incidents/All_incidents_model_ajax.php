<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: All_incidents_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of all incidents in admin's dashboard
Controller: Incidents
Author: Nwankwo Ikemefuna
Date Created: 18th June, 2018
*/


class All_incidents_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('incidents_model');
	}

	var $table = 'incidents';
	var $column_order = array(null, 'id', 'student_id', 'caption', 'description', 'incident_date', 'actions_taken', 'date_added'); //set column field database for datatable orderable
	var $column_search = array('id', 'student_id', 'caption', 'description', 'incident_date', 'actions_taken', 'date_added'); //set column field database for datatable searchable 
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
		$this->db->where('school_id', school_id);
		$query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records() {
		$this->the_query();
		$this->db->where('school_id', school_id);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records() {
		$this->db->where('school_id', school_id);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}


	public function actions($incident_id) {
		$y = $this->incidents_model->get_incident_details($incident_id);
		$student_id = $y->student_id;
		$student_name = $this->common_model->get_student_fullname($student_id);
		$first_name = get_firstname($student_name);
		return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('incidents/edit_incident/'.$incident_id) .'"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; View / Edit Incident </a></p>
		
		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('incidents/evidence/'.$incident_id) .'"> <i class="fa fa-file-archive-o" style="color: green"></i> &nbsp; Add / Manage Evidence </a></p>

		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('incidents/student_incidents/'.$student_id) .'"> <i class="fa fa-user" style="color: green"></i> &nbsp;' . $first_name . '\'s Incidents </a></p>

		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#delete'.$incident_id.'"> <i class="fa fa-trash" style="color: red"></i> &nbsp; Delete Incident</a></p>';
	}
	
	
	public function options($incident_id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$incident_id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($incident_id) {
		$y = $this->incidents_model->get_incident_details($incident_id);
		return '<div class="modal fade" id="options'.$incident_id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: ' . $y->caption . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						. $this->actions($incident_id) .
					'</div>
				</div>
			</div>
		</div>';
	} 
	
	
	
	public function modal_delete_confirm($incident_id) {
		$y = $this->incidents_model->get_incident_details($incident_id);
		return '<div class="modal fade" id="delete'.$incident_id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Delete: ' . $y->caption . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						Are you sure you want to permanently delete this incident?
					</div>
					<div class="modal-footer">
						<a class="btn btn-sm btn-danger" role="button" href="' .base_url('incidents/delete_incident/'.$incident_id). '"> Yes, Delete </a>
						<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function modals($incident_id) {
		return 	$this->modal_options($incident_id) . 
				$this->modal_delete_confirm($incident_id);
	}
	
	
	
	
}