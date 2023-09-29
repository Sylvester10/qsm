
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

<div class="new-item m-b-30">
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('homework_class_teacher/homework'); ?>" ><i class="fa fa-tasks"></i> All Homework</a>
</div>
		
<?php 
echo form_open_multipart('homework_class_teacher/new_homework_action'); ?>
	
	<div class="row">
		<div class="col-md-8 col-sm-12 col-xs-12">

			<div class="form-group">
				<label class="form-control-label">Homework</label>
				<textarea name="homework" class="form-control t250" required><?php echo set_value('homework'); ?></textarea>
				<div class="form-error"><?php echo form_error('homework'); ?></div>
			</div>

			<div class="form-group m-t-20">
				<label class="form-control-label">Additional Message (optional)</label>
				<textarea name="additional_message" class="form-control t100"><?php echo set_value('additional_message'); ?></textarea>
				<div class="form-error"><?php echo form_error('additional_message'); ?></div>
			</div>

		</div>

		<div class="col-md-4 col-sm-12 col-xs-12">

			<div class="form-group">
				<label class="form-control-label">Upload Material (Optional)</label><br />
				<small>Only image, Word, PDF and zip files allowed (max 5MB). If the material is more than one, organise them and compress into a zip file. </small>
				<input type="file" name="material" class="form-control" />
				<div class="form-error"><?php echo $upload_error['error']; ?></div>
			</div>

			<div class="form-group">
				<label class="form-control-label">Class</label>
				<select name="class_id" class="form-control selectpicker" required readonly>
					<option selected value="<?php echo $class_id; ?>"><?php echo $class; ?></option>
				</select>
			</div>

			<div class="form-group">
				<label class="form-control-label">Subject</label>
				<select name="subject_id" class="form-control selectpicker" required>
					<option value="">Select Subject</option>
					<?php echo $subjects_option; ?>
				</select>
			</div>
		
			<div class="form-group">
				<label class="form-control-label">Date of Submission</label>
				<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
					<input type="text" class="form-control" name="submission_date" value="<?php echo set_value('submission_date', default_calendar_date()); ?>" required readonly />
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
				</div>
			</div>

			<div class="form-group">       
				<button type="submit" class="btn btn-primary btn-lg m-t-5">Submit</button>
			</div>

		</div>

	</div><!--/.row-->
					
<?php echo form_close(); ?>	
			