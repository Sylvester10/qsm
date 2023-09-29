<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Students_report_admin
Role: Controller
Description: Controls access to all students pages and functions from the admin's end
Model: Students_report_model
Author: Nwankwo Ikemefuna
Date Created: 28th June, 2018
Date Modified: 29th January, 2019
*/


class Student_reports_admin extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->admin_restricted(); //allow only logged in users to access this class
        $this->load->model('students_report_model');
        $this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
        //get school id
        $this->school_id = $this->admin_details->school_id;
        require_once "application/core/constants/Session.php"; //require session constants
        $this->module_restricted_admin(school_id, mod_student_reports); //student reports module
        $this->activation_restricted_admin(school_id); 

        //get current user
        $this->c_user = 'admin';
        //user role
        $this->c_user_role = 'admin';

        //module-level scripts
        $this->shared_module_scripts = array('s_student_reports');
    }




    /* ========== Select Class ========== */
    public function select_class_reports() { //select class in modal in header: current term
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required');
        $session = current_session_slug;
        $term = current_term;
        $class_id = $this->input->post('class_id', TRUE);
        if ($this->form_validation->run())  {
            //redirect to the students report page of the selected class
            redirect(site_url($this->c_controller.'/reports/'.$session.'/'.$term.'/'.$class_id));
        } else {
            $this->session->set_flashdata('status_msg_error', 'Something went wrong!');
            redirect($this->agent->referrer());
        }
    }


    /* ========== Select Class: Archived ========== */
    public function select_archived_reports() { //select class in modal in header: archived
        $this->form_validation->set_rules('session', 'Session', 'trim|required');
        $this->form_validation->set_rules('term', 'Term', 'trim|required');
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required');
        $session = $this->input->post('session', TRUE);
        $term = $this->input->post('term', TRUE);
        $class_id = $this->input->post('class_id', TRUE);
        if ($this->form_validation->run())  {
            //redirect to the students report page of the selected class
            redirect(site_url($this->c_controller.'/reports/'.$session.'/'.$term.'/'.$class_id));
        } else {
            $this->session->set_flashdata('status_msg_error', 'Something went wrong!');
            redirect($this->agent->referrer());
        }
    }



    
    /* ========== Student Reports ========== */
    public function reports($session, $term, $class_id) {
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $class_details = $this->common_model->get_class_details($class_id);
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $this->admin_section_restricted($section_id);
        $page_title = 'End-of-Term Reports: ' . $class_details->class;
        $this->admin_header($page_title, $page_title); 
        $data = $this->students_report_model->report_broadsheet_data($session, $term, $class_id); 
        $data['controller'] = $this->c_controller;
        $this->load->view('shared/students_report/end_term/real/reports', $data);
        $this->admin_footer();
    }
    
    
    public function students_report_ajax($session, $term, $class_id) {
        $this->load->model('ajax/admin/students_report/end_term/students_report_model_ajax', 'current_model');
        $list = $this->current_model->get_records($session, $term, $class_id);
        $data = array();
        foreach ($list as $y) {
            $student_id = $y->student_id;
            $row = array(); 
            $row[] = checkbox_bulk_action($y->id);
            $row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
            $row[] = $this->common_model->get_student_details_by_id($student_id)->admission_id;
            $row[] = $this->common_model->get_student_fullname($student_id);
            $row[] = count($this->students_report_model->get_test_scores($session, $term, $class_id, $student_id)); //subjects registered
            $row[] = $y->total_scored; 
            $row[] = $this->students_report_model->get_overall_percentage_score($session, $term, $class_id, $student_id);
            $row[] = $this->students_report_model->get_student_position($session, $term, $class_id, $student_id);
            $row[] = $this->students_report_model->get_result_decision($session, $term, $class_id, $student_id);
            $row[] = $this->students_report_model->get_result_approval_status($session, $term, $class_id, $student_id);
            $row[] = $this->common_model->get_class_details($y->resumption_class_id)->class;
            $row[] = $this->students_report_model->get_class_teacher_comment($session, $term, $class_id, $student_id);
            $row[] = $this->students_report_model->get_head_teacher_comment($session, $term, $class_id, $student_id);
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->current_model->count_all_records($session, $term, $class_id),
            "recordsFiltered" => $this->current_model->count_filtered_records($session, $term, $class_id),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }




    /* ========== Broadsheet Report ========== */
    public function class_broadsheet($session, $term, $class_id) {
         //check result exists
        $this->students_report_model->check_class_result_exists($session, $term, $class_id, $this->c_controller);
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $class_details = $this->common_model->get_class_details($class_id);
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $this->admin_section_restricted($section_id);
        $page_title = 'End-of-Term Class Broadsheet Report: ' . $class_details->class;
        $this->admin_header($page_title, $page_title);  
        $data = $this->students_report_model->report_broadsheet_data($session, $term, $class_id);
        $data['controller'] = $this->c_controller;
        $this->load->view('shared/students_report/end_term/real/class_broadsheet', $data);
        $this->admin_footer();
    }



    /* ===== Print class broadsheet report ===== */
    public function print_class_broadsheet($session, $term, $class_id) {
        //check result exists
        $this->students_report_model->check_class_result_exists($session, $term, $class_id, $this->c_controller);
        //check class and student exist in this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $class_details = $this->common_model->get_class_details($class_id); 
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $this->admin_section_restricted($section_id);
        $page_title = 'Print End-of-Term Class Broadsheet Report: ' . $class_details->class;
        $this->print_header($page_title, $page_title);  
        //call report data
        $data = $this->students_report_model->report_broadsheet_data($session, $term, $class_id);
        $this->load->view('shared/students_report/end_term/real/print_class_broadsheet', $data);
        $this->print_footer();
    }




    /* ========== Produce Target Grade ========== */
    public function target_grade($session, $term, $class_id, $student_id) {
        //check class and student exist in this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'admin');
        $page_title = 'End-of-Term Target Grades: ' . $this->common_model->get_student_fullname($student_id);
        $this->admin_header($page_title, $page_title);
        $class_details = $this->common_model->get_class_details($class_id); 
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $this->admin_section_restricted($section_id);
        //call report data
        $data = $this->students_report_model->produce_report_data($session, $term, $class_id, $student_id);
        $data['subjects'] = $this->common_model->get_subjects_by_section(school_id, $section_id);
        $this->load->view('shared/students_report/end_term/real/target_grade', $data);
        $this->admin_footer();
    }


    public function produce_target_grade_ajax($session, $term, $class_id, $student_id) { 
        $this->form_validation->set_rules('previous_grade[]', 'Previous Grade', "trim|max_length[2]");
        $this->form_validation->set_rules('target_grade[]', 'Target Grade', "trim|max_length[2]");

        $subject_id = $this->input->post('subject_id', TRUE);
        $previous_grade = $this->input->post('previous_grade', TRUE);
        $target_grade = $this->input->post('target_grade', TRUE);

        if ($this->form_validation->run()) {

            $total_subjects = count($subject_id);
            for ($i = 0; $i < $total_subjects; $i++) {
                $d_subject_id = $subject_id[$i];
                $d_previous_grade = $previous_grade[$i];
                $d_target_grade = $target_grade[$i];

                $query = $this->students_report_model->check_target_grade_exists($session, $term, $class_id, $student_id, $d_subject_id);
                if ($query->num_rows() == 0) { //data does not exists, do insert
                    $this->students_report_model->insert_target_grade($session, $term, $class_id, $student_id, $d_subject_id, $d_previous_grade, $d_target_grade); 
                } else { //data exists, do update
                    $this->students_report_model->update_target_grade($session, $term, $class_id, $student_id, $d_subject_id, $d_previous_grade, $d_target_grade);
                }

            }
            echo 1;
        } else {
            echo validation_errors();
        }
    }


    public function delete_target_grade($tg_id) { 
        //check target grade exist for this school
        $this->check_school_data_exists(school_id, $tg_id, 'id', 'target_grades', 'admin');
        $this->students_report_model->delete_target_grade($tg_id);
        $this->session->set_flashdata('status_msg', 'Target grade deleted successfully.');
        redirect($this->agent->referrer());
    }




    /* ========== Produce Report ========== */
    public function produce_report($session, $term, $class_id, $student_id) {
        //check class and student exist in this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'admin');
        $page_title = 'Produce End-of-Term Report: ' . $this->common_model->get_student_fullname($student_id);
        $this->admin_header($page_title, $page_title);
        $class_details = $this->common_model->get_class_details($class_id); 
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $this->admin_section_restricted($section_id);
        //call report data
        $data = $this->students_report_model->produce_report_data($session, $term, $class_id, $student_id);
        $data['subjects'] = $this->common_model->get_subjects_by_section(school_id, $section_id);
        $data['template_id'] = $this->common_model->get_section_details($section_id)->template_id;
        $this->load->view('shared/students_report/end_term/real/produce_report', $data);
        $this->admin_footer();
    }


    public function produce_test_score_ajax($session, $term, $class_id, $student_id) { 
        $class_details = $this->common_model->get_class_details($class_id); 
        $section_id = $class_details->section_id;
        $ca_max_score = $this->common_model->get_section_details($section_id)->ca_max_score;
        $exam_max_score = $this->common_model->get_section_details($section_id)->exam_max_score;

        $this->form_validation->set_rules('previous_grade[]', 'Previous Grade', "trim|max_length[2]");
        $this->form_validation->set_rules('target_grade[]', 'Target Grade', "trim|max_length[2]");
        $this->form_validation->set_rules('ca_score[]', 'CA Score', "trim|is_natural|less_than_equal_to[{$ca_max_score}]",
            array(
                'less_than_equal_to' => "CA score cannot exceed {$ca_max_score}",
            )
        );
        $this->form_validation->set_rules('exam_score[]', 'Exam Score', "trim|is_natural|less_than_equal_to[{$exam_max_score}]",
            array(
                'less_than_equal_to' => "Exam score cannot exceed {$exam_max_score}",
            )
        );
        $this->form_validation->set_rules('subject_comment[]', 'Subject Comment', "trim|max_length[45]",
            array(
                'max_length' => "Subject comment cannot exceed 45 characters",
            )
        );

        $subject_id = $this->input->post('subject_id', TRUE);
        $previous_grade = $this->input->post('previous_grade', TRUE);
        $target_grade = $this->input->post('target_grade', TRUE);
        $ca_score = $this->input->post('ca_score', TRUE);
        $exam_score = $this->input->post('exam_score', TRUE);
        $subject_comment = $this->input->post('subject_comment', TRUE);

        if ($this->form_validation->run()) {

            $total_subjects = count($subject_id);
            for ($i = 0; $i < $total_subjects; $i++) {
                $d_subject_id = $subject_id[$i];
                $d_previous_grade = $previous_grade[$i];
                $d_target_grade = $target_grade[$i];
                $d_ca_score = $ca_score[$i];
                $d_exam_score = $exam_score[$i];
                $d_subject_comment = $subject_comment[$i];
                $subject_teacher_id = NULL; //not subject teacher

                //Target Grade
                $target_grade_query = $this->students_report_model->check_target_grade_exists($session, $term, $class_id, $student_id, $d_subject_id);
                if ($target_grade_query->num_rows() == 0) { //data does not exists, do insert
                    $this->students_report_model->insert_target_grade($session, $term, $class_id, $student_id, $d_subject_id, $d_previous_grade, $d_target_grade); 
                } else { //data exists, do update
                    $this->students_report_model->update_target_grade($session, $term, $class_id, $student_id, $d_subject_id, $d_previous_grade, $d_target_grade);
                }

                //Test Scores
                $test_score_query = $this->students_report_model->check_test_score_exists($session, $term, $class_id, $student_id, $d_subject_id);
                if ($test_score_query->num_rows() == 0) { //data does not exists, do insert
                    $this->students_report_model->insert_test_score($session, $term, $class_id, $student_id, $d_subject_id, $d_ca_score, $d_exam_score, $d_subject_comment, $subject_teacher_id); 
                } else { //data exists, do update
                    $this->students_report_model->update_test_score($session, $term, $class_id, $student_id, $d_subject_id, $d_ca_score, $d_exam_score, $d_subject_comment, $subject_teacher_id);
                }

            }
            echo 1;
        } else {
            echo validation_errors();
        }
    }


    public function delete_test_score($test_score_id) { 
        //check test score exist in this school
        $this->check_school_data_exists(school_id, $test_score_id, 'id', 'test_scores', 'admin');
        $this->students_report_model->delete_test_score($test_score_id);
        $this->session->set_flashdata('status_msg', 'Test score deleted successfully.');
        redirect($this->agent->referrer());
    }





    /* ========== Aptitude Scores ========== */
    public function produce_aptitude_score_ajax($session, $term, $class_id, $student_id) { 
        $this->form_validation->set_rules('aptitude_id[]', 'Aptitude', 'trim');
        $aptitude_id = $this->input->post('aptitude_id', TRUE);
        $score = $this->input->post('score', TRUE);
        if ($this->form_validation->run()) {
            $total_aptitudes = count($aptitude_id);
            for ($i = 0; $i < $total_aptitudes; $i++) {
                //check if an item is selected, ignore otherwise
                if ( isset($score[$i]) ) {
                    $d_aptitude_id = $aptitude_id[$i];
                    $d_score = $score[$i];
                    $query = $this->students_report_model->check_aptitude_score_exists($session, $term, $class_id, $student_id, $d_aptitude_id);
                    if ($query->num_rows() == 0) { //data does not exists, do insert
                        $this->students_report_model->insert_aptitude_score($session, $term, $class_id, $student_id, $d_aptitude_id, $d_score);
                    } else { //data already exists, do update
                        $this->students_report_model->update_aptitude_score($session, $term, $class_id, $student_id, $d_aptitude_id, $d_score); 
                    } 
                }
            }
            echo 1;
        } else {
            echo validation_errors();
        }
    }


    public function delete_aptitude_score($aptitude_score_id) { 
        //check test score exist in this school
        $this->check_school_data_exists(school_id, $aptitude_score_id, 'id', 'aptitude_scores', 'admin');
        $this->students_report_model->delete_aptitude_score($aptitude_score_id);
        $this->session->set_flashdata('status_msg', 'Aptitude score deleted successfully.');
        redirect($this->agent->referrer());
    }



    //Attendance scores
    public function produce_att_scores_ajax($session, $term, $class_id, $student_id) {
        //attendance
        $this->form_validation->set_rules('customize_att', 'Customize Attendance', 'trim');
        $customize_att = $this->input->post('customize_att', TRUE);
        if (isset($customize_att)) {
            $this->form_validation->set_rules('att_present', 'Days Present', 'trim|required|is_natural');
            $this->form_validation->set_rules('att_absent[]', 'Days Absent', 'trim|required|is_natural');
            $this->form_validation->set_rules('att_tardy[]', 'Times Tardy', 'trim|required|is_natural');
        } else {
            $this->form_validation->set_rules('att_present', 'Days Present', 'trim');
            $this->form_validation->set_rules('att_absent[]', 'Days Absent', 'trim');
            $this->form_validation->set_rules('att_tardy[]', 'Times Tardy', 'trim');
        }
        if ($this->form_validation->run()) {
            $this->students_report_model->update_att_scores($session, $term, $class_id, $student_id); 
            echo 1;
        } else {
            echo validation_errors();
        }
    }



    /* ========== Class Teacher's Comment ========== */
    public function produce_class_teacher_comment_ajax($session, $term, $class_id, $student_id) { 
        $p_comment = $this->input->post('p_comment', TRUE);
        if ($p_comment) { //personalize checkbox is checked, unrequire selectfield and require personalized comment textarea
            $this->form_validation->set_rules('comment', 'Comment', 'trim');
            $this->form_validation->set_rules('personalized_comment', 'Personalized Comment', 'trim|required');
        } else { //personalize checkbox is not checked, require selectfield and unrequire personalized comment textarea
            $this->form_validation->set_rules('comment', 'Comment', 'trim|required');
            $this->form_validation->set_rules('personalized_comment', 'Personalized Comment', 'trim');
        }
        if ($this->form_validation->run()) {
            $query = $this->students_report_model->check_class_teacher_comment_exists($session, $term, $class_id, $student_id);
            if ($query->num_rows() == 0) { //data does not exists, do insert
                $this->students_report_model->insert_class_teacher_comment($session, $term, $class_id, $student_id); 
            } else { //data already exists, do update
                $this->students_report_model->update_class_teacher_comment($session, $term, $class_id, $student_id); 
            }
            echo 1;
        } else {
            echo validation_errors();
        }
    }




    /* ========== Head Teacher's Comment ========== */
    public function produce_head_teacher_comment_ajax($session, $term, $class_id, $student_id) { 
        $this->form_validation->set_rules('p_comment', 'Personalize Comment', 'trim|required');
        $this->form_validation->set_rules('personalized_comment', 'Personalized Comment', 'trim|required');
        if ($this->form_validation->run()) {
            $query = $this->students_report_model->check_head_teacher_comment_exists($session, $term, $class_id, $student_id);
            if ($query->num_rows() == 0) { //data does not exists, do insert
                $this->students_report_model->insert_head_teacher_comment($session, $term, $class_id, $student_id); 
            } else { //data already exists, do update
                $this->students_report_model->update_head_teacher_comment($session, $term, $class_id, $student_id); 
            }
            echo 1;
        } else {
            echo validation_errors();
        }
    }





    /* ========== Head Teacher Actions========== */
    public function approve_result($result_id) { 
        //check result exists in this school
        $this->check_school_data_exists(school_id, $result_id, 'id', 'student_results', 'admin');
        $result_details = $this->students_report_model->get_result_details_by_id($result_id);
        $class_details = $this->common_model->get_class_details($result_details->class_id); 
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $this->admin_section_restricted($section_id);
        //ensure head teacher's signature has been updated
        $signature = $this->admin_details->signature;
        $this->students_report_model->check_signature($signature, 'admin');
        $approved_by = $this->admin_details->id;
        $this->students_report_model->approve_result($result_id, $approved_by);
        $this->session->set_flashdata('status_msg', 'Result approved successfully.');
        redirect($this->agent->referrer());
    }


    public function reject_result($result_id) { 
        //check result exists in this school
        $this->check_school_data_exists(school_id, $result_id, 'id', 'student_results', 'admin');
        $result_details = $this->students_report_model->get_result_details_by_id($result_id);
        $class_details = $this->common_model->get_class_details($result_details->class_id); 
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $this->admin_section_restricted($section_id);
        //ensure head teacher's signature has been updated
        $signature = $this->admin_details->signature;
        $this->students_report_model->check_signature($signature, 'admin');
        $this->students_report_model->reject_result($result_id);
        $this->session->set_flashdata('status_msg', 'Result rejected successfully.');
        redirect($this->agent->referrer());
    }


    public function mark_result_pending($result_id) { 
        //check result exists in this school
        $this->check_school_data_exists(school_id, $result_id, 'id', 'student_results', 'admin');
        $result_details = $this->students_report_model->get_result_details_by_id($result_id);
        $class_details = $this->common_model->get_class_details($result_details->class_id); 
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $this->admin_section_restricted($section_id);
        //ensure head teacher's signature has been updated
        $signature = $this->admin_details->signature;
        $this->students_report_model->check_signature($signature, 'admin');
        $this->students_report_model->mark_result_pending($result_id);
        $this->session->set_flashdata('status_msg', 'Result marked pending successfully.');
        redirect($this->agent->referrer());
    }


    public function approve_all_results($session, $term, $class_id) { 
        //check class exists in this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $class_details = $this->common_model->get_class_details($class_id); 
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $this->admin_section_restricted($section_id);
        //ensure head teacher's signature has been updated
        $signature = $this->admin_details->signature;
        $this->students_report_model->check_signature($signature, 'admin');
        $approved_by = $this->admin_details->id;
        $this->students_report_model->approve_all_results($session, $term, $class_id, $approved_by);
        $this->session->set_flashdata('status_msg', 'All results approved successfully.');
        redirect($this->agent->referrer());
    }


    public function reject_all_results($session, $term, $class_id) { 
        //check class exists in this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $class_details = $this->common_model->get_class_details($class_id); 
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $this->admin_section_restricted($section_id);
        //ensure head teacher's signature has been updated
        $signature = $this->admin_details->signature;
        $this->students_report_model->check_signature($signature, 'admin');
        $this->students_report_model->reject_all_results($session, $term, $class_id);
        $this->session->set_flashdata('status_msg', 'All results rejected successfully.');
        redirect($this->agent->referrer());
    }


    public function mark_all_results_pending($session, $term, $class_id) { 
        //check class exists in this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $class_details = $this->common_model->get_class_details($class_id); 
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $this->admin_section_restricted($section_id);
        //ensure head teacher's signature has been updated
        $signature = $this->admin_details->signature;
        $this->students_report_model->check_signature($signature, 'admin');
        $this->students_report_model->mark_all_results_pending($session, $term, $class_id);
        $this->session->set_flashdata('status_msg', 'All results marked pending successfully.');
        redirect($this->agent->referrer());
    }


    public function set_resumption_class($result_id) { 
        //check result exists in this school
        $this->check_school_data_exists(school_id, $result_id, 'id', 'student_results', 'admin');
        $result_details = $this->students_report_model->get_result_details_by_id($result_id);
        $student_id = $result_details->student_id;
        $student_name = $this->common_model->get_student_fullname($student_id);
        $this->form_validation->set_rules('resumption_class_id', 'Resumption Class', 'trim|required');
        $resumption_class_id = $this->input->post('resumption_class_id', TRUE);
        $resumption_class = $this->common_model->get_class_details($resumption_class_id)->class;
        if ($this->form_validation->run()) {
            $this->students_report_model->set_resumption_class($result_id);
            $this->session->set_flashdata('status_msg', "Resumption class successfully set to {$resumption_class} for {$student_name}");
        } else {
            $this->session->set_flashdata('status_msg_error', "Error setting resumption class.");
        }
        redirect($this->agent->referrer());
    }
    
    
    public function bulk_actions_reports() { 
        $this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
        $selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
        if ($this->form_validation->run()) {
            if ($selected_rows > 0) {
                $approved_by = $this->admin_details->id;
                $this->students_report_model->bulk_actions_reports($approved_by); 
            } else {
                $this->session->set_flashdata('status_msg_error', 'No item selected.');
            }
        } else {
            $this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
        }
        redirect($this->agent->referrer());
    }






    /* ========== Report Card ========== */
    public function report_card($template_id, $session, $term, $class_id, $student_id) {
        //validate report template
        $this->students_report_model->check_report_template($template_id, $session, $term, $class_id, $student_id, 'admin');
        
        //check class and student exist in this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'admin');
        $class_details = $this->common_model->get_class_details($class_id); 
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $this->admin_section_restricted($section_id);
        //check result exists
        $this->students_report_model->check_result_exists($session, $term, $class_id, $student_id, $this->c_controller);
        //check all has been added
        $this->students_report_model->check_report_card_ok_admin($session, $term, $class_id, $student_id);

        $page_title = 'End-of-Term Report Card: ' . $this->common_model->get_student_fullname($student_id);
        $this->admin_header($page_title, $page_title);  
        //call report data
        $data = $this->students_report_model->report_card_data($session, $term, $class_id, $student_id);

        $template_folder = 'template_'.$template_id;
        $data['template_id'] = $template_id;
        $data['controller'] = $this->c_controller;
        $this->load->view('shared/students_report/end_term/real/'.$template_folder.'/report_card', $data);
        $this->admin_footer();
    }


    /* ===== Print Report Card ===== */
    public function print_report($template_id, $session, $term, $class_id, $student_id) {
        //validate report template
        $this->students_report_model->check_report_template($template_id, $session, $term, $class_id, $student_id, 'admin');

        //check class and student exist in this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'admin');
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'admin');
        $class_details = $this->common_model->get_class_details($class_id); 
        //ensure head teacher is assigned to this section
        $section_id = $class_details->section_id;
        $this->admin_section_restricted($section_id);
        //check result exists
        $this->students_report_model->check_result_exists($session, $term, $class_id, $student_id, $this->c_controller);
        //check all has been added
        $this->students_report_model->check_report_card_ok_admin($session, $term, $class_id, $student_id);
        
        $page_title = 'Print End-of-Term Report Card: ' . $this->common_model->get_student_fullname($student_id);
        $this->print_header($page_title, $page_title);  
        //call report data
        $data = $this->students_report_model->report_card_data($session, $term, $class_id, $student_id);
        
        $template_folder = 'template_'.$template_id;
        $data['template_id'] = $template_id;
        $data['controller'] = $this->c_controller;
        $this->load->view('shared/students_report/end_term/real/'.$template_folder.'/print_report', $data);
        $this->print_footer();
    }



    
}