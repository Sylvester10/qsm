<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Plan
Role: Controller
Description: Plan Class controls access to all plans and functions
Model: Plan_model
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class Plan extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->admin_restricted(); //allow only logged in users to access this class
		$this->load->model('plan_model');
		$this->admin_details = $this->common_model->get_admin_details($this->session->admin_email);
		//get school id
		$this->school_id = $this->admin_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants

		//module-level scripts
		$this->admin_module_scripts = array('s_plan');
	}




	/* ========== Account Info========== */
	public function account_info() { 
		$this->admin_header('Account Info', 'Account Info');
		$plan_id = school_plan_id;
		$plan = $this->common_model->get_plan_details($plan_id)->plan;
		$price = $this->common_model->get_plan_price_by_location($plan_id);
		$data['plan_id'] = $plan_id;
		$data['plan'] = $plan;
		$data['price'] = $price;
		$this->load->view('admin/plan/account_info', $data);
		$this->admin_footer();
	}



	/* ========== All Plans ========== */
	public function plans() { 
		$this->admin_header('Plans & Features', 'Plans & Features');
		$data['plans'] = $this->common_model->get_plans();
		$data['modules'] = $this->common_model->get_modules()->result();
		$this->load->view('admin/plan/plans', $data);
		$this->admin_footer(); 
	}
	
	
	
	/* ========== Switch Plan ========== */
	public function switch_plan_ajax() { 
		//check if account has not been activated, if true, allow plan change, else redirect to upgrade page
		$this->plan_model->check_status_on_plan_change();
		$this->form_validation->set_rules('plan_id', 'Plan', 'trim|required');
		if ($this->form_validation->run())  {	

			//check if demo user	
			$demo_action_allowed = $this->demo_action_restricted_admin_ajax();
			if ($demo_action_allowed)	{
				$this->plan_model->switch_plan();
				echo 1;
			} else {
				echo $this->demo_action_restricted_msg();
			}	

		} else { 
			echo validation_errors();
		}
	}
	
	



}
	
