
	
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
									<h3 class="text-center" style="margin-bottom: -20px"><i class="fa fa-user"></i> Student Login</h3>
									<div class="form d-flex align-items-center">
										<div class="content">

											<?php
											//process form asynchronously using AJAX
											$student_form_attributes = array("id" => "student_login_form");
											echo form_open('student_acc/login_ajax', $student_form_attributes); ?>

												<?php
												//check if user requested a page before being forced to log in
												if ($this->session->login_redirect_student) { ?>
													<input type="hidden" id="requested_page_student" value="<?php echo $this->session->requested_page_student; ?>" />
												<?php } else { ?>
													<input type="hidden" id="requested_page_student" value="<?php echo base_url('student'); ?>" />
												<?php } ?>


												<div class="form-group">
													<label>Registration ID</label>
													<input type="text" name="reg_id" class="form-control p-l-10" value="QSM" required>
												</div>

												<div class="form-group">
													<label>Password</label>
													<input type="password" name="password" class="form-control p-l-10" value="" required>
												</div>
												
												<div id="student_status_msg" class="d-login-msg m-t-20"></div>
												
												<button class="btn btn-primary btn-block">Login</button>

											<?php echo form_close(); ?>

											<div class="m-t-20">
												<a href="<?php echo base_url('user_recover_password'); ?>" class="forgot-pass f-s-19">Forgot Password?</a>
											</div>

											<div class="m-t-20 f-s-14">
												<i class="fa fa-hand-o-right"></i> Contact your school administrator if you do not know or have forgotten your Registration ID.
												<br />
												<i class="fa fa-hand-o-right"></i> If you are signing in for the first time, click <a href="<?php echo base_url('student_acc/set_password'); ?>">here</a> to set your password.
											</div>

										</div><!--/.content-->
									</div><!--/.form d-flex-->
								</div><!--/.tab-pane-->	


								

								<div id="parent_login" class="tab-pane">
									<h3 class="text-center"><i class="fa fa-user"></i> Parent Login</h3>
									<div class="form d-flex align-items-center">
										<div class="content">

											<?php
											//process form asynchronously using AJAX
											$parent_form_attributes = array("id" => "parent_login_form");
											echo form_open('parent_acc/login_ajax', $parent_form_attributes); ?>

												<?php
												//check if user requested a page before being forced to log in
												if ($this->session->login_redirect_parent) { ?>
													<input type="hidden" id="requested_page_parent" value="<?php echo $this->session->requested_page_parent; ?>" />
												<?php } else { ?>
													<input type="hidden" id="requested_page_parent" value="<?php echo base_url('school_parent'); ?>" />
												<?php } ?>

											
												<div class="form-group">
													<label>Email</label>
													<input type="email" name="email" class="form-control p-l-10" required>	
												</div>
												<div class="form-group">
													<label>Password</label>
													<input type="password" name="password" class="form-control p-l-10" value="" required>
												</div>
												
												<div id="parent_status_msg" class="d-login-msg m-t-20"></div>
												
												<button class="btn btn-primary btn-block">Login</button>

											<?php echo form_close(); ?>

											<div class="m-t-20">
												<a href="<?php echo base_url('user_recover_password'); ?>" class="forgot-pass f-s-19">Forgot Password?</a>
											</div>


											<div class="m-t-20 f-s-14">
												<i class="fa fa-hand-o-right"></i> Contact your school administrator if you do not know which email address was used to create your account.
												<br />
												<i class="fa fa-hand-o-right"></i> If you are signing in for the first time, click <a href="<?php echo base_url('parent_acc/set_password'); ?>">here</a> to set your password.
											</div>

										</div><!--/.content-->
									</div><!--/.form d-flex-->
								</div><!--/.tab-pane-->
								
								
								<ul class="home_links">
									<li><a href="<?php echo base_url(); ?>">Home</a></li>
								</ul>
								
								
							</div><!--/.tab-content-->
						</div><!--/.m-t-20-->
					</div><!--/.col-lg-6 switch-user-->
					
				</div><!--/.row-->
			</div><!--/.form-holder-->
		</div><!--/.container-->
		<!--/Page content-->
		
		
      
