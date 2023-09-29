
	
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
									<h3 class="text-center" style="margin-bottom: -20px"><i class="fa fa-user"></i> Admin Login</h3>
									<div class="form d-flex align-items-center">
										<div class="content">

											<?php
											//process form asynchronously using AJAX
											$admin_form_attributes = array("id" => "admin_login_form");
											echo form_open('admin_acc/login_ajax', $admin_form_attributes); ?>

												<?php
												//check if user requested a page before being forced to log in
												if ($this->session->login_redirect_admin) { ?>
													<input type="hidden" id="requested_page_admin" value="<?php echo $this->session->requested_page_admin; ?>" />
												<?php } else { ?>
													<input type="hidden" id="requested_page_admin" value="<?php echo base_url('admin'); ?>" />
												<?php } ?>

												
												<div class="form-group">
													<label>Email</label>
													<input type="email" name="email" class="form-control p-l-10" required>	
												</div>
												<div class="form-group">
													<label>Password</label>
													<input type="password" name="password" class="form-control p-l-10" value="" required>
												</div>
												
												<div id="admin_status_msg" class="d-login-msg m-t-20"></div>
												
												<button class="btn btn-primary btn-block">Login</button>

											<?php echo form_close(); ?>

											<div class="m-t-20">
												<a href="<?php echo base_url('recover_password'); ?>" class="forgot-pass f-s-19">Forgot Password?</a>
											</div>
										</div><!--/.content-->
									</div><!--/.form d-flex-->
								</div><!--/.tab-pane-->	



								
								<div id="staff_login" class="tab-pane">
									<h3 class="text-center"><i class="fa fa-user"></i> Staff Login</h3>
									<div class="form d-flex align-items-center">
										<div class="content">

											<?php
											//process form asynchronously using AJAX
											$staff_form_attributes = array("id" => "staff_login_form");
											echo form_open('staff_acc/login_ajax', $staff_form_attributes); ?>

												<?php
												//check if user requested a page before being forced to log in
												if ($this->session->login_redirect_staff) { ?>
													<input type="hidden" id="requested_page_staff" value="<?php echo $this->session->requested_page_staff; ?>" />
												<?php } else { ?>
													<input type="hidden" id="requested_page_staff" value="<?php echo base_url('staff'); ?>" />
												<?php } ?>

											
												<div class="form-group">
													<label>Email</label>
													<input type="email" name="email" class="form-control p-l-10" required>	
												</div>
												<div class="form-group">
													<label>Password</label>
													<input type="password" name="password" class="form-control p-l-10" value="" required>
												</div>
												
												<div id="staff_status_msg" class="d-login-msg m-t-20"></div>
												
												<button class="btn btn-primary btn-block">Login</button>

											<?php echo form_close(); ?>

											<div class="m-t-20">
												<a href="<?php echo base_url('recover_password'); ?>" class="forgot-pass f-s-19">Forgot Password?</a>
											</div>
										</div><!--/.content-->
									</div><!--/.form d-flex-->
								</div><!--/.tab-pane-->
								
								
								<ul class="home_links">
									<li><a href="<?php echo base_url(); ?>">Home</a></li>
									<li><a href="<?php echo base_url('install'); ?>">Register</a></li>
								</ul>
								
								
							</div><!--/.tab-content-->
						</div><!--/.m-t-20-->
					</div><!--/.col-lg-6 switch-user-->
					
				</div><!--/.row-->
			</div><!--/.form-holder-->
		</div><!--/.container-->
		<!--/Page content-->
		
		
      
