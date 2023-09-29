	
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
						<div class="form d-flex align-items-center">
							<div class="content">
							
								<?php if ( $valid_code ) { ?>

									<center>

										<h3 class="text-bold"><?php echo $school_name; ?></h3>
										<i class="fa fa-check-circle-o f-s-50 text-success"></i>
										<h4 class="text-success">Confirmation code validated!</h4>
										Click on the button below to complete your account confirmation.

										<?php
										//process form asynchronously using AJAX
										$form_attributes = array("id" => "confirm_school_account_form");
										echo form_open('installation/confirm_school_account_ajax/'.$school_id.'/'.$confirm_code, $form_attributes); ?>

											<input type="hidden" id="school_id" value="<?php echo $school_id; ?>" />
											<input type="hidden" id="confirm_code" value="<?php echo $confirm_code; ?>" />

											<div id="confirm_status_msg"></div>

											<button class="btn btn-primary btn-sm"> Complete Confirmation </button>

										<?php echo form_close(); ?>

									</center>
									
								<?php } else { ?>

									<center>

										<h3 class="text-bold"><?php echo $school_name; ?></h3>
										<i class="fa fa-times-circle-o f-s-50 text-danger"></i>
										<h4 style="color: red">Email confirmation failed! </h4>		
										The confirmation code is invalid or has expired.
										
										<?php
										//process form asynchronously using AJAX
										$form_attributes = array("id" => "resend_confirmation_form");
										echo form_open('installation/resend_confirmation_ajax/'.$school_id, $form_attributes); ?>

											<input type="hidden" id="school_id" value="<?php echo $school_id; ?>" />

											<div id="resend_status_msg"></div>

											<button class="btn btn-primary btn-sm" id="resend_btn"> Resend Confirmation </button>

										<?php echo form_close(); ?>


									</center>
										
								<?php } //endif ?>
								
							</div><!--/.content-->
						</div><!--/.form d-flex-->

					</div><!--/.col-lg-6 switch-user-->
					
				</div><!--/.row-->
			</div><!--/.form-holder-->
		</div><!--/.container-->
		<!--/Page content-->
		
		
      
