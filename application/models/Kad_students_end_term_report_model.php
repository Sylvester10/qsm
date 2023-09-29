<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: Students_end_term_report_model
Role: Model
Description: Controls the DB processes of students' reports
Controller: Kad_students_end_term_report, Kad_students_end_term_report_staff, Kad_students_end_term_report_subject_teacher, Kad_student_end_term_reports, Student_end_term_reports_parent
Author: Nwankwo Ikemefuna
Date Created: 20th February, 2019
*/


class Kad_students_end_term_report_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->model('fees_model');
    }



    public function produce_report_data($session, $term, $class_id, $student_id) {
        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        $student_details = $this->common_model->get_student_details_by_id($student_id);
        $class_details = $this->common_model->get_class_details($class_id);
        $section_id = $class_details->section_id;
        $section_details = $this->common_model->get_section_details($section_id);
        //template details
        $template_id = $class_details->bespoke_template_id;
        $template_details = $this->get_template_details($template_id);
        $data['y'] = $student_details; 
        $data['student_name'] = $this->common_model->get_student_fullname($student_id);
        $data['admission_id'] = $student_details->admission_id;
        $data['session'] = $session; 
        $data['the_session'] = get_the_session($session); 
        $data['term'] = $term; 
        $data['class_id'] = $class_id; 
        $data['section_id'] = $section_id; 
        $data['class'] = $class_details->class;
        $data['student_id'] = $student_id; 
        $data['section'] =  $section_details->section;
        $data['pass_mark'] = $section_details->pass_mark;
        $data['class_details'] = $class_details;
        $data['result_details'] = $result_details;
        $data['template_id'] = $template_id;
        $data['allow_class_teacher_comment'] = $template_details->allow_class_teacher_comment;
        $data['allow_head_teacher_comment'] = $template_details->allow_head_teacher_comment;
        $data['att_present'] = $this->common_model->get_attendance_present($session, $term, $class_id, $student_id);
        $data['att_absent'] = $this->common_model->get_attendance_absent($session, $term, $class_id, $student_id);
        $data['class_population'] =  $this->common_model->get_class_population($class_id);
        $data['test_scores'] = $this->get_test_scores($session, $term, $class_id, $student_id);
        $data['total_possible_score'] = $this->get_total_possible_score($session, $term, $class_id, $student_id);
        $data['overall_total_score'] = $this->get_overall_total_score($session, $term, $class_id, $student_id);
        $data['overall_percentage_score'] = $this->get_overall_percentage_score($session, $term, $class_id, $student_id); 
        $data['position'] = $this->get_student_position($session, $term, $class_id, $student_id);
        $data['class_teacher_comment'] = $this->get_class_teacher_comment($session, $term, $class_id, $student_id);
        $data['head_teacher_comment'] = $this->get_head_teacher_comment($session, $term, $class_id, $student_id);
        $data['date_approved'] = $this->get_report_approved_date($session, $term, $class_id, $student_id);
        $data['sections'] = $this->school_sections();
        return $data;
    }


    public function report_card_data($session, $term, $class_id, $student_id) {
        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        $student_details = $this->common_model->get_student_details_by_id($student_id);
        $class_details = $this->common_model->get_class_details($class_id);
        $section_id = $class_details->section_id;
        $section_details = $this->common_model->get_section_details($section_id);
        //resumption class
        $resumption_class_id = $result_details->resumption_class_id;    
        $resumption_class = $this->common_model->get_class_details($resumption_class_id)->class;
        //template details
        $template_id = $class_details->bespoke_template_id;
        $template_details = $this->get_template_details($template_id);
        $data['y'] = $student_details; 
        $data['student_name'] = $this->common_model->get_student_fullname($student_id);
        $data['student_dob'] = $this->get_student_dob($student_id);
        $data['student_admission_date'] = $this->get_student_admission_date($student_id);
        $data['student_sex'] = $student_details->sex;
        $data['admission_id'] = $student_details->admission_id;
        $data['last_promoted'] = $student_details->last_promoted;
        $data['session'] = $session; 
        $data['the_session'] = get_the_session($session); 
        $data['term'] = $term; 
        $data['class_id'] = $class_id; 
        $data['section_id'] = $section_id; 
        $data['class'] = $class_details->class;
        $data['resumption_class_id'] = $resumption_class_id;
        $data['resumption_class'] = $resumption_class;
        $data['student_id'] = $student_id; 
        $data['section'] =  $section_details->section;
        $data['class_details'] = $class_details;
        $data['result_details'] = $result_details;
        $data['template_id'] = $template_id;
        $data['allow_class_teacher_comment'] = $template_details->allow_class_teacher_comment;
        $data['allow_head_teacher_comment'] = $template_details->allow_head_teacher_comment;
        $data['att_present'] = $result_details->att_present;
        $data['att_absent'] = $result_details->att_absent;
        $data['att_tardy'] = $result_details->att_tardy;
        $data['att_total'] = $result_details->att_present + $result_details->att_absent;
        $data['class_population'] =  $this->common_model->get_class_population($class_id);
        $data['test_scores'] = $this->get_test_scores($session, $term, $class_id, $student_id);
         $data['total_possible_score'] = $this->get_total_possible_score($session, $term, $class_id, $student_id);
        $data['overall_total_score'] = $this->get_overall_total_score($session, $term, $class_id, $student_id);
        $data['overall_percentage_score'] = $this->get_overall_percentage_score($session, $term, $class_id, $student_id); 
        $data['position'] = $this->get_student_position($session, $term, $class_id, $student_id);
        $data['class_teacher_comment'] = $this->get_class_teacher_comment($session, $term, $class_id, $student_id);
        $data['head_teacher_comment'] = $this->get_head_teacher_comment($session, $term, $class_id, $student_id);
        $data['class_teacher_name'] = $this->get_class_teacher_name($session, $term, $class_id, $student_id);
        $data['class_teacher_signature'] = $this->get_class_teacher_signature($session, $term, $class_id, $student_id);
        $data['head_teacher_signature'] = $this->get_head_teacher_signature($session, $term, $class_id, $student_id);
        $data['date_approved'] = $this->get_report_approved_date($session, $term, $class_id, $student_id);
        $data['report_stamp'] = $this->get_school_stamp($session, $term, $class_id, $student_id);
        $data['sections'] = $this->school_sections();
        $data['school_info'] = $this->common_model->get_school_info(school_id);
        $data['term_info'] = $this->common_model->get_term_info(school_id);
        $data['terms'] = kad_academy_report_terms();
        $data['attendance_params'] = kad_academy_attendance_params();
        $data['head_of_school'] = 'H. Sherman-Nwakwesi (Mrs.)';
        return $data;
    }


    public function report_data($session, $term, $class_id) {
        $class_details = $this->common_model->get_class_details($class_id);
        $section_id = $class_details->section_id;
        $section_details = $this->common_model->get_section_details($section_id);
        //template details
        $template_id = $class_details->bespoke_template_id;
        $template_details = $this->get_template_details($template_id);
        $data['session'] = $session; 
        $data['the_session'] = get_the_session($session);
        $data['term'] = $term; 
        $data['class_id'] = $class_id; 
        $data['section_id'] = $section_id; 
        $data['class'] = $class_details->class;
        $data['slug'] = $class_details->slug;
        $data['level'] = $class_details->level;
        $data['section'] =  $section_details->section;
        $data['ranking'] = $section_details->ranking;
        $data['pass_mark'] = $section_details->pass_mark;
        $data['class_details'] = $class_details;
        $data['template_id'] = $template_id;
        $data['allow_class_teacher_comment'] = $template_details->allow_class_teacher_comment;
        $data['allow_head_teacher_comment'] = $template_details->allow_head_teacher_comment;
        $data['class_population'] =  $this->common_model->get_class_population($class_id);
        $data['class_results'] = $this->get_class_results($session, $term, $class_id);
        $data['class_test_scores'] = $this->get_class_test_scores($session, $term, $class_id);
        $data['subjects'] = $this->common_model->get_subjects_by_section(school_id, $section_id);
        $data['school_info'] = $this->common_model->get_school_info(school_id);
        return $data;
    }


    private function class_where_array($session, $term, $class_id) { 
        $where = array(
            'class_id' => $class_id,
            'session' => $session,
            'term' => $term,
        );
        return $where;
    }


    private function student_where_array($session, $term, $class_id, $student_id) { 
        $where = array(
            'student_id' => $student_id,
            'class_id' => $class_id,
            'session' => $session,
            'term' => $term,
        );
        return $where;
    }


    private function school_sections() {
        $sections = $this->common_model->get_sections(school_id); 
        $i = 0;
        $total_sections = count($sections);
        $sections_string = "";
        foreach ($sections as $s) {
            $section = $s->section;
            $i++;
            //list sections inline, separate with comma if not end of list
            $sections_string .= ($i == $total_sections) ?  $section : ($section. ', ');
        }
        return $sections_string;
    }



    /* ========== Templates ========== */
     public function get_template_details($template_id) { 
        return $this->db->get_where('kad_end_term_report_templates', array('id' => $template_id))->row();
    }


    public function get_template_details_by_category($category) { 
        return $this->db->get_where('kad_end_term_report_templates', array('category' => $category))->row();
    }




    /* ========== Test Scores ========== */
    public function get_test_score_table($category) { 
        $tables = [
            'pre_kg'        =>      'kad_end_term_test_scores',
            'kg'            =>      'kad_end_term_test_scores',
            'snr_kg'        =>      'kad_end_term_test_scores',
            'year_1'        =>      'kad_end_term_test_scores',
            'year_2'        =>      'kad_end_term_test_scores',
            'year_3_6'      =>      'kad_end_term_test_scores',
            'secondary'     =>      'kad_end_term_sec_test_scores',
        ];
        return $tables[$category];
    }


    public function get_subject_categories($template_id) {
        //$this->db->order_by('category', 'asc'); 
        return $this->db->get_where('kad_end_term_subject_categories', array('template_id' => $template_id))->result();
    }


    public function get_subject_category_details($subject_cat_id) { 
        return $this->db->get_where('kad_end_term_subject_categories', array('id' => $subject_cat_id))->row();
    }


    public function get_subject_category_items($subject_cat_id) { 
        return $this->db->get_where('kad_end_term_subject_category_items', array('subject_cat_id' => $subject_cat_id))->result();
    }


    public function get_subject_category_item_details($subject_id) { 
        return $this->db->get_where('kad_end_term_subject_category_items', array('id' => $subject_id))->row();
    }



    //Category = Pre-KG, KG, Senior KG and Year 1, Year 2
    public function insert_test_score($session, $term, $class_id, $student_id, $subject_id, $progress, $category) {

        //check if progress was supplied, if not, set as NULL
        if ($progress != '') { 
            $progress = $progress;
        } else {
            $progress = NULL;
        }
        
        //check that progress field has a value 
        if ($progress == '') { 
            return NULL;
        } else {
            $data = array(
                'school_id' => school_id, 
                'student_id' => $student_id, 
                'class_id' => $class_id, 
                'subject_id' => $subject_id, 
                'progress' => $progress, 
                'session' => $session, 
                'term' => $term, 
            );
            $table = $this->get_test_score_table($category);
            $this->db->insert($table, $data);
            //update total scored
            $this->update_total_scored($session, $term, $class_id, $student_id);  
        }
    } 


    public function update_test_score($session, $term, $class_id, $student_id, $subject_id, $progress, $category) {

        //check if progress was supplied, if not, set as NULL
        if ($progress != '') { 
            $progress = $progress;
        } else {
            $progress = NULL;
        }
    
        $query = $this->check_test_score_exists($session, $term, $class_id, $student_id, $subject_id);
        $test_score_id = $query->row()->id;
        $data = array(
            'progress' => $progress,  
        );
        $table = $this->get_test_score_table($category);
        $this->db->where('id', $test_score_id); 
        $this->db->update($table, $data);    
        //update total scored
        $this->update_total_scored($session, $term, $class_id, $student_id);    
    } 


    //Category = Year 3-6 (Test score)
    public function insert_year_3_6_test_score($session, $term, $class_id, $student_id, $subject_id, $assessment, $category) {

        //check if assessment was supplied, if not, set as NULL
        if ($assessment != '') { 
            $assessment = $assessment;
        } else {
            $assessment = NULL;
        }
        
        //check that progress field has a value 
        if ($assessment == '') { 
            return NULL;
        } else {
            $data = array(
                'school_id' => school_id, 
                'student_id' => $student_id, 
                'class_id' => $class_id, 
                'subject_id' => $subject_id, 
                'assessment' => $assessment, 
                'session' => $session, 
                'term' => $term, 
            );
            $table = $this->get_test_score_table($category);
            $this->db->insert($table, $data);
            //update total scored
            $this->update_total_scored($session, $term, $class_id, $student_id);  
        }
    } 


    public function update_year_3_6_test_score($session, $term, $class_id, $student_id, $subject_id, $assessment, $category) {

        //check if assessment was supplied, if not, set as NULL
        if ($assessment != '') { 
            $assessment = $assessment;
        } else {
            $assessment = NULL;
        }
    
        $query = $this->check_test_score_exists($session, $term, $class_id, $student_id, $subject_id);
        $test_score_id = $query->row()->id;
        $data = array(
            'assessment' => $assessment,
        );
        $table = $this->get_test_score_table($category);
        $this->db->where('id', $test_score_id); 
        $this->db->update($table, $data);    
        //update total scored
        $this->update_total_scored($session, $term, $class_id, $student_id);    
    } 


    public function get_year_3_6_subject_score_data($session, $term, $class_id, $student_id, $subject_score, $item) {
        if ($subject_score > 0) {
            switch ($subject_score) {
                case in_array($subject_score, range(0, 59)):
                    $data = [
                        'grade' => 'E',
                    ];
                break;
                case in_array($subject_score, range(60, 63)):
                    $data = [
                        'grade' => 'D-',
                    ];
                break;
                case in_array($subject_score, range(64, 67)):
                    $data = [
                        'grade' => 'D',
                    ];
                break;
                case in_array($subject_score, range(68, 69)):
                    $data = [
                        'grade' => 'D+',
                    ];
                break;
                case in_array($subject_score, range(70, 73)):
                    $data = [
                        'grade' => 'C-',
                    ];
                break;
                case in_array($subject_score, range(74, 77)):
                    $data = [
                        'grade' => 'C',
                    ];
                break;
                case in_array($subject_score, range(78, 79)):
                    $data = [
                        'grade' => 'C+',
                    ];
                break;
                case in_array($subject_score, range(80, 83)):
                    $data = [
                        'grade' => 'B-',
                    ];
                break;
                case in_array($subject_score, range(84, 86)):
                    $data = [
                        'grade' => 'B',
                    ];
                break;
                case in_array($subject_score, range(87, 89)):
                    $data = [
                        'grade' => 'B+',
                    ];
                break;
                case in_array($subject_score, range(90, 94)):
                    $data = [
                        'grade' => 'A-',
                    ];
                break;
                case in_array($subject_score, range(95, 100)):
                    $data = [
                        'grade' => 'A',
                    ];
                break;
            }
            return $data[$item];
        } else {
            return NULL;
        }
    } 



    //Category = Year 3-6 (Progress score)
    public function insert_year_3_6_progress_score($session, $term, $class_id, $student_id, $subject_id, $progress) {

        //check if progress was supplied, if not, set as NULL
        if ($progress != '') { 
            $progress = $progress;
        } else {
            $progress = NULL;
        }
        
        //check that progress field has a value 
        if ($progress == '') { 
            return NULL;
        } else {
            $data = array(
                'school_id' => school_id, 
                'student_id' => $student_id, 
                'class_id' => $class_id, 
                'subject_id' => $subject_id, 
                'progress' => $progress, 
                'session' => $session, 
                'term' => $term, 
            );
            $this->db->insert('kad_end_term_year_3_6_progress_scores', $data);
        }
    } 


    public function update_year_3_6_progress_score($session, $term, $class_id, $student_id, $subject_id, $progress) {

        //check if progress was supplied, if not, set as NULL
        if ($progress != '') { 
            $progress = $progress;
        } else {
            $progress = NULL;
        }
    
        $query = $this->check_year_3_6_progress_score_exists($session, $term, $class_id, $student_id, $subject_id);
        $progress_score_id = $query->row()->id;
        $data = array(
            'progress' => $progress,
        );
        $this->db->where('id', $progress_score_id); 
        $this->db->update('kad_end_term_year_3_6_progress_scores', $data);      
    } 



    public function get_year_3_6_progress_score_details($progress_score_id) {
        return $this->db->get_where('kad_end_term_year_3_6_progress_scores', array('id' => $progress_score_id))->row();
    }


    public function check_year_3_6_progress_score_exists($session, $term, $class_id, $student_id, $subject_id) {
        $where = array(
            'student_id' => $student_id,
            'class_id' => $class_id,
            'subject_id' => $subject_id,
            'session' => $session,
            'term' => $term,
        );
        return $this->db->get_where('kad_end_term_year_3_6_progress_scores', $where);
    }


    public function delete_year_3_6_progress_score($progress_score_id) {
        $this->db->delete('kad_end_term_year_3_6_progress_scores', array('id' => $progress_score_id));    
    } 


    

    //Category = Secondary
    public function insert_secondary_test_score($session, $term, $class_id, $student_id, $subject_id, $effort, $assessment, $subject_comment, $category) {

        //check if effort was supplied, if not, set as NULL
        if ($effort != '') { 
            $effort = $effort;
        } else {
            $effort = NULL;
        }
        //check if assessment was supplied, if not, set as NULL
        if ($assessment != '') { 
            $assessment = $assessment;
        } else {
            $assessment = NULL;
        }
        //check if subject comment was supplied, if not, set as NULL
        if ($subject_comment != '') { 
            $subject_comment = ucfirst($subject_comment);
        } else {
            $subject_comment = NULL;
        }
        
        //check that assessment field has a value 
        if ($assessment == '') { 
            return NULL;
        } else {
            $data = array(
                'school_id' => school_id, 
                'student_id' => $student_id, 
                'class_id' => $class_id, 
                'subject_id' => $subject_id, 
                'effort' => $effort, 
                'assessment' => $assessment, 
                'subject_comment' => $subject_comment, 
                'session' => $session, 
                'term' => $term, 
            );
            $table = $this->get_test_score_table($category);
            $this->db->insert($table, $data);
            //update total scored
            $this->update_total_scored($session, $term, $class_id, $student_id);  
        }
    } 


    public function update_secondary_test_score($session, $term, $class_id, $student_id, $subject_id, $effort, $assessment, $subject_comment, $category) {

        //check if effort was supplied, if not, set as NULL
        if ($effort != '') { 
            $effort = $effort;
        } else {
            $effort = NULL;
        }
        //check if assessment was supplied, if not, set as NULL
        if ($assessment != '') { 
            $assessment = $assessment;
        } else {
            $assessment = NULL;
        }
        //check if subject comment was supplied, if not, set as NULL
        if ($subject_comment != '') { 
            $subject_comment = ucfirst($subject_comment);
        } else {
            $subject_comment = NULL;
        }

        $query = $this->check_test_score_exists($session, $term, $class_id, $student_id, $subject_id);
        $test_score_id = $query->row()->id;
        $data = array(
            'effort' => $effort, 
            'assessment' => $assessment, 
            'subject_comment' => $subject_comment, 
        );
        $table = $this->get_test_score_table($category);
        $this->db->where('id', $test_score_id); 
        $this->db->update($table, $data);    
        //update total scored
        $this->update_total_scored($session, $term, $class_id, $student_id);    
    } 


    public function get_secondary_subject_score_data($session, $term, $class_id, $student_id, $subject_score, $item) {
        if ($subject_score > 0) {
            switch ($subject_score) {
                case in_array($subject_score, range(0, 39)):
                    $data = [
                        'standard' => 'Ungraded',
                        'grade' => 'U',
                    ];
                break;
                case in_array($subject_score, range(40, 49)):
                    $data = [
                        'standard' => 'Cause for Concern',
                        'grade' => 'E',
                    ];
                break;
                case in_array($subject_score, range(50, 59)):
                    $data = [
                        'standard' => 'Weak',
                        'grade' => 'D',
                    ];
                break;
                case in_array($subject_score, range(60, 69)):
                    $data = [
                        'standard' => 'Satisfactory',
                        'grade' => 'C',
                    ];
                break;
                case in_array($subject_score, range(70, 79)):
                    $data = [
                        'standard' => 'Good',
                        'grade' => 'B',
                    ];
                break;
                case in_array($subject_score, range(80, 89)):
                    $data = [
                        'standard' => 'Excellent',
                        'grade' => 'A',
                    ];
                break;
                case in_array($subject_score, range(90, 100)):
                    $data = [
                        'standard' => 'Outstanding',
                        'grade' => 'A*',
                    ];
                break;
            }
            return $data[$item];
        } else {
            return NULL;
        }
    } 




    /* ============= General =========== */
    public function update_total_scored($session, $term, $class_id, $student_id) {
        //check if student result already exists in current class, session and term
        $query = $this->get_result_details($session, $term, $class_id, $student_id);
        //get student's class teacher
        $class_teacher_id = $this->common_model->get_class_details($class_id)->class_teacher_id;
            
        $total_scored = $this->get_overall_total_score($session, $term, $class_id, $student_id);
        //if student's data doesn't exist, insert data
        if ( ! $query ) {
            $insert_data = array(
                'school_id' => school_id, 
                'student_id' => $student_id, 
                'class_id' => $class_id, 
                'resumption_class_id' => $class_id, //set as current
                'session' => $session, 
                'term' => $term,
                'total_scored' => $total_scored, 
                'status' => 'Pending', 
                'produced_by' => $class_teacher_id,
            );
            return $this->db->insert('kad_end_term_student_results', $insert_data);  

        } else { //student data exists, do update

            $result_id = $query->id;
            $update_data = array(
                'total_scored' => $total_scored, 
                'produced_by' => $class_teacher_id,
            );
            $this->db->where('id', $result_id); 
            return $this->db->update('kad_end_term_student_results', $update_data);  
        }
    }


    public function get_test_score_details($test_score_id, $class_id) {
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
        $category = $this->get_template_details($template_id)->category;
        $table = $this->get_test_score_table($category);
        return $this->db->get_where($table, array('id' => $test_score_id))->row();
    }


    public function check_test_score_exists($session, $term, $class_id, $student_id, $subject_id) {
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
        $category = $this->get_template_details($template_id)->category;
        $table = $this->get_test_score_table($category);
        $where = array(
            'student_id' => $student_id,
            'class_id' => $class_id,
            'subject_id' => $subject_id,
            'session' => $session,
            'term' => $term,
        );
        return $this->db->get_where($table, $where);
    }


    public function delete_test_score($test_score_id, $class_id) {
        //update total scored
        $this->update_total_scored_on_test_delete($test_score_id, $class_id);
        //delete the test score
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
        $category = $this->get_template_details($template_id)->category;
        $table = $this->get_test_score_table($category);
        $this->db->delete($table, array('id' => $test_score_id));    
    } 


    public function update_total_scored_on_test_delete($test_score_id, $class_id) {
        //update total scored by subtracting the sum of progress, effort and behaviour scores for from total scored
        $t = $this->get_test_score_details($test_score_id, $class_id);
        $total_test_scores = $t->assessment;
        //get result details
        $session = $t->session; 
        $term = $t->term; 
        $class_id = $t->class_id; 
        $student_id = $t->student_id; 
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        $result = $this->db->get_where('kad_end_term_student_results', $where)->row();
        $total_scored = $result->total_scored;
        $result_id = $result->id;
        $data = array(
            'total_scored' => $total_scored - $total_test_scores
        );
        $this->db->where('id', $result_id);
        return $this->db->update('kad_end_term_student_results', $data);
    } 




    //Category = Pre-KG, KG, Senior KG (Misc)
    public function update_misc_scores($session, $term, $class_id, $student_id) {
        //array of items
        $colours = $this->input->post('colours', TRUE);
        $shapes = $this->input->post('shapes', TRUE);
        $letters_upper = $this->input->post('letters_upper', TRUE);
        $letters_lower = $this->input->post('letters_lower', TRUE);
        $letters_isolated = $this->input->post('letters_isolated', TRUE);
        $numbers = $this->input->post('numbers', TRUE);
        //implode items into string and concatenate using comma as delimeter
        $colours_str = (count($colours) > 0) ? implode(', ', $colours) : NULL;
        $shapes_str = (count($shapes) > 0) ? implode(', ', $shapes) : NULL;
        $letters_upper_str = (count($letters_upper) > 0) ? implode(', ', $letters_upper) : NULL;
        $letters_lower_str = (count($letters_lower) > 0) ? implode(', ', $letters_lower) : NULL;
        $letters_isolated_str = (count($letters_isolated) > 0) ? implode(', ', $letters_isolated) : NULL;
        $numbers_str = (count($numbers) > 0) ? implode(', ', $numbers) : NULL;

        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        $result_id = $result_details->id;
        $data = array(
            'colours' => $colours_str,  
            'shapes' => $shapes_str,  
            'letters_upper' => $letters_upper_str,  
            'letters_lower' => $letters_lower_str,  
            'letters_isolated' => $letters_isolated_str,  
            'numbers' => $numbers_str,  
        );
        $this->db->where('id', $result_id); 
        $this->db->update('kad_end_term_student_results', $data);     
    } 



    //Category = Year 2, Year 3-6 (Skills)
    public function get_year_2_skills() { 
        return $this->db->get('kad_end_term_year_2_skills')->result();
    }


    public function get_year_3_6_skills() { 
        return $this->db->get('kad_end_term_year_3_6_skills')->result();
    }


    public function insert_skill_score($session, $term, $class_id, $student_id, $skill_id, $skill) {

        //check if skill was supplied, if not, set as NULL
        if ($skill != '') { 
            $skill = $skill;
        } else {
            $skill = NULL;
        }
        
        //check that skill field has a value 
        if ($skill == '') { 
            return NULL;
        } else {
            $data = array(
                'school_id' => school_id, 
                'student_id' => $student_id, 
                'class_id' => $class_id, 
                'skill_id' => $skill_id, 
                'skill' => $skill, 
                'session' => $session, 
                'term' => $term, 
            );
            $this->db->insert('kad_end_term_year_2_3_skill_scores', $data);
        }
    }


    public function update_skill_score($session, $term, $class_id, $student_id, $skill_id, $skill) {

        //check if skill was supplied, if not, set as NULL
        if ($skill != '') { 
            $skill = $skill;
        } else {
            $skill = NULL;
        }
    
        $query = $this->check_skill_score_exists($session, $term, $class_id, $student_id, $skill_id);
        $skill_score_id = $query->row()->id;
        $data = array(
            'progress' => $skill,  
        );
        $this->db->where('id', $skill_score_id); 
        $this->db->update('kad_end_term_year_2_3_skill_scores', $data);     
    }  


    public function get_skill_score_details($skill_score_id) {
        return $this->db->get_where('kad_end_term_year_2_3_skill_scores', array('id' => $skill_score_id))->row();
    }


    public function check_skill_score_exists($session, $term, $class_id, $student_id, $skill_id) {
        $where = array(
            'student_id' => $student_id,
            'class_id' => $class_id,
            'skill_id' => $skill_id,
            'session' => $session,
            'term' => $term,
        );
        return $this->db->get_where('kad_end_term_year_2_3_skill_scores', $where);
    }


    public function delete_skill_score($skill_score_id) {
        $this->db->delete('kad_end_term_year_2_3_skill_scores', array('id' => $skill_score_id));    
    }




    /* ============ Attendance scores ================= */
    public function update_att_scores($session, $term, $class_id, $student_id) {
        //attendance
        $customize_att = $this->input->post('customize_att', TRUE);
        if (isset($customize_att)) {
            $att_type = 'custom';
            $att_present = $this->input->post('att_present', TRUE);
            $att_absent = $this->input->post('att_absent', TRUE);
            $att_tardy = $this->input->post('att_tardy', TRUE);
        } else {
            $att_type = 'class';
            $att_present = $this->common_model->get_attendance_present($session, $term, $class_id, $student_id);
            $att_absent = $this->common_model->get_attendance_absent($session, $term, $class_id, $student_id);
            $att_tardy = NULL;
        }
        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        $result_id = $result_details->id;
        $data = array(
            'att_type' => $att_type, 
            'att_present' => $att_present, 
            'att_absent' => $att_absent, 
            'att_tardy' => $att_tardy, 
        );
        $this->db->where('id', $result_id); 
        $this->db->update('kad_end_term_student_results', $data);  
    } 




    /* ========== Class Teacher's Comments ========== */
    public function get_class_teacher_comment_details($comment_id) { 
        return $this->db->get_where('kad_end_term_class_teacher_remarks', array('id' => $comment_id))->row();
    }


    public function check_class_teacher_comment_exists($session, $term, $class_id, $student_id) {
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        return $this->db->get_where('kad_end_term_class_teacher_remarks', $where);
    } 


    public function insert_class_teacher_comment($session, $term, $class_id, $student_id) {
        $comment = ucfirst($this->input->post('comment', TRUE));
        $data = array(
            'school_id' => school_id, 
            'student_id' => $student_id, 
            'class_id' => $class_id, 
            'session' => $session, 
            'term' => $term, 
            'comment' => $comment, 
        );
        return $this->db->insert('kad_end_term_class_teacher_remarks', $data);   
    } 


    public function update_class_teacher_comment($session, $term, $class_id, $student_id) {
        $comment = ucfirst($this->input->post('comment', TRUE));
        $query = $this->check_class_teacher_comment_exists($session, $term, $class_id, $student_id);
        $comment_id = $query->row()->id;
        $data = array(
            'comment' => $comment, 
        );
        $this->db->where('id', $comment_id);    
        return $this->db->update('kad_end_term_class_teacher_remarks', $data);   
    }





    /* ========== Head Teacher's Comments ========== */
    public function get_head_teacher_comment_details($comment_id) { 
        return $this->db->get_where('kad_end_term_head_teacher_remarks', array('id' => $comment_id))->row();
    }


    public function check_head_teacher_comment_exists($session, $term, $class_id, $student_id) {
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        return $this->db->get_where('kad_end_term_head_teacher_remarks', $where);
    } 


    public function insert_head_teacher_comment($session, $term, $class_id, $student_id) {
        $comment = ucfirst($this->input->post('comment', TRUE));
        $data = array(
            'school_id' => school_id, 
            'student_id' => $student_id, 
            'class_id' => $class_id, 
            'session' => $session, 
            'term' => $term, 
            'comment' => $comment, 
        );
        return $this->db->insert('kad_end_term_head_teacher_remarks', $data);   
    } 


    public function update_head_teacher_comment($session, $term, $class_id, $student_id) {
        $comment = ucfirst($this->input->post('comment', TRUE));
        $query = $this->check_head_teacher_comment_exists($session, $term, $class_id, $student_id);
        $comment_id = $query->row()->id;
        $data = array(
            'comment' => $comment, 
        );
        $this->db->where('id', $comment_id);    
        return $this->db->update('kad_end_term_head_teacher_remarks', $data);   
    }





    /* ========== Results and Report Card ========== */
    public function get_result_details_by_id($result_id) { 
        return $this->db->get_where('kad_end_term_student_results', array('id' => $result_id))->row();
    }

    
    public function get_result_details($session, $term, $class_id, $student_id) { 
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        return $this->db->get_where('kad_end_term_student_results', $where)->row();
    }


    public function get_class_results($session, $term, $class_id) { 
        $this->db->order_by('total_scored', 'desc'); //highest score first
        $where = array(
            'class_id' => $class_id, 
            'session' => $session, 
            'term' => $term, 
        );
        return $this->db->get_where('kad_end_term_student_results', $where)->result();
    }
    
    
    public function get_student_result_sessions($student_id) { 
        return $this->db->get_where('kad_end_term_student_results', array('student_id' => $student_id))->result();
    }


    public function check_signature($signature, $controller) {
        if ($signature != NULL) {
            return TRUE;
        } else {
            $signature_url = '<a href="' . base_url($controller.'/signature') . '" target="_blank" style="color: green"><b>here</b></a>';
            $this->session->set_flashdata('status_msg_error', "No signature found. Please update your signature {$signature_url} before continuing.");
            redirect($this->agent->referrer());
        }
    } 


    public function get_result_decision($session, $term, $class_id, $student_id) {
        //check if category is pre_kg  and return null (pre_kg has no assessment score)
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
        $category = $this->kad_students_end_term_report_model->get_template_details($template_id)->category;
        if ($category != 'secondary') {
            return '<b>-</b>';
        }

        $class_details = $this->common_model->get_class_details($class_id); 
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $percentage_scored = $this->the_percentage_score($session, $term, $class_id, $student_id);
        //get pass mark
        $pass_mark = $this->common_model->get_section_details($section_id)->pass_mark;
        //pass if percentage score is greater than or equals pass mark, fail otherwise
        return ($percentage_scored >= $pass_mark) ? '<b class="text-success">Passed</b>' : '<b class="text-danger">Failed</b>';
    } 


    public function get_result_approval_status($session, $term, $class_id, $student_id) {
        //get status
        $q_status = $this->get_result_details($session, $term, $class_id, $student_id)->status;
        switch ($q_status) {
            case 'Approved': 
                $status = '<b class="text-success">Approved</b>';
            break;
            case 'Rejected': 
                $status = '<b class="text-danger">Rejected</b>';
            break;
            default:
                $status = '<b class="text-primary">Pending</b>';
            break;
        }
        return $status;
    } 


    public function get_class_test_scores($session, $term, $class_id) {
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
        $category = $this->get_template_details($template_id)->category;
        $table = $this->get_test_score_table($category);
        $where = $this->class_where_array($session, $term, $class_id);
        $this->db->where($where);
        return $this->db->get($table)->result();
    } 


    public function get_test_scores($session, $term, $class_id, $student_id) {
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
        $category = $this->get_template_details($template_id)->category;
        $table = $this->get_test_score_table($category);
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        $this->db->order_by('subject_id', "asc"); //important esp for broadsheet report
        $this->db->where($where);
        return $this->db->get($table)->result();
    } 


    public function get_attendance_info($session, $term, $class_id, $student_id) {
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        $query = $this->db->get_where('kad_end_term_student_results', $where)->row();
        if ($query) {
            $att_info = 'Present: ' . $query->att_present . '; ';
            $att_info .= 'Absent: ' . $query->att_absent . '; ';
            $att_info .= 'Tardy: ' . $query->att_tardy;
        } else {
            $att_info = '';
        }
        return $att_info;
    } 


    public function get_class_teacher_comment($session, $term, $class_id, $student_id) {
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        $query = $this->db->get_where('kad_end_term_class_teacher_remarks', $where);
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
        $allow_class_teacher_comment = $this->get_template_details($template_id)->allow_class_teacher_comment;
        if ($allow_class_teacher_comment == 'true') {
            return ($query->num_rows() > 0) ? $query->row()->comment : NULL; 
        } else {
            return '(not required)';
        }
    } 


    public function get_head_teacher_comment($session, $term, $class_id, $student_id) {
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        $query = $this->db->get_where('kad_end_term_head_teacher_remarks', $where);
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
        $allow_head_teacher_comment = $this->get_template_details($template_id)->allow_head_teacher_comment;
        if ($allow_head_teacher_comment == 'true') {
            return ($query->num_rows() > 0) ? $query->row()->comment : NULL; 
        } else {
            return '(not required)';
        }
    } 


    public function get_total_possible_score($session, $term, $class_id, $student_id) {
        $test_scores = $this->get_test_scores($session, $term, $class_id, $student_id);
        //total possible score = total no of subjects * 100
        $total_possible_score = count($test_scores) * 100; 
        return ($total_possible_score > 0) ? $total_possible_score : NULL;
    }


    public function get_subject_total_score($session, $term, $class_id, $student_id, $subject_id) {
        $test_scores = $this->get_test_scores($session, $term, $class_id, $student_id);
        $total_test_score = 0;
        foreach ($test_scores as $t) {
            if ($t->subject_id == $subject_id) { 
                $total_test_score = $t->assessment;     
                break;
            }
        }
        $class_details = $this->common_model->get_class_details($class_id); 
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        //compare total score to passmark, show in red if less than passmark
        $pass_mark = $this->common_model->get_section_details($section_id)->pass_mark;
        if ($total_test_score >= $pass_mark) {
            return $total_test_score;
        } else {
            return '<span class="subject_score_red">' . $total_test_score . '</span>';
        }
    }


    public function get_overall_total_score($session, $term, $class_id, $student_id) {
        $test_scores = $this->get_test_scores($session, $term, $class_id, $student_id);
        $overall_total_score = 0;
        foreach ($test_scores as $t) { 
            $total_test_score = $t->assessment;   
            $overall_total_score += $total_test_score; //accumulate the total scores
        } 
        return ($overall_total_score > 0) ? $overall_total_score : NULL;
    }


    public function the_percentage_score($session, $term, $class_id, $student_id) {
        $total_possible_score = $this->get_total_possible_score($session, $term, $class_id, $student_id);
        $total_scored = $this->get_overall_total_score($session, $term, $class_id, $student_id);
        if ($total_possible_score > 0 && $total_scored > 0) {
            //percentage score = (total scored / total possible score) * 100
            $percentage_score = ($total_scored / $total_possible_score) * 100;
            //convert percentage score to integer
            return intval($percentage_score);
        } else {
            return NULL;
        }
    }

 
    public function get_overall_percentage_score($session, $term, $class_id, $student_id) {
        $percentage_score = $this->the_percentage_score($session, $term, $class_id, $student_id);
        if ($percentage_score != NULL) {
            return ($percentage_score > 0) ? (number_format($percentage_score, 1).'%') : NULL; //round off to 1 d.p.
        } else {
            return NULL;
        }
    } 


    public function get_student_position($session, $term, $class_id, $student_id) {
        $result_exists = $this->get_result_details($session, $term, $class_id, $student_id);
        if ($result_exists) {

            //check if category is pre_kg  and return null (pre_kg has no assessment score)
            $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
            $category = $this->kad_students_end_term_report_model->get_template_details($template_id)->category;
            if ($category != 'secondary') {
                return '<b>-</b>';
            }

            //rank the scores, create a column called position and save the ranks
            $query = "SELECT *, FIND_IN_SET( total_scored, 
                    ( SELECT GROUP_CONCAT( total_scored ORDER BY total_scored DESC ) 
                    FROM kad_end_term_student_results 
                    WHERE 
                        class_id = '$class_id' AND 
                        session = '$session' AND 
                        term = '$term'
                    )
                    ) AS position
                    FROM kad_end_term_student_results
                    WHERE 
                        student_id = '$student_id'";
            $position = $this->db->query($query)->row()->position;
            //ordinalize the position (i.e. 1st, 2nd, 3rd, 4th, etc)
            $position = get_ordinal_number($position);
            return $position;
        } else {
            return NULL;
        }
    }


    public function get_student_passport($student_id) {
        $y = $this->common_model->get_student_details_by_id($student_id);
        $passport = $y->passport;
        return ($passport != NULL) ? base_url('assets/uploads/photos/students/'.$passport) : NULL;
    } 


    public function get_student_dob($student_id) {
        $y = $this->common_model->get_student_details_by_id($student_id);
        $dob = $y->date_of_birth;
        return ($dob != NULL) ? x_date_full($dob) : NULL;
    } 


    public function get_student_admission_date($student_id) {
        $y = $this->common_model->get_student_details_by_id($student_id);
        $admission_date = $y->admission_date;
        return ($admission_date != NULL) ? x_date_full($admission_date) : NULL;
    }
    
    
    public function get_subject_teacher($test_score_id, $class_id) {
        $test_details = $this->get_test_score_details($test_score_id, $class_id);
        $subject_id = $test_details->subject_id;
        //check if class and subject are assigned to a subject teacher
        $this->db->like('classes_assigned', $class_id, 'both'); //wildcard %class_id%
        $this->db->like('subjects_assigned', $subject_id, 'both'); //wildcard %subject_id%
        $this->db->limit(1);
        $this->db->where('school_id', school_id);
        $query = $this->db->get('subject_teachers')->row();
        return ($query) ? $query->staff_id : NULL;
    } 


    public function get_subject_teacher_name($test_score_id, $class_id) {
        $subject_teacher_id = $this->get_subject_teacher($test_score_id, $class_id);
        if ($subject_teacher_id == NULL) { 
            return NULL;
        } else {
            $subject_teacher_details = $this->common_model->get_staff_details_by_id($subject_teacher_id);
            $title = $subject_teacher_details->title;
            $full_name = $subject_teacher_details->name;
            $full_name = trim($full_name);
            $names = explode(" ", $full_name); //break name string into an array of individual words
            if ( count($names) > 1 ) { //name contains at least 2 words
                $last_name = $names[0]; //array index 0, surname
                $first_name = $names[1]; //array index 1, firstname
                $initial = mb_substr($first_name, 0, 1);
                $subject_teacher_name = $title . ' ' . $initial . '. ' . $last_name;
            } else {
                $last_name = $names[0]; //array index 0, surname
                $subject_teacher_name = $title . ' ' . $last_name;
            }
            return $subject_teacher_name;
        } 
    } 


    public function get_class_teacher_name($session, $term, $class_id, $student_id) {
        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        if ($result_details) {
            $class_details = $this->common_model->get_class_details($class_id);
            $class_teacher_id = $class_details->class_teacher_id;
            if ($class_teacher_id != NULL) {
                $class_teacher_details = $this->common_model->get_staff_details_by_id($class_teacher_id);
                $name = $class_teacher_details->title . ' ' . $class_teacher_details->name;
                return $name;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    } 


    public function get_class_teacher_signature($session, $term, $class_id, $student_id) {
        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        if ($result_details) {
            $class_details = $this->common_model->get_class_details($class_id);
            $class_teacher_id = $class_details->class_teacher_id;
            if ($class_teacher_id != NULL) {
                $class_teacher_details = $this->common_model->get_staff_details_by_id($class_teacher_id);
                $signature = $class_teacher_details->signature;
                //if teacher has updated signature, return signature, else return default signature
                return ($signature != NULL) ? base_url('assets/uploads/signature/staff/'.$signature) : staff_signature;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    } 


    public function get_head_teacher_designation($session, $term, $class_id, $student_id) {
        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        if ($result_details) {
            $status = $result_details->status;
            $head_teacher_id = $result_details->approved_by;
            //check if result has been approved by head teacher
            if ($status == 'Approved') {
                $head_teacher_details = $this->common_model->get_admin_details_by_id($head_teacher_id);
                $designation = $head_teacher_details->designation;
                return $designation;
            } else { //result pending approval or rejected
                return 'Head Teacher / Principal';
            }
        } else {
            return 'Head Teacher / Principal';
        }
    } 


    public function get_head_teacher_name($session, $term, $class_id, $student_id) {
        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        if ($result_details) {
            $status = $result_details->status;
            $head_teacher_id = $result_details->approved_by;
            //check if result has been approved by head teacher
            if ($status == 'Approved') {
                $head_teacher_details = $this->common_model->get_admin_details_by_id($head_teacher_id);
                $name = $head_teacher_details->name;
                return $name;
            } else { //result pending approval or rejected
                return NULL;
            }
        } else {
            return NULL;
        }
    } 


    public function get_head_teacher_signature($session, $term, $class_id, $student_id) {
        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        if ($result_details) {
            $status = $result_details->status;
            $head_teacher_id = $result_details->approved_by;
            //check if result has been approved by head teacher
            if ($status == 'Approved') {
                $head_teacher_details = $this->common_model->get_admin_details_by_id($head_teacher_id);
                $signature = $head_teacher_details->signature;
                //if head teacher has updated signature, return signature, else return default signature
                return ($signature != NULL) ? base_url('assets/uploads/signature/admins/'.$signature) : admin_signature;
            } else { //result pending approval or rejected
                return NULL;
            }
        } else {
            return NULL;
        }
    } 


    public function get_school_stamp($session, $term, $class_id, $student_id) {
        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        if ($result_details) {
            $status = $result_details->status;
            //check if result has been approved by head teacher
            if ($status == 'Approved') {
                $school_info = $this->common_model->get_school_info(school_id);
                $stamp = $school_info->school_stamp;
                //if school stamp has been updated, return school stamp, else return default stamp
                return ($stamp != NULL) ? base_url('assets/uploads/stamp/'.$stamp) : school_stamp;
            } else { //result pending approval or rejected
                return NULL;
            }
        } else {
            return NULL;
        }
    } 


    public function get_report_approved_date($session, $term, $class_id, $student_id) {
        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        if ($result_details) {
            $status = $result_details->status;      
            //check if result has been approved by head teacher
            if ($status == 'Approved') {
                return x_date($result_details->date_approved);
            } else { //result pending approval or rejected
                return NULL;
            }
        } else {
            return NULL;
        }
    } 


    


    /* ========== Head Teacher ========== */
    public function approve_result($result_id, $approved_by) {
        $data = array(
            'status' => 'Approved',
            'approved_by' => $approved_by,
            'date_approved' => default_calendar_date(),
        );
        $this->db->where('id', $result_id); 
        return $this->db->update('kad_end_term_student_results', $data); 
    } 


    public function reject_result($result_id) {
        $data = array(
            'status' => 'Rejected',
            'approved_by' => NULL,
            'date_approved' => NULL,
        );
        $this->db->where('id', $result_id); 
        return $this->db->update('kad_end_term_student_results', $data); 
    } 


    public function mark_result_pending($result_id) {
        $data = array(
            'status' => 'Pending',
            'approved_by' => NULL,
            'date_approved' => NULL,
        );
        $this->db->where('id', $result_id); 
        return $this->db->update('kad_end_term_student_results', $data); 
    } 


    public function approve_all_results($session, $term, $class_id, $approved_by) {
        $where = array(
            'class_id' => $class_id, 
            'session' => $session, 
            'term' => $term,
        );
        $data = array(
            'status' => 'Approved',
            'approved_by' => $approved_by,
            'date_approved' => default_calendar_date(),
        );
        $this->db->where($where);   
        return $this->db->update('kad_end_term_student_results', $data); 
    } 


    public function reject_all_results($session, $term, $class_id) {
        $where = array(
            'class_id' => $class_id, 
            'session' => $session, 
            'term' => $term,
        );
        $data = array(
            'status' => 'Rejected',
            'approved_by' => NULL,
            'date_approved' => NULL,
        );
        $this->db->where($where);   
        return $this->db->update('kad_end_term_student_results', $data); 
    } 


    public function mark_all_results_pending($session, $term, $class_id) {
        $where = array(
            'class_id' => $class_id, 
            'session' => $session, 
            'term' => $term,
        );
        $data = array(
            'status' => 'Pending',
            'approved_by' => NULL,
            'date_approved' => NULL,
        );
        $this->db->where($where);   
        return $this->db->update('kad_end_term_student_results', $data); 
    } 


    public function set_resumption_class($result_id) {
        $data = array(
            'resumption_class_id' => $this->input->post('resumption_class_id', TRUE)
        );
        $this->db->where('id', $result_id);
        return $this->db->update('kad_end_term_student_results', $data); 
    } 
    
    
    public function bulk_actions_reports($approved_by) {
        $selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
        $bulk_action_type = $this->input->post('bulk_action_type', TRUE);       
        $row_id = $this->input->post('check_bulk_action', TRUE);
        $results = ($selected_rows == 1) ? 'result' : 'results';
        foreach ($row_id as $result_id) {
            switch ($bulk_action_type) {
                case 'approve':
                    $this->approve_result($result_id, $approved_by);
                    $this->session->set_flashdata('status_msg', "{$selected_rows} {$results} approved successfully.");
                break;
                case 'reject':
                    $this->reject_result($result_id);
                    $this->session->set_flashdata('status_msg', "{$selected_rows} {$results} rejected successfully.");
                break;
                case 'mark_pending':
                    $this->mark_result_pending($result_id);
                    $this->session->set_flashdata('status_msg', "{$selected_rows} {$results} marked pending successfully.");
                break;
                case 'promote':
                    $this->bulk_students_resumption_class($result_id);
                break;
            }
        } 
    }


    public function bulk_students_resumption_class($result_id) { 
        $selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
        $this->form_validation->set_rules('resumption_class_id', 'Resumption Class', 'trim|required');
        $resumption_class_id = $this->input->post('resumption_class_id', TRUE);
        $resumption_class = $this->common_model->get_class_details($resumption_class_id)->class;
        $students = ($selected_rows == 1) ? 'student' : 'students';
        if ($this->form_validation->run()) {
            $this->set_resumption_class($result_id);
            $this->session->set_flashdata('status_msg', "Resumption class successfully set to {$resumption_class} for {$selected_rows} {$students}.");
        } else {
            $this->session->set_flashdata('status_msg_error', "Error setting resumption class.");
        }
    }





    /* ============= Conditional Redirects =============== */
    public function check_class_result_exists($session, $term, $class_id, $controller) { 
        $total_results = count($this->get_class_results($session, $term, $class_id));
        if ($total_results > 0) {
            return TRUE;
        } else {
            $class = $this->common_model->get_class_details($class_id)->class;
            $this->session->set_flashdata('status_msg_error', "No result found for this {$class} class");
            //redirect to produce report of admin or staff panel
            redirect(site_url($controller.'/end_term_reports/'.$session.'/'.$term.'/'.$class_id));
        }
    }


    public function check_result_exists($session, $term, $class_id, $student_id, $controller) { 
        $query = $this->get_result_details($session, $term, $class_id, $student_id);
        if ($query) {
            return TRUE;
        } else {
            $this->session->set_flashdata('status_msg_error', "No result data found!");
            //redirect to produce report of admin or staff panel
            redirect(site_url($controller.'/produce_report/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id));
        }
    }


    public function check_report_template($template_id, $session, $term, $class_id, $student_id, $controller) { 
        //Ensure template ID exists and is the one attached to the section
        $template_exists = $this->get_template_details($template_id);
        $attached_template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
        if ($template_exists && $template_id === $attached_template_id) {
            return TRUE;
        } elseif ($template_exists && $template_id != $attached_template_id) {
            //redirect to report card page with attached template ID
            $report_card_url = $this->c_controller.'/report_card/'.$attached_template_id.'/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;
            redirect($report_card_url);
        } elseif ( ! $template_exists) { 
            //template ID does not exist, redirect to restricted access page
            redirect($controller.'/restricted_access');
        }
    }


    public function check_report_card_ok_staff($session, $term, $class_id, $student_id) {
        //create array to hold error messages
        $error_messages = array();

        //subject/test scores
        $test_scores = count($this->get_test_scores($session, $term, $class_id, $student_id));
        if ($test_scores == 0) {
            $message = 'Subject scores not submitted!';
            array_push($error_messages, $message);
        }

        //class teacher comment
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
        $allow_class_teacher_comment = $this->get_template_details($template_id)->allow_class_teacher_comment;
        $comment = $this->get_class_teacher_comment($session, $term, $class_id, $student_id);
        if ($allow_class_teacher_comment == 'true' && $comment == NULL) {
            $message = 'Class Teacher\'s comment not submitted!';
            array_push($error_messages, $message);
        } 

        if ( ! empty($error_messages) ) {
            $error_messages_string = implode("<br />", $error_messages);
            $error_messages_string = '<b>Please fix the following:</b> <br />' . $error_messages_string; 
            return $this->session->set_flashdata('status_msg_error', $error_messages_string);   
        }
    } 


    public function check_report_card_ok_admin($session, $term, $class_id, $student_id) {
        //create array to hold error messages
        $error_messages = array();

        //subject/test scores
        $test_scores = count($this->get_test_scores($session, $term, $class_id, $student_id));
        if ($test_scores == 0) {
            $message = 'Subject scores not submitted!';
            array_push($error_messages, $message);
        }

        //class teacher comment
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
        $allow_class_teacher_comment = $this->get_template_details($template_id)->allow_class_teacher_comment;
        $comment = $this->get_class_teacher_comment($session, $term, $class_id, $student_id);
        if ($allow_class_teacher_comment == 'true' && $comment == NULL) {
            $message = 'Class Teacher\'s comment not submitted!';
            array_push($error_messages, $message);
        } 

        //head teacher comment
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
        $allow_head_teacher_comment = $this->get_template_details($template_id)->allow_head_teacher_comment;
        $comment = $this->get_head_teacher_comment($session, $term, $class_id, $student_id);
        if ($allow_head_teacher_comment == 'true' && $comment == NULL) {
            $message = 'Head Teacher\'s comment not submitted!';
            array_push($error_messages, $message);
        } 

        //approval status
        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        $status = $result_details->status;
        //check if result has been approved by head teacher
        if ($status != 'Approved') { //result pending approval or rejected
            $message = 'Result not approved!';
            array_push($error_messages, $message);
        } else { //result approved
            $message = NULL;
        }

        if ( ! empty($error_messages) ) {
            $error_messages_string = implode("<br />", $error_messages);
            $error_messages_string = '<b>Please fix the following:</b> <br />' . $error_messages_string; 
            return $this->session->set_flashdata('status_msg_error', $error_messages_string);   
        }
    } 


    public function check_approval_status($session, $term, $class_id, $student_id, $controller) {
        //check if result has been approved by head teacher. if true, disallow further production action (from class teacher only)
        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        $status = $result_details->status;
        //check if result has been approved by head teacher
        if ($status != 'Approved') { //result pending approval or rejected
            return TRUE;
        } else { //result has been approved
            $this->session->set_flashdata('status_msg_error', "This result has been approved. Further action no longer possible.");
            //redirect to produce report page of user's panel
            redirect(site_url($controller.'/produce_report/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id));
        }
    } 
    

    
    
}