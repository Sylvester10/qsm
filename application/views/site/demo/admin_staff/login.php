
	
		<!--Page content-->
		<div class="container d-flex align-items-center">
			<div class="form-holder has-shadow">
				<div class="row">
					<!-- Logo & Information Panel-->
					<div class="col-lg-6 login-background">
						<div class="info d-flex align-items-center">
							<div class="content">
								<div class="logo">
									<h1><?php echo software_initials; ?></h1>
								</div>
								<p><?php echo software_tagline; ?></p>
							</div>
						</div>
					</div>
					<!-- Form Panel    -->
					<div class="col-lg-6 bg-white switch-user">
					
						<div class="m-t-20">
						
							<div class="m-l-15 m-r-20 m-b-20">
								<div class="p-l-10 p-r-10">
									<div class="p-l-10 p-r-10">
										<?php echo flash_message_success('login_msg'); ?>
										<?php echo flash_message_danger('login_msg_error'); ?>
									</div>
								</div>
							</div>
						
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#admin_login">Admin</a></li>
								<li><a data-toggle="tab" href="#staff_login">Staff</a></li>
							</ul>
							<div class="tab-content m-t-20 p-30">
								
								<div id="admin_login" class="tab-pane  active">
									<h3 class="text-center" style="margin-bottom: -20px"><i class="fa fa-user"></i> Demo | Admin Login</h3>
									<div class="form d-flex align-items-center">
										<div class="content">

											<?php
											//process form asynchronously using AJAX
											$admin_role_attributes = array("id" => "demo_admin_role_form");
											echo form_open('demo/admin_role_ajax', $admin_role_attributes); ?>
												
												<b>How to Use</b> 
												<p>Select Admin Role/Level and click OK to get details.The login email and password for the selected admin account will be populated into the corresponding fields. Click Login to login.</p>

												<div class="form-group">
													<label>Admin Role/Level </label>
													<div class="input-group">
														<select class="form-control" name="admin_role" id="admin_role" required>
															
															<?php 
															$count = 1;
															$admin_roles = demo_roles_admin();
															foreach ($admin_roles as $key => $value) {
																
																$selected = ($count == 1) ? 'selected' : NULL; ?>
																<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
																
																<?php $count++;
															} ?>
															
														</select>
														<div class="input-group-append">
														    <button class="btn btn-primary">Ok</button>
														</div>
													</div>
												</div>

											<?php echo form_close(); ?>


											<?php
											//process form asynchronously using AJAX
											$admin_form_attributes = array("id" => "demo_admin_login_form");
											echo form_open('demo/admin_login_ajax', $admin_form_attributes); ?>

												<?php
												//check if user requested a page before being forced to log in
												if ($this->session->login_redirect_admin) { ?>
													<input type="hidden" id="requested_page_admin" value="<?php echo $this->session->requested_page_admin; ?>" />
												<?php } else { ?>
													<input type="hidden" id="requested_page_admin" value="<?php echo base_url('admin'); ?>" />
												<?php } ?>

												<div class="form-group">
													<label>Name
														<span class="d_loader"  style="display: none"><i class="fa fa-spinner fa-spin text-success"></i></span>
													</label>
													<input type="text" id="admin_name" class="form-control p-l-10" readonly />
												</div>


												<div class="form-group">
													<label>Email
														<span class="d_loader" style="display: none"><i class="fa fa-spinner fa-spin text-success"></i></span>
													</label>
													<input type="email" name="email" id="admin_email" class="form-control p-l-10" required readonly />	
												</div>

												<div id="admin_login_msg" class="d-login-msg m-t-20"></div>
												
												<button class="btn btn-primary btn-block">Login</button>

											<?php echo form_close(); ?>
						
										</div><!--/.content-->
									</div><!--/.form d-flex-->
								</div><!--/.tab-pane-->	
								






								<div id="staff_login" class="tab-pane">
									<h3 class="text-center"><i class="fa fa-user"></i> Demo | Staff Login</h3>
									<div class="form d-flex align-items-center">
										<div class="content">

											<?php
											//process form asynchronously using AJAX
											$staff_role_attributes = array("id" => "demo_staff_role_form");
											echo form_open('demo/staff_role_ajax', $staff_role_attributes); ?>

												<b>How to Use</b> 
												<p>Select Staff Role and click OK to get login details.The email and password for the selected staff account will be populated into the corresponding fields. Click Login to login.</p>

												<div class="form-group">
													<label>Staff Role </label>
													<div class="input-group">
														<select class="form-control" name="staff_role" id="staff_role" required>
														
															<?php 
															$count = 1;
															$admin_roles = demo_roles_staff();
															foreach ($admin_roles as $key => $value) {
																
																$selected = ($count == 1) ? 'selected' : NULL; ?>
																<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
																
																<?php $count++;
															} ?>
															
														</select>
														<div class="input-group-append">
														    <button class="btn btn-primary">Ok</button>
														</div>
													</div>
												</div>

											<?php echo form_close(); ?>

											
											<?php
											//process form asynchronously using AJAX
											$staff_form_attributes = array("id" => "demo_staff_login_form");
											echo form_open('demo/staff_login_ajax', $staff_form_attributes); ?>

												<?php
												//check if user requested a page before being forced to log in
												if ($this->session->login_redirect_staff) { ?>
													<input type="hidden" id="requested_page_staff" value="<?php echo $this->session->requested_page_staff; ?>" />
												<?php } else { ?>
													<input type="hidden" id="requested_page_staff" value="<?php echo base_url('staff'); ?>" />
												<?php } ?>


												<div class="form-group">
													<label>Name
														<span class="d_loader"  style="display: none"><i class="fa fa-spinner fa-spin text-success"></i></span>
													</label>
													<input type="text" id="staff_name" class="form-control p-l-10" readonly />
												</div>

												<div class="form-group">
													<label>Email
														<span class="d_loader" style="display: none"><i class="fa fa-spinner fa-spin text-success"></i></span>
													</label>
													<input type="email" name="email" id="staff_email" class="form-control p-l-10" required readonly />	
												</div>
												
												<div id="staff_login_msg" class="d-login-msg m-t-20"></div>
												
												<button class="btn btn-primary btn-block">Login</button>

											<?php echo form_close(); ?>

										
										</div><!--/.content-->
									</div><!--/.form d-flex-->
								</div><!--/.tab-pane-->



								<div>
									<ul class="home_links">
										<li><a href="<?php echo base_url(); ?>">Home</a></li>
									</ul>
								</div>
							
								
								
							</div><!--/.tab-content-->
						</div><!--/.m-t-20-->
					</div><!--/.col-lg-6 switch-user-->
					
				</div><!--/.row-->
			</div><!--/.form-holder-->
		</div><!--/.container-->
		<!--/Page content-->
		
		
      
