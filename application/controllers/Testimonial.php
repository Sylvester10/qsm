<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Testimonial
Role: Controller
Description: Controls access to testimonial pages and functions in super admin panel
Model: Testimonial_model
Author: Nwankwo Ikemefuna
Date Created: 16th August, 2018
*/



class Testimonial extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->super_admin_restricted(); //allow only logged in super admins to access this class
		$this->load->model('testimonial_model');
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);

		//module-level scripts
		$this->super_admin_module_scripts = array('s_testimonial');
	}




	/* ====== Contact testimonials ====== */
	public function index() {
		$total_records = $this->testimonial_model->count_all_testimonials();
		$inner_page_title = 'Testimonials (' . $total_records . ')'; 
		$this->super_admin_header('Testimonials', $inner_page_title);	
		$data['total_records'] = $this->testimonial_model->count_all_testimonials();
        $data['total_published'] = $this->testimonial_model->count_published_testimonials();
		$data['total_unpublished'] = $this->testimonial_model->count_unpublished_testimonials();
		$this->load->view('super_admin/testimonials/all_testimonials', $data);
        $this->super_admin_footer();
	}
	
	
	public function all_testimonials_ajax() {
		$this->load->model('ajax/super_admin/testimonials/testimonials_model_ajax', 'current_model');
		$list = $this->current_model->get_records();
		$data = array();
		foreach ($list as $y) {
			$status = ($y->published == 'true') ? '<span class="text-success">Published</span>' : '<span class="text-danger">Unpublished</span>'; 
			$row = array();	
			$row[] = 	'<div class="row">
							<div class="col-md-1">'
								. checkbox_bulk_action($y->id) .
							'</div>
							<div class="col-md-8">
								Name: ' .$y->name. '<br />
								Designation: ' .$y->designation. '<br />
								Rating: ' . $y->rating . '<br />
								Date: ' . x_date($y->date) . ' (' .time_ago($y->date). ')<br />
								Status: ' . $status .
								'<p class="m-t-15">' .$y->testimony. '</p>
							</div>
							<div class="col-md-2 col-md-offset-1">'
								.$this->current_model->options($y->id) . $this->current_model->modals($y->id). 
							'</div>
						</div>'; //bundle all columns into one column
			$data[] = $row; 
		}
		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->current_model->count_all_records(),
			"recordsFiltered" => $this->current_model->count_filtered_records(),
			"data" => $data,
		);
		echo json_encode($output);
	}


	public function add_new_testimonial_ajax() {	
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('designation', 'Designation', 'trim|required');
		$this->form_validation->set_rules('rating', 'Rating', 'trim|required');
		$this->form_validation->set_rules('testimony', 'Testimony', 'trim|required');
		if ($this->form_validation->run())  {		
			$this->testimonial_model->add_new_testimonial();
			echo 1;	
		} else { 
			echo validation_errors();
		}
    }


	public function publish_testimonial($testimonial_id) {
		$this->testimonial_model->publish_testimonial($testimonial_id);
		$this->session->set_flashdata('status_msg', 'Testimonial published successfully.');
		redirect($this->agent->referrer());
	}
	

	public function unpublish_testimonial($testimonial_id) { 
		$this->testimonial_model->unpublish_testimonial($testimonial_id);
		$this->session->set_flashdata('status_msg', 'Testimonial unpublished successfully.');
		redirect($this->agent->referrer());
	}
	
	
	public function delete_testimonial($testimonial_id) { 
		$this->testimonial_model->delete_testimonial($testimonial_id);
		$this->session->set_flashdata('status_msg', 'Testimonial deleted successfully.');
		redirect($this->agent->referrer());
	}
	
	
	public function bulk_actions_testimonials() { 
		$this->form_validation->set_rules('check_bulk_action', 'Bulk Select', 'trim');
		$selected_rows = count($this->input->post('check_bulk_action', TRUE)); 
		if ($this->form_validation->run()) {
			if ($selected_rows > 0) {
				$this->testimonial_model->bulk_actions_testimonials();
			} else {
				$this->session->set_flashdata('status_msg_error', 'No item selected.');
			}
		} else {
			$this->session->set_flashdata('status_msg_error', 'Bulk action failed!');
		}
		redirect($this->agent->referrer());
	}





}