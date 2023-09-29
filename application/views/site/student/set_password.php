
	
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
					
						<div class="m-l-15 m-r-20 m-b-20">
							<div class="p-l-10 p-r-10">
								<div class="p-l-10 p-r-10">
									<?php echo flash_message_success('status_msg'); ?>
									<?php echo flash_message_danger('status_msg_error'); ?>
								</div>
							</div>
						</div>
						<h3 class="text-center"><i class="fa fa-lock"></i> Set Student Password</h3>
						<div class="form d-flex align-items-center">
							<div class="content">
								
								<?php
								//process form asynchronously using AJAX
								$form_attributes = array("id" => "set_pass_student_form");
								echo form_open('student_acc/set_password_ajax', $form_attributes); ?>

									<div class="form-group">
										<label>Registration ID</label>
										<input type="text" name="reg_id" class="form-control p-l-10" value="QSM" required>
									</div>

									<div class="form-group">
										<label>Password</label>
										<input type="password" name="password" class="form-control p-l-10" value="" required>
									</div>

									<div class="form-group">
										<label>Confirm Password</label>
										<input type="password" name="c_password" class="form-control p-l-10" placeholder="Re-enter your password here" required>
									</div>
									
									<div id="status_msg" class="d-login-msg m-t-20"></div>
									
									<button class="btn btn-primary btn-block">Set Password</button>

								<?php echo form_close(); ?>
								
								
								<div class="m-t-20 f-s-14">
									<i class="fa fa-hand-o-right"></i> Contact your school administrator if you do not know or have forgotten your Registration ID.
								</div>
								

								<div>
									<ul class="home_links m-t-20">
										<li><a href="<?php echo base_url(); ?>">Home</a></li>
										<li><a href="<?php echo base_url('user_login'); ?>">Login</a></li>
									</ul>
								</div>


							</div><!--/.content-->
						</div><!--/.form d-flex-->

					</div><!--/.col-lg-6 switch-user-->
					
				</div><!--/.row-->
			</div><!--/.form-holder-->
		</div><!--/.container-->
		<!--/Page content-->
		
		
      
