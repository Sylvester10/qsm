<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: School_publications_student
Role: Controller
Description: School_publications_student Class controls access to all publications from the student's end
Model: Publications_model
Author: Nwankwo Ikemefuna
Date Created: 29th August, 2018
*/


class School_publications_student extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->student_restricted(); //allow only logged in users to access this class
		$this->load->model('publications_model');
		$this->student_details = $this->common_model->get_student_details_by_reg_id($this->session->student_reg_id);
		//get school id
		$this->school_id = $this->student_details->school_id;
		require_once "application/core/constants/Session.php"; //require session constants
		//ensure user login and current module are covered by current plan
		$this->login_module_restricted(school_id, mod_student_login, mod_publication_management, 'student'); 
		//ensure school account is activated
		$this->activation_restricted_student(school_id); 

        //module-level scripts
        $this->student_module_scripts = array();
	}

	

	/* ====== News ====== */
	public function news() {
		$this->student_header('News', 'News');
		//config for pagination
        $config = array();
		$per_page = 5;  //number of items to be displayed per page
        $uri_segment = 3;  //pagination segment id
		$config["base_url"] = base_url('school_publications_student/news');
        $config["total_rows"] = $this->publications_model->count_published_news();
        $config["per_page"] = $per_page;
		$config["uri_segment"] = $uri_segment;
		$config['cur_tag_open'] = '<a class="pagination-active-page" href="#!">';	//disable click event of current link
        $config['cur_tag_close'] = '</a>';
        $config['first_link'] = 'First';
        $config['next_link'] = '&raquo;';	// >>
        $config['prev_link'] = '&laquo;';	// <<
		$config['last_link'] = 'Last';
		$config['display_pages'] = TRUE; //show pagination link digits
		$config['num_links'] = 3; //number of digit links
        $this->pagination->initialize($config);
		$page = $this->uri->segment($uri_segment) ? $this->uri->segment($uri_segment) : 0;
		$data["news"] = $this->publications_model->get_published_news($config["per_page"], $page);
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
		$data['total_records'] = $this->publications_model->count_published_news();
		$this->load->view('student/school_publications/news', $data);
		$this->student_footer();
	}
	
	
	public function single_news($id, $slug) {
		//check post id and slug exist for this school
		$this->check_school_data_exists(school_id, $id, 'id', 'news', 'student');
		$this->check_school_data_exists(school_id, $slug, 'slug', 'news', 'student');
		$news_details = $this->publications_model->get_news_details($id);
		$title = $news_details->title;
		$this->student_header($title, $title);
		$data['y'] = $news_details;
		$this->load->view('student/school_publications/single_news', $data);
		$this->student_footer();
	}
	
	


	
	/* ====== Newsletters ====== */
	public function newsletters() {
		$this->student_header('Newsletters', 'Newsletters');	
		//config for pagination
        $config = array();
		$per_page = 5;  //number of items to be displayed per page
        $uri_segment = 3;  //pagination segment id
		$config["base_url"] = base_url('school_publications_student/newsletters');
        $config["total_rows"] = $this->publications_model->count_published_newsletters();
        $config["per_page"] = $per_page;
		$config["uri_segment"] = $uri_segment;
		$config['cur_tag_open'] = '<a class="pagination-active-page" href="#!">';	//disable click event of current link
        $config['cur_tag_close'] = '</a>';
        $config['first_link'] = 'First';
        $config['next_link'] = '&raquo;';	// >>
        $config['prev_link'] = '&laquo;';	// <<
		$config['last_link'] = 'Last';
		$config['display_pages'] = TRUE; //show pagination link digits
		$config['num_links'] = 3; //number of digit links
        $this->pagination->initialize($config);
		$page = $this->uri->segment($uri_segment) ? $this->uri->segment($uri_segment) : 0;
		$data["newsletters"] = $this->publications_model->get_published_newsletters($config["per_page"], $page);
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
		$data['total_records'] = $this->publications_model->count_published_newsletters();
		$this->load->view('student/school_publications/newsletters', $data);
		$this->student_footer();
	}
	
	
	
	
	/* ====== Term Dates ====== */
	public function term_dates() {
		$this->student_header('Term Dates', 'Term Dates');
		//config for pagination
        $config = array();
		$per_page = 5;  //number of items to be displayed per page
        $uri_segment = 3;  //pagination segment id
		$config["base_url"] = base_url('school_publications_student/term_dates');
        $config["total_rows"] = $this->publications_model->count_published_term_dates();
        $config["per_page"] = $per_page;
		$config["uri_segment"] = $uri_segment;
		$config['cur_tag_open'] = '<a class="pagination-active-page" href="#!">';	//disable click event of current link
        $config['cur_tag_close'] = '</a>';
        $config['first_link'] = 'First';
        $config['next_link'] = '&raquo;';	// >>
        $config['prev_link'] = '&laquo;';	// <<
		$config['last_link'] = 'Last';
		$config['display_pages'] = TRUE; //show pagination link digits
		$config['num_links'] = 3; //number of digit links
        $this->pagination->initialize($config);
		$page = $this->uri->segment($uri_segment) ? $this->uri->segment($uri_segment) : 0;
		$data["term_dates"] = $this->publications_model->get_published_term_dates($config["per_page"], $page);
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
		$data['total_records'] = $this->publications_model->count_published_term_dates();
		$this->load->view('student/school_publications/term_dates', $data);
		$this->student_footer();
	}

	
	
	
	/* ====== Calendar Dates ====== */
	public function calendar_dates() {
		$this->student_header('Events Calendar', 'Events Calendar');
		
		// Create template of preferences
		$prefs['template'] = custom_calendar_template(); //the custom calendar template
		$prefs['show_next_prev'] = true; //show next and previous links
		$prefs['next_prev_url'] = base_url('school_publications_student/calendar_dates'); //url for calendar pagination
		$prefs['month_type'] = 'long'; //full month name
		$prefs['day_type'] = 'short'; //3-letter day type
		$prefs['start_day'] = 'sunday'; //start calendar on sunday
		$prefs['show_other_days'] = FALSE; //Do not display days of other months that share the first or last week of the calendar month.
		
		//load calendar library with preferences
		$this->load->library('calendar', $prefs);
		
		if ($this->uri->segment(4)) { 
			$year = $this->uri->segment(3); //year URI segment
			$month = $this->uri->segment(4); //month URI segment
		} else { //first page, load current year and date
			$year = date("Y", time()); //full year eg 2018
			$month = date("m", time()); //numeric month eg 04 for April
		}
		$events = $this->calendar_events($month, $year); //get events
		$data['calendar'] = $this->calendar->generate($year, $month, $events); //generate calendar with events
		$this->load->view('student/school_publications/calendar_dates', $data);
		$this->student_footer();
	}
	
	
	public function calendar_events($month, $year) {
		//load calendar events
		$the_events = $this->publications_model->get_calendar_events();
		//create an associative array to hold the events
		$data = array();
		
		foreach ($the_events as $y) {

			//VERY IMPORTANT! 
			//Check if day is less than 10 and remove the leading 0 if true. This is because CI calendar library renders days from 1 to 9 as 1 digit, while the days are saved as 2 digits in db.
			$y->day = ($y->day < 10) ? substr($y->day, 1) : $y->day; //strip off the 1st xter i.e. 0
			$date = $y->year .'-'. $y->month .'-'. $y->day;
			//check that date is on current month and year (this is necessary to avoid duplicating same event date on all the months)
			if ($month == $y->month && $year == $y->year) {
				//day = 'link to event on this day'
				$data[$y->day] = 	'<div class="content" data-toggle="modal" data-target="#event' .$date. '">'
										. $y->day . 
									'</div>'
									. $this->publications_model->modal_calendar_event_content($y->day, $y->month, $y->year, $y->date_unix); //show details in a modal window
			} 
		}
		return $data;
	}
	
	
	public function calendar_dates_list() {
		$inner_page_title = 'Events Calendar List (' . count($this->publications_model->get_calendar_events()) . ')'; 
		$this->student_header('Events Calendar List', 'Events Calendar List');
		//config for pagination
        $config = array();
		$per_page = 5;  //number of items to be displayed per page
        $uri_segment = 3;  //pagination segment id: school_publications_student/calendar_dates_list/pagination_id
		$config["base_url"] = base_url('school_publications_student/calendar_dates_list');
        $config["total_rows"] = count($this->publications_model->get_calendar_events());
        $config["per_page"] = $per_page;
		$config["uri_segment"] = $uri_segment;
		$config['cur_tag_open'] = '<a class="pagination-active-page" href="#!">';	//disable click event of current link
        $config['cur_tag_close'] = '</a>';
        $config['first_link'] = 'First';
        $config['next_link'] = '&raquo;';	// >>
        $config['prev_link'] = '&laquo;';	// <<
		$config['last_link'] = 'Last';
		$config['display_pages'] = TRUE; //show pagination link digits
		$config['num_links'] = 3; //number of digit links
        $this->pagination->initialize($config);
		$page = $this->uri->segment($uri_segment) ? $this->uri->segment($uri_segment) : 0;
		$data["calendar_list"] = $this->publications_model->get_calendar_events_list($config["per_page"], $page);
		$data["total_records"] = $config["total_rows"];
        $str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
		$this->load->view('student/school_publications/calendar_dates_list', $data);
		$this->student_footer();
	}
	

	
	
}