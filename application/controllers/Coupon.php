<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Coupon
Role: Controller
Description: Coupons Class helps manage coupons from super admin's end
Model: Coupon_model
Author: Nwankwo Ikemefuna
Date Created: 25th October, 2018
*/



class Coupon extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->super_admin_restricted(); //allow only logged in super admins to access this class
		$this->load->model('coupon_model');
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);

		//module-level scripts
		$this->super_admin_module_scripts = array('s_coupon');
	}




	public function free_trial_coupons() { 
		$this->super_admin_header('Discount Vouchers', 'Discount Vouchers');
		$data['coupons'] = $this->coupon_model->get_free_trial_coupons();
		$data['plans'] = $this->common_model->get_plans();
		$this->load->view('super_admin/coupons/free_trial_coupons', $data);
		$this->super_admin_footer();
	}

	
	public function new_free_trial_coupon_ajax() { 
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('code', 'Code', 'trim|required');
		$this->form_validation->set_rules('discount', 'Discount', 'trim|required');
		$this->form_validation->set_rules('valid_until', 'Valid Until', 'trim|required');
		if ($this->form_validation->run())  {	
			$coupon_code = $this->input->post('code', TRUE);
			$coupon_code = strtoupper($coupon_code);
			//check if coupon code already exists
			$query = $this->coupon_model->get_free_trial_coupon_details_by_code($coupon_code);

			if ( ! $query ) { //coupon code does not exist
				$this->coupon_model->new_free_trial_coupon();
				echo 1;
			} else {
				echo 'Coupon code already exists!';
			}

		} else { 
			echo validation_errors();
		}
	}


	public function edit_free_trial_coupon($coupon_id) { 
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('code', 'Code', 'trim|required');
		$this->form_validation->set_rules('discount', 'Discount', 'trim|required');
		$this->form_validation->set_rules('valid_until', 'Valid Until', 'trim|required');
		if ($this->form_validation->run())  {	
			$this->coupon_model->edit_free_trial_coupon($coupon_id);
			$this->session->set_flashdata('status_msg', 'Coupon edited successfully');
		} else { 
			$this->session->set_flashdata('status_msg_error', 'Something went wrong');
		}
		redirect($this->agent->referrer());
	}


	public function delete_free_trial_coupon($coupon_id) { 
		$this->coupon_model->delete_free_trial_coupon($coupon_id);
		$this->session->set_flashdata('status_msg', 'Coupon deleted successfully.');
		redirect($this->agent->referrer());
	}


	public function delete_school_free_trial_coupon($school_id) { 
		$this->coupon_model->delete_school_free_trial_coupon($school_id);
		$this->session->set_flashdata('status_msg', 'School deleted successfully from coupon list.');
		redirect($this->agent->referrer());
	}



}