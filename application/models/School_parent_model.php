<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: School_parent_model
Role: Model
Description: Controls the DB processes of the parent
Controller: School_parent
Author: Nwankwo Ikemefuna
Date Created: 6th September, 2018
*/


class School_parent_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->parent_details = $this->common_model->get_parent_details($this->session->parent_email);
	}
	
	

	
	/* ===== Dashboard ===== */
	public function send_quick_mail() {	
		$parent_name = $this->parent_details->name;
		//get school's official mail
		$email = official_mail;
		$subject = ucwords($this->input->post('subject', TRUE));
		$message = nl2br(ucfirst($this->input->post('message', TRUE)));
		$message = $message. '<p>Sent from parent dashboard by <b>' . $parent_name . '</b></p>';
		return email_user($email, $subject, $message);
	}


	public function get_parent_child_details() { 
		$parent_id = $this->parent_details->id;
		return $this->db->get_where('students', array('parent_id' => $parent_id))->row();
	}


	public function modal_select_child($modal_id, $modal_title, $button_caption, $redirect_method, $redirect_type) { 
		$parent_id = $this->parent_details->id;
		return '<div class="modal fade" id="' . $modal_id . '" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-width">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">' . $modal_title . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">'
						
						. form_open('school_parent/select_child') .
						
							'<input type="hidden" name="redirect_method" value="' . $redirect_method . '" />
							<input type="hidden" name="redirect_type" value="' . $redirect_type . '" />

							<div class="form-group">
								<label class="form-control-label">Child</label>
								<select class="form-control selectpicker" name="student_id" required>
									<option value="">Select Child</option>'

									. $this->common_model->parent_children_option_select($parent_id) .

								'</select>
								<div class="form-error">' . form_error('student_id') . '</div>
							</div>
							
							<div>
								<button class="btn btn-primary">' . $button_caption . '</button>
							</div>'

						. form_close() .

					'</div>
				</div>
			</div>
		</div>';
	}



	/* ===== Attendance ===== */
	private function where_array($session, $term, $class_id, $student_id) { 
		$where = array(
			'student_id' => $student_id,
			'class_id' => $class_id,
			'session' => $session,
			'term' => $term,
		);
		return $where;
	}


	public function get_recent_attendance($class_id, $student_id, $limit) {
    	$this->db->limit($limit);
    	$this->db->order_by('date', 'DESC');
    	$where = array(
    		'student_id' => $student_id,
    		'class_id' => $class_id,
			'session' => current_session_slug,
			'term' => current_term,
		);
    	return $this->db->get_where('attendance', $where)->result();	
    }


	public function get_student_attendance($session, $term, $class_id, $student_id) {
    	$where = $this->where_array($session, $term, $class_id, $student_id);
		$this->db->order_by('date', 'desc');
		return $this->db->get_where('attendance', $where)->result();	
    }
	
	
	public function check_attendance($student_id, $date) {
    	$where = array(
			'student_id' => $student_id,
			'date' => $date,
		);
    	return $this->db->get_where('attendance', $where)->row();	
    }
	
	
	 
	
	
	/* ===== Email Notifications ===== */
    public function get_email_notif_details() {
		$parent_id = $this->parent_details->id;
		$this->db->where('parent_id', $parent_id);
    	return $this->db->get_where('parent_email_notifs')->row();	
    }


    public function update_email_notifications() {
    	$data = array(
			'child_absence' => $this->input->post('child_absence', TRUE),
			'newsletters' => $this->input->post('newsletters', TRUE),
		);
    	$parent_id = $this->parent_details->id;
    	$this->db->where('parent_id', $parent_id);
    	return $this->db->update('parent_email_notifs', $data);	
    }




	
	
	/* ===== Checks ===== */
    public function check_child($student_id) {
		//ensure student is parent's child
		$student_details = $this->common_model->get_student_details_by_id($student_id);
		$parent_id = $student_details->parent_id;
		if ($student_details && $parent_id == $this->parent_details->id) {
			return TRUE;
		} else {
			redirect(site_url('school_parent/restricted_access')); 
		}
	}


    public function check_session_and_term($session, $term) {
		//ensure session and term are same as current session and term
		//this is required for methods which only allows student to access current session and term data
		if ($session == current_session_slug && $term == current_term) {
			return TRUE;
		} else {
			redirect(site_url('school_parent/restricted_access')); //redirect to restricted access page
		}
	}



}
	