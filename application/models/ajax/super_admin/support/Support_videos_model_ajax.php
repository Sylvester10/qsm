<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Activated_schools_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of activated school accounts
Controller: School_account
Author: Nwankwo Ikemefuna
Date Created: 17th August, 2018
*/


class Support_videos_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('support_model');
	}
	
	var $table = 'support_videos';
	var $column_order = array(null, 'id', 'title', 'description', 'url', 'category', 'date_added'); //set column field database for datatable orderable
	var $column_search = array('id', 'title', 'description', 'url', 'category', 'date_added'); //set column field database for datatable searchable 
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
		$query = $this->db->get();
		return $query->result();
	}

	
	function count_filtered_records() {
		$this->the_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	
	public function count_all_records() {
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}



	public function actions($id) {
		return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#edit'.$id.'"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit</a></p>

		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#delete'.$id.'"> <i class="fa fa-trash" style="color: red"></i> &nbsp; Delete</a></p>';
	}
	
	
	public function options($id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($id) {
		$y = $this->support_model->get_support_video_details($id);
		return '<div class="modal fade" id="options'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: ' . $y->title . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						. $this->actions($id) .
					'</div>
				</div>
			</div>
		</div>';
	} 


	public function edit_support_video_form($id) {
		$y = $this->support_model->get_support_video_details($id);
		return form_open('support/edit_video/'.$id). 
			'<div class="m-t-10">
				<label>Title</label> <br />
				<input type="text" class="form-control w-100" name="title" value="'. $y->title .'" required />
			</div>
			<div class="m-t-10">
				<label>Description</label> <br />
				<textarea class="form-control w-100" name="description" maxlength="100" required > '. $y->description .' </textarea>
			</div>
			<div class="m-t-10">
				<label>Video URL</label> <br />
				<input type="text" class="form-control w-100" name="url" value="'. $y->url .'" required />
			</div>
			<div class="m-t-10">
				<label>Category</label> <br />
				<select class="form-control w-100" name="category">
					<option value=" '. $y->category .'">'. $y->category .'</option>
					<option value="Admin"> Admin </option>
					<option value="Staff"> Staff </option>
					<option value="Student"> Student </option>
					<option value="Parent"> Parent </option>
				</select>
			</div>
			<div class="m-t-10">
				<button class="btn btn-primary">Update</button>
			</div>'
		. form_close();
	}
	
	
	public function modal_edit_support_video($id) {
		$y = $this->support_model->get_support_video_details($id);
		return	'<div class="modal fade" id="edit'.$id.'" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-form-sm">
						<div class="modal-header">
							<div class="pull-right">
								<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
							</div>
							<h4 class="modal-title"> <i class="fa fa-pencil"></i> Edit Video: ' . $y->title . '</h4>
						</div><!--/.modal-header-->
						<div class="modal-body">'
							. $this->edit_support_video_form($id) .
						'</div>
					</div>
				</div>
			</div>';
	}


	public function modal_delete_confirm($id) {
		$y = $this->support_model->get_support_video_details($id);
		return '<div class="modal fade" id="delete'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Delete: ' . $y->title . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						Are you sure you want to permanently delete ' . $y->title . '?
					</div>
					<div class="modal-footer">
						<a class="btn btn-sm btn-danger" role="button" href="' .base_url('support/delete_video/'.$id). '"> Yes, Delete </a>
						<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>';
	} 

	
	public function modals($id) {
		return 	$this->modal_options($id) . 
				$this->modal_edit_support_video($id) .
				$this->modal_delete_confirm($id);
	}

	
	
	
}