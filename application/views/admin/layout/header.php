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
		//require header files
		require "application/views/shared/assets/header_assets.php"; 
		require "application/views/admin/layout/modals/header_modals.php"; 
	?>

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
							if ($admin_details->photo != NULL) { ?>
								<img src="<?php echo base_url('assets/uploads/photos/admins/'.$admin_details->photo_thumb); ?>" alt="..."  class="img-circle profile_img">
							<?php } else { ?>
								<img src="<?php echo user_avatar; ?>" alt="..." class="img-circle profile_img">
							<?php } ?>
						</div>
						<div class="profile_info">
							<span>Welcome,</span>
							<h2><?php echo $admin_details->name; ?></h2>
						</div>
					</div><!-- /menu profile quick info -->

					<br />

					<!-- sidebar menu -->
					<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
						<div class="menu_section">
							<ul class="nav side-menu">

								<li><a href="<?php echo base_url('admin'); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
								
								<?php 
								//show activation page link if account has not been activated
								if (school_account_activation_status == 'false') { ?>

									<li><a href="<?php echo base_url('activate'); ?>"><i class="fa fa-unlock-alt"></i>Activate Account</a></li>

								<?php } ?>


								<?php 
								//show plan upgrade page link if current plan is not Pro Plus
								if (school_plan_id != 3) { ?>

									<li class="hide"><a href="<?php echo base_url('plan/upgrade'); ?>"><i class="fa fa-signal"></i>Upgrade Account</a></li>

								<?php } ?>


								<li><a href="<?php echo base_url('plan/plans'); ?>"><i class="fa fa-object-group"></i>Plans & Features</a></li>

								<li><a href="<?php echo base_url('plan/account_info'); ?>"><i class="fa fa-credit-card"></i>Account Info</a></li>

								<li><a><i class="fa fa-cog"></i> Settings <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('settings/term_info'); ?>">Term Settings</a></li>
										<li><a href="<?php echo base_url('settings/mid_term_report_settings'); ?>">Mid-Term Report Settings</a></li>
										<li><a href="<?php echo base_url('settings/report_settings'); ?>">End of Term Report Settings</a></li>
										<li><a href="<?php echo base_url('settings/school_stamp'); ?>">School Stamp</a></li>
										<li><a href="<?php echo base_url('settings/school_info'); ?>">School Info</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-cubes"></i> Sections <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('sections/new_section'); ?>">New Section</a></li>
										<li><a href="<?php echo base_url('sections'); ?>">Manage Sections</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-cubes"></i> Subjects <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('subjects'); ?>">Manage Subjects</a></li>
										<li><a href="<?php echo base_url('subjects/subject_groups'); ?>">Subject Groups</a></li>
									</ul>
								</li>

								
								<li><a><i class="fa fa-cubes"></i> Classes <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('classes'); ?>">Manage Classes</a></li>
										<li><a href="#" data-toggle="modal" data-target="#select_class_view">View Class</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-user-plus"></i> Students <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('students_admin/student_registration'); ?>">New Student</a></li>
										<li><a href="<?php echo base_url('students_admin/students'); ?>">All Students</a></li>
										<li><a href="<?php echo base_url('students_admin/suspended_students'); ?>">Suspended Students</a></li>
										<li><a href="<?php echo base_url('students_admin/revoked_students'); ?>">Revoked Students</a></li>
										<li><a href="<?php echo base_url('students_admin/graduated_students'); ?>">Graduated Students</a></li>
										<li><a href="<?php echo base_url('student_import/import_students'); ?>">Import Students</a></li>
										<li><a href="<?php echo base_url('student_import/imported_students'); ?>">Outstanding Imports</a></li>
										<li><a href="<?php echo base_url('student'); ?>" target="_blank">Open Student Panel</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-refresh"></i> Attendance <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="#" data-toggle="modal" data-target="#select_class_attendance">Attendance</a></li>
										<li><a href="#" data-toggle="modal" data-target="#select_archived_class_attendance">Archived Attendance</a></li>
									</ul>
								</li>


								<?php
								//KAD Academy Student Reports (Bespoke)
								if (school_id == kad_school_id) { ?>

									<li><a><i class="fa fa-line-chart"></i> Student Reports (KAD)<span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
											<li><a href="#" data-toggle="modal" data-target="#select_class_mid_term_reports">Mid-Term Reports</a></li>
											<li><a href="#" data-toggle="modal" data-target="#select_archived_mid_term_reports">Archived Mid-term Reports</a></li>
											<li class="divider"></li>
											<li><a href="#" data-toggle="modal" data-target="#select_class_end_term_reports">End-of-Term Reports</a></li>
											<li><a href="#" data-toggle="modal" data-target="#select_archived_end_term_reports">Archived End-of-term Reports</a></li>
										</ul>
									</li>

								<?php } else { ?>
								
									<li><a><i class="fa fa-line-chart"></i> Student Reports <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
											<li><a href="#" data-toggle="modal" data-target="#select_class_mid_term_reports">Mid-Term Reports</a></li>
											<li><a href="#" data-toggle="modal" data-target="#select_archived_mid_term_reports">Archived Mid-term Reports</a></li>
											<li class="divider"></li>
											<li><a href="#" data-toggle="modal" data-target="#select_class_end_term_reports">End-of-Term Reports</a></li>
											<li><a href="#" data-toggle="modal" data-target="#select_archived_end_term_reports">Archived End-of-term Reports</a></li>
										</ul>
									</li>

								<?php } ?>

								
								<li><a><i class="fa fa-tasks"></i> Homework <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="#" data-toggle="modal" data-target="#select_class_new_homework">New Homework</a></li>
										<li><a href="#" data-toggle="modal" data-target="#select_class_view_homework">Manage Homework</a></li>
										<li><a href="#" data-toggle="modal" data-target="#select_archive_clear_homework">Clear Homework</a></li>
									</ul>
								</li>	

								
								<li><a><i class="fa fa-clock-o"></i> Time Tabling <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="#" data-toggle="modal" data-target="#select_class_lesson_periods">Lesson Periods</a></li>
										<li><a href="#" data-toggle="modal" data-target="#select_class_test_schedules">Test Schedules</a></li>
										<li><a href="<?php echo base_url('timetable_admin/test_schedules'); ?>">Manage Test Schedules</a></li>
										<li><a href="#" data-toggle="modal" data-target="#select_class_exam_schedules">Exam Schedules</a></li>
										<li><a href="<?php echo base_url('timetable_admin/exam_schedules'); ?>">Manage Exam Schedules</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-money"></i> Fees <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('fees_admin/fee_categories'); ?>">Fee Categories</a></li>
										<li><a href="<?php echo base_url('fees_admin/fee_discount_categories'); ?>">Fee Discount Categories</a></li>
										<li><a href="<?php echo base_url('fees_admin/new_class_fees'); ?>">New Class Fees</a></li>
										<li><a href="<?php echo base_url('fees_admin/manage_fees'); ?>">Manage Fees</a></li>
										<li><a href="#" data-toggle="modal" data-target="#select_class_collect_fees">Collect Fees</a></li>
										<li><a href="#" data-toggle="modal" data-target="#select_class_full_fee_payment">Full Payment</a></li>
										<li><a href="#" data-toggle="modal" data-target="#select_class_partial_fee_payment">Partial Payment</a></li>
										<li><a href="#" data-toggle="modal" data-target="#select_class_no_fee_payment">No Payment</a></li>
										<li><a href="<?php echo base_url('fees_admin/payment_summary/'.current_session_slug.'/'.current_term); ?>">Payment Summary</a></li>
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

								
								<li><a><i class="fa fa-book"></i> School Library <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('school_library_admin/new_book'); ?>">New Book</a></li>
										<li><a href="<?php echo base_url('school_library_admin'); ?>">Manage Books</a></li>
										<li><a href="<?php echo base_url('school_library_admin/borrowed_books'); ?>">Borrowed Books</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-history"></i> Incidents <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('incidents'); ?>">Manage Incidents</a></li>
									</ul>
								</li>


								<?php 
								//ensure admin's level is 1 (Chief Admin) 
								if ($this->admin_details->level == 1) { ?>
								
									<li><a><i class="fa fa-money"></i> Requisitions <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
											<li><a href="<?php echo base_url('prs_admin/initiate_request'); ?>">Initiate Request</a></li>
											<li><a href="<?php echo base_url('prs_admin/requests/'.current_session_slug.'/'.current_term); ?>">All Requests</a></li>
											<li><a href="<?php echo base_url('prs_admin/pending_requests/'.current_session_slug.'/'.current_term); ?>">Pending Requests</a></li>
											<li><a href="<?php echo base_url('prs_admin/approved_requests/'.current_session_slug.'/'.current_term); ?>">Approved Requests</a></li>
											<li><a href="<?php echo base_url('prs_admin/declined_requests/'.current_session_slug.'/'.current_term); ?>">Declined Requests</a></li>
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


									<li><a><i class="fa fa-tasks"></i> Staff Reports <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
											<li><a href="<?php echo base_url('weekly_reports_admin/weekly_reports/'.current_session_slug.'/'.current_term); ?>">All Reports</a></li>
											<li><a href="#" data-toggle="modal" data-target="#select_archive_weekly_reports">Archived Reports</a></li>
											<li><a href="<?php echo base_url('weekly_reports_admin/weekly_report_types'); ?>">Report Types</a></li>
										</ul>
									</li> 

								<?php } //endif level == 1 ?>    


								<li><a><i class="fa fa-user-plus"></i> Parents <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('parents'); ?>">All Parents</a></li>
										<li><a href="<?php echo base_url('parents/new_parent'); ?>">New Parent</a></li>
										<li><a href="<?php echo base_url('school_parent'); ?>" target="_blank">Open Parent Panel</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-user-plus"></i> Staff <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('school_staff/new_staff'); ?>">New Staff</a></li>
										<li><a href="<?php echo base_url('school_staff'); ?>">All Staff</a></li>
										<li><a href="<?php echo base_url('school_staff/class_teachers'); ?>">Class Teachers</a></li>
										<li><a href="<?php echo base_url('school_staff/subject_teachers'); ?>">Subject Teachers</a></li>
										<li><a href="<?php echo base_url('school_staff/attendance'); ?>">Attendance</a></li>
										<li><a href="<?php echo base_url('staff_import/import_staff'); ?>">Import Staff</a></li>
										<li><a href="<?php echo base_url('staff_import/imported_staff'); ?>">Outstanding Imports</a></li>
										<li><a href="<?php echo base_url('staff'); ?>">Open Staff Panel</a></li>
									</ul>
								</li>


								<?php 
								//ensure admin's level is 1 (Chief Admin) 
								if ($this->admin_details->level == 1) { ?>

									<li><a><i class="fa fa-user-plus"></i> Admin Users <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
											<li><a href="<?php echo base_url('admin_users/new_admin'); ?>">New Admin</a></li>
											<li><a href="<?php echo base_url('admin_users'); ?>">All Admins</a></li>
										</ul>
									</li>

								<?php } //endif level == 1 ?>


								<li><a><i class="fa fa-bullhorn"></i> School Publications <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('school_publications_admin/term_dates'); ?>">Term Dates</a></li>
										<li><a href="<?php echo base_url('school_publications_admin/news'); ?>">News</a></li>
										<li><a href="<?php echo base_url('school_publications_admin/newsletters'); ?>">Newsletters</a></li>
										<li><a href="<?php echo base_url('school_publications_admin/calendar_dates'); ?>">Events Calendar</a></li>
										<li><a href="<?php echo base_url('school_publications_admin/calendar_dates_list'); ?>">Events List</a></li>
									</ul>
								</li>

								
								<li><a href="<?php echo base_url('publications_admin/term_dates'); ?>"><i class="fa fa-calendar-check-o"></i>Term Dates</a></li>

									
								<li><a><i class="fa fa-bullhorn"></i> Announcement <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('publications_admin/general_announcement'); ?>">General Announcement</a></li>
										<li><a href="<?php echo base_url('publications_admin/staff_announcement'); ?>">Staff Announcement</a></li>
									</ul>
								</li> 

								
								<li><a><i class="fa fa-book"></i> News <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('publications_admin/create_news'); ?>">Create News</a></li>
										<li><a href="<?php echo base_url('publications_admin/news'); ?>">News</a></li>
									</ul>
								</li> 

									
								<li><a><i class="fa fa-envelope-o"></i> Newsletter <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('publications_admin/create_newsletter'); ?>">Create Newsletters</a></li>
										<li><a href="<?php echo base_url('publications_admin/newsletters'); ?>">Newsletters</a></li>
									</ul>
								</li>

								
								<li><a><i class="fa fa-calendar"></i> Calendar Events <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('publications_admin/calendar_dates'); ?>">Calendar Grid</a></li>
										<li><a href="<?php echo base_url('publications_admin/calendar_dates_list'); ?>">Calendar List</a></li>
									</ul>
								</li>

									
								<li><a><i class="fa fa-user"></i> Profile <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('admin/profile'); ?>">My Profile</a></li>
										<li><a href="<?php echo base_url('admin/signature'); ?>">Signature</a></li>
										<li><a href="<?php echo base_url('admin/notifications'); ?>">Notifications</a></li>
									</ul>
								</li>
								
								
								<li><a><i class="fa fa-question-circle"></i> Help <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('admin/contact_vendor'); ?>">Contact Vendor</a></li>
									</ul>
								</li>
								
								
								<li><a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i>Visit Site</a></li>

								
								<li><a href="<?php echo base_url($logout_url); ?>"><i class="fa fa-sign-out"></i>Logout</a></li>

								
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
									if ($admin_details->photo != NULL) { ?>
										<img src="<?php echo base_url('assets/uploads/photos/admins/'.$admin_details->photo_thumb); ?>" alt="...">
									<?php } else { ?>
										<img src="<?php echo user_avatar; ?>">
									<?php } ?>
									<?php echo $admin_details->name; ?>
									<span class=" fa fa-angle-down"></span>
								</a>
								<ul class="dropdown-menu dropdown-usermenu pull-right">
									<li><a href="<?php echo base_url('admin/contact_vendor'); ?>"><i class="fa fa-envelope pull-right"></i> Contact Vendor</a></li>
									<li><a href="<?php echo base_url('admin/profile'); ?>"><i class="fa fa-user pull-right"></i> Profile</a></li>
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
											<a href="<?php echo base_url('admin/notifications'); ?>">
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
								<h3 class="text-bold"><a href="<?php echo base_url('admin'); ?>" title="Dashboard"><?php echo software_initials; ?></a> &raquo; <?php echo school_name; ?> <small><i class="fa fa-user"></i> Admin</small></h3>
							<?php } else { ?>
								<h3 class="text-bold"><a href="<?php echo base_url('admin'); ?>" title="Dashboard"><?php echo software_initials; ?></a> &raquo; <a href="<?php echo school_website; ?>" target="_blank" title="Visit school website"><?php echo school_name; ?></a> <small><i class="fa fa-user"></i> Admin</small></h3>
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
					<p>
						Account Plan: <?php echo school_plan; ?>
						<?php 
						//show upgrade call-to-action if plan is not Pro Plus
						if (school_plan_id != 3) { ?>
							<span class="text-bold text-primary m-l-5 hide"><a href="<?php echo  base_url('plan/upgrade'); ?>">Upgrade</a></span>
						<?php } ?>
					</p>
					<p>
						Account Mode: <?php echo (school_account_activation_status != 'false') ? 'Paid' : school_account_mode; ?>
						<?php 
						if (school_account_activation_status == 'false' && school_account_mode == 'Free Trial') { 
							echo ' (' . $free_trial_remaining_time . ')'; 
						} ?>
					</p>

					<?php 
					if (school_account_activation_status == 'false') {
						$h_activation_link = '<span class="text-bold text-primary m-l-5"><a href="' . base_url('activate') . '">Activate Now</a></span>';
						$school_account_activation_status = '<span class="text-danger text-bold">No</span>' . $h_activation_link;
					} else {
						$school_account_activation_status = '<span class="text-success text-bold">Yes</span>';
					} ?>

					<p>Activated: <?php echo $school_account_activation_status; ?></p>
					<p>Installed: <?php echo time_ago(date_installed); ?></p>	
				</div>
				<div class="col-md-6">	
					<div class="pull-right">
						<p>Current Session: <?php echo current_session; ?></p>
						<p>Current Term: <?php echo current_term; ?></p>
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
							<p>Search a student by name, registration or admission ID</p>
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
							<div>
								<a class="btn btn-primary" href="<?php echo base_url('students_admin/students'); ?>" target="_blank">View All Students</a>
							</div>
						</div>
					</div>	
				</div>
			</div>
			
			
			
			<?php
			//check if fresh account has been activated and display notice for Free Trial mode which has not expired
			if (free_trial_activation_status == 'false' && school_account_mode == 'Free Trial' && ! $expired_free_trial_account) { ?>
				<div class="alert alert-warning text-center text-bold f-s-17" style="color: #fff;">
					<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>
					Notice: You are on a 30-day Free Trial. Your Free Trial will expire on <?php echo account_expiration_date; ?>. If you wish to activate your account now, click <a class="notice-link" href="<?php echo base_url('activate'); ?>">here</a>
				</div>
			<?php } ?>


			<?php
			//check if fresh account has been activated and display notice for Paid mode
			if (free_trial_activation_status == 'false' && school_account_mode != 'Free Trial') { ?>
				<div class="alert alert-warning text-center text-bold f-s-17" style="color: #fff;">
					<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>
					Notice: School account not activated! <a class="notice-link" href="<?php echo base_url('activate'); ?>">Activate now</a> to unlock features under your plan 
				</div>
			<?php } ?>


			<?php
			//check if Free Trial Account has expired and display notice
			if ($expired_free_trial_account) { ?>
				<div class="alert alert-warning text-center text-bold f-s-17" style="color: #fff;">
					<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>
					Notice: Your 30-day free trial has expired! <a class="notice-link" href="<?php echo base_url('activate'); ?>">Activate now</a> to continue enjoying the features covered by your plan
				</div>
			<?php } ?>


			<?php
			//check if Annual Subscription Account is about to expire (some days days leading to expiration day) and display notice
			if ($about_expiring_annual_subscription && ! $expired_annual_subscription) { ?>
				<div class="alert alert-warning text-center text-bold f-s-17" style="color: #fff;">
					<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>
					Notice: Your annual subscription will expire in <?php echo $subscription_remaining_time; ?>! <a class="notice-link" href="<?php echo base_url('activate'); ?>">Renew now</a> to continue enjoying the features covered by your plan
				</div>
			<?php } ?>


			<?php
			//check if Annual Subscription Account has expired and display notice
			if ($expired_annual_subscription) { ?>
				<div class="alert alert-warning text-center text-bold f-s-17" style="color: #fff;">
					<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>
					Notice: Your annual subscription has expired! <a class="notice-link" href="<?php echo base_url('activate'); ?>">Renew now</a> to continue enjoying the features covered by your plan
				</div>
			<?php } ?>



			<?php 
			//check if general announcement has been created for this school
			if ( $vendor_announcement ) { 

				$vendor_announcement = 'NOTICE FROM VENDOR &raquo; ' . $vendor_announcement->announcement . ' &raquo; QSM Team'; ?>

				<div class="alert alert-success text-center text-bold f-s-16">
					<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>
					<div class="j_marquee m-l-5 m-r-5"><?php echo $vendor_announcement; ?></div>
				</div>

			<?php } ?>



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
			if ($this->session->demo_admin_loggedin) { 
			
				//check if super user
				if ($this->session->demo_super_user_admin) { ?>
				
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

					
		