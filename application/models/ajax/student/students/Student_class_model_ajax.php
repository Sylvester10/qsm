<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Single_class_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of students in a given class in student's dashboard
Controller: Student
Author: Nwankwo Ikemefuna
Date Created: 28th August, 2018
*/


class Student_class_model_ajax extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('student_model');
		$this->student_details = $this->common_model->get_student_details_by_reg_id($this->session->student_reg_id);
	}


	var $table = 'students';
	var $column_order = array(null, 'id', 'last_name', 'first_name', 'other_name', 'sex', 'date_registered'); //set column field database for datatable orderable
	var $column_search = array('id', 'last_name', 'first_name', 'other_name', 'sex', 'date_registered'); //set column field database for datatable searchable 
	var $order = array('last_name' => 'asc'); 

	
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
		$class_id = $this->student_details->class_id;
		$this->db->where(array('school_id' => school_id, 'class_id' => $class_id, 'revoked' => 'false', 'graduated' => 'false'));
	    $query = $this->db->get();
		return $query->result();
	}
	

	function count_filtered_records() {
		$this->the_query();
		$class_id = $this->student_details->class_id;
		$this->db->where(array('school_id' => school_id, 'class_id' => $class_id, 'revoked' => 'false', 'graduated' => 'false'));
	    $query = $this->db->get();
		return $query->num_rows();
	}
	
	
	public function count_all_records() {
		$class_id = $this->student_details->class_id;
		$this->db->where(array('school_id' => school_id, 'class_id' => $class_id, 'revoked' => 'false', 'graduated' => 'false'));
	    $this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	
	
	
}