
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

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
				<li class="active"><a href="#initiate_request" data-toggle="tab"><?php echo software_initials; ?></a></li>
				<li><a href="#recent_notification" data-toggle="tab">Recent Notifications</a></li>
				<li><a href="#quick_email" data-toggle="tab">Quick Mail</a></li>
			</ul>
		</div>
		<div class="panel-body">
			<div class="tab-content">


			
				<div class="tab-pane active" id="initiate_request">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<h1 class="text-bold m-b-30"><?php echo software_name; ?></h1>
							<p class="m-b-30">
								<?php echo software_description; ?>
							</p>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<img class="img-responsive m-t-30 float-center" src="<?php echo base_url('assets/images/qsm.jpg'); ?>" alt="request image" />
						</div>
					</div>
				</div>


		
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
							<a class="btn btn-lg btn-primary" href="<?php echo base_url('staff/notifications'); ?>">View all notifications</a>
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
							echo form_open('staff/send_quick_mail_ajax', $q_form_attributes); ?>
								<h3 class="m-b-30 text-bold">Quick Mail</h3>	
								<div class="form-group">
									<label class="form-control-label">Email</label>
									<input type="email" name="email" class="form-control" required />
								</div>
								<div class="form-group">
									<label class="form-control-label">Subject</label>
									<input type="text" name="subject" class="form-control"  required />
								</div>
								<div class="form-group">
									<label class="form-control-label">Message</label>
									<textarea name="message" class="form-control t100" required></textarea>
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