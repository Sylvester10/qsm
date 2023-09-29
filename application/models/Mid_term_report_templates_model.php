<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: Mid_term_report_templates_model
Role: Model
Description: Controls the DB processes of report templates
Controller: Mid_term_report_templates
Author: Nwankwo Ikemefuna
Date Created: 23rd January, 2019
*/


class Mid_term_report_templates_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->student_name = 'Abiodun Chukwuma Danjuma';
        $this->student_sex = 'Male';
        $this->this_year = date('Y');
        $this->admission_id = 'XYZ/'.$this->this_year.'/00047';
        $this->section = 'Primary';
        $this->student_class_id = 1;
        $this->class_population = 32;
        $this->student_class = 'Grade 4';
        $this->resumption_class = 'Grade 5';
        $this->x_session = $this->this_year .'/'. ($this->this_year + 1);
        $this->the_session = get_the_session($this->x_session);
        $this->x_term = '3rd';
        $this->today = x_date_full(date('Y-m-d'));
        $this->total_subjects = 13;
        $this->mt_total = 20;
    }


    public function report_data() {
        $data['student_name'] = $this->student_name;
        $data['admission_id'] = $this->admission_id;
        $data['student_sex'] = $this->student_sex;
        $data['session'] = $this->x_session; 
        $data['the_session'] = $this->the_session; 
        $data['term'] = $this->x_term; 
        $data['section'] =  $this->section;
        $data['class_id'] = $this->student_class_id;
        $data['class'] = $this->student_class;
        $data['class_population'] = $this->class_population;
        $data['mt_classwork'] = 5;
        $data['mt_homework'] = 5;
        $data['mt_tests'] = 10;
        $data['mt_class_teacher_comment'] = 'true';
        $data['mt_total'] = $this->mt_total;
        $data['total_possible_score'] = $this->total_subjects * 100;
        $data['overall_total_score'] = $this->get_overall_converted_total_score();
        $data['overall_percentage_score'] = $this->get_overall_percentage_score();
        $data['attendance_remark'] = 'Regular';
        $data['evaluation'] = 'Average'; 
        $data['class_teacher_comment'] = 'Good result but put more effort!';
        $data['class_teacher_signature'] = staff_signature; //default
        $data['head_teacher_signature'] = admin_signature; //default
        $data['class_teacher_name'] = 'Mr. Ogoma Johnson';
        $data['head_teacher_name'] = 'Mrs. Daniels Theresa';
        $data['head_teacher_designation'] = 'Head Teacher';
        $data['date_approved'] = $this->today;
        $data['show_previous_grade'] = 'true';
        $data['show_target_grade'] = 'true';
        $data['show_gp'] = 'true';
        $data['show_subject_position'] = 'true';
        $data['show_class_min'] = 'true';
        $data['show_class_max'] = 'true';
        $data['show_class_average'] = 'true';
        $data['show_performance_summary'] = 'true';
        $data['show_subject_teacher_name'] = 'true';
        $data['show_subject_teacher_comment'] = 'true';
        $data['show_class_teacher_name'] = 'true';
        $data['show_subject_teacher_name'] = 'true';
        $data['show_head_teacher_name'] = 'true';
        $data['show_student_passport'] = 'true';
        $data['student_passport'] = report_passport;
        $data['report_stamp'] = school_stamp; //default
        $data['sections'] = $this->school_sections();
        $data['subject_groups'] = $this->get_subject_groups();
        $data['test_scores'] = $this->get_test_scores();
        $data['school_info'] = $this->common_model->get_school_info(school_id);
        $data['term_info'] = $this->common_model->get_term_info(school_id);
        $data['report_templates'] = $this->settings_model->get_mid_term_report_templates();
        $data['report_evaluation'] = $this->get_report_evaluation();
        return $data;
    }


    public function get_sections() {
        $sections = array(
            'Nursery',
            'Primary',
            'Junior Secondary',
            'Senior Secondary',
        );
        return $sections;
    }


    private function school_sections() {
        $sections = $this->get_sections(); 
        $i = 0;
        $total_sections = count($sections);
        $sections_string = "";
        foreach ($sections as $section) {
            $i++;
            //list sections inline, separate with comma if not end of list
            $sections_string .= ($i == $total_sections) ?  $section : ($section. ', ');
        }
        return $sections_string;
    }


    public function get_subject_groups() {
        $subject_groups = array(
            //sg_id => subject group
            1   =>  'English Studies',
            2   =>  'Arithmetics',
            3   =>  'Basic Science and Technology',
            4   =>  'Religion and National Values',
            5   =>  'Culture and Creative Art',
        );
        return $subject_groups;
    }


    private function get_test_scores() {
        $test_scores = array(
            //array(subject[0], previous grade[1], target grade[2], classwork[3], homework[4], tests[5], subject position[6], subject teacher[7], subject group ID[8])
            array('English Language', 'B', 'A', 4, 5, 8, '7th', 'Mr. F. Jacobs', 1),
            array('Mathematics', 'C', 'B', 5, 3, 9, '6th', 'Miss T. Abaji', 2),
            array('Social Studies', 'B', 'A', 4, 4, 10, '3rd', 'Mr. O. Williams', 4),
            array('General Science', 'B', 'A', 3, 5, 8, '9th', 'Mr. G. Dayo', 3),
            array('Bible Knowledge', 'C', 'A', 5, 5, 10, '1st', 'Mrs. P. Audu', 4),
            array('Health Education', 'B', 'A', 4, 3, 7, '12th', 'Mr. J. Onuoha', 3),
            array('Civic Education', 'A', 'A<sup>+</sup>', 5, 2, 10, '12th', 'Miss C. West', 4),
            array('Verbal Reasoning', 'C', 'B', 3, 5, 6, '18th', 'Mr. S. Effiong', 1),
            array('Quantitative Reasoning', 'E', 'C', 3, 3, 8, '22nd', 'Mrs. R. Idoko', 2),
            array('Writing / Calligraphy', 'C', 'B', 4, 5, 10, '2nd', 'Mrs. K. Benson', 5),
            array('Arts & Colouring', 'C', 'A', 3, 4, 6, '16th', 'Mrs. F. Donald', 5),
            array('Phonics', 'C', 'B', 5, 4, 8, '10th', 'Mz. V. Kwensa', 1),
            array('Library Studies', 'A', 'A', 2, 4, 9, '17th', 'Miss A. Anderson', 1),
        );
        return $test_scores;
    }


    public function get_subject_score_data($percentage_score, $item) {
        $percentage_score = intval($percentage_score);
        switch ($percentage_score) {
            case in_array($percentage_score, range(0, 9)):
                $data = [
                    'grade' => 'G',
                    'evaluation' => 'Very poor',
                    'comment' => 'Very poor result',
                ];
            break;
            case in_array($percentage_score, range(10, 19)):
                $data = [
                    'grade' => 'G',
                    'evaluation' => 'Very poor',
                    'comment' => 'Very poor result',
                ];
            break;
            case in_array($percentage_score, range(20, 29)):
                $data = [
                    'grade' => 'G',
                    'evaluation' => 'Very poor',
                    'comment' => 'Very poor result',
                ];
            break;
            case in_array($percentage_score, range(30, 39)):
                $data = [
                    'grade' => 'F',
                    'evaluation' => 'Poor',
                    'comment' => 'Poor result',
                ];
            break;
            case in_array($percentage_score, range(40, 49)):
                $data = [
                    'grade' => 'E',
                    'evaluation' => 'Satisfactory',
                    'comment' => 'Satisfactory result. Put effort',
                ];
            break;
            case in_array($percentage_score, range(50, 59)):
                $data = [
                    'grade' => 'D',
                    'evaluation' => 'Average',
                    'comment' => 'Average result. You can do better',
                ];
            break;
            case in_array($percentage_score, range(60, 69)):
                $data = [
                    'grade' => 'C',
                    'evaluation' => 'Average',
                    'comment' => 'Good result but you can do better',
                ];
            break;
            case in_array($percentage_score, range(70, 79)):
                $data = [
                    'grade' => 'B',
                    'evaluation' => 'Good',
                    'comment' => 'Good result. There\'s still room for improvement',
                ];
            break;
            case in_array($percentage_score, range(80, 89)):
                $data = [
                    'grade' => 'A',
                    'evaluation' => 'Very good',
                    'comment' => 'Very good result',
                ];
            break;
            case in_array($percentage_score, range(90, 100)):
                $data = [
                    'grade' => 'A<sup>+</sup>',
                    'evaluation' => 'Excellent',
                    'comment' => 'Excellent result. Keep it up',
                ];
            break;
        }
        return $data[$item];
    }


    public function get_report_evaluation() { 
        //create multi-dimensional array...
        $evaluation = array (
            //array(range[0], letter grade[1], evaluation[2], performance summary[3])
            array('0-9',    'G',    'Very Poor',            0),
            array('10-19',  'G',    'Very Poor',            0),
            array('20-29',  'G',    'Very Poor',            0),
            array('30-39',  'F',    'Poor',                 0),
            array('40-49',  'E',    'Satisfactory',         0),
            array('50-59',  'D',    'Average',              0),
            array('60-69',  'C',    'Average',              1),
            array('70-79',  'B',    'Good',                 4),
            array('80-89',  'A',    'Very Good',            5),
            array('90-100', 'A<sup>+</sup>',  'Excellent',  3),
        );
        return $evaluation;
    }


    public function get_subject_total_score($classwork, $homework, $tests) {
        $total = $classwork + $homework + $tests;
        return $total;
    }


    public function get_subject_percentage_score($total_score) {
        //percentage score = (total scored / max total score) * 100 
        $percentage_score = ($total_score / $this->mt_total) * 100;
        return $percentage_score;
    }


    public function get_overall_converted_total_score() {
        $test_scores = $this->get_test_scores();
        $overall_total = 0;
        foreach ($test_scores as $t) {
            $classwork = $t[3]; 
            $homework = $t[4]; 
            $tests = $t[5]; 
            $subject_total = $this->get_subject_total_score($classwork, $homework, $tests);
            $percentage_score = $this->get_subject_percentage_score($subject_total, $this->mt_total);
            //accumulate total
            $overall_total += $percentage_score;
        }
        return $overall_total;
    }


    public function get_overall_percentage_score() {
        $overall_total = $this->get_overall_converted_total_score();
        $total_possible_score = $this->total_subjects * 100;
        //overall percentage score = (overall converted total / total possible score) * 100 
        $percentage_score = ($overall_total / $total_possible_score) * 100;
        return number_format($percentage_score, 1) . '%';
    }



}