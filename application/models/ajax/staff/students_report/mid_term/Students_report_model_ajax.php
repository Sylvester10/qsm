<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Students_report_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of students reports in a given class in staff's dashboard
Controller: Student_reports_class_teacher, Student_reports_subject_teacher
Author: Nwankwo Ikemefuna
Date Created: 29th June, 2018
*/


class Students_report_model_ajax extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->model('students_mid_term_report_model');
    }


    var $table = 'mid_term_student_results';
    var $column_order = array(null, 'id', 'student_id', 'total_scored'); //set column field database for datatable orderable
    var $column_search = array('id', 'student_id', 'total_scored'); //set column field database for datatable searchable 
    var $order = array('total_scored' => 'desc'); //order by highest score

    
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
    

    function get_records($session, $term, $class_id) {
        $this->the_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $this->db->where(array('session' => $session, 'term' => $term, 'class_id' => $class_id));
        $query = $this->db->get();
        return $query->result();
    }
    

    function count_filtered_records($session, $term, $class_id) {
        $this->the_query();
        $this->db->where(array('session' => $session, 'term' => $term, 'class_id' => $class_id));
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    
    public function count_all_records($session, $term, $class_id) {
        $this->db->where(array('session' => $session, 'term' => $term, 'class_id' => $class_id));
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }


    public function actions($result_id) {
        $r = $this->students_mid_term_report_model->get_result_details_by_id($result_id);
        $session = $r->session;
        $term = $r->term;
        $class_id = $r->class_id;
        $student_id = $r->student_id;
        $student_report_url = $session.'/'.$term.'/'.$class_id.'/'.$student_id;

        $class_details = $this->common_model->get_class_details($class_id); 
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $template_id = $this->common_model->get_section_details($section_id)->mt_template_id;

        return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/produce_report/'.$student_report_url) .'"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Report </a></p>
        
        <p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/report_card/'.$template_id.'/'.$student_report_url) .'"> <i class="fa fa-eye" style="color: green"></i> &nbsp; View Report Card </a></p>';
    }
    
    
    public function options($result_id) {
        return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$result_id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
    }
    
    
    public function modal_options($result_id) {
        $r = $this->students_mid_term_report_model->get_result_details_by_id($result_id);
        $student_id = $r->student_id;
        $student_name = $this->common_model->get_student_fullname($student_id);
        return '<div class="modal fade" id="options'.$result_id.'" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content modal-width">
                    <div class="modal-header">
                        <div class="pull-right">
                            <button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
                        </div>
                        <h4 class="modal-title">Actions: ' . $student_name . '</h4>
                    </div><!--/.modal-header-->
                    <div class="modal-body">'
                        . $this->actions($result_id, $this->c_controller) .
                    '</div>
                </div>
            </div>
        </div>';
    } 
    
    
    public function modals($result_id) {
        return  $this->modal_options($result_id, $this->c_controller);
    }
    
    
    
    
}