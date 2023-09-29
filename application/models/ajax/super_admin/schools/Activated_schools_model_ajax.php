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


class Activated_schools_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('school_account_model');
	}
	
	var $table = 'school_info';
	var $column_order = array(null, 'id', 'school_name', 'school_location', 'country', 'chief_admin_id', 'plan_id', 'mode', 'activated', 'date_installed'); //set column field database for datatable orderable
	var $column_search = array('id', 'school_name', 'school_location', 'country', 'chief_admin_id', 'plan_id', 'mode', 'activated', 'date_installed'); //set column field database for datatable searchable 
	var $order = array('date_installed' => 'desc');

	
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
		$annual_subscription_period = annual_subscription_period;
		//query clause: where activated is true and date activated is greater than (today - annual_subscription_period days)
		$where = 	"activated = 'true' AND 
					date_activated > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$annual_subscription_period} DAY)";
	    $this->db->where($where);
		$query = $this->db->get();
		return $query->result();
	}

	
	function count_filtered_records() {
		$this->the_query();
		$annual_subscription_period = annual_subscription_period;
		//query clause: where activated is true and date activated is greater than (today - annual_subscription_period days)
		$where = 	"activated = 'true' AND 
					date_activated > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$annual_subscription_period} DAY)";
	    $this->db->where($where);
		$query = $this->db->get();
		return $query->num_rows();
	}

	
	public function count_all_records() {
		$annual_subscription_period = annual_subscription_period;
		//query clause: where activated is true and date activated is greater than (today - annual_subscription_period days)
		$where = 	"activated = 'true' AND 
					date_activated > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL {$annual_subscription_period} DAY)";
	    $this->db->where($where);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}


	public function chief_admin_info($school_id) {
		$y = $this->school_account_model->get_school_info($school_id);
		$d = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
		return  'Name: ' . $d->name . '<br />
				Phone No: ' . $d->phone . '<br />
				Email: ' . $d->email;
	}


	public function confirmation_status($school_id) {
		$y = $this->school_account_model->get_school_info($school_id);
		if ($y->confirmed == 'false') {
			return '<b class="text-danger">Unconfirmed</b>';
		} else {
			return '<b class="text-success">Confirmed</b>';
		}
	}


	public function confirm_actions($school_id) {
		$y = $this->school_account_model->get_school_info($school_id);
		if ($y->confirmed == 'false') {
			return 
			'<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('school_account/resend_email_confirmation/'.$school_id) .'"> <i class="fa fa-send" style="color: green"></i> &nbsp; Resend Email Confirmation </a></p>

			<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('school_account/confirm_school_account/'.$school_id) .'"> <i class="fa fa-check" style="color: green"></i> &nbsp; Confirm Email </a></p>';
		} 
	}


	public function actions($school_id) {

		$total_admins = count($this->common_model->get_admins($school_id));
		$total_staff = count($this->common_model->get_staff($school_id));
		$total_students = count($this->common_model->get_students($school_id));
		$total_parents = count($this->common_model->get_parents($school_id));

		return $this->confirm_actions($school_id) . 

		'<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('school_account/deactivate_school_account/'.$school_id) .'"> <i class="fa fa-times" style="color: red"></i> &nbsp; De-activate Account </a></p>

		<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#message'.$school_id.'"> <i class="fa fa-envelope" style="color: green"></i> &nbsp; Message Chief Admin</a></p>

		<hr />

		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('school_users/admins/'.$school_id) .'"> <i class="fa fa-users" style="color: green"></i> &nbsp; View Admins (' . $total_admins . ') </a></p>

		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('school_users/staff/'.$school_id) .'"> <i class="fa fa-users" style="color: green"></i> &nbsp; View Staff (' . $total_staff . ') </a></p>

		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('school_users/students/'.$school_id) .'"> <i class="fa fa-users" style="color: green"></i> &nbsp; View Students (' . $total_students . ') </a></p>

		<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url('school_users/parents/'.$school_id) .'"> <i class="fa fa-users" style="color: green"></i> &nbsp; View Parents (' . $total_parents . ') </a></p>';
		
	}
	
	
	public function options($school_id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$school_id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($school_id) {
		$y = $this->school_account_model->get_school_info($school_id);
		return '<div class="modal fade" id="options'.$school_id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: ' . $y->school_name . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						. $this->actions($school_id) .
					'</div>
				</div>
			</div>
		</div>';
	} 


	public function message_chief_admin_form($school_id) {
		return form_open('school_account/message_chief_admin/'.$school_id). 
			'<div>
				<textarea class="t200 w-100 m-b-20" name="message" placeholder="Your message" required></textarea>
			</div>
			<div>
				<button class="btn btn-primary"> <i class="fa fa-arrow-circle-right"></i> Send Message</button>
			</div>'
		. form_close();
	}
	
	
	public function modal_message_chief_admin($school_id) {
		$y = $this->school_account_model->get_school_info($school_id);
		$d = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
		return '<div class="modal fade" id="message'.$school_id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Message: ' . $d->name . '</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								. $this->message_chief_admin_form($school_id) .
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function modals($school_id) {
		return 	$this->modal_options($school_id) . 
				$this->modal_message_chief_admin($school_id);
	}

	
	
	
}