<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Weekly_reports_model
Role: Model
Description: Controls the DB processes of Weekly Reports
Controller: Weekly_reports_admin, Weekly_reports_staff
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/

class Weekly_reports_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}



	public function get_weekly_report_details($report_id)	{ 
		return $this->db->get_where('weekly_reports', array('id' => $report_id))->row();
	}


	public function get_weekly_reports_by_type($report_type_id)	{ 
		return $this->db->get_where('weekly_reports', array('school_id' => school_id, 'report_type_id' => $report_type_id))->result();
	}
	
	

	/* ========== Staff Actions ========== */

	public function submit_weekly_report($report_file, $submitted_by) { 
		$week = $this->input->post('week', TRUE); 
		$starting_date = $this->input->post('starting_date', TRUE); 
		$ending_date = $this->input->post('ending_date', TRUE); 	
		$report_type_id = $this->input->post('report_type_id', TRUE); 	
		$data = array (
			'school_id' => school_id,
			'submitted_by' => $submitted_by,
			'week' => $week,
			'starting_date' => $starting_date,
			'ending_date' => $ending_date,
			'report_type_id' => $report_type_id,
			'report' => $report_file,
			'session' => current_session_slug,
			'term' => current_term,	
		);
		$insert = $this->db->insert('weekly_reports', $data);
		$report_id = $this->db->insert_id($insert);
		
		//generate and update ref ID
		$ref_id = generate_ref_id($report_id);
		$data = array (
			'ref_id' => $ref_id
		);
		$this->db->where('id', $report_id);	
		$this->db->update('weekly_reports', $data);	
		
		//notify admins by mail
		$this->notify_admins($report_id, $submitted_by);
	}
	
	
	private function notify_admins($report_id, $submitted_by) {
		$staff_name = $this->common_model->get_staff_details_by_id($submitted_by)->name;
		$session = current_session_slug;
		$the_session = get_the_session($session);
		$term = current_term;
		//notify admins by mail
		$y = $this->get_weekly_report_details($report_id);
		//get privileged admins (with level 1)
		$level = 1;
		$admins = $this->common_model->get_admins_by_level(school_id, $level);
		$redirect_url = base_url('weekly_reports_admin/weekly_reports/'.$session.'/'.$term);
		$subject = 'New Weekly Report';
		$message = 'Hi admin, <br />
					You have a new weekly report submission. <br /> 
					<b>Report Details:</b><br /> 
					Ref ID: ' .$y->ref_id. '<br />
					Report for: Week ' . $y->week . ', ' . $y->term . ' term, ' . $the_session . ' session <br />
					Submitted by: ' . $staff_name . '<br />
					Visit your <a href="' .$redirect_url. '">admin dashboard</a> to see more details and download this report.';
		email_multiple($admins, $subject, $message); //email admins	
	}




	/* ========== Admin Actions ========== */

	public function remark_weekly_report($report_id) {
		$remark = nl2br(ucfirst($this->input->post('remark', TRUE))); 
		$y = $this->get_weekly_report_details($report_id);
		$reporter_id = $y->submitted_by;
		$reporter_email = $this->common_model->get_staff_details_by_id($reporter_id)->email;
		$reporter_name = $this->common_model->get_staff_details_by_id($reporter_id)->name;
		$reporter_name = get_firstname($reporter_name);
		$the_session = get_the_session($y->session);
		//send email to report creator
		$subject = 'Weekly Report';
		$message = "Hi {$reporter_name}, <br />
					Your report of week {$y->week}, {$y->term} term, {$the_session} session, with Ref ID {$y->ref_id} has been remarked. <br />
					Login to your dashboard to see more details.";
		email_user($reporter_email, $subject, $message);
		
		//send notif to staff
		$notif_message = 	"Hi {$reporter_name}, <br />
							Your report of week {$y->week}, {$y->term} term, {$the_session} session, with Ref ID {$y->ref_id} has been remarked.";
		$this->common_model->notify_user(school_id, $reporter_id, $subject, $notif_message, 'staff_notifs'); 
		
		$data = array (
			'remark' => $remark
		);		 
        $this->db->where('id', $report_id);			
		return $this->db->update('weekly_reports', $data);
    } 
	
	
	public function delete_weekly_report($report_id) {
		$y = $this->get_weekly_report_details($report_id);
		unlink('./assets/uploads/weekly_reports/'.$y->report); //remove report content from server
		return $this->db->delete('weekly_reports', array('id' => $report_id));
    } 
	
	
	public function delete_bulk_weekly_reports() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		$reports = ($selected_rows == 1) ? 'report' : 'reports';
		foreach ($row_id as $report_id) {
			$this->delete_weekly_report($report_id);
			$this->session->set_flashdata('status_msg', "{$selected_rows} {$reports} deleted successfully.");
		} 
	}





	/* ===== Report Types ===== */
	public function get_report_type_details($report_type_id)	{ 
		return $this->db->get_where('weekly_report_types', array('id' => $report_type_id))->row();
	}


	public function get_report_types()	{ 
		return $this->db->get_where('weekly_report_types', array('school_id' => school_id))->result();
	}


	public function add_new_report_type() { 
		$type = ucwords($this->input->post('type', TRUE)); 
		$data = array(
			'school_id' => school_id,
			'type' => $type,
		);
		return $this->db->insert('weekly_report_types', $data);
	}


	public function edit_report_type($report_type_id) { 
		$type = ucwords($this->input->post('type', TRUE)); 
		$data = array(
			'type' => $type,
		);
		$this->db->where('id', $report_type_id);
		$this->db->update('weekly_report_types', $data);
	}


	public function check_report_type_exists($type) { 
		//check if report type already exists for this school
		$this->db->where(array('school_id' => school_id, 'type' => $type));
		return $this->db->get('weekly_report_types')->num_rows();
	}
	
	
	public function delete_report_type($report_type_id) {
		return $this->db->delete('weekly_report_types', array('id' => $report_type_id));
    } 
	

	
	
}