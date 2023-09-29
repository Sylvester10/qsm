
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>
	
	
	<div class="panel with-nav-tabs panel-default">
		<div class="panel-heading">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#profile" data-toggle="tab">My Profile</a></li>
				<li><a href="#fee_info" data-toggle="tab">My School Fees</a></li>
				<li><a href="#quick_email" data-toggle="tab">Quick Mail</a></li>
			</ul>
		</div>
		<div class="panel-body">
			<div class="tab-content">
			
				<div class="tab-pane active" id="profile">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<h1 class="m-b-20 text-bold"><?php echo $student_name; ?></h1>
							<div class="m-b-30">
								<p>School Name: <?php echo school_name; ?></p>
								<p>Registration ID: <?php echo $y->reg_id; ?></p>
								<p>Admission ID: <?php echo $y->admission_id; ?></p>
								<p>Class: <?php echo $class; ?></p>
								<p>Class Teacher: <?php echo $class_teacher; ?></p>
								<div class="m-t-20"></div>
								<p>Current Session: <?php echo current_session; ?></p>
								<p>Current Term: <?php echo current_term; ?></p>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<img class="img-responsive m-t-30 float-center" src="<?php echo base_url('assets/images/dash1.jpg'); ?>" alt="School Manager" />
						</div>
					</div>
				</div>


				<div class="tab-pane fade" id="fee_info">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<h3 class="m-b-10 text-bold">Fee Information</h3>	
							<div class="m-b-30">
								<p>Current Session: <?php echo current_session; ?></p>
								<p>Current Term: <?php echo current_term; ?></p>
								<p>Total Fees: <?php echo $total_fees; ?></p>
								<p>Due Date: <?php echo $current_term_fees_due_date; ?></p>
								<p>Payment Status: <?php echo $payment_status; ?></p>
								<p>Amount Paid: <?php echo $amount_paid; ?></p>
								<p>Balance: <?php echo $balance; ?></p>
								<p>Transaction Reference: <?php echo $transaction_id; ?></p>
								<p>Date Paid: <?php echo $date_paid; ?></p>
								
								<p><a class="btn btn-primary" href="<?php echo base_url('student/school_fees'); ?>">More Details &raquo;</a></p>
								
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<img class="img-responsive m-t-30 float-center" src="<?php echo base_url('assets/images/dash2.jpg'); ?>" alt="School Manager" />
						</div>
					</div>
				</div>

				
				<div class="tab-pane fade" id="quick_email">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					
							<?php 
							$q_form_attributes = array("id" => "quick_mail_form");
							echo form_open('student/send_quick_mail_ajax', $q_form_attributes); ?>

								<h3 class="m-b-10 text-bold">Quick Mail</h3>	
								<p>Send a quick mail to your class teacher: <?php echo $class_teacher; ?></p>
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