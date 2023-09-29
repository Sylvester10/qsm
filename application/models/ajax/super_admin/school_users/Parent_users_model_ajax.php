<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Parent_users_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of school parent users
Controller: School_users
Author: Nwankwo Ikemefuna
Date Created: 17th November, 2018
*/


class Parent_users_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	var $table = 'parents';
	var $column_order = array(null, 'id', 'name', 'email'); //set column field database for datatable orderable
	var $column_search = array('id', 'name', 'email'); //set column field database for datatable searchable 
	var $order = array('id' => 'asc');

	
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
	

	function get_records($school_id) {
		$this->the_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$where = array(
			'school_id' => $school_id,
		);
		$this->db->where($where);
	    $query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records($school_id) {
		$this->the_query();
		$where = array(
			'school_id' => $school_id,
		);
		$this->db->where($where);
	    $query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records($school_id) {
		$where = array(
			'school_id' => $school_id,
		);
		$this->db->where($where);
	    $this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	
	public function actions($id) {
		return '<p><a type="button" href="' . base_url('school_users/super_user_login_parent/'.$id) .'" class="btn btn-default btn-sm btn-block action-btn clickable" target="_blank"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Login as Super User </a></p>';
	}
	
	
	public function options($id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($id) {
		$y = $this->common_model->get_parent_details_by_id($id);
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

	
	public function modals($id) {
		return 	$this->modal_options($id);
	}
	
	
	
	
	
}