<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Sections_model
Role: Model
Description: Controls the DB processes of sections 
Controller: Sections
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
Date Modified: 21st, 2019
*/

class Sections_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
    }



    
    /* ===== Sections ===== */
    public function check_section_exists($section) { 
        //check if section already exists for this school
        $this->db->where(array('school_id' => school_id, 'section' => $section));
        return $this->db->get('sections')->num_rows();
    }


    public function add_new_section() { 
        //mid-term report
        $mt_classwork = $this->input->post('mt_classwork', TRUE);
        $mt_homework = $this->input->post('mt_homework', TRUE);
        $mt_tests = $this->input->post('mt_tests', TRUE);
        $mt_total = $mt_classwork + $mt_homework + $mt_tests;

        $data = array (
            'school_id' => school_id,
            'section' => ucwords($this->input->post('section', TRUE)),
            'level' => $this->input->post('level', TRUE),

            //mid-term report settings
            'mt_classwork' => $mt_classwork,
            'mt_homework' => $mt_homework,
            'mt_tests' => $mt_tests,
            'mt_total' => $mt_total,
            'mt_template_id' => 1, //default: report template 1
			'mt_class_teacher_comment' => $this->input->post('mt_class_teacher_comment', TRUE),

            //End-of-term report settings
            'ca_max_score' => $this->input->post('ca_max_score', TRUE),
            'exam_max_score' => $this->input->post('exam_max_score', TRUE),
            'pass_mark' => $this->input->post('pass_mark', TRUE),
            'ranking' => $this->input->post('ranking', TRUE),
            'enable_subject_grouping' => $this->input->post('enable_subject_grouping', TRUE),
            'enable_aptitudes' => $this->input->post('enable_aptitudes', TRUE),
            'aptitude_display_type' => $this->input->post('aptitude_display_type', TRUE),
            'show_previous_grade' => $this->input->post('show_previous_grade', TRUE),
            'show_target_grade' => $this->input->post('show_target_grade', TRUE),
            'show_gp' => $this->input->post('show_gp', TRUE),
            'show_subject_position' => $this->input->post('show_subject_position', TRUE),
            'show_class_min' => $this->input->post('show_class_min', TRUE),
            'show_class_max' => $this->input->post('show_class_max', TRUE),
            'show_class_average' => $this->input->post('show_class_average', TRUE),
            'show_performance_summary' => $this->input->post('show_performance_summary', TRUE),
            'show_subject_teacher_name' => $this->input->post('show_subject_teacher_name', TRUE),
            'show_subject_teacher_comment' => $this->input->post('show_subject_teacher_comment', TRUE),
            'show_class_teacher_name' => $this->input->post('show_class_teacher_name', TRUE),
            'show_head_teacher_name' => $this->input->post('show_head_teacher_name', TRUE),
            'show_student_passport' => $this->input->post('show_student_passport', TRUE),
            'template_id' => 3, //default: report template 3
        );
        $this->db->insert('sections', $data);
    }


    public function edit_section($section_id) { 
        //mid-term report
        $mt_classwork = $this->input->post('mt_classwork', TRUE);
        $mt_homework = $this->input->post('mt_homework', TRUE);
        $mt_tests = $this->input->post('mt_tests', TRUE);
        $mt_total = $mt_classwork + $mt_homework + $mt_tests;

        $data = array (
            'section' => ucwords($this->input->post('section', TRUE)),
            'level' => $this->input->post('level', TRUE),

            //mid-term report settings
            'mt_classwork' => $mt_classwork,
            'mt_homework' => $mt_homework,
            'mt_tests' => $mt_tests,
            'mt_total' => $mt_total,
			'mt_class_teacher_comment' => $this->input->post('mt_class_teacher_comment', TRUE),

            //End-of-term report settings
            'ca_max_score' => $this->input->post('ca_max_score', TRUE),
            'exam_max_score' => $this->input->post('exam_max_score', TRUE),
            'pass_mark' => $this->input->post('pass_mark', TRUE),
            'ranking' => $this->input->post('ranking', TRUE),
            'enable_subject_grouping' => $this->input->post('enable_subject_grouping', TRUE),
            'enable_aptitudes' => $this->input->post('enable_aptitudes', TRUE),
            'aptitude_display_type' => $this->input->post('aptitude_display_type', TRUE),
            'show_previous_grade' => $this->input->post('show_previous_grade', TRUE),
            'show_target_grade' => $this->input->post('show_target_grade', TRUE),
            'show_gp' => $this->input->post('show_gp', TRUE),
            'show_subject_position' => $this->input->post('show_subject_position', TRUE),
            'show_class_min' => $this->input->post('show_class_min', TRUE),
            'show_class_max' => $this->input->post('show_class_max', TRUE),
            'show_class_average' => $this->input->post('show_class_average', TRUE),
            'show_performance_summary' => $this->input->post('show_performance_summary', TRUE),
            'show_subject_teacher_name' => $this->input->post('show_subject_teacher_name', TRUE),
            'show_subject_teacher_comment' => $this->input->post('show_subject_teacher_comment', TRUE),
            'show_class_teacher_name' => $this->input->post('show_class_teacher_name', TRUE),
            'show_head_teacher_name' => $this->input->post('show_head_teacher_name', TRUE),
            'show_student_passport' => $this->input->post('show_student_passport', TRUE),
        );
        $this->db->where('id', $section_id);
        $this->db->update('sections', $data);
    }


    public function get_section_next_order_level() {
        $sections = $this->common_model->get_sections(school_id);
        $total_sections = count($sections);
        if ($total_sections > 0) { //section exists
            //get last (highest) order level
            $this->db->select_max('level');
            $this->db->where('school_id', school_id);
            $last_level = $this->db->get('sections')->row()->level;
            $next_level = $last_level + 1;
        } else { //no section added yet
            //set level as 1
            $next_level = 1;
        }
        return $next_level;
    }


    public function delete_section($section_id) {
        //delete all classes and subjects created under this section
        $this->db->delete('classes', array('section_id' => $section_id));
        $this->db->delete('subjects', array('section_id' => $section_id));
        //delete the section
        return $this->db->delete('sections', array('id' => $section_id));
    } 


}