<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Student_acc_model
Role: Model
Description: Controls the DB processes of student account
Controller: Student_acc
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
Date Created: 21st January, 2019
*/


class Student_acc_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }



    public function check_reg_id_exists($reg_id) {  
        $reg_id_exists = $this->db->get_where('students', array('reg_id' => $reg_id))->row();
        return ($reg_id_exists) ? TRUE : FALSE;  
    }
    
    
    public function set_password($reg_id) {
        $password = hash('ripemd128', $this->input->post('password', TRUE));
        $data = array (
            'password' => $password,         
        );       
        $this->db->where('reg_id', $reg_id);            
        return $this->db->update('students', $data);
    }
    
    
    public function change_password($reg_id) {
        $password = hash('ripemd128', $this->input->post('password', TRUE));
        $data = array (
            'password' => $password,     
        );       
        $this->db->where('reg_id', $reg_id);            
        return $this->db->update('students', $data);
    }
    
        



}