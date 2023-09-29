<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Settings_model
Role: Model
Description: Controls the DB processes of account settings 
Controller: Settings
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
Date Modified: 21st, 2019
*/

class Settings_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
    }



    /* ===== Term Settings ===== */
    public function update_term_info() { 
        $data = array (
            'session' => $this->input->post('session', TRUE),
            'term' => $this->input->post('term', TRUE),
            'term_start_date' => $this->input->post('term_start_date', TRUE),
            'term_closing_date' => $this->input->post('term_closing_date', TRUE),
            'current_term_fees_due_date' => $this->input->post('current_term_fees_due_date', TRUE),
            'next_term' => $this->input->post('next_term', TRUE),
            'next_term_start_date' => $this->input->post('next_term_start_date', TRUE),
            'next_term_fees_due_date' => $this->input->post('next_term_fees_due_date', TRUE),
        );
        $this->db->where('school_id', school_id);
        $this->db->update('term_info', $data);
    }
    
    
    
    /* ===== Report Settings ===== */
    public function update_report_evaluation($id, $grade, $gp, $evaluation, $head_teacher_comment) { 
        $data = array (
            'grade' => strtoupper($grade),
            'gp' => $gp,
            'evaluation' => ucwords($evaluation),
            'head_teacher_comment' => ucwords($head_teacher_comment),
        );
        $this->db->where('id', $id);
        $this->db->update('report_evaluation', $data);
    }


    public function add_new_aptitude() { 
        $data = array (
            'school_id' => school_id,
            'aptitude' => ucwords($this->input->post('aptitude', TRUE)),
            'domain' => ucfirst($this->input->post('domain', TRUE)),
        );
        $this->db->insert('aptitudes', $data);
    }


    public function edit_aptitude($aptitude_id) { 
        $data = array (
            'aptitude' => ucwords($this->input->post('aptitude', TRUE)),
            'domain' => ucfirst($this->input->post('domain', TRUE)),
        );
        $this->db->where('id', $aptitude_id);
        $this->db->update('aptitudes', $data);
    }


    public function check_aptitude_exists($aptitude) { 
        $this->db->where(array('school_id' => school_id, 'aptitude' => $aptitude));
        return $this->db->get('aptitudes')->num_rows();
    }


    public function delete_aptitude($id) {
        return $this->db->delete('aptitudes', array('id' => $id));
    } 


    public function attach_report_template($template_id) { 
        $section_idx = $this->input->post('section_id', TRUE);
        foreach ($section_idx as $section_id) { 
            $data = array (
                'template_id' => $template_id,
            );
            $this->db->where('id', $section_id);
            $this->db->update('sections', $data);
        }
    }






    /* ===== Mid-term Report Settings ===== */
    public function update_mid_term_report_evaluation($id, $grade, $evaluation) { 
        $data = array (
            'grade' => strtoupper($grade),
            'evaluation' => ucwords($evaluation),
        );
        $this->db->where('id', $id);
        $this->db->update('mid_term_report_evaluation', $data);
    }


    public function attach_mid_term_report_template($mt_template_id) { 
        $section_idx = $this->input->post('section_id', TRUE);
        foreach ($section_idx as $section_id) { 
            $data = array (
                'mt_template_id' => $mt_template_id,
            );
            $this->db->where('id', $section_id);
            $this->db->update('sections', $data);
        }
    }




    /* =================== Report Templates ====================== */
    public function get_report_templates() { 
        $this->db->order_by('template_id', 'asc');
        return $this->db->get('report_templates')->result();
    }


    public function get_report_template_details($template_id) { 
        return $this->db->get_where('report_templates', array('template_id' => $template_id))->row();
    }


    public function get_sections_by_report_template($school_id, $template_id) { 
        return $this->db->get_where('sections', array('school_id' => $school_id, 'template_id' => $template_id))->result();
    }






    /* =================== Mid-term Report Templates ====================== */
    public function get_mid_term_report_templates() { 
        $this->db->order_by('template_id', 'asc');
        return $this->db->get('mid_term_report_templates')->result();
    }


    public function get_mid_term_report_template_details($template_id) { 
        return $this->db->get_where('mid_term_report_templates', array('template_id' => $template_id))->row();
    }


    public function get_sections_by_mid_term_report_template($school_id, $template_id) { 
        return $this->db->get_where('sections', array('school_id' => $school_id, 'mt_template_id' => $template_id))->result();
    }
    
    




    /* ========== School Stamp ========== */
    public function update_school_stamp($stamp) { 
        $data = array (
            'school_stamp' => $stamp,
        );
        $this->db->where('id', school_id);
        return $this->db->update('school_info', $data);
    }
    
    
    public function delete_old_school_stamp() {
        $y = $this->common_model->get_school_info(school_id);
        unlink('./assets/uploads/stamp/'.$y->school_stamp); //delete the stamp
    } 
    
    
    
    
    /* ===== School Info ===== */
    public function update_school_info() { 
        $school_name = ucwords($this->input->post('school_name', TRUE)); 
        $school_location = ucwords($this->input->post('school_location', TRUE)); 
        $official_mail = strtolower($this->input->post('official_mail', TRUE)); 
        $telephone_line = $this->input->post('telephone_line', TRUE); 
        $school_website = $this->input->post('school_website', TRUE); 
        //prepend http:// to website url if not provided
        $school_website = prep_url($school_website);
        $school_motto = ucfirst($this->input->post('school_motto', TRUE)); 
        $data = array (
            'school_name' => $school_name,
            'school_location' => $school_location,
            'official_mail' => $official_mail,
            'telephone_line' => $telephone_line,
            'school_website' => $school_website,
            'school_motto' => $school_motto,
        );
        $this->db->where('id', school_id);
        return $this->db->update('school_info', $data);
    }
    
    
    public function update_school_logo($school_logo, $school_favicon) { 
        $data = array (
            'school_logo' => $school_logo,
            'school_favicon' => $school_favicon,
        );
        $this->db->where('id', school_id);
        return $this->db->update('school_info', $data);
    }
    
    
    public function delete_old_school_logo() {
        $y = $this->common_model->get_school_info(school_id);
        unlink('./assets/uploads/logo/'.$y->school_logo); //delete the school logo
        unlink('./assets/uploads/logo/'.$y->school_favicon); //delete the favicon
    } 
    
    
    public function reset_school_logo() { //reset school logo to app's default
        $this->delete_old_school_logo(); //delete the logo and favicon
        $data = array (
            'school_logo' => NULL,
            'school_favicon' => NULL,
        );
        $this->db->where('id', school_id);
        return $this->db->update('school_info', $data);
    } 


}