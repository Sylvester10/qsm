
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


<?php require "application/views/super_admin/schools/includes/school_stats.php"; ?>

<div class="row m-b-50">

	<div class="animated flipInY col-lg-3 col-md-6 col-sm-6 col-xs-12">
		<div class="tile-stats custom-bg-blue">
			<div class="icon"><i class="fa fa-users"></i></div>
			<div class="count"><?php echo number_format($total_admins); ?></div>
			<h3 class="stats-title">Admins</h3>
		</div>
	</div>

	<div class="animated flipInY col-lg-3 col-md-6 col-sm-6 col-xs-12">
		<div class="tile-stats custom-bg-blue">
			<div class="icon"><i class="fa fa-users"></i></div>
			<div class="count"><?php echo number_format($total_staff); ?></div>
			<h3 class="stats-title">Staff</h3>
		</div>
	</div>

	<div class="animated flipInY col-lg-3 col-md-6 col-sm-6 col-xs-12">
		<div class="tile-stats custom-bg-blue">
			<div class="icon"><i class="fa fa-users"></i></div>
			<div class="count"><?php echo number_format($total_students); ?></div>
			<h3 class="stats-title">Students</h3>
		</div>
	</div>

	<div class="animated flipInY col-lg-3 col-md-6 col-sm-6 col-xs-12">
		<div class="tile-stats custom-bg-blue">
			<div class="icon"><i class="fa fa-users"></i></div>
			<div class="count"><?php echo number_format($total_parents); ?></div>
			<h3 class="stats-title">Parents</h3>
		</div>
	</div>

</div>


<div class="panel with-nav-tabs panel-default">
	<div class="panel-heading">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#software_details" data-toggle="tab"><?php echo software_initials; ?></a></li>
			<li><a href="#activity_stats" data-toggle="tab">User Activity Stats</a></li>
			<li><a href="#plans_features" data-toggle="tab">Plans & Features</a></li>
			<li><a href="#quick_email" data-toggle="tab">Quick Mail</a></li>
			<li><a href="#bulk_email" data-toggle="tab">Bulk Mail</a></li>
		</ul>
	</div>
	<div class="panel-body">
		<div class="tab-content">
		
			<div class="tab-pane active" id="software_details">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<h1 class="text-center m-b-30"><?php echo software_name; ?></h1>
						<p class="m-b-30">
							<?php echo software_description; ?>
						</p>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<img class="img-responsive m-t-30 float-center" src="<?php echo base_url('assets/images/qsm.jpg'); ?>" alt="School Manager" />
					</div>
				</div>
			</div>


			<div class="tab-pane" id="activity_stats">
				<?php require "application/views/super_admin/dashboard/includes/activity_stats.php"; ?>
			</div>


			<div class="tab-pane" id="plans_features">
				<?php require "application/views/super_admin/plan/plans.php"; ?>
			</div>

			
			<div class="tab-pane fade" id="quick_email">
				
				<?php 
				$q_form_attributes = array("id" => "quick_mail_form");
				echo form_open('super_admin/send_quick_mail_ajax', $q_form_attributes); ?>

					<h3 class="m-b-30">Quick Mail</h3>	
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
						<textarea name="message" class="form-control t200" required></textarea>
					</div>
					
					<div id="q_status_msg"></div>
					
					<div class="form-group">       
						<input type="submit" value="Send Mail" class="btn btn-lg btn-primary">
					</div>

				<?php echo form_close(); ?>

			</div>

			
			<div class="tab-pane fade" id="bulk_email">

				<?php 
				$b_form_attributes = array("id" => "bulk_mail_form");
				echo form_open('super_admin/send_bulk_mail_ajax', $b_form_attributes); ?>

					<h3 class="m-b-30">Bulk Mail</h3>	
					<div class="form-group">
						<label class="form-control-label">Mailing List</label>
						<select class="form-control" name="mailing_list" required>
							<option value="">Select Mailing List</option>	
							<option value="chief_admins">School Chief Admins (<?php echo $total_chief_admins; ?>)</option>
						</select>
					</div>
					<div class="form-group">
						<label class="form-control-label">Subject</label>
						<input type="text" name="subject" class="form-control" required />
					</div>
					<div class="form-group">
						<label class="form-control-label">Message</label>
						<textarea name="message" class="form-control t200" required></textarea>
					</div>
					
					<div id="b_status_msg"></div>
					
					<div class="form-group">       
						<input type="submit" value="Send Mail" class="btn btn-lg btn-primary">
					</div>

				<?php echo form_close(); ?>

			</div>
			
		</div>
	</div>
</div>