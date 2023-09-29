
	
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
										<?php echo flash_message_success('pass_recovery_msg'); ?>
										<?php echo flash_message_danger('pass_recovery_msg_error'); ?>
									</div>
								</div>
							</div>
						
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#student_pass_recovery">Student</a></li>
								<li><a data-toggle="tab" href="#parent_pass_recovery">Parent</a></li>
							</ul>
							<div class="tab-content m-t-20 p-30">
								
								<div id="student_pass_recovery" class="tab-pane active">
									<h3 class="text-center" style="margin-bottom: -20px"><i class="fa fa-unlock"></i> Student Password Recovery</h3>
									<div class="form d-flex align-items-center">
										<div class="content">

											<?php
											//process form asynchronously using AJAX
											$student_form_attributes = array("id" => "recover_pass_student_form");
											echo form_open('student_acc/recover_password_ajax', $student_form_attributes); ?>

												<div class="form-group">
													<label>Registration ID</label>
													<input type="text" name="reg_id" class="form-control p-l-10" value="QSM" required>
												</div>

												<div class="form-group">
													<label>Password Reset Code</label>
													<input type="text" name="pass_reset_code" class="form-control p-l-10" maxlength="4" required>
												</div>

												<div class="form-group">
													<label>New Password</label>
													<input type="password" name="password" class="form-control p-l-10" value="" required>
												</div>

												<div class="form-group">
													<label>Confirm Password</label>
													<input type="password" name="c_password" class="form-control p-l-10" placeholder="Re-enter your new password here" required>
												</div>
												
												<div id="student_status_msg" class="d-login-msg m-t-20"></div>
												
												<button class="btn btn-primary btn-block">Recover Password</button>

											<?php echo form_close(); ?>

											<div class="m-t-20 f-s-14">
												<i class="fa fa-hand-o-right"></i> Contact your school administrator if you do not know or have forgotten your Registration ID or Password Reset Code.
											</div>

										</div><!--/.content-->
									</div><!--/.form d-flex-->
								</div><!--/.tab-pane-->	


								
								<div id="parent_pass_recovery" class="tab-pane">
									<h3 class="text-center"><i class="fa fa-unlock"></i> Parent Password Recovery</h3>
									<div class="form d-flex align-items-center">
										<div class="content">
											
											<?php
											//process form asynchronously using AJAX
											$parent_form_attributes = array("id" => "recover_pass_parent_form");
											echo form_open('parent_acc/recover_password_ajax', $parent_form_attributes); ?>

												<div class="form-group">
													<label>Email</label>
													<input type="email" name="email"  id="parent_email" class="form-control p-l-10" required>	
												</div>
												
												<div id="parent_status_msg" class="d-login-msg m-t-20"></div>
												
												<button class="btn btn-primary btn-block">Recover Password</button>
											<?php echo form_close(); ?>
										</div><!--/.content-->
									</div><!--/.form d-flex-->
								</div><!--/.tab-pane-->
								
								
								<ul class="home_links">
									<li><a href="<?php echo base_url(); ?>">Home</a></li>
									<li><a href="<?php echo base_url('user_login'); ?>">Login</a></li>
								</ul>
								
										
							</div><!--/.tab-content-->
						</div><!--/.m-t-20-->
					</div><!--/.col-lg-6 switch-user-->
					
				</div><!--/.row-->
			</div><!--/.form-holder-->
		</div><!--/.container-->
		<!--/Page content-->
		
		
      
