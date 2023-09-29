
	
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
								<li class="active"><a data-toggle="tab" href="#student_login">Student</a></li>
								<li><a data-toggle="tab" href="#parent_login">Parent</a></li>
							</ul>
							<div class="tab-content m-t-20 p-30">
								
								<div id="student_login" class="tab-pane  active">
									<h3 class="text-center" style="margin-bottom: -20px"><i class="fa fa-user"></i> Demo | Student Login</h3>
									<div class="form d-flex align-items-center">
										<div class="content">

											<?php
											//process form asynchronously using AJAX
											$student_role_attributes = array("id" => "demo_student_role_form");
											echo form_open('demo/student_role_ajax', $student_role_attributes); ?>

												<b>How to Use</b> 
												<p>Select Student Role and click OK to get login details.The Reg ID and password for the selected student account will be populated into the corresponding fields. Click Login to login.</p>

												<div class="form-group">
													<label>Select a Student</label>
													<div class="input-group">
														<select class="form-control" name="student_role" id="student_role" required>
														
															<?php 
															$count = 1;
															$admin_roles = demo_roles_student();
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
											$student_form_attributes = array("id" => "demo_student_login_form");
											echo form_open('demo/student_login_ajax', $student_form_attributes); ?>

												<?php
												//check if user requested a page before being forced to log in
												if ($this->session->login_redirect_student) { ?>
													<input type="hidden" id="requested_page_student" value="<?php echo $this->session->requested_page_student; ?>" />
												<?php } else { ?>
													<input type="hidden" id="requested_page_student" value="<?php echo base_url('student'); ?>" />
												<?php } ?>


												<div class="form-group">
													<label>Name
														<span class="d_loader"  style="display: none"><i class="fa fa-spinner fa-spin text-success"></i></span>
													</label>
													<input type="text" id="student_name" class="form-control p-l-10" readonly />
												</div>

												<div class="form-group">
													<label>Registration ID
														<span class="d_loader" style="display: none"><i class="fa fa-spinner fa-spin text-success"></i></span>
													</label>
													<input type="text" name="reg_id" id="student_reg_id" class="form-control p-l-10" required readonly />
												</div>
												
												<div id="student_login_msg" class="d-login-msg m-t-20"></div>
												
												<button class="btn btn-primary btn-block">Login</button>

											<?php echo form_close(); ?>

										</div><!--/.content-->
									</div><!--/.form d-flex-->
								</div><!--/.tab-pane-->	





								
								<div id="parent_login" class="tab-pane">
									<h3 class="text-center"><i class="fa fa-user"></i> Demo | Parent Login</h3>
									<div class="form d-flex align-items-center">
										<div class="content">

											<?php
											//process form asynchronously using AJAX
											$parent_role_attributes = array("id" => "demo_parent_role_form");
											echo form_open('demo/parent_role_ajax', $parent_role_attributes); ?>

												<b>How to Use</b> 
												<p>Select a parent with specific number of children and click OK to get login details.The email and password for the selected parent account will be populated into the corresponding fields. Click Login to login.</p>

												<div class="form-group">
													<label>Number of Children</label>
													<div class="input-group">
														<select class="form-control" name="parent_role" id="parent_role" required>
														
															<?php 
															$count = 1;
															$admin_roles = demo_roles_parent();
															foreach ($admin_roles as $key => $value) {
																
																$selected = ($count == 2) ? 'selected' : NULL; ?>
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
											$parent_form_attributes = array("id" => "demo_parent_login_form");
											echo form_open('demo/parent_login_ajax', $parent_form_attributes); ?>

												<?php
												//check if user requested a page before being forced to log in
												if ($this->session->login_redirect_parent) { ?>
													<input type="hidden" id="requested_page_parent" value="<?php echo $this->session->requested_page_parent; ?>" />
												<?php } else { ?>
													<input type="hidden" id="requested_page_parent" value="<?php echo base_url('school_parent'); ?>" />
												<?php } ?>


												<div class="form-group">
													<label>Name
														<span class="d_loader"  style="display: none"><i class="fa fa-spinner fa-spin text-success"></i></span>
													</label>
													<input type="text" id="parent_name" class="form-control p-l-10" readonly />
												</div>
											
												<div class="form-group">
													<label>Email
														<span class="d_loader" style="display: none"><i class="fa fa-spinner fa-spin text-success"></i></span>
													</label>
													<input type="email" name="email" id="parent_email" class="form-control p-l-10" required readonly />	
												</div>
												
												<div id="parent_login_msg" class="d-login-msg m-t-20"></div>
												
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
		
		
      
