<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Installation
Role: Controller
Description: Installation Class controls software installation including free trial, buy (with and without online payment)
Model: installation_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class Installation extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('installation_model');
        $this->paypal_currency_code = $this->config->item('paypal_lib_currency_code'); 
        $this->paypal_logo = $this->config->item('paypal_lib_logo'); 

        //module-level scripts
        $this->site_module_scripts = array('s_installation');
    }

    
    
    /* ===== Free Trial Installation ===== */
    public function free_trial_install($plan_id = NULL) { //route: install/$1
        $this->site_header('Install: Free Trial');
        $query = $this->common_model->get_plan_details($plan_id);
        $plan_id = ($plan_id == NULL) ? 1 : $plan_id; //defaults to 1 if plan ID is null
        $plan_id = ($query) ? $plan_id : 1; //defaults to 1 if plan ID segment in URL in not a valid plan ID eg 4
        $plan = $this->common_model->get_plan_details($plan_id)->plan;
        $plans = $this->common_model->get_plans();
        $price = $this->common_model->get_plan_price_by_location($plan_id);
        $captcha_code = mt_rand(111111, 999999); //6-digit code for captcha test
        $data['plans'] = $plans;
        $data['plan_id'] = $plan_id;
        $data['plan'] = $plan;
        $data['price'] = $price;
        $data['captcha_code'] = $captcha_code;
        $this->load->view('site/installation/free_trial_install', $data);
        $this->site_footer();
    }
    
     
    public function free_trial_install_ajax() { 
    
        $login_url = '<a href="' . base_url('login') . '">login</a>';       
        
        //school info
        $this->form_validation->set_rules('plan_id', 'Plan', 'trim|required');
        $this->form_validation->set_rules('mode', 'Mode', 'trim|required');
        $this->form_validation->set_rules('school_name', 'School Name', 'trim|required');
        $this->form_validation->set_rules('school_location', 'School Address', 'trim|required');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        $this->form_validation->set_rules('currency', 'Currency', 'trim|required');
        
        $school_email = $this->input->post('official_mail', TRUE); 
        $this->form_validation->set_rules('official_mail', 'Official Email Address', 'trim|required|valid_email|is_unique[school_info.official_mail]',
            array('is_unique' => "This email address [{$school_email}] is already registered with this application. If it's you, proceed to {$login_url}.")
        );
        
        $this->form_validation->set_rules('telephone_line', 'Official Telephone Line', 'trim|required|max_length[20]');
        $this->form_validation->set_rules('school_website', 'School Website', 'trim');
        $this->form_validation->set_rules('school_motto', 'School Motto', 'trim');
        
        //admin info 
        $this->form_validation->set_rules('admin_name', 'Admin Name', 'trim|required');
        
        $admin_email = $this->input->post('admin_email', TRUE); 
        $this->form_validation->set_rules('admin_email', 'Admin Email Address', 'trim|required|valid_email|is_unique[admins.email]',
            array('is_unique' => "This email address [{$admin_email}] is already registered with this application. If it's you, proceed to {$login_url}, or use a different email address.")
        );
        $this->form_validation->set_rules('admin_phone', 'Admin Phone Number', 'trim|required|max_length[20]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('c_password', 'Password', 'required|matches[password]', 
            array('matches' => 'Passwords do not match')
        );
        $this->form_validation->set_rules('referrer', 'How did you hear about us', 'trim|required');
        $this->form_validation->set_rules('captcha', 'Captcha', 'trim');
        $this->form_validation->set_rules('c_captcha', 'Captcha Code', 'required|exact_length[6]|matches[captcha]', 
            array('matches' => 'Incorrect captcha code')
        );

        if ($this->form_validation->run())  {       
            $this->installation_model->free_trial_install();
            echo 1;
        } else { 
            echo validation_errors();
        }
    } 



    /* ===== Buy and Install (PayPal) ===== */
    public function buy_install($plan_id = NULL) { //route: buy/$1
        //check plan ID exists
        $this->site_header('Buy & Install Software with PayPal');
        $query = $this->common_model->get_plan_details($plan_id);
        $plan_id = ($plan_id == NULL) ? 1 : $plan_id; //defaults to 1 if plan ID is null
        $plan_id = ($query) ? $plan_id : 1; //defaults to 1 if plan ID segment in URL in not a valid plan ID eg 4
        $plan = $this->common_model->get_plan_details($plan_id)->plan;
        $plans = $this->common_model->get_plans();
        $price = $this->common_model->get_plan_price_by_location($plan_id);
        $captcha_code = mt_rand(111111, 999999);
        $data['plans'] = $plans;
        $data['plan_id'] = $plan_id;
        $data['plan'] = $plan;
        $data['price'] = $price;
        $data['captcha_code'] = $captcha_code; //6-digit code for captcha test
        $data['paypal_logo'] = $this->paypal_logo;
        $data['location'] = ip_info_safe("Visitor", "Country");
        $this->load->view('site/installation/buy_install', $data);
        $this->site_footer();
    }
    
    
    private function payment_data() {  
        //create payment request parameters and values
        $plan_id = $this->input->post('plan_id', TRUE); 
        $plan = $this->common_model->get_plan_details($plan_id)->plan;
        $amount = $this->common_model->get_plan_price_digit_by_location_no_format($plan_id);
        $price = $this->common_model->get_plan_price_by_location($plan_id);
        $currency = $this->common_model->get_currency_letter_by_location(); //either NGN or USD depending on user's location
        
        $admin_name = $this->input->post('admin_name', TRUE); 
        $first_name = get_firstname($admin_name);
        $email = $this->input->post('admin_email', TRUE); 
        $phone = $this->input->post('admin_phone', TRUE); 

        //create array of data
        $data['plan_id'] = $plan_id;
        $data['plan'] = $plan;
        $data['price'] = $price;
        $data['amount'] = $amount;
        $data['currency'] = $currency;
        $data['email'] = $email;
        $data['first_name'] = $first_name;
        $data['phone'] = $phone;
        return $data;
    }
    
    
    public function buy_install_ajax() {    
            
        $login_url = '<a href="' . base_url('login') . '">login</a>';       
        
        //school info
        $this->form_validation->set_rules('plan_id', 'Plan', 'trim|required');
        $this->form_validation->set_rules('mode', 'Mode', 'trim|required');
        $this->form_validation->set_rules('school_name', 'School Name', 'trim|required');
        $this->form_validation->set_rules('school_location', 'School Address', 'trim|required');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        $this->form_validation->set_rules('currency', 'Currency', 'trim|required');
        
        $school_email = $this->input->post('official_mail', TRUE); 
        $this->form_validation->set_rules('official_mail', 'Official Email Address', 'trim|required|valid_email|is_unique[school_info.official_mail]',
            array('is_unique' => "This email address [{$school_email}] is already registered with this application. If it's you, proceed to {$login_url}.")
        );
    
        $this->form_validation->set_rules('telephone_line', 'Official Telephone Line', 'trim|required|max_length[20]');
        $this->form_validation->set_rules('school_website', 'School Website', 'trim');
        $this->form_validation->set_rules('school_motto', 'School Motto', 'trim');

        //admin info 
        $this->form_validation->set_rules('admin_name', 'Admin Name', 'trim|required');
        
        $admin_email = $this->input->post('admin_email', TRUE); 
        $this->form_validation->set_rules('admin_email', 'Admin Email Address', 'trim|required|valid_email|is_unique[admins.email]',
            array('is_unique' => "This email address [{$admin_email}] is already registered with this application. If it's you, proceed to {$login_url} and continue with your payment, or use a different email address.")
        );
        $this->form_validation->set_rules('admin_phone', 'Admin Phone Number', 'trim|required|max_length[20]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('c_password', 'Password', 'required|matches[password]', 
            array('matches' => 'Passwords do not match')
        );
        $this->form_validation->set_rules('referrer', 'How did you hear about us', 'trim|required');
        $this->form_validation->set_rules('captcha', 'Captcha', 'trim');
        $this->form_validation->set_rules('c_captcha', 'Captcha Code', 'required|exact_length[6]|matches[captcha]', 
            array('matches' => 'Incorrect captcha code')
        );

        if ($this->form_validation->run() == FALSE) {
            
            $errors = array(
                'validation_status' => 0,
                'validation_errors' => validation_errors()
            );
            echo json_encode($errors);
            
        } else {
            
            $school_name = ucwords($this->input->post('school_name', TRUE)); 
            $school_location = ucwords($this->input->post('school_location', TRUE)); 
            $country = ucwords($this->input->post('country', TRUE)); 
            $currency_code = ucwords($this->input->post('currency', TRUE)); 
            $official_mail = strtolower($this->input->post('official_mail', TRUE)); 
            $telephone_line = $this->input->post('telephone_line', TRUE); 
            $school_website = $this->input->post('school_website', TRUE); 
            //prepend http:// to website url if not provided
            $school_website = prep_url($school_website);
            $school_motto = ucfirst($this->input->post('school_motto', TRUE)); 
            $referrer = $this->input->post('referrer', TRUE); 

            $plan_id = $this->input->post('plan_id', TRUE); 
            $mode = 'Paid';
            $year_installed = date('Y');

            //activation code 
            $activation_code = $this->installation_model->generate_activation_code($plan_id);
            
            $insert_data = array (
                'school_name'           =>      $school_name,
                'school_location'       =>      $school_location,
                'country'               =>      $country,
                'currency_code'         =>      $currency_code,
                'official_mail'         =>      $official_mail,
                'telephone_line'        =>      $telephone_line,
                'school_website'        =>      $school_website,
                'school_motto'          =>      $school_motto,
                'referrer'              =>      $referrer,
                'year_installed'        =>      $year_installed,
                'plan_id'               =>      $plan_id,
                'mode'                  =>      $mode,
                'activated'             =>      'false', 
                'activation_code'       =>      $activation_code,
                'show_activation_code'  =>      'false',
                'confirmed'             =>      'false',
            );
            //insert school data
            $school_id = $this->installation_model->buy_install_insert($insert_data);
            
            //create array of data to be jscon_encoded
            $json_data = array(
                "school_id" => $school_id,
                "plan_id" => $plan_id,
                "validation_status" => 1,
            );
            echo json_encode($json_data);
            
        }
    } 
    
    
    public function pay_with_paypal($school_id, $plan_id) {
        // Set variables for paypal form
        $returnURL = base_url('installation/success');
        $cancelURL = base_url('installation/cancel'); 
        $notifyURL = base_url('paypal/ipn_buy'); //IPN listener URL
        
        //get user details
        $item_name = 'Account Activation';
        $amount = $this->common_model->get_plan_details($plan_id)->price_dollar;
        $currency_code = $this->paypal_currency_code;
        
        //Cross Site Request Forgery parameters
        $csrf = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        $csrf_name = $csrf['name'];
        $csrf_value = $csrf['hash'];
        
        // Add fields to PayPal form
        $this->paypal_lib->add_field($csrf_name, $csrf_value); 
        $this->paypal_lib->add_field('return', $returnURL); //NOTE: if set, overrides default return URL specified in PayPal profile
        $this->paypal_lib->add_field('cancel_return', $cancelURL);
        $this->paypal_lib->add_field('notify_url', $notifyURL); //NOTE: if set, overrides default notify URL specified in PayPal profile
        $this->paypal_lib->add_field('item_name', $item_name);
        $this->paypal_lib->add_field('amount',  $amount);
        $this->paypal_lib->add_field('currency_code',  $currency_code);
        $this->paypal_lib->add_field('custom', $school_id); //custom field
         
        // Render paypal form
        $this->paypal_lib->paypal_auto_form();
    }
    
    
    public function success() {
        $this->site_header('Transaction Status');
        // Get the transaction data using PDT-specific variables
        $paypal_return_data     = $this->input->get();
        $school_id              = $paypal_return_data['cm']; //custom message
        $school_name            = $this->common_model->get_school_info($school_id)->school_name;
        $data['school_name']    = $school_name;
        $data['payment_for']    = $paypal_return_data['item_name'];
        $data['txn_id']         = $paypal_return_data["tx"]; //transaction ID
        $data['amount']         = $paypal_return_data["amt"]; //amount
        $data['currency_code']  = $paypal_return_data["cc"]; //currency code
        $data['status']         = $paypal_return_data["st"]; //payment status
        $this->load->view('site/installation/paypal/success', $data);
        $this->site_footer();
    }
    
     
    public function cancel() {
        $this->site_header('Transaction Canceled');
        $data['action_url'] = 'installation/buy_install';
        $this->load->view('site/installation/paypal/cancel', $data);
        $this->site_footer(); 
    }






    /* ===== Buy and Install: With Verification Code ===== */
    public function buy_other_install($plan_id = NULL) { //route: buy_other/$1
        //check plan ID exists
        $this->site_header('Buy & Install Software');
        $query = $this->common_model->get_plan_details($plan_id);
        $plan_id = ($plan_id == NULL) ? 1 : $plan_id; //defaults to 1 if plan ID is null
        $plan_id = ($query) ? $plan_id : 1; //defaults to 1 if plan ID segment in URL in not a valid plan ID eg 4
        $plan = $this->common_model->get_plan_details($plan_id)->plan;
        $plans = $this->common_model->get_plans();
        $price = $this->common_model->get_plan_price_by_location($plan_id);
        $captcha_code = mt_rand(111111, 999999);
        $data['plans'] = $plans;
        $data['plan_id'] = $plan_id;
        $data['plan'] = $plan;
        $data['price'] = $price;
        $data['captcha_code'] = $captcha_code; //6-digit code for captcha test
        $this->load->view('site/installation/buy_other_install', $data);
        $this->site_footer();
    }


    public function buy_other_install_ajax() {  
        $login_url = '<a href="' . base_url('login') . '">login</a>';
        
        //school info
        $this->form_validation->set_rules('plan_id', 'Plan', 'trim|required');
        $this->form_validation->set_rules('mode', 'Mode', 'trim|required');
        $this->form_validation->set_rules('school_name', 'School Name', 'trim|required');
        $this->form_validation->set_rules('school_location', 'School Address', 'trim|required');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        $this->form_validation->set_rules('currency', 'Currency', 'trim|required');
        
        $school_email = $this->input->post('official_mail', TRUE); 
        $this->form_validation->set_rules('official_mail', 'Official Email Address', 'trim|required|valid_email|is_unique[school_info.official_mail]',
            array('is_unique' => "This email address [{$school_email}] is already registered with this application. If it's you, proceed to {$login_url}.")
        );
            
        $this->form_validation->set_rules('telephone_line', 'Official Telephone Line', 'trim|required|max_length[20]');
        $this->form_validation->set_rules('school_website', 'School Website', 'trim');
        $this->form_validation->set_rules('school_motto', 'School Motto', 'trim');
            
        //admin info 
        $this->form_validation->set_rules('admin_name', 'Admin Name', 'trim|required');
        
        $admin_email = $this->input->post('admin_email', TRUE); 
        $this->form_validation->set_rules('admin_email', 'Admin Email Address', 'trim|required|valid_email|is_unique[admins.email]',
            array('is_unique' => "This email address [{$admin_email}] is already registered with this application. If it's you, proceed to {$login_url} and continue with your payment, or use a different email address.")
        );
        $this->form_validation->set_rules('admin_phone', 'Admin Phone Number', 'trim|required|max_length[20]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('c_password', 'Password', 'required|matches[password]', 
            array('matches' => 'Passwords do not match')
        );
        $this->form_validation->set_rules('referrer', 'How did you hear about us', 'trim|required');
        $this->form_validation->set_rules('captcha', 'Captcha', 'trim');
        $this->form_validation->set_rules('c_captcha', 'Captcha Code', 'required|exact_length[6]|matches[captcha]', 
            array('matches' => 'Incorrect captcha code')
        );

        if ($this->form_validation->run())  {       
            $this->installation_model->buy_other_install();
            echo 1;
        } else { 
            echo validation_errors();
        }
    } 






    /* ===== Confirm Account ===== */
    public function email_confirmation($school_id, $confirm_code) {
        //ensure school id exists
        $this->installation_model->check_school_id_exists($school_id);
        //check confirm status
        $this->installation_model->check_confirm_status($school_id);
        $this->login_header('Email Confirmation');
        $data['school_id'] = $school_id;
        $data['school_name'] = $this->common_model->get_school_info($school_id)->school_name;
        $data['confirm_code'] = $confirm_code;
        $data['valid_code'] = $this->installation_model->validate_confirm_code($school_id, $confirm_code);
        $this->load->view('site/installation/email_confirmation', $data);
        $this->login_footer();
    }


    public function confirm_school_account_ajax($school_id, $confirm_code) { 
        //set validation rules
        $this->form_validation->set_rules('school_id', 'School ID', 'trim');
        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
        } else {
            $valid_code = $this->installation_model->validate_confirm_code($school_id, $confirm_code);
            if ($valid_code) {
                $this->installation_model->confirm_school_account($school_id);
                echo 1;
            } else {
                echo "Invalid confirmation code!";
            }
        }
    }


    public function resend_confirmation_ajax($school_id) { 
        //set validation rules
        $this->form_validation->set_rules('school_id', 'School ID', 'trim');
        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
        } else {
            $this->installation_model->resend_confirmation($school_id);
            echo 1;
        }
    }
    


    
    
}