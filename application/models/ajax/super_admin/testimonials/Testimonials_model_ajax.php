<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Testimonials_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of testimonials
Controller: Testimonials
Author: Nwankwo Ikemefuna
Date Created: 26th December, 2018
*/


class Testimonials_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('testimonial_model');
	}
	
	var $table = 'testimonials';
	var $column_order = array(null, 'id', 'name', 'designation', 'testimony', 'rating', 'date'); //set column field database for datatable orderable
	var $column_search = array('id', 'name', 'designation', 'testimony', 'rating', 'date'); //set column field database for datatable searchable 
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


	public function publish_actions($testimonial_id) {
		$y = $this->testimonial_model->get_testimonial_details($testimonial_id);
		if ($y->published == 'false') {
			return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('testimonial/publish_testimonial/'.$testimonial_id) .'"> <i class="fa fa-eye" style="color: green"></i> &nbsp; Publish </a></p>';
		} else {
			return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('testimonial/unpublish_testimonial/'.$testimonial_id) .'"> <i class="fa fa-eye-slash" style="color: red"></i> &nbsp; Unpublish </a></p>';
		}
	}
	
	
	public function actions($testimonial_id) {
		return $this->publish_actions($testimonial_id) .
		'<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#delete'.$testimonial_id.'"> <i class="fa fa-trash" style="color: red"></i> &nbsp; Delete </a></p>';
	}
	
	
	public function options($testimonial_id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$testimonial_id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($testimonial_id) {
		$y = $this->testimonial_model->get_testimonial_details($testimonial_id);
		return '<div class="modal fade" id="options'.$testimonial_id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: ' .$y->name. '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						.$this->actions($testimonial_id).
					'</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function modals($testimonial_id) {
		$y = $this->testimonial_model->get_testimonial_details($testimonial_id);
		$modal_delete_confirm = modal_delete_confirm($testimonial_id, $y->name, 'testimony', 'testimonial/delete_testimonial');
		return 	$this->modal_options($testimonial_id) . 
				$modal_delete_confirm;
	}

	
	
	
}