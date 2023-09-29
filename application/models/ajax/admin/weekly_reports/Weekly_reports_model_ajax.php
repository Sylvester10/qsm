<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Weekly_reports_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of all weekly requests from admin's end
Controller: Weekly_reports_admin
Author: Nwankwo Ikemefuna
Date Created: 18th May, 2018
*/


class Weekly_reports_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('weekly_reports_model');
	}

	var $table = 'weekly_reports';
	var $column_order = array(null, 'id', 'ref_id', 'submitted_by', 'week', 'starting_date', 'ending_date', 'term', 'session', 'report', 'remark', 'date_submitted'); //set column field database for datatable orderable
	var $column_search = array('id', 'ref_id', 'submitted_by', 'week', 'starting_date', 'ending_date', 'term', 'session', 'report', 'remark', 'date_submitted'); //set column field database for datatable searchable 
	var $order = array('date_submitted' => 'desc'); 
 
 
	
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
	

	function get_records($session, $term) {
		$this->the_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where(array('school_id' => school_id, 'session' => $session, 'term' => $term));
		$query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records($session, $term) {
		$this->the_query();
		$this->db->where(array('school_id' => school_id, 'session' => $session, 'term' => $term));
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records($session, $term) {
		$this->db->where(array('school_id' => school_id, 'session' => $session, 'term' => $term));
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}


	public function report_type($report_id) {
		$y = $this->weekly_reports_model->get_weekly_report_details($report_id);
		$report_type_id = $y->report_type_id;
		if ($report_type_id != NULL) {
			$report_type = $this->weekly_reports_model->get_report_type_details($report_type_id)->type;
			return $report_type;
		} else {
			return NULL;
		}
	}
	
	
	public function check_remark_view($report_id) {
		$y = $this->weekly_reports_model->get_weekly_report_details($report_id);
		if ($y->remark != NULL) {
			return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#view_remark' .$report_id. '"> <i class="fa fa-eye" style="color: green"></i> &nbsp; View Remark </a></p>';
		} else {
			return '';
		}
	}
	
	
	public function check_remark_action($report_id) {
		$y = $this->weekly_reports_model->get_weekly_report_details($report_id);
		if ($y->remark != NULL) {
			return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#remark'.$report_id.'"> <i class="fa fa-commenting-o" style="color: green"></i> &nbsp; Edit Remark </a></p>';
		} else {
			return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#remark'.$report_id.'"> <i class="fa fa-commenting-o" style="color: green"></i> &nbsp; Add Remark </a></p>';
		}
	}
	
	
	public function actions($report_id) {
		$y = $this->weekly_reports_model->get_weekly_report_details($report_id);
		return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#download'.$report_id.'"> <i class="fa fa-download" style="color: green"></i> &nbsp; Download Report </a></p>'
		
		.$this->check_remark_action($report_id) . $this->check_remark_view($report_id) .
		
		'<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#delete'.$report_id.'"> <i class="fa fa-trash" style="color: red"></i> &nbsp; Delete </a></p>';
	}
	
	
	public function options($report_id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$report_id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($report_id) {
		$y = $this->weekly_reports_model->get_weekly_report_details($report_id);
		return '<div class="modal fade" id="options'.$report_id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: ' . $y->ref_id . ' (Week ' . $y->week . ')</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						.$this->actions($report_id).
					'</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function report_file($report_id) {
		$y = $this->weekly_reports_model->get_weekly_report_details($report_id);
		$the_session = get_the_session($y->session);
		$download_path = base_url('assets/uploads/weekly_reports/'.$y->report);
		return '<p style="word-break: break-all;"> Week ' . $y->week . ', ' . $y->term . ' Term, ' . $the_session . ' Session </p>
				<p class="m-t-10"><a class="btn btn-primary btn-sm" href="' . $download_path . '" target="_blank">Download</a></p>';
	}
	
	
	public function modal_download_report($report_id) {
		$y = $this->weekly_reports_model->get_weekly_report_details($report_id);
		return '<div class="modal fade" id="download'.$report_id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form-sm">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Download: ' . $y->ref_id . ' (Week ' . $y->week . ')</h4>
							</div><!--/.modal-header-->
							<div class="modal-body text-center">'
								. $this->report_file($report_id) .
							'</div>
						</div>
					</div>
				</div>';
	}
	
		
	public function remark_form($report_id) {
		$y = $this->weekly_reports_model->get_weekly_report_details($report_id);
		return form_open('weekly_reports_admin/remark_weekly_report/'.$report_id) . 
			'<div>
				<textarea class="t200 w-100 m-b-20" name="remark" placeholder="Your remark" required>' 
					. strip_tags($y->remark) . 
				'</textarea>
			</div>
			<div>
				<button class="btn btn-primary"> <i class="fa fa-arrow-circle-right"></i> Submit Remark</button>
			</div>'
		. form_close();
	}
	
	
	public function modal_remark_report($report_id) {
		$y = $this->weekly_reports_model->get_weekly_report_details($report_id);
		return '<div class="modal fade" id="remark'.$report_id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Add Remark: ' . $y->ref_id . ' (Week ' . $y->week . ')</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								. $this->remark_form($report_id) .
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function modal_view_remark($report_id) {
		$y = $this->weekly_reports_model->get_weekly_report_details($report_id);
		return '<div class="modal fade" id="view_remark'.$report_id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Remark: ' . $y->ref_id . ' (Week ' . $y->week . ')</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								. $y->remark .
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function modal_delete_confirm($report_id) {
		$y = $this->weekly_reports_model->get_weekly_report_details($report_id);
		return '<div class="modal fade" id="delete'.$report_id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Delete: ' . $y->ref_id . ' (Week ' . $y->week . ')</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						Are you sure you want to permanently delete this report?
					</div>
					<div class="modal-footer">
						<a class="btn btn-sm btn-danger" role="button" href="' .base_url('weekly_reports_admin/delete_weekly_report/'.$report_id). '"> Yes, Delete </a>
						<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function modals($report_id) {
		return 	$this->modal_options($report_id) . 
				$this->modal_download_report($report_id) .
				$this->modal_remark_report($report_id) .
				$this->modal_view_remark($report_id) .
				$this->modal_delete_confirm($report_id);
	}
	
	
	
	
}