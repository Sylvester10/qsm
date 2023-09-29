<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Support_model
Role: Model
Description: Controls the DB processes of the Support controller
Controller: Support
Author: Sylvester Nmakwe
Date Created: 29th August, 2018
*/

class Support_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	
	
	


	public function add_new_video() {
		$title = ucwords($this->input->post('title', TRUE));
		$description = ucfirst($this->input->post('description', TRUE));
		$url = $this->input->post('url', TRUE);
		//prepend http:// to url if not provided
		$url = prep_url($url);
		$category = ucwords($this->input->post('category', TRUE));
		$data = array(
			'title' => $title,
			'description' => $description,
			'url' => $url,
			'category' => $category,
		);
		$this->db->insert('support_videos', $data);	
    } 


    public function edit_video($id) {
		$title = ucwords($this->input->post('title', TRUE));
		$description = ucfirst($this->input->post('description', TRUE));
		$url = $this->input->post('url', TRUE);
		//prepend http:// to url if not provided
		$url = prep_url($url);
		$category = ucwords($this->input->post('category', TRUE));
		$data = array(
			'title' => $title,
			'description' => $description,
			'url' => $url,
			'category' => $category,
		);
		$this->db->where('id', $id);
		$this->db->update('support_videos', $data);
	}


	public function delete_video($id) {
		return $this->db->delete('support_videos', array('id' => $id));
    } 


    public function get_support_video_details($id) { 
		return $this->db->get_where('support_videos', array('id' => $id))->row();
	}


	public function count_videos($category) { 
		$this->db->where('category', $category);
		return $this->db->get('support_videos')->num_rows(); 
	}


	public function get_videos($limit, $offset, $category) {		
		$this->db->limit($limit, $offset); //limit to be used as per_page, offset to be used as pagination segment
		$this->db->order_by("title", "DESC"); //order by date DESC i.e. latest newsletters first
		$this->db->where('category', $category);
		$query = $this->db->get('support_videos');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
            return false;
    }


    




}