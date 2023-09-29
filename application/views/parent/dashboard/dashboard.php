
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>
	
	
	<div class="panel with-nav-tabs panel-default">
		<div class="panel-heading">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#children" data-toggle="tab">My Children</a></li>
				<li><a href="#quick_email" data-toggle="tab">Quick Mail</a></li>
			</ul>
		</div>
		<div class="panel-body">
			<div class="tab-content">
			
				<div class="tab-pane active" id="children">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<h3 class="text-bold">My Children (<?php echo count($children); ?>)</h3>
		
							<?php 
							foreach ($children as $c) { 

								$child_name = $this->common_model->get_student_fullname($c->id); 
								$child_passport = $this->common_model->student_passport_alt($c->id); ?>

								<p class="m-t-20"><?php echo $child_passport; ?> <a href="<?php echo base_url('school_parent/child_profile/'.$c->id); ?>" title="View <?php echo $c->first_name; ?>'s profile"><?php echo $child_name; ?></a></p>

							<?php } ?>
							
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<img class="img-responsive m-t-30 float-center" src="<?php echo base_url('assets/images/dash1.jpg'); ?>" alt="School Manager" />
						</div>
					</div>
				</div>

				
				<div class="tab-pane fade" id="quick_email">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					
							<?php 
							$q_form_attributes = array("id" => "quick_mail_form");
							echo form_open('school_parent/send_quick_mail_ajax', $q_form_attributes); ?>

								<h3 class="m-b-10 text-bold">Quick Mail</h3>	
								<p>Send a quick mail to school administration.</p>
								<div class="form-group"> 
									<label class="form-control-label">Subject</label>
									<input type="text" name="subject" class="form-control" required />
								</div>
								<div class="form-group">
									<label class="form-control-label">Message</label>
									<textarea name="message" class="form-control t150" required></textarea>
								</div>
								
								<div id="q_status_msg"></div>
								
								<div class="form-group">       
									<input type="submit" value="Send Mail" class="btn btn-lg btn-primary">
								</div>

							<?php echo form_close(); ?>

						</div>

						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<img class="img-responsive m-t-30 float-center" src="<?php echo base_url('assets/images/dash3.jpg'); ?>" alt="School Manager" />
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>