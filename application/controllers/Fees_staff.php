<?php
defined('BASEPATH') or die('Direct access not allowed');



/* ===== Documentation ===== 
Name: Fees_staff
Role: Controller
Description: Fees Class controls access to all fees pages and functions from the staff's end
Model: Fees_model
Author: Nwankwo Ikemefuna
Date Created: 24th June, 2018
*/


class Fees_staff extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->staff_restricted(); //allow only logged in users to access this class
        $this->load->model('fees_model');
        $this->staff_role_restricted('Bursar'); //only staff with this role can access this module
        $this->staff_details = $this->common_model->get_staff_details($this->session->staff_email);
        //get school id
        $this->school_id = $this->staff_details->school_id;
        require_once "application/core/constants/Session.php"; //require session constants
        $this->module_restricted_staff(school_id, mod_fee_management); //fee management module
        $this->activation_restricted_staff(school_id); 

        //get current user 
        $this->c_user = 'staff';

        //module-level scripts
        $this->shared_module_scripts = array('s_fees');
    }




    /* ====== Fee Categories ====== */
    public function fee_categories() {
        $fee_categories = $this->fees_model->get_fee_categories();
        $page_title = 'Fee Categories';
        $inner_page_title = 'Fee Categories (' . count($fee_categories) . ')';
        $this->staff_header($page_title, $inner_page_title);
        $data['fee_categories'] = $fee_categories;
        $this->load->view('shared/fees/fee_categories', $data);
        $this->staff_footer();
    }


    public function new_fee_category_ajax() {   
        $this->form_validation->set_rules('category', 'Category', 'trim|required');
        if ($this->form_validation->run())  {   
            $this->fees_model->new_fee_category();
            echo 1;
        } else { 
            echo validation_errors();
        }
    }


    public function edit_fee_category($fee_cat_id) {
        //check fee details exists for this school
        $this->check_school_data_exists(school_id, $fee_cat_id, 'id', 'fee_categories', 'staff');   
        $this->form_validation->set_rules('category', 'Category', 'required');
        $category = ucwords($this->input->post('category', TRUE)); 
        $y = $this->fees_model->get_fee_category_details($fee_cat_id);
        if ($this->form_validation->run())  {   
            $query = $this->fees_model->check_fee_category_exists($category);
            //ensure subject group does not already exist, or it is same as current subject group
            if ( $query == 0 || ($query > 0 && $y->category == $category) ) {
                $this->fees_model->edit_fee_category($fee_cat_id);
                $this->session->set_flashdata('status_msg', 'Fee category updated succesfully.');
            } else {
                $this->form_validation->set_message('status_msg_error', "{$category} already exists.");
            }
        } else { 
            $this->session->set_flashdata('status_msg_error', 'Error updating fee category.');
        }
        redirect($this->agent->referrer());
    }


    public function delete_fee_category($fee_cat_id) { 
        //check fee exists for this school
        $this->check_school_data_exists(school_id, $fee_cat_id, 'id', 'fee_categories', 'staff');
        $this->fees_model->delete_fee_category($fee_cat_id);
        $this->session->set_flashdata('status_msg', 'Fee category deleted successfully.');
        redirect($this->agent->referrer());
    }




    /* ====== Fee Discount Categories ====== */
    public function fee_discount_categories() {
        $fee_discount_categories = $this->fees_model->get_fee_discount_categories();
        $page_title = 'Fee Discount Categories';
        $inner_page_title = 'Fee Discount Categories (' . count($fee_discount_categories) . ')';
        $this->staff_header($page_title, $inner_page_title);
        $data['fee_discount_categories'] = $fee_discount_categories;
        $this->load->view('shared/fees/fee_discount_categories', $data);
        $this->staff_footer();
    }


    public function new_fee_discount_category_ajax() {  
        $this->form_validation->set_rules('category', 'Category', 'trim|required');
        $this->form_validation->set_rules('discount', 'Discount', 'trim|required');
        if ($this->form_validation->run())  {   
            $this->fees_model->new_fee_discount_category();
            echo 1;
        } else { 
            echo validation_errors();
        }
    }


    public function edit_fee_discount_category($discount_cat_id) {
        //check fee details exists for this school
        $this->check_school_data_exists(school_id, $discount_cat_id, 'id', 'fee_discount_categories', 'staff'); 
        $this->form_validation->set_rules('category', 'Category', 'required');
        $this->form_validation->set_rules('discount', 'Discount', 'trim|required');
        if ($this->form_validation->run())  {   
            $category = ucwords($this->input->post('category', TRUE)); 
            $y = $this->fees_model->get_fee_discount_category_details($discount_cat_id);
            $query = $this->fees_model->check_fee_discount_category_exists($category);
            //ensure subject group does not already exist, or it is same as current subject group
            if ( $query == 0 || ($query > 0 && $y->category == $category) ) {
                $this->fees_model->edit_fee_discount_category($discount_cat_id);
                $this->session->set_flashdata('status_msg', 'Discount category updated succesfully.');
            } else {
                $this->form_validation->set_message('status_msg_error', "{$category} already exists.");
            }
        } else { 
            $this->session->set_flashdata('status_msg_error', 'Error updating fee category.');
        }
        redirect($this->agent->referrer());
    }


    public function fee_discount_category($discount_cat_id) {
        $this->check_school_data_exists(school_id, $discount_cat_id, 'id', 'fee_discount_categories', 'staff');
        $y = $this->fees_model->get_fee_discount_category_details($discount_cat_id);
        $page_title = 'Discount Category: ' . $y->category;
        $this->staff_header($page_title, $page_title);
        $students_using_discount = $this->fees_model->get_all_students_on_fee_discount($discount_cat_id);
        $data['category'] = $y->category;
        $data['discount'] = $y->discount;
        $data['discount_cat_id'] = $discount_cat_id;
        $fee_discount_categories = $this->fees_model->get_fee_discount_categories();
        $data['fee_discount_categories'] = $fee_discount_categories;
        $data['students_using_discount'] = $students_using_discount;
        $this->load->view('shared/fees/fee_discount_category', $data);
        $this->staff_footer();
    }


    public function delete_fee_discount_category($discount_cat_id) { 
        //check fee discount exists for this school
        $this->check_school_data_exists(school_id, $discount_cat_id, 'id', 'fee_discount_categories', 'staff');
        $this->fees_model->delete_fee_discount_category($discount_cat_id);
        $this->session->set_flashdata('status_msg', 'Discount category deleted successfully.');
        redirect($this->agent->referrer());
    }


    public function search_students_for_fee_discount_ajax() {
        $keyword = $this->input->post('keyword');
        $data = $this->fees_model->search_students_for_fee_discount_apply($keyword);       
        echo json_encode($data);
    }


    public function get_student_class_ajax($student_id) {   
        $y = $this->common_model->get_student_details_by_id($student_id);
        $class_id = $y->class_id;
        $class_details = $this->common_model->get_class_details($class_id);
        if ($class_details) {
            $data = array(
                'found' => 'true', 
                'student_class' => $class_details->class,
            );
            echo json_encode($data);
        } else {
            $data = array(
                'found' => 'false',
            );
            echo json_encode($data);
        }
    }


    public function apply_fee_discount_to_student($discount_cat_id, $student_id) { 
        $this->check_school_data_exists(school_id, $discount_cat_id, 'id', 'fee_discount_categories', 'staff');
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'staff');
        $this->fees_model->apply_fee_discount_to_student($discount_cat_id, $student_id);
        $this->session->set_flashdata('status_msg', 'Discount category applied to student successfully.');
        redirect($this->agent->referrer());
    }


    public function remove_student_from_fee_discount($student_id) { 
        //check student exists for this school
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'staff');
        $this->fees_model->remove_student_from_fee_discount($student_id);
        $this->session->set_flashdata('status_msg', 'Student removed from discount successfully.');
        redirect($this->agent->referrer());
    }


    public function change_student_fee_discount_category($student_id) {
        //check student exists for this school
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'staff'); 
        $this->form_validation->set_rules('fee_discount_cat_id', 'Discount Category', 'required');
        if ($this->form_validation->run())  {   
            $this->fees_model->change_student_fee_discount_category($student_id);
            $this->session->set_flashdata('status_msg', 'Discount category updated succesfully.');
        } else { 
            $this->session->set_flashdata('status_msg_error', 'Error updating discount category.');
        }
        redirect($this->agent->referrer());
    }






    /* ====== Manage Fees ====== */
    public function manage_fees() {
        $page_title = 'Manage Fees (Current Term)';
        $this->staff_header($page_title, $page_title);
        $session = current_session_slug;
        $term = current_term;   
        $data['the_session'] = current_session;
        $data['term'] = $term;
        $data['fee_details'] = $this->fees_model->get_term_fee_details($session, $term);
        $data['fee_sessions'] = $this->fees_model->get_school_fee_sessions();
        $data['sections'] = $this->common_model->get_sections(school_id);
        $data['fee_categories'] = $this->fees_model->get_fee_categories();
        $this->load->view('shared/fees/manage_fees', $data);
        $this->staff_footer();
    }


    public function new_class_fees() {
        $page_title = 'New Class Fees (Current Term)';
        $this->staff_header($page_title, $page_title);
        $session = current_session_slug;
        $term = current_term;   
        $data['the_session'] = current_session;
        $data['term'] = $term;
        $data['fee_details'] = $this->fees_model->get_term_fee_details($session, $term);
        $data['fee_sessions'] = $this->fees_model->get_school_fee_sessions();
        $data['sections'] = $this->common_model->get_sections(school_id);
        $data['fee_categories'] = $this->fees_model->get_fee_categories();
        $this->load->view('shared/fees/new_class_fees', $data);
        $this->staff_footer();
    }


    public function new_class_fees_ajax() { 
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required');
        $this->form_validation->set_rules('amount[]', 'Amount', 'trim|is_natural');
        $session = current_session_slug;
        $term = current_term;
        $class_id = $this->input->post('class_id', TRUE); 
        $amount = $this->input->post('amount', TRUE); 
        $fee_cat_id = $this->input->post('fee_cat_id', TRUE); 
        
        if ($this->form_validation->run())  {   
            $query = $this->fees_model->check_fee_details_exist($session, $term, $class_id);

            if ($query > 0) {
                $class = $this->common_model->get_class_details($class_id)->class;
                echo "Fees already set for {$class} for the current term.";
            } else {

                $term_fees = [];
                $total_fees_payable = 0;
                $total_amount_fields_selected = 0;
                for ($i = 0; $i < count($fee_cat_id); $i++) {
                    //check if amount was entered, ignore otherwise
                    if ($amount[$i] != '') {
                        $d_fee_cat_id = $fee_cat_id[$i];
                        $d_amount = $amount[$i];
                        //concatenate fees in the format fee_cat_id:amount, ++
                        $term_fees[] = $d_fee_cat_id.':'.$d_amount;
                        //accumulate amount
                        $total_fees_payable += $d_amount; 
                        //do this to ensure at least 1 amount field has data 
                        $total_amount_fields_selected++;
                    }
                }

                if ($total_amount_fields_selected > 0) {
                    $term_fees = implode(', ', $term_fees);
                    $this->fees_model->add_new_class_fees($class_id, $term_fees, $total_fees_payable); 
                    echo 1;
                } else { //no amount field has data
                    echo 'Amount must be specified for at least 1 fee category';
                }
            }

        } else { 
            echo validation_errors();
        }
    }


    public function edit_class_fees($fee_id) {
        //check fee details exists for this school
        $this->check_school_data_exists(school_id, $fee_id, 'id', 'fee_details', 'staff');  
        $fee_details = $this->fees_model->get_fee_details($fee_id);
        $class_id = $fee_details->class_id;
        $class = $this->common_model->get_class_details($class_id)->class;
        $page_title = 'Edit Fees: ' . $class;
        $this->staff_header($page_title, $page_title);
        $data['y'] = $fee_details;
        $data['class'] = $class;
        $data['fee_categories'] = $this->fees_model->get_fee_categories();
        $this->load->view('shared/fees/edit_class_fees', $data);
        $this->staff_footer();
    }


    public function edit_class_fees_ajax($fee_id) {
        //check fee details exists for this school
        $this->check_school_data_exists(school_id, $fee_id, 'id', 'fee_details', 'staff');  
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required');
        $this->form_validation->set_rules('amount[]', 'Amount', 'trim|is_natural');
        $session = current_session_slug;
        $term = current_term;
        $class_id = $this->input->post('class_id', TRUE); 
        $amount = $this->input->post('amount', TRUE); 
        $fee_cat_id = $this->input->post('fee_cat_id', TRUE); 
        
        if ($this->form_validation->run())  {   

            $term_fees = [];
            $total_fees_payable = 0;
            $total_amount_fields_selected = 0;
            for ($i = 0; $i < count($fee_cat_id); $i++) {
                //check if amount was entered, ignore otherwise
                if ($amount[$i] != '') {
                    $d_fee_cat_id = $fee_cat_id[$i];
                    $d_amount = $amount[$i];
                    //concatenate fees in the format fee_cat_id:amount, ++
                    $term_fees[] = $d_fee_cat_id.':'.$d_amount;
                    //accumulate amount
                    $total_fees_payable += $d_amount; 
                    //do this to ensure at least 1 amount field has data 
                    $total_amount_fields_selected++;
                }
            }

            if ($total_amount_fields_selected > 0) {
                $term_fees = implode(', ', $term_fees);
                $this->fees_model->edit_class_fees($fee_id, $term_fees, $total_fees_payable); 
                echo 1;
            } else { //no amount field has data
                echo 'Amount must be specified for at least 1 fee category';
            }

        } else { 
            echo validation_errors();
        }
    }


    public function import_fees_ajax() {    
        $this->form_validation->set_rules('session', 'Session', 'trim|required');
        $this->form_validation->set_rules('term', 'Term', 'trim|required');
        if ($this->form_validation->run() === FALSE)  {
            echo validation_errors();
        } else {

            $session = $this->input->post('session', TRUE); 
            $term = $this->input->post('term', TRUE); 
            //fetch fees for this session and term
            $term_fees = $this->fees_model->get_term_fee_details($session, $term);
            
            //check if fee details exists for this session and term
            if ( count($term_fees) === 0) {
                echo 'No fee details found for the selected session and term';
            } else {

                foreach ($term_fees as $f) {
                    $term_fees = $f->term_fees;
                    $total_fees_payable = $f->total_fees_payable;
                    $class_id = $f->class_id;
                    $class = $this->common_model->get_class_details($class_id)->class;
                    //check if fees already exist for this class in the current session and term
                    $current_session = current_session_slug;
                    $current_term = current_term;
                    $query = $this->fees_model->get_fee_details_by_class($current_session, $current_term, $class_id);
                    if ($query) {
                        $existing_fee_id = $query->id;
                        //do update
                        $this->fees_model->import_fees_update($existing_fee_id, $class_id, $term_fees, $total_fees_payable);
                    } else {
                        //do insert
                        $this->fees_model->import_fees_insert($class_id, $term_fees, $total_fees_payable);
                    }
                }
                echo 1;

            }
        }
    }


    public function delete_fee_details($fee_id) { 
        //check fee exists for this school
        $this->check_school_data_exists(school_id, $fee_id, 'id', 'fee_details', 'staff');
        $this->fees_model->delete_fee_details($fee_id);
        $this->session->set_flashdata('status_msg', 'Fee details deleted successfully.');
        redirect($this->agent->referrer());
    }


    public function clear_fee_details() { 
        $this->fees_model->clear_fee_details();
        $this->session->set_flashdata('status_msg', 'Fee details cleared successfully.');
        redirect($this->agent->referrer());
    }







    /* ========== Select Class ========== */
    public function select_class_collect_fees() { //select class in modal in header: current term
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required');
        $class_id = $this->input->post('class_id', TRUE);
        if ($this->form_validation->run())  {
            //redirect to the collect fees page of the selected class
            redirect(site_url($this->c_controller.'/collect_fees/'.$class_id));
        } else {
            $this->session->set_flashdata('status_msg_error', 'Something went wrong!');
            redirect($this->agent->referrer());
        }
    }


    public function select_class_fee_payment() { //select class in modal in header: current session and term
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required');
        $redirect_method = $this->input->post('redirect_method', TRUE);
        $session = current_session_slug;
        $term = current_term;
        $class_id = $this->input->post('class_id', TRUE);
        $uri_segment = $redirect_method.'/'.$session.'/'.$term.'/'.$class_id;
        if ($this->form_validation->run())  {
            //redirect to the appropriate page of the selected class
            redirect(site_url($this->c_controller.'/'.$uri_segment));
        } else {
            $this->session->set_flashdata('status_msg_error', 'Something went wrong!');
            redirect($this->agent->referrer());
        }
    }


    public function select_archive_class_fee_payment() { //select class in modal in header: archive
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required');
        $this->form_validation->set_rules('session', 'Session', 'trim|required');
        $this->form_validation->set_rules('term', 'Term', 'trim|required');
        $redirect_method = $this->input->post('redirect_method', TRUE);
        $session = $this->input->post('session', TRUE);
        $term = $this->input->post('term', TRUE);
        $class_id = $this->input->post('class_id', TRUE);
        $uri_segment = $redirect_method.'/'.$session.'/'.$term.'/'.$class_id;
        if ($this->form_validation->run())  {
            //redirect to the appropriate page of the selected class
            redirect(site_url($this->c_controller.'/'.$uri_segment));
        } else {
            $this->session->set_flashdata('status_msg_error', 'Something went wrong!');
            redirect($this->agent->referrer());
        }
    }


    public function select_archive_payment_summary() { 
        $this->form_validation->set_rules('session', 'Session', 'trim|required');
        $this->form_validation->set_rules('term', 'Term', 'trim|required');
        $session = $this->input->post('session', TRUE);
        $term = $this->input->post('term', TRUE);
        if ($this->form_validation->run())  {
            //redirect to the collect fees page of the selected class
            redirect(site_url($this->c_controller.'/payment_summary/'.$session.'/'.$term));
        } else {
            $this->session->set_flashdata('status_msg_error', 'Something went wrong!');
            redirect($this->agent->referrer());
        }
    }


    
    public function collect_fees($class_id) {
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
        $class = $this->common_model->get_class_details($class_id)->class;
        $page_title = "Collect Fees: {$class}";
        $this->staff_header($page_title, $page_title);
        $session = current_session_slug;    
        $term = current_term;
        //stats: class  
        $data = $this->fees_model->fees_data($session, $term, $class_id);
        $this->load->view('shared/fees/collect_fees', $data);
        $this->staff_footer();
    }
    
    
    public function collect_fees_ajax($class_id) {
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
        $this->load->model('ajax/shared/fees/collect_fees_model_ajax', 'current_model'); //from students table
        $list = $this->current_model->get_records($class_id);
        $data = array();
        foreach ($list as $y) {
            $session = current_session_slug;    
            $term = current_term;
            $student_id = $y->id;
            $class_id = $this->common_model->get_student_details_by_id($student_id)->class_id;  
            $row = array(); 
            $row[] = checkbox_bulk_action($student_id);
            $row[] = $this->current_model->options($student_id) . $this->current_model->modals($session, $term, $class_id, $student_id);
            $row[] = $y->admission_id;
            $row[] = $this->common_model->get_student_fullname($student_id);
            $row[] = $this->fees_model->student_discount_category($student_id);
            $row[] = $this->fees_model->get_amount_payable_by_student($session, $term, $class_id, $student_id);
            $row[] = $this->current_model->payment_status($session, $term, $class_id, $student_id);
            $row[] = $this->current_model->fees_amount_paid($session, $term, $class_id, $student_id);
            $row[] = $this->current_model->fees_balance($session, $term, $class_id, $student_id);
            $row[] = $this->current_model->get_fees_date_paid($session, $term, $class_id, $student_id);
            $row[] = $this->current_model->get_fee_transaction_id($session, $term, $class_id, $student_id);
            $row[] = $this->common_model->get_suspension_status($student_id);
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->current_model->count_all_records($class_id),
            "recordsFiltered" => $this->current_model->count_filtered_records($class_id),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    public function full_payment($session, $term, $class_id) {
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
        $class = $this->common_model->get_class_details($class_id)->class;
        $page_title = "Full Payment: {$class}";
        $this->staff_header($page_title, $page_title);
        $data = $this->fees_model->fees_data($session, $term, $class_id);
        $this->load->view('shared/fees/full_payment', $data);
        $this->staff_footer();
    }
    
    
    public function full_payment_ajax($session, $term, $class_id) {
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
        $this->load->model('ajax/shared/fees/full_payment_model_ajax', 'current_model'); //from students table
        $list = $this->current_model->get_records($session, $term, $class_id);
        $data = array();
        foreach ($list as $y) {
            $payment_id = $y->id;
            $class_id = $y->class_id;
            $student_id = $y->student_id;
            $admission_id = $this->common_model->get_student_details_by_id($student_id)->admission_id;
            $row = array(); 
            $row[] = checkbox_bulk_action($payment_id);
            $row[] = $this->current_model->options($payment_id) . $this->current_model->modals($payment_id);
            $row[] = $admission_id;
            $row[] = $this->common_model->get_student_fullname($student_id);
            $row[] = $this->fees_model->student_discount_category($student_id);
            $row[] = $this->fees_model->get_amount_payable_by_student($session, $term, $class_id, $student_id);
            $row[] = $this->current_model->fees_amount_paid($payment_id);
            $row[] = $this->current_model->get_fees_date_paid($payment_id);
            $row[] = $y->transaction_id;
            $row[] = $this->fees_model->get_validation_info($payment_id);
            $row[] = $this->common_model->get_suspension_status($student_id);
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->current_model->count_all_records($session, $term, $class_id),
            "recordsFiltered" => $this->current_model->count_filtered_records($session, $term, $class_id),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    public function partial_payment($session, $term, $class_id) {
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
        $class = $this->common_model->get_class_details($class_id)->class;
        $page_title = "Partial Payment: {$class}";
        $this->staff_header($page_title, $page_title);
        $data = $this->fees_model->fees_data($session, $term, $class_id);
        $this->load->view('shared/fees/partial_payment', $data);
        $this->staff_footer();
    }
    
    
    public function partial_payment_ajax($session, $term, $class_id) {
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
        $this->load->model('ajax/shared/fees/partial_payment_model_ajax', 'current_model'); //from students table
        $list = $this->current_model->get_records($session, $term, $class_id);
        $data = array();
        foreach ($list as $y) {
            $payment_id = $y->id;
            $class_id = $y->class_id;
            $student_id = $y->student_id;
            $admission_id = $this->common_model->get_student_details_by_id($student_id)->admission_id;
            $view_details = '<div class="pull-right"><a class="underline-link" href="' . base_url($this->c_controller.'/partial_payment_details/'.$payment_id) . '">View details</a></div>';
            $installment = $y->last_installment . $view_details;
            $row = array(); 
            $row[] = checkbox_bulk_action($payment_id);
            $row[] = $this->current_model->options($payment_id) . $this->current_model->modals($payment_id);
            $row[] = $admission_id;
            $row[] = $this->common_model->get_student_fullname($student_id);
            $row[] = $this->fees_model->student_discount_category($student_id);
            $row[] = $this->fees_model->get_amount_payable_by_student($session, $term, $class_id, $student_id);
            $row[] = $installment;
            $row[] = $this->current_model->fees_last_amount_paid($payment_id);
            $row[] = $this->current_model->fees_total_amount_paid($payment_id);
            $row[] = $this->current_model->fees_balance($payment_id);
            $row[] = $this->fees_model->get_partial_validation_info($payment_id);
            $row[] = $this->common_model->get_suspension_status($student_id);
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->current_model->count_all_records($session, $term, $class_id),
            "recordsFiltered" => $this->current_model->count_filtered_records($session, $term, $class_id),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    public function partial_payment_details($payment_id) {
        //check payment exists for this school
        $this->check_school_data_exists(school_id, $payment_id, 'id', 'fee_payment', 'staff');
        $f = $this->fees_model->get_fee_payment_details_by_id($payment_id);
        $session = $f->session;
        $term = $f->term;
        $class_id = $f->class_id;
        $student_id = $f->student_id;
        $class = $this->common_model->get_class_details($class_id)->class;
        $student_name = $this->common_model->get_student_fullname($student_id);
        $page_title = "Partial Payment Details: {$student_name}";
        $this->staff_header($page_title, $page_title);
        $data['payment_id'] = $payment_id;
        $data['student_id'] = $student_id;
        $data['student_name'] = $student_name;
        $data['session'] = $session;
        $data['the_session'] = get_the_session($session);
        $data['term'] = $term;
        $data['class_id'] = $class_id;
        $data['class'] = $class;
        $data['single_installment_details'] = $this->fees_model->get_installment_details($payment_id);
        $data['amount_payable'] = $this->fees_model->get_amount_payable_by_student($session, $term, $class_id, $student_id);
        $data['fees_due_date'] = $this->fees_model->get_fees_due_date();
        $data['fees_overdue'] = $this->fees_model->check_fees_overdue();
        $data['f'] = $f;
        $this->load->view('shared/fees/partial_payment_details', $data);
        $this->staff_footer();
    }


    public function no_payment($session, $term, $class_id) {
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
        $class = $this->common_model->get_class_details($class_id)->class;
        $page_title = "No Payment: {$class}";
        $this->staff_header($page_title, $page_title);
        $data = $this->fees_model->fees_data($session, $term, $class_id);
        $this->load->view('shared/fees/no_payment', $data);
        $this->staff_footer();
    }
    
    
    public function no_payment_ajax($session, $term, $class_id) {
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
        $this->load->model('ajax/shared/fees/no_payment_model_ajax', 'current_model'); //from students table
        $list = $this->current_model->get_records($session, $term, $class_id);
        $data = array();
        foreach ($list as $y) {
            $payment_id = $y->id;
            $class_id = $y->class_id;
            $student_id = $y->student_id;
            $admission_id = $this->common_model->get_student_details_by_id($student_id)->admission_id;
            $row = array(); 
            $row[] = $this->current_model->options($payment_id) . $this->current_model->modals($payment_id);
            $row[] = $admission_id;
            $row[] = $this->common_model->get_student_fullname($student_id);
            $row[] = $this->fees_model->student_discount_category($student_id);
            $row[] = $this->fees_model->get_amount_payable_by_student($session, $term, $class_id, $student_id);
            $row[] = $y->status;
            $row[] = NULL;
            $row[] = NULL;
            $row[] = NULL;
            $row[] = NULL;
            $row[] = $this->common_model->get_suspension_status($student_id);
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->current_model->count_all_records($session, $term, $class_id),
            "recordsFiltered" => $this->current_model->count_filtered_records($session, $term, $class_id),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }



    
    /* ====== Payment Summary ====== */
    public function payment_summary($session, $term) {
        $the_session = get_the_session($session);
        $page_title = "Payment Summary: {$the_session}, {$term} Term";
        $this->staff_header($page_title, $page_title);
        $data['fee_details'] = $this->fees_model->get_term_fee_details($session, $term);
        $data['sections'] = $this->common_model->get_sections(school_id);
        $data['classes'] = $this->common_model->get_classes(school_id);
        $data['session'] = $session;
        $data['the_session'] = $the_session;
        $data['term'] = $term;
        //stats: all
        $data['total_full_payment'] = count($this->fees_model->get_full_payment($session, $term));
        $data['total_partial_payment'] = count($this->fees_model->get_partial_payment($session, $term));
        $data['total_no_payment'] = $this->fees_model->get_no_payment($session, $term);
        $data['total_amount'] = $this->fees_model->get_total_amount_paid($session, $term);
        $data['total_amount_expected'] = $this->fees_model->get_total_amount_expected($session, $term);
        $data['school_population'] = count($this->common_model->get_students_list_with_suspended(school_id));
        $this->load->view('shared/fees/payment_summary', $data);
        $this->staff_footer();
    }
    
    
    public function approve_full_payment($session, $term, $class_id, $student_id) { 
        //check student exists for this school
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'staff');
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff');
        $this->form_validation->set_rules('transaction_id', 'Transaction/Payment ID', 'trim|required');
        $this->form_validation->set_rules('date_paid', 'Date Paid', 'trim|required');
        $class = $this->common_model->get_class_details($class_id)->class;
        $student_name = $this->common_model->get_student_fullname($student_id); 
        if ($this->form_validation->run())  {   
            //check if the fees for the class has been set
            $query = $this->fees_model->check_fee_details_exist($session, $term, $class_id, $student_id);
            if ($query) {
                $this->fees_model->approve_full_payment($session, $term, $class_id, $student_id);
                $this->session->set_flashdata('status_msg', "Full payment approved successfully for {$student_name}.");
            } else {
                $this->session->set_flashdata('status_msg_error', "No fee data found for {$class} class.");
            }

        } else {
            $this->session->set_flashdata('status_msg_error', 'Error approving payment.');
        }
        redirect($this->agent->referrer());
    }
    
    
    public function approve_partial_payment($session, $term, $class_id, $student_id) {
        //check student exists for this school
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'staff');
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff'); 
        $this->form_validation->set_rules('transaction_id', 'Transaction/Payment ID', 'trim|required');
        $this->form_validation->set_rules('fees_amount_paid', 'Amount Paid', 'trim|required|is_natural');
        $this->form_validation->set_rules('date_paid', 'Date Paid', 'trim|required');
        $amount_paid = s_currency_symbol . number_format($this->input->post('fees_amount_paid', TRUE)); 
        $class = $this->common_model->get_class_details($class_id)->class;
        $student_name = $this->common_model->get_student_fullname($student_id); 
        if ($this->form_validation->run())  {   
            //check if the fees for the class has been set
            $query = $this->fees_model->check_fee_details_exist($session, $term, $class_id, $student_id);
            if ($query) {
                $this->fees_model->approve_partial_payment($session, $term, $class_id, $student_id);
                $this->session->set_flashdata('status_msg', "Payment of {$amount_paid} approved successfully for {$student_name}.");
            } else {
                $this->session->set_flashdata('status_msg_error', "No fee data found for {$class} class.");
            }
            
        } else {
            $this->session->set_flashdata('status_msg_error', 'Error approving payment.');
        }
        redirect($this->agent->referrer());
    }
    
    
    public function approve_subsequent_partial_payment($session, $term, $class_id, $student_id) { 
        //check student exists for this school
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'staff');
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff'); 
        $this->form_validation->set_rules('transaction_id', 'Transaction/Payment ID', 'trim|required');
        $this->form_validation->set_rules('fees_amount_paid', 'Amount Paid', 'trim|required|is_natural');
        $this->form_validation->set_rules('date_paid', 'Date Paid', 'trim|required');
        $amount_paid = s_currency_symbol . number_format($this->input->post('fees_amount_paid', TRUE)); 
        $class = $this->common_model->get_class_details($class_id)->class;
        $student_name = $this->common_model->get_student_fullname($student_id); 
        if ($this->form_validation->run())  {   
            //check if the fees for the class has been set
            $query = $this->fees_model->check_fee_details_exist($session, $term, $class_id, $student_id);
            if ($query) {
                $this->fees_model->approve_subsequent_partial_payment($session, $term, $class_id, $student_id);
                $this->session->set_flashdata('status_msg', "Payment of {$amount_paid} approved successfully for {$student_name}.");
            } else {
                $this->session->set_flashdata('status_msg_error', "No fee data found for {$class} class.");
            }
            
        } else {
            $this->session->set_flashdata('status_msg_error', 'Error approving payment.');
        }
        redirect($this->agent->referrer());
    }
    
    
    public function unapprove_payment($session, $term, $class_id, $student_id) {
        $payment_details = $this->fees_model->get_payment_details($session, $term, $class_id, $student_id);
        $payment_id = $payment_details->id;
        //ensure payment has not been validated
        $this->fees_model->payment_validation_restricted($payment_id);
        //check student exists for this school
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'staff');
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff'); 
        $this->fees_model->unapprove_payment($session, $term, $class_id, $student_id);
        $student_name = $this->common_model->get_student_fullname($student_id); 
        $this->session->set_flashdata('status_msg', "{$student_name}'s payment unapproved successfully.");
        redirect($this->agent->referrer());
    }
 

    public function mark_fees_unpaid($session, $term, $class_id, $student_id) { 
        $payment_details = $this->fees_model->get_payment_details($session, $term, $class_id, $student_id);
        $payment_id = $payment_details->id;
        //ensure payment has not been validated
        $this->fees_model->payment_validation_restricted($payment_id);
        //check student exists for this school
        $this->check_school_data_exists(school_id, $student_id, 'id', 'students', 'staff');
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff'); 
        $this->fees_model->mark_fees_unpaid($session, $term, $class_id, $student_id);
        $student_name = $this->common_model->get_student_fullname($student_id); 
        $this->session->set_flashdata('status_msg', "{$student_name}'s fees marked unpaid successfully.");
        redirect($this->agent->referrer());
    }
    
    

    public function bulk_actions_fees($session, $term, $class_id) { 
        //check class exists for this school
        $this->check_school_data_exists(school_id, $class_id, 'id', 'classes', 'staff'); 
        $this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
        $selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
        if ($this->form_validation->run()) {
            if ($selected_rows > 0) {
                $this->fees_model->bulk_actions_fees($session, $term, $class_id); 
            } else {
                $this->session->set_flashdata('status_msg_error', 'No item selected.');
            }
        } else {
            $this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
        }
        redirect($this->agent->referrer());
    }
    
    
    
}