<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Settings
Role: Controller
Description: Settings Class controls account settings
Model: Settings_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
Date Modified: 21st January, 2019
*/


class Settings extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->admin_restricted(); //allow only logged in users to access this class
        $this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
        //get school id
        $this->school_id = $this->admin_details->school_id;
        require_once "application/core/constants/Session.php"; //require session constants

        //module-level scripts
        $this->admin_module_scripts = array('s_settings');
    }





    /* ====== Term Settings ====== */
    public function term_info() {
        $this->admin_header('Term Info', 'Term Info');  
        $data['y'] = $this->common_model->get_term_info(school_id);
        $this->load->view('admin/settings/term_info', $data);
        $this->admin_footer();
    }


    public function update_term_info_ajax() {   
        $this->form_validation->set_rules('session', 'Session', 'trim|required');
        $this->form_validation->set_rules('term', 'Term', 'trim|required');
        $this->form_validation->set_rules('term_start_date', 'Term Start Date', 'trim|required');
        $this->form_validation->set_rules('term_closing_date', 'Term Closing Date', 'trim|required');
        $this->form_validation->set_rules('current_term_fees_due_date', 'Current Term Fees Due Date', 'trim|required');
        $this->form_validation->set_rules('next_term', 'Next Term', 'trim|required');
        $this->form_validation->set_rules('next_term_start_date', 'Next Term Start Date', 'trim|required');
        $this->form_validation->set_rules('next_term_fees_due_date', 'Next Term Fees Due Date', 'trim|required');
        
        if ($this->form_validation->run()) {    

            //check if demo user    
            $demo_action_allowed = $this->demo_action_restricted_admin_ajax();
            if ($demo_action_allowed)   {
                $this->settings_model->update_term_info();
                echo 1;
            } else {
                echo $this->demo_action_restricted_msg();
            }

        } else { 
            echo validation_errors();
        }
    }

    
    
    
    
    /* ====== Report Settings ====== */
    public function report_settings() {
        $this->admin_header('Report Settings', 'Report Settings');  
        $data['evaluation'] = $this->common_model->get_report_evaluation(school_id);
        $data['aptitudes'] = $this->common_model->get_aptitudes(school_id);
        $data['sections'] = $this->common_model->get_sections(school_id);
        $data['report_templates'] = $this->settings_model->get_report_templates();
        $this->load->view('admin/settings/report_settings', $data);
        $this->admin_footer();
    }
    
    


    /* ====== Report Evaluation ====== */
    public function update_report_evaluation_ajax() { 
        $this->form_validation->set_rules('grade[]', 'Letter Grade', 'trim|required|max_length[2]');
        $this->form_validation->set_rules('gp[]', 'GP', 'trim|required|is_natural|max_length[1]');
        $this->form_validation->set_rules('evaluation[]', 'Evaluation', 'trim|required');
        $this->form_validation->set_rules('head_teacher_comment[]', 'Evaluation', 'trim|required');
        $evaluation_id = $this->input->post('evaluation_id', TRUE);
        $grade = $this->input->post('grade', TRUE);
        $gp = $this->input->post('gp', TRUE);
        $evaluation = $this->input->post('evaluation', TRUE);
        $head_teacher_comment = $this->input->post('head_teacher_comment', TRUE);

        if ($this->form_validation->run()) {

            //check if demo user    
            $demo_action_allowed = $this->demo_action_restricted_admin_ajax();
            if ($demo_action_allowed) {
                for ($i = 0; $i < count($evaluation_id); $i++) {
                    $id = $evaluation_id[$i];
                    $d_grade = $grade[$i];
                    $d_gp = $gp[$i];
                    $d_evaluation = $evaluation[$i];
                    $d_head_teacher_comment = $head_teacher_comment[$i];
                    $this->settings_model->update_report_evaluation($id, $d_grade, $d_gp, $d_evaluation, $d_head_teacher_comment);
                }
                echo 1;
            } else {
                echo $this->demo_action_restricted_msg();
            }
            
        } else {
            echo validation_errors();
        }
    }



    
    /* ====== Behavioural Aptitudes ====== */
    public function add_new_aptitude_ajax() {   
        $this->form_validation->set_rules('aptitude', 'Behavioural Aptitude', 'trim|required|callback_check_aptitude_exists');
        $this->form_validation->set_rules('domain', 'Domain', 'trim|required');
        if ($this->form_validation->run())  {
            $count = count($this->common_model->get_aptitudes(school_id));
            //ensure aptitudes do not exceed 15
            if ($count < 15) { 
                $this->settings_model->add_new_aptitude();
                echo 1; 
            } else {
                echo 'Maximum aptitudes allowed is 15';
            }   
        } else { 
            echo validation_errors();
        }
    }


    public function edit_aptitude($aptitude_id) {   
        $current_aptitude = $this->common_model->get_aptitude_details($aptitude_id)->aptitude;
        $new_aptitude = ucwords($this->input->post('aptitude', TRUE));
        if ($current_aptitude != $new_aptitude) { 
            $this->form_validation->set_rules('aptitude', 'Behavioural Aptitude', 'trim|required|callback_check_aptitude_exists');
        } else {
            $this->form_validation->set_rules('aptitude', 'Behavioural Aptitude', 'trim|required');
        }
        $this->form_validation->set_rules('domain', 'Domain', 'trim|required');
        if ($this->form_validation->run())  {
            $this->settings_model->edit_aptitude($aptitude_id);
            $this->session->set_flashdata('status_msg', 'Aptitude updated successfully.');
        } else { 
            $this->session->set_flashdata('status_msg_error', 'Error updating Aptitude.');
        }
        redirect($this->agent->referrer());
    }


     public function check_aptitude_exists() {
        //callback function to check if aptitude exists 
        $aptitude = ucwords($this->input->post('aptitude', TRUE));
        $query = $this->settings_model->check_aptitude_exists($aptitude);
        if ($query == 0) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_aptitude_exists', "{$aptitude} already exists.");
            return FALSE;
        }
    }
    
    
    public function delete_aptitude($id) { 
        $this->demo_action_restricted_admin();
        //check aptitude exists for this school
        $this->check_school_data_exists(school_id, $id, 'id', 'aptitudes', 'admin');
        $this->settings_model->delete_aptitude($id);
        $this->session->set_flashdata('status_msg', 'Behavioural Aptitude deleted successfully.');
        redirect($this->agent->referrer());
    }



    public function attach_report_template($template_id) { 
        $this->form_validation->set_rules('section_id[]', 'Section', 'trim|required');
        if ($this->form_validation->run())  {       
            $this->settings_model->attach_report_template($template_id);
            $this->session->set_flashdata('status_msg', "Report template attached successfully.");
        } else {
            $this->session->set_flashdata('status_msg_error', 'Error attaching report template.');
        }
        redirect($this->agent->referrer());
    }


    



    /* ====== Mid-term Report Settings ====== */
    public function mid_term_report_settings() {
        $this->admin_header('Mid-Term Report Settings', 'Mid-Term Report Settings');  
        $data['evaluation'] = $this->common_model->get_mid_term_report_evaluation(school_id);
        $data['sections'] = $this->common_model->get_sections(school_id);
        $data['report_templates'] = $this->settings_model->get_mid_term_report_templates();
        $this->load->view('admin/settings/mid_term_report_settings', $data);
        $this->admin_footer();
    }
    

    public function update_mid_term_report_evaluation_ajax() { 
        $this->form_validation->set_rules('grade[]', 'Letter Grade', 'trim|required|max_length[2]');
        $this->form_validation->set_rules('evaluation[]', 'Evaluation', 'trim|required');
        $evaluation_id = $this->input->post('evaluation_id', TRUE);
        $grade = $this->input->post('grade', TRUE);
        $evaluation = $this->input->post('evaluation', TRUE);
        
        if ($this->form_validation->run()) {

            //check if demo user    
            $demo_action_allowed = $this->demo_action_restricted_admin_ajax();
            if ($demo_action_allowed) {
                for ($i = 0; $i < count($evaluation_id); $i++) {
                    $id = $evaluation_id[$i];
                    $d_grade = $grade[$i];
                    $d_evaluation = $evaluation[$i];
                    $this->settings_model->update_mid_term_report_evaluation($id, $d_grade, $d_evaluation);
                }
                echo 1;
            } else {
                echo $this->demo_action_restricted_msg();
            }
            
        } else {
            echo validation_errors();
        }
    }


    public function attach_mid_term_report_template($mt_template_id) { 
        $this->form_validation->set_rules('section_id[]', 'Section', 'trim|required');
        if ($this->form_validation->run())  {       
            $this->settings_model->attach_mid_term_report_template($mt_template_id);
            $this->session->set_flashdata('status_msg', "Report template attached successfully.");
        } else {
            $this->session->set_flashdata('status_msg_error', 'Error attaching report template.');
        }
        redirect($this->agent->referrer());
    }




    
    
    
    /* ====== School Info ====== */
    public function school_info($error = array('error' => '')) {
        $this->admin_header('School Info', 'School Info');  
        $data['y'] = $this->common_model->get_school_info(school_id);
        $data['upload_error'] = $error;
        $this->load->view('admin/settings/school_info', $data);
        $this->admin_footer();
    }
    
    
    public function edit_school_info_ajax() {   
        $this->form_validation->set_rules('school_name', 'School Name', 'trim|required');
        $this->form_validation->set_rules('school_location', 'School Location', 'trim|required');
        $this->form_validation->set_rules('official_mail', 'Official Email Address', 'trim|required|valid_email');
        $this->form_validation->set_rules('telephone_line', 'Official Telephone Line', 'trim|required|max_length[20]');
        $this->form_validation->set_rules('school_website', 'School Website', 'trim');
        $this->form_validation->set_rules('school_motto', 'School Motto', 'trim');
        
        if ($this->form_validation->run())  {   

            //check if demo user    
            $demo_action_allowed = $this->demo_action_restricted_admin_ajax();
            if ($demo_action_allowed)   {
                $this->settings_model->update_school_info();
                echo 1;
            } else {
                echo $this->demo_action_restricted_msg();
            }   

        } else { 
            echo validation_errors();
        }
    }
    
    
    public function update_school_logo($error = array('error' => '')) { 
        //check if demo user
        $this->demo_action_restricted_admin();

        //config for file uploads
        $config['upload_path']          = './assets/uploads/logo/'; //path to save the files
        $config['allowed_types']        = 'png|PNG';  //extensions which are allowed
        $config['max_size']             = 100; //image size cannot exceed 100KB
        $config['min_width']            = 150; //min width 150px
        $config['min_height']           = 150; //min height 150px
        $config['max_width']            = 250; //max width 250px
        $config['max_height']           = 250; //max height 250px
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
        $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
        $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
                                        
        $this->load->library('upload', $config);
        
        if ( $_FILES['school_logo']['name'] == "" ) { //file is not selected
            $this->session->set_flashdata('status_msg_error', "No file selected!");
            $this->school_info(); //reload page
            
        } elseif ( ( ! $this->upload->do_upload('school_logo')) && ($_FILES['school_logo']['name'] != "") ) {   
            //upload does not happen when file is selected
            $error = array('error' => $this->upload->display_errors());
            $this->school_info($error); //reload page with upload errors
            
        } else { //file is selected, upload happens, everyone is happy
            //delete the old school logo and favicon
            $this->settings_model->delete_old_school_logo();
            $school_logo = $this->upload->data('file_name');
            //generate a 64x64 image for use as favicon
            $school_favicon = generate_image_thumb($school_logo, '64', '64');   
            $this->settings_model->update_school_logo($school_logo, $school_favicon);
            $this->session->set_flashdata('status_msg', "School logo updated successfully!");
            redirect('settings/school_info');
        }
    }
    
    
    public function reset_school_logo() {  //reset school logo to app's default
        //check if demo user
        $this->demo_action_restricted_admin();
        $this->settings_model->reset_school_logo();
        $this->session->set_flashdata('status_msg', 'School logo successfully reset to default.');
        redirect('settings/school_info');
    }



    /* ========== School Stamp =========== */
    public function school_stamp($error = array('error' => '')) {
        $this->admin_header('School Stamp', 'School Stamp');    
        $data['y'] = $this->common_model->get_school_info(school_id);
        $data['upload_error'] = $error;
        $this->load->view('admin/settings/school_stamp', $data);
        $this->admin_footer();
    }


    public function update_school_stamp($error = array('error' => '')) { 
        //check if demo user
        $this->demo_action_restricted_admin();

        //config for file uploads
        $config['upload_path']          = './assets/uploads/stamp'; //path to save the files
        $config['allowed_types']        = 'jpeg|JPEG|jpg|JPG|png|PNG';  //extensions which are allowed
        $config['max_size']             = 64; //image size cannot exceed 64KB
        $config['file_ext_tolower']     = TRUE; //force file extension to lower case
        $config['remove_spaces']        = TRUE; //replace space in file names with underscores to avoid break
        $config['detect_mime']          = TRUE; //detect type of file to avoid code injection
                                        
        $this->load->library('upload', $config);
        
        if ( $_FILES['stamp']['name'] == "" ) { //file is not selected
            $this->session->set_flashdata('status_msg_error', "No file selected!");
            redirect(site_url('settings/school_stamp'));
            
        } elseif ( ( ! $this->upload->do_upload('stamp')) && ($_FILES['stamp']['name'] != "") ) {   
            //upload does not happen when file is selected
            $error = array('error' => $this->upload->display_errors());
            $this->school_stamp($error); //reload page with upload errors
            
        } else { //file is selected, upload happens, everyone is happy
            //delete the old signature file
            $this->settings_model->delete_old_school_stamp();
            $stamp = $this->upload->data('file_name');
            $this->settings_model->update_school_stamp($stamp);
            $this->session->set_flashdata('status_msg', "School stamp updated successfully!");
            redirect(site_url('settings/school_stamp'));
        }
    }




}