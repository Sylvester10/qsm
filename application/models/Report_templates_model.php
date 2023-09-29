<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: Report_templates_model
Role: Model
Description: Controls the DB processes of report templates
Controller: Report_templates
Author: Nwankwo Ikemefuna
Date Created: 1st November, 2018
*/


class Report_templates_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('fees_model');
		$this->student_name = 'Abiodun Chukwuma Danjuma';
		$this->student_sex = 'Male';
		$this->this_year = date('Y');
		$this->admission_id = 'XYZ/'.$this->this_year.'/00047';
		$this->section = 'Primary';
		$this->student_class_id = 1;
		$this->class_population = 32;
		$this->student_class = 'Grade 4';
		$this->resumption_class = 'Grade 5';
		$this->resumption_class_id = 2;
		$this->x_session = $this->this_year .'/'. ($this->this_year + 1);
		$this->the_session = get_the_session($this->x_session);
		$this->x_term = '3rd';
		$this->today = x_date_full(date('Y-m-d'));
		$this->total_subjects = 13;
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
		$data['resumption_class_id'] = $this->resumption_class_id;
		$data['resumption_class'] = $this->resumption_class;
		$data['class_population'] = $this->class_population;
		$data['total_possible_score'] = $this->total_subjects * 100;
		$data['overall_total_score'] = $this->get_overall_total_score();
        $data['overall_percentage_score'] = $this->get_overall_percentage_score();
		$data['position'] = '17th';
		$data['class_percentage_average'] = '73.9%'; 
		$data['gpa'] = '3.0'; 
		$data['overall_grade'] = 'C'; 
		$data['evaluation'] = 'Average'; 
		$data['att_present'] = 69;
		$data['att_total'] = 73;
		$data['attendance_remark'] = 'Regular';
		$data['class_teacher_comment'] = 'Good result but put more effort!';
		$data['head_teacher_comment'] = 'Average performance. Put more effort.';
		$data['class_teacher_signature'] = staff_signature; //default
		$data['head_teacher_signature'] = admin_signature; //default
		$data['class_teacher_name'] = 'Mr. Ogoma Johnson';
		$data['head_teacher_name'] = 'Mrs. Daniels Theresa';
		$data['head_teacher_designation'] = 'Head Teacher';
		$data['date_approved'] = $this->today;
		$data['ca_max_score'] = 30;
		$data['exam_max_score'] = 70;
		$data['pass_mark'] = 40;
		$data['ranking'] = 'true';
		$data['enable_aptitudes'] = 'true';
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
		$data['next_term_fees'] = (s_country == 'Nigeria') ? '₦52,650.00' : '$1,075.00';
		$data['report_stamp'] = school_stamp; //default
		$data['sections'] = $this->school_sections();
		$data['subject_groups'] = $this->get_subject_groups();
		$data['test_scores'] = $this->get_test_scores();
		$data['aptitude_keys'] = $this->get_aptitude_keys();
		$data['aptitude_scores'] = $this->get_aptitude_scores();
		$data['affective_aptitude_scores'] = $this->get_affective_aptitude_scores();
		$data['psychomotor_aptitude_scores'] = $this->get_psychomotor_aptitude_scores();
		$data['school_info'] = $this->common_model->get_school_info(school_id);
		$data['term_info'] = $this->common_model->get_term_info(school_id);
		$data['report_templates'] = $this->settings_model->get_report_templates();
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
			1 	=> 	'English Studies',
			2 	=> 	'Arithmetics',
			3 	=> 	'Basic Science and Technology',
			4 	=> 	'Religion and National Values',
			5 	=> 	'Culture and Creative Art',
		);
		return $subject_groups;
	}


	private function get_test_scores() {
		$test_scores = array(
			//array(subject[0], previous grade[1], target grade[2], ca[3], exam[4], subject position[5], class min[6], class max[7], class average[8], subject teacher[9], subject group ID[10])
			array('English Language', 'B', 'A', 19, 52, '11th', 55, 91, 80, 'Mr. F. Jacobs', 1),
			array('Mathematics', 'C', 'B', 20, 39, '26th', 43, 87, 67, 'Miss T. Abaji', 2),
			array('Social Studies', 'B', 'A', 28, 61, '3rd', 50, 91, 76, 'Mr. O. Williams', 4),
			array('General Science', 'B', 'A', 16, 47, '17th', 45, 80, 65, 'Mr. G. Dayo', 3),
			array('Bible Knowledge', 'C', 'A', 25, 50, '8th', 39, 97, 76, 'Mrs. P. Audu', 4),
			array('Health Education', 'B', 'A', 18, 44, '15th', 51, 94, 71, 'Mr. J. Onuoha', 3),
			array('Civic Education', 'A', 'A<sup>+</sup>', 30, 35, '12th', 45, 99, 82, 'Miss C. West', 4),
			array('Verbal Reasoning', 'C', 'B', 27, 30, '25th', 52, 95, 85, 'Mr. S. Effiong', 1),
			array('Quantitative Reasoning', 'E', 'C', 12, 38, '22nd', 41, 86, 70, 'Mrs. R. Idoko', 2),
			array('Writing / Calligraphy', 'C', 'B', 24, 48, '7th', 50, 92, 83, 'Mrs. K. Benson', 5),
			array('Arts & Colouring', 'C', 'A', 22, 20, '29th', 40, 81, 68, 'Mrs. F. Donald', 5),
			array('Phonics', 'C', 'B', 16, 31, '27th', 47, 91, 73, 'Mz. V. Kwensa', 1),
			array('Library Studies', 'A', 'A', 26, 45, '13th', 56, 90, 65, 'Miss A. Anderson', 1),
		);
		return $test_scores;
	}


	public function get_subject_total_score($ca_score, $exam_score) {
        $total_score = $ca_score + $exam_score;
        return $total_score;
    }


    public function get_overall_total_score() {
        $test_scores = $this->get_test_scores();
        $overall_total = 0;
        foreach ($test_scores as $t) {
            $ca_score = $t[3]; 
            $exam_score = $t[4]; 
            $subject_total = $this->get_subject_total_score($ca_score, $exam_score);
            //accumulate total
            $overall_total += $subject_total;
        }
        return $overall_total;
    }


    public function get_overall_percentage_score() {
        $overall_total = $this->get_overall_total_score();
        $total_possible_score = $this->total_subjects * 100;
        //overall percentage score = (overall total / total possible score) * 100 
        $percentage_score = ($overall_total / $total_possible_score) * 100;
        return $percentage_score;
    }


    public function get_subject_score_data($total_score, $item) {
        $total_score = intval($total_score);
        switch ($total_score) {
            case in_array($total_score, range(0, 9)):
                $data = [
                    'grade' => 'G',
                    'gp' => 0,
                    'evaluation' => 'Very poor',
                    'comment' => 'Very poor result',
                ];
            break;
            case in_array($total_score, range(10, 19)):
                $data = [
                    'grade' => 'G',
                    'gp' => 0,
                    'evaluation' => 'Very poor',
                    'comment' => 'Very poor result',
                ];
            break;
            case in_array($total_score, range(20, 29)):
                $data = [
                    'grade' => 'G',
                    'gp' => 0,
                    'evaluation' => 'Very poor',
                    'comment' => 'Very poor result',
                ];
            break;
            case in_array($total_score, range(30, 39)):
                $data = [
                    'grade' => 'F',
                    'gp' => 0,
                    'evaluation' => 'Poor',
                    'comment' => 'Poor result',
                ];
            break;
            case in_array($total_score, range(40, 49)):
                $data = [
                    'grade' => 'E',
                    'gp' => 1,
                    'evaluation' => 'Satisfactory',
                    'comment' => 'Satisfactory result. Put effort',
                ];
            break;
            case in_array($total_score, range(50, 59)):
                $data = [
                    'grade' => 'D',
                    'gp' => 2,
                    'evaluation' => 'Average',
                    'comment' => 'Average result. You can do better',
                ];
            break;
            case in_array($total_score, range(60, 69)):
                $data = [
                    'grade' => 'C',
                    'gp' => 3,
                    'evaluation' => 'Average',
                    'comment' => 'Good result but you can do better',
                ];
            break;
            case in_array($total_score, range(70, 79)):
                $data = [
                    'grade' => 'B',
                    'gp' => 4,
                    'evaluation' => 'Good',
                    'comment' => 'Good result. There\'s still room for improvement',
                ];
            break;
            case in_array($total_score, range(80, 89)):
                $data = [
                    'grade' => 'A',
                    'gp' => 5,
                    'evaluation' => 'Very good',
                    'comment' => 'Very good result',
                ];
            break;
            case in_array($total_score, range(90, 100)):
                $data = [
                    'grade' => 'A<sup>+</sup>',
                    'gp' => 5,
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
			//array(range[0], letter grade[1], GP[2], evaluation[3], performance summary[4])
			array('0-9', 	'G', 	0, 	'Very Poor',			0),
			array('10-19', 	'G', 	0, 	'Very Poor',			0),
			array('20-29', 	'G', 	0, 	'Very Poor',			0),
			array('30-39', 	'F', 	0, 	'Poor',					0),
			array('40-49', 	'E', 	1, 	'Satisfactory',			2),
			array('50-59', 	'D', 	2, 	'Average',				3),
			array('60-69', 	'C', 	3, 	'Average',				3),
			array('70-79', 	'B', 	4, 	'Good',					4),
			array('80-89', 	'A', 	5, 	'Very Good',			1),
			array('90-100',	'A<sup>+</sup>', 5.0, 'Excellent',	0),
		);
		return $evaluation;
	}


	public function get_aptitude_keys() {
		$aptitude_keys = array(
			1 => 'Very Poor',
			2 => 'Poor',
			3 => 'Average',
			4 => 'Good',
			5 => 'Excellent',
		);
		return $aptitude_keys;
	}


	private function get_aptitude_scores() { 
		$aptitude_scores = array (
			//array(aptitude[0], score[1])
			array('Attentiveness', 5), 
			array('Complete Work on Time', 4), 
			array('Completion of Homework', 3), 
			array('Curious about Class Event', 2), 
			array('Honesty', 1),
			array('Manipulative Skill', 2),
			array('Neatness', 3),
			array('Obedience', 4),
			array('Punctuality', 5),
			array('Relationship with Others', 4),
			array('Self-control', 3),
			array('Socialize Well with Others', 2),
			array('Work Independently', 1),
		);
		return $aptitude_scores;
	}


	private function get_affective_aptitude_scores() { 
		$aptitude_scores = array (
			//array(aptitude[0], score[1])
			array('Attentiveness', 5), 
			array('Complete Work on Time', 4), 
			array('Curiousity', 3), 
			array('Honesty', 2),
			array('Neatness', 1),
			array('Obedience', 2),
			array('Punctuality', 3),
			array('Relationship with Others', 4),
			array('Self-control', 5),
		);
		return $aptitude_scores;
	}


	private function get_psychomotor_aptitude_scores() { 
		$aptitude_scores = array (
			//array(aptitude[0], score[1])
			array('Handwriting', 1), 
			array('Manipulative Skill', 2), 
			array('Painting/Drawing', 3), 
			array('Games/Sports', 4), 
			array('Crafts', 5),
			array('Handling of Tools', 4),
			array('Oral Communication', 3), 
		);
		return $aptitude_scores;
	}

}