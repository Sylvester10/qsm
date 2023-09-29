<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Classes_model
Role: Model
Description: Controls the DB processes of subjects 
Controller: Classes
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
Date Modified: 21st, 2019
*/

class Classes_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
    }



    /* ===== Classes ===== */
    public function add_new_class() { 
        $class = ucwords($this->input->post('class', TRUE)); 
        $slug = get_slug($this->input->post('class', TRUE)); //slug for class so it can be used in URL
        $level = ucwords($this->input->post('level', TRUE)); 
        $order_level = ucwords($this->input->post('order_level', TRUE)); 
        $section_id = $this->input->post('section_id', TRUE); 
        $class_teacher_id = $this->input->post('class_teacher_id', TRUE);
        if ($class_teacher_id != NULL) {
            $class_teacher_id = $this->input->post('class_teacher_id', TRUE);
        } else {
            $class_teacher_id = NULL;
        }
        $data = array (
            'school_id' => school_id,
            'class' => $class,
            'slug' => $slug,
            'level' => $level,
            'order_level' => $order_level,
            'section_id' => $section_id,
            'class_teacher_id' => $class_teacher_id,
        );
        $this->db->insert('classes', $data);
    }
    
    
    public function edit_class($class_id) { 
        $class = ucwords($this->input->post('class', TRUE)); 
        $slug = get_slug($this->input->post('class', TRUE)); //slug for class so it can be used in URL
        $level = ucwords($this->input->post('level', TRUE)); 
        $order_level = ucwords($this->input->post('order_level', TRUE)); 
        $section_id = $this->input->post('section_id', TRUE); 
        $data = array (
            'class' => $class,
            'slug' => $slug,
            'level' => $level,
            'order_level' => $order_level,
            'section_id' => $section_id,
        );
        $this->db->where('id', $class_id);
        $this->db->update('classes', $data);
    }


    public function check_class_exists($class) { 
        //check if class already exists for this school
        $this->db->where(array('school_id' => school_id, 'class' => $class));
        return $this->db->get('classes')->num_rows();
    }


    public function change_class_teacher($class_id) { 
        $class_teacher_id = $this->input->post('class_teacher_id', TRUE);
        if ($class_teacher_id != NULL) {
            $class_teacher_id = $this->input->post('class_teacher_id', TRUE);
        } else {
            $class_teacher_id = NULL;
        }
        $data = array (
            'class_teacher_id' => $class_teacher_id,
        );
        $this->db->where('id', $class_id);
        $this->db->update('classes', $data);
    }
    
    
    public function check_class_teacher_exists($class_teacher_id) {  
        //used to check if teacher is already assigned to another class
        $this->db->where('class_teacher_id', $class_teacher_id);
        return $this->db->get('classes')->num_rows();
    }
    
    
    public function unassign_class($class_id) { 
        $data = array (
            'class_teacher_id' => NULL
        );
        $this->db->where('id', $class_id);
        $this->db->update('classes', $data);
    }


    public function message_class_teacher($class_id) {
        $message = nl2br(ucfirst($this->input->post('message', TRUE))); 
        $subject = 'Message from Admin';
        $y = $this->common_model->get_class_details($class_id);
        $class_teacher_email = $this->common_model->get_staff_details_by_id($y->class_teacher_id)->email;
        return email_user($class_teacher_email, $subject, $message);
    } 


    public function get_class_next_order_level() {
        $classes = $this->common_model->get_classes(school_id);
        if (count($classes) > 0) { //section exists
            //get last (highest) order level
            $this->db->select_max('order_level');
            $this->db->where('school_id', school_id);
            $last_level = $this->db->get('classes')->row()->order_level;
            $next_level = $last_level + 1;
        } else { //no section added yet
            //set level as 1
            $next_level = 1;
        }
        return $next_level;
    }
    
    
    public function delete_class($class_id) {
        return $this->db->delete('classes', array('id' => $class_id));
    } 

    


}