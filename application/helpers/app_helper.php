<?php
defined('BASEPATH') OR exit('No direct script access allowed');


	function prevent_sandbox_indexing() {
		if (ENVIRONMENT == 'testing') {
	    	return '<meta name="robots" content="noindex, nofollow">';
	    } else {
			return '<meta name="robots" content="all, follow">';
		}
	}
	

	function check_javascript_enabled() { 
		$CI = & get_instance(); //get instance of code igniter super object so helpers can be accessed outside of object context
		?>
			<!-- No JavaScript -->
			<noscript>
				<?php
				//check current page to ensure redirect doesn't apply to the no_js page
				$current_method = $CI->router->fetch_method();
				if ($current_method != 'no_js') { ?>
					<meta http-equiv="refresh" content="0; url=<?php echo base_url('no_js'); ?>" />
				<?php } ?>
			</noscript>
		<?php
	}


	function get_firstname($full_name) { //get firstname from a string of names
		$full_name = trim($full_name);
		$names = explode(" ", $full_name); //break name string into an array of individual words
		if ( count($names) > 1 ) { //name contains at least 2 words
			$first_name = $names[1]; //array index 1, likely firstname
		} else {
			$first_name = $names[0]; //array index 0
		}
		return $first_name;
	}
	
	
	function custom_validation_errors() {
		if ( validation_errors() ) { 
			return '<div class="alert alert-danger alert-dismissable text-center">
				<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>'
				.validation_errors().
			'</div>';
		}
	} 
	

	function flash_message_success($message) {
		$CI = & get_instance(); //get instance of code igniter super object
		//success flash messages
		if ( $CI->session->flashdata($message) ) { 						
			return '<div class="alert alert-success alert-dismissable text-center">
						<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>' 
						.$CI->session->flashdata($message).
					'</div>';
		}
	}
	
	function flash_message_danger($message) {
		$CI = & get_instance(); //get instance of code igniter super object
		//danger flash messages
		if ( $CI->session->flashdata($message) ) { 						
			return '<div class="alert alert-danger alert-dismissable text-center">
						<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>' 
						.$CI->session->flashdata($message). 
					'</div>';
		}
	}

	
	function flash_message_warning($message) {
		$CI =& get_instance(); //get instance of code igniter super object
		//warning flash messages
		if ( $CI->session->flashdata($message) ) { 						
			return '<div class="alert alert-warning alert-dismissable text-center">
						<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>' 
						.$CI->session->flashdata($message). 
					'</div>';
		}
	}

	
	function flash_message_info($message) {
		$CI =& get_instance(); //get instance of code igniter super object
		//info flash messages
		if ( $CI->session->flashdata($message) ) { 						
			return '<div class="alert alert-info alert-dismissable text-center">
						<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>' 
						.$CI->session->flashdata($message). 
					'</div>';
		}
	}


	function generate_snippet($string, $max_characters) {
		$snippet = mb_strimwidth(strip_tags($string), 0, $max_characters, "...");
		return $snippet;
	}
	
	
	function radio_value($current_value, $option_value) { 
		//check the selected radio value and set it as default (helpful when editing an entity that has a radio field)
		//this makes use of CI's set_radio() 3rd argument, which sets a radio field as default by setting its value to TRUE
		return ($current_value == $option_value) ? TRUE : NULL; 
    }
	
	
	function generate_image_thumb($file_name, $width, $height) { //function to resize image while maintaining aspect ratio
		$CI =& get_instance(); //get instance of code igniter super object
		//config for image library
		$config['image_library'] = 'gd2';
		$config['source_image'] = $CI->upload->upload_path.$file_name;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $width;
		$config['height'] = $height;

		//load image library
		$CI->load->library('image_lib', $config);

		//resize image
		$CI->image_lib->resize();
		
		// handle if there is any problem
		if ( ! $CI->image_lib->resize()) {
			return $file_name; //if resize fails, return original image
		} else {
			$suffix = '_thumb.'; //eg cat.jpg becomes cat_thumb.jpg
			$image_thumb = str_ireplace('.', $suffix, $file_name); //add suffix
			return $image_thumb;
		}
	}
	
	
	function generate_ref_id($id) {
		switch ($id) {
			case in_array($id, range(1, 9)):  //1 to 9, prepend 6 zeros to id
				$ref_id = '#000000' . $id;
			break;
			case in_array($id, range(10, 99)):  //10 to 99, prepend 5 zeros to id
				$ref_id = '#00000' . $id;
			break;
			case in_array($id, range(100, 999)):  //100 to 999, prepend 4 zeros to id
				$ref_id = '#0000' . $id;
			break;
			case in_array($id, range(1000, 9999)):  //1000 to 9999, prepend 3 zeros to id
				$ref_id = '#000' . $id;
			break;
			case in_array($id, range(10000, 99999)):  //10000 to 99999, prepend 2 zeros to id
				$ref_id = '#00' . $id;
			break;
			case in_array($id, range(100000, 999999)):  //100000 to 999999, prepend 1 zero to id
				$ref_id = '#0' . $id;
			break;
			default: //1000000+, return id as is
				$ref_id = $id;
			break;
		}
		return $ref_id;		
	}


	function staff_titles() {
		$titles = [
			'Mr.',
			'Mrs.',
			'Miss',
			'Mz.',
			'Dr.',
		];
		return $titles;
	}


	function staff_designations() {
		$designations = [
			'Head of School',
			'School Manager',
			'Principal',
			'Acting Principal',
			'Vice Principal',
			'HR Personnel',
			'Admin Personnel',
			'Guidance Counsellor',
			'SEN Cordinator',
			'Head Teacher',
			'Acting Head Teacher',
			'Assistant Head Teacher',
			'Class Teacher',
			'Class Assistant',
			'Accountant',
			'Bursar',
			'ICT Personnel',
			'Librarian',
			'Sports Cordinator',
			'Cashier',
			'Store Manager',
			'Janitor',
			'Cook',
			'Security Personnel',
			'Other',
		];
		return $designations;
	}


	function staff_qualifications() {
		$qualifications = [
			'FSLC',
			'SSCE',
			'NCE',
			'OND',
			'Degree',
			'Masters',
			'PhD',
			'Other',
			'None',
		];
		return $qualifications;
	}


	function staff_roles() {
		$roles = [
			'School Manager',
			'Academic Administrator',
			'Class Teacher',
			'Subject Teacher',
			'Bursar',
			'Librarian',
			'Publication Manager',
			'Basic Staff',
		];
		return $roles;
	}


	function admin_designations() {
		$designations = [
			'Proprietor',
			'Proprietress',
			'School Director',
			'Board Member',
			'Head of School',
			'School Manager',
			'Principal',
			'Acting Principal',
			'Vice Principal',
			'Head Teacher',
			'Acting Head Teacher',
			'Assistant Head Teacher',
			'Admin Personnel',
		];
		return $designations;
	}
	
	
	function get_module_icon($module) {
		$icons = [
			'Core' => 'fa fa-sliders',
			'Student Management' => 'fa fa-user-plus',
			'Staff Management' => 'fa fa-user-plus',
			'Financial Requisitions' => 'fa fa-money',
			'Staff Reports' => 'fa fa-tasks',
			'Incidents' => 'fa fa-history',
			'Section & Class Management' => 'fa fa-cubes',
			'Subject Management' => 'fa fa-bookmark',
			'User Management' => 'fa fa-user-circle',
			'Data Export' => 'fa fa-download',
			'Student Attendance' => 'fa fa-refresh',
			'Staff Attendance' => 'fa fa-refresh',
			'Student Reports' => 'fa fa-line-chart',
			'Staff Login' => 'fa fa-sign-in',
			'Fee Management' => 'fa fa-money',
			'School Library' => 'fa fa-book',
			'Student Bulk Data Import' => 'fa fa-upload',
			'Staff Bulk Data Import' => 'fa fa-upload',
			'Timetable' => 'fa fa-clock-o',
			'Student Login' => 'fa fa-sign-in',
			'Parent Login' => 'fa fa-sign-in',
			'Homework' => 'fa fa-tasks',
			'Publication Management' => 'fa fa-bullhorn',
			'Parent Complaint' => 'fa fa-bullhorn',
			'Parent Enquiry' => 'fa fa-user',
		];
		return $icons[$module];
	}
	
	
	function world_religions() {
		$religions = array(
			'Christianity',
			'Islam',
			'Judaism',
			'Hinduism',
			'Budduism',
			'Other',
		);
		return $religions;
	}


	function get_free_trial_remaining_time($date) {
		$free_trial_period = free_trial_period;
	    $date = strtotime("$date + {$free_trial_period} Days"); //+ free_trial_period days of free trial
		$remaining = $date - time();
		$days_remaining = floor($remaining / 86400);
		$hours_remaining = floor(($remaining % 86400) / 3600);
		$days = ($days_remaining == 1) ? 'day' : 'days';
		$hours = ($hours_remaining == 1) ? 'hr' : 'hrs';
		if ($days_remaining < 0 && $hours_remaining < 0) { //expired
			$remaining_time = '<span class="text-danger">Expired</span>';
		} elseif ($days_remaining < 1) { //to avoid 0 days left ...
			$remaining_time = "{$hours_remaining} {$hours} left";
		} else { 
			$remaining_time = "{$days_remaining} {$days} left";
		}
		return $remaining_time;
	}


	function get_annual_subscription_remaining_time($date) {
		$annual_subscription_period = annual_subscription_period;
	    $date = strtotime("$date + {$annual_subscription_period} Days"); //+ free_trial_period days of free trial
		$remaining = $date - time();
		$days_remaining = floor($remaining / 86400);
		$hours_remaining = floor(($remaining % 86400) / 3600);
		$days = ($days_remaining == 1) ? 'day' : 'days';
		$hours = ($hours_remaining == 1) ? 'hr' : 'hrs';
		if ($days_remaining < 0 && $hours_remaining < 0) { //expired
			$remaining_time = '0 days';
		} elseif ($days_remaining < 1) { //to avoid 0 days left ...
			$remaining_time = "{$hours_remaining} {$hours}";
		} else { 
			$remaining_time = "{$days_remaining} {$days}";
		}
		return $remaining_time;
	}
	
	
	function get_expiration_date($date_installed) {
		$free_trial_period = free_trial_period;
		$expiration_date = strtotime("+$free_trial_period days", strtotime($date_installed));
		$expiration_date = date('Y/m/d', $expiration_date);
		return x_date_full($expiration_date);  
    }
	
	
	function get_renewal_date($activation_status, $last_activation_date) {
		//check if account has been activated
		if ($activation_status == 'true') { //account has been activated
			$days_in_year = annual_subscription_period;
			$renewal_date = strtotime("+$days_in_year days", strtotime($last_activation_date));
			$renewal_date = date('Y/m/d', $renewal_date);
			return x_date_full($renewal_date);  
		} else { //account not activated
			return NULL;
		}
    }
		
	
	function time_ago($time) { //return time in ago
		//add mysql-server time difference to time;
		$time_diff = mysql_time_difference;
		$time = strtotime("+$time_diff hours", strtotime($time));
		$now = time(); //current time
		$units = 1; //units to show... eg. 9 hours ago, 3 weeks ago. 
		return strtolower(timespan($time, $now, $units)). ' ago';
    }


    function time_ago_alt($time) { //return time in ago
		//add mysql-server time difference to time;
		$time_diff = mysql_time_difference;
		$time = strtotime("+$time_diff hours", strtotime($time));
		$now = time(); //current time
		$units = 2; //units to show... eg. 9 hours 30 minutes ago, 3 weeks 4 days ago. 
		return strtolower(timespan($time, $now, $units)). ' ago';
    }


    function get_last_login_ago($last_login) { 
    	if ($last_login == NULL) {
    		return '<span class="text-danger">Unknown</span>';
    	}
		//add mysql-server time difference to time;
		//$time_diff = mysql_time_difference;
		$last_login = strtotime($last_login);
		//$last_login = strtotime("+$time_diff hours", strtotime($last_login));
		$now = time(); //current time
		$units = 1; //units to show... eg. 9 hours ago, 3 weeks ago. 
		if ( ($now - $last_login) <= login_refresh_time) {
			return '<span class="text-success text-bold">Online<sup><i class="fa fa-dot-circle-o fa-pulse"></i></sup></span>';
		} else {
			return strtolower(timespan($last_login, $now, $units)). ' ago';
		}
    }
	
	
	function x_date($date) { //format date in the form eg 23rd Aug, 2018 from timestamp in db
		return date("jS M, Y", strtotime($date));
	}

	
	function check_date($date) {
		return ($date != NULL) ? x_date($date) : '';
	}

	
	function x_date_full($date) { //format date in the form eg 23rd August, 2018 from timestamp in db
		return date("jS F, Y", strtotime($date));
	}


	function x_date_time($time) {
	   $date = date("jS F, Y", strtotime($time)). ' at ' .date("h:i A", strtotime($time));
	   return $date;
    }


	function x_time_12hour($date) { //eg 05:20 PM
		return date("h:i A", strtotime($date));
	}


	function x_time_24hour($date) { //eg 17:20
		return date("h:i A", strtotime($date));
	}
	
	
	function default_calendar_date() { //default date for bootstrap calendar box
		$current_day = date('Y/m/d'); //in the format yyyy/mm/dd
		return $current_day;
	}

	function date_today() { //default date for bootstrap calendar box
		$current_day = date('d/m/Y'); //in the format dd/mm/yyyy
		return $current_day;
	}


	function default_html_date() { //default date for html calendar box
		$current_day = date('m/d/Y'); //in the format mm/dd/yyyy
		return $current_day;
	}
	
	
	function get_slug($title) { //get slug from titles and captions for use in URL
		$title = str_replace(' ', '-', $title);	//replace space in title with hyphen
		$slug = preg_replace('/[^A-Za-z0-9\-]/', '', $title);	//allowed xters. Otherwise, remove
		return strtolower($slug);
	}


	function get_session_slug($session) { //session is saved as yyyy/yyyy, get as yyyy-yyyyy
		$session_slug = str_replace('/', '-', $session);	//replace slash with hyphen
		return $session_slug;
	}


	function get_the_session($session_slug) { //session slug is saved as yyyy-yyyy, get as yyyy/yyyyy
		$session = str_replace('-', '/', $session_slug);	//replace hyphen with slash
		return $session;
	}


	function get_students_new_report($session, $term, $class_id, $target_grade = FALSE) { 
		$CI =& get_instance();
		$students = $CI->common_model->get_students_list_by_class($class_id);
		?>
			<div class="row">
				<div class="col-md-8 col-sm-12 col-xs-12">
					<table id="students_new_report_table" class="table table-no-border cell-text-middle">
			            <thead>
			                <tr>
			                    <th class="w-5-p"></th>
			                    <th class="min-w-200"></th>
			                    <th></th>
			                </tr>
			            </thead>
			            <tbody>

			            	<?php
			            	$count = 1;
			            	foreach ($students as $s) {
			            		$student_id = $s->id;
			            		$student_name = $CI->common_model->get_student_fullname($student_id);
			            		$produce_report_url = $CI->c_controller.'/produce_report/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id; ?>

			            		<tr>
			            			<td><?php echo $count; ?></td>
			            			<td><?php echo $student_name; ?></td>
			            			<td>
			            				<a class="btn btn-success btn-sm" href="<?php echo base_url($produce_report_url); ?>">Produce Report</a>
				            			<?php
				            			if ($target_grade) {
				            				$target_grade_url = $CI->c_controller.'/target_grade/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id; ?>
				            				<a class="btn btn-success btn-sm" href="<?php echo base_url($target_grade_url); ?>">Target Grade</a>
				            			<?php } ?>
				            		</td>
			            		</tr>
			            		<?php $count++;
			            	} ?>
			             
			            </tbody>
			        </table>
				</div>
			</div>
	    <?php
	}
	
	
	function generate_image_preview() { //generate image preview
		$onclick = 'document.getElementById(\'remove_image\').click()';
		return '<div id="image_preview_area">
					<img id="image_preview" alt="Generating image preview..." />
					<div class="remove_image_area">
						<input id="remove_image" style="display: none" />
						<input type="button" class="btn btn-danger btn-sm" onclick="' .$onclick. '" value="X" title="Remove image" />
					</div>
				</div>';
	}


	function pagination_links($links, $ul_class) {
		$link_list = "";
		foreach ($links as $link) {
			$link_list .= '<li>' . $link . '</li>';
		} 
		$pagination_links 	= 	'<ul class="' . $ul_class . '">'
				        			. $link_list .
				    			'</ul>';
		return $pagination_links;
	}


	function get_array_value_rank($array_values, $val, $ordinalize = FALSE) {
		$ordered_values = $array_values;
		//sort the values in descending order i.e highest value first
		rsort($ordered_values);
		$ranks = [];
		foreach ($array_values as $key => $value) {
		    foreach ($ordered_values as $ordered_key => $ordered_value) {
		        if ($value === $ordered_value) {
		            $key = $ordered_key;
		            break;
		        }
		    }
		    $rank = (int) $key + 1; //because array is indexed from 0
		    //create associative array of values and their ranks
		    $ranks[$value] = $rank;
		}
		//return ordinalized rank if allowed, else, return just rank
		return ($ordinalize == FALSE) ? $ranks[$val] : get_ordinal_number($ranks[$val]);
	}


	function get_ordinal_value($number) {
		//NOTE: There is a CI4 helper function for this purpose using the inflector helper  
    	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
    	if ((($number % 100) >= 11) && (($number%100) <= 13)) {
        	$ordninal = 'th';
    	} else {
        	$ordninal = $ends[$number % 10];
		}
		return $ordninal;
	}


	function get_ordinal_number($number) {
		//NOTE: There is a CI4 helper function for this purpose using the inflector helper  
    	$ends = array('th','st','nd','rd','th','th','th','th','th','th');
    	if ((($number % 100) >= 11) && (($number%100) <= 13)) {
        	$ordninal = $number . 'th';
    	} else {
        	$ordninal = $number . $ends[$number % 10];
		}
		return $ordninal;
	}


	function custom_calendar_template() {
		//custom calendar template
		return '{table_open}<table class="cal_table" cellpadding="1" cellspacing="2">{/table_open}

					//heading row
					{heading_row_start}<tr>{/heading_row_start}

						//previous month link
						{heading_previous_cell}
							<th class="prev_sign"><a href="{previous_url}">&laquo;</a></th>
						{/heading_previous_cell}
						
						//title i.e. Month & Year
						{heading_title_cell}
							<th colspan="{colspan}">{heading}</th>
						{/heading_title_cell}
						
						//next month link
						{heading_next_cell}
							<th class="next_sign"><a href="{next_url}">&raquo;</a></th>
						{/heading_next_cell}

					{heading_row_end}</tr>{/heading_row_end}

					//Deciding where week row starts
					{week_row_start}<tr class="week_name">{/week_row_start}
						//Deciding week day cell and week days
						{week_day_cell}<td>{week_day}</td>{/week_day_cell}
						//week row end
					{week_row_end}</tr>{/week_row_end}

					//days
					{cal_row_start}<tr>{/cal_row_start}
						{cal_cell_start}<td>{/cal_cell_start}

							//cell with content
							{cal_cell_content}
								{content}
							{/cal_cell_content}
							
							//cell with content current day
							{cal_cell_content_today}
								{content}
							{/cal_cell_content_today}

							//cell without content
							{cal_cell_no_content}
								{day}
							{/cal_cell_no_content}
							
							//cell without content current day
							{cal_cell_no_content_today}
								<div class="today">
									{day}
								</div>
							{/cal_cell_no_content_today}

							//blank cell i.e. cells in a month without day in it
							{cal_cell_blank}
								&nbsp;
							{/cal_cell_blank}

						{cal_cell_end}</td>{/cal_cell_end}
						
					{cal_row_end}</tr>{/cal_row_end}

				{table_close}</table>{/table_close}';
	}
	
	
	function get_month_value_short($value) { 
		switch ($value) {
			case '01':
				return 'Jan';
				break;
			case '02':
				return 'Feb';
				break;
			case '03':
				return 'Mar';
				break;
			case '04':
				return 'Apr';
				break;
			case '05':
				return 'May';
				break;
			case '06':
				return 'Jun';
				break;
			case '07':
				return 'Jul';
				break;
			case '08':
				return 'Aug';
				break;
			case '09':
				return 'Sep';
				break;
			case '10':
				return 'Oct';
				break;
			case '11':
				return 'Nov';
				break;
			case '12':
				return 'Dec';
				break;
		}
	}
	
	
	function get_month_value_long($value) { 
		switch ($value) {
			case '01':
				return 'January';
				break;
			case '02':
				return 'February';
				break;
			case '03':
				return 'March';
				break;
			case '04':
				return 'April';
				break;
			case '05':
				return 'May';
				break;
			case '06':
				return 'June';
				break;
			case '07':
				return 'July';
				break;
			case '08':
				return 'August';
				break;
			case '09':
				return 'September';
				break;
			case '10':
				return 'October';
				break;
			case '11':
				return 'November';
				break;
			case '12':
				return 'December';
				break;
		}
	}
	
	
	function get_month_value_short_array()	{ 
		$data = array(
			'01' => 'Jan',
			'02' => 'Feb',
			'03' => 'Mar',
			'04' => 'Apr',
			'05' => 'May',
			'06' => 'Jun',
			'07' => 'Jul',
			'08' => 'Aug',
			'09' => 'Sep',
			'10' => 'Oct',
			'11' => 'Nov',
			'12' => 'Dec'
		);
		return $data;
	}
	
	
	function get_month_value_long_array()	{ 
		$data = array(
			'01' => 'January',
			'02' => 'February',
			'03' => 'March',
			'04' => 'April',
			'05' => 'May',
			'06' => 'June',
			'07' => 'July',
			'08' => 'August',
			'09' => 'September',
			'10' => 'October',
			'11' => 'November',
			'12' => 'December'
		);
		return $data;
	}


	function checkbox_bulk_action($id)	{ 
		return '<input type="checkbox" class="flat bulk_select_box" name="check_bulk_action[]" value="' .$id. '" />';
	}


	function bulk_select_options($options) {
		$select_options = "";
		foreach ($options as $value => $caption) {
			$select_options .= '<option value="'.$value.'" >'.$caption.'</option>';
		} 
		return $select_options;
	}
	
	
	function modal_bulk_actions($form_url, $options) {
		return form_open($form_url).
				'<div class="row bulk-section">
				<div class="col-md-5">
					With selected: <br/> 
					<select class="form-control bulk-element bulk_action_type" name="bulk_action_type">
						<option value="" class="no_item">Bulk Action</option>'
						. bulk_select_options($options) .
					'</select>
					<input type="button" class="btn btn-primary btn-sm bulk-element bulk_action_btn" data-toggle="modal" data-target="#bulk_action_confirm" title="Options" value="Apply" disabled />
				</div>
			</div>
			<div class="modal fade" id="bulk_action_confirm" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<div class="pull-right">
								<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
							</div>
							<h4 class="modal-title"> <i class="fa fa-database"></i> Bulk Actions</h4>
						</div><!--/.modal-header-->
						<div class="modal-body">
							Note that all selected records will be affected. Are you sure you want to continue? 
						</div>
						<div class="modal-footer">
							<input type="submit" class="btn btn-primary" href="' .$form_url. '" value="Yes, Continue" />
							<button data-dismiss="modal" class="btn btn-default"> No, Cancel </button>
						</div>
					</div>
				</div>
			</div>';
	}
	
	
	function modal_bulk_actions_alt($form_url, $options) { //with select all box just below Apply button
		return form_open($form_url).
				'<div class="row bulk-section">
				<div class="col-md-5">
					With selected: <br/> 
					<select class="form-control bulk-element bulk_action_type" name="bulk_action_type">
						<option value="" class="no_item">Bulk Action</option>'
						. bulk_select_options($options) .
					'</select>
					<input type="button" class="btn btn-primary btn-sm bulk-element bulk_action_btn" data-toggle="modal" data-target="#bulk_action_confirm" title="Options" value="Apply" disabled />
					<br/>
					<input type="checkbox" class="radio-box select_all" /> Select All
				</div>
			</div>
			<div class="modal fade" id="bulk_action_confirm" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<div class="pull-right">
								<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
							</div>
							<h4 class="modal-title"> <i class="fa fa-database"></i> Bulk Actions</h4>
						</div><!--/.modal-header-->
						<div class="modal-body">
							Note that all selected records will be affected. Are you sure you want to continue? 
						</div>
						<div class="modal-footer">
							<input type="submit" class="btn btn-primary" href="' .$form_url. '" value="Yes, Continue" />
							<button data-dismiss="modal" class="btn btn-default"> No, Cancel </button>
						</div>
					</div>
				</div>
			</div>';
	}
	
	
	function delete_bulk_items($column, $table) { //remind to remove
		$CI =& get_instance();
		$row_id = $CI->input->post('check_bulk_action', TRUE);		
		foreach ($row_id as $id) {
			$CI->db->delete($table, array($column => $id));
		} 
	}
	
	
	function modal_delete_confirm($id, $title, $item, $url, $custom_msg = NULL) { //confirm delete actions
		return '<div class="modal fade" id="delete'.$id.'" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">Delete: ' . $title . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						Are you sure you want to permanently delete this ' . $item . '?
						<p class="m-t-10"> ' . $custom_msg . '</p>
					</div>
					<div class="modal-footer">
						<a class="btn btn-sm btn-danger" role="button" href="' .base_url($url.'/'.$id). '"> Yes, Delete </a>
						<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>';
	} 
	
	
	function modal_confirm_action($modal_id, $title, $custom_msg, $url) { //confirm actions
		return '<div class="modal fade" id="' . $modal_id . '" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title">' . $title . '</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						<p> ' . $custom_msg . '</p>
					</div>
					<div class="modal-footer">
						<a class="btn btn-sm btn-danger" role="button" href="' .base_url($url). '"> Yes, Continue </a>
						<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>';
	} 


	function modal_select_class($school_id, $modal_id, $modal_title, $form_url, $button_caption, $redirect_method = NULL) { 
		$CI =& get_instance();
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
						
						. form_open($form_url) .
						
							'<input type="hidden" name="redirect_method" value="' . $redirect_method . '" />

							<div class="form-group">
								<label class="form-control-label">Select Class</label>
								<select class="form-control selectpicker" name="class_id" required>
									<option value="">-Select-</option>'
									. $CI->common_model->classes_option_by_section_group($school_id) .
								'</select>
								<div class="form-error">' . form_error('class_id') . '</div>
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


	function modal_select_subject_teacher_class($staff_id, $modal_id, $modal_title, $form_url, $button_caption, $redirect_method = NULL) { 
		$CI =& get_instance();
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
						
						. form_open($form_url) .
						
							'<input type="hidden" name="redirect_method" value="' . $redirect_method . '" />

							<div class="form-group">
								<label class="form-control-label">Select Class</label>
								<select class="form-control selectpicker" name="class_id" required>
									<option value="">-Select-</option>'
									. $CI->common_model->subject_teacher_classes_option($staff_id) .
								'</select>
								<div class="form-error">' . form_error('class_id') . '</div>
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


	function modal_select_archived_data($year_installed, $modal_id, $modal_title, $form_url, $current_session, $current_term, $button_caption, $redirect_method = NULL) { 
		$current_session_slug = get_session_slug($current_session);
		$selected_1st_term = ($current_term == '1st') ? 'selected' : NULL;
		$selected_2nd_term = ($current_term == '2nd') ? 'selected' : NULL;
		$selected_3rd_term = ($current_term == '3rd') ? 'selected' : NULL;
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
						
						. form_open($form_url) .

							'<input type="hidden" name="redirect_method" value="' . $redirect_method . '" />
						
							<div class="form-group">
								<label class="form-control-label">Session (since ' . $year_installed . ')</label>
								<select class="form-control" name="session" required>'
									. get_sessions_spent($year_installed, $current_session_slug) .
								'</select>
								<div class="form-error">' . form_error('session') . '</div>
							</div>
						
							<div class="form-group">
								<label class="form-control-label">Term</label>
								<select class="form-control" name="term" required>
									<option ' . $selected_1st_term . ' value="1st" ' . set_select('term', '1st') . '>1st</option>
									<option ' . $selected_2nd_term . ' value="2nd" ' . set_select('term', '2nd') . '>2nd</option>
									<option ' . $selected_3rd_term . ' value="3rd" ' . set_select('term', '3rd') . '>3rd</option>
								</select>
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


	function modal_select_archived_class_data($school_id, $year_installed, $modal_id, $modal_title, $form_url, $current_session, $current_term, $button_caption, $redirect_method = NULL) { 
		$current_session_slug = get_session_slug($current_session);
		$CI =& get_instance();
		$selected_1st_term = ($current_term == '1st') ? 'selected' : NULL;
		$selected_2nd_term = ($current_term == '2nd') ? 'selected' : NULL;
		$selected_3rd_term = ($current_term == '3rd') ? 'selected' : NULL;
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
						
						. form_open($form_url) .
						
							'<input type="hidden" name="redirect_method" value="' . $redirect_method . '" />

							<div class="form-group">
								<label class="form-control-label">Session (since ' . $year_installed . ')</label>
								<select class="form-control" name="session" required>'
									. get_sessions_spent($year_installed, $current_session_slug) .
								'</select>
								<div class="form-error">' . form_error('session') . '</div>
							</div>
						
							<div class="form-group">
								<label class="form-control-label">Term</label>
								<select class="form-control" name="term" required>
									<option ' . $selected_1st_term . ' value="1st" ' . set_select('term', '1st') . '>1st</option>
									<option ' . $selected_2nd_term . ' value="2nd" ' . set_select('term', '2nd') . '>2nd</option>
									<option ' . $selected_3rd_term . ' value="3rd" ' . set_select('term', '3rd') . '>3rd</option>
								</select>
							</div>

							<div class="form-group">
								<label class="form-control-label">Select Class</label>
								<select class="form-control selectpicker" name="class_id" required>
									<option value="">-Select-</option>'
									. $CI->common_model->classes_option_by_section_group($school_id) .
								'</select>
								<div class="form-error">' . form_error('class_id') . '</div>
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
	
	
	function demo_roles_admin() {
		$roles = array(
			'chief_admin' => 'Chief Admin, School Director/Proprietor',
			'head_teacher' => 'Head Teacher',
			'principal' => 'Principal',
		);
		return $roles;
	}
	
	
	function demo_roles_staff() {
		$roles = array(
			'all' => 'All',
			'class_teacher' => 'Class Teacher',
			'subject_teacher' => 'Subject Teacher',
			'bursar' => 'Bursar/Accountant',
			'librarian' => 'Librarian',
			'publication_manager' => 'Publication Manager',
		);
		return $roles;
	}
	
	
	function demo_roles_student() {
		$roles = array(
			'female_student' => 'Female Student',
			'male_student' => 'Male Student',
		);
		return $roles;
	}
	
	
	function demo_roles_parent() {
		$roles = array(
			'1' => 'One Child',
			'2' => 'Two Children',
			'3' => 'Three Children',
		);
		return $roles;
	}


	function predefined_class_teacher_comments() {
		$comments = array(
			'Excellent result. Keep it up!',
			'Very good result. Keep it up!',
			'Good result. Keep it up!',
			'Good result but you can do better.',
			'Good result but there is still room for improvement.',
			'An average result. You you can do better.',
			'An average result. Put more effort.',
			'A pass. Pay more attention to your studies.',
			'Poor result. Pay more attention to your studies.',
		);
		return $comments;
	}


	function grade_chart_colors() {
		$colors = array(
			'#d9534f',	//0-9
			'#d9534f',  //10-19
			'#d9534f',  //20-29
			'#d9534f',  //30-39
			'#3498DB',  //40-49
			'#455C73',  //50-59
			'#3498DB',  //60-69
			'#26B99A',  //70-79
			'#BDC3C7',  //80-89
			'#9B59B6',  //90-100
		);
		return $colors;
	}

	
	function predefined_class_teacher_comment_options() {
		$comments = predefined_class_teacher_comments();
		$comment_options = "";
		foreach ($comments as $comment) {
			$comment_options .= '<option value="' . $comment . '">' . $comment . '</option>';
		}
		return $comment_options;
	}


	function get_sessions_spent($year_installed, $current_session = NULL) {
		//starting from year school installed app to current year
		//if app was installed in 2018, start year is 2017, in order to get first session as 2017/2018
		$start_year = $year_installed - 1; 
		$current_year = date('Y');
		$session_options = "";
		for ($i = $current_year; $i >= $start_year; $i--) { 
			$session = $i . '/' . ($i + 1); //human-friendly session for display eg 2017/2018
			$session_slug = $i . '-' . ($i + 1); //url-friendly session for storage eg 2017-2018
			$selected = ($current_session == $session_slug) ? 'selected' : NULL;
			$session_options .= '<option ' . $selected . ' value="' . $session_slug . '" ' . set_select('session', $session_slug) . '>' . $session . '</option>';
		}
		return $session_options; 
	}


	function get_referrers() {
		$referrers = array(
			'Google',
			'Other Search Engine',
			'Promotional Email',
			'SMS',
			'Social Media',
			'From a Friend',
			'Other',
		);
		return $referrers;
	}
	
	
	function nigerian_banks() {
		$banks = array (
			'Access Bank',
			'Diamond Bank',
			'Dynamic Standard Bank',
			'Ecobank',
			'Enterprise Bank',
			'Fidelity Bank',
			'First Bank',
			'First City Monument Bank (FCMB)',
			'Guaranty Trust Bank (GTB)',
			'Heritage Bank',
			'Jaiz Bank',
			'Keystone Bank',
			'Mainstreet Bank',
			'Providus Bank',
			'Skye Bank',
			'Stanbic IBTC Bank',
			'Standard Chartered Bank',
			'Sterling Bank',
			'Suntrust Bank',
			'Union Bank',
			'United Bank for Africa (UBA)',
			'Unity Bank',
			'Wema Bank',
			'Zenith Bank',
		);
		return $banks; 
	}
	
	
	function countries() {
		$countries = array (
			'Afghanistan',
			'Albania',
			'Algeria',
			'Andorra',
			'Angola',
			'Antigua and Barbuda',
			'Argentina',
			'Armenia',
			'Aruba',
			'Australia',
			'Austria',
			'Azerbaijan',
			'Bahamas', 
			'The Bahrain',
			'Bangladesh',
			'Barbados',
			'Belarus',
			'Belgium',
			'Belize',
			'Benin',
			'Bhutan',
			'Bolivia',
			'Bosnia and Herzegovina',
			'Botswana',
			'Brazil',
			'Brunei',
			'Bulgaria',
			'Burkina Faso',
			'Burma',
			'Burundi',
			'Cambodia',
			'Cameroon',
			'Canada',
			'Cabo Verde',
			'Central African Republic',
			'Chad',
			'Chile',
			'China',
			'Colombia',
			'Comoros',
			'Congo', 
			'Democratic Republic of the Congo', 
			'Costa Rica',
			'Cote d Ivoire',
			'Croatia',
			'Cuba',
			'Curacao',
			'Cyprus',
			'Czechia',
			'Denmark',
			'Djibouti',
			'Dominica',
			'Dominican Republic',
			'East Timor (see Timor-Leste)',
			'Ecuador',
			'Egypt',
			'El Salvador',
			'Equatorial Guinea',
			'Eritrea',
			'Estonia',
			'Ethiopia',
			'Fiji',
			'Finland',
			'France',
			'Gabon',
			'Gambia', 
			'Georgia',
			'Germany',
			'Ghana',
			'Greece',
			'Grenada',
			'Guatemala',
			'Guinea',
			'Guinea-Bissau',
			'Guyana',
			'Haiti',
			'Holy See',
			'Honduras',
			'Hong Kong',
			'Hungary',
			'Iceland',
			'India',
			'Indonesia',
			'Iran',
			'Iraq',
			'Ireland',
			'Israel',
			'Italy',
			'Jamaica',
			'Japan',
			'Jordan',
			'Kazakhstan',
			'Kenya',
			'Kiribati',
			'Korea, North',
			'Korea, South',
			'Kosovo',
			'Kuwait',
			'Kyrgyzstan',
			'Laos',
			'Latvia',
			'Lebanon',
			'Lesotho',
			'Liberia',
			'Libya',
			'Liechtenstein',
			'Lithuania',
			'Luxembourg',
			'Macau',
			'Macedonia',
			'Madagascar',
			'Malawi',
			'Malaysia',
			'Maldives',
			'Mali',
			'Malta',
			'Marshall Islands',
			'Mauritania',
			'Mauritius',
			'Mexico',
			'Micronesia',
			'Moldova',
			'Monaco',
			'Mongolia',
			'Montenegro',
			'Morocco',
			'Mozambique',
			'Namibia',
			'Nauru',
			'Nepal',
			'Netherlands',
			'New Zealand',
			'Nicaragua',
			'Niger',
			'Nigeria',
			'North Korea',
			'Norway',
			'Oman',
			'Pakistan',
			'Palau',
			'Palestinian Territories',
			'Panama',
			'Papua New Guinea',
			'Paraguay',
			'Peru',
			'Philippines',
			'Poland',
			'Portugal',
			'Qatar',
			'Romania',
			'Russia',
			'Rwanda',
			'Saint Kitts and Nevis',
			'Saint Lucia',
			'Saint Vincent and the Grenadines',
			'Samoa',
			'San Marino',
			'Sao Tome and Principe',
			'Saudi Arabia',
			'Senegal',
			'Serbia',
			'Seychelles',
			'Sierra Leone',
			'Singapore',
			'Sint Maarten',
			'Slovakia',
			'Slovenia',
			'Solomon Islands',
			'Somalia',
			'South Africa',
			'South Korea',
			'South Sudan',
			'Spain',
			'Sri Lanka',
			'Sudan',
			'Suriname',
			'Swaziland',
			'Sweden',
			'Switzerland',
			'Syria',
			'Taiwan',
			'Tajikistan',
			'Tanzania',
			'Thailand',
			'Timor-Leste',
			'Togo',
			'Tonga',
			'Trinidad and Tobago',
			'Tunisia',
			'Turkey',
			'Turkmenistan',
			'Tuvalu',
			'Uganda',
			'Ukraine',
			'United Arab Emirates',
			'United Kingdom (UK)',
			'United States of America (USA)',
			'Uruguay',
			'Uzbekistan',
			'Vanuatu',
			'Venezuela',
			'Vietnam',
			'Yemen',
			'Zambia',
			'Zimbabwe',
			
		);
		return $countries; 
	}
	
	
	function currency_codes() {
		$currencies = array (
			'Albania Lek' => '76',
			'Afghanistan Afghani' => '1547',
			'Argentina Peso' => '36',
			'Aruba Guilder' => '402',
			'Australia Dollar' => '36',
			'Azerbaijan New Manat' => '1084',
			'Bahamas Dollar' => '36',
			'Barbados Dollar' => '36',
			'Belarus Ruble'	=> '66',
			'Belize Dollar' =>	'66',
			'Bermuda Dollar' => '36',
			'Bolivia Bolíviano'	=> '36',
			'Bosnia & Herzegovina Convertible Marka' => '75',
			'Botswana Pula'	=> '80',
			'Bulgaria Lev' => '1083',
			'Brazil Real' => '82',
			'Brunei Darussalam Dollar' => '36',
			'Cambodia Riel'	=>	'6107',
			'Canada Dollar'	=>	'36',
			'Cayman Islands Dollar'	=>	'36',
			'Chile Peso'	=>	'36',
			'China Yuan Renminbi'	=>	'165',
			'Colombia Peso'	=>	'36',
			'Costa Rica Colon'	=>	'8353',
			'Croatia Kuna'	=>	'107',
			'Cuba Peso'	=>	'8369',
			'Czech Republic Koruna'	=>	'75',
			'Denmark Krone'	=>	'107',
			'Dominican Republic Peso'	=>	'82',
			'East Caribbean Dollar'	=>	'36',
			'Egypt Pound'	=>	'163',
			'El Salvador Colon'	=>	'36',
			'Euro Member Countries'	=>	'8364',
			'Falkland Islands (Malvinas) Pound'	=>	'163',
			'Fiji Dollar'	=>	'36',
			'Ghana Cedi'	=>	'162',
			'Gibraltar Pound'	=>	'163',
			'Guatemala Quetzal'	=>	'81',
			'Guernsey Pound'	=>	'163',
			'Guyana Dollar'	=>	'36',
			'Honduras Lempira'	=>	'76',
			'Hong Kong Dollar'	=>	'36',
			'Hungary Forint'	=>	'70',
			'Iceland Krona'	=>	'107',
			'India Rupee'	=>	'8377',
			'Indonesia Rupiah'	=>	'82',
			'Iran Rial'	=>	'65020',
			'Isle of Man Pound'	=>	'163',
			'Israel Shekel'	=>	'8362',
			'Jamaica Dollar'	=>	'74',
			'Japan Yen'	=>	'165',
			'Jersey Pound'	=>	'163',
			'Kazakhstan Tenge'	=>	'1083',
			'Korea (North) Won'	=>	'8361',
			'Korea (South) Won'	=>	'8361',
			'Kyrgyzstan Som'	=>	'1083',
			'Laos Kip'	=>	'8365',
			'Lebanon Pound'	=>	'163',
			'Liberia Dollar'	=>	'36',
			'Macedonia Denar'	=>	'1076',
			'Malaysia Ringgit'	=>	'82',
			'Mauritius Rupee'	=>	'8360',
			'Mexico Peso'	=>	'36',
			'Mongolia Tughrik'	=>	'8366',
			'Mozambique Metical'	=>	'77',
			'Namibia Dollar'	=>	'36',
			'Nepal Rupee'	=>	'8360',
			'Netherlands Antilles Guilder'	=>	'402',
			'New Zealand Dollar'	=>	'36',
			'Nicaragua Cordoba'	=>	'67',
			'Nigeria Naira'	=>	'8358',
			'Korea (North) Won'	=>	'8361',
			'Norway Krone'	=>	'107',
			'Oman Rial'	=>	'65020',
			'Pakistan Rupee'	=>	'8360',
			'Panama Balboa'	=>	'66',
			'Paraguay Guarani'	=>	'71',
			'Peru Sol'	=>	'83',
			'Philippines Peso'	=>	'8369',
			'Poland Zloty'	=>	'122',
			'Qatar Riyal'	=>	'65020',
			'Romania New Leu'	=>	'108',
			'Russia Ruble'	=>	'1088',
			'Saint Helena Pound'	=>	'163',
			'Saudi Arabia Riyal'	=>	'65020',
			'Serbia Dinar'	=>	'1044',
			'Seychelles Rupee'	=>	'8360',
			'Singapore Dollar'	=>	'36',
			'Solomon Islands Dollar'	=>	'36',
			'Somalia Shilling'	=>	'83',
			'South Africa Rand'	=>	'82',
			'Korea (South) Won'	=>	'8361',
			'Sri Lanka Rupee'	=>	'8360',
			'Sweden Krona'	=>	'107',
			'Switzerland Franc'	=>	'67',
			'Suriname Dollar'	=>	'36',
			'Syria Pound'	=>	'163',
			'Taiwan New Dollar'	=>	'78',
			'Thailand Baht'	=>	'3647',
			'Trinidad and Tobago Dollar'	=>	'84',
			'Turkey Lira'	=>	'8378',
			'Tuvalu Dollar'	=>	'36',
			'Ukraine Hryvnia'	=>	'8372',
			'United Kingdom Pound'	=>	'163',
			'United States Dollar'	=>	'36',
			'Uruguay Peso'	=>	'36',
			'Uzbekistan Som'	=>	'1083',
			'Venezuela Bolivar'	=>	'66',
			'Viet Nam Dong'	=>	'8363',
			'Yemen Rial'	=>	'65020',
			'Zimbabwe Dollar'	=>	'90',
		);
		return $currencies;
	}
	
	
	function get_currency_symbol($currency_code) {
		$currency_symbol = '&#'.$currency_code.';';
		return $currency_symbol; 
	}


	function get_price_by_location($plan_id, $location, $price) {
		if ($location == 'Nigeria') {
			$price = '&#8358;'.$price; //Nigerian Naira currency
		} else {
			$price = '&#36;'.$price; //Dollar currency
		}
		return $price; 
	}


	function morris_chart_legend($legend_data_array) {
		foreach ($legend_data_array as $label => $color) { ?>
			<div class="morris_line_legend">
				<i class="fa fa-line-chart"></i>
				<span><?php echo $label; ?></span>
			</div>	
		<?php } 
	}


	function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
	    $output = NULL;
	    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
	        $ip = $_SERVER["REMOTE_ADDR"];
	        if ($deep_detect) {
	            if (filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
	                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	            if (filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
	                $ip = $_SERVER['HTTP_CLIENT_IP'];
	        }
	    }
	    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
	    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
	    $continents = array(
	        "AF" => "Africa",
	        "AN" => "Antarctica",
	        "AS" => "Asia",
	        "EU" => "Europe",
	        "OC" => "Australia (Oceania)",
	        "NA" => "North America",
	        "SA" => "South America"
	    );
	    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
	        $ipdat = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
	        if (strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
	            switch ($purpose) {
	                case "location":
	                    $output = array(
	                        "city"           => $ipdat->geoplugin_city,
	                        "state"          => $ipdat->geoplugin_regionName,
	                        "country"        => $ipdat->geoplugin_countryName,
	                        "country_code"   => $ipdat->geoplugin_countryCode,
	                        "continent"      => $continents[strtoupper($ipdat->geoplugin_continentCode)],
	                        "continent_code" => $ipdat->geoplugin_continentCode
	                    );
	                    break;
	                case "address":
	                    $address = array($ipdat->geoplugin_countryName);
	                    if (strlen($ipdat->geoplugin_regionName) >= 1)
	                        $address[] = $ipdat->geoplugin_regionName;
	                    if (strlen($ipdat->geoplugin_city) >= 1)
	                        $address[] = $ipdat->geoplugin_city;
	                    $output = implode(", ", array_reverse($address));
	                    break;
	                case "city":
	                    $output = $ipdat->geoplugin_city;
	                    break;
	                case "state":
	                    $output = $ipdat->geoplugin_regionName;
	                    break;
	                case "region":
	                    $output = $ipdat->geoplugin_regionName;
	                    break;
	                case "country":
	                    $output = $ipdat->geoplugin_countryName;
	                    break;
	                case "countrycode":
	                    $output = $ipdat->geoplugin_countryCode;
	                    break;
	            }
	        }
	    }
	    return $output;
	}


	function ip_info_safe($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
	    $output = NULL;
	    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
	        $ip = $_SERVER["REMOTE_ADDR"];
	        if ($deep_detect) {
	            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
	                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
	                $ip = $_SERVER['HTTP_CLIENT_IP'];
	        }
	    }
	    $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
	    $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
	    $continents = array(
	        "AF" => "Africa",
	        "AN" => "Antarctica",
	        "AS" => "Asia",
	        "EU" => "Europe",
	        "OC" => "Australia (Oceania)",
	        "NA" => "North America",
	        "SA" => "South America"
	    );
	    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
	        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
	        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
	            switch ($purpose) {
	                case "location":
	                    $output = array(
	                        "city"           => @$ipdat->geoplugin_city,
	                        "state"          => @$ipdat->geoplugin_regionName,
	                        "country"        => @$ipdat->geoplugin_countryName,
	                        "country_code"   => @$ipdat->geoplugin_countryCode,
	                        "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
	                        "continent_code" => @$ipdat->geoplugin_continentCode
	                    );
	                    break;
	                case "address":
	                    $address = array($ipdat->geoplugin_countryName);
	                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
	                        $address[] = $ipdat->geoplugin_regionName;
	                    if (@strlen($ipdat->geoplugin_city) >= 1)
	                        $address[] = $ipdat->geoplugin_city;
	                    $output = implode(", ", array_reverse($address));
	                    break;
	                case "city":
	                    $output = @$ipdat->geoplugin_city;
	                    break;
	                case "state":
	                    $output = @$ipdat->geoplugin_regionName;
	                    break;
	                case "region":
	                    $output = @$ipdat->geoplugin_regionName;
	                    break;
	                case "country":
	                    $output = @$ipdat->geoplugin_countryName;
	                    break;
	                case "countrycode":
	                    $output = @$ipdat->geoplugin_countryCode;
	                    break;
	            }
	        }
	    }
	    return $output;
	}


	function nigerian_states() {
		$states = array(
				'Abuja',
				'Abia',
				'Adamawa',
				'Akwa Ibom',
				'Anambra',
				'Bauchi',
				'Bayelsa',
				'Benue',
				'Borno',
				'Cross River',
				'Delta',
				'Ebonyi',
				'Edo',
				'Ekiti',
				'Enugu',
				'Gombe',
				'Imo',
				'Jigawa',
				'Kaduna',
				'Kano',
				'Katsina',
				'Kebbi',
				'Kogi',
				'Kwara',
				'Lagos',
				'Nasarawa',
				'Niger',
				'Ogun',
				'Ondo',
				'Osun',
				'Oyo',
				'Plateau',
				'Rivers',
				'Sokoto',
				'Taraba',
				'Yobe',
				'Zamfara',
			);
		return $states;
	}
	
	