
	
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
										<?php echo flash_message_success('status_msg'); ?>
										<?php echo flash_message_danger('status_msg_error'); ?>
									</div>
								</div>
							</div>
						
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#admin_pass_recovery">Admin</a></li>
								<li><a data-toggle="tab" href="#staff_pass_recovery">Staff</a></li>
							</ul>
							<div class="tab-content m-t-20 p-30">
								
								<div id="admin_pass_recovery" class="tab-pane active">
									<h3 class="text-center" style="margin-bottom: -20px"><i class="fa fa-unlock"></i> Admin Password Recovery</h3>
									<div class="form d-flex align-items-center">
										<div class="content">
											
											<?php
											//process form asynchronously using AJAX
											$admin_form_attributes = array("id" => "recover_pass_admin_form");
											echo form_open('admin_acc/recover_password_ajax', $admin_form_attributes); ?>

												<div class="form-group">
													<label>Email</label>
													<input type="email" name="email" id="admin_email" class="form-control p-l-10" required>	
												</div>
												
												<div id="admin_status_msg" class="d-login-msg m-t-20"></div>
												
												<button class="btn btn-primary btn-block">Recover Password</button>

											<?php echo form_close(); ?>

										</div><!--/.content-->
									</div><!--/.form d-flex-->
								</div><!--/.tab-pane-->	



								
								
								<div id="staff_pass_recovery" class="tab-pane">
									<h3 class="text-center"><i class="fa fa-unlock"></i> Staff Password Recovery</h3>
									<div class="form d-flex align-items-center">
										<div class="content">
											<?php
											//process form asynchronously using AJAX
											$staff_form_attributes = array("id" => "recover_pass_staff_form");
											echo form_open('staff_acc/recover_password_ajax', $staff_form_attributes); ?>

												<div class="form-group">
													<label>Email</label>
													<input type="email" name="email"  id="staff_email" class="form-control p-l-10" required>	
												</div>
												
												<div id="staff_status_msg" class="d-login-msg m-t-20"></div>
												
												<button class="btn btn-primary btn-block">Recover Password</button>

											<?php echo form_close(); ?>
											
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
		
		
      
