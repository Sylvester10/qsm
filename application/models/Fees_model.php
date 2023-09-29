<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== */
/*
Name: Fees_model
Role: Model
Description: Controls the DB processes of fees from the admin/staff's end
Controller: Fees_admin, Fees_staff
Authors: Sylvester Nmakwe, Nwankwo Ikemefuna
Date Created: 11th June, 2018
*/


class Fees_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}




	public function fees_data($session, $term, $class_id) {
		$class = $this->common_model->get_class_details($class_id)->class;
		//stats: class	
		$data['total_class_full_payment'] = count($this->fees_model->get_class_full_payment($session, $term, $class_id));
		$data['total_class_partial_payment'] = count($this->fees_model->get_class_partial_payment($session, $term, $class_id));
		$data['total_class_no_payment'] = $this->fees_model->get_class_no_payment($session, $term, $class_id);
		$data['total_class_validated_full_payment'] = count($this->fees_model->get_class_validated_full_payment($session, $term, $class_id));
		$data['total_class_validated_partial_payment'] = count($this->fees_model->get_class_validated_partial_payment($session, $term, $class_id));
		$data['total_class_amount'] = $this->fees_model->get_class_total_amount_paid($session, $term, $class_id);
		$data['total_class_amount_expected'] = $this->fees_model->get_class_total_amount_expected($session, $term, $class_id);
		//stats: all
		$data['total_full_payment'] = count($this->fees_model->get_full_payment($session, $term));
		$data['total_partial_payment'] = count($this->fees_model->get_partial_payment($session, $term));
		$data['total_no_payment'] = $this->fees_model->get_no_payment($session, $term);
		$data['total_validated_full_payment'] = count($this->fees_model->get_validated_full_payment($session, $term));
		$data['total_validated_partial_payment'] = count($this->fees_model->get_validated_partial_payment($session, $term));
		$data['total_amount'] = $this->fees_model->get_total_amount_paid($session, $term);
		$data['total_amount_expected'] = $this->fees_model->get_total_amount_expected($session, $term);
		//misc
		$data['amount_payable'] = $this->fees_model->get_amount_payable_by_class($session, $term, $class_id);
		$data['school_population'] = count($this->common_model->get_students_list_with_suspended(school_id));
		$data['class_population'] = $this->common_model->get_class_population($class_id);
		$data['total_students'] = count($this->common_model->get_students_list_by_class($class_id));
		$data['fees_due_date'] = $this->fees_model->get_fees_due_date();
		$data['fees_overdue'] = $this->fees_model->check_fees_overdue();
		$data['session'] = $session;
		$data['the_session'] = get_the_session($session);
		$data['term'] = $term;
		$data['class_id'] = $class_id;
		$data['class'] = $class;
		return $data;
	}




	/* ========== Fee Categories ========== */
	public function get_fee_category_details($fee_cat_id) {
		return $this->db->get_where('fee_categories', array('id' => $fee_cat_id))->row();	
	}


	public function get_fee_categories() {
		return $this->db->get_where('fee_categories', array('school_id' => school_id))->result();	
	}


	public function new_fee_category() { 
		$category_items = $this->input->post('category', TRUE); 
		//explode category to get individual items (note that comma is used as delimeter)
		$categories = explode(",", $category_items);
		foreach ($categories as $category) {
			//ensure category does not already exist
			$category = ucwords($category);
			$query = $this->check_fee_category_exists($category);
			if ($query == 0) {
				$data = array(
					'school_id' => school_id,
					'category' => $category,
				);
				$this->db->insert('fee_categories', $data);
			} 
		}
	}


	public function edit_fee_category($fee_cat_id) { 
		$category = ucwords($this->input->post('category', TRUE)); 
		$data = array(
			'category' => $category,
		);
		$this->db->where('id', $fee_cat_id);
		return $this->db->update('fee_categories', $data);
	}


	public function check_fee_category_exists($category) { 
		$where = array(
			'school_id' => school_id,
			'category' => $category,
		);
		return $this->db->get_where('fee_categories', $where)->num_rows();
	}


	public function delete_fee_category($fee_cat_id) {
		return $this->db->delete('fee_categories', array('id' => $fee_cat_id));
    } 





    /* ========== Fee Discount Categories ========== */
	public function get_fee_discount_category_details($discount_cat_id) {
		return $this->db->get_where('fee_discount_categories', array('id' => $discount_cat_id))->row();	
	}


	public function get_fee_discount_categories() {
		return $this->db->get_where('fee_discount_categories', array('school_id' => school_id))->result();	
	}


	public function new_fee_discount_category() { 
		$data = array(
			'school_id' => school_id,
			'category' => ucwords($this->input->post('category', TRUE)),
			'discount' => $this->input->post('discount', TRUE),
		);
		return $this->db->insert('fee_discount_categories', $data);	
	}


	public function edit_fee_discount_category($discount_cat_id) { 
		$data = array(
			'category' => ucwords($this->input->post('category', TRUE)),
			'discount' => $this->input->post('discount', TRUE),
		);
		$this->db->where('id', $discount_cat_id);
		return $this->db->update('fee_discount_categories', $data);
	}


	public function check_fee_discount_category_exists($category) { 
		$where = array(
			'school_id' => school_id,
			'category' => $category,
		);
		return $this->db->get_where('fee_discount_categories', $where)->num_rows();
	}


	public function delete_fee_discount_category($discount_cat_id) {
		return $this->db->delete('fee_discount_categories', array('id' => $discount_cat_id));
    } 


    public function get_all_students_on_fee_discount($discount_cat_id) { 
		$this->db->order_by('last_name', 'asc');
		$where = array(
			'school_id' => school_id,
			'fee_discount_cat_id' => $discount_cat_id, 
			'revoked' => 'false', 
			'graduated' => 'false',
		);
		return $this->db->get_where('students', $where)->result();	
	}


	public function get_students_on_fee_discount_in_class($class_id) { 
		$this->db->order_by('last_name', 'asc');
		$where = array(
			'school_id' => school_id,
			'fee_discount_cat_id !=' => NULL, //not null 
			'class_id' => $class_id, 
			'revoked' => 'false', 
			'graduated' => 'false',
		);
		return $this->db->get_where('students', $where)->result();	
	}


	public function search_students_for_fee_discount_apply($keyword) {
		$where = array(
			'school_id' => school_id,
			'fee_discount_cat_id' => NULL, //exclude those who are already on discount 
			'revoked' => 'false', 
			'graduated' => 'false',
		);
        $this->db->where($where);
        $this->db->like('reg_id', $keyword, 'both'); //wildcard %title%
        $this->db->or_like('admission_id', $keyword, 'both'); //wildcard %title%
        $this->db->or_like('last_name', $keyword, 'both'); //wildcard %title%
        $this->db->or_like('first_name', $keyword, 'both'); //wildcard %title%
        $this->db->or_like('other_name', $keyword, 'both'); //wildcard %title%
        $this->db->limit(15);
       	$this->db->order_by('rand()');
		return $this->db->get('students')->result_array();	
    }


    public function apply_fee_discount_to_student($discount_cat_id, $student_id) { 
		$data = array(
			'fee_discount_cat_id' => $discount_cat_id
		);
		$this->db->where('id', $student_id);
		return $this->db->update('students', $data);
	}


	public function change_student_fee_discount_category($student_id) { 
		$data = array(
			'fee_discount_cat_id' => $this->input->post('fee_discount_cat_id', TRUE),
		);
		$this->db->where('id', $student_id);
		return $this->db->update('students', $data);
	}


	public function remove_student_from_fee_discount($student_id) { 
		$data = array(
			'fee_discount_cat_id' => NULL
		);
		$this->db->where('id', $student_id);
		return $this->db->update('students', $data);
	}





	/* ========== Manage Fees ========== */
	public function get_fee_details($fee_id) {
		return $this->db->get_where('fee_details', array('id' => $fee_id))->row();	
	}


	public function get_fee_details_by_class($session, $term, $class_id) {
		$where = array(
			'school_id' => school_id,
			'class_id' => $class_id,
			'session' => $session,
			'term' => $term,
		);
		return $this->db->get_where('fee_details', $where)->row();	
	}


	public function get_term_fee_details($session, $term) {
		$where = array(
			'school_id' => school_id,
			'session' => $session,
			'term' => $term,
		);
		$this->db->order_by('class_id', 'asc');
		return $this->db->get_where('fee_details', $where)->result();	
	}


	public function check_fee_details_exist($session, $term, $class_id) { 
		$where = array(
			'school_id' => school_id,
			'class_id' => $class_id,
			'session' => $session,
			'term' => $term,
		);
		return $this->db->get_where('fee_details', $where)->num_rows();
	}


	public function get_school_fee_sessions() { 
		//Get all reference of fees for this school
		$this->db->select('session');
		$this->db->distinct();
		$where = array(
			'school_id' => school_id,
			'session !=' => current_session_slug, //exclude current term from query
		);
		$this->db->where($where);
		$this->db->order_by('session', 'DESC'); //most recent session first
		return $this->db->get('fee_details')->result();
	}


	public function add_new_class_fees($class_id, $term_fees, $total_fees_payable) { 
		$data = array(
			'school_id' => school_id,
			'class_id' => $class_id,
			'term_fees' => $term_fees,
			'total_fees_payable' => $total_fees_payable,
			'session' => current_session_slug,
			'term' => current_term,
		);
		return $this->db->insert('fee_details', $data);
	}


	public function edit_class_fees($fee_id, $term_fees, $total_fees_payable) { 
		$data = array(
			'term_fees' => $term_fees,
			'total_fees_payable' => $total_fees_payable,
		);
		$this->db->where('id', $fee_id);
		return $this->db->update('fee_details', $data);
	}


	public function import_fees_insert($class_id, $term_fees, $total_fees_payable) { 
		$current_session = current_session_slug;
		$current_term = current_term;
		$data = array(
			'school_id' => school_id,
			'class_id' => $class_id,
			'term_fees' => $term_fees,
			'total_fees_payable' => $total_fees_payable,
			'session' => $current_session,
			'term' => $current_term,
		);
		return $this->db->insert('fee_details', $data);
	}


	public function import_fees_update($fee_id, $class_id, $term_fees, $total_fees_payable) { 
		$current_session = current_session_slug;
		$current_term = current_term;
		$data = array(
			'term_fees' => $term_fees,
			'total_fees_payable' => $total_fees_payable,
			'session' => $current_session,
			'term' => $current_term,
		);
		$this->db->where('id', $fee_id);
		return $this->db->update('fee_details', $data);
	}


	public function delete_fee_details($fee_id) {
		return $this->db->delete('fee_details', array('id' => $fee_id));
    } 


    public function clear_fee_details() {
    	$current_session = current_session_slug;
		$current_term = current_term;
		$where = array(
			'school_id' => school_id,
			'session' => $current_session,
			'term' => $current_term,
		);
		return $this->db->delete('fee_details', $where);
    } 


    public function get_term_fee_amount($fee_id, $fee_cat_id) {
		$fee_details = $this->fees_model->get_fee_details($fee_id);
		if ( ! $fee_details ) {
			return NULL;
		} else {

			$term_fees_str = $fee_details->term_fees;
			//saved in the  format fee_cat_id:amount, ++
			$term_fees_arr = explode(', ', $term_fees_str);

			$single_fee_details = [];
			foreach ($term_fees_arr as $term_fee) {
				$single_fee_details[] = explode(":", $term_fee);
			} 

			$amount_payable = "";
			foreach ($single_fee_details as $d) {
				$d_fee_cat_id = $d[0];
				$d_amount = $d[1];

				//compare fee_cat_id
				if ($d_fee_cat_id == $fee_cat_id) {
					$amount_payable = $d_amount;
				} 
			}
			return $amount_payable;
		}
	}


    public function get_fee_breakdown($fee_id) {
		$fee_details = $this->fees_model->get_fee_details($fee_id);
		if ( ! $fee_details ) {
			return NULL;
		} else {

			$term_fees_str = $fee_details->term_fees;
			//saved in the  format fee_cat_id:amount, ++
			$term_fees_arr = explode(',', $term_fees_str);

			$single_fee_details = [];
			foreach ($term_fees_arr as $term_fee) {
				$single_fee_details[] = explode(":", $term_fee);
			} 

			$fee_breakdown_arr = [];
			foreach ($single_fee_details as $d) {
				$fee_cat_id = $d[0];
				$amount = $d[1];
				//get fee category
				$fee_category = $this->get_fee_category_details($fee_cat_id)->category;
				$amount_payable = s_currency_symbol . number_format($amount);
				$fee_breakdown_arr[] = $fee_category . ' - ' . $amount_payable;
			}
			$fee_breakdown_str = implode('; ', $fee_breakdown_arr);
			return $fee_breakdown_str;
		}
	}






    /* ============= Get some things ============ */
	public function get_amount_payable_by_class_no_format($session, $term, $class_id) {
		$fee_details = $this->get_fee_details_by_class($session, $term, $class_id);
		if ($fee_details) {
			$amount_payable = $fee_details->total_fees_payable;
			return $amount_payable;
		} else {
			return NULL;
		}
	}


	public function get_amount_payable_by_class($session, $term, $class_id) {
		$fee_details = $this->get_fee_details_by_class($session, $term, $class_id);
		if ($fee_details) {
			$amount_payable = s_currency_symbol . number_format($fee_details->total_fees_payable);
			return $amount_payable;
		} else {
			return NULL;
		}
	}


	private function get_discounted_amount_payable($discount_cat_id, $amount_payable) { 
		$discount = $this->get_fee_discount_category_details($discount_cat_id)->discount;
		$discounted_amount = $amount_payable - ceil(($discount/100)* $amount_payable);
		return $discounted_amount;
	}


	public function get_amount_payable_by_student_no_format($session, $term, $class_id, $student_id) {
		$fee_details = $this->get_fee_details_by_class($session, $term, $class_id);
		if ($fee_details) {
			//check if student has discount
			$y = $this->common_model->get_student_details_by_id($student_id);
			$fee_discount_cat_id = $y->fee_discount_cat_id;
			if ($fee_discount_cat_id != NULL) {
				//discounted amount
				$amount_payable = $this->get_discounted_amount_payable($fee_discount_cat_id, $fee_details->total_fees_payable);
			} else {
				//original amount
				$amount_payable = $fee_details->total_fees_payable;
			}
			return $amount_payable;
		} else {
			return NULL;
		}
	}


	public function get_amount_payable_by_student($session, $term, $class_id, $student_id) {
		$fee_details = $this->get_fee_details_by_class($session, $term, $class_id);
		if ($fee_details) {
			//check if student has discount
			$y = $this->common_model->get_student_details_by_id($student_id);
			$fee_discount_cat_id = $y->fee_discount_cat_id;
			if ($fee_discount_cat_id != NULL) {
				//discounted amount
				$amount_payable = $this->get_discounted_amount_payable($fee_discount_cat_id, $fee_details->total_fees_payable);
			} else {
				//original amount
				$amount_payable = $fee_details->total_fees_payable;
			}
			return s_currency_symbol . number_format($amount_payable);
		} else {
			return NULL;
		}
	}


	public function student_discount_category($student_id) {
		$y = $this->common_model->get_student_details_by_id($student_id);
		$fee_discount_cat_id = $y->fee_discount_cat_id;
		if ($fee_discount_cat_id != NULL) {
			$fee_discount_details = $this->get_fee_discount_category_details($fee_discount_cat_id);
			if ($fee_discount_details) {
				$category = $fee_discount_details->category;
				$fee_discount_url = base_url($this->c_controller.'/fee_discount_category/'.$fee_discount_cat_id);
				return '<a class="text-bold text-success" href="'.$fee_discount_url.'" title="view/manage" target="_blank">' . $category . '</a>';
			} else {
				return NULL;
			}
		} else {
			return NULL;
		}
	}
	
	
	
	

	/* ========== Stats:  Class ========== */

	public function get_class_full_payment($session, $term, $class_id) {
		$where = array(
			'school_id' => school_id,
			'status' => 'Full Payment',
			'session' => $session,
			'term' => $term,
			'class_id' => $class_id,
		);
		$this->db->where($where);
		return $this->db->get('fee_payment')->result();	
	}


	public function get_class_validated_full_payment($session, $term, $class_id) {
		$where = array(
			'school_id' => school_id,
			'status' => 'Full Payment',
			'session' => $session,
			'term' => $term,
			'class_id' => $class_id,
			'validated' => 'true',
		);
		$this->db->where($where);
		return $this->db->get('fee_payment')->result();	
	}
	
	
	public function get_class_partial_payment($session, $term, $class_id) {
		$where = array(
			'school_id' => school_id,
			'status' => 'Partial Payment',
			'session' => $session,
			'term' => $term,
			'class_id' => $class_id,
		);
		$this->db->where($where);
		return $this->db->get('fee_payment')->result();	
	}


	public function get_class_validated_partial_payment($session, $term, $class_id) {
		$where = array(
			'school_id' => school_id,
			'status' => 'Partial Payment',
			'session' => $session,
			'term' => $term,
			'class_id' => $class_id,
			'validated' => 'true',
		);
		$this->db->where($where);
		return $this->db->get('fee_payment')->result();	
	}
	
	
	public function get_class_no_payment($session, $term, $class_id) {
		$class_population = $this->common_model->get_class_population($class_id);
		$total_full_payment = count($this->get_class_full_payment($session, $term, $class_id));
		$total_partial_payment = count($this->get_class_partial_payment($session, $term, $class_id));
		$total_paid = $total_full_payment + $total_partial_payment;
		$no_payment = $class_population - $total_paid;
		return $no_payment;
	}


	public function get_class_total_amount_paid($session, $term, $class_id) {
		$this->db->select_sum('amount_paid');
		$where = array(
			'school_id' => school_id,
			'session' => $session,
			'term' => $term,
			'class_id' => $class_id,
		);
		$this->db->where($where);
		$total_amount = $this->db->get('fee_payment')->row()->amount_paid;
		return ($total_amount > 0) ? (s_currency_symbol . number_format($total_amount, 2)) : NULL;
	}


	public function get_class_total_discounted_amount_expected($session, $term, $class_id) {
		$class_population = $this->common_model->get_class_population($class_id);
		$total_fees_payable = $this->get_amount_payable_by_class_no_format($session, $term, $class_id);
		if ($total_fees_payable == NULL) {
			$total_amount_expected = NULL;
		} else {
			//get students who are on discount in this class
			$students_on_discount = $this->get_students_on_fee_discount_in_class($class_id);
			$total_students_on_discount = count($students_on_discount);
			if ($total_students_on_discount > 0) {
				$total_discounted_amount_expected = 0;			
				foreach ($students_on_discount as $y) {
					$student_id = $y->id;
					//get discount details
					$fee_discount_cat_id = $y->fee_discount_cat_id;
					$fee_discount_details = $this->get_fee_discount_category_details($fee_discount_cat_id);
					//get amount payable by each student on discount
					$amount_payable = $this->get_discounted_amount_payable($fee_discount_cat_id, $total_fees_payable);
					//accumulate amount expected from each student on discount
					$total_discounted_amount_expected += $amount_payable;	
				}
				$total_amount_expected = $total_discounted_amount_expected;
				$total_amount_expected = s_currency_symbol . number_format($total_amount_expected, 2);
			} else { //no student on discount in this class
				$total_amount_expected = NULL;
			}
		}
		return $total_amount_expected;	
	}


	public function get_class_total_undiscounted_amount_expected($session, $term, $class_id) {
		$class_population = $this->common_model->get_class_population($class_id);
		$total_fees_payable = $this->get_amount_payable_by_class_no_format($session, $term, $class_id);
		if ($total_fees_payable == NULL) {
			$total_amount_expected = NULL;
		} else {
			//get students who are on discount in this class
			$students_on_discount = $this->get_students_on_fee_discount_in_class($class_id);
			$total_students_on_discount = count($students_on_discount);
			//deduct students on discount from class population to get those without
			$total_students_without_discount = $class_population - $total_students_on_discount;
			//get total amount expected from students without discount
			$total_undiscounted_amount_expected = $total_fees_payable * $total_students_without_discount;
			//add up amount expected from students with and without discount
			$total_amount_expected = $total_undiscounted_amount_expected;
			$total_amount_expected = s_currency_symbol . number_format($total_amount_expected, 2);
		}
		return $total_amount_expected;	
	}


	public function get_class_total_amount_expected($session, $term, $class_id) {
		$class_population = $this->common_model->get_class_population($class_id);
		$total_fees_payable = $this->get_amount_payable_by_class_no_format($session, $term, $class_id);
		if ($total_fees_payable == NULL) {
			$total_amount_expected = NULL;
		} else {
			//get students who are on discount in this class
			$students_on_discount = $this->get_students_on_fee_discount_in_class($class_id);
			$total_students_on_discount = count($students_on_discount);
			if ($total_students_on_discount > 0) {
				$total_discounted_amount_expected = 0;			
				foreach ($students_on_discount as $y) {
					$student_id = $y->id;
					//get discount details
					$fee_discount_cat_id = $y->fee_discount_cat_id;
					$fee_discount_details = $this->get_fee_discount_category_details($fee_discount_cat_id);
					//get amount payable by each student on discount
					$amount_payable = $this->get_discounted_amount_payable($fee_discount_cat_id, $total_fees_payable);
					//accumulate amount expected from each student on discount
					$total_discounted_amount_expected += $amount_payable;	
				}
				//deduct students on discount from class population to get those without
				$total_students_without_discount = $class_population - $total_students_on_discount;
				//get total amount expected from students without discount
				$total_undiscounted_amount_expected = $total_fees_payable * $total_students_without_discount;
				//add up amount expected from students with and without discount
				$total_amount_expected = $total_discounted_amount_expected + $total_undiscounted_amount_expected;
				$total_amount_expected = s_currency_symbol . number_format($total_amount_expected, 2);
			} else { //no student on discount in this class
				//multiply class fee payable by class population to get total amount expected from this class
				$total_amount_expected = $total_fees_payable * $class_population;
				$total_amount_expected = s_currency_symbol . number_format($total_amount_expected, 2);
			}
		}
		return $total_amount_expected;	
	}


	public function get_class_total_amount_expected_no_format($session, $term, $class_id) {
		$class_population = $this->common_model->get_class_population($class_id);
		$total_fees_payable = $this->get_amount_payable_by_class_no_format($session, $term, $class_id);
		if ($total_fees_payable == NULL) {
			$total_amount_expected = NULL;
		} else {
			//get students who are on discount in this class
			$students_on_discount = $this->get_students_on_fee_discount_in_class($class_id);
			$total_students_on_discount = count($students_on_discount);
			if ($total_students_on_discount > 0) {
				$total_discounted_amount_expected = 0;			
				foreach ($students_on_discount as $y) {
					$student_id = $y->id;
					//get discount details
					$fee_discount_cat_id = $y->fee_discount_cat_id;
					$fee_discount_details = $this->get_fee_discount_category_details($fee_discount_cat_id);
					//get amount payable by each student on discount
					$amount_payable = $this->get_discounted_amount_payable($fee_discount_cat_id, $total_fees_payable);
					//accumulate amount expected from each student on discount
					$total_discounted_amount_expected += $amount_payable;	
				}
				//deduct students on discount from class population to get those without
				$total_students_without_discount = $class_population - $total_students_on_discount;
				//get total amount expected from students without discount
				$total_undiscounted_amount_expected = $total_fees_payable * $total_students_without_discount;
				//add up amount expected from students with and without discount
				$total_amount_expected = $total_discounted_amount_expected + $total_undiscounted_amount_expected;
			} else { //no student on discount in this class
				//multiply class fee payable by class population to get total amount expected from this class
				$total_amount_expected = $total_fees_payable * $class_population;
			}
		}
		return $total_amount_expected;	
	}





	/* ========== Stats:  All ========== */

	public function get_full_payment($session, $term) {
		$where = array(
			'school_id' => school_id,
			'status' => 'Full Payment',
			'session' => $session,
			'term' => $term,
		);
		$this->db->where($where);
		return $this->db->get('fee_payment')->result();	
	}


	public function get_validated_full_payment($session, $term) {
		$where = array(
			'school_id' => school_id,
			'status' => 'Full Payment',
			'session' => $session,
			'term' => $term,
			'validated' => 'true',
		);
		$this->db->where($where);
		return $this->db->get('fee_payment')->result();	
	}
	
	
	public function get_partial_payment($session, $term) {
		$where = array(
			'school_id' => school_id,
			'status' => 'Partial Payment',
			'session' => $session,
			'term' => $term,
		);
		$this->db->where($where);
		return $this->db->get('fee_payment')->result();	
	}


	public function get_validated_partial_payment($session, $term) {
		$where = array(
			'school_id' => school_id,
			'status' => 'Partial Payment',
			'session' => $session,
			'term' => $term,
			'validated' => 'true',
		);
		$this->db->where($where);
		return $this->db->get('fee_payment')->result();	
	}
	
	
	public function get_no_payment($session, $term) {
		$school_population = count($this->common_model->get_students_list_with_suspended(school_id));
		$total_full_payment = count($this->get_full_payment($session, $term));
		$total_partial_payment = count($this->get_partial_payment($session, $term));
		$total_paid = $total_full_payment + $total_partial_payment;
		$no_payment = $school_population - $total_paid;
		return $no_payment;
	}


	public function get_total_amount_paid($session, $term) {
		$this->db->select_sum('amount_paid');
		$where = array(
			'school_id' => school_id,
			'session' => $session,
			'term' => $term,
		);
		$this->db->where($where);
		$total_amount = $this->db->get('fee_payment')->row()->amount_paid;
		return ($total_amount > 0) ? (s_currency_symbol . number_format($total_amount, 2)) : NULL;
	}


	public function get_total_amount_expected($session, $term) {
		//get fee data for all classes in this school
		$fee_details = $this->get_term_fee_details($session, $term);
		if (count($fee_details) == 0) {
			$total_amount_expected = NULL;
		} else {
			$total_amount_expected = 0;
			foreach ($fee_details as $y) {
				$class_id = $y->class_id;
				$class_population = $this->common_model->get_class_population($class_id);
				$total_fees_payable = $this->get_amount_payable_by_class_no_format($session, $term, $class_id);
				//multiply class fee payable by class population to get total amount expected from each class
				//finally, accumulate the result for each class
				$total_amount_expected += $total_fees_payable * $class_population;
				$total_amount_expected = s_currency_symbol . number_format($total_amount_expected, 2);
			}
		}
		return $total_amount_expected;	
	}


	public function get_total_amount_expected22333($session, $term) {
		//get fee data for all classes in this school
		$fee_details = $this->get_term_fee_details($session, $term);
		if (count($fee_details) == 0) {
			$total_amount_expected = NULL;
		} else {
			$total_amount_expected = 0;
			foreach ($fee_details as $y) {
				$class_id = $y->class_id;
				$class_total_amount_expected = $this->get_class_total_amount_expected_no_format($session, $term, $class_id);
				if ($class_total_amount_expected != NULL) {
					$total_amount_expected += $class_total_amount_expected;
					$total_amount_expected = s_currency_symbol . number_format($total_amount_expected, 2);
				} else {
					$total_amount_expected = NULL;
				}
			}
		}
		return $total_amount_expected;	
	}






	/* ========== Actions++ ========== */

	public function get_fee_payment_details_by_id($payment_id) {
		return $this->db->get_where('fee_payment', array('id' => $payment_id))->row();	
	}
	
	
	public function get_payment_details($session, $term, $class_id, $student_id) {
		$where = array(
			'student_id' => $student_id,
			'class_id' => $class_id,
			'session' => $session,
			'term' => $term,
		);
		$this->db->where($where);
		return $this->db->get('fee_payment')->row();	
	}


	public function update_payment_info($payment_id, $status, $transaction_id, $amount_paid, $balance, $date_paid, $last_installment = NULL, $last_amount_paid = NULL, $last_total_amount_paid = NULL, $installment_details = NULL) {
		$data = array (
			'status' => $status,
			'transaction_id' => $transaction_id,
			'amount_paid' => $amount_paid,
			'balance' => $balance,
			'date_paid' => $date_paid,
			'last_installment' => $last_installment,
			'last_amount_paid' => $last_amount_paid,
			'last_total_amount_paid' => $last_total_amount_paid,
			'installment_details' => $installment_details,
		);	
		$this->db->where('id', $payment_id);
		return $this->db->update('fee_payment', $data); 
    } 	


	public function approve_full_payment($session, $term, $class_id, $student_id) {
		$transaction_id = $this->input->post('transaction_id', TRUE);
		$date_paid = $this->input->post('date_paid', TRUE);
		//get the fees for the student's class
		$total_fees_payable = $this->get_amount_payable_by_student_no_format($session, $term, $class_id, $student_id);
		$amount_paid = $total_fees_payable;
		$balance = 'No Balance';
		$status = 'Full Payment';
		$data = array (
			'school_id' => school_id,
			'student_id' => $student_id,
			'class_id' => $class_id,
			'status' => $status,
			'transaction_id' => $transaction_id,
			'amount_paid' => $total_fees_payable,
			'balance' => $balance,
			'date_paid' => $date_paid,
			'session' => $session,
			'term' => $term,
		);	
		//check if payment data exist
		$payment_details = $this->get_payment_details($session, $term, $class_id, $student_id);
		if ( ! $payment_details ) { //payment data does not exist, do insert
			$this->db->insert('fee_payment', $data);
		} else { //payment data exists, do update
			$payment_id = $payment_details->id;
			$this->update_payment_info($payment_id, $status, $transaction_id, $amount_paid, $balance, $date_paid);
		}
    } 	
	
	
	public function approve_partial_payment($session, $term, $class_id, $student_id) {
		$transaction_id = $this->input->post('transaction_id', TRUE);
		$amount_paid = $this->input->post('fees_amount_paid', TRUE);
		$date_paid = $this->input->post('date_paid', TRUE);
		//get the fees for the class
		$total_fees_payable = $this->get_amount_payable_by_student_no_format($session, $term, $class_id, $student_id);
		//balance is total fees payable minus amount paid.
		$balance = $total_fees_payable - $amount_paid;
		
		//check if amount entered is greater than or equal the fees payable
		if ($amount_paid >= $total_fees_payable) { //student has attained full payment	

			$balance = 'No Balance';
			$status = 'Full Payment';
			$data = array (
				'school_id' => school_id,
				'student_id' => $student_id,
				'class_id' => $class_id,
				'status' => $status,
				'transaction_id' => $transaction_id,
				'amount_paid' => $total_fees_payable,
				'balance' => $balance,
				'date_paid' => $date_paid,
				'session' => $session,
				'term' => $term,
			);	
			//check if payment data exist
			$payment_details = $this->get_payment_details($session, $term, $class_id, $student_id);
			if ( ! $payment_details ) { //payment data does not exist, do insert
				$this->db->insert('fee_payment', $data);
			} else { //payment data exists, do update
				$payment_id = $payment_details->id;
				$this->update_payment_info($payment_id, $status, $transaction_id, $total_fees_payable, $balance, $date_paid);
			}
			
		} else { //student has not paid fully

			$balance = $balance;
			$status = 'Partial Payment';
			$last_installment = 1;
			$last_amount_paid = $amount_paid;
			$last_total_amount_paid = $amount_paid;
			$validated = 'false';
			$installment_details = "{$last_installment}, {$amount_paid}, {$transaction_id}, {$date_paid}, {$validated}";
			
			$data = array (
				'school_id' => school_id,
				'student_id' => $student_id,
				'class_id' => $class_id,
				'status' => $status,
				'transaction_id' => $transaction_id,
				'amount_paid' => $amount_paid,
				'balance' => $balance,
				'date_paid' => $date_paid,
				'last_installment' => $last_installment,
				'last_amount_paid' => $last_amount_paid,
				'last_total_amount_paid' => $last_total_amount_paid,
				'installment_details' => $installment_details,
				'session' => $session,
				'term' => $term,
			);	
			//check if payment data exist
			$payment_details = $this->get_payment_details($session, $term, $class_id, $student_id);
			if ( ! $payment_details ) { //payment data does not exist, do insert
				$this->db->insert('fee_payment', $data);
			} else { //payment data exists, do update
				$payment_id = $payment_details->id;
				$this->update_payment_info($payment_id, $status, $transaction_id, $amount_paid, $balance, $date_paid, $last_installment, $last_amount_paid, $last_total_amount_paid, $installment_details);
			}
		} 
    } 	
	
	
	public function approve_subsequent_partial_payment($session, $term, $class_id, $student_id) {
		$transaction_id = $this->input->post('transaction_id', TRUE);
		$amount_paid = $this->input->post('fees_amount_paid', TRUE);
		$date_paid = $this->input->post('date_paid', TRUE);

		$payment_details = $this->get_payment_details($session, $term, $class_id, $student_id);
		$payment_id = $payment_details->id;
		$previous_payment = $payment_details->amount_paid;
		//total amount paid so far is previous payment + new payment
		$total_amount_paid = $previous_payment + $amount_paid;
		//get the fees for the class
		$total_fees_payable = $this->get_amount_payable_by_student_no_format($session, $term, $class_id, $student_id);
		//balance is total fees payable minus total amount paid.
		$balance = $total_fees_payable - $total_amount_paid;

		$last_installment = $payment_details->last_installment + 1;
		$last_amount_paid = $amount_paid;
		$last_total_amount_paid = $payment_details->last_total_amount_paid + $amount_paid;
		$validated = 'false';
		$installment_details = "{$last_installment}, {$amount_paid}, {$transaction_id}, {$date_paid}, {$validated}";

		//prepend new installment details to old installment details. Use [,] as separator 
		$old_installment_details = $payment_details->installment_details;
		$installment_details = $old_installment_details . '[,]' . $installment_details;
		
		//check if student has made full payment
		//use >= incase former debt is rolled over
		if ($total_amount_paid >= $total_fees_payable) { //student has attained full payment
			
			$data = array (
				'status' => 'Full Payment',
				'transaction_id' => $transaction_id,
				'amount_paid' => $total_fees_payable,
				'balance' => 'No Balance',
				'date_paid' => $date_paid,
				'last_installment' => $last_installment,
				'last_amount_paid' => $last_amount_paid,
				'last_total_amount_paid' => $last_total_amount_paid,
				'installment_details' => $installment_details,
			);	
			$this->db->where('id', $payment_id);
			$this->db->update('fee_payment', $data);

			
		} else { //student has not paid fully
			
			$data = array (
				'status' => 'Partial Payment',
				'transaction_id' => $transaction_id,
				'amount_paid' => $total_amount_paid,
				'balance' => $balance,
				'date_paid' => $date_paid,
				'last_installment' => $last_installment,
				'last_amount_paid' => $last_amount_paid,
				'last_total_amount_paid' => $last_total_amount_paid,
				'installment_details' => $installment_details,
			);	
			$this->db->where('id', $payment_id);
			$this->db->update('fee_payment', $data);
		}
    } 	


    public function mark_fees_unpaid($session, $term, $class_id, $student_id) {
    	$status = 'No Payment';
    	$transaction_id = NULL;
    	$amount_paid = NULL;
    	$balance = NULL;
    	$date_paid = NULL;
		$data = array (
			'school_id' => school_id,
			'student_id' => $student_id,
			'class_id' => $class_id,
			'status' => $status,
			'session' => $session,
			'term' => $term,
			'last_installment' => NULL,
			'last_amount_paid' => NULL,
			'last_total_amount_paid' => NULL,
			'installment_details' => NULL,
		);
		//check if payment data exist
		$payment_details = $this->get_payment_details($session, $term, $class_id, $student_id);
		if ( ! $payment_details ) { //payment data does not exist, do insert
			$this->db->insert('fee_payment', $data);
		} else { //payment data exists, do update
			$payment_id = $payment_details->id;
			$this->update_payment_info($payment_id, $status, $transaction_id, $amount_paid, $balance, $date_paid);
		}
    } 	
	
	
	public function unapprove_payment($session, $term, $class_id, $student_id) {
		$payment_details = $this->get_payment_details($session, $term, $class_id, $student_id);
		$payment_id = $payment_details->id;
		//delete fee info
		$this->db->delete('fee_payment', array('id' => $payment_id));
    } 	
	
	
	public function validate_payment($payment_id, $validated_by) {
		$data = array (
			'validated' => 'true',
			'validated_by' => $validated_by,
			'date_validated' => date('Y-m-d H:i:s'),
		);	
		$this->db->where('id', $payment_id);
		return $this->db->update('fee_payment', $data); 
    } 	
	
	
	public function invalidate_payment($payment_id) {
		$data = array (
			'validated' => 'false',
			'validated_by' => NULL,
			'date_validated' => NULL,
		);	
		$this->db->where('id', $payment_id);
		return $this->db->update('fee_payment', $data); 
    } 	


    public function get_installment_details($payment_id) {
		$f = $this->fees_model->get_fee_payment_details_by_id($payment_id);
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


    public function validate_partial_payment($payment_id, $installment, $validated_by) {
		$f = $this->get_fee_payment_details_by_id($payment_id);
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

		$updated_installment_details = "";
		$count = 1; 
		$total_installments = count($single_installment);
		foreach ($single_installment_details as $d) {
			$s_installment = $d[0];
			$s_amount_paid = $d[1];
			$s_transaction_id = $d[2];
			$s_date_paid = $d[3];
			$s_validated = $d[4];
			//check if current installment equal to the target installment
			if ($s_installment == $installment) {
				$s_validated = 'true';
			} else {
				$s_validated = $s_validated;
			}

			//ensure separator does not get appended to the end...
			$separator = ($count == $total_installments) ? NULL : "[,]";

			//collect the updated installment details as a string
			$updated_installment_details .= $s_installment .', '. $s_amount_paid .', '. $s_transaction_id .', '. $s_date_paid .', '. $s_validated . $separator; 

			$count++;
		}

		//update the database...
		$data = array(
			'validated' => 'true',
			'validated_by' => $validated_by,
			'date_validated' => date('Y-m-d H:i:s'),
			'installment_details' => $updated_installment_details,
		);
		$this->db->where('id', $payment_id);
		$this->db->update('fee_payment', $data);
	}   


	public function invalidate_partial_payment($payment_id, $installment) {
		$f = $this->get_fee_payment_details_by_id($payment_id);
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

		$updated_installment_details = "";
		$count = 1; 
		$total_installments = count($single_installment);
		foreach ($single_installment_details as $d) {
			$s_installment = $d[0];
			$s_amount_paid = $d[1];
			$s_transaction_id = $d[2];
			$s_date_paid = $d[3];
			$s_validated = $d[4];
			//check if current installment equal to the target installment
			if ($s_installment == $installment) {
				$s_validated = 'false';
			} else {
				$s_validated = $s_validated;
			}

			//ensure separator does not get appended to the end...
			$separator = ($count == $total_installments) ? NULL : "[,]";

			//collect the updated installment details as a string
			$updated_installment_details .= $s_installment .', '. $s_amount_paid .', '. $s_transaction_id .', '. $s_date_paid .', '. $s_validated . $separator; 

			$count++;
		}
		//return $updated_installment_details;

		//update the database...
		$data = array(
			'installment_details' => $updated_installment_details,
		);
		$this->db->where('id', $payment_id);
		$this->db->update('fee_payment', $data);
	}  


	public function validate_all_partial_payment($payment_id, $validated_by) {
		$f = $this->get_fee_payment_details_by_id($payment_id);
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

		$updated_installment_details = "";
		$count = 1; 
		$total_installments = count($single_installment);
		foreach ($single_installment_details as $d) {
			$s_installment = $d[0];
			$s_amount_paid = $d[1];
			$s_transaction_id = $d[2];
			$s_date_paid = $d[3];
			$s_validated = $d[4];
			//set all validation status to true
			$s_validated = 'true';
			//ensure separator does not get appended to the end...
			$separator = ($count == $total_installments) ? NULL : "[,]";

			//collect the updated installment details as a string
			$updated_installment_details .= $s_installment .', '. $s_amount_paid .', '. $s_transaction_id .', '. $s_date_paid .', '. $s_validated . $separator; 

			$count++;
		}
		//return $updated_installment_details;

		//update the database...
		$data = array(
			'validated' => 'true',
			'validated_by' => $validated_by,
			'date_validated' => date('Y-m-d H:i:s'),
			'installment_details' => $updated_installment_details,
		);
		$this->db->where('id', $payment_id);
		$this->db->update('fee_payment', $data);
	} 


	public function invalidate_all_partial_payment($payment_id) {
		$f = $this->get_fee_payment_details_by_id($payment_id);
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

		$updated_installment_details = "";
		$count = 1; 
		$total_installments = count($single_installment);
		foreach ($single_installment_details as $d) {
			$s_installment = $d[0];
			$s_amount_paid = $d[1];
			$s_transaction_id = $d[2];
			$s_date_paid = $d[3];
			$s_validated = $d[4];
			//set all validation status to false
			$s_validated = 'false';

			//ensure separator does not get appended to the end...
			$separator = ($count == $total_installments) ? NULL : "[,]";

			//collect the updated installment details as a string
			$updated_installment_details .= $s_installment .', '. $s_amount_paid .', '. $s_transaction_id .', '. $s_date_paid .', '. $s_validated . $separator; 

			$count++;
		}
		//return $updated_installment_details;

		//update the database...
		$data = array(
			'validated' => 'false',
			'validated_by' => NULL,
			'date_validated' => NULL,
			'installment_details' => $updated_installment_details,
		);
		$this->db->where('id', $payment_id);
		$this->db->update('fee_payment', $data);
	}     


    public function bulk_actions_fees($session, $term, $class_id) {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		$students = ($selected_rows == 1) ? 'student' : 'students';
		foreach ($row_id as $student_id) {
			switch ($bulk_action_type) {
				case 'mark_fees_unpaid':
					$this->mark_fees_unpaid($session, $term, $class_id, $student_id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$students}'s fees marked unpaid successfully.");
				break;
				case 'unapprove':
					$this->unapprove_payment($session, $term, $class_id, $student_id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$students}'s fees unapproved successfully.");
				break;
			}
		} 
	}
	
	
	public function get_validation_status($payment_id) {
		$f = $this->get_fee_payment_details_by_id($payment_id);
		return ($f->validated == 'false') ? '<b class="text-success">Yes</b>' : '<b class="text-danger">No</b>';
	}


	private function get_validator($payment_id) {
		$f = $this->get_fee_payment_details_by_id($payment_id);
		$validator = ($f->validated == 'true') ? $this->common_model->get_admin_details_by_id($f->validated_by)->name : NULL;
		return $validator;
	}
	
	
	public function get_validation_info($payment_id) {
		$f = $this->get_fee_payment_details_by_id($payment_id);
		$validator = $this->get_validator($payment_id);
		$validated = ($f->validated == 'true') ? '<b class="text-success">Validated</b>' : '<b class="text-danger">Not Validated</b>';
		$date_validated = ($f->validated == 'true') ? x_date($f->date_validated) : NULL;
		return  'Status: ' . $validated . '<br />
				Validated By: ' . $validator . '<br />
				Date Validated: ' . $date_validated;
	}


	public function get_partial_validation_info($payment_id) {
		$f = $this->get_fee_payment_details_by_id($payment_id);
		$validator = $this->get_validator($payment_id);
		$validated = ($f->validated == 'true') ? '<b class="text-success">Validated</b>' : '<b class="text-danger">Not Validated</b>';
		$date_validated = ($f->validated == 'true') ? x_date($f->date_validated) : NULL;
		return  'Last Validated By: ' . $validator . '<br />
				Last Validated On: ' . $date_validated;
	}


	public function get_installment_details_alt($payment_id) {
		$installment_details = $this->get_installment_details($payment_id);
		if ($installment_details == NULL) {
			return NULL;
		} else {

			$info = "";
			foreach ($installment_details as $d) {
				$installment = $d[0];
				$amount_paid = s_currency_symbol . number_format($d[1]);
				$transaction_id = $d[2];
				$date_paid = x_date($d[3]);
				if ($d[4] == 'true') {
					$validate_url = base_url('fees_admin/invalidate_partial_payment/'.$payment_id.'/'.$installment);
					$validated = 	'<b class="text-success">Yes</b>
									<a class="btn btn-default btn-xs" href="' . $validate_url . '" title="Invalidate this payment"><i class="fa fa-times text-danger"></i> Invalidate</a>';
				} else {
					$validate_url = base_url('fees_admin/validate_partial_payment/'.$payment_id.'/'.$installment);
					$validated = 	'<b class="text-danger">No</b>
									<a class="btn btn-default btn-xs" href="' . $validate_url . '" title="Validate this payment"><i class="fa fa-check text-success"></i> Validate</a>';
				}
				$info .=	'<p>
								Installment: ' . $installment . ';
								Amount Paid: ' . $amount_paid . ';
								Transaction ID: ' . $transaction_id . ';
								Date Paid: ' . $date_paid . ';
								Validated: ' . $validated . 
							'</p>';
			}
			return $info; 
		}
	} 
	
	
	public function payment_validation_restricted($payment_id) {
		//prevent actions such as Mark Unpaid, unapprove for validated fees
		$f = $this->get_fee_payment_details_by_id($payment_id);
		$validated = $f->validated;
		if ($validated != 'true') {
			return TRUE;
		} else {
			$this->session->set_flashdata('status_msg_error', 'Payment already validated!');
			redirect($this->agent->referrer());
		}
	}


	public function get_fees_due_date() {
		$date_due = x_date($this->common_model->get_term_info(school_id)->current_term_fees_due_date);
		$is_overdue = $this->check_fees_overdue();
		if ($is_overdue) { //overdue
			return '<span class="text-danger">' . $date_due . '</span>';
		} else {
			return $date_due;
		}
    } 


    public function check_fees_overdue() {
		$date_due = $this->common_model->get_term_info(school_id)->current_term_fees_due_date; //saved in the format yyyy/mm/dd
		//remove hyphen from date so that we can get date as a numerical value 
		$date_due = str_replace("/", "", $date_due); //now yyyymmdd
		//get today's date in the same yyyymmdd format
		$today = date('Ymd');
		//check if today is greater than date due 
		if ($today > $date_due) {
			return TRUE; //it's beyond date due
		} else {
			return FALSE; //not due
		}
    } 

	
}