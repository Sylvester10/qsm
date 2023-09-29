<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Notifs_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of admin notifications
Controller: Admin
Author: Nwankwo Ikemefuna
Date Created: 20th April, 2018
*/


class Notifs_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('admin_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
	}
	
	var $table = 'admin_notifs';
	var $column_order = array(null, 'id', 'subject', 'message', 'read', 'date'); //set column field database for datatable orderable
	var $column_search = array('id', 'subject', 'message', 'read', 'date'); //set column field database for datatable searchable 
	var $order = array('date' => 'desc');

	
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
		$this->db->where('user_id', $this->admin_details->id);
	    $query = $this->db->get();
		return $query->result();
	}

	
	function count_filtered_records() {
		$this->the_query();
		$this->db->where('user_id', $this->admin_details->id);
	    $query = $this->db->get();
		return $query->num_rows();
	}

	
	public function count_all_records() {
		$this->db->where('user_id', $this->admin_details->id);
	    $this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	
	public function options($id) {
		return '<a type="button" href="#" class="btn btn-danger btn-sm modal-toggle-btn m-t-15 clickable" data-toggle="modal" data-target="#delete'.$id.'" title="Delete Notification"> <i class="fa fa-trash"></i> </a>';
	}
	
	
	public function modal_delete_confirm($id) {
		$y = $this->admin_model->get_notif_details($id);
		return '<div class="modal fade" id="delete'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Delete: ' .$y->subject. '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						Are you sure you want to permanently delete this notification?
					</div>
					<div class="modal-footer">
						<a class="btn btn-danger" href="' .base_url('admin/delete_notif/'.$id). '"> Yes, Delete </a>
						<button data-dismiss="modal" class="btn btn-default"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>';
	}
	
	public function modals($id) {
		return 	$this->modal_delete_confirm($id);
	}

	
	
	
}