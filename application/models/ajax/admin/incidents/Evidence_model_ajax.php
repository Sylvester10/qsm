<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Evidence_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of all incidents' evidences in admin's dashboard
Controller: Incidents
Author: Nwankwo Ikemefuna
Date Created: 18th June, 2018
*/


class Evidence_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('incidents_model');
	}

	var $table = 'incident_evidence';
	var $column_order = array(null, 'id', 'incident_id', 'evidence'); //set column field database for datatable orderable
	var $column_search = array('id', 'incident_id', 'evidence'); //set column field database for datatable searchable 
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
	

	function get_records($incident_id) {
		$this->the_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where('incident_id', $incident_id);
		$query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records($incident_id) {
		$this->the_query();
		$this->db->where('incident_id', $incident_id);
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records($incident_id) {
		$this->db->where('incident_id', $incident_id);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}


	public function options($evidence_id) {
		$y = $this->incidents_model->get_evidence_details($evidence_id);
		$download_path = base_url('assets/uploads/incidents/'.$y->evidence);
		return '<a type="button" href="' . $download_path . '" class="btn btn-primary btn-sm clickable" title="Download file" target="_blank"> <i class="fa fa-download"></i></a>

		<a type="button" href="#" class="btn btn-danger btn-sm clickable" data-toggle="modal" data-target="#delete'.$evidence_id.'" title="Delete file"> <i class="fa fa-trash"></i></a>';
	}
	
	
	public function modal_delete_confirm($evidence_id) {
		$y = $this->incidents_model->get_evidence_details($evidence_id);
		return '<div class="modal fade" id="delete'.$evidence_id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Delete File</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						Are you sure you want to permanently delete this file?
					</div>
					<div class="modal-footer">
						<a class="btn btn-sm btn-danger" role="button" href="' .base_url('incidents/delete_evidence/'.$evidence_id). '"> Yes, Delete </a>
						<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function modals($evidence_id) {
		return 	$this->modal_delete_confirm($evidence_id);
	}
	
	
	
	
}