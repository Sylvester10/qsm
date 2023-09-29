	
	<div class="modal fade" id="new_period" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">New Lesson Period</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					<?php 
					$form_attributes = array("id" => "new_lesson_period_form");
					echo form_open('timetable_admin/new_lesson_period_ajax', $form_attributes); ?>

						<div class="form-group">
							<label class="form-control-label">Class</label>
							<select name="class_id" class="form-control selectpicker" required readonly>
								<option selected value="<?php echo $class_id; ?>"><?php echo $class; ?></option>
							</select>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Period Type</label>
							<select name="period_type" id="period_type" class="form-control selectpicker" required>
								<option selected value="Subject" id="pt_subject">Subject</option>
								<option value="Break" id="pt_break">Break</option>
								<option value="Other Activity" id="pt_other_activity">Other Activity</option>
							</select>
						</div>
 
						<div class="form-group" id="subject_area">
							<label class="form-control-label">Subject</label>
							<select name="subject_id" id="subject_id" class="form-control selectpicker">
								<option value="">Select Subject</option>
								<?php echo $subjects_option; ?>
							</select>
						</div>
						
						<div class="form-group" id="break_area" style="display: none">
							<label class="form-control-label">Break Type</label>
							<select name="break_type" id="break_type" class="form-control selectpicker">
								<option value="">Select Break Type</option>
								<option value="Short Break">Short Break</option>
								<option value="Long Break">Long Break</option>
								<option value="Normal Break">Normal Break</option>
							</select>
						</div>
						
						<div class="form-group" id="other_activity_area" style="display: none">
							<label class="form-control-label">Other Activity <small>(e.g. General Assembly, House Meeting, Extra-mural Lessons, Closure, etc)</small></label>
							<input type="text" name="other_activity" id="other_activity" class="form-control" placeholder="enter activity here" />
						</div>
 
						<div class="form-group">
							<label class="form-control-label">Day</label>
							<select name="day" class="form-control selectpicker" required>
								<option value="">Select Day</option>
								<option value="Monday">Monday</option>
								<option value="Tuesday">Tuesday</option>
								<option value="Wednesday">Wednesday</option>
								<option value="Thursday">Thursday</option>
								<option value="Friday">Friday</option>	
							</select>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Starting Period</label>
							<div class="input-group bootstrap-timepicker timepicker">
								<input name="start_time" id="timepicker" type="text" class="form-control input-small" required readonly />
								<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
							</div>
						</div>

						<div class="form-group">
							<label class="form-control-label">Ending Period</label>
							<div class="input-group bootstrap-timepicker timepicker">
								<input name="end_time" id="timepicker2" type="text" class="form-control input-small" required readonly />
								<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
							</div>
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



	<!--Duplicate Lesson Periods-->
	<div class="modal fade hide" id="duplicate" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Duplicate Periods</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					<p>Note: This action will copy all the lesson periods for <?php echo $class; ?> into the selected class.</p>

					<?php echo form_open('timetable_admin/duplicate_lesson_period/'.$class_id); ?>

						<input type="hidden" id="old_class_id" value="<?php echo $class_id; ?>" />
						<input type="hidden" id="old_class" value="<?php echo $class; ?>" />

						<div class="form-group">
							<label class="form-control-label">Class</label>
							<select name="class_id" class="form-control selectpicker" id="new_class" required>
								<option value="">Select Class</option>
								<?php echo $classes_option; ?>
							</select>
						</div>
						
						<div id="status_msg"></div>
						
						<div>
							<button class="btn btn-primary">Submit</button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
	

	
	<!--Lesson Periods Clear Confirm-->
	<div class="modal fade" id="clear" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Clear Periods</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					Are you sure you want to clear all lesson periods for <?php echo $class; ?>? This action will delete all the records for this class. <br />
				</div>
				<div class="modal-footer">
					<a class="btn btn-sm btn-danger" role="button" href="<?php echo base_url('timetable_admin/clear_lesson_periods/'.$class_id); ?>"> Yes, Clear All </a>
					<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
				</div>
			</div>
		</div>
	</div>