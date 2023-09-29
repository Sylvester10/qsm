<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Gallery_model
Role: Model
Description: Controls the DB processes of gallery from super admin panel
Controller: Gallery
Author: Nwankwo Ikemefuna
Date Created: 24th December, 2018
*/


class Gallery_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}




	public function get_screenshot_details($id) {
		return $this->db->get_where('screenshots', array('id' => $id))->row();
	}


	public function get_screenshots($limit, $offset) {		
		$this->db->limit($limit, $offset); //limit to be used as per_page, offset to be used as pagination segment
		$this->db->order_by("s_order", "ASC"); 
		$query = $this->db->get_where('screenshots');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }


    public function get_published_screenshots($limit, $offset) {		
		$this->db->limit($limit, $offset); //limit to be used as per_page, offset to be used as pagination segment
		$this->db->order_by("s_order", "ASC"); 
		$query = $this->db->get_where('screenshots', array('published' => 'true'));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }


    public function get_published_screenshots_web() { //published screenshots for web
		$this->db->order_by('s_order', 'DESC');
		$this->db->limit(30); 
		return $this->db->get_where('screenshots', array('published' => 'true'))->result();
	}


    public function count_screenshots() { 
		return $this->db->get_where('screenshots')->num_rows();
	}


	public function count_published_screenshots() { 
		return $this->db->get_where('screenshots', array('published' => 'true'))->num_rows();
	}


	public function count_unpublished_screenshots() { 
		return $this->db->get_where('screenshots', array('published' => 'false'))->num_rows();
	}


    
	
	public function get_last_uploaded_screenshot() { 
		$this->db->order_by('s_order', 'DESC');
		$this->db->limit(1); 
		return $this->db->get_where('screenshots')->row();
	}


	public function upload_screenshot($screenshot) {
		//get last row
		$last_row = $this->get_last_uploaded_screenshot();
		$last_s_order = $last_row->s_order; 
		$s_order = $last_s_order + 1; //increment order
		$data = array (
			'screenshot' => $screenshot, 
			's_order' => $s_order, 
		);
		return $this->db->insert('screenshots', $data);
	}


	public function publish_screenshot($screenshot_id) { 
		$data = array (
			'published' => 'true',
		);
		$this->db->where('id', $screenshot_id);
		return $this->db->update('screenshots', $data);
	}


	public function unpublish_screenshot($screenshot_id) { 
		$data = array (
			'published' => 'false',
		);
		$this->db->where('id', $screenshot_id);
		return $this->db->update('screenshots', $data);
	}


	public function delete_screenshot($screenshot_id)	{
		$p = $this->get_screenshot_details($screenshot_id);
		//delete screenshot from folder
		unlink('./assets/uploads/screenshots/'.$p->screenshot);
		//delete record from database
		$this->db->delete('screenshots', array('id' => $screenshot_id));
	}

	
	public function bulk_actions_screenshots() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		$screenshots = ($selected_rows == 1) ? 'screenshot' : 'screenshots';
		foreach ($row_id as $screenshot_id) {
			switch ($bulk_action_type) {
				case 'publish':
					$this->publish_screenshot($screenshot_id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$screenshots} published successfully.");
				break;
				case 'unpublish':
					$this->unpublish_screenshot($screenshot_id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$screenshots} unpublished successfully.");
				break;
				case 'delete':
					$this->delete_screenshot($screenshot_id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$screenshots} deleted successfully.");
				break;
			}
		} 
	}



}