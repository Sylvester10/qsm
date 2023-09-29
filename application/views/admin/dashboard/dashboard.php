
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


	<div class="row m-b-50">
	
		<div class="animated flipInY col-lg-3 col-md-6 col-sm-6 col-xs-12">
			<div class="tile-stats custom-bg-blue">
				<div class="icon"><i class="fa fa-users"></i></div>
				<div class="count"><?php echo $total_students; ?></div>
				<h3 class="stats-title">Students</h3>
			</div>
		</div>
		<div class="animated flipInY col-lg-3 col-md-6 col-sm-6 col-xs-12">
			<div class="tile-stats custom-bg-blue">
				<div class="icon"><i class="fa fa-users"></i></div>
				<div class="count"><?php echo $total_staff; ?></div>
				<h3 class="stats-title">Staff</h3>
			</div>
		</div>
		<div class="animated flipInY col-lg-3 col-md-6 col-sm-6 col-xs-12">
			<div class="tile-stats custom-bg-blue">
				<div class="icon"><i class="fa fa-users"></i></div>
				<div class="count"><?php echo $total_classes; ?></div>
				<h3 class="stats-title">Classes</h3>
			</div>
		</div>
		<div class="animated flipInY col-lg-3 col-md-6 col-sm-6 col-xs-12">
			<div class="tile-stats custom-bg-blue">
				<div class="icon"><i class="fa fa-users"></i></div>
				<div class="count"><?php echo $total_sections; ?></div>
				<h3 class="stats-title">Sections</h3>
			</div>
		</div>
		
	</div>


	<div class="panel with-nav-tabs panel-default">
		<div class="panel-heading">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#software_details" data-toggle="tab"><?php echo software_initials; ?></a></li>
				<li><a href="#plans_features" data-toggle="tab">Plans & Features</a></li>
				<?php 
				$requisitions_module = $this->common_model->module_restricted(school_id, mod_requisitions);
				if ($requisitions_module) { ?>
					<li><a href="#recent_request" data-toggle="tab">Recent Request</a></li>
				<?php } ?>
				<li><a href="#recent_notification" data-toggle="tab">Recent Notifications</a></li>
				<li><a href="#quick_email" data-toggle="tab">Quick Mail</a></li>
				<li><a href="#bulk_email" data-toggle="tab">Bulk Mail</a></li>
			</ul>
		</div>
		<div class="panel-body">
			<div class="tab-content">
			
				<div class="tab-pane active" id="software_details">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<h1 class="m-b-30 text-bold"><?php echo software_name; ?></h1>
							<p class="m-b-30">
								<?php echo software_description; ?>
							</p>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<img class="img-responsive m-t-30 float-center" src="<?php echo base_url('assets/images/qsm.jpg'); ?>" alt="School Manager" />
						</div>
					</div>
				</div>



				<div class="tab-pane" id="plans_features">
					<?php require "application/views/admin/plan/plans.php"; ?>
				</div>


				
				<?php 
				if ($requisitions_module) { ?>
				
					<div class="tab-pane fade in" id="recent_request">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<h3 class="m-b-30 text-bold">Recent Request</h3>

								<?php
								foreach ($recent_request as $y) { ?>

									<p><b>Ref ID</b>: <?php echo $y->ref_id; ?></p>
									<p><b>Raised by</b>: <?php echo $y->raised_by; ?></p>
									<p><b>Purpose</b>: <?php echo $y->purpose; ?></p>
									<p><b>Items</b>: <?php echo $y->items; ?></p>
									<p><b>More info</b>: <?php echo $y->items_info; ?></p>
									<p><b>Urgency</b>: <?php echo $y->urgency; ?></p>
									<p><b>Amount</b>: <?php echo s_currency_symbol . number_format($y->amount_digits, 0); ?></p>
									<p><b>Date raised</b>: <?php echo x_date($y->date_raised); ?></p>
									<p><b>Status</b>: <?php echo ucfirst($y->status); ?></p>
									<?php if ($y->status == 'approved') { ?>
										<p><b>Approved by</b>: <?php echo $y->approved_by; ?></p>
										<p><b>Amount approved</b>: <?php echo s_currency_symbol . number_format($y->amount_approved, 0); ?></p>
										<p><b>Date approved</b>: <?php echo ($y->date_approved != NULL) ? x_date($y->date_approved) : ''; ?></p>
									<?php } ?>
									<p><b>Account name</b>: <?php echo $y->acc_name; ?></p>
									<p><b>Account number</b>: <?php echo $y->acc_number; ?></p>
									<p><b>Bank name</b>: <?php echo $y->bank_name; ?></p>
									<p><b>Remark</b> <br/> <?php echo $y->remark; ?></p>

								<?php } ?>

								<hr />
								<a class="btn btn-lg btn-primary" href="<?php echo base_url('prs_admin'); ?>">View all requests</a>
							</div>

							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<img class="img-responsive m-t-30 float-center" src="<?php echo base_url('assets/images/dash2.jpg'); ?>" alt="School Manager" />
							</div>

						</div>
					</div>
					
				<?php } //endif mod_prs == 'true' ?>
				


				
				<div class="tab-pane fade" id="recent_notification">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<h3 class="m-b-30 text-bold">Recent Notifications</h3>

							<?php
							foreach ($recent_notifs as $n) { ?>

								<h4><b><?php echo $n->subject; ?></b> <small>(<?php echo time_ago($n->date); ?>)</small></h4>
								<p><?php echo $n->message; ?></p>
								<hr />

							<?php } ?>

							<a class="btn btn-lg btn-primary" href="<?php echo base_url('admin/notifications'); ?>">View all notifications</a>
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
							echo form_open('admin/send_quick_mail_ajax', $q_form_attributes); ?>

								<h3 class="m-b-30 text-bold">Quick Mail</h3>	
								<div class="form-group">
									<label class="form-control-label">Email</label>
									<input type="email" name="email" class="form-control" required />
								</div>
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



				
				<div class="tab-pane fade" id="bulk_email">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

							<?php 
							$b_form_attributes = array("id" => "bulk_mail_form");
							echo form_open('admin/send_bulk_mail_ajax', $b_form_attributes); ?>

								<h3 class="m-b-30 text-bold">Bulk Mail</h3>	
								<div class="form-group">
									<label class="form-control-label">Mailing List</label>
									<select class="form-control" name="mailing_list" required>
										<option value="">Select Mailing List</option>	
										<option value="parents">Parents (<?php echo $total_parents; ?>)</option>	
										<option value="staff">Staff (<?php echo $total_staff; ?>)</option>	
										<option value="admins">Admins (<?php echo $total_admins; ?>)</option>	
									</select>
								</div>
								<div class="form-group">
									<label class="form-control-label">Subject</label>
									<input type="text" name="subject" class="form-control" required />
								</div>
								<div class="form-group">
									<label class="form-control-label">Message</label>
									<textarea name="message" class="form-control t150" required></textarea>
								</div>
								
								<div id="b_status_msg"></div>
								
								<div class="form-group">       
									<input type="submit" value="Send Mail" class="btn btn-lg btn-primary">
								</div>

							<?php echo form_close(); ?>

						</div>

						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<img class="img-responsive m-t-30 float-center" src="<?php echo base_url('assets/images/dash5.jpg'); ?>" alt="School Manager" />
						</div>

					</div>
				</div>


				
			</div>
		</div>
	</div>