<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Coupon_model
Role: Model
Description: Controls the DB processes of coupons
Controller: *
Author: Nwankwo Ikemefuna
Date Created: 25th October, 2018
*/

class Coupon_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}




	
	public function get_free_trial_coupons() { 
		return $this->db->get('coupons_free_trial')->result();
	}
	
	
	public function get_free_trial_coupon_by_name($coupon_name) { 
		$this->db->where('name', $coupon_name);
		return $this->db->get('coupons_free_trial')->row();
	}


	public function get_free_trial_coupon_details($coupon_id) { 
		$this->db->where('id', $coupon_id);
		return $this->db->get('coupons_free_trial')->row();
	}


	public function get_free_trial_coupon_details_by_code($coupon_code) { 
		$this->db->where('code', $coupon_code);
		return $this->db->get('coupons_free_trial')->row();
	}


	public function get_school_coupon_status($school_id) { 
		return $this->db->get_where('coupon_users_free_trial', array('school_id' => $school_id))->row();
	}


	public function get_free_trial_coupon_users($coupon_id) { 
		return $this->db->get_where('coupon_users_free_trial', array('coupon_id' => $coupon_id))->result();
	}


	public function get_discounted_price_naira($plan_id, $discount) { 
		$price = $this->common_model->get_plan_details($plan_id)->price_naira;
		$discounted_price = $price - ceil(($discount/100)* $price);
		$discounted_price = number_format($discounted_price);
		$discounted_price = '&#8358;' . $discounted_price;
		return $discounted_price;
	}


	public function get_discounted_price_dollar($plan_id, $discount) { 
		$price = $this->common_model->get_plan_details($plan_id)->price_dollar;
		$discounted_price = $price - ceil(($discount/100)* $price);
		$discounted_price = number_format($discounted_price);
		$discounted_price = '&#36;' . $discounted_price;
		return $discounted_price;
	}


	public function get_plan_discounted_price_digit_by_location($plan_id, $discount) { 
		$p = $this->common_model->get_plan_details($plan_id);
		$price_naira = $p->price_naira;
		$price_dollar = $p->price_dollar;
		$location = ip_info_safe("Visitor", "Country");
		if ($location == 'Nigeria') {
			$price = $price_naira; //Nigerian Naira currency
			$discounted_price = $price - ceil(($discount/100) * $price);
		} else {
			$price = $price_dollar; //Dollar currency 
			$discounted_price = $price - ceil(($discount/100) * $price);
		}
		return $discounted_price; 
	}


	public function get_plan_discounted_price_by_location($plan_id, $discount) { 
		$p = $this->common_model->get_plan_details($plan_id);
		$price_naira = $p->price_naira;
		$price_dollar = $p->price_dollar;
		$location = ip_info_safe("Visitor", "Country");
		if ($location == 'Nigeria') {
			$price = $price_naira; //Nigerian Naira currency
			$discounted_price = $price - ceil(($discount/100) * $price);
			$discounted_price = number_format($discounted_price);
			$discounted_price = '&#8358;' . $discounted_price;
		} else {
			$price = $price_dollar; //Dollar currency 
			$discounted_price = $price - ceil(($discount/100) * $price);
			$discounted_price = number_format($discounted_price);
			$discounted_price = '&#36;' . $discounted_price;
		}
		return $discounted_price; 
	}






	/* Coupon Management by Super Admin */

	public function new_free_trial_coupon() {
		$data = array(
			'name' => ucwords($this->input->post('name', TRUE)),
			'code' => strtoupper($this->input->post('code', TRUE)),
			'discount' => $this->input->post('discount', TRUE),
			'valid_until' => $this->input->post('valid_until', TRUE),
		);
		return $this->db->insert('coupons_free_trial', $data);	
    } 


    public function edit_free_trial_coupon($coupon_id) {
		$data = array(
			'name' => ucwords($this->input->post('name', TRUE)),
			'discount' => $this->input->post('discount', TRUE),
			'valid_until' => $this->input->post('valid_until', TRUE),
		);
		$this->db->where('id', $coupon_id);
		return $this->db->update('coupons_free_trial', $data);	
    } 


    public function delete_free_trial_coupon($coupon_id) { 
		$this->db->where('id', $coupon_id);
		return $this->db->delete('coupons_free_trial');
	}


	public function delete_school_free_trial_coupon($school_id) { 
		//check if school has coupon
		$query = $this->get_school_coupon_status($school_id);
		if ($query) {
			$this->db->where('school_id', $school_id);
			return $this->db->delete('coupon_users_free_trial');
		} else {
			return FALSE;
		}
	}


	public function delete_school_free_trial_coupon_on_expire($school_id) { 
		//check if school has coupon
		$query = $this->get_school_coupon_status($school_id);
		if ($query) {
			$coupon_id = $query->coupon_id;
			$coupon_details = $this->coupon_model->get_free_trial_coupon_details($coupon_id);
			$discount = $coupon_details->discount;
			$valid_until = $coupon_details->valid_until;
			$now = date('Y-m-d H:i:s');
			if ($valid_until < $now) { //coupon has expired
				$this->db->where('school_id', $school_id);
				return $this->db->delete('coupon_users_free_trial');
			}
		} else {
			return FALSE;
		}
	}



}