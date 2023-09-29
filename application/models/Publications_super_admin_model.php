<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Publications_super_admin_model
Role: Model
Description: Controls the DB processes of publications from super admin panel
Controller: Publications_super_admin
Author: Nwankwo Ikemefuna
Date Created: 22nd January, 2019
*/

class Publications_super_admin_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        //$this->load->model('school_account_model');
    }
    
        
    
    /* ===== Announcement ===== */
    public function get_announcement() { 
        return $this->db->get_where('vendor_announcement', array('id' => 1))->row();
    }


    public function get_published_announcement() { 
        return $this->db->get_where('vendor_announcement', array('id' => 1, 'published' => 'true'))->row();
    }


    public function get_random_demo_admin() { 
        $this->db->limit(1);
        $this->db->order_by('rand()');
        return $this->db->get_where('admins', array('demo_user' => 'true'))->row();
    }


    public function get_account_categories() {
        //NOT IN USE
        $all_users = $this->school_account_model->get_all_schools();
        $lite_users = $this->school_account_model->get_schools_by_plan_id(1);
        $pro_users = $this->school_account_model->get_schools_by_plan_id(2);
        $pro_plus_users = $this->school_account_model->get_schools_by_plan_id(3);
        $free_trial_users = $this->school_account_model->get_free_trial_schools();
        $expired_free_trial_users = $this->school_account_model->get_expired_free_trial_schools();
        $activated_users = $this->school_account_model->get_all_activated_schools();
        $expired_subscription_users = $this->school_account_model->get_expired_annual_subscription_schools();

        $accounts = array(
            'all_users' => $all_users,
            'lite_users' => $lite_users,
            'pro_users' => $pro_users,
            'pro_plus_users' => $pro_plus_users,
            'free_trial_users' => $free_trial_users,
            'expired_free_trial_users' => $expired_free_trial_users,
            'activated_users' => $activated_users,
            'expired_subscription_users' => $expired_subscription_users,
        );
        return $accounts;
    }
    
    
    public function create_announcement() {
        $announcement = ucfirst($this->input->post('announcement', TRUE));
        $data = array (
            'announcement' => $announcement,
        );       
        return $this->db->insert('vendor_announcement', $data);
    } 


    public function update_announcement() {
        $announcement = ucfirst($this->input->post('announcement', TRUE));
        $data = array (
            'announcement' => $announcement,
            'date' => date('Y-m-d H:i:s'),
        );       
        $this->db->where('id', 1);
        return $this->db->update('vendor_announcement', $data);
    } 


    public function publish_announcement() {
        $data = array (
            'published' => 'true',
        );       
        $this->db->where('id', 1);
        return $this->db->update('vendor_announcement', $data);
    } 


    public function unpublish_announcement() {
        $data = array (
            'published' => 'false',
        );       
        $this->db->where('id', 1);
        return $this->db->update('vendor_announcement', $data);
    } 



}