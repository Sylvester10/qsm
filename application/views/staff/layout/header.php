<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- No Javascript -->
	<?php echo check_javascript_enabled(); ?>
	
	<link rel="icon" href="<?php echo school_favicon; ?>" type="image/png" />

    <title><?php echo $title; ?> | <?php echo software_name; ?> </title>

    <?php 
	//require header assets files
	require "application/views/shared/assets/header_assets.php"; 
	require "application/views/staff/layout/modals/header_modals.php"; ?>
	
</head>

<body class="nav-md">
  
    <div class="container body">
		<div class="main_container">
			<div class="col-md-3 left_col">
				<div class="left_col scroll-view">
					<div class="navbar nav_title" style="border: 0;">
						<a href="<?php echo base_url(); ?>" class="site_title" target="_blank"><i class="fa fa-graduation-cap"></i> <span><?php echo software_initials; ?></span></a>
					</div>

					<div class="clearfix"></div>

					<!-- menu profile quick info -->
					<div class="profile clearfix">
						<div class="profile_pic">
							<?php 
							if ($staff_details->passport_photo != NULL) { ?>
								<img src="<?php echo base_url('assets/uploads/photos/staff/'.$staff_details->passport); ?>" alt="..." class="img-circle profile_img">
							<?php } else { ?>
								<img src="<?php echo user_avatar; ?>" alt="..." class="img-circle profile_img">
							<?php } ?>
						</div>
						<div class="profile_info">
							<span>Welcome,</span>
							<h2><?php echo $staff_details->name; ?></h2>
						</div>
					</div><!-- /menu profile quick info -->

					<br />

					<!-- sidebar menu -->
					<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
						<div class="menu_section">
							<ul class="nav side-menu">
							
								<li><a href="<?php echo base_url('staff'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>

								<li><a href="<?php echo base_url('staff/school_info'); ?>"><i class="fa fa-institution"></i>School Info</a></li>


								<?php 
								//show this to class teacher
								if ($this->common_model->get_staff_role('Class Teacher')) { ?>

									<div class="divider"></div>
									<div class="m-t-15"></div>
								 	<h3>Class Teacher</h3>

									<?php if ($student_management_module) { ?>

										<li><a><i class="fa fa-users"></i> Students <span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="<?php echo base_url('class_teacher/single_class'); ?>">My Students</a></li>
											</ul>
										</li>

										<li><a><i class="fa fa-refresh"></i> Attendance <span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="<?php echo base_url('class_teacher/attendance'); ?>">Mark Attendance</a></li>
											</ul>
										</li>

									<?php } ?>


									<?php if ($student_reports_module) { ?>
									
										<?php
										//bespoke for kad academy
										if (school_id == kad_school_id) {
											$mt_reports_controller = 'kad_student_mid_term_reports_class_teacher';
											$et_reports_controller = 'kad_student_end_term_reports_class_teacher';
										} else {
											$mt_reports_controller = 'student_mid_term_reports_class_teacher';
											$et_reports_controller = 'student_reports_class_teacher';
										}

										//check if teacher is currently assigned to a class
										$query_class_assigned = $this->common_model->get_class_details_by_teacher($staff_details->id);

										if ($query_class_assigned) { //teacher is assigned to a class, get class id; 
											$teacher_class_id = $query_class_assigned->id; ?>

											<li><a><i class="fa fa-line-chart"></i> Student Reports (KAD)<span class="fa fa-chevron-down"></span></a>
												<ul class="nav child_menu">
													<li><a href="<?php echo base_url($mt_reports_controller.'/reports/'.current_session_slug.'/'.current_term.'/'.$teacher_class_id); ?>">Mid-Term Reports</a></li>
													<li><a href="<?php echo base_url($et_reports_controller.'/reports/'.current_session_slug.'/'.current_term.'/'.$teacher_class_id); ?>">End-of-Term Reports</a></li>
												</ul>
											</li>

										<?php } ?>

									<?php } ?>


									<?php if ($homework_module) { ?>

										<li><a><i class="fa fa-tasks"></i> Homework <span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="<?php echo base_url('homework_class_teacher/new_homework'); ?>">New Homework</a></li>
												<li><a href="<?php echo base_url('homework_class_teacher/homework'); ?>">All Homework</a></li>
											</ul>
										</li>	

									<?php } ?>

								<?php } // endif class teacher ?>


								

								<?php 
								//show this to subject teacher
								if ($this->common_model->get_staff_role('Subject Teacher')) { ?>

									<div class="divider"></div>
									<div class="m-t-15"></div>
								 	<h3>Subject Teacher</h3>

									<li><a><i class="fa fa-users"></i> Students <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
											<li><a href="#" data-toggle="modal" data-target="#select_class_st_single_class">My Classes</a></li>
										</ul>
									</li>

									<?php if ($student_reports_module) { ?>

										<li><a><i class="fa fa-line-chart"></i> Student Reports (KAD)<span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="#" data-toggle="modal" data-target="#select_class_st_mid_term_reports">Mid-Term Reports</a></li>
												<li><a href="#" data-toggle="modal" data-target="#select_class_st_end_term_reports">End-of-Term Reports</a></li>
											</ul>
										</li>


									<?php } ?>


									<?php if ($homework_module) { ?>

										<li><a><i class="fa fa-tasks"></i> Homework <span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="#" data-toggle="modal" data-target="#select_class_st_new_homework">New Homework</a></li>
												<li><a href="#" data-toggle="modal" data-target="#select_class_st_all_homework">All Homework</a></li>
											</ul>
										</li>	

									<?php } ?>


								<?php } //endif subject teacher ?>


								<div class="divider"></div>



								<?php if ($timetable_module) { ?>

									<li><a><i class="fa fa-clock-o"></i> Time Tabling <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
											
											<?php
											//check if staff is a class teacher and currently assigned to a class
											if ($this->common_model->get_staff_role('Class Teacher')) { 

												$class_teacher_query = $this->common_model->get_class_details_by_teacher($staff_details->id);

												if ($class_teacher_query) { 
													$teacher_class_id = $class_teacher_query->id;
													$teacher_class = $class_teacher_query->class;
													//staff is teacher and currently assigned, show lesson period for teacher's class ?>
				
													<li><a href="<?php echo base_url('timetable_staff/lesson_periods/'.$teacher_class_id); ?>">Lesson Periods: <?php echo $teacher_class; ?></a></li>

													<li><a href="<?php echo base_url('timetable_staff/class_test_schedules/'.$teacher_class_id); ?>">Test Schedules: <?php echo $teacher_class; ?></a></li>

													<li><a href="<?php echo base_url('timetable_staff/class_exam_schedules/'.$teacher_class_id); ?>">Exam Schedules: <?php echo $teacher_class; ?></a></li>

												<?php } 

											} ?>

											<li><a href="#" data-toggle="modal" data-target="#select_class_lesson_periods">Lesson Periods</a></li>
											<li><a href="#" data-toggle="modal" data-target="#select_class_test_schedules">Test Schedules</a></li>
											<li><a href="<?php echo base_url('timetable_staff/test_schedules'); ?>">All Test Schedules</a></li>
											<li><a href="#" data-toggle="modal" data-target="#select_class_exam_schedules">Exam Schedules</a></li>
											<li><a href="<?php echo base_url('timetable_staff/exam_schedules'); ?>">All Exam Schedules</a></li>
										</ul>
									</li>	

								<?php } ?>



								<?php 
								//show this to bursar
								if ($this->common_model->get_staff_role('Bursar')) { ?>

									<?php if ($fee_management_module) { ?>

										<li><a><i class="fa fa-money"></i> Fees <span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="<?php echo base_url('fees_staff/fee_categories'); ?>">Fee Categories</a></li>
												<li><a href="<?php echo base_url('fees_staff/fee_discount_categories'); ?>">Fee Discount Categories</a></li>
												<li><a href="<?php echo base_url('fees_staff/new_class_fees'); ?>">New Class Fees</a></li>
												<li><a href="<?php echo base_url('fees_staff/manage_fees'); ?>">Manage Fees</a></li>
												<li><a href="<?php echo base_url('fees_staff/payment_summary/'.current_session_slug.'/'.current_term); ?>">Payment Summary</a></li>
												<li><a href="#" data-toggle="modal" data-target="#select_class_collect_fees">Collect Fees</a></li>
												<li><a href="#" data-toggle="modal" data-target="#select_class_full_fee_payment">Full Payment</a></li>
												<li><a href="#" data-toggle="modal" data-target="#select_class_partial_fee_payment">Partial Payment</a></li>
												<li><a href="#" data-toggle="modal" data-target="#select_class_no_fee_payment">No Payment</a></li>
											</ul>
										</li>


										<li><a><i class="fa fa-money"></i> Archived Fees <span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="#" data-toggle="modal" data-target="#select_archive_full_fee_payment">Full Payment</a></li>
												<li><a href="#" data-toggle="modal" data-target="#select_archive_partial_fee_payment">Partial Payment</a></li>
												<li><a href="#" data-toggle="modal" data-target="#select_archive_no_fee_payment">No Payment</a></li>
												<li><a href="#" data-toggle="modal" data-target="#select_archive_fee_payment_summary">Payment Summary</a></li>	
											</ul>
										</li>

									<?php } ?>

								<?php } // endif bursar ?>

								
								
								<?php 
								//show this to librarian
								if ($this->common_model->get_staff_role('Librarian')) { ?>

									<?php if ($school_library_module) { ?>
							
										<li><a><i class="fa fa-book"></i> School Library <span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="<?php echo base_url('school_library_staff/new_book'); ?>">New Book</a></li>
												<li><a href="<?php echo base_url('school_library_staff'); ?>">All Books</a></li>
												<li><a href="<?php echo base_url('school_library_staff/borrowed_books'); ?>">Borrowed Books</a></li>
											</ul>
										</li>

									<?php } ?>

								<?php } // endif librarian ?>
								
							

								<?php 
								//show this to administrative staff (principals, head teachers)
								if ($this->common_model->get_staff_role('Academic Administrator')) { ?>

									<?php if ($requisitions_module) { ?>
							
										<li><a><i class="fa fa-money"></i>Requisitions <span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="<?php echo base_url('prs_staff/initiate_request'); ?>">Initiate Request</a></li>
											<li><a href="<?php echo base_url('prs_staff/requests/'.current_session_slug.'/'.current_term); ?>">All Requests</a></li>
											<li><a href="<?php echo base_url('prs_staff/pending_requests/'.current_session_slug.'/'.current_term); ?>">Pending Requests</a></li>
											<li><a href="<?php echo base_url('prs_staff/approved_requests/'.current_session_slug.'/'.current_term); ?>">Approved Requests</a></li>
											<li><a href="<?php echo base_url('prs_staff/declined_requests/'.current_session_slug.'/'.current_term); ?>">Declined Requests</a></li>
											</ul>
										</li>


										<li><a><i class="fa fa-money"></i> Archived Requisitions <span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="#" data-toggle="modal" data-target="#select_archive_all_requests">All Requests</a></li>
												<li><a href="#" data-toggle="modal" data-target="#select_archive_pending_requests">Pending Requests</a></li>
												<li><a href="#" data-toggle="modal" data-target="#select_archive_approved_requests">Approved Requests</a></li>
												<li><a href="#" data-toggle="modal" data-target="#select_archive_declined_requests">Declined Requests</a></li>
											</ul>
										</li>     

									<?php } ?> 
								
								<?php } //endif academic administrator  ?>

								

								<?php if ($weekly_reports_module) { ?>
							
									<li><a><i class="fa fa-tasks"></i> Staff Reports <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
											<li><a href="<?php echo base_url('weekly_reports_staff/submit_weekly_report'); ?>">New Report</a></li>
											<li><a href="<?php echo base_url('weekly_reports_staff/weekly_reports/'.current_session_slug.'/'.current_term); ?>">All Reports</a></li>
											<li><a href="#" data-toggle="modal" data-target="#select_archive_weekly_reports">Archived Reports</a></li>
										</ul>
									</li> 

								<?php } ?>     



								<?php if ($publication_management_module) { ?>
		
									<li><a><i class="fa fa-bullhorn"></i> School Publications <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
											<li><a href="<?php echo base_url('school_publications_staff/term_dates'); ?>">Term Dates</a></li>
											<li><a href="<?php echo base_url('school_publications_staff/news'); ?>">News</a></li>
											<li><a href="<?php echo base_url('school_publications_staff/newsletters'); ?>">Newsletters</a></li>
											<li><a href="<?php echo base_url('school_publications_staff/calendar_dates'); ?>">Events Calendar</a></li>
											<li><a href="<?php echo base_url('school_publications_staff/calendar_dates_list'); ?>">Events List</a></li>
										</ul>
									</li>

								<?php } ?>
								
								
								
								<?php 
								//show this to website content manager
								if ($this->common_model->get_staff_role('Publication Manager')) { ?>

									<?php if ($publication_management_module) { ?>
							
										<li><a href="<?php echo base_url('publications_staff/term_dates'); ?>"><i class="fa fa-calendar-check-o"></i>Term Dates</a></li>

									
										<li><a><i class="fa fa-bullhorn"></i> Announcement <span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="<?php echo base_url('publications_staff/general_announcement'); ?>">General Announcement</a></li>
												<li><a href="<?php echo base_url('publications_staff/staff_announcement'); ?>">Staff Announcement</a></li>
											</ul>
										</li> 

										
										<li><a><i class="fa fa-book"></i> News <span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="<?php echo base_url('publications_staff/create_news'); ?>">Create News</a></li>
												<li><a href="<?php echo base_url('publications_staff/news'); ?>">News</a></li>
											</ul>
										</li> 

											
										<li><a><i class="fa fa-envelope-o"></i> Newsletter <span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="<?php echo base_url('publications_staff/create_newsletter'); ?>">Create Newsletters</a></li>
												<li><a href="<?php echo base_url('publications_staff/newsletters'); ?>">Newsletters</a></li>
											</ul>
										</li>

										
										<li><a><i class="fa fa-calendar"></i> Calendar Events <span class="fa fa-chevron-down"></span></a>
											<ul class="nav child_menu">
												<li><a href="<?php echo base_url('publications_staff/calendar_dates'); ?>">Calendar Grid</a></li>
												<li><a href="<?php echo base_url('publications_staff/calendar_dates_list'); ?>">Calendar List</a></li>
											</ul>
										</li>

									<?php } ?>
									
								<?php } // endif publication manager ?>


								<li><a><i class="fa fa-user"></i> Profile <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('staff/profile'); ?>">My Profile</a></li>
										<li><a href="<?php echo base_url('staff/signature'); ?>">Signature</a></li>
										<li><a href="<?php echo base_url('staff/notifications'); ?>">Notifications</a></li>
									</ul>
								</li>
								
								<li><a><i class="fa fa-question-circle"></i> Help <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo software_vendor_site; ?>">Contact Vendor</a></li>
									</ul>
								</li>	
								
								<li><a href="<?php echo base_url($logout_url); ?>"><i class="fa fa-sign-out"></i> Logout</a></li>
								
							</ul>
						</div>
					</div><!-- /sidebar menu -->
				</div>
			</div>

			<!-- top navigation -->
			<div class="top_nav">
				<div class="nav_menu">
					<nav>
						<div class="nav toggle">
							<a id="menu_toggle" title="Toggle Sidebar Menu"><i class="fa fa-bars"></i><span class="text-bold f-s-22"> MENU</span></a>
						</div>

						<ul class="nav navbar-nav navbar-right">
						
							<li class="">
								<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<?php 
									if ($staff_details->passport_photo != NULL) { ?>
										<img src="<?php echo base_url('assets/uploads/photos/staff/'.$staff_details->passport); ?>" alt="...">
									<?php } else { ?>
										<img src="<?php echo user_avatar; ?>">
									<?php } ?>
									<?php echo $staff_details->name; ?>
									<span class=" fa fa-angle-down"></span>
								</a>
								<ul class="dropdown-menu dropdown-usermenu pull-right">
									<li><a href="<?php echo software_vendor_site; ?>"><i class="fa fa-external-link pull-right"></i> Contact Vendor</a></li>
									<li><a href="<?php echo base_url('staff/profile'); ?>"><i class="fa fa-user pull-right"></i> Profile</a></li>
									<li><a href="<?php echo base_url($logout_url); ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
								</ul>
							</li>

							<li role="presentation" class="dropdown">
								<a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false" title="Notifications">
									<i class="fa fa-bell"></i>
									<?php if ($total_unread_notifs > 0) { ?>
										<span class="badge bg-green"><?php echo $total_unread_notifs; ?></span>
									<?php } ?>
								</a>
								<ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
									<?php if ($total_unread_notifs > 0) { 
										foreach ($unread_notifs as $x) { ?>	
											<li>
												<a href="#!">
													<div class="pull-right"><?php echo time_ago($x->date); ?></div>
													<br />
													<h5><i class="fa fa-bell"></i> <?php echo $x->subject; ?></h5>
													<p class="message" style="white-space: pre-line;"><?php echo $x->message; ?></p>
												</a>
											</li>
										<?php } 
									} else { ?>
										<li><a href="#!">No new notification.</a></li>
									<?php } ?>
									<li>
										<div class="text-center">
											<a href="<?php echo base_url('staff/notifications'); ?>">
												<strong>See all notifications</strong>
												&raquo;
											</a>
										</div>
									</li>
								</ul>
							</li>
							
						</ul>
					</nav>
				</div>
			</div><!-- /top navigation -->

			
        <div class="right_col" role="main">
		
			<div class="row m-b-15">
				<div class="col-md-12" style="border-bottom: 1px solid #f2f2f2;">
					<div class="row">
						<div class="col-md-8">
						
							<?php 
							//if school website url is provided, create a link with the school name, else display name w/o link
							if (school_website == software_website) { ?>
								<h3 class="text-bold"><a href="<?php echo base_url('staff'); ?>" title="Dashboard"><?php echo software_initials; ?></a> &raquo; <?php echo school_name; ?> <small><i class="fa fa-user"></i> Staff</small></h3>
							<?php } else { ?>
								<h3 class="text-bold"><a href="<?php echo base_url('staff'); ?>" title="Dashboard"><?php echo software_initials; ?></a> &raquo; <a href="<?php echo school_website; ?>" target="_blank" title="Visit school website"><?php echo school_name; ?></a> <small><i class="fa fa-user"></i> Staff</small></h3>
							<?php } ?>
							
						</div>
						<div class="col-md-4">
							<div class="pull-right">
								<button class="btn btn-default" id="search_btn" title="Search student by name or admission ID"> <i class="fa fa-search"></i> Search Student</button>
								<button class="btn btn-default" id="collapse_btn" style="display: none"><i class="fa fa-eye-slash"></i> Hide Search</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-6">
					<p>Current Session: <?php echo current_session; ?></p>
					<p>Current Term: <?php echo current_term; ?></p>
				</div>
				<div class="col-md-6">	
					<div class="pull-right">
						<p>Today's Date: <?php echo date('l, d M, Y'); ?></p>
						<p>Current Time: <span id="current_ime"></span></p>
					</div>
				</div>
			</div>

			<div class="row m-b-15">
				<div class="col-md-12">
					<!--Search Student-->
					<div class="search_area">
						<div id="the_search_area" style="display: none">
							<p>Search a student by name or admission ID</p>
							<table id="search_student_table" class="table table-bordered cell-text-middle" style="text-align: left">
								<input type="hidden" id="csrf_hash_search" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								
								<thead>
									<tr>
										<th> Name </th>
										<th> Registration ID </th>
										<th> Admission ID </th>
										<th> Class </th>
										<th> Actions </th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>	
				</div>
			</div>


			<?php 
			//check if general announcement has been created for this school
			if ( $general_announcement ) { 

				$gen_announcement = 'GENERAL ANNOUNCEMENT &raquo; ' . $general_announcement->announcement; ?>

				<div class="alert alert-info text-center text-bold f-s-18 announcement_scroll">
					<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>
					<div class="j_marquee m-l-5 m-r-5"><?php echo $gen_announcement; ?></div>
				</div>

			<?php } ?>


			<?php 
			//check if staff announcement has been created for this school
			if ( $staff_announcement ) { 

				$sta_announcement = 'STAFF ANNOUNCEMENT &raquo; ' . $staff_announcement->announcement; ?>

				<div class="alert alert-info text-center text-bold f-s-18 announcement_scroll">
					<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>
					<div class="j_marquee m-l-5 m-r-5"><?php echo $sta_announcement; ?></div>
				</div>

			<?php } ?>
			
			
			
			<?php
			//check if in demo mode
			if ($this->session->demo_staff_loggedin) { 
			
				//check if super user
				if ($this->session->demo_super_user_staff) { ?>
				
					<div class="pull-right">
						<div class="label label-info text-white f-s-17" title="You are currently in demo mode as a super user">Demo Mode: Super User</div>
					</div>
					
				<?php } else { ?>
			
					<div class="pull-right">
						<div class="label label-info text-white f-s-17" title="You are currently in demo mode">Demo Mode</div>
					</div>
					
				<?php } ?>
				
			<?php } ?>
			
			
			
			<div class="x_panel">
				<div class="x_title">
					<h2 class="page_title"><?php echo $inner_page_title; ?></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<!-- page content -->
					
		