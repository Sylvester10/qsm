<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: Students_mid_term_report_model
Role: Model
Description: Controls the DB processes of students' reports
Controller: Kad_students_mid_term_report, Kad_students_mid_term_report_staff, Kad_students_mid_term_report_subject_teacher, Kad_student_mid_term_reports, Student_mid_term_reports_parent
Author: Nwankwo Ikemefuna
Date Created: 20th February, 2019
*/


class Kad_students_mid_term_report_model extends CI_Model {
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
        $template_id = $class_details->bespoke_mt_template_id;
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
        $data['class_population'] =  $this->common_model->get_class_population($class_id);
        $data['test_scores'] = $this->get_test_scores($session, $term, $class_id, $student_id);
        $data['total_possible_score'] = $this->get_total_possible_score($session, $term, $class_id, $student_id);
        $data['overall_total_score'] = $this->get_overall_total_score($session, $term, $class_id, $student_id);
        $data['overall_percentage_score'] = $this->get_overall_percentage_score($session, $term, $class_id, $student_id); 
        $data['position'] = $this->get_student_position($session, $term, $class_id, $student_id);
        $data['class_teacher_comment'] = $this->get_class_teacher_comment($session, $term, $class_id, $student_id);
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
        //template details
        $template_id = $class_details->bespoke_mt_template_id;
        $template_details = $this->get_template_details($template_id);
        $data['y'] = $student_details; 
        $data['student_name'] = $this->common_model->get_student_fullname($student_id);
        $data['student_dob'] = $this->get_student_dob($student_id);
        $data['student_admission_date'] = $this->get_student_admission_date($student_id);
        $data['student_sex'] = $student_details->sex;
        $data['admission_id'] = $student_details->admission_id;
        $data['session'] = $session; 
        $data['the_session'] = get_the_session($session); 
        $data['term'] = $term; 
        $data['class_id'] = $class_id; 
        $data['section_id'] = $section_id; 
        $data['class'] = $class_details->class;
        $data['student_id'] = $student_id; 
        $data['section'] =  $section_details->section;
        $data['class_details'] = $class_details;
        $data['result_details'] = $result_details;
        $data['template_id'] = $template_id;
        $data['allow_class_teacher_comment'] = $template_details->allow_class_teacher_comment;
        $data['class_population'] =  $this->common_model->get_class_population($class_id);
        $data['test_scores'] = $this->get_test_scores($session, $term, $class_id, $student_id);
         $data['total_possible_score'] = $this->get_total_possible_score($session, $term, $class_id, $student_id);
        $data['overall_total_score'] = $this->get_overall_total_score($session, $term, $class_id, $student_id);
        $data['overall_percentage_score'] = $this->get_overall_percentage_score($session, $term, $class_id, $student_id); 
        $data['position'] = $this->get_student_position($session, $term, $class_id, $student_id);
        $data['class_teacher_comment'] = $this->get_class_teacher_comment($session, $term, $class_id, $student_id);
        $data['class_teacher_name'] = $this->get_class_teacher_name($session, $term, $class_id, $student_id);
        $data['class_teacher_signature'] = $this->get_class_teacher_signature($session, $term, $class_id, $student_id);
        $data['date_approved'] = $this->get_report_approved_date($session, $term, $class_id, $student_id);
        $data['report_stamp'] = $this->get_school_stamp($session, $term, $class_id, $student_id);
        $data['sections'] = $this->school_sections();
        $data['school_info'] = $this->common_model->get_school_info(school_id);
        $data['term_info'] = $this->common_model->get_term_info(school_id);
        return $data;
    }


    public function report_data($session, $term, $class_id) {
        $class_details = $this->common_model->get_class_details($class_id);
        $section_id = $class_details->section_id;
        $section_details = $this->common_model->get_section_details($section_id);
        //template details
        $template_id = $class_details->bespoke_mt_template_id;
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
        return $this->db->get_where('kad_mid_term_report_templates', array('id' => $template_id))->row();
    }


    public function get_template_details_by_category($category) { 
        return $this->db->get_where('kad_mid_term_report_templates', array('category' => $category))->row();
    }




    /* ========== Test Scores ========== */
    public function get_test_score_table($category) { 
        $tables = [
            'pre_kg'        =>      'kad_mid_term_pre_kg_test_scores',
            'kg'            =>      'kad_mid_term_kg_test_scores',
            'snr_kg'        =>      'kad_mid_term_snr_kg_test_scores',
            'primary'       =>      'kad_mid_term_pri_test_scores',
            'secondary'     =>      'kad_mid_term_sec_test_scores',
        ];
        return $tables[$category];
    }



    //Category = Pre-KG
    public function get_pre_kg_subject_categories() { 
        return $this->db->get_where('kad_mid_term_pre_kg_subject_categories')->result();
    }


    public function get_pre_kg_subject_category_details($subject_cat_id) { 
        return $this->db->get_where('kad_mid_term_pre_kg_subject_categories', array('id' => $subject_cat_id))->row();
    }


    public function get_pre_kg_subject_category_items($subject_cat_id) { 
        return $this->db->get_where('kad_mid_term_pre_kg_subject_category_items', array('subject_cat_id' => $subject_cat_id))->result();
    }


    public function get_pre_kg_subject_category_item_details($subject_id) { 
        return $this->db->get_where('kad_mid_term_pre_kg_subject_category_items', array('id' => $subject_id))->row();
    }


    public function insert_pre_kg_test_score($session, $term, $class_id, $student_id, $subject_id, $progress, $category) {

        //check if progress was supplied, if not, set as NULL
        if ($progress != '') { 
            $progress = $progress;
        } else {
            $progress = NULL;
        }
        
        //check that assessment field has a value 
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


    public function update_pre_kg_test_score($session, $term, $class_id, $student_id, $subject_id, $progress, $category) {

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




    //Category = KG, Senior KG and Primary
    public function insert_kg_skg_primary_test_score($session, $term, $class_id, $student_id, $subject_id, $assessment, $subject_comment, $category) {
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


    public function update_kg_skg_primary_test_score($session, $term, $class_id, $student_id, $subject_id, $assessment, $subject_comment, $category) {

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
            'assessment' => $assessment, 
            'subject_comment' => $subject_comment, 
        );
        $table = $this->get_test_score_table($category);
        $this->db->where('id', $test_score_id); 
        $this->db->update($table, $data);    
        //update total scored
        $this->update_total_scored($session, $term, $class_id, $student_id);    
    } 


    //Category = KG, Senior KG, Primary
    public function get_kg_primary_test_score_rubric($session, $term, $class_id, $student_id, $subject_score, $item) {
        if ($subject_score >= 0) {
            switch ($subject_score) {
                case in_array($subject_score, range(0, 44)):
                    $data = [
                        'value'     => 3,
                        'remark'    => 'Not Satisfactory'
                    ];
                break;
                case in_array($subject_score, range(45, 64)):
                    $data = [
                        'value'     => 2,
                        'remark'    => 'Needs Improvement'
                    ];
                break;
                case in_array($subject_score, range(65, 100)):
                    $data = [
                        'value'     => 1,
                        'remark'    => 'Satisfactory'
                    ];
                break;
            }
            return $data[$item];
        } else {
            return NULL;
        }
    }



    //Category = Secondary
    public function insert_secondary_test_score($session, $term, $class_id, $student_id, $subject_id, $effort, $behaviour, $assessment, $subject_comment, $category) {
        //check if effort was supplied, if not, set as NULL
        if ($effort != '') { 
            $effort = $effort;
        } else {
            $effort = NULL;
        }
        //check if behaviour was supplied, if not, set as NULL
        if ($behaviour != '') { 
            $behaviour = $behaviour;
        } else {
            $behaviour = NULL;
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
                'behaviour' => $behaviour, 
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


    public function update_secondary_test_score($session, $term, $class_id, $student_id, $subject_id, $effort, $behaviour, $assessment, $subject_comment, $category) {
        //check if effort was supplied, if not, set as NULL
        if ($effort != '') { 
            $effort = $effort;
        } else {
            $effort = NULL;
        }
        //check if behaviour was supplied, if not, set as NULL
        if ($behaviour != '') { 
            $behaviour = $behaviour;
        } else {
            $behaviour = NULL;
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
            'behaviour' => $behaviour, 
            'assessment' => $assessment, 
            'subject_comment' => $subject_comment, 
        );
        $table = $this->get_test_score_table($category);
        $this->db->where('id', $test_score_id); 
        $this->db->update($table, $data);    
        //update total scored
        $this->update_total_scored($session, $term, $class_id, $student_id);    
    } 


    public function get_secondary_test_score_rubric($session, $term, $class_id, $student_id, $subject_score, $item) {
        if ($subject_score >= 0) {
            switch ($subject_score) {
                case in_array($subject_score, range(0, 49)):
                    $data = [
                        'value'     => 3,
                        'remark'    => 'Below Expectation'
                    ];
                break;
                case in_array($subject_score, range(50, 74)):
                    $data = [
                        'value'     => 2,
                        'remark'    => 'Good'
                    ];
                break;
                case in_array($subject_score, range(75, 100)):
                    $data = [
                        'value'     => 1,
                        'remark'    => 'Very Good'
                    ];
                break;
            }
            return $data[$item];
        } else {
            return NULL;
        }
    }






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
                'session' => $session, 
                'term' => $term,
                'total_scored' => $total_scored, 
                'status' => 'Pending', 
                'produced_by' => $class_teacher_id,
            );
            return $this->db->insert('kad_mid_term_student_results', $insert_data);  

        } else { //student data exists, do update

            $result_id = $query->id;
            $update_data = array(
                'total_scored' => $total_scored, 
                'produced_by' => $class_teacher_id,
            );
            $this->db->where('id', $result_id); 
            return $this->db->update('kad_mid_term_student_results', $update_data);  
        }
    }


    public function get_test_score_details($test_score_id, $class_id) {
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_mt_template_id;
        $category = $this->get_template_details($template_id)->category;
        $table = $this->get_test_score_table($category);
        return $this->db->get_where($table, array('id' => $test_score_id))->row();
    }


    public function check_test_score_exists($session, $term, $class_id, $student_id, $subject_id) {
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_mt_template_id;
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
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_mt_template_id;
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
        $result = $this->db->get_where('kad_mid_term_student_results', $where)->row();
        $total_scored = $result->total_scored;
        $result_id = $result->id;
        $data = array(
            'total_scored' => $total_scored - $total_test_scores
        );
        $this->db->where('id', $result_id);
        return $this->db->update('kad_mid_term_student_results', $data);
    } 




    /* ========== Class Teacher's Comments ========== */
    public function get_class_teacher_comment_details($comment_id) { 
        return $this->db->get_where('kad_mid_term_class_teacher_remarks', array('id' => $comment_id))->row();
    }


    public function check_class_teacher_comment_exists($session, $term, $class_id, $student_id) {
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        return $this->db->get_where('kad_mid_term_class_teacher_remarks', $where);
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
        return $this->db->insert('kad_mid_term_class_teacher_remarks', $data);   
    } 


    public function update_class_teacher_comment($session, $term, $class_id, $student_id) {
        $comment = ucfirst($this->input->post('comment', TRUE));
        $query = $this->check_class_teacher_comment_exists($session, $term, $class_id, $student_id);
        $comment_id = $query->row()->id;
        $data = array(
            'comment' => $comment, 
        );
        $this->db->where('id', $comment_id);    
        return $this->db->update('kad_mid_term_class_teacher_remarks', $data);   
    }





    /* ========== Results and Report Card ========== */
    public function get_result_details_by_id($result_id) { 
        return $this->db->get_where('kad_mid_term_student_results', array('id' => $result_id))->row();
    }

    
    public function get_result_details($session, $term, $class_id, $student_id) { 
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        return $this->db->get_where('kad_mid_term_student_results', $where)->row();
    }


    public function get_class_results($session, $term, $class_id) { 
        $this->db->order_by('total_scored', 'desc'); //highest score first
        $where = array(
            'class_id' => $class_id, 
            'session' => $session, 
            'term' => $term, 
        );
        return $this->db->get_where('kad_mid_term_student_results', $where)->result();
    }
    
    
    public function get_student_result_sessions($student_id) { 
        return $this->db->get_where('kad_mid_term_student_results', array('student_id' => $student_id))->result();
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
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_mt_template_id;
        $category = $this->kad_students_mid_term_report_model->get_template_details($template_id)->category;
        if ($category == 'pre_kg') {
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
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_mt_template_id;
        $category = $this->get_template_details($template_id)->category;
        $table = $this->get_test_score_table($category);
        $where = $this->class_where_array($session, $term, $class_id);
        $this->db->where($where);
        return $this->db->get($table)->result();
    } 


    public function get_test_scores($session, $term, $class_id, $student_id) {
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_mt_template_id;
        $category = $this->get_template_details($template_id)->category;
        $table = $this->get_test_score_table($category);
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        $this->db->order_by('subject_id', "asc"); //important esp for broadsheet report
        $this->db->where($where);
        return $this->db->get($table)->result();
    } 


    public function get_class_teacher_comment($session, $term, $class_id, $student_id) {
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        $query = $this->db->get_where('kad_mid_term_class_teacher_remarks', $where);
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_mt_template_id;
        $allow_class_teacher_comment = $this->get_template_details($template_id)->allow_class_teacher_comment;
        if ($allow_class_teacher_comment == 'true') {
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
            $template_id = $this->common_model->get_class_details($class_id)->bespoke_mt_template_id;
            $category = $this->kad_students_mid_term_report_model->get_template_details($template_id)->category;
            if ($category == 'pre_kg') {
                return '<b>-</b>';
            }

            //rank the scores, create a column called position and save the ranks
            $query = "SELECT *, FIND_IN_SET( total_scored, 
                    ( SELECT GROUP_CONCAT( total_scored ORDER BY total_scored DESC ) 
                    FROM kad_mid_term_student_results 
                    WHERE 
                        class_id = '$class_id' AND 
                        session = '$session' AND 
                        term = '$term'
                    )
                    ) AS position
                    FROM kad_mid_term_student_results
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
    
    
    public function get_subject_teacher($test_score_id) {
        $test_details = $this->get_test_score_details($test_score_id);
        $class_id = $test_details->class_id;
        $subject_id = $test_details->subject_id;
        //check if class and subject are assigned to a subject teacher
        $this->db->like('classes_assigned', $class_id, 'both'); //wildcard %class_id%
        $this->db->like('subjects_assigned', $subject_id, 'both'); //wildcard %subject_id%
        $this->db->limit(1);
        $this->db->where('school_id', school_id);
        $query = $this->db->get('subject_teachers')->row();
        return ($query) ? $query->staff_id : NULL;
    } 


    public function get_subject_teacher_name($test_score_id) {
        $subject_teacher_id = $this->get_subject_teacher($test_score_id);
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
        return $this->db->update('kad_mid_term_student_results', $data); 
    } 


    public function reject_result($result_id) {
        $data = array(
            'status' => 'Rejected',
            'approved_by' => NULL,
            'date_approved' => NULL,
        );
        $this->db->where('id', $result_id); 
        return $this->db->update('kad_mid_term_student_results', $data); 
    } 


    public function mark_result_pending($result_id) {
        $data = array(
            'status' => 'Pending',
            'approved_by' => NULL,
            'date_approved' => NULL,
        );
        $this->db->where('id', $result_id); 
        return $this->db->update('kad_mid_term_student_results', $data); 
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
        return $this->db->update('kad_mid_term_student_results', $data); 
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
        return $this->db->update('kad_mid_term_student_results', $data); 
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
        return $this->db->update('kad_mid_term_student_results', $data); 
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
            }
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
            redirect(site_url($controller.'/mid_term_reports/'.$session.'/'.$term.'/'.$class_id));
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
        $attached_template_id = $this->common_model->get_class_details($class_id)->bespoke_mt_template_id;
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
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_mt_template_id;
        $allow_class_teacher_comment = $this->get_template_details($template_id)->allow_class_teacher_comment;
        $comment = $this->get_class_teacher_comment($session, $term, $class_id, $student_id);
        if ($allow_class_teacher_comment == 'true' && $comment == NULL) {
            $message = 'Class Teacher\'s comment not submitted!';
            array_push($error_messages, $message);
        } 

        //class teacher signature
        $class_teacher_id = $this->common_model->get_class_details($class_id)->class_teacher_id;
        if ($class_teacher_id != NULL) {
            $signature = $this->common_model->get_staff_details_by_id($class_teacher_id)->signature;
            if ($signature == NULL) {
                $message = 'Class Teacher\'s signature not found! Default signature in use.';
                array_push($error_messages, $message);
            }
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
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_mt_template_id;
        $allow_class_teacher_comment = $this->get_template_details($template_id)->allow_class_teacher_comment;
        $comment = $this->get_class_teacher_comment($session, $term, $class_id, $student_id);
        if ($allow_class_teacher_comment == 'true' && $comment == NULL) {
            $message = 'Class Teacher\'s comment not submitted!';
            array_push($error_messages, $message);
        } 

        //class teacher signature
        $class_teacher_id = $this->common_model->get_class_details($class_id)->class_teacher_id;
        if ($class_teacher_id != NULL) {
            $signature = $this->common_model->get_staff_details_by_id($class_teacher_id)->signature;
            if ($signature == NULL) {
                $message = 'Class Teacher\'s signature not found! Default signature in use.';
                array_push($error_messages, $message);
            }
        }

        //approval status
        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        $status = $result_details->status;
        //check if result has been approved by head teacher
        if ($status != 'Approved') { //result pending approval or rejected
            $message = 'Result not approved!';
            array_push($error_messages, $message);
        } else { //result approved
            //check school stamp
            $school_info = $this->common_model->get_school_info(school_id);
            $stamp = $school_info->school_stamp;
            if ($stamp == NULL) {
                $message = 'School stamp not found! Default in use.';
                array_push($error_messages, $message);
            }
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