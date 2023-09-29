<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Sections
Role: Controller
Description: controls school section management
Model: Sections_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
Date Modified: 21st January, 2019
*/


class Sections extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->admin_restricted(); //allow only logged in users to access this class
        $this->load->model('sections_model');
        $this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
        //get school id
        $this->school_id = $this->admin_details->school_id;
        require_once "application/core/constants/Session.php"; //require session constants

        //module-level scripts
        $this->admin_module_scripts = array('s_sections');
    }



    /* ====== Section ====== */
    public function index() {
        $inner_page_title = 'Sections (' .count($this->common_model->get_sections(school_id)). ')'; 
        $this->admin_header('Sections', $inner_page_title); 
        $data['sections'] = $this->common_model->get_sections(school_id);
        $this->load->view('admin/sections/sections', $data);
        $this->admin_footer();
    }


    public function new_section() {
        $page_title = 'New Section'; 
        $this->admin_header($page_title, $page_title);  
        $data['next_level'] = $this->sections_model->get_section_next_order_level();
        $this->load->view('admin/sections/new_section', $data);
        $this->admin_footer();
    }
    
    
    public function add_new_section_ajax() {    
        $this->form_validation->set_rules('section', 'Section', 'trim|required|callback_check_section_exists');
        $this->form_validation->set_rules('level', 'Level', 'trim|required');

        //mid-term report settings
        $this->form_validation->set_rules('mt_classwork', 'Max. Classwork Score', 'trim|required|is_natural');
        $this->form_validation->set_rules('mt_homework', 'Max. Homework Score', 'trim|required|is_natural');
        $this->form_validation->set_rules('mt_tests', 'Max. Tests Score', 'trim|required|is_natural');
        $this->form_validation->set_rules('mt_class_teacher_comment', 'Class Teacher Comment', 'trim|required');

        //End of term report settings
        $this->form_validation->set_rules('ca_max_score', 'Max. CA Score', 'trim|required|is_natural');
        $this->form_validation->set_rules('exam_max_score', 'Max. Exam Score', 'required|is_natural|callback_check_total_score');
        $this->form_validation->set_rules('pass_mark', 'Pass Mark', 'trim|required|is_natural|greater_than_equal_to[30]|less_than_equal_to[100]', 
            array(
                'greater_than_equal_to' => 'Pass Mark cannot be less than 30',
                'less_than_equal_to' => 'Pass Mark cannot exceed 100',
            )
        );

        $this->form_validation->set_rules('ranking', 'Ranking/Position', 'trim|required');
        $this->form_validation->set_rules('enable_aptitudes', 'Behavioural Aptitudes', 'trim|required');
        $this->form_validation->set_rules('aptitude_display_type', 'Behavioural Aptitudes Layout Type', 'trim|required');
        $this->form_validation->set_rules('enable_subject_grouping', 'Subject Grouping', 'trim|required');
        $this->form_validation->set_rules('show_previous_grade', 'Previous Grades', 'trim|required');
        $this->form_validation->set_rules('show_target_grade', 'Target Grades', 'trim|required');
        $this->form_validation->set_rules('show_gp', 'GP', 'trim|required');
        $this->form_validation->set_rules('show_subject_position', 'Subject Position', 'trim|required');
        $this->form_validation->set_rules('show_class_min', 'Class Min', 'trim|required');
        $this->form_validation->set_rules('show_class_max', 'Class Max', 'trim|required');
        $this->form_validation->set_rules('show_class_average', 'Class Average', 'trim|required');
        $this->form_validation->set_rules('show_performance_summary', 'Performance Summary', 'trim|required');
        $this->form_validation->set_rules('show_subject_teacher_name', 'Subject Teacher Name', 'trim|required');
        $this->form_validation->set_rules('show_subject_teacher_comment', 'Subject Teacher Comment', 'trim|required');
        $this->form_validation->set_rules('show_class_teacher_name', 'Class Teacher Name', 'trim|required');
        $this->form_validation->set_rules('show_head_teacher_name', 'Head Teacher Name', 'trim|required');
        $this->form_validation->set_rules('show_student_passport', 'Student Passport', 'trim|required');
        
        if ($this->form_validation->run())  {       
            $this->sections_model->add_new_section();
            echo 1;
        } else { 
            echo validation_errors();
        }
    }


    public function edit_section($section_id) {
        //check section exists for this school
        $this->check_school_data_exists(school_id, $section_id, 'id', 'sections', 'admin');
        $y = $this->common_model->get_section_details($section_id);
        $page_title = 'Edit Section: ' . $y->section; 
        $this->admin_header($page_title, $page_title);  
        $data['y'] = $y;
        $this->load->view('admin/sections/edit_section', $data);
        $this->admin_footer();
    }


    public function edit_section_ajax($section_id) {    
        //check section exists for this school
        $this->check_school_data_exists(school_id, $section_id, 'id', 'sections', 'admin');

        $section = ucwords($this->input->post('section', TRUE));
        //check if entered section is different from section being edited. If different, make field unique
        $y = $this->common_model->get_section_details($section_id);
        if ($y->section != $section) {
            $this->form_validation->set_rules('section', 'Section', 'trim|required|callback_check_section_exists');
        } else {
            $this->form_validation->set_rules('section', 'Section', 'trim|required');
        }
        $this->form_validation->set_rules('level', 'Level', 'trim|required');

        //mid-term report settings
        $this->form_validation->set_rules('mt_classwork', 'Max. Classwork Score', 'trim|required|is_natural');
        $this->form_validation->set_rules('mt_homework', 'Max. Homework Score', 'trim|required|is_natural');
        $this->form_validation->set_rules('mt_tests', 'Max. Tests Score', 'trim|required|is_natural');
		$this->form_validation->set_rules('mt_class_teacher_comment', 'Class Teacher Comment', 'trim|required');

        //End of term report settings
        $this->form_validation->set_rules('ca_max_score', 'Max. CA Score', 'trim|required|is_natural');
        $this->form_validation->set_rules('exam_max_score', 'Max. Exam Score', 'required|is_natural|callback_check_total_score');
        $this->form_validation->set_rules('pass_mark', 'Pass Mark', 'trim|required|is_natural|greater_than_equal_to[30]|less_than_equal_to[100]', 
            array(
                'greater_than_equal_to' => 'Pass Mark cannot be less than 30',
                'less_than_equal_to' => 'Pass Mark cannot exceed 100',
            )
        );

        $this->form_validation->set_rules('ranking', 'Ranking/Position', 'trim|required');
        $this->form_validation->set_rules('enable_aptitudes', 'Behavioural Aptitudes', 'trim|required');
        $this->form_validation->set_rules('aptitude_display_type', 'Behavioural Aptitudes Layout Type', 'trim|required');
        $this->form_validation->set_rules('enable_subject_grouping', 'Subject Grouping', 'trim|required');
        $this->form_validation->set_rules('show_previous_grade', 'Previous Grades', 'trim|required');
        $this->form_validation->set_rules('show_target_grade', 'Target Grades', 'trim|required');
        $this->form_validation->set_rules('show_gp', 'GP', 'trim|required');
        $this->form_validation->set_rules('show_subject_position', 'Subject Position', 'trim|required');
        $this->form_validation->set_rules('show_class_min', 'Class Min', 'trim|required');
        $this->form_validation->set_rules('show_class_max', 'Class Max', 'trim|required');
        $this->form_validation->set_rules('show_class_average', 'Class Average', 'trim|required');
        $this->form_validation->set_rules('show_performance_summary', 'Performance Summary', 'trim|required');
        $this->form_validation->set_rules('show_subject_teacher_name', 'Subject Teacher Name', 'trim|required');
        $this->form_validation->set_rules('show_subject_teacher_comment', 'Subject Teacher Comment', 'trim|required');
        $this->form_validation->set_rules('show_class_teacher_name', 'Class Teacher Name', 'trim|required');
        $this->form_validation->set_rules('show_head_teacher_name', 'Head Teacher Name', 'trim|required');
        $this->form_validation->set_rules('show_student_passport', 'Student Passport', 'trim|required');
        
        if ($this->form_validation->run()) {    
            $this->sections_model->edit_section($section_id);
            echo 1; 
        } else { 
            echo validation_errors();
        }
    }


    public function check_total_score() {   //callback function to ensure CA + Exam = 100
        $ca_max_score = $this->input->post('ca_max_score', TRUE);   
        $exam_max_score = $this->input->post('exam_max_score', TRUE);   
        $total = $ca_max_score + $exam_max_score;
        if ($total == 100) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_total_score', "Max. CA and exam scores must equal 100.");
            return FALSE;
        }
    }


    public function check_section_exists() {
        //callback function to check if section exists  
        $section = ucwords($this->input->post('section', TRUE));
        $query = $this->sections_model->check_section_exists($section);
        if ($query == 0) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_section_exists', "{$section} section already exists.");
            return FALSE;
        }
    }
    
    
    public function delete_section($section_id) { 
        //check if demo user
        $this->demo_action_restricted_admin();
        //check section exists for this school
        $this->check_school_data_exists(school_id, $section_id, 'id', 'sections', 'admin');
        $this->sections_model->delete_section($section_id);
        $this->session->set_flashdata('status_msg', 'Section deleted successfully.');
        redirect($this->agent->referrer());
    }


    

}