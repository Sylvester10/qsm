<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: Students_mid_term_report_model
Role: Model
Description: Controls the DB processes of students' reports
Controller: Students_report, Students_mid_term_report_staff, Students_mid_term_report_subject_teacher, Student_mid_term_reports, Student_mid_term_reports_parent
Author: Nwankwo Ikemefuna
Date Created: 27th June, 2018
*/


class Students_mid_term_report_model extends CI_Model {
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
        $data['mt_classwork'] = $section_details->mt_classwork;
        $data['mt_homework'] = $section_details->mt_homework;
        $data['mt_tests'] = $section_details->mt_tests;
        $data['mt_total'] = $section_details->mt_total;
		$data['mt_class_teacher_comment'] = $section_details->mt_class_teacher_comment;
        $data['ranking'] = $section_details->ranking;
        $data['pass_mark'] = $section_details->pass_mark;
        $data['show_previous_grade'] = $section_details->show_previous_grade;
        $data['show_target_grade'] = $section_details->show_target_grade;
        $data['show_gp'] = $section_details->show_gp;
        $data['show_class_min'] = $section_details->show_class_min;
        $data['show_class_max'] = $section_details->show_class_max;
        $data['show_class_average'] = $section_details->show_class_average;
        $data['show_subject_teacher_name'] = $section_details->show_subject_teacher_name;
        $data['show_subject_teacher_comment'] = $section_details->show_subject_teacher_comment;
        $data['show_class_teacher_name'] = $section_details->show_class_teacher_name;
        $data['show_head_teacher_name'] = $section_details->show_head_teacher_name;
        $data['class_details'] = $class_details;
        $data['result_details'] = $result_details;
        $data['class_population'] =  $this->common_model->get_class_population($class_id);
        $data['test_scores'] = $this->get_test_scores($session, $term, $class_id, $student_id);
        $data['total_possible_score'] = $this->get_total_possible_score($session, $term, $class_id, $student_id);
        $data['overall_total_score'] = $this->get_overall_total_score($session, $term, $class_id, $student_id);
        $data['overall_percentage_score'] = $this->get_overall_percentage_score($session, $term, $class_id, $student_id); 
        $data['overall_grade'] = $this->get_overall_score_data($session, $term, $class_id, $student_id, 'grade');
        $data['evaluation'] = $this->get_overall_score_data($session, $term, $class_id, $student_id, 'evaluation');
        $data['position'] = $this->get_student_position($session, $term, $class_id, $student_id);
        $data['class_percentage_average'] = $this->get_class_percentage_average($session, $term, $class_id, $student_id);
        $data['student_passport'] = $this->get_student_passport($student_id);
        $data['class_teacher_comment'] = $this->get_class_teacher_comment($session, $term, $class_id, $student_id);
        $data['date_approved'] = $this->get_report_approved_date($session, $term, $class_id, $student_id);
        $data['report_stamp'] = $this->get_school_stamp($session, $term, $class_id, $student_id);
        $data['sections'] = $this->school_sections();
        return $data;
    }


    public function report_card_data($session, $term, $class_id, $student_id) {
        $result_details = $this->get_result_details($session, $term, $class_id, $student_id);
        $student_details = $this->common_model->get_student_details_by_id($student_id);
        $class_details = $this->common_model->get_class_details($class_id);
        $section_id = $class_details->section_id;
        $section_details = $this->common_model->get_section_details($section_id);
        $data['y'] = $student_details; 
        $data['student_name'] = $this->common_model->get_student_fullname($student_id);
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
        $data['mt_classwork'] = $section_details->mt_classwork;
        $data['mt_homework'] = $section_details->mt_homework;
        $data['mt_tests'] = $section_details->mt_tests;
        $data['mt_total'] = $section_details->mt_total;
        $data['mt_class_teacher_comment'] = $section_details->mt_class_teacher_comment;
        $data['ranking'] = $section_details->ranking;
        $data['pass_mark'] = $section_details->pass_mark;
        $data['enable_subject_grouping'] = $section_details->enable_subject_grouping;
        $data['show_previous_grade'] = $section_details->show_previous_grade;
        $data['show_target_grade'] = $section_details->show_target_grade;
        $data['show_gp'] = $section_details->show_gp;
        $data['show_subject_position'] = $section_details->show_subject_position;
        $data['show_class_min'] = $section_details->show_class_min;
        $data['show_class_max'] = $section_details->show_class_max;
        $data['show_class_average'] = $section_details->show_class_average;
        $data['show_subject_teacher_name'] = $section_details->show_subject_teacher_name;
        $data['show_subject_teacher_comment'] = $section_details->show_subject_teacher_comment;
        $data['show_class_teacher_name'] = $section_details->show_class_teacher_name;
        $data['show_head_teacher_name'] = $section_details->show_head_teacher_name;
        $data['show_performance_summary'] = $section_details->show_performance_summary;
        $data['show_student_passport'] = $section_details->show_student_passport;
        $data['class_details'] = $class_details;
        $data['result_details'] = $result_details;
        $data['class_population'] =  $this->common_model->get_class_population($class_id);
        $data['test_scores'] = $this->get_test_scores($session, $term, $class_id, $student_id);
        $data['total_possible_score'] = $this->get_total_possible_score($session, $term, $class_id, $student_id);
        $data['overall_total_score'] = $this->get_overall_total_score($session, $term, $class_id, $student_id);
        $data['overall_percentage_score'] = $this->get_overall_percentage_score($session, $term, $class_id, $student_id); 
        $data['overall_grade'] = $this->get_overall_score_data($session, $term, $class_id, $student_id, 'grade');
        $data['evaluation'] = $this->get_overall_score_data($session, $term, $class_id, $student_id, 'evaluation');
        $data['position'] = $this->get_student_position($session, $term, $class_id, $student_id);
        $data['class_percentage_average'] = $this->get_class_percentage_average($session, $term, $class_id, $student_id);
        $data['student_passport'] = $this->get_student_passport($student_id);
        $data['class_teacher_comment'] = $this->get_class_teacher_comment($session, $term, $class_id, $student_id);
        $data['class_teacher_name'] = $this->get_class_teacher_name($session, $term, $class_id, $student_id);
        $data['class_teacher_signature'] = $this->get_class_teacher_signature($session, $term, $class_id, $student_id);
        $data['head_teacher_designation'] = $this->get_head_teacher_designation($session, $term, $class_id, $student_id);
        $data['head_teacher_name'] = $this->get_head_teacher_name($session, $term, $class_id, $student_id);
        $data['head_teacher_signature'] = $this->get_head_teacher_signature($session, $term, $class_id, $student_id);
        $data['date_approved'] = $this->get_report_approved_date($session, $term, $class_id, $student_id);
        $data['report_stamp'] = $this->get_school_stamp($session, $term, $class_id, $student_id);
        $data['sections'] = $this->school_sections();
        $data['subject_groups'] = $this->common_model->get_subject_groups(school_id);
        $data['ungrouped_subjects'] = $this->common_model->get_ungrouped_subject(school_id);
        $data['school_info'] = $this->common_model->get_school_info(school_id);
        $data['term_info'] = $this->common_model->get_term_info(school_id);
        $data['report_evaluation'] = $this->common_model->get_report_evaluation(school_id);
        //rowspan for template 1
        $total_grade_keys = count($data['report_evaluation']);
        //rowspan should be total no of grade keys + 1 (1 is for the GRADE KEY title)
        $data['rowspan'] = $total_grade_keys + 1; //rowspan for table
        return $data;
    }


    public function report_data($session, $term, $class_id) {
        $class_details = $this->common_model->get_class_details($class_id);
        $section_id = $class_details->section_id;
        $section_details = $this->common_model->get_section_details($section_id);
        $data['session'] = $session; 
        $data['the_session'] = get_the_session($session);
        $data['term'] = $term; 
        $data['class_id'] = $class_id; 
        $data['section_id'] = $section_id; 
        $data['class'] = $class_details->class;
        $data['slug'] = $class_details->slug;
        $data['level'] = $class_details->level;
        $data['section'] =  $section_details->section;
        $data['ca_max_score'] = $section_details->ca_max_score;
        $data['exam_max_score'] = $section_details->exam_max_score;
        $data['ranking'] = $section_details->ranking;
        $data['pass_mark'] = $section_details->pass_mark;
        $data['show_previous_grade'] = $section_details->show_previous_grade;
        $data['show_target_grade'] = $section_details->show_target_grade;
        $data['show_gp'] = $section_details->show_gp;
        $data['show_class_min'] = $section_details->show_class_min;
        $data['show_class_max'] = $section_details->show_class_max;
        $data['show_class_average'] = $section_details->show_class_average;
        $data['class_details'] = $class_details;
        $data['class_population'] =  $this->common_model->get_class_population($class_id);
        $data['class_results'] = $this->get_class_results($session, $term, $class_id);
        $data['class_test_scores'] = $this->get_class_test_scores($session, $term, $class_id);
        $data['subjects'] = $this->common_model->get_subjects_by_section(school_id, $section_id);
        $data['school_info'] = $this->common_model->get_school_info(school_id);
        $data['report_evaluation'] = $this->common_model->get_report_evaluation(school_id);
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




    /* ========== Target Grade ========== */
    private function tg_student_where_array($session, $term, $class_id, $student_id, $subject_id) {
        $where = array(
            'student_id' => $student_id,
            'class_id' => $class_id,
            'subject_id' => $subject_id,
            'session' => $session,
            'term' => $term,
        );
        return $where;
    }


    public function get_target_grade_details($tg_id) { 
        return $this->db->get_where('mid_term_target_grades', array('id' => $tg_id))->row();
    }


    public function check_target_grade_exists($session, $term, $class_id, $student_id, $subject_id) {
        $where = $this->tg_student_where_array($session, $term, $class_id, $student_id, $subject_id);
        return $this->db->get_where('mid_term_target_grades', $where);
    }


    public function get_previous_grade($session, $term, $class_id, $student_id, $subject_id) {
        $where = $this->tg_student_where_array($session, $term, $class_id, $student_id, $subject_id);
        $query = $this->db->get_where('mid_term_target_grades', $where)->row();
        return $query ? $query->previous_grade : NULL;
    } 


    public function get_target_grade($session, $term, $class_id, $student_id, $subject_id) {
        $where = $this->tg_student_where_array($session, $term, $class_id, $student_id, $subject_id);
        $query = $this->db->get_where('mid_term_target_grades', $where)->row();
        return $query ? $query->target_grade : NULL;
    } 


    public function insert_target_grade($session, $term, $class_id, $student_id, $subject_id, $previous_grade, $target_grade) {

        //check if previous grade was supplied, if not, set as NULL
        if ($previous_grade != '') { 
            $previous_grade = strtoupper($previous_grade);
        } else {
            $previous_grade = NULL;
        }

        //check if target grade was supplied, if not, set as NULL
        if ($target_grade != '') { 
            $target_grade = strtoupper($target_grade);
        } else {
            $target_grade = NULL;
        }
        
        //check that target grade field has a value 
        if ($target_grade == '') { 
            return NULL;
        } else {
            $data = array(
                'school_id' => school_id, 
                'student_id' => $student_id, 
                'class_id' => $class_id, 
                'subject_id' => $subject_id, 
                'previous_grade' => $previous_grade, 
                'target_grade' => $target_grade, 
                'session' => $session, 
                'term' => $term, 
            );
            $this->db->insert('mid_term_target_grades', $data);
        }
    } 


    public function update_target_grade($session, $term, $class_id, $student_id, $subject_id, $previous_grade, $target_grade) {

        //check if previous grade was supplied, if not, set as NULL
        if ($previous_grade != '') { 
            $previous_grade = strtoupper($previous_grade);
        } else {
            $previous_grade = NULL;
        }

        //check if target grade was supplied, if not, set as NULL
        if ($target_grade != '') { 
            $target_grade = strtoupper($target_grade);
        } else {
            $target_grade = NULL;
        }

        $query = $this->check_target_grade_exists($session, $term, $class_id, $student_id, $subject_id);
        $tg_id = $query->row()->id;
        $data = array(
            'previous_grade' => $previous_grade, 
            'target_grade' => $target_grade, 
        );
        $this->db->where('id', $tg_id); 
        $this->db->update('mid_term_target_grades', $data);  
    } 


    public function delete_target_grade($tg_id) {
        $this->db->delete('mid_term_target_grades', array('id' => $tg_id));  
    } 



    


    /* ========== Test Scores ========== */
    public function get_test_score_details($test_score_id) { 
        return $this->db->get_where('mid_term_test_scores', array('id' => $test_score_id))->row();
    }


    public function check_test_score_exists($session, $term, $class_id, $student_id, $subject_id) {
        $where = array(
            'student_id' => $student_id,
            'class_id' => $class_id,
            'subject_id' => $subject_id,
            'session' => $session,
            'term' => $term,
        );
        return $this->db->get_where('mid_term_test_scores', $where);
    }


    public function insert_test_score($session, $term, $class_id, $student_id, $subject_id, $classwork, $homework, $tests, $subject_comment, $subject_teacher_id) {

        //check if classwork was supplied, if not, set as NULL
        if ($classwork != '') { 
            $classwork = $classwork;
        } else {
            $classwork = NULL;
        }
        //check if homework was supplied, if not, set as NULL
        if ($homework != '') { 
            $homework = $homework;
        } else {
            $homework = NULL;
        }
        //check if tests was supplied, if not, set as NULL
        if ($tests != '') { 
            $tests = $tests;
        } else {
            $tests = NULL;
        }
        //check if subject comment was supplied, if not, set as NULL
        if ($subject_comment != '') { 
            $subject_comment = ucfirst($subject_comment);
        } else {
            $subject_comment = NULL;
        }
        
        //check that at least classwork, homework or tests field has a value 
        if ($classwork == '' && $homework == '' && $tests == '') { 
            return NULL;
        } else {
            $data = array(
                'school_id' => school_id, 
                'student_id' => $student_id, 
                'class_id' => $class_id, 
                'subject_id' => $subject_id, 
                'classwork' => $classwork, 
                'homework' => $homework, 
                'tests' => $tests, 
                'subject_comment' => $subject_comment, 
                'subject_teacher_id' => $subject_teacher_id, 
                'session' => $session, 
                'term' => $term, 
            );
            $this->db->insert('mid_term_test_scores', $data);
            //update total scored
            $this->update_total_scored($session, $term, $class_id, $student_id);    
        }
    } 


    public function update_test_score($session, $term, $class_id, $student_id, $subject_id, $classwork, $homework, $tests, $subject_comment, $subject_teacher_id) {

        //check if classwork was supplied, if not, set as NULL
        if ($classwork != '') { 
            $classwork = $classwork;
        } else {
            $classwork = NULL;
        }
        //check if homework was supplied, if not, set as NULL
        if ($homework != '') { 
            $homework = $homework;
        } else {
            $homework = NULL;
        }
        //check if tests was supplied, if not, set as NULL
        if ($tests != '') { 
            $tests = $tests;
        } else {
            $tests = NULL;
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
            'classwork' => $classwork, 
            'homework' => $homework, 
            'tests' => $tests, 
            'subject_comment' => $subject_comment, 
            'subject_teacher_id' => $subject_teacher_id, 
        );
        $this->db->where('id', $test_score_id); 
        $this->db->update('mid_term_test_scores', $data);    
        //update total scored
        $this->update_total_scored($session, $term, $class_id, $student_id);    
    } 


    public function update_total_scored($session, $term, $class_id, $student_id) {
        //check if student result already exists in current class, session and term
        $query = $this->get_result_details($session, $term, $class_id, $student_id);
        //get student's class teacher
        $class_teacher_id = $this->common_model->get_class_details($class_id)->class_teacher_id;
            
        //accumulate scores 
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
            return $this->db->insert('mid_term_student_results', $insert_data);  

        } else { //student data exists, do update

            $result_id = $query->id;
            $update_data = array(
                'total_scored' => $total_scored, 
                'produced_by' => $class_teacher_id,
            );
            $this->db->where('id', $result_id); 
            return $this->db->update('mid_term_student_results', $update_data);  
        }
    }


    public function delete_test_score($test_score_id) {
        //update total scored
        $this->update_total_scored_on_test_delete($test_score_id);
        //delete the test score
        $this->db->delete('mid_term_test_scores', array('id' => $test_score_id));    
    } 


    public function update_total_scored_on_test_delete($test_score_id) {
        //update total scored by subtracting the sum of classwork, homework and tests scores for from total scored
        $t = $this->get_test_score_details($test_score_id);
        $total_test_scores = $t->classwork + $t->homework + $t->tests;
        //get result details
        $session = $t->session; 
        $term = $t->term; 
        $class_id = $t->class_id; 
        $student_id = $t->student_id; 
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        $result = $this->db->get_where('mid_term_student_results', $where)->row();
        $total_scored = $result->total_scored;
        $result_id = $result->id;
        $data = array(
            'total_scored' => $total_scored - $total_test_scores
        );
        $this->db->where('id', $result_id);
        return $this->db->update('mid_term_student_results', $data);
    } 




    /* ========== Class Teacher's Comments ========== */
    public function get_class_teacher_comment_details($comment_id) { 
        return $this->db->get_where('mid_term_class_teacher_remarks', array('id' => $comment_id))->row();
    }


    public function check_class_teacher_comment_exists($session, $term, $class_id, $student_id) {
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        return $this->db->get_where('mid_term_class_teacher_remarks', $where);
    } 


    public function insert_class_teacher_comment($session, $term, $class_id, $student_id) {
        $p_comment = $this->input->post('p_comment', TRUE);
        if ($p_comment) { //personalize comment checkbox is checked, collect personalized comment
            $comment = ucfirst($this->input->post('personalized_comment', TRUE));
            $personalized = 'true';
        } else { //personalize comment checkbox is not checked, collect comment from select field
            $comment = ucfirst($this->input->post('comment', TRUE));
            $personalized = 'false';
        }
        $data = array(
            'school_id' => school_id, 
            'student_id' => $student_id, 
            'class_id' => $class_id, 
            'session' => $session, 
            'term' => $term, 
            'comment' => $comment, 
            'personalized' => $personalized, 
        );
        return $this->db->insert('mid_term_class_teacher_remarks', $data);   
    } 


    public function update_class_teacher_comment($session, $term, $class_id, $student_id) {
        $p_comment = $this->input->post('p_comment', TRUE);
        if ($p_comment) { //personalize comment checkbox is checked, collect personalized comment
            $comment = ucfirst($this->input->post('personalized_comment', TRUE));
            $personalized = 'true';
        } else { //personalize comment checkbox is not checked, collect comment from select field
            $comment = ucfirst($this->input->post('comment', TRUE));
            $personalized = 'false';
        }
        $query = $this->check_class_teacher_comment_exists($session, $term, $class_id, $student_id);
        $comment_id = $query->row()->id;
        $data = array(
            'comment' => $comment, 
            'personalized' => $personalized,
        );
        $this->db->where('id', $comment_id);    
        return $this->db->update('mid_term_class_teacher_remarks', $data);   
    }





    /* ========== Results and Report Card ========== */
    public function get_result_details_by_id($result_id) { 
        return $this->db->get_where('mid_term_student_results', array('id' => $result_id))->row();
    }

    
    public function get_result_details($session, $term, $class_id, $student_id) { 
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        return $this->db->get_where('mid_term_student_results', $where)->row();
    }


    public function get_class_results($session, $term, $class_id) { 
        $this->db->order_by('total_scored', 'desc'); //highest score first
        $where = array(
            'class_id' => $class_id, 
            'session' => $session, 
            'term' => $term, 
        );
        return $this->db->get_where('mid_term_student_results', $where)->result();
    }
    
    
    public function get_student_result_sessions($student_id) { 
        return $this->db->get_where('mid_term_student_results', array('student_id' => $student_id))->result();
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
        $where = $this->class_where_array($session, $term, $class_id);
        $this->db->where($where);
        return $this->db->get('mid_term_test_scores')->result();
    } 


    public function get_test_scores($session, $term, $class_id, $student_id) {
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        $this->db->order_by('subject_id', "asc"); //important esp for broadsheet report
        $this->db->where($where);
        return $this->db->get('mid_term_test_scores')->result();
    } 


    public function get_class_teacher_comment($session, $term, $class_id, $student_id) {
        $where = $this->student_where_array($session, $term, $class_id, $student_id);
        $query = $this->db->get_where('mid_term_class_teacher_remarks', $where);
        return ($query->num_rows() > 0) ? $query->row()->comment : NULL; 
    } 


    public function get_total_possible_score($session, $term, $class_id, $student_id) {
        $test_scores = $this->get_test_scores($session, $term, $class_id, $student_id);
        //total possible score = total no of subjects * 100
        $total_possible_score = count($test_scores) * 100; 
        return ($total_possible_score > 0) ? $total_possible_score : NULL;
    }


    public function get_subject_unconverted_total_score($classwork, $homework, $tests) {
        $total = $classwork + $homework + $tests;
        return $total;
    }


    public function get_subject_percentage_score($class_id, $total_score) {
        $section_id = $this->common_model->get_class_details($class_id)->section_id;
        //get mt_total
        $mt_total = $this->common_model->get_section_details($section_id)->mt_total;
        //percentage score = (total scored / max total score) * 100 
        $percentage_score = ($total_score / $mt_total) * 100;
        return intval($percentage_score);
    }


    public function get_subject_total_score($session, $term, $class_id, $student_id, $subject_id) {
        $test_scores = $this->get_test_scores($session, $term, $class_id, $student_id);
        $total_test_score = 0;
        foreach ($test_scores as $t) {
            if ($t->subject_id == $subject_id) { 
                $total_test_score = $t->classwork + $t->homework + $t->tests;
                $total_test_score = $this->get_subject_percentage_score($class_id, $total_test_score);       
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
            $total_test_score = $t->classwork + $t->homework + $t->tests; 
            $converted_total = $this->get_subject_percentage_score($class_id, $total_test_score);
            $overall_total_score += $converted_total; //accumulate the total scores
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


    public function get_performance_summary($session, $term, $class_id, $student_id, $start_range, $end_range) {
        $test_scores = $this->get_test_scores($session, $term, $class_id, $student_id);
        //calculate performance summary
        $performance_summary = array();
        foreach ($test_scores as $t) {
            $total_test_score = $t->classwork + $t->homework + $t->tests; 
            $converted_total = $this->get_subject_percentage_score($class_id, $total_test_score);
            //check range of total score, then increment array by 1
            if ( in_array($converted_total, range($start_range, $end_range)) ) {
                $performance_summary[]++;
            }
        } 
        $performance_summary = count($performance_summary);
        return $performance_summary;
    }


    public function get_class_percentage_average($session, $term, $class_id, $student_id) {
        //sum up the total scores of students in this class
        $where = $this->class_where_array($session, $term, $class_id);
        $this->db->select_sum('total_scored');
        $this->db->where($where);
        $total_scores = $this->db->get('mid_term_student_results')->row()->total_scored;
        
        //divide total scores by number of students in this class whose results have been produced
        $total_students =  $this->db->get_where('mid_term_student_results', $where)->num_rows();
        //ensure total students is > 0 to avoid divisio by zero
        if ($total_students > 0) {
            $average_score = $total_scores / $total_students;
            //get percentage of average score
            $total_possible_score = $this->get_total_possible_score($session, $term, $class_id, $student_id);
            if ($total_possible_score > 0 && $average_score > 0) {
                //percentage average = (average score / total possible score) * 100
                $percentage_average = ($average_score / $total_possible_score) * 100;
                return ($percentage_average > 0) ? (number_format($percentage_average, 1).'%') : NULL; //round off to 1 d.p.
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }


    public function get_class_subject_minimum_score($session, $term, $class_id, $subject_id) {
        //query to get student with minimum total score in this subject
        $query =    "SELECT MIN(classwork + homework + tests) AS class_min FROM mid_term_test_scores WHERE 
                        class_id = '$class_id' AND 
                        subject_id = '$subject_id' AND 
                        session = '$session' AND 
                        term = '$term'
                    ";
        $class_min = $this->db->query($query)->row()->class_min;
        return ($class_min > 0) ? $class_min : NULL;
    }


    public function get_class_subject_maximum_score($session, $term, $class_id, $subject_id) {
        //query to get student with maximum total score in this subject
        $query =    "SELECT MAX(classwork + homework + tests) AS class_max FROM mid_term_test_scores WHERE 
                        class_id = '$class_id' AND 
                        subject_id = '$subject_id' AND 
                        session = '$session' AND 
                        term = '$term'
                    ";
        $class_max = $this->db->query($query)->row()->class_max;
        return ($class_max > 0) ? $class_max : NULL;
    }

    
    public function get_class_subject_average($session, $term, $class_id, $subject_id) {
        $where = array(
            'class_id' => $class_id, 
            'subject_id' => $subject_id, 
            'session' => $session, 
            'term' => $term, 
        );  
        //sum up the classwork scores of students for this subject in this class
        $this->db->select_sum('classwork');
        $this->db->where($where);
        $classworks = $this->db->get('mid_term_test_scores')->row()->classwork;
        
        //sum up the homework scores of students for this subject in this class
        $this->db->select_sum('homework');
        $this->db->where($where);
        $homeworks = $this->db->get('mid_term_test_scores')->row()->homework;

        //sum up the tests scores of students for this subject in this class
        $this->db->select_sum('tests');
        $this->db->where($where);
        $tests = $this->db->get('mid_term_test_scores')->row()->tests;
        
        //get total
        $total_scores = $classworks + $homeworks + $tests;
        
        //divide total scores by number of students in this class whose results have been produced
        $where2 = $this->class_where_array($session, $term, $class_id);
        $total_students =  $this->db->get_where('mid_term_student_results', $where2)->num_rows();
        $subject_average = $total_scores / $total_students;
        $subject_average = intval($subject_average); //get average as whole number
        return ($subject_average > 0) ? $subject_average : NULL;
    }


    public function get_subject_position($session, $term, $class_id, $student_id, $subject_id) {
        $test_score_exists = $this->check_test_score_exists($session, $term, $class_id, $student_id, $subject_id);
        if ($test_score_exists) {
            //rank the scores, create a column called position and save the ranks
            $query =    "SELECT *, 
                            FIND_IN_SET( (classwork + homework + tests), 
                                ( SELECT GROUP_CONCAT( (classwork + homework + tests) ORDER BY (classwork + homework + tests) DESC ) 
                                    FROM mid_term_test_scores 
                                    WHERE 
                                        class_id = '$class_id' AND 
                                        subject_id = '$subject_id' AND 
                                        session = '$session' AND 
                                        term = '$term'
                                )
                            ) AS position
                        FROM mid_term_test_scores
                        WHERE 
                            subject_id = '$subject_id' AND 
                            student_id = '$student_id'
                        ";
            $position = $this->db->query($query)->row()->position;
            //ordinalize the position (i.e. 1st, 2nd, 3rd, 4th, etc)
            $position = get_ordinal_number($position);
            return $position;
        } else {
            return NULL;
        }
    }


    public function get_student_position($session, $term, $class_id, $student_id) {
        $result_exists = $this->get_result_details($session, $term, $class_id, $student_id);
        if ($result_exists) {
            //rank the scores, create a column called position and save the ranks
            $query = "SELECT *, FIND_IN_SET( total_scored, 
                    ( SELECT GROUP_CONCAT( total_scored ORDER BY total_scored DESC ) 
                    FROM mid_term_student_results 
                    WHERE 
                        class_id = '$class_id' AND 
                        session = '$session' AND 
                        term = '$term'
                    )
                    ) AS position
                    FROM mid_term_student_results
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


    public function get_subject_score_data($subject_score, $item) {
        if ($subject_score > 0) {
            switch ($subject_score) {
                case in_array($subject_score, range(0, 9)):
                    $range = '0-9';
                    $data = [
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($subject_score, range(10, 19)):
                    $range = '10-19';
                    $data = [
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($subject_score, range(20, 29)):
                    $range = '20-29';
                    $data = [
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($subject_score, range(30, 39)):
                    $range = '30-39';
                    $data = [
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($subject_score, range(40, 49)):
                    $range = '40-49';
                    $data = [
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($subject_score, range(50, 59)):
                    $range = '50-59';
                    $data = [
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($subject_score, range(60, 69)):
                    $range = '60-69';
                    $data = [
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($subject_score, range(70, 79)):
                    $range = '70-79';
                    $data = [
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($subject_score, range(80, 89)):
                    $range = '80-89';
                    $data = [
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($subject_score, range(90, 100)):
                    $range = '90-100';
                    $data = [
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
            }
            return $data[$item];
        } else {
            return NULL;
        }
    }


    private function get_overall_score_data($session, $term, $class_id, $student_id, $item) {
        $percentage_score = $this->the_percentage_score($session, $term, $class_id, $student_id);
        if ($percentage_score != NULL) {
            //convert percentage score to integer
            $percentage_score = intval($percentage_score);
            switch ($percentage_score) {
                case in_array($percentage_score, range(0, 9)):
                    $range = '0-9';
                    $data = [
                        'evaluation' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->evaluation,
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($percentage_score, range(10, 19)):
                    $range = '10-19';
                    $data = [
                        'evaluation' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->evaluation,
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($percentage_score, range(20, 29)):
                    $range = '20-29';
                    $data = [
                        'evaluation' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->evaluation,
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($percentage_score, range(30, 39)):
                    $range = '30-39';
                    $data = [
                        'evaluation' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->evaluation,
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($percentage_score, range(40, 49)):
                    $range = '40-49';
                    $data = [
                        'evaluation' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->evaluation,
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($percentage_score, range(50, 59)):
                    $range = '50-59';
                    $data = [
                        'evaluation' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->evaluation,
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($percentage_score, range(60, 69)):
                    $range = '60-69';
                    $data = [
                        'evaluation' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->evaluation,
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($percentage_score, range(70, 79)):
                    $range = '70-79';
                    $data = [
                        'evaluation' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->evaluation,
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($percentage_score, range(80, 89)):
                    $range = '80-89';
                    $data = [
                        'evaluation' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->evaluation,
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
                case in_array($percentage_score, range(90, 100)):
                    $range = '90-100';
                    $data = [
                        'evaluation' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->evaluation,
                        'grade' => $this->common_model->get_mid_term_report_evaluation_by_range(school_id, $range)->grade,
                    ];
                break;
            }
            return $data[$item];
        } else {
            return NULL;
        }
    }


    public function get_student_passport($student_id) {
        $y = $this->common_model->get_student_details_by_id($student_id);
        $passport = $y->passport;
        return ($passport != NULL) ? base_url('assets/uploads/photos/students/'.$passport) : NULL;
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
        return $this->db->update('mid_term_student_results', $data); 
    } 


    public function reject_result($result_id) {
        $data = array(
            'status' => 'Rejected',
            'approved_by' => NULL,
            'date_approved' => NULL,
        );
        $this->db->where('id', $result_id); 
        return $this->db->update('mid_term_student_results', $data); 
    } 


    public function mark_result_pending($result_id) {
        $data = array(
            'status' => 'Pending',
            'approved_by' => NULL,
            'date_approved' => NULL,
        );
        $this->db->where('id', $result_id); 
        return $this->db->update('mid_term_student_results', $data); 
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
        return $this->db->update('mid_term_student_results', $data); 
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
        return $this->db->update('mid_term_student_results', $data); 
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
        return $this->db->update('mid_term_student_results', $data); 
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
        $template_exists = $this->settings_model->get_mid_term_report_template_details($template_id);
        $section_id = $this->common_model->get_class_details($class_id)->section_id;
        $attached_template_id = $this->common_model->get_section_details($section_id)->mt_template_id;
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
		$section_id = $this->common_model->get_class_details($class_id)->section_id;
        $mt_class_teacher_comment = $this->common_model->get_section_details($section_id)->mt_class_teacher_comment;
        $comment = $this->get_class_teacher_comment($session, $term, $class_id, $student_id);
        if ($mt_class_teacher_comment == 'true' && $comment == NULL) {
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
        $section_id = $this->common_model->get_class_details($class_id)->section_id;
        $mt_class_teacher_comment = $this->common_model->get_section_details($section_id)->mt_class_teacher_comment;
        $comment = $this->get_class_teacher_comment($session, $term, $class_id, $student_id);
        if ($mt_class_teacher_comment == 'true' && $comment == NULL) {
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