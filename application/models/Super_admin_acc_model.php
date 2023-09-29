<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Super_admin_acc_model
Role: Model
Description: Controls the DB processes of super admin account
Controller: Super_admin_acc
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
Date Created: 21st January, 2019
*/


class Super_admin_acc_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }



    public function check_email_exists($email) {    
        $email_exists = $this->db->get_where('super_admins', array('email' => $email))->row();
        return ($email_exists) ? TRUE : FALSE;  
    }
    
    
    public function set_password($email) {
        $password = hash('ripemd128', $this->input->post('password', TRUE));
        $data = array (
            'password' => $password,         
        );       
        $this->db->where('email', $email);          
        return $this->db->update('super_admins', $data);
    }
    
    
    public function update_pass_reset_code($super_admin_id, $pass_reset_code) {
        $data = array (
            'pass_reset_code' => $pass_reset_code        
        );       
        $this->db->where('id', $super_admin_id);            
        return $this->db->update('super_admins', $data);
    }
    
    
    public function validate_pass_reset_code($super_admin_id, $pass_reset_code) {
        //validate password reset code
        $code = $this->common_model->get_super_admin_details_by_id($super_admin_id)->pass_reset_code;
        return ($code == $pass_reset_code) ? TRUE : FALSE;
    }
    
    
    public function change_password($super_admin_id) {
        $password = hash('ripemd128', $this->input->post('password', TRUE));
        $data = array (
            'password' => $password,         
            'pass_reset_code' => NULL  //destroy the code
        );       
        $this->db->where('id', $super_admin_id);            
        return $this->db->update('super_admins', $data);
    }
    
        



}