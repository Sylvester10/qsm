<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Publication_management_model
Role: Model
Description: Controls the DB processes of publications from admin/staff panel
Controller: Admin, Staff
Author: Nwankwo Ikemefuna
Date Created: 31st May, 2018
*/

class Publications_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	
		
	
	/* ===== General Announcement ===== */
	public function get_general_announcement() { 
		return $this->db->get_where('general_announcement', array('school_id' => school_id))->row();
	}


	public function get_published_general_announcement() { 
		return $this->db->get_where('general_announcement', array('school_id' => school_id, 'published' => 'true'))->row();
	}
	
	
	public function create_general_announcement() {
		$announcement = ucfirst($this->input->post('announcement', TRUE));
		$data = array (
			'school_id' => school_id,
			'announcement' => $announcement,
		);		 
		return $this->db->insert('general_announcement', $data);
    } 


	public function update_general_announcement() {
		$announcement = ucfirst($this->input->post('announcement', TRUE));
		$data = array (
			'announcement' => $announcement,
			'date' => date('Y-m-d H:i:s'),
		);		 
		$this->db->where('school_id', school_id);
		return $this->db->update('general_announcement', $data);
    } 


    public function publish_general_announcement() {
		$data = array (
			'published' => 'true',
		);		 
		$this->db->where('school_id', school_id);
		return $this->db->update('general_announcement', $data);
    } 


    public function unpublish_general_announcement() {
		$data = array (
			'published' => 'false',
		);		 
		$this->db->where('school_id', school_id);
		return $this->db->update('general_announcement', $data);
    } 


    public function delete_general_announcement($id) {
		return $this->db->delete('general_announcement', array('id' => $id));
    } 




    /* ===== Staff Announcement ===== */
	public function get_staff_announcement() { 
		return $this->db->get_where('staff_announcement', array('school_id' => school_id))->row();
	}


	public function get_published_staff_announcement() { 
		return $this->db->get_where('staff_announcement', array('school_id' => school_id, 'published' => 'true'))->row();
	}
	
	
	public function create_staff_announcement() {
		$announcement = ucfirst($this->input->post('announcement', TRUE));
		$data = array (
			'school_id' => school_id,
			'announcement' => $announcement,
		);		 
		return $this->db->insert('staff_announcement', $data);
    } 


	public function update_staff_announcement() {
		$announcement = ucfirst($this->input->post('announcement', TRUE));
		$data = array (
			'announcement' => $announcement,
			'date' => date('Y-m-d H:i:s'),
		);		 
		$this->db->where('school_id', school_id);
		return $this->db->update('staff_announcement', $data);
    } 


    public function publish_staff_announcement() {
		$data = array (
			'published' => 'true',
		);		 
		$this->db->where('school_id', school_id);
		return $this->db->update('staff_announcement', $data);
    } 


    public function unpublish_staff_announcement() {
		$data = array (
			'published' => 'false',
		);		 
		$this->db->where('school_id', school_id);
		return $this->db->update('staff_announcement', $data);
    } 


    public function delete_staff_announcement($id) {
		return $this->db->delete('staff_announcement', array('id' => $id));
    } 
	
	
	
	
	
	/* ===== News ===== */
	public function get_news_details($id)	{ 
		return $this->db->get_where('news', array('id' => $id))->row();
	}


	public function get_news($limit, $offset) {		
		$this->db->limit($limit, $offset); //limit to be used as per_page, offset to be used as pagination segment
		$this->db->order_by("date", "DESC"); //order by date DESC i.e. latest newsletters first
		$query = $this->db->get_where('news', array('school_id' => school_id));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
            return false;
    }


    public function get_published_news($limit, $offset) {		
		$this->db->limit($limit, $offset); //limit to be used as per_page, offset to be used as pagination segment
		$this->db->order_by("date", "DESC"); //order by date DESC i.e. latest newsletters first
		$query = $this->db->get_where('news', array('school_id' => school_id, 'published' => 'true'));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
            return false;
    }


    public function count_news() { //get published news articles
		return $this->db->get_where('news', array('school_id' => school_id))->num_rows();
	}
	
	
	public function count_published_news() { 
		return $this->db->get_where('news', array('school_id' => school_id, 'published' => 'true'))->num_rows();
	}
	
	
	public function count_unpublished_news() { 
		return $this->db->get_where('news', array('school_id' => school_id, 'published' => 'false'))->num_rows();
	}
	
	
	public function create_news($posted_by, $poster_id) { 
		/*
		//For snippet, mb_strimwidth is used get the first 300 xters from the content and append ...
		//strip_tags is used to remove html tags when post is shared
		//For slug, the title is processed to replace spaces with hyphen, and remove special xters that are not url-friendly.
		*/
		$title = ucwords($this->input->post('title', TRUE)); 
		$slug = get_slug($title); 
		$content = ucfirst($this->input->post('body', TRUE));		
		$snippet = mb_strimwidth(strip_tags($content), 0, 300, "...");
		$body = nl2br($content);
		
		$featured_image = $this->upload->data('file_name');
		//generate thumbnail of the image with dimension 85x75
		$thumbnail = generate_image_thumb($featured_image, '85', '75');		
		$data = array (
			'school_id' => school_id,
			'posted_by' => $posted_by,
			'poster_id' => $poster_id,
			'title' => $title,
			'slug' => $slug,
			'snippet' => $snippet,
			'body' => $body,
			'featured_image' => $featured_image,
			'featured_image_thumb' => $thumbnail,
			'published' => 'true',
		);
		return $this->db->insert('news', $data);
	}
	
	
	public function edit_news($id, $featured_image, $thumbnail) { 
		/*
		//For snippet, mb_strimwidth is used get the first 300 xters from the content and append ...
		//strip_tags is used to remove html tags when post is shared
		//For slug, the title is processed to replace spaces with hyphen, and remove special xters that are not url-friendly.
		*/
		$title = ucwords($this->input->post('title', TRUE)); 
		$slug = get_slug($title); 
		$content = ucfirst($this->input->post('body', TRUE));		
		$snippet = mb_strimwidth(strip_tags($content), 0, 300, "...");
		$body = nl2br($content);
		
		$data = array (
			'title' => $title,
			'slug' => $slug,
			'snippet' => $snippet,
			'body' => $body,
			'featured_image' => $featured_image,
			'featured_image_thumb' => $thumbnail,
			'published' => 'true',
		);
		$this->db->where('id', $id);
		return $this->db->update('news', $data);
	}
	
	
	public function publish_news($id) { 
		$data = array (
			'published' => 'true',
		);
		$this->db->where('id', $id);
		return $this->db->update('news', $data);
	}
	
	
	public function unpublish_news($id) { 
		$data = array (
			'published' => 'false',
		);
		$this->db->where('id', $id);
		return $this->db->update('news', $data);
	}
	
	
	public function delete_news_featured_image($id) {
		$y = $this->get_news_details($id);
		unlink('./assets/uploads/news/'.$y->featured_image); //delete the featured image
		unlink('./assets/uploads/news/'.$y->featured_image_thumb); //delete the thumbnail
    } 
	
	
	public function delete_news($id) {
		$y = $this->get_news_details($id);
		$this->delete_news_featured_image($id); //remove image files from server
		return $this->db->delete('news', array('id' => $id));
    } 
	
	
	public function bulk_actions_news() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		foreach ($row_id as $id) {
			switch ($bulk_action_type) {
				case 'publish':
					$this->publish_news($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} News article published successfully.");
				break;
				case 'unpublish':
					$this->unpublish_news($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} News article unpublished successfully.");
				break;
				case 'delete':
					$this->delete_news($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} News article deleted successfully.");
				break;
			}
		} 
	}
	
	
	
	
	/* ===== Newsletters ===== */
	public function get_newsletter_details($id)	{ 
		return $this->db->get_where('newsletters', array('id' => $id))->row();
	}
	
	
	public function get_newsletters($limit, $offset) {		
		$this->db->limit($limit, $offset); //limit to be used as per_page, offset to be used as pagination segment
		$this->db->order_by("date", "DESC"); //order by date DESC i.e. latest newsletters first
		$query = $this->db->get_where('newsletters', array('school_id' => school_id));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
            return false;
    }


    public function get_published_newsletters($limit, $offset) {		
		$this->db->limit($limit, $offset); //limit to be used as per_page, offset to be used as pagination segment
		$this->db->order_by("date", "DESC"); //order by date DESC i.e. latest newsletters first
		$query = $this->db->get_where('newsletters', array('school_id' => school_id, 'published' => 'true'));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
            return false;
    }


    public function count_newsletters() { //get published news articles
		return $this->db->get_where('newsletters', array('school_id' => school_id))->num_rows();
	}
	
	
	public function count_published_newsletters() { 
		return $this->db->get_where('newsletters', array('school_id' => school_id, 'published' => 'true'))->num_rows();
	}
	
	
	public function count_unpublished_newsletters() { 
		return $this->db->get_where('newsletters', array('school_id' => school_id, 'published' => 'false'))->num_rows();
	}
	
	
	public function create_newsletter($the_file, $posted_by, $poster_id) { 
		$title = ucwords($this->input->post('title', TRUE)); 	
		//check which button was clicked
		$submit_type = $this->input->post('submit_type', TRUE);
		$published = ($submit_type == 'create_publish') ? 'true' : 'false'; 	
		$data = array (
			'school_id' => school_id,
			'posted_by' => $posted_by,
			'poster_id' => $poster_id,
			'title' => $title,
			'the_file' => $the_file,
			'published' => $published,
		);
		$insert = $this->db->insert('newsletters', $data);
		$id = $this->db->insert_id($insert);
		
		//notify parents of newly created newsletter if submit type is create and publish
		if ($submit_type == 'create_publish') {
			$this->notify_parents_new_newsletter($id);
		}
	}
	
	
	private function notify_parents_new_newsletter($id) { 
		//notify parents by mail
		$y = $this->get_newsletter_details($id);
		$parents = $this->common_model->get_parents(school_id);
		$subject = date('F Y') . ' Newsletter'; //eg May 2018 Newsletter
		$message = 'Hi Sir/Ma, <br />
					We have published a new episode of our monthly newsletter for this month: <b>' . date('F Y') . '</b>
					<br />
					<p>You received this mail because your child/ward is registered as a pupil in ' . school_name . '.</p>';
		$attachment = $this->get_newsletter_file($id);
		email_multiple($parents, $subject, $message, $attachment); 
	}
	
	
	public function publish_newsletter($id) { 
		$data = array (
			'published' => 'true',
		);
		$this->db->where('id', $id);
		$this->db->update('newsletters', $data);

		//notify parents
		$this->notify_parents_new_newsletter($id);
	}
	
	
	public function unpublish_newsletter($id) { 
		$data = array (
			'published' => 'false',
		);
		$this->db->where('id', $id);
		return $this->db->update('newsletters', $data);
	}


	private function get_newsletter_file($id) {
		$y = $this->get_newsletter_details($id);
		return 'assets/uploads/newsletters/'.$y->the_file;
    } 
	
	
	public function delete_newsletter($id) {
		$y = $this->get_newsletter_details($id);
		unlink('./assets/uploads/newsletters/'.$y->the_file); //remove file from server
		return $this->db->delete('newsletters', array('id' => $id));
    } 
	
	
	public function bulk_actions_newsletters() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		$newsletters = ($selected_rows == 1) ? 'newsletter' : 'newsletters';
		foreach ($row_id as $id) {
			switch ($bulk_action_type) {
				case 'publish':
					$this->publish_newsletter($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$newsletters} published successfully.");
				break;
				case 'unpublish':
					$this->unpublish_newsletter($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$newsletters} unpublished successfully.");
				break;
				case 'delete':
					$this->delete_newsletter($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$newsletters} deleted successfully.");
				break;
			}
		} 
	}
	
	
	
	
	/* ===== Term Dates ===== */
	public function get_term_date_details($id)	{ 
		return $this->db->get_where('term_dates', array('id' => $id))->row();
	}


	public function get_term_dates() { 
		return $this->db->get_where('term_dates', array('school_id' => school_id))->result();
	}


	public function get_published_term_dates($limit, $offset) {		
		$this->db->limit($limit, $offset); //limit to be used as per_page, offset to be used as pagination segment
		$this->db->order_by("date", "asc");
		$query = $this->db->get_where('term_dates', array('school_id' => school_id, 'published' => 'true'));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
            return false;
    }


	public function count_term_dates() { //get published news articles
		return $this->db->get_where('term_dates', array('school_id' => school_id))->num_rows();
	}
	
	
	public function count_published_term_dates() { 
		return $this->db->get_where('term_dates', array('school_id' => school_id, 'published' => 'true'))->num_rows();
	}
	
	
	public function count_unpublished_term_dates() { 
		return $this->db->get_where('term_dates', array('school_id' => school_id, 'published' => 'false'))->num_rows();
	}
	
	
	public function create_term_date() { 
		$activity = ucwords($this->input->post('activity', TRUE)); 	
		$term_date = $this->input->post('term_date', TRUE); 	
		$end_date = $this->input->post('end_date', TRUE); 	
		
		$data = array (
			'school_id' => school_id,
			'activity' => $activity,
			'term_date' => $term_date,
			'end_date' => $end_date,
			'published' => 'true',
		);
		$insert = $this->db->insert('term_dates', $data);
		$id = $this->db->insert_id($insert);
		
		//update date unix
		$this->update_term_date_unix($id);
	}
	
	
	public function update_term_date_unix($id) {
		$y = $this->get_term_date_details($id);
		$date_unix = $this->generate_date_unix_term_date($y->term_date);
		$data = array (
			'date_unix' => $date_unix,
		);
		$this->db->where('id', $id);
		return $this->db->update('term_dates', $data);
	}
	
	
	public function generate_date_unix_term_date($date) {
		//break date into arrays of day, month and year. format of yyyy-mm-dd. separator is "-"
		$x_date = explode('-', $date);
		$year = $x_date[0]; //year is array index 2
		$month = $x_date[1]; //month is array index 0
		$day = $x_date[2]; //day is array index 1
		
		//date unix: required to order the dates chronologically (in the order yyyymmdd)
		$date_unix = $year.$month.$day;
		return $date_unix;
	}
	
	
	public function update_term_date($id) { 
		$activity = ucwords($this->input->post('activity', TRUE)); 	
		$term_date = $this->input->post('term_date', TRUE); 	
		$end_date = $this->input->post('end_date', TRUE); 	
		$date_unix = $this->generate_date_unix_term_date($term_date);
		$data = array (
			'activity' => $activity,
			'term_date' => $term_date,
			'end_date' => $end_date,
			'date_unix' => $date_unix,
		);
		$this->db->where('id', $id);
		$this->db->update('term_dates', $data);
		//update date unix
		$this->update_term_date_unix($id);
	}
	
	
	public function check_term_date($id) {
		$y = $this->get_term_date_details($id);
		//check if date has an end date
		if ($y->end_date == NULL || $y->end_date == '') { //no end date, return only term date
			return x_date_full($y->term_date);
		} else { //end date exists, return start and end date
			return x_date_full($y->term_date) . ' &nbsp; - &nbsp; ' . x_date_full($y->end_date);
		}
	}


	public function publish_term_date($id) { 
		$data = array (
			'published' => 'true',
		);
		$this->db->where('id', $id);
		return $this->db->update('term_dates', $data);
	}
	
	
	public function unpublish_term_date($id) { 
		$data = array (
			'published' => 'false',
		);
		$this->db->where('id', $id);
		return $this->db->update('term_dates', $data);
	}
	
	
	public function delete_term_date($id) {
		return $this->db->delete('term_dates', array('id' => $id));
    } 
	
	
	public function bulk_actions_term_dates() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		foreach ($row_id as $id) {
			switch ($bulk_action_type) {
				case 'publish':
					$this->publish_term_date($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} Term Dates published successfully.");
				break;
				case 'unpublish':
					$this->unpublish_term_date($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} Term Dates unpublished successfully.");
				break;
				case 'delete':
					$this->delete_term_date($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} Term Dates deleted successfully.");
				break;
			}
		} 
	}
	
	
	public function clear_term_dates() {
		return $this->db->delete('term_dates', array('school_id' => school_id));
    } 
	
	
	
	
	
	/* ===== Calendar Dates ===== */
	public function get_calendar_date_details($id) {
		return $this->db->get_where('calendar_dates', array('id' => $id))->row();
	}
	
	
	public function create_calendar_date() { 
		$calendar_date = $this->input->post('calendar_date', TRUE); 	
		$caption = ucwords($this->input->post('caption', TRUE)); 	
		$description = nl2br(ucfirst($this->input->post('description', TRUE))); 
		
		$x_date = explode('/', $calendar_date);
		$year = $x_date[0]; //year is array index 0 
		$month = $x_date[1]; //month is array index 1
		$day = $x_date[2]; //day is array index 2
		$date_unix = $this->generate_date_unix_calendar_date($calendar_date);
		
		$data = array (
			'school_id' => school_id, 
			'year' => $year, 
			'month' => $month, 
			'day' => $day, 
			'date_unix' => $date_unix, 
			'caption' => $caption,
			'description' => $description,
		);
		return $this->db->insert('calendar_dates', $data);
	}
	
	
	public function get_date_unix($date_unix) {
		return $this->db->get_where('calendar_dates', array('date_unix' => $date_unix))->num_rows();
	}
	
	
	public function generate_date_unix_calendar_date($date) {
		//break date into arrays of day, month and year. Note that "/" must be specified as separator in datepicker initialization and date format must be set as yyyy/mm/dd
		$x_date = explode('/', $date);
		$year = $x_date[0]; //year is array index 0 
		$month = $x_date[1]; //month is array index 1
		$day = $x_date[2]; //day is array index 2
		
		//date unix: required to order the events chronologically when viewed as a list (in the order yyyymmdd)
		$date_unix = $year.$month.$day;
		return $date_unix;
	}
	
	
	public function edit_calendar_date($id) { 
		$calendar_date = $this->input->post('calendar_date', TRUE); 	
		$caption = ucwords($this->input->post('caption', TRUE)); 	
		$description = nl2br(ucfirst($this->input->post('description', TRUE))); 
		
		$x_date = explode('/', $calendar_date);
		$year = $x_date[0]; //year is array index 0 
		$month = $x_date[1]; //month is array index 1
		$day = $x_date[2]; //day is array index 2
		$date_unix = $this->generate_date_unix_calendar_date($calendar_date);
		
		$data = array (
			'year' => $year, 
			'month' => $month, 
			'day' => $day, 
			'date_unix' => $date_unix, 
			'caption' => $caption,
			'description' => $description,
		);
		$this->db->where('id', $id);
		return $this->db->update('calendar_dates', $data);
	}
	
	
	public function get_event_details($id)	{ 
		return $this->db->get_where('calendar_dates', array('id' => $id))->row();
	} 
	
	
	public function get_calendar_events() { 
		return $this->db->get_where('calendar_dates')->result();
	}
	
	
	public function get_calendar_events_list($limit, $offset) {		
		$this->db->limit($limit, $offset); //limit to be used as per_page, offset to be used as pagination segment
		$this->db->order_by("date_unix", "ASC"); //order by date_unix ASC so that the dates appear chronologically
		$query = $this->db->get_where('calendar_dates');
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
            return false;
    }
	
	
	private function get_events_by_date($date_unix) {
		$this->db->order_by('date_unix', 'asc');
		return $this->db->get_where('calendar_dates', array('school_id' => school_id, 'date_unix' => $date_unix))->result();
	}


	private function same_day_events($date_unix) {
		$same_day_events = $this->get_events_by_date($date_unix);
		$events = "";
		foreach ($same_day_events as $e) {
			$events .= 	'<div class="m-b-30">
							<h3>' . $e->caption . '</h3>
							<p style="font-weight: 300; font-size: 16px;">' . $e->description . '</p>
						</div>';
		}
		return $events;
	}
	
	
	public function modal_calendar_event_content($day, $month, $year, $date_unix) {
		$date = $year .'-'. $month .'-'. $day;  // yyyy-mm-dd to conform with mysql date format
		return  '<div class="modal fade" id="event'.$date.'" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-form">
						<div class="modal-header">
							<div class="pull-right">
								<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
							</div>
							<h4 class="modal-title text-left text-bold">Event Details: ' . x_date_full($date) . '</h4>
						</div><!--/.modal-header--> 
						<div class="modal-body text-left" style="margin-top: -15px;">'
							
							. $this->same_day_events($date_unix) .
						'</div>
					</div>
					</div>
				</div>
			</div>';
	}


	public function delete_calendar_date($id) {
		return $this->db->delete('calendar_dates', array('id' => $id));
    } 
	
	
	public function clear_calendar_dates() {
		return $this->db->delete('calendar_dates', array('school_id' => school_id));
    } 


	public function bulk_actions_calendar_dates() {
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		$bulk_action_type = $this->input->post('bulk_action_type', TRUE);		
		$row_id = $this->input->post('check_bulk_action', TRUE);
		$events = ($selected_rows == 1) ? 'event' : 'events';
		foreach ($row_id as $id) {
			switch ($bulk_action_type) {
				case 'delete':
					$this->delete_calendar_date($id);
					$this->session->set_flashdata('status_msg', "{$selected_rows} {$events} deleted successfully.");
				break;
			}
		} 
	}
	
	
	
	
	
	
}