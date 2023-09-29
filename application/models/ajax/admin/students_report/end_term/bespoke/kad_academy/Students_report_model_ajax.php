<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/* ===== Documentation ===== 
Name: Students_report_model_ajax
Role: Ajax model for jQuery DataTables
Description: Handles ajax DB processes of end-term students reports in a given class in admin's dashboard
Controller: Kad_student_end_term_reports_admin
Bespoke: KAD Academy
Author: Nwankwo Ikemefuna
Date Created: 29th June, 2018
*/


class Students_report_model_ajax extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->model('kad_students_end_term_report_model');
    }


    var $table = 'kad_end_term_student_results';
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


    public function approve_actions($result_id) {
        $r = $this->kad_students_end_term_report_model->get_result_details_by_id($result_id);
        //check approval status
        switch ($r->status) {
            case 'Pending':
                return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/approve_result/'.$result_id) .'"> <i class="fa fa-check" style="color: green"></i> &nbsp; Approve Result </a></p>
                <p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/reject_result/'.$result_id) .'"> <i class="fa fa-times" style="color: red"></i> &nbsp; Reject Result </a></p>';
            break;
            case 'Approved':
                return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/mark_result_pending/'.$result_id) .'"> <i class="fa fa-circle" style="color: #f0ad4e"></i> &nbsp; Mark as Pending </a></p>
                <p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/reject_result/'.$result_id) .'"> <i class="fa fa-times" style="color: red"></i> &nbsp; Reject Result </a></p>';
            break;
            case 'Rejected':
                return '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/mark_result_pending/'.$result_id) .'"> <i class="fa fa-circle" style="color: #f0ad4e"></i> &nbsp; Mark as Pending </a></p>
                <p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/approve_result/'.$result_id) .'"> <i class="fa fa-check" style="color: green"></i> &nbsp; Approve Result </a></p>';
            break;
        }
    }


    public function actions($result_id) {
        $r = $this->kad_students_end_term_report_model->get_result_details_by_id($result_id);
        $session = $r->session;
        $term = $r->term;
        $class_id = $r->class_id;
        $student_id = $r->student_id;
        $student_report_url = $session.'/'.$term.'/'.$class_id.'/'.$student_id;
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;

        return $this->approve_actions($result_id) . 

        '<p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/produce_report/'.$student_report_url) .'"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Report </a></p>

        <p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn clickable" data-toggle="modal" data-target="#resumption_class'.$result_id.'"> <i class="fa fa-send" style="color: green"></i> &nbsp; Resumption Class </a></p>
        
        <p><a type="button" class="btn btn-default btn-sm btn-block action-btn clickable" href="' . base_url($this->c_controller.'/report_card/'.$template_id.'/'.$student_report_url) .'"> <i class="fa fa-eye" style="color: green"></i> &nbsp; View Report Card </a></p>';
    }
    
    
    public function options($result_id) {
        return '<div class="text-center"><a type="button" href="#" class="btn btn-primary btn-sm modal-toggle-btn clickable" data-toggle="modal" data-target="#options'.$result_id.'" title="Options"> <i class="fa fa-navicon"></i> </a></div>';
    }
    
    
    public function modal_options($result_id) {
        $r = $this->kad_students_end_term_report_model->get_result_details_by_id($result_id);
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
                        .$this->actions($result_id).
                    '</div>
                </div>
            </div>
        </div>';
    } 


    public function set_resumption_class_form($result_id) {
        $r = $this->kad_students_end_term_report_model->get_result_details_by_id($result_id);
        $class_id = $r->class_id;
        $class = $this->common_model->get_class_details($class_id)->class;
        $slug = $this->common_model->get_class_details($class_id)->slug;
        return form_open($this->c_controller.'/set_resumption_class/'.$result_id). 
            '<div>Select class in which the selected students will resume into next term. This helps to:
                <ul>
                    <li>Indicate class to be promoted to on the report card.</li>
                    <li>Retrieve fees payable for next term.</li>
                </ul>
                <p>Ignore if same class.</p>
                <p>Note: Specifiying a resumption class does not automatically promote a student to the specified resumption class. Promotion can only be done in the <a class="underline-link" href="' . base_url('students_admin/single_class/'.$slug) . '" target="_blank">class page</a>. All promotions should be carried out after report production is fully completed and issued out (ideally at the begining of a new term).</p>
            </div>
            <label>Resumption Class</label>
            <select class="form-control w-100 selectpicker" name="resumption_class_id" required>
                <option value="' . $class_id . '">' . $class . '</option>'
                . $this->common_model->classes_option_by_section_group(school_id) .
            '</select>
            <div class="m-t-20">
                <button class="btn btn-primary">Submit</button>
            </div>'
        . form_close();
    }
    
    
    public function modal_set_resumption_class($result_id) {
        $r = $this->kad_students_end_term_report_model->get_result_details_by_id($result_id);
        $student_id = $r->student_id;
        $student_name = $this->common_model->get_student_fullname($student_id);
        return  '<div class="modal fade" id="resumption_class'.$result_id.'" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content modal-form-sm">
                        <div class="modal-header">
                            <div class="pull-right">
                                <button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
                            </div>
                            <h4 class="modal-title"> <i class="fa fa-line-chart"></i> Resumption Class: ' . $student_name . '</h4>
                        </div><!--/.modal-header-->
                        <div class="modal-body">'
                            . $this->set_resumption_class_form($result_id) .
                        '</div>
                    </div>
                </div>
            </div>';
    }
    
    
    public function modals($result_id) {
        return  $this->modal_options($result_id) .
                $this->modal_set_resumption_class($result_id);
    }
    
    
    
    
}