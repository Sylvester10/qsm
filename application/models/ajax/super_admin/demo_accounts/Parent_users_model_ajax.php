<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Parent_users_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of demo parent users
Controller: Demo_accounts
Author: Nwankwo Ikemefuna
Date Created: 12th November, 2018
*/


class Parent_users_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('demo_accounts_model');
		$this->demo_school_id = demo_school_id;
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
	

	function get_records() {
		$this->the_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$where = array(
			'school_id' => $this->demo_school_id,
			'demo_user' => 'true',
		);
		$this->db->where($where);
	    $query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records() {
		$this->the_query();
		$where = array(
			'school_id' => $this->demo_school_id,
			'demo_user' => 'true',
		);
		$this->db->where($where);
	    $query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records() {
		$where = array(
			'school_id' => $this->demo_school_id,
			'demo_user' => 'true',
		);
		$this->db->where($where);
	    $this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	
	public function actions($id) {
		return '<p><a type="button" href="' . base_url('demo_accounts/super_user_login_parent/'.$id) .'" class="btn btn-default btn-sm btn-block action-btn clickable" target="_blank"> <i class="fa fa-sign-in" style="color: green"></i> &nbsp; Login as Super User </a></p>
		
		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#edit'.$id.'"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Demo Role </a></p>';
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
	
	
	private function demo_roles($demo_role) {
		//get admin demo roles
		$roles = demo_roles_parent();
		$role_options ="";
		foreach ($roles as $key => $value) {
			$selected = ($demo_role == $key) ? 'selected' : NULL;
			$role_options .= '<option ' . $selected . ' value="'. $key . '">' . $value . '</option>';
		} 
		return $role_options;
	}
	
	
	public function edit_parent_form($id) {
		$y = $this->common_model->get_parent_details_by_id($id);
		return form_open('demo_accounts/edit_parent/'.$id).
			'<div class="m-b-10">
				<label>Demo Role</label> <br />
				<select class="form-control w-100" name="demo_role" required>'
					. $this->demo_roles($y->demo_role) . 
				'</select>
			</div>	
			<div class="m-t-10">
				<button class="btn btn-primary">Submit</button>
			</div>'
		. form_close();
	}


	public function modal_edit_parent($id) {
		$y = $this->common_model->get_parent_details_by_id($id);
		return '<div class="modal fade" id="edit'.$id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form-sm">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Edit: ' . $y->name . '</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								. $this->edit_parent_form($id) .
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function modal_delete_confirm($id) {
		$y = $this->common_model->get_parent_details_by_id($id);
		return '<div class="modal fade" id="delete'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Delete: ' .$y->name. '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						Are you sure you want to permanently delete ' .$y->name. '?
					</div>
					<div class="modal-footer">
						<a class="btn btn-sm btn-danger" role="button" href="' .base_url('demo_accounts/delete_parent/'.$id). '"> Yes, Delete </a>
						<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function modals($id) {
		return 	$this->modal_options($id) . 
				$this->modal_edit_parent($id) . 
				$this->modal_delete_confirm($id);
	}
	
	
	
	
}