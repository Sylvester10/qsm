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
							if ($student_details->passport != NULL) { ?>
								<img src="<?php echo base_url('assets/uploads/photos/students/'.$student_details->passport); ?>" alt="..."  class="img-circle profile_img">
							<?php } else { ?>
								<img src="<?php echo ($student_details->sex == 'Male') ? male_student_avatar : female_student_avatar; ?>" alt="..." class="img-circle profile_img">
							<?php } ?>
						</div>
						<div class="profile_info">
							<span>Welcome,</span>
							<h2><?php echo $student_fullname; ?></h2>
						</div>
					</div><!-- /menu profile quick info -->

					<br />

					<!-- sidebar menu -->
					<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
						<div class="menu_section">
							<ul class="nav side-menu">

								
								<li><a href="<?php echo base_url('student'); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>

								<li><a href="<?php echo base_url('student/school_info'); ?>"><i class="fa fa-institution"></i>School Info</a></li>

								<li><a href="<?php echo base_url('student/student_class'); ?>"><i class="fa fa-users"></i>My Class</a></li>

								<li><a href="<?php echo base_url('student/attendance'); ?>"><i class="fa fa-refresh"></i>My Attendance</a></li>


								<?php if ($homework_module) { ?>

									<li><a href="<?php echo base_url('homework_student/homework'); ?>"><i class="fa fa-tasks"></i>My Homework</a></li>

								<?php } ?>


								<?php if ($fee_management_module) { ?>

									<li><a href="<?php echo base_url('student/school_fees'); ?>"><i class="fa fa-money"></i>My School Fees</a></li>

								<?php } ?>


								<?php if ($student_reports_module) { 
									
									//bespoke for kad academy
									if (school_id == kad_school_id) {
										$mt_reports_controller = 'kad_student_mid_term_reports';
										$et_reports_controller = 'kad_student_end_term_reports';
									} else {
										$mt_reports_controller = 'student_mid_term_reports';
										$et_reports_controller = 'student_reports';
									} ?>

									<li><a href="<?php echo base_url($mt_reports_controller.'/check_result'); ?>"><i class="fa fa-line-chart"></i>Check Mid-Term Result</a></li>

									<li><a href="<?php echo base_url($et_reports_controller.'/check_result'); ?>"><i class="fa fa-line-chart"></i>Check End-of-Term Result</a></li>

								<?php } ?>


								<?php if ($timetable_module) { ?>

									<li><a><i class="fa fa-clock-o"></i>My Timetable <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
											<li><a href="<?php echo base_url('timetable_student/lesson_periods'); ?>">Lesson Periods</a></li>
											<li><a href="<?php echo base_url('timetable_student/test_schedules'); ?>">Test Schedules</a></li>
											<li><a href="<?php echo base_url('timetable_student/exam_schedules'); ?>">Exam Schedules</a></li>
										</ul>
									</li>

								<?php } ?>


								<?php if ($publication_management_module) { ?>

									<li><a><i class="fa fa-bullhorn"></i>School Publications <span class="fa fa-chevron-down"></span></a>
										<ul class="nav child_menu">
											<li><a href="<?php echo base_url('school_publications_student/term_dates'); ?>">Term Dates</a></li>
											<li><a href="<?php echo base_url('school_publications_student/news'); ?>">News</a></li>
											<li><a href="<?php echo base_url('school_publications_student/newsletters'); ?>">Newsletters</a></li>
											<li><a href="<?php echo base_url('school_publications_student/calendar_dates'); ?>">Events Calendar</a></li>
											<li><a href="<?php echo base_url('school_publications_student/calendar_dates_list'); ?>">Events List</a></li>
										</ul>
									</li>

								<?php } ?>


								<li><a href="<?php echo base_url('student/profile'); ?>"><i class="fa fa-user"></i>Profile</a></li>

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
									if ($student_details->passport != NULL) { ?>
										<img src="<?php echo base_url('assets/uploads/photos/students/'.$student_details->passport); ?>" alt="...">
									<?php } else { ?>
										<img src="<?php echo ($student_details->sex == 'Male') ? male_student_avatar : female_student_avatar; ?>">
									<?php } ?>
									<?php echo $student_fullname; ?>
									<span class=" fa fa-angle-down"></span>
								</a>
								<ul class="dropdown-menu dropdown-usermenu pull-right">
									<li><a href="<?php echo base_url('student/profile'); ?>"><i class="fa fa-user pull-right"></i> Profile</a></li>
									<li><a href="<?php echo base_url($logout_url); ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
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

						<div class="col-md-7">	
						
							<?php 
							//if school website url is provided, create a link with the school name, else display name w/o link
							if (school_website == software_website) { ?>
								<h3 class="text-bold"><a href="<?php echo base_url('student'); ?>" title="Dashboard"><?php echo software_initials; ?></a> &raquo; <?php echo school_name; ?> <small><i class="fa fa-user"></i> Student</small></h3>
							<?php } else { ?>
								<h3 class="text-bold"><a href="<?php echo base_url('student'); ?>" title="Dashboard"><?php echo software_initials; ?></a> &raquo; <a href="<?php echo school_website; ?>" target="_blank" title="Visit school website"><?php echo school_name; ?></a> <small><i class="fa fa-user"></i> Student</small></h3>
							<?php } ?>
							
						</div>

						<div class="col-md-5">
							<div class="pull-right">
								<h4>My Class: <?php echo $student_class; ?></h4>
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


			<?php 
			//check if general announcement has been created for this school
			if ( $general_announcement ) { 

				$gen_announcement = 'ANNOUNCEMENT &raquo; ' . $general_announcement->announcement; ?>

				<div class="alert alert-info text-center text-bold f-s-18 announcement_scroll">
					<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>
					<div class="j_marquee m-l-5 m-r-5"><?php echo $gen_announcement; ?></div>
				</div>

			<?php } ?>
			
			
			
			<?php
			//check if in demo mode
			if ($this->session->demo_student_loggedin) { 
			
				//check if super user
				if ($this->session->demo_super_user_student) { ?>
				
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

					
		