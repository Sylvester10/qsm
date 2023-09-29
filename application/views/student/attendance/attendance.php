
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

					<div class="">
						<b>Last 10 Attendance</b>
						<br />
						<?php 
						$limit = 10;
						$recent_attendance = $this->student_model->get_recent_attendance($limit);

						if ( count($recent_attendance) == 0 ) { ?>

							<p class="text-danger">No attendance data found for the current term.</p>

						<?php } else { 

							foreach ($recent_attendance as $r) { ?>
								
								<div class="recent_attendance">
									<small>
										<?php echo x_date($r->date); 
										if ($r->status == 'Present') { ?> 
											<i class="fa fa-check text-success"></i>
										<?php } else { ?>
											<i class="fa fa-times text-danger"></i>
										<?php } ?>
									</small>
								</div>

							<?php } ?>

						<?php } ?>
						
					</div>

					<br />
					<p>Select date to see attendance details.</p>

					<?php 
					$form_attributes = array("id" => "check_attendance_form");
					echo form_open('student/check_attendance_ajax', $form_attributes); ?>

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
							<p>Class: <span id="class"></span></p>
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
