<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo school_favicon_def; ?>" type="image/png" />

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
						<a href="<?php echo base_url('super_admin'); ?>" class="site_title"><i class="fa fa-graduation-cap"></i> <span><?php echo software_initials; ?></span></a>
					</div>

					<div class="clearfix"></div>

					<!-- menu profile quick info -->
					<div class="profile clearfix">
						<div class="profile_pic">
							<?php 
							if ($super_admin_details->photo != NULL) { ?>
								<img src="<?php echo base_url('assets/uploads/photos/super_admins/'.$super_admin_details->photo_thumb); ?>" alt="..."  class="img-circle profile_img">
							<?php } else { ?>
								<img src="<?php echo user_avatar; ?>" alt="..." class="img-circle profile_img">
							<?php } ?>
						</div>
						<div class="profile_info">
							<span>Welcome,</span>
							<h2><?php echo $super_admin_fname; ?></h2>
						</div>
					</div><!-- /menu profile quick info -->

					<br />

					<!-- sidebar menu -->
					<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
						<div class="menu_section">
							<ul class="nav side-menu">

								
								<li><a href="<?php echo base_url('super_admin'); ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>

								<li><a href="<?php echo base_url('super_admin/plans'); ?>"><i class="fa fa-object-group"></i>Plans & Modules</a></li>


								<li><a><i class="fa fa-institution"></i> School Accounts <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('school_account/activated_schools'); ?>">Activated Schools</a></li>
										<li><a href="<?php echo base_url('school_account/free_trial_schools'); ?>">Free Trial Schools</a></li>
										<li><a href="<?php echo base_url('school_account/expired_free_trial_schools'); ?>">Expired Free Trial Schools</a></li>
										<li><a href="<?php echo base_url('school_account/expired_annual_subscription_schools'); ?>">Expired Annual Subscription Schools</a></li>
										<li><a href="<?php echo base_url('school_account/schools'); ?>">All Schools</a></li>
										<li><a href="<?php echo base_url('admin'); ?>" target="_blank">Open Client Panel</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-users"></i> Demo Accounts <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('demo_accounts/admins'); ?>">Admins</a></li>
										<li><a href="<?php echo base_url('demo_accounts/staff'); ?>">Staff</a></li>
										<li><a href="<?php echo base_url('demo_accounts/students'); ?>">Students</a></li>
										<li><a href="<?php echo base_url('demo_accounts/parents'); ?>">Parents</a></li>
									</ul>
								</li>

								
								<li><a><i class="fa fa-database"></i> Data Management <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('data_management/data_backup'); ?>">Data Backup</a></li>
										<li><a href="<?php echo base_url('data_management/database_metadata'); ?>">Database Metadata</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-gift"></i> Coupons <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('coupon/free_trial_coupons'); ?>">Discount Vouchers</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-bullhorn"></i> Publications <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('publications_super_admin/announcement'); ?>">Announcement</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-bullhorn"></i> Testimonials <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('testimonial'); ?>">All Testimonials</a></li>
										<li><a href="<?php echo base_url('testimonial/published_testimonials'); ?>">Published Testimonials</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-image"></i> Gallery <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('gallery/screenshots'); ?>">All Screenshots</a></li>
										<li><a href="<?php echo base_url('gallery/published_screenshots'); ?>">Published Screenshots</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-question-circle"></i> Support <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('support/support_videos'); ?>">Support Videos</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-clock-o"></i> Cron Jobs <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('cron_jobs'); ?>">Cron Daemons</a></li>
									</ul>
								</li>
								
								
								<li><a><i class="fa fa-envelope"></i> Contact Messages <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('message/contact_messages'); ?>">Messages</a></li>
									</ul>
								</li>


								<li><a><i class="fa fa-code"></i> Developer <span class="fa fa-chevron-down"></span></a>
									<ul class="nav child_menu">
										<li><a href="<?php echo base_url('dev_test'); ?>">Dev Tests</a></li>
									</ul>
								</li>

								
								<li><a href="#<?php echo base_url('super_admin/profile'); ?>"><i class="fa fa-user"></i>Profile</a></li>


								<li><a href="<?php echo base_url('super_admin_logout'); ?>"><i class="fa fa-sign-out"></i>Logout</a></li>

								
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
									if ($super_admin_details->photo != NULL) { ?>
										<img src="<?php echo base_url('assets/uploads/photos/super_admins/'.$super_admin_details->photo_thumb); ?>" alt="...">
									<?php } else { ?>
										<img src="<?php echo user_avatar; ?>">
									<?php } ?>
									<?php echo $super_admin_fname; ?>
									<span class=" fa fa-angle-down"></span>
								</a>
								<ul class="dropdown-menu dropdown-usermenu pull-right">
									<li><a href="<?php echo base_url('super_admin/profile'); ?>"><i class="fa fa-user pull-right"></i> Profile</a></li>
									<li><a href="<?php echo base_url('super_admin_logout'); ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
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
							<h3 class="text-bold"><a href="<?php echo software_website; ?>" target="_blank" title="Visit software website"><?php echo software_name; ?></a> <a href="<?php echo base_url('super_admin'); ?>" title="Dashboard"><small><i class="fa fa-user"></i> Super Admin</small></a></h3>
						</div>
						<div class="col-md-4">
							<div class="pull-right">
								<button class="btn btn-default" id="#search_btn" title="Search school by name"> <i class="fa fa-search"></i> Search School</button>
								<button class="btn btn-default" id="#collapse_btn" style="display: none"><i class="fa fa-eye-slash"></i> Hide Search</button>
							</div>
						</div>
					</div>
				
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<p>Today's Date: <?php echo date('l, d M, Y'); ?></p>
				</div>
				<div class="col-md-6">
					<div class="pull-right">
						<p>Current Time: <span id="current_ime"></span></p>
					</div>
				</div>
			</div>


			<div class="row m-b-15">
				<div class="col-md-12">
					<!--Search Student-->
					<div class="search_area">
						<div id="the_search_area" style="display: none">
							<p>Search school by name</p>
							<table id="search_school_table" class="table table-bordered cell-text-middle" style="text-align: left">
								<input type="hidden" id="csrf_hash_search" value="<?php echo $this->security->get_csrf_hash(); ?>" />
								
								<thead>
									<tr>
										<th> School ID </th>
										<th> Name </th>
										<th> Actions </th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							<div>
								<a class="btn btn-primary" href="<?php echo base_url('school_account/schools'); ?>" target="_blank">View All Schools</a>
							</div>
						</div>
					</div>	
				</div>
			</div>
			
			<div class="x_panel">
				<div class="x_title">
					<h2 class="page_title"><?php echo $inner_page_title; ?></h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<!-- page content -->

					
		