
	
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
								<div class="p-l-10 p-r-10 m-t-20">
									<?php echo flash_message_success('login_msg'); ?>
									<?php echo flash_message_danger('login_msg_error'); ?>
								</div>
							</div>
						</div>
						<h3 class="text-center"><i class="fa fa-unlock"></i> Super Admin Login</h3>
						<div class="form d-flex align-items-center">
							<div class="content">
							
								<?php
								//process form asynchronously using AJAX
								$form_attributes = array("id" => "super_admin_login_form");
								echo form_open('super_admin_acc/login_ajax', $form_attributes); ?>

									<?php
									//check if user requested a page before being forced to log in
									if ($this->session->login_redirect_super_admin) { ?>
										<input type="hidden" id="requested_page_super_admin" value="<?php echo $this->session->requested_page_super_admin; ?>" />
									<?php } else { ?>
										<input type="hidden" id="requested_page_super_admin" value="<?php echo base_url('super_admin'); ?>" />
									<?php } ?>

									<div class="form-group">
										<label>Email</label>
										<input type="email" name="email" class="form-control p-l-10" required>	
									</div>
									<div class="form-group">
										<label>Password</label>
										<input type="password" name="password" class="form-control p-l-10" value="" required>
									</div>
									
									<div id="status_msg" class="d-login-msg m-t-20"></div>
									
									<button class="btn btn-primary btn-block">Login</button>

								<?php echo form_close(); ?>


								<div class="m-t-20">
									<a href="<?php echo base_url('super_admin_acc/recover_password'); ?>" class="forgot-pass f-s-19">Forgot Password?</a>
								</div>

								<div>
									<ul class="home_links_super_admin">
										<li><a href="<?php echo base_url(); ?>">Home</a></li>
										<li><a href="<?php echo base_url('admin'); ?>">Client Panel</a></li>
									</ul>
								</div>
								
							</div><!--/.content-->
						</div><!--/.form d-flex-->

					</div><!--/.col-lg-6 switch-user-->
					
				</div><!--/.row-->
			</div><!--/.form-holder-->
		</div><!--/.container-->
		<!--/Page content-->
		
		
      
