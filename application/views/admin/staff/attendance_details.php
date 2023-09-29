
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('school_staff/staff_attendance'); ?>"><i class="fa fa-histroy"></i> Mark Attendance</a>
	</div>

	<div class="row m-b-50">
	
		<div class="animated flipInY col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<div class="tile-stats custom-bg-blue">
				<div class="icon"><i class="fa fa-check"></i></div>
				<div class="count"><?php echo $att_present; ?></div>
				<h3 class="stats-title">Present</h3>
			</div>
		</div>
		<div class="animated flipInY col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<div class="tile-stats custom-bg-blue">
				<div class="icon"><i class="fa fa-times"></i></div>
				<div class="count"><?php echo $att_absent; ?></div>
				<h3 class="stats-title">Absent</h3>
			</div>
		</div>
		<div class="animated flipInY col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<div class="tile-stats custom-bg-blue">
				<div class="icon"><i class="fa fa-th"></i></div>
				<div class="count"><?php echo $att_total; ?></div>
				<h3 class="stats-title">Total</h3>
			</div>
		</div>
		
	</div>
		

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			
			<div class="x_panel">
				<div class="x_title">
					<h4 class="page_title text-bold f-s-17">Check Attendance Details</h4>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">

					<h3 class="text-bold">Staff Name: <?php echo $staff_name; ?></h3>
					<p>Select date to see attendance details.</p>

					<?php 
					$form_attributes = array("id" => "check_attendance_form");
					echo form_open('school_staff/check_attendance_ajax/'.$id, $form_attributes); ?>

						<input type="hidden" id="staff_id" value="<?php echo $id; ?>" /> 

						<div class="form-group">
							<label class="form-control-label">Select Date</label>
							<div class="input-group date date_datepicker_no_future" data-date-format="yyyy-mm-dd">
								<input type="text" class="form-control" name="date" value="<?php echo set_value('date', default_calendar_date()); ?>" required readonly />
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
							</div>
						</div>

						<div id="status_msg"></div>

						<div id="attendance_details_box" class="p-t-10" style="display: none">
							<p>Session: <span id="session"></span></p>
							<p>Term: <span id="term"></span></p>
							<p>Date: <span id="date"></span></p>
							<p>Status: <span id="status"></span></p>
						</div>

						<div class="m-t-20">
							<button class="btn btn-primary btn-lg">Check</button>
						</div>

					<?php echo form_close(); ?>


				</div>
			</div>

		</div><!--/.col-->
	</div><!--/.row-->