<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Cron_jobs_school_data_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of db cron jobs
Controller: Cron_jobs
Author: Nwankwo Ikemefuna
Date Created: 2nd of January, 2019
*/


class Cron_jobs_school_data_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('cron_jobs_model');
	}
	
	var $table = 'cron_jobs_school_data';
	var $column_order = array(null, 'id', 'name', 'title', 'school_id', 'status', 'time'); //set column field database for datatable orderable
	var $column_search = array('id', 'name', 'title', 'school_id', 'status', 'time'); //set column field database for datatable searchable 
	var $order = array('time' => 'desc');

	
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
	

	function get_records($name) {
		$this->the_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where('name', $name);
		$query = $this->db->get();
		return $query->result();
	}

	
	function count_filtered_records($name) {
		$this->the_query();
		$this->db->where('name', $name);
		$query = $this->db->get();
		return $query->num_rows();
	}

	
	public function count_all_records($name) {
		$this->db->where('name', $name);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}


	public function actions($cj_id) {
		return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#delete'.$cj_id.'"> <i class="fa fa-trash" style="color: red"></i> &nbsp; Delete </a></p>';
	}
	
	
	public function options($cj_id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-danger btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#delete'.$cj_id.'" title="Delete"> <i class="fa fa-trash"></i> </a></div>';
	}
	
	
	public function modals($cj_id) {
		$y = $this->cron_jobs_model->get_cron_job_school_data_details($cj_id);
		$modal_delete_confirm = modal_delete_confirm($cj_id, $y->title, 'cron job', 'cron_jobs/delete_cron_job_school_data');
		return $modal_delete_confirm;
	}

	
	
	
}