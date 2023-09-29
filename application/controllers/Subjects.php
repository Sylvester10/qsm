<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Subjects
Role: Controller
Description: controls school subject management
Model: Subjects_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
Date Modified: 21st January, 2019
*/


class Subjects extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->admin_restricted(); //allow only logged in users to access this class
        $this->load->model('subjects_model');
        $this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
        //get school id
        $this->school_id = $this->admin_details->school_id;
        require_once "application/core/constants/Session.php"; //require session constants

        //module-level scripts
        $this->admin_module_scripts = array('s_subjects');
    }




    /* ====== Subjects ====== */
    public function index() {
        $inner_page_title = 'Subjects (' .count($this->common_model->get_subjects(school_id)). ')'; 
        $this->admin_header('Subjects', $inner_page_title); 
        $data['sections'] = $this->common_model->get_sections(school_id);
        $data['subject_groups'] = $this->common_model->get_subject_groups(school_id);
        $data['subject_group_options'] = $this->common_model->subject_group_options(school_id);
        $this->load->view('admin/subjects/subjects', $data);
        $this->admin_footer();
    }


    public function add_new_subject_ajax() {    
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required|callback_check_subject_exists');
        $this->form_validation->set_rules('subject_short', 'Short Name', 'trim|required|max_length[6]');
        $this->form_validation->set_rules('subject_group_id', 'Subject Group', 'trim');
        $this->form_validation->set_rules('section_id[]', 'Section', 'trim|required');
        if ($this->form_validation->run())  {   
            $this->subjects_model->add_new_subject();
            echo 1;
        } else { 
            echo validation_errors();
        }
    }

    
    public function edit_subject($subject_id) { 
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('subject_short', 'Short Name', 'trim|required|max_length[6]');
        $this->form_validation->set_rules('subject_group_id', 'Subject Group', 'trim');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required');
        $subject = ucwords($this->input->post('subject', TRUE));
        $section_id = $this->input->post('section_id', TRUE);
        $section = $this->common_model->get_section_details($section_id)->section;
        //check if entered subject group is different from the one being edited. If different, make field unique
        $y = $this->common_model->get_subject_details($subject_id);
        if ($this->form_validation->run())  {   
            $query = $this->subjects_model->check_subject_exists($subject, $section_id);
            //ensure subject does not already exist, or it is same as current subject
            if ( $query == 0 || ($query > 0 && $y->subject == $subject) ) {
                $this->subjects_model->edit_subject($subject_id);
                $this->session->set_flashdata('status_msg', 'Subject updated succesfully.');
            } else {
                $this->form_validation->set_message('status_msg_error', "{$subject} already exists for {$section} section.");
            }
        } else { 
            $this->session->set_flashdata('status_msg_error', 'Error updating subject.');
        }
        redirect($this->agent->referrer());
    }

    
    public function check_subject_exists() {
        //callback function to check if subject already exists for the selected section
        $subject = $this->input->post('subject', TRUE); 
        $section_id = implode(", ", $this->input->post('section_id', TRUE));    
        $section_array = explode(", ", $section_id);
        foreach ($section_array as $key => $value) {    
            $query = $this->subjects_model->check_subject_exists($subject, $value);
            if ($query == 0) {
                return TRUE;
            } else {
                $section = $this->common_model->get_section_details($value)->section;
                $this->form_validation->set_message('check_subject_exists', "{$subject} already exists for {$section} section.");
                return FALSE;
            }
        }
    }
    
    
    public function delete_subject($subject_id) { 
        //check if demo user
        $this->demo_action_restricted_admin();
        //check subject exists for this school
        $this->check_school_data_exists(school_id, $subject_id, 'id', 'subjects', 'admin');
        $this->subjects_model->delete_subject($subject_id);
        $this->session->set_flashdata('status_msg', 'Subject deleted successfully.');
        redirect($this->agent->referrer());
    }






    /* ====== Subject Groups ====== */
    public function subject_groups() {
        $inner_page_title = 'Subject Groups (' .count($this->common_model->get_subject_groups(school_id)). ')'; 
        $this->admin_header('Subject Groups', $inner_page_title);   
        $data['subject_groups'] = $this->common_model->get_subject_groups(school_id);
        $data['total_ungrouped_subjects'] = count($this->common_model->get_ungrouped_subject(school_id));
        $this->load->view('admin/subjects/subject_groups', $data);
        $this->admin_footer();
    }


    public function add_new_subject_group_ajax() {  
        $this->form_validation->set_rules('subject_group', 'Subject Group', 'trim|required');
        if ($this->form_validation->run())  {   
            $this->subjects_model->add_new_subject_group();
            echo 1;
        } else { 
            echo validation_errors();
        }
    }


    public function edit_subject_group($subject_group_id) { 
        $this->form_validation->set_rules('subject_group', 'Subject Group', 'trim|required');
        $subject_group = ucwords($this->input->post('subject_group', TRUE));
        $y = $this->common_model->get_subject_group_details($subject_group_id);
        if ($this->form_validation->run())  {   
            $query = $this->subjects_model->check_subject_group_exists($subject_group);
            //ensure subject group does not already exist, or it is same as current subject group
            if ( $query == 0 || ($query > 0 && $y->subject_group == $subject_group) ) {
                $this->subjects_model->edit_subject_group($subject_group_id);
                $this->session->set_flashdata('status_msg', 'Subject group updated succesfully.');
            } else {
                $this->form_validation->set_message('status_msg_error', "{$subject_group} already exists.");
            }
        } else { 
            $this->session->set_flashdata('status_msg_error', 'Error updating subject group.');
        }
        redirect($this->agent->referrer());
    }

    
    public function check_subject_group_exists() {
        //callback function to check if subject already exists for the selected section
        $subject_group = $this->input->post('subject_group', TRUE); 
        $query = $this->subjects_model->check_subject_group_exists($subject_group);
        if ($query == 0) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_subject_group_exists', "{$subject_group} already exists.");
            return FALSE;
        }
    }
    
    
    public function delete_subject_group($subject_group_id) { 
        //check if demo user
        $this->demo_action_restricted_admin();
        //check subject exists for this school
        $this->check_school_data_exists(school_id, $subject_group_id, 'id', 'subject_groups', 'admin');
        $this->subjects_model->delete_subject_group($subject_group_id);
        $this->session->set_flashdata('status_msg', 'Subject group deleted successfully.');
        redirect($this->agent->referrer());
    }

    


}