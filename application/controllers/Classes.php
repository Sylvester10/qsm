<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Classes
Role: Controller
Description: controls school class management
Model: Classes_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
Date Modified: 21st January, 2019
*/


class Classes extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->admin_restricted(); //allow only logged in users to access this class
        $this->load->model('classes_model');
        $this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
        //get school id
        $this->school_id = $this->admin_details->school_id;
        require_once "application/core/constants/Session.php"; //require session constants

        //module-level scripts
        $this->admin_module_scripts = array('s_classes');
    }




    /* ====== Classes ====== */
    public function index() {
        $inner_page_title = 'Classes (' .count($this->common_model->get_classes(school_id)). ')'; 
        $this->admin_header('Classes', $inner_page_title);  
        $this->load->model('fees_model');
        $data['sections'] = $this->common_model->get_sections(school_id);
        $data['teachers'] = $this->common_model->get_staff_by_role(school_id, 'Class Teacher');
        $data['next_level'] = $this->classes_model->get_class_next_order_level();
        $this->load->view('admin/classes/classes', $data);
        $this->admin_footer();
    }

    
    public function add_new_class_ajax() {  
        $class_teacher_id = $this->input->post('class_teacher_id', TRUE);
        $this->form_validation->set_rules('class', 'Class', 'trim|required|callback_check_class_exists');
        if ($class_teacher_id != NULL) {
            $this->form_validation->set_rules('class_teacher_id', 'Class Teacher', 'callback_check_class_teacher_exists');
        } else {
            $this->form_validation->set_rules('class_teacher_id', 'Class Teacher', 'trim');
        }
        $this->form_validation->set_rules('level', 'Level', 'trim|required');
        $this->form_validation->set_rules('order_level', 'Order Level', 'trim|required');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required');
        if ($this->form_validation->run())  {       
            $this->classes_model->add_new_class();
            echo 1; 
        } else { 
            echo validation_errors();
        }
    }
    
    
    public function edit_class($class_id) {
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $y = $this->common_model->get_class_details($class_id);
        $class = $this->input->post('class', TRUE);
        //if the selected class is different from the current class, make class field unique
        if ( $y->class != $class ) {
            $this->form_validation->set_rules('class', 'Class', 'trim|required|callback_check_class_exists');
        } else {
            $this->form_validation->set_rules('class', 'Class', 'trim|required');
        }
        $this->form_validation->set_rules('level', 'Level', 'trim|required');
        $this->form_validation->set_rules('order_level', 'Order Level', 'trim|required');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required');
        
        if ($this->form_validation->run())  {   
            $this->classes_model->edit_class($class_id);
            $this->session->set_flashdata('status_msg', 'Class updated successfully.');
            redirect(site_url('classes'));
        } else { 
            $this->index(); //reload page with validation errors
        }
    }


    public function check_class_exists() {
        //callback function to check if class exists    
        $class = $this->input->post('class', TRUE);
        $query = $this->classes_model->check_class_exists($class);
        if ($query == 0) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_class_exists', "{$class} already exists.");
            return FALSE;
        }
    }
    

    public function change_class_teacher($class_id) {
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $y = $this->common_model->get_class_details($class_id);
        $class_teacher_id = $this->input->post('class_teacher_id', TRUE);
        //if the selected class teacher is different from the current one, make field unique
        if ( $y->class_teacher_id != $class_teacher_id ) {
            $this->form_validation->set_rules('class_teacher_id', 'Class Teacher', 'callback_check_class_teacher_exists');
        } else {
            $this->form_validation->set_rules('class_teacher_id', 'Class Teacher', 'trim');
        }
        
        if ($this->form_validation->run())  {   
            $this->classes_model->change_class_teacher($class_id);
            $this->session->set_flashdata('status_msg', 'Class Teacher updated successfully.');
            redirect(site_url('classes'));
        } else { 
            $this->index(); //reload page with validation errors
        }
    }

    
    public function check_class_teacher_exists() {
        $class_teacher_id = $this->input->post('class_teacher_id', TRUE);
        $class_teacher_name = $this->common_model->get_staff_details_by_id($class_teacher_id)->name;
        $query = $this->classes_model->check_class_teacher_exists($class_teacher_id);
        //if class is already assigned, make class teacher field unique 
        if ($query == 0) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_class_teacher_exists', "{$class_teacher_name} is already assigned to another class.");
            return FALSE;
        }
    }
    
    
    public function unassign_class($class_id) { 
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $y = $this->common_model->get_class_details($class_id);
        if ($y->class_teacher != 'Unassigned') {
            $this->classes_model->unassign_class($class_id);
            $this->session->set_flashdata('status_msg', "Class unassigned successfully. You may now assign this class to another teacher.");
        } else {
            $this->session->set_flashdata('status_msg_error', "Class already unassigned!");
        }
        redirect(site_url('classes'));
    }
    
    
    public function message_class_teacher($class_id) { 
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
        $y = $this->common_model->get_class_details($class_id);
        if ($this->form_validation->run())  {
            if ($y->class_teacher_email != 'Unassigned') {
                $this->classes_model->message_class_teacher($class_id);
                $class_teacher = $this->common_model->get_staff_details($y->class_teacher_email)->name;
                $this->session->set_flashdata('status_msg', "Message successfully sent to {$class_teacher}.");
            } else {
                $this->session->set_flashdata('status_msg_error', 'No class teacher assigned to this class.');
            }
        } else {
            $this->session->set_flashdata('status_msg_error', 'Error sending message to class teacher.');
        }
        redirect($this->agent->referrer());
    }
    
    
    public function delete_class($class_id) { 
        //check if demo user
        $this->demo_action_restricted_admin();
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $this->classes_model->delete_class($class_id);
        $this->session->set_flashdata('status_msg', 'Class deleted successfully.');
        redirect(site_url('classes'));
    }
    
    


}