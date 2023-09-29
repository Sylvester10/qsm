<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Super_admin_acc
Role: Controller
Description: controls super admin account pages such as login, password recovery, etc
Model: Super_admin_acc_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
Date Modified: 21st January, 2019
*/


class Super_admin_acc extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('super_admin_acc_model');

        //module-level scripts
        $this->site_module_scripts = array('s_super_admin_acc');
    }

    
    
    
    public function set_password() {
        $this->site_header('Set Password');
        $this->load->view('site/super_admin/set_password');
        $this->site_footer();
    }
    
    
    public function set_password_ajax() {   
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('c_password', 'Password', 'required|matches[password]', 
            array('matches' => 'Passwords do not match')
        );  
        $email = $this->input->post('email', TRUE);
        $email_exists = $this->super_admin_acc_model->check_email_exists($email);
        $y = $this->common_model->get_super_admin_details($email);
        if ($this->form_validation->run())  {   
        
            if  ( $email_exists ) { //email exists, i.e. user is a staff member
                
                //check if password is null
                if ($y->password == NULL) { //user is yet to set password
                    $this->super_admin_acc_model->set_password($email);
                    echo 1; 
                } else { //user has previously set password, refer to password recovery page
                    $pass_recovery_url = base_url('super_admin_acc/recover_password');
                    $first_name = get_firstname($y->name);
                    echo 'Hi ' . $first_name . ', your Super Admin password is already set. If you have forgotten your password, click <a style="color: green" href="' . $pass_recovery_url . '"> here </a> to recover it.';
                }
                
            } else {
                //email not found in team table
                echo 'Password set failed! Email not found.';
            }
            
        } else { //form validation is not successful, display validation errors
            echo validation_errors();
        }
    }


    public function login() {
        $this->site_header('Super Admin Login');
        $this->load->view('site/super_admin/login');
        $this->site_footer();
    }
    
    
    public function login_ajax() {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        $email = $this->input->post('email', TRUE); 
        $password = hash('ripemd128', $this->input->post('password', TRUE));
        $email_exists = $this->super_admin_acc_model->check_email_exists($email);
        $y = $this->common_model->get_super_admin_details($email);
        
        if ($this->form_validation->run())  {   
                        
            if ( $email_exists && $y->password == $password && $y->active != 'false' ) {
                //email and password correct and super admin is active, create session with email and create login session
                $login_data = array(
                    'super_admin_email' => $email, 
                    'super_admin_loggedin' => TRUE
                );
                $this->session->set_userdata($login_data);
                echo 1;
                
            } elseif ( $email_exists && $y->password == NULL ) {    
                //staff yet to set password
                $pass_set_url = base_url('super_admin_acc/set_password');
                $first_name = get_firstname($y->name);
                echo 'Hi ' .$first_name. ', your Super Admin password has not been set. Click <a style="color: green" href="' .$pass_set_url. '"> here </a> to set it now.';
                
            } elseif ( $email_exists && $y->password == $password && $y->active == 'false') {   
                //super_admin no longer active
                $first_name = get_firstname($y->name);
                echo "Hi {$first_name}, you are no longer active. Please contact site administrator.";
                
            } elseif  ( $email_exists && $y->password != $password ) {
                 //super_admin supplied wrong password
                $first_name = get_firstname($y->name);
                echo "Hi {$first_name}, the password you supplied is incorrect.";
                
            } elseif ( ! $email_exists ) {
                //email not found
                echo 'Login failed! Email not found.';
            }
            
        } else { //form validation is not successful
            echo validation_errors();
        }
    }


    public function recover_password() {
        $this->site_header('Password Recovery');
        $this->load->view('site/super_admin/recover_password');
        $this->site_footer();
    }
   
    
    public function recover_password_ajax() {
        $this->form_validation->set_rules('email', ' Email', 'trim|required|valid_email');
        $email = $this->input->post('email', TRUE);
        $email_exists = $this->super_admin_acc_model->check_email_exists($email);
        
        if ($this->form_validation->run()) {
            if ( ! $email_exists ) {
                echo "This email address [{$email}] does not exist as a Super Admin";           
            } else { 
                $y = $this->common_model->get_super_admin_details($email);
                $super_admin_id = $y->id;
                $first_name = get_firstname($y->name);
                $subject = 'Super Admin Password Reset';
                $pass_reset_code = hash('ripemd128', mt_rand(100000000000, 999999999999));
                $pword_reset_url = base_url('super_admin_acc/change_password/'.$super_admin_id.'/'.$pass_reset_code);
                $anchor_link = email_call2action_blue($pword_reset_url, 'Reset Password');
                $message = "Hi <b>{$first_name}!</b> <br />
                You requested for password reset for your Super Admin account. <br />
                Click on the link below to reset your password. <br /> 
                {$anchor_link} <br /> <br /> 
                If you did not make this request, ignore this message. <br /> 
                Please do not reply this message.";
                
                //send email
                if ( email_user_default($email, $subject, $message) ) {
                    //update the password reset code
                    $this->super_admin_acc_model->update_pass_reset_code($super_admin_id, $pass_reset_code);
                    echo 1;
                } else {
                    echo "Error sending mail! Please contact site administrator.";
                }
            }
   
        } else {
            echo validation_errors();
        }                                 
    }
    
    
    public function change_password($super_admin_id, $pass_reset_code) {
        $this->site_header('Password Reset: Super Admin');
        $data['y'] = $this->common_model->get_super_admin_details_by_id($super_admin_id);
        $data['valid_code'] = $this->super_admin_acc_model->validate_pass_reset_code($super_admin_id, $pass_reset_code);
        $this->load->view('site/super_admin/change_password', $data);
        $this->site_footer();
    }
    
   
    public function change_password_ajax($super_admin_id) {
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('c_password', 'Password', 'trim|required|matches[password]', 
            array('matches' => 'Passwords do not match')
        );          
        if ($this->form_validation->run()) {        
            $this->super_admin_acc_model->change_password($super_admin_id);
            echo 1;
        } else {    
            echo validation_errors();
        }
    }
    
    
    public function logout() {
        $data = array(
            'super_admin_email', 
            'super_admin_loggedin'
        );
        $this->session->unset_userdata($data);
        redirect(site_url('super_admin_login'));
    }

   

}