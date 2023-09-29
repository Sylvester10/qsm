<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Term_dates_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of all term dates in staff dashboard
Controller: publications_staff
Author: Nwankwo Ikemefuna
Date Created: 22nd May, 2018
*/


class Term_dates_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('publications_model');
	}

	var $table = 'term_dates';
	var $column_order = array(null, 'id', 'activity', 'term_date', 'end_date', 'date_unix', 'published', 'date'); //set column field database for datatable orderable
	var $column_search = array('id', 'activity', 'term_date', 'end_date', 'date_unix', 'published', 'date'); //set column field database for datatable searchable 
	var $order = array('date_unix' => 'asc'); //order chronologically
 
	
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
	
	
	public function check_term_date($id) {
		$y = $this->publications_model->get_term_date_details($id);
		//check if date has an end date
		if ($y->end_date == NULL || $y->end_date == '') { //no end date, return only term date
			return x_date_full($y->term_date);
		} else { //end date exists, return start and end date
			return x_date_full($y->term_date) . ' &nbsp; - &nbsp; ' . x_date_full($y->end_date);
		}
	}
	
	
	public function published_status($id) {
		$y = $this->publications_model->get_term_date_details($id);
		if ($y->published == 'true') {
			return '<b class="text-success"> Published </b>';
		} else {
			return '<b class="text-primary"> Draft </b>';
		}
	}
	
	
	public function publish_actions($id) {
		$y = $this->publications_model->get_term_date_details($id);
		if ($y->published == 'true') {
			return '<p><a type="button" href="' .base_url('publications_staff/unpublish_term_date/'.$id). '" class="btn btn-default btn-sm btn-block action-btn clickable"> <i class="fa fa-eye-slash" style="color: green"></i> &nbsp; Unpublish </a></p>';
		} else {
			return '<p><a type="button" href="' .base_url('publications_staff/publish_term_date/'.$id). '" class="btn btn-default btn-sm btn-block action-btn clickable"> <i class="fa fa-eye" style="color: green"></i> &nbsp; Publish </a></p>';
		}
	}
	
	
	public function actions($id) {
		$y = $this->publications_model->get_term_date_details($id);
		return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#edit'.$id.'"> <i class="fa fa-edit" style="color: green"></i> &nbsp; Edit </a></p>'
		
		. $this->publish_actions($id) .
		
		'<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#delete'.$id.'"> <i class="fa fa-trash" style="color: red"></i> &nbsp; Delete </a></p>';
	}
	
	
	public function options($id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($id) {
		$y = $this->publications_model->get_term_date_details($id);
		return '<div class="modal fade" id="options'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: ' .$y->activity. '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						.$this->actions($id).
					'</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function term_date_edit_form($id) {
		$y = $this->publications_model->get_term_date_details($id);
		return form_open('publications_staff/update_term_date/'. $id) .
				'<div class="m-b-10">
					<label class="form-control-label">Activity</label> <br />
					<input type="text" class="form-control w-100" name="activity" value="' .$y->activity. '" required />
				</div>
				<div class="m-b-10">
					<label class="form-control-label">Date (Format: mm/dd/yyyy)</label> <br />
					<input type="date" class="form-control w-100" name="term_date" value="' .$y->term_date.'" required />
				</div>
				<div class="m-b-10">
					<label class="form-control-label">End Date (Leave blank if not required. Format: mm/dd/yyyy)</label> <br />
					<input type="date" class="form-control w-100" name="end_date" value="' .$y->end_date.'" />
				</div>
				<div>
					<button class="btn btn-primary">Update</button>
				</div>'
			. form_close();
	}
	
	
	public function modal_edit_term_date($id) {
		$y = $this->publications_model->get_term_date_details($id);
		return '<div class="modal fade" id="edit'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-form-sm">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Update: ' .$y->activity . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						. $this->term_date_edit_form($id) .
					'</div>
				</div>
			</div>
		</div>';
	}
	
	
	public function modal_delete_confirm($id) {
		$y = $this->publications_model->get_term_date_details($id);
		return '<div class="modal fade" id="delete'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Delete: ' .$y->activity. '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						Are you sure you want to permanently delete this term date?
					</div>
					<div class="modal-footer">
						<a class="btn btn-sm btn-danger" role="button" href="' .base_url('publications_staff/delete_term_date/'.$id). '"> Yes, Delete </a>
						<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function modals($id) {
		return 	$this->modal_options($id) . 
				$this->modal_edit_term_date($id) .
				$this->modal_delete_confirm($id);
	}
	
	
	
}