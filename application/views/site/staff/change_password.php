
	
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
									<?php echo flash_message_success('pass_reset_msg'); ?>
									<?php echo flash_message_danger('pass_reset_msg_error'); ?>
								</div>
							</div>
						</div>
						<h3 class="text-center"><i class="fa fa-unlock"></i> Staff Password Reset</h3>
						<div class="form d-flex align-items-center">
							<div class="content">
							
								<?php if ( ! $valid_code ) { //invalid or expired code ?>
									<h4 class="text-danger">The password reset key is invalid or expired. If you submitted the password recovery request more than once, ensure you clicked on the reset link of the most recent mail sent to you.</h4> 
									
								<?php } else { //code is valid and not expired, show form ?>
							
									<?php
									//process form asynchronously using AJAX
									$form_attributes = array("id" => "change_pass_staff_form");
									echo form_open('staff_acc/change_password_ajax/'.$y->id, $form_attributes); ?>
									
										<input type="hidden" id="staff_id" value="<?php echo $y->id; ?>" />
										<div class="form-group">
											<label>New Password</label>
											<input type="password" name="password" class="form-control p-l-10" value="" required>
										</div>
										<div class="form-group">
											<label>Confirm Password</label>
											<input type="password" name="c_password" class="form-control p-l-10" placeholder="Re-enter password" value="" required>
										</div>
										
										<div id="status_msg" class="d-login-msg m-t-20"></div>
										
										<button class="btn btn-primary btn-block">Reset Password</button>
										
									<?php echo form_close(); ?>
									
								<?php } ?>
								
							</div><!--/.content-->
						</div><!--/.form d-flex-->

					</div><!--/.col-lg-6 switch-user-->
					
				</div><!--/.row-->
			</div><!--/.form-holder-->
		</div><!--/.container-->
		<!--/Page content-->
		
		
      
