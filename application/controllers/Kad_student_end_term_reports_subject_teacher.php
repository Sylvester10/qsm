<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Kad_student_end_term_reports_subject_teacher
Role: Controller
Description: Controls access to students' End-Term reports from the staff's end
Bespoke to: KAD Academy
Model: Kad_students_end_term_report_model
Author: Nwankwo Ikemefuna
Date Created: 19th February, 2019
Date Modified: 19th February, 2019
*/


class Kad_student_end_term_reports_subject_teacher extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->staff_restricted(); //allow only logged in users to access this class
        $this->staff_role_restricted('Subject Teacher'); //only staff with this role can access this module
        $this->check_staff_is_subject_teacher(); //allow only class teachers who are currently assigned to a class to access this module
        $this->load->model('subject_teacher_model');
        $this->load->model('kad_students_end_term_report_model');
        $this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
        $this->class_details = $this->common_model->get_class_details_by_teacher($this->staff_details->id);
        //get school id
        $this->school_id = $this->staff_details->school_id;
        require_once "application/core/constants/Session.php"; //require session constants
        $this->module_restricted_staff(school_id, mod_student_reports); //student reports module
        $this->activation_restricted_staff(school_id); 
        
        //ensure that bespoke module is not accessed by other schools
        $this->bespoke_restricted_module(school_id, kad_school_id, 'staff'); 

        //get current user
        $this->c_user = 'staff';
        //user role
        $this->c_user_role = 'subject_teacher';
        //school template folder
        $this->bespoke_template_folder = 'bespoke/kad_academy';

        //module-level scripts
        $this->shared_module_scripts = array('s_kad_student_reports');
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
        if ($this->form_validation->run())  {
            $session = $this->input->post('session', TRUE);
            $term = $this->input->post('term', TRUE);
            $class_id = $this->input->post('class_id', TRUE);
            //get category
            $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
            $category = $this->kad_students_end_term_report_model->get_template_details($template_id)->category;
            //redirect to the students report page of the selected class
            redirect(site_url($this->c_controller.'/reports/'.$session.'/'.$term.'/'.$class_id));
        } else {
            $this->session->set_flashdata('status_msg_error', 'Something went wrong!');
            redirect($this->agent->referrer());
        }
    }



    
    /* ========== Student Reports ========== */
    public function reports($session, $term, $class_id) {
        //check session and term is same as current session and current term
        $this->subject_teacher_model->check_session_and_term($session, $term);
        //check this class is assigned to this teacher
        $this->subject_teacher_model->check_class_assigned($class_id);

        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');

        $class_details = $this->common_model->get_class_details($class_id);
        $page_title = 'End-Term Reports: ' . $class_details->class;
        $this->staff_header($page_title, $page_title); 
        $template_id = $this->common_model->get_class_details($class_id)->bespoke_template_id;
        $category = $this->kad_students_end_term_report_model->get_template_details($template_id)->category;
        $data = $this->kad_students_end_term_report_model->report_data($session, $term, $class_id); 
        $data['controller'] = $this->c_controller;
        $this->load->view('shared/students_report/end_term/real/'.$this->bespoke_template_folder.'/shared/reports', $data);
        $this->staff_footer();
    }
    
    
    public function students_report_ajax($session, $term, $class_id) {
        $this->load->model('ajax/staff/students_report/end_term/'.$this->bespoke_template_folder.'/students_report_model_ajax', 'current_model');
        $list = $this->current_model->get_records($session, $term, $class_id);
        $data = array();
        foreach ($list as $y) {
            $student_id = $y->student_id;
            //resumption class
            $resumption_class_id = $y->resumption_class_id;    
            $resumption_class = $this->common_model->get_class_details($resumption_class_id)->class;
            $row = array(); 
            $row[] = checkbox_bulk_action($y->id);
            $row[] = $this->current_model->options($y->id) . $this->current_model->modals($y->id);
            $row[] = $this->common_model->get_student_details_by_id($student_id)->admission_id;
            $row[] = $this->common_model->get_student_fullname($student_id);
            $row[] = count($this->kad_students_end_term_report_model->get_test_scores($session, $term, $class_id, $student_id)); //subjects registered
            $row[] = $y->total_scored; 
            $row[] = $this->kad_students_end_term_report_model->get_overall_percentage_score($session, $term, $class_id, $student_id);
            $row[] = $this->kad_students_end_term_report_model->get_student_position($session, $term, $class_id, $student_id);
            $row[] = $this->kad_students_end_term_report_model->get_result_decision($session, $term, $class_id, $student_id);
            $row[] = $this->kad_students_end_term_report_model->get_result_approval_status($session, $term, $class_id, $student_id);
            $row[] = $resumption_class;
            $row[] = $this->kad_students_end_term_report_model->get_attendance_info($session, $term, $class_id, $student_id);
            $row[] = $this->kad_students_end_term_report_model->get_class_teacher_comment($session, $term, $class_id, $student_id);
            $row[] = $this->kad_students_end_term_report_model->get_head_teacher_comment($session, $term, $class_id, $student_id);
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



    /* ========== Produce Report ========== */
    public function produce_report($session, $term, $class_id, $student_id) {
        //check session and term is same as current session and current term
        $this->subject_teacher_model->check_session_and_term($session, $term);
        //check this class is assigned to this teacher
        $this->subject_teacher_model->check_class_assigned($class_id);
        //check student is in this teacher's class
        $this->subject_teacher_model->check_student_class($class_id, $student_id);

        //check class and student exist in this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'staff');

        $page_title = 'Produce End-Term Report: ' . $this->common_model->get_student_fullname($student_id);
        $this->staff_header($page_title, $page_title);
        $class_details = $this->common_model->get_class_details($class_id); 
        $section_id = $class_details->section_id;
        //template details
        $template_id = $class_details->bespoke_template_id;
        $category = $this->kad_students_end_term_report_model->get_template_details($template_id)->category;
        //call report data
        $data = $this->kad_students_end_term_report_model->produce_report_data($session, $term, $class_id, $student_id);
        $data['subjects'] = $this->common_model->get_subject_teacher_subjects_array_by_section($this->staff_details->id, $section_id);
        $data['category'] = $category;
        $data['template_id'] = $template_id;
        $this->load->view('shared/students_report/end_term/real/'.$this->bespoke_template_folder.'/'.$category.'/produce_report', $data);
        $this->staff_footer();
    }




    //Category = Pre-KG, KG, Senior KG and Year 1, Year 2
    public function produce_test_score_ajax($session, $term, $class_id, $student_id) {
        //check if result has been approved by head teacher. if true, disallow further production action (from class/subject teacher only)
        $result_details = $this->kad_students_end_term_report_model->get_result_details($session, $term, $class_id, $student_id);
        $status = $result_details->status;
        //check if result has been approved by head teacher
        if ($status != 'Approved') { //result pending approval or rejected

            //template details
            $class_details = $this->common_model->get_class_details($class_id); 
            $template_id = $class_details->bespoke_template_id;
            $category = $this->kad_students_end_term_report_model->get_template_details($template_id)->category; 
            $this->form_validation->set_rules('progress[]', 'Progress', "trim");

            $subject_id = $this->input->post('subject_id', TRUE);
            $progress = $this->input->post('progress', TRUE);

            if ($this->form_validation->run()) {

                $total_subjects = count($subject_id);
                for ($i = 0; $i < $total_subjects; $i++) {
                    //check if an item is selected, ignore otherwise
                    if ( isset($progress[$i]) ) {
                        $d_subject_id = $subject_id[$i];
                        $d_progress = $progress[$i];
                        //Test Scores
                        $test_score_query = $this->kad_students_end_term_report_model->check_test_score_exists($session, $term, $class_id, $student_id, $d_subject_id);
                        if ($test_score_query->num_rows() == 0) { //data does not exists, do insert
                            $this->kad_students_end_term_report_model->insert_test_score($session, $term, $class_id, $student_id, $d_subject_id, $d_progress, $category); 
                        } else { //data exists, do update
                            $this->kad_students_end_term_report_model->update_test_score($session, $term, $class_id, $student_id, $d_subject_id, $d_progress, $category);
                        }
                    }
                }
                echo 1;

            } else {
                echo validation_errors();
            }

        } else { //result has been approved
            echo 'This result has been approved. Further action no longer possible.';
        }
    }


    //Category = Year 3-6 (Test Score)
    public function produce_year_3_6_test_score_ajax($session, $term, $class_id, $student_id) {
        //check if result has been approved by head teacher. if true, disallow further production action (from class/subject teacher only)
        $result_details = $this->kad_students_end_term_report_model->get_result_details($session, $term, $class_id, $student_id);
        $status = $result_details->status;
        //check if result has been approved by head teacher
        if ($status != 'Approved') { //result pending approval or rejected

            //template details
            $class_details = $this->common_model->get_class_details($class_id); 
            $template_id = $class_details->bespoke_template_id;
            $category = $this->kad_students_end_term_report_model->get_template_details($template_id)->category; 
            $max_assessment = 100;
            $this->form_validation->set_rules('assessment[]', 'Score', "trim|is_natural|less_than_equal_to[{$max_assessment}]",
                array(
                    'less_than_equal_to' => "Assessment score cannot exceed {$max_assessment}",
                )
            ); 
            $this->form_validation->set_rules('progress[]', 'Progress', 'trim');

            $subject_id = $this->input->post('subject_id', TRUE);
            $assessment = $this->input->post('assessment', TRUE);

            if ($this->form_validation->run()) {

                $total_subjects = count($subject_id);
                for ($i = 0; $i < $total_subjects; $i++) {
                    $d_subject_id = $subject_id[$i];
                    $d_assessment = $assessment[$i];
                    //Test Scores
                    $test_score_query = $this->kad_students_end_term_report_model->check_test_score_exists($session, $term, $class_id, $student_id, $d_subject_id);
                    if ($test_score_query->num_rows() == 0) { //data does not exists, do insert
                        $this->kad_students_end_term_report_model->insert_year_3_6_test_score($session, $term, $class_id, $student_id, $d_subject_id, $d_assessment, $category); 
                    } else { //data exists, do update
                        $this->kad_students_end_term_report_model->update_year_3_6_test_score($session, $term, $class_id, $student_id, $d_subject_id, $d_assessment, $category);
                    }
                }
                echo 1;

            } else {
                echo validation_errors();
            }

        } else { //result has been approved
            echo 'This result has been approved. Further action no longer possible.';
        }
    }


    //Category = Year 3-6 (Progres Score)
    public function produce_year_3_6_progress_score_ajax($session, $term, $class_id, $student_id) {
        //check if result has been approved by head teacher. if true, disallow further production action (from class/subject teacher only)
        $result_details = $this->kad_students_end_term_report_model->get_result_details($session, $term, $class_id, $student_id);
        $status = $result_details->status;
        //check if result has been approved by head teacher
        if ($status != 'Approved') { //result pending approval or rejected

            $this->form_validation->set_rules('progress[]', 'Progress', 'trim');
            $subject_id = $this->input->post('subject_id', TRUE);
            $progress = $this->input->post('progress', TRUE);

            if ($this->form_validation->run()) {

                $total_subjects = count($subject_id);
                for ($i = 0; $i < $total_subjects; $i++) {
                    //check if an item is selected, ignore otherwise
                    if ( isset($progress[$i]) ) {
                        $d_subject_id = $subject_id[$i];
                        $d_progress = $progress[$i];
                        //Progress Scores
                        $progress_score_query = $this->kad_students_end_term_report_model->check_year_3_6_progress_score_exists($session, $term, $class_id, $student_id, $d_subject_id);
                        if ($progress_score_query->num_rows() == 0) { //data does not exists, do insert
                            $this->kad_students_end_term_report_model->insert_year_3_6_progress_score($session, $term, $class_id, $student_id, $d_subject_id, $d_progress); 
                        } else { //data exists, do update
                            $this->kad_students_end_term_report_model->update_year_3_6_progress_score($session, $term, $class_id, $student_id, $d_subject_id, $d_progress);
                        }
                    }
                }
                echo 1;

            } else {
                echo validation_errors();
            }

        } else { //result has been approved
            echo 'This result has been approved. Further action no longer possible.';
        }
    }


    public function delete_year_3_6_progress_score($progress_score_id) { 
        $t = $this->kad_students_end_term_report_model->get_year_3_6_progress_score_details($test_score_id);
        $session = $t->session;
        $term = $t->term;
        $class_id = $t->class_id;
        $student_id = $t->student_id;
        //check this class is assigned to this teacher
        $this->subject_teacher_model->check_class_assigned($class_id);
        //check student is in this teacher's class
        $this->subject_teacher_model->check_student_class($class_id, $student_id);
        //check result has not been approved
        $this->kad_students_end_term_report_model->check_approval_status($session, $term, $class_id, $student_id, $this->c_controller);
        $this->kad_students_end_term_report_model->delete_year_3_6_progress_score($progress_score_id);
        $this->session->set_flashdata('status_msg', 'Progress score deleted successfully.');
        redirect($this->agent->referrer());
    }


    
    //Category = Secondary
    public function produce_secondary_test_score_ajax($session, $term, $class_id, $student_id) { 
        //check if result has been approved by head teacher. if true, disallow further production action (from class/subject teacher only)
        $result_details = $this->kad_students_end_term_report_model->get_result_details($session, $term, $class_id, $student_id);
        $status = $result_details->status;
        //check if result has been approved by head teacher
        if ($status != 'Approved') { //result pending approval or rejected

            //template details
            $class_details = $this->common_model->get_class_details($class_id); 
            $template_id = $class_details->bespoke_template_id;
            $category = $this->kad_students_end_term_report_model->get_template_details($template_id)->category;
            $max_effort = 3;
            $max_assessment = 100;
            $len_subject_comment = 400;
            $this->form_validation->set_rules('effort[]', 'Progress', "trim|is_natural|less_than_equal_to[{$max_effort}]",
                array(
                    'less_than_equal_to' => "Effort score cannot exceed {$max_effort}",
                )
            );
            $this->form_validation->set_rules('assessment[]', 'Assessment Score', "trim|is_natural|less_than_equal_to[{$max_assessment}]",
                array(
                    'less_than_equal_to' => "Assessment score cannot exceed {$max_assessment}",
                )
            ); 
            $this->form_validation->set_rules('subject_comment[]', 'Subject Comment', "trim|max_length[{$len_subject_comment}]",
                array(
                    'max_length' => "Subject comment cannot exceed {$len_subject_comment} characters",
                )
            );

            $subject_id = $this->input->post('subject_id', TRUE);
            $effort = $this->input->post('effort', TRUE);
            $assessment = $this->input->post('assessment', TRUE);
            $subject_comment = $this->input->post('subject_comment', TRUE);

            if ($this->form_validation->run()) {

                $total_subjects = count($subject_id);
                for ($i = 0; $i < $total_subjects; $i++) {
                    $d_subject_id = $subject_id[$i];
                    $d_effort = $effort[$i];
                    $d_assessment = $assessment[$i];
                    $d_subject_comment = $subject_comment[$i];
                    //Test Scores
                    $test_score_query = $this->kad_students_end_term_report_model->check_test_score_exists($session, $term, $class_id, $student_id, $d_subject_id);
                    if ($test_score_query->num_rows() == 0) { //data does not exists, do insert
                        $this->kad_students_end_term_report_model->insert_secondary_test_score($session, $term, $class_id, $student_id, $d_subject_id, $d_effort, $d_assessment, $d_subject_comment, $category); 
                    } else { //data exists, do update
                        $this->kad_students_end_term_report_model->update_secondary_test_score($session, $term, $class_id, $student_id, $d_subject_id, $d_effort, $d_assessment, $d_subject_comment, $category);
                    }
                }
                echo 1;

            } else {
                echo validation_errors();
            }

        } else { //result has been approved
            echo 'This result has been approved. Further action no longer possible.';
        }
    }



    public function delete_test_score($test_score_id, $class_id) { 
        $t = $this->kad_students_end_term_report_model->get_test_score_details($test_score_id, $class_id);
        $session = $t->session;
        $term = $t->term;
        $class_id = $t->class_id;
        $student_id = $t->student_id;
        //check this class is assigned to this teacher
        $this->subject_teacher_model->check_class_assigned($class_id);
        //check student is in this teacher's class
        $this->subject_teacher_model->check_student_class($class_id, $student_id);
        //check result has not been approved
        $this->kad_students_end_term_report_model->check_approval_status($session, $term, $class_id, $student_id, $this->c_controller);
        $this->kad_students_end_term_report_model->delete_test_score($test_score_id, $class_id);
        $this->session->set_flashdata('status_msg', 'Test score deleted successfully.');
        redirect($this->agent->referrer());
    }





    /* ========== Report Card ========== */
    public function report_card($template_id, $session, $term, $class_id, $student_id) {
        //validate report template
        $this->kad_students_end_term_report_model->check_report_template($template_id, $session, $term, $class_id, $student_id, 'staff');
        //check session and term is same as current session and current term
        $this->subject_teacher_model->check_session_and_term($session, $term);
        //check this class is assigned to this teacher
        $this->subject_teacher_model->check_class_assigned($class_id);
        //check student is in this teacher's class
        $this->subject_teacher_model->check_student_class($class_id, $student_id);
        //check result exists
        $this->kad_students_end_term_report_model->check_result_exists($session, $term, $class_id, $student_id, $this->c_controller);
        //check all has been added
        $this->kad_students_end_term_report_model->check_report_card_ok_staff($session, $term, $class_id, $student_id);

        //check class and student exist in this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'staff');

        $page_title = 'End-Term Report Card: ' . $this->common_model->get_student_fullname($student_id);
        $this->staff_header($page_title, $page_title);  
        //call report data
        $data = $this->kad_students_end_term_report_model->report_card_data($session, $term, $class_id, $student_id);

        $class_details = $this->common_model->get_class_details($class_id); 
        //template details
        $template_id = $class_details->bespoke_template_id;
        $category = $this->kad_students_end_term_report_model->get_template_details($template_id)->category; 
        $template_folder = $category;
        $data['category'] = $category;
        $data['template_id'] = $template_id;
        $data['controller'] = $this->c_controller;
        $this->load->view('shared/students_report/end_term/real/'.$this->bespoke_template_folder.'/'.$template_folder.'/report_card', $data);
        $this->staff_footer();
    }


    /* ===== Print Report Card ===== */
    public function print_report($template_id, $session, $term, $class_id, $student_id) {
        //validate report template
        $this->kad_students_end_term_report_model->check_report_template($template_id, $session, $term, $class_id, $student_id, 'staff');
        //check session and term is same as current session and current term
        $this->subject_teacher_model->check_session_and_term($session, $term);
        //check this class is assigned to this teacher
        $this->subject_teacher_model->check_class_assigned($class_id);
        //check student is in this teacher's class
        $this->subject_teacher_model->check_student_class($class_id, $student_id);
        //check result exists
        $this->kad_students_end_term_report_model->check_result_exists($session, $term, $class_id, $student_id, $this->c_controller);
        //check all has been added
        $this->kad_students_end_term_report_model->check_report_card_ok_staff($session, $term, $class_id, $student_id);

        //check class and student exist in this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'staff');

        $page_title = 'Print End-Term Report Card: ' . $this->common_model->get_student_fullname($student_id);
        $this->print_header($page_title, $page_title);  
        //call report data
        $data = $this->kad_students_end_term_report_model->report_card_data($session, $term, $class_id, $student_id);

        $class_details = $this->common_model->get_class_details($class_id); 
        //template details
        $template_id = $class_details->bespoke_template_id;
        $category = $this->kad_students_end_term_report_model->get_template_details($template_id)->category; 
        $template_folder = $category;
        $data['category'] = $category;
        $data['template_id'] = $template_id;
        $data['controller'] = $this->c_controller;
        $this->load->view('shared/students_report/end_term/real/'.$this->bespoke_template_folder.'/'.$template_folder.'/print_report', $data);
        $this->print_footer();
    }


    
}