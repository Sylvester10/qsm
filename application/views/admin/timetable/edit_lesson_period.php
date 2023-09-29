
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('timetable_admin/lesson_periods/'.$d->class_id); ?>"><i class="fa fa-clock-o"></i> Lesson Periods</a>
	</div>


<div class="row">
	<div class="col-md-6">
	
		<?php
		$form_attributes = array("id" => "edit_lesson_period_form");
		echo form_open('timetable_admin/edit_lesson_period_ajax/'.$d->id, $form_attributes); 
		
			$the_class = $this->common_model->get_class_details($d->class_id)->class; ?>
			
			<input type="hidden" id="lesson_period_id" value="<?php echo $d->id; ?>" />
			<input type="hidden" id="class_id" value="<?php echo $d->class_id; ?>" />

			<div class="form-group">
				<label class="form-control-label">Class</label>
				<select name="class_id" class="form-control selectpicker" required readonly>
					<option selected value="<?php echo $d->class_id; ?>"><?php echo $the_class; ?></option>
				</select>
			</div>

			<div class="form-group">
				<label class="form-control-label">Period Type</label>
				<select name="period_type" id="period_type" class="form-control selectpicker" required>
					<option <?php echo ($d->period_type == 'Subject') ? 'selected' : NULL; ?> value="Subject" id="pt_subject">Subject</option>
					<option <?php echo ($d->period_type == 'Break') ? 'selected' : NULL; ?> value="Break" id="pt_break">Break</option>
					<option <?php echo ($d->period_type == 'Other Activity') ? 'selected' : NULL; ?> value="Other Activity" id="pt_other_activity">Other Activity</option>
				</select>
			</div>

			<div class="form-group" id="subject_area" <?php if ($d->period_type == 'Subject') { ?> style="display: block" <?php } else { ?> style="display: none" <?php } ?> >
				<label class="form-control-label">Subject</label>
				<select name="subject_id" id="subject_id" class="form-control selectpicker">
					<option selected value="<?php echo $d->subject_id; ?>"><?php echo $subject; ?></option>
					<?php echo $subjects_option; ?>
				</select>
			</div>
			
			<div class="form-group" id="break_area" <?php if ($d->period_type == 'Break') { ?> style="display: block" <?php } else { ?> style="display: none" <?php } ?> >
				<label class="form-control-label">Break Type</label>
				<select name="break_type" id="break_type" class="form-control selectpicker">
					<option <?php echo ($d->activity == 'Short Break') ? 'selected' : NULL; ?> value="Short Break">Short Break</option>
					<option <?php echo ($d->activity == 'Long Break') ? 'selected' : NULL; ?>  value="Long Break">Long Break</option>
					<option <?php echo ($d->activity == 'Normal Break') ? 'selected' : NULL; ?> value="Normal Break">Normal Break</option>
				</select>
			</div>
			
			<div class="form-group" id="other_activity_area" <?php if ($d->period_type == 'Other Activity') { ?> style="display: block" <?php } else { ?> style="display: none" <?php } ?> >
				<label class="form-control-label">Other Activity <small>(e.g. General Assembly, House Meeting, Extra-mural Lessons, Closure, etc)</small></label>
				<input type="text" name="other_activity" id="other_activity" value="<?php echo $subject; ?>" class="form-control" placeholder="enter activity here" />
			</div>

			<div class="form-group">
				<label class="form-control-label">Day</label>
				<select name="day" class="form-control selectpicker" required>
					<option selected value="<?php echo $d->day; ?>"><?php echo $d->day; ?></option>
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
					<input name="start_time" id="timepicker" type="text" value="<?php echo $d->start_time; ?>"  class="form-control input-small" required readonly />
					<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
				</div>
			</div>

			<div class="form-group">
				<label class="form-control-label">Ending Period</label>
				<div class="input-group bootstrap-timepicker timepicker">
					<input name="end_time" id="timepicker2" type="text" value="<?php echo $d->end_time; ?>" class="form-control input-small" required readonly />
					<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
				</div>
			</div>
			
			<div id="status_msg"></div>
			
			<div>
				<button class="btn btn-primary">Update</button>
			</div>
		<?php echo form_close(); ?>
		
	</div>
</div>