<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: Cron_jobs_model
Role: Model
Description: Controls the DB processes of cron jobs from super admin panel
Controller: Cron_jobs
Author: Nwankwo Ikemefuna
Date Created: 30th December, 2018
*/


class Cron_jobs_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('cron_model');
	}



	/* ======== Cron Daemons ======= */
	public function get_cron_daemon_by_name($name) {
		return $this->db->get_where('cron_daemons', array('name' => $name))->row();
	}


	public function get_cron_daemons() { //published testimonials for web
		$this->db->order_by("id", "ASC"); 
		return $this->db->get('cron_daemons')->result();
	}



	/* ======== Cron Jobs DB Actions======= */
	public function get_cron_job_db_details($cj_id) {
		return $this->db->get_where('cron_jobs_db', array('id' => $cj_id))->row();
	}


	public function delete_cron_job_db($cj_id) {
		return $this->db->delete('cron_jobs_db', array('id' => $cj_id));
    } 


	public function bulk_actions_cron_jobs_db() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		foreach ($row_id as $cj_id) {
			switch ($bulk_action_type) {
				case 'delete':
					$this->delete_cron_job_db($cj_id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} cron jobs deleted successfully.");
				break;
			}
		} 
	}
	
	
	public function clear_cron_jobs_db($name) {
		return $this->db->delete('cron_jobs_db', array('name' => $name));
	}



    /* ======== Cron Jobs School Data Actions======= */
    public function get_cron_job_school_data_details($cj_id) {
		return $this->db->get_where('cron_jobs_school_data', array('id' => $cj_id))->row();
	}


	public function delete_cron_job_school_data($cj_id) {
		return $this->db->delete('cron_jobs_school_data', array('id' => $cj_id));
    } 


	public function bulk_actions_cron_jobs_school_data() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		foreach ($row_id as $cj_id) {
			switch ($bulk_action_type) {
				case 'delete':
					$this->delete_cron_job_school_data($cj_id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} cron jobs deleted successfully.");
				break;
			}
		} 
	}
	
	
	public function clear_cron_jobs_school_data($name) {
		return $this->db->delete('cron_jobs_school_data', array('name' => $name));
	}



	//Count all Cron Jobs for a daemon
	public function count_all_cron_jobs_by_daemon($category, $name) {
		if ($category == 'database_data') {
			$total_jobs = $this->db->get_where('cron_jobs_db', array('name' => $name))->num_rows();
		} else {
			$total_jobs = $this->db->get_where('cron_jobs_school_data', array('name' => $name))->num_rows();
		}
		return number_format($total_jobs);
	}


	//Count all Cron Jobs (across all daemons)
	public function count_all_cron_jobs() {
		$db = $this->db->count_all_results('cron_jobs_db');
		$school_data = $this->db->count_all_results('cron_jobs_school_data');
		$total_jobs = $db + $school_data;
		return number_format($total_jobs);
	}


    //Clear all Cron Jobs (across all daemons)
    public function clear_all_cron_jobs() {
		$this->db->truncate('cron_jobs_db');
		$this->db->truncate('cron_jobs_school_data');
    } 


	



	/* ======== Cron Jobs Query======= */
	public function get_unconfirmed_email_accounts($limit, $offset) {
		$this->db->limit($limit, $offset);
		$query = $this->cron_model->get_unconfirmed_email_accounts();
		if ( count($query) > 0) {
            foreach ($query as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;	
	}


	public function get_passive_confirmed_accounts($limit, $offset) {
		$this->db->limit($limit, $offset);
		$query = $this->cron_model->get_passive_confirmed_accounts();
		if ( count($query) > 0) {
            foreach ($query as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;	
	}


	public function get_passive_free_trial_accounts($limit, $offset) {
		$this->db->limit($limit, $offset);
		$query = $this->cron_model->get_passive_free_trial_accounts();
		if ( count($query) > 0) {
            foreach ($query as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;	
	}


	public function get_passive_activated_accounts($limit, $offset) {
		$this->db->limit($limit, $offset);
		$query = $this->cron_model->get_passive_activated_accounts();
		if ( count($query) > 0) {
            foreach ($query as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;	
	}


	public function get_expiring_free_trial_accounts($limit, $offset) {
		$this->db->limit($limit, $offset);
		$query = $this->cron_model->get_expiring_free_trial_accounts();
		if ( count($query) > 0) {
            foreach ($query as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;	
	}


	public function get_expired_free_trial_accounts($limit, $offset) {
		$this->db->limit($limit, $offset);
		$query = $this->cron_model->get_expired_free_trial_accounts();
		if ( count($query) > 0) {
            foreach ($query as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;	
	}


	public function get_expired_free_trial_monthly_accounts($limit, $offset) {
		$this->db->limit($limit, $offset);
		$query = $this->cron_model->get_expired_free_trial_monthly_accounts();
		if ( count($query) > 0) {
            foreach ($query as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;	
	}


	
}