<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Mid_term_report_templates
Role: Controller
Description: controls access to all mid-term report/result templates from admin panel
Model: Mid-term_report_template_model
Author: Nwankwo Ikemefuna
Date Created: 23rd January, 2019
*/


class Mid_term_report_templates extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('mid_term_report_templates_model');
        $this->admin_restricted(); //allow only logged in users to access this class
        $this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
        //get school id
        $this->school_id = $this->admin_details->school_id;
        require_once "application/core/constants/Session.php"; //require session constants
    }

    
    
    /* ============= Template 1 ============ */

    public function template_1() {
        $template_id = 1;
        $page_title = 'Mid-term Report Template ' . $template_id;
        $this->admin_header($page_title, $page_title);  
        $template_folder = 'template_'.$template_id;
        //report data
        $data = $this->mid_term_report_templates_model->report_data();
        $data['template_folder'] = $template_folder;
        $data['template_id'] = $template_id;
        $data['print_url'] = 'mid_term_report_templates/print_template_'.$template_id;
        $data['report_evaluation'] = $this->mid_term_report_templates_model->get_report_evaluation();
        $total_grade_keys = count($data['report_evaluation']);
        //rowspan should be total no of grade keys + 1 (1 is for the GRADE KEY title)
        $data['rowspan'] = $total_grade_keys + 1; //rowspan for table
        $this->load->view('shared/students_report/mid_term/samples/'.$template_folder.'/report_card', $data);
        $this->admin_footer();
    }


    public function print_template_1() {
        $template_id = 1;
        $page_title = 'Mid-term Report Template ' . $template_id;
        $this->print_header($page_title);   
        $template_folder = 'template_'.$template_id;
        //report data
        $data = $this->mid_term_report_templates_model->report_data();
        $data['template_folder'] = $template_folder;
        $data['template_id'] = $template_id;
        $data['print_url'] = 'mid_term_report_templates/print_template_'.$template_id;
        $this->load->view('shared/students_report/mid_term/samples/'.$template_folder.'/print_report', $data);
        $this->print_footer();
    }


    public function template_2() {
        $template_id = 2;
        $page_title = 'Mid-term Report Template ' . $template_id;
        $this->admin_header($page_title, $page_title);  
        $template_folder = 'template_'.$template_id;
        //report data
        $data = $this->mid_term_report_templates_model->report_data();
        $data['template_folder'] = $template_folder;
        $data['template_id'] = $template_id;
        $data['print_url'] = 'mid_term_report_templates/print_template_'.$template_id;
        $this->load->view('shared/students_report/mid_term/samples/'.$template_folder.'/report_card', $data);
        $this->admin_footer();
    }


    public function print_template_2() {
        $template_id = 2;
        $page_title = 'Mid-term Report Template ' . $template_id;
        $this->print_header($page_title);   
        $template_folder = 'template_'.$template_id;
        //report data
        $data = $this->mid_term_report_templates_model->report_data();
        $data['template_folder'] = $template_folder;
        $data['template_id'] = $template_id;
        $data['print_url'] = 'mid_term_report_templates/print_template_'.$template_id;
        $this->load->view('shared/students_report/mid_term/samples/'.$template_folder.'/print_report', $data);
        $this->print_footer();
    }


    public function template_3() {
        $template_id = 3;
        $page_title = 'Mid-term Report Template ' . $template_id;
        $this->admin_header($page_title, $page_title);  
        $template_folder = 'template_'.$template_id;
        //report data
        $data = $this->mid_term_report_templates_model->report_data();
        $data['template_folder'] = $template_folder;
        $data['template_id'] = $template_id;
        $data['print_url'] = 'mid_term_report_templates/print_template_'.$template_id;
        $this->load->view('shared/students_report/mid_term/samples/'.$template_folder.'/report_card', $data);
        $this->admin_footer();
    }


    public function print_template_3() {
        $template_id = 3;
        $page_title = 'Mid-term Report Template ' . $template_id;
        $this->print_header($page_title);   
        $template_folder = 'template_'.$template_id;
        //report data
        $data = $this->mid_term_report_templates_model->report_data();
        $data['template_folder'] = $template_folder;
        $data['template_id'] = $template_id;
        $data['print_url'] = 'mid_term_report_templates/print_template_'.$template_id;
        $this->load->view('shared/students_report/mid_term/samples/'.$template_folder.'/print_report', $data);
        $this->print_footer();
    }


    public function template_4() {
        $template_id = 4;
        $page_title = 'Mid-term Report Template ' . $template_id;
        $this->admin_header($page_title, $page_title);  
        $template_folder = 'template_'.$template_id;
        //report data
        $data = $this->mid_term_report_templates_model->report_data();
        $data['enable_subject_grouping'] = 'true';
        $data['template_folder'] = $template_folder;
        $data['template_id'] = $template_id;
        $data['print_url'] = 'mid_term_report_templates/print_template_'.$template_id;
        $this->load->view('shared/students_report/mid_term/samples/'.$template_folder.'/report_card', $data);
        $this->admin_footer();
    }


    public function print_template_4() {
        $template_id = 4;
        $page_title = 'Mid-term Report Template ' . $template_id;
        $this->print_header($page_title);   
        $template_folder = 'template_'.$template_id;
        //report data
        $data = $this->mid_term_report_templates_model->report_data();
        $data['enable_subject_grouping'] = 'true';
        $data['template_folder'] = $template_folder;
        $data['template_id'] = $template_id;
        $data['print_url'] = 'mid_term_report_templates/print_template_'.$template_id;
        $this->load->view('shared/students_report/mid_term/samples/'.$template_folder.'/print_report', $data);
        $this->print_footer();
    }



}