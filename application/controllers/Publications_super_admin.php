<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Publications_super_admin
Role: Controller
Description: Publications_super_admin Class controls publications to school admins from super admin panel
Model: Publications_super_admin_model
Author: Nwankwo Ikemefuna
Date Created: 22nd January, 2019
*/



class Publications_super_admin extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->super_admin_restricted(); //allow only logged in super admins to access this class
        $this->load->model('publications_super_admin_model');
        $this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);

        //module-level scripts
        $this->super_admin_module_scripts = array('s_publications');
    }





    /* ====== Announcement ====== */
    public function announcement() {
        $this->super_admin_header('Announcement', 'Announcement');    
        $data['announcement'] = $this->publications_super_admin_model->get_announcement();
        $data['demo_admin_id'] = $this->publications_super_admin_model->get_random_demo_admin()->id;
        $this->load->view('super_admin/publications/announcement', $data);
        $this->super_admin_footer();
    }


    public function create_announcement_ajax() {    
        $this->form_validation->set_rules('announcement', 'Announcement', 'trim|required');
        if ($this->form_validation->run())  {       
            $this->publications_super_admin_model->create_announcement();
            echo 1; 
        } else { 
            echo validation_errors();
        }
    }
    
    
    public function update_announcement_ajax() {    
        $this->form_validation->set_rules('announcement', 'Announcement', 'trim|required');
        if ($this->form_validation->run())  {       
            $this->publications_super_admin_model->update_announcement();
            echo 1; 
        } else { 
            echo validation_errors();
        }
    }


    public function publish_announcement() { 
        $this->publications_super_admin_model->publish_announcement();
        $this->session->set_flashdata('status_msg', 'Announcement published successfully.');
        redirect($this->agent->referrer());
    }


    public function unpublish_announcement() { 
        $this->publications_super_admin_model->unpublish_announcement();
        $this->session->set_flashdata('status_msg', 'Announcement unpublished successfully.');
        redirect($this->agent->referrer());
    }


}