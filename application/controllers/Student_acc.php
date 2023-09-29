<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Student_acc
Role: Controller
Description: controls student account pages such as login, password recovery, etc
Model: Student_acc_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
Date Modified: 21st January, 2019
*/


class Student_acc extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('student_acc_model');

        //module-level scripts
        $this->site_module_scripts = array('s_student_acc');
    }

    
    
    
    public function set_password() {
        $this->site_header('Set Password');
        $this->load->view('site/student/set_password');
        $this->site_footer();
    }


    public function set_password_ajax() {   
        $this->form_validation->set_rules('reg_id', 'Registration ID', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[3]');
        $this->form_validation->set_rules('c_password', 'Password', 'required|matches[password]', 
            array('matches' => 'Passwords do not match')
        );  
        $reg_id = $this->input->post('reg_id', TRUE);
        $reg_id_exists = $this->student_acc_model->check_reg_id_exists($reg_id);
        $y = $this->common_model->get_student_details_by_reg_id($reg_id);
        if ($this->form_validation->run())  {   
        
            if  ( $reg_id_exists ) { //reg_id exists, i.e. user is a staff member
                
                //check if password is null
                if ($y->password == NULL) { //user is yet to set password
                    $this->student_acc_model->set_password($reg_id);
                    echo 1; 
                } else { //user has previously set password, refer to password recovery page
                    $pass_recovery_url = base_url('user_recover_password');
                    $first_name = $y->first_name;
                    echo 'Hi ' . $first_name . ', your student password is already set. If you have forgotten your password, click <a style="color: green" href="' . $pass_recovery_url . '"> here </a> to recover it.';
                }
                
            } else {
                //reg_id not found in team table
                echo 'Password set failed! Registration ID not found.';
            }
            
        } else { //form validation is not successful, display validation errors
            echo validation_errors();
        }
    }
    
    
    public function login_ajax() {
        $this->form_validation->set_rules('reg_id', 'Registration ID', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        $reg_id = $this->input->post('reg_id', TRUE);   
        $password = hash('ripemd128', $this->input->post('password', TRUE));
        $reg_id_exists = $this->student_acc_model->check_reg_id_exists($reg_id);
        
        if ($this->form_validation->run())  {   

            if ($reg_id_exists) {

                $y = $this->common_model->get_student_details_by_reg_id($reg_id);
                $first_name = $y->first_name;
                $school_id = $y->school_id;
                //get school info
                $s = $this->common_model->get_school_info($school_id);
                $confirmed = $s->confirmed;
                        
                if ($y->password == $password && $confirmed != 'false') {
                    //reg_id and password correct and student is active, create session with reg_id and create login session
                    $login_data = array(
                        'student_reg_id' => $reg_id, 
                        'student_loggedin' => TRUE
                    );
                    $this->session->set_userdata($login_data);

                    //success message
                    echo 1;
                    
                } elseif ($y->password == NULL) {   
                    //staff yet to set password
                    $pass_set_url = base_url('student_acc/set_password');
                    echo 'Hi ' . $first_name . ', your password has not been set. Click <a style="color: green" href="' .$pass_set_url. '"> here </a> to set it now.';

                } elseif ($y->password == $password && $confirmed == 'false') { 
                    //school account not confirmed
                    echo "Hi {$first_name}, your school account has not been confirmed. Contact the chief admin of your school.";
                    
                } elseif  ($y->password != $password) {
                     //student supplied wrong password
                    echo "Hi {$first_name}, the password you supplied is incorrect.";

                }
                
            } else {
                //reg ID not found
                echo 'Login failed! Registration ID not found.';
            }
            
        } else { //form validation is not successful
            echo validation_errors();
        }
    }
   
    
    public function recover_password_ajax() {
        $this->form_validation->set_rules('reg_id', 'Registration ID', 'trim|required');
        $this->form_validation->set_rules('pass_reset_code', 'Password Reset Code', 'trim|required|exact_length[4]');
        $this->form_validation->set_rules('password', 'New Password', 'trim|required');
        $this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|matches[password]', 
            array('matches' => 'Passwords do not match')
        );  
        $reg_id = $this->input->post('reg_id', TRUE);
        $pass_reset_code = $this->input->post('pass_reset_code', TRUE);
        $reg_id_exists = $this->student_acc_model->check_reg_id_exists($reg_id);
        
        if ($this->form_validation->run()) {
            if ( ! $reg_id_exists ) {
                echo "This Registration ID [{$reg_id}] does not exist. Please enter the Registration ID that is associated with your student account";          
            } else { 
                $y = $this->common_model->get_student_details_by_reg_id($reg_id);
                $first_name = $y->first_name;
                
                //check if password reset code matches
                if ($pass_reset_code == $y->pass_reset_code) {
                    //update the new password and redirect to login page
                    $this->student_acc_model->change_password($reg_id);
                    echo 1;
                } else {
                    echo "Incorrect password reset code. If you have forgotten your code, contact your school admin for recovery.";
                }
            }
   
        } else {
            echo validation_errors();
        }                                 
    }
    
    
    public function logout() {
        $data = array(
            'student_email', 
            'student_loggedin',
            'demo_student_loggedin', //demo user session
            'demo_super_user_student', //demo super user session
        );
        $this->session->unset_userdata($data);
        redirect(site_url('user_login'));
    }

   

}