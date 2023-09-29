<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Student_mid_term_reports
Role: Controller
Description: Controls access to all student reports pages and functions from the student's end
Model: Students_mid_term_report_model
Author: Nwankwo Ikemefuna
Date Created: 1st February, 2019
Date Modified: 1st February, 2019
*/


class Student_mid_term_reports extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->student_restricted(); //allow only logged in users to access this class
        $this->load->model('students_mid_term_report_model');
        $this->student_details = $this->common_model->get_student_details_by_reg_id($this->session->student_reg_id);
        //get school id
        $this->school_id = $this->student_details->school_id;
        require_once "application/core/constants/Session.php"; //require session constants
        //ensure user login and current module are covered by current plan
        $this->login_module_restricted(school_id, mod_student_login, mod_student_reports, 'student'); 
        //ensure school account is activated
        $this->activation_restricted_student(school_id); 

        //module-level scripts
        $this->student_module_scripts = array('s_student_reports');
    }




    public function check_result() {
        $this->student_header('Check Mid-Term Result', 'Check Mid-Term Result');
        $student_id = $this->student_details->id;       
        $data['result_sessions'] = $this->students_mid_term_report_model->get_student_result_sessions($student_id);
        $data['current_class_id'] = $this->student_details->class_id;
        $this->load->view('student/reports/mid_term/check_result', $data);
        $this->student_footer();
    }


    public function check_result_ajax() { 
        //set validation rules
        $this->form_validation->set_rules('session', 'Session', 'trim|required');
        $this->form_validation->set_rules('term', 'term', 'trim|required');
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required');

        if ($this->form_validation->run() == FALSE) {

            $errors = array(
                'validation_status' => 0,
                'validation_errors' => validation_errors(),
            );
            echo json_encode($errors);
        
        } else {

            $session = $this->input->post('session', TRUE);
            $term = $this->input->post('term', TRUE);
            $class_id = $this->input->post('class_id', TRUE);
            $student_id = $this->student_details->id;   

            //get report template
            $class_details = $this->common_model->get_class_details($class_id); 
            $section_id = $class_details->section_id;
            $template_id = $this->common_model->get_section_details($section_id)->template_id;

            //check if result exists for selected session, term and class. Also check if result has been approved
            $result_exists = $this->check_result_exists($session, $term, $class_id, $student_id);
            $query = $this->students_mid_term_report_model->get_result_details($session, $term, $class_id, $student_id);

            if ($result_exists && $query->status == 'Approved') { //result exists and has been approved
                $json_data = array(
                    'response' => 1,
                    'template_id' => $template_id,
                );
                echo json_encode($json_data);

            } elseif ($result_exists && $query->status != 'Approved') { //result exists but has not been approved or is declined

                $json_data = array(
                    'response' => 0,
                    'message' => "Result pending approval. Check back later.",
                );
                echo json_encode($json_data);

            } else {

                 //result doesn't exist
                $json_data = array(
                    'response' => 0,
                    'message' => "No result found for selected session, term and class.",
                );
                echo json_encode($json_data);
            }
            
        }
    }


    private function check_result_exists($session, $term, $class_id, $student_id) { 
        $query = $this->students_mid_term_report_model->get_result_details($session, $term, $class_id, $student_id);
        if ($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    private function report_card_restricted($session, $term, $class_id, $student_id) { 
        $result_exists = $this->check_result_exists($session, $term, $class_id, $student_id);
        $query = $this->students_mid_term_report_model->get_result_details($session, $term, $class_id, $student_id); 
        if ($result_exists && $query->status == 'Approved') {
            return TRUE;
        } elseif ($result_exists && $query->status != 'Approved') {
            $this->session->set_flashdata('status_msg_error', 'Result pending approval. Check back later.');
            redirect(site_url($this->c_controller.'/check_result'));
        } else {
            $this->session->set_flashdata('status_msg_error', 'No result found for selected session, term and class.');
            redirect(site_url($this->c_controller.'/check_result'));
        }
    }



    /* ========== Report Card ========== */
    public function report_card($template_id, $session, $term, $class_id) {
        $student_id = $this->student_details->id;   
        //validate report template
        $this->students_mid_term_report_model->check_report_template($template_id, $session, $term, $class_id, $student_id, 'student');

        //ensure all is well
        $this->report_card_restricted($session, $term, $class_id, $student_id);
        $page_title = 'Mid-Term Report Card: ' . $this->common_model->get_student_fullname($student_id);
        $this->student_header($page_title, $page_title);
        //call report data
        $data = $this->students_mid_term_report_model->report_card_data($session, $term, $class_id, $student_id);

        $template_folder = 'template_'.$template_id;
        $data['template_id'] = $template_id;
        $data['controller'] = $this->c_controller;
        $this->load->view('shared/students_report/mid_term/real/'.$template_folder.'/report_card', $data);
        $this->student_footer();
    }



    /* ===== Print Report Card ===== */
    public function print_report($template_id, $session, $term, $class_id) {
        $student_id = $this->student_details->id;   
        //validate report template
        $this->students_mid_term_report_model->check_report_template($template_id, $session, $term, $class_id, $student_id, 'student');
          
        //ensure all is well
        $this->report_card_restricted($session, $term, $class_id, $student_id);
        $page_title = 'Print Mid-Term Report Card: ' . $this->common_model->get_student_fullname($student_id);
        $this->print_header($page_title, $page_title);
        //call report data
        $data = $this->students_mid_term_report_model->report_card_data($session, $term, $class_id, $student_id);

        $template_folder = 'template_'.$template_id;
        $data['template_id'] = $template_id;
        $data['controller'] = $this->c_controller;
        $this->load->view('shared/students_report/mid_term/real/'.$template_folder.'/print_report', $data);
        $this->print_footer();
    }
    
    
    
    
}