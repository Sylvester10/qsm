<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Search_student_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of all searched students in staff's dashboard
Controller: Students_staff
Author: Nwankwo Ikemefuna
Date Created: 15th June, 2018
*/


class Search_student_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('staff_model');
	}

	var $table = 'students';
	var $column_search = array('id', 'admission_id', 'last_name', 'first_name', 'other_name', 'date_of_birth', 'sex', 'blood_group', 'place_of_birth', 'nationality', 'state_of_origin', 'local_gov', 'home_town', 'home_address', 'present_school', 'present_school_address', 'present_class', 'class_id', 'passport_photo', 'date_registered'); //set column field database for datatable searchable 
	var $order = array('date_registered' => 'desc'); 

	
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
	}
	

	function get_records() {
		$this->the_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where(array('school_id' => school_id));
		$query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records() {
		$this->the_query();
		$this->db->where(array('school_id' => school_id));
		$query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records() {
		$this->db->where(array('school_id' => school_id));
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	
	public function student_passport($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		return '<img class="search_photo img-round" src="' .base_url('assets/uploads/photos/students/'.$y->passport). '" />';
	}
	
	
	public function actions($id) {
		return '<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#s_message'.$id.'"> <i class="fa fa-envelope" style="color: green"></i> &nbsp; Message Parent</a></p>';
	}
	
	
	public function options($id) {
		return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#s_options'.$id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
	}
	
	
	public function modal_options($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		return '<div class="modal fade" id="s_options'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Actions: ' . $this->common_model->get_student_fullname($id) . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						.$this->actions($id).
					'</div>
				</div>
			</div>
		</div>';
	} 
	
	
	public function message_parent_form($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		return form_open('students_staff/message_parent/'.$id). 
			'<div>
				<textarea class="t200 w-100 m-b-20" name="message" placeholder="Your message" required></textarea>
			</div>
			<div>
				<button class="btn btn-primary"> <i class="fa fa-arrow-circle-right"></i> Send Message</button>
			</div>'
		.form_close();
	}
	
	
	public function modal_message_parent($id) {
		$y = $this->common_model->get_student_details_by_id($id);
		$parent_id = $y->parent_id;
		$parent_name = $this->common_model->get_parent_details_by_id($parent_id)->name;
		return '<div class="modal fade" id="s_message'.$id.'" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content modal-form">
							<div class="modal-header">
								<div class="pull-right">
									<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
								</div>
								<h4 class="modal-title">Message: ' . $parent_name . '</h4>
							</div><!--/.modal-header-->
							<div class="modal-body">'
								. $this->message_parent_form($id) .
							'</div>
						</div>
					</div>
				</div>';
	}
	
	
	public function modals($id) {
		return 	$this->modal_options($id) . 
				$this->modal_message_parent($id);
	}
	
	
	
	
}