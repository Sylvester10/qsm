<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Web
Role: Controller
Description: Web Class controls access to pages such as Homepage and Features
Model: Web_model
Author: Sylvester Nmakwe, Nwankwo Ikemefuna
Date Created: 10th August, 2018
*/


class Web extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('web_model');
		$this->load->model('message_model');
		$this->load->model('support_model');
		$this->load->model('gallery_model');
		$this->load->model('testimonial_model');
		$this->paypal_buy_button = $this->config->item('paypal_lib_buy_button'); 
	}
	
	

	/* ===== Homepage ===== */
	public function index() {
		$software_name = software_name;
		$software_tagline = software_tagline;
		$title = $software_name . ' - ' . $software_tagline;
		$this->home_header($title); 
		$data['currency'] = $this->common_model->get_currency_by_location(); //currency symbol (naira or dollar depending on visitor location
		$data['price_lite'] = $this->common_model->get_plan_price_digit_by_location(1); //naira or dollar
		$data['price_pro'] = $this->common_model->get_plan_price_digit_by_location(2); //naira or dollar
		$data['price_pro_plus'] = $this->common_model->get_plan_price_digit_by_location(3); //naira or dollar
		$data['paypal_buy_button'] = $this->paypal_buy_button;
		$data['screenshots'] = $this->gallery_model->get_published_screenshots_web();
		$data['total_screenshots'] = $this->gallery_model->count_published_screenshots();
		$data['modules'] = $this->common_model->get_modules()->result();
		$data['published_testimonials'] = $this->testimonial_model->get_published_testimonials_web();
		$this->load->view('web/homepage', $data);
		$this->web_footer();
	}
	
	
	/* ===== Features ===== */
	public function features() {
		$this->web_header('Features');
		$data['modules'] = $this->common_model->get_modules()->result();
		$this->load->view('web/features', $data);
		$this->web_footer();
	}



	/* ===== Contact Us ===== */
	public function contact_us_ajax() { 
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
		$this->form_validation->set_rules('message', 'Message', 'trim|required');
		$this->form_validation->set_rules('captcha', 'Captcha', 'trim');
		$this->form_validation->set_rules('c_captcha', 'Captcha Code', 'required|exact_length[6]|matches[captcha]', 
			array('matches' => 'Incorrect captcha code')
		);
		if ($this->form_validation->run())  {	
			$this->message_model->contact_us(); //insert the data into db
			echo 1;
		} else { 
			echo validation_errors();
		}
	}
	
	
	/* ===== FAQs ===== */
	public function faq() {
		$this->web_header('FAQs');
		$data['currency'] = $this->common_model->get_currency_by_location(); //currency symbol (naira or dollar depending on visitor location
		$data['price_lite'] = $this->common_model->get_plan_price_digit_by_location(1); //naira or dollar
		$data['price_pro'] = $this->common_model->get_plan_price_digit_by_location(2); //naira or dollar
		$data['price_pro_plus'] = $this->common_model->get_plan_price_digit_by_location(3); //naira or dollar
		$this->load->view('web/faq', $data);
		$this->web_footer();
	}
	
	
	public function admin_videos() {
		$this->web_header('Admin Videos');
		//config for pagination
		$category = 'Admin';
        $config = array();
		$per_page = 12;  //number of items to be displayed per page
        $uri_segment = 3;  //pagination segment id
		$config["base_url"] = base_url('web/admin_videos');
		$config["total_rows"] = $this->support_model->count_videos($category);
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
		$str_links = $this->pagination->create_links();
        $data["links"] = explode('&nbsp;', $str_links); //explode the links 1 2 3 4 into distinct items for styling.
		$config["total_rows"] = $this->support_model->count_videos($category);
		$data['videos'] = $this->support_model->get_videos($config["per_page"], $page, $category);
		$this->load->view('web/admin_videos', $data);
		$this->web_footer();
	}


	
	
}