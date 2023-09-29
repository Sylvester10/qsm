<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== */
/*
Name: Fees_model_student
Role: Model
Description: Controls the DB processes of fees from the student's end
Controller: Student
Authors: Nwankwo Ikemefuna
Date Created: 30th August, 2018
*/


class Fees_model_student extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->student_details = $this->common_model->get_student_details_by_reg_id($this->session->student_reg_id);
	}




	private function get_fee_details_by_class() {
		$where = array(
			'school_id' => school_id,
			'class_id' => $this->student_details->class_id,
			'session' => current_session_slug,
			'term' => current_term,
		);
		return $this->db->get_where('fee_details', $where)->row();	
	}


	public function get_total_fees_payable() {
		$query = $this->get_fee_details_by_class();
		//ensure fee details have been added for student's class
		if ($query) {
			$total_fees_payable = s_currency_symbol . number_format($query->total_fees_payable);
		} else {
			$total_fees_payable = NULL;
		}
		return $total_fees_payable;
	}


	public function get_payment_details() {
		$where = array(
			'student_id' => $this->student_details->id,
			'class_id' => $this->student_details->class_id,
			'session' => current_session_slug,
			'term' => current_term,
		);
		$this->db->where($where);
		return $this->db->get('fee_payment')->row();	
	}
	
	
	public function get_installment_details() {
		$f = $this->get_payment_details();
		if ( ! $f ) {
			return NULL;
		} else {
			if ($f->installment_details == NULL) {
				return NULL;
			} else {
				$installment_details = $f->installment_details;
				//installment details is saved as last_installment, amount_paid, transaction_id, date_paid[,]...
				//explode into array and loop through to get details of each installment
				$installment_details = explode("[,]", $installment_details);
				$single_installment = array();
				foreach ($installment_details as $single) {
					$single_installment[] = $single;	
				}

				//explode single installment into array to get details
				$single_installment_details = array();
				foreach ($single_installment as $details) {
					$single_installment_details[] = explode(", ", $details);
				} 
				return $single_installment_details;
			}
		}
	} 

	
	public function payment_status() {
		$query = $this->get_payment_details();
		if ( ! $query ) { //no payment data found for current term for this student
			$status = NULL;;
		} else { //payment data found for current term for this student
			$payment_status = $query->status;
			switch ($payment_status) {
				case 'Full Payment':
					$status = '<b class="text-success">Full Payment</b>';
				break;
				case 'Partial Payment':
					$status = '<b class="text-primary">Partial Payment</b>';
				break;
				default:
					$status = '<b class="text-danger">No Payment</b>';
				break;
			}
		}
		return $status;
	}
	
	
	public function fees_amount_paid() {
		$query = $this->get_payment_details();
		if ( ! $query ) { //no payment data found for current term for this student
			$amount_paid = NULL;
		} else { //payment data found for current term for this student
			$amount_paid = ($query->amount_paid != NULL) ? (s_currency_symbol . number_format($query->amount_paid)) : NULL;
		}
		return $amount_paid;
	}
	
	
	public function fees_balance() {
		$query = $this->get_payment_details();
		if ( ! $query ) { //no payment data found for current term for this student
			$balance = NULL;
		} else { //payment data found for current term for this student
			$balance = ($query->balance == NULL || $query->balance == 'No Balance') ? NULL : (s_currency_symbol . number_format($query->balance));
		}
		return $balance;
	}
	
	
	public function get_fees_date_paid() {
		$query = $this->get_payment_details();
		if ( ! $query ) { //no payment data found for current term for this student
			$date_paid = NULL;
		} else { //payment data found for current term for this student
			$date_paid = x_date($query->date_paid);
		}
		return $date_paid;
	}


	public function get_fee_transaction_id() {
		$query = $this->get_payment_details();
		if ( ! $query ) { //no payment data found for current term for this student
			$transaction_id = NULL;
		} else { //payment data found for current term for this student
			$transaction_id = $query->transaction_id;
		}
		return $transaction_id;
	}
	
	
	public function get_fee_last_installment() {
		$query = $this->get_payment_details();
		if ( ! $query ) { //no payment data found for current term for this student
			$last_installment = NULL;
		} else { //payment data found for current term for this student
			$last_installment = $query->last_installment;
		}
		return $last_installment;
	}

	
}