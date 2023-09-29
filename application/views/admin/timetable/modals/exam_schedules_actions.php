	
	<div class="modal fade" id="new_schedule" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">New Exam Schedule</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					<?php 
					$form_attributes = array("id" => "new_exam_schedule_form");
					echo form_open('timetable_admin/new_exam_schedule_ajax', $form_attributes); ?>
					
						<div class="form-group">
							<label class="form-control-label">Exam Date</label>
							<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
								<input type="text" class="form-control" name="date" value="<?php echo set_value('date', default_calendar_date()); ?>" required readonly />
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Exam Time</label>
							<div class="input-group bootstrap-timepicker timepicker">
								<input name="time" id="timepicker" type="text" class="form-control input-small" required readonly />
								<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
							</div>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Class(es)</label>
							<select name="class_id[]" class="form-control selectpicker" multiple required>
								<?php echo $classes_option; ?>
							</select>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Subject</label>
							<select name="subject_id" class="form-control selectpicker" required>
								<option value="">Select Subject</option>
								<?php echo $subjects_option; ?>
							</select>
						</div>
						
						<div id="status_msg"></div>
						
						<div>
							<button class="btn btn-primary">Submit </button>
							<a type="button" class="btn btn-primary" href="<?php echo base_url('refresh_page'); ?>" title="Refresh page to see changes">Refresh Page</a>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
	
	
	<!--Schedules Clear Confirm-->
	<div class="modal fade" id="clear" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Clear Schedules</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					Are you sure you want to clear all exam schedules? This action will delete all the records. <br />
				</div>
				<div class="modal-footer">
					<a class="btn btn-sm btn-danger" role="button" href="<?php echo base_url('timetable_admin/clear_exam_schedules'); ?>"> Yes, Clear All </a>
					<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
				</div>
			</div>
		</div>
	</div>