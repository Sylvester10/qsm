<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Subjects_model
Role: Model
Description: Controls the DB processes of subjects 
Controller: Subjects
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
Date Modified: 21st, 2019
*/

class Subjects_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
    }



    /* ===== Subjects Groups ===== */
    public function add_new_subject_group() {  
        $subject_group_items = $this->input->post('subject_group', TRUE); 
        //explode category to get individual items (note that comma is used as delimeter)
        $subject_groups = explode(",", $subject_group_items);
        foreach ($subject_groups as $subject_group) {
            //ensure category does not already exist
            $subject_group = ucwords($subject_group);
            $query = $this->check_subject_group_exists($subject_group);
            if ($query == 0) {
                $data = array(
                    'school_id' => school_id,
                    'subject_group' => $subject_group,
                );
                $this->db->insert('subject_groups', $data);
            } 
        }
    }


    public function edit_subject_group($subject_group_id) { 
        $subject_group = ucwords($this->input->post('subject_group', TRUE)); 
        $data = array(
            'subject_group' => $subject_group,
        );
        $this->db->where('id', $subject_group_id);
        $this->db->update('subject_groups', $data);
    }


    public function check_subject_group_exists($subject_group) { 
        //check if subject group already exists for this school
        $this->db->where(array('school_id' => school_id, 'subject_group' => $subject_group));
        return $this->db->get('subject_groups')->num_rows();
    }
    
    
    public function delete_subject_group($subject_group_id) {
        return $this->db->delete('subject_groups', array('id' => $subject_group_id));
    } 
    
    
    
    
    /* ===== Subjects ===== */
    public function add_new_subject() { 
        $subject = ucwords($this->input->post('subject', TRUE)); 
        $subject_short = ucwords($this->input->post('subject_short', TRUE)); 
        $subject_group_id = $this->input->post('subject_group_id', TRUE);   
        if ($subject_group_id != '') {
            $subject_group_id = ucwords($this->input->post('subject_group_id', TRUE)); 
        } else {
            $subject_group_id = NULL;
        }
        $section_id = implode(", ", $this->input->post('section_id', TRUE));
        //check if more than one section was selected
        $section_array = explode(", ", $section_id);
        $data = array();
        foreach ($section_array as $d_section_id) {
            $row = array();
            $row['school_id'] = school_id;
            $row['subject'] = $subject;
            $row['subject_short'] = $subject_short;
            $row['subject_group_id'] = $subject_group_id;
            $row['section_id'] = $d_section_id;
            $data[] = $row;
        }
        return $this->db->insert_batch('subjects', $data);
    }


    public function edit_subject($subject_id) { 
        $subject = ucwords($this->input->post('subject', TRUE));
        $subject_short = ucwords($this->input->post('subject_short', TRUE)); 
        $subject_group_id = $this->input->post('subject_group_id', TRUE);   
        if ($subject_group_id != '') {
            $subject_group_id = ucwords($this->input->post('subject_group_id', TRUE)); 
        } else {
            $subject_group_id = NULL;
        }
        $section_id = $this->input->post('section_id', TRUE);
        $data = array(
            'subject' => $subject,
            'subject_short' => $subject_short,
            'subject_group_id' => $subject_group_id,
            'section_id' => $section_id,
        );
        $this->db->where('id', $subject_id);
        $this->db->update('subjects', $data);
    }


    public function check_subject_exists($subject, $section_id) { 
        //check if subject already exists for the selected section
        $this->db->where(array('school_id' => school_id, 'subject' => $subject, 'section_id' => $section_id));
        return $this->db->get('subjects')->num_rows();
    }
    
    
    public function delete_subject($subject_id) {
        return $this->db->delete('subjects', array('id' => $subject_id));
    } 



}