
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

<div class="new-item m-b-30">
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('homework_class_teacher/view_homework/'.$y->id); ?>" ><i class="fa fa-eye"></i> View Homework</a>
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('homework_class_teacher/homework'); ?>" ><i class="fa fa-tasks"></i> All Homework</a>
</div>
		
<?php 
echo form_open_multipart('homework_class_teacher/edit_homework_action/'.$y->id); ?>
	
	<div class="row">
		<div class="col-md-8 col-sm-12 col-xs-12">

			<div class="form-group">
				<label class="form-control-label">Homework</label>
				<textarea name="homework" class="form-control t250"><?php echo set_value('homework', strip_tags($y->the_homework)); ?></textarea>
				<div class="form-error"><?php echo form_error('homework'); ?></div>
			</div>

			<div class="form-group m-t-20">
				<label class="form-control-label">Additional Message (optional)</label>
				<textarea name="additional_message" class="form-control t100"><?php echo set_value('additional_message', $y->additional_message); ?></textarea>
				<div class="form-error"><?php echo form_error('additional_message'); ?></div>
			</div>

		</div>

		<div class="col-md-4 col-sm-12 col-xs-12">

			<div class="form-group">
				<label class="form-control-label">Upload Material (Optional)</label><br />
				<small>Only image, Word, PDF and zip files allowed (max 5MB). If the material is more than one, organise them and compress into a zip file. </small>
				<br />
				
				<?php if ($y->material != NULL) { ?>
					Material: <?php echo $y->material; ?>
					<a href="<?php echo base_url('homework_class_teacher/delete_homework_material/'.$y->id); ?>" class="underline-link m-l-5">delete</a>
				<?php } ?>

				<input type="file" name="material" class="form-control" />
				<div class="form-error"><?php echo $upload_error['error']; ?></div>
			</div>

			<div class="form-group">
				<label class="form-control-label">Class</label>
				<select name="class_id" class="form-control selectpicker" required readonly>
					<option selected value="<?php echo $y->class_id; ?>"><?php echo $class; ?></option>
				</select>
			</div>

			<div class="form-group">
				<label class="form-control-label">Subject</label>
				<select name="subject_id" class="form-control selectpicker" required>
					<option selected value="<?php echo $y->subject_id; ?>"><?php echo $subject; ?></option>
					<?php echo $subjects_option; ?>
				</select>
			</div>
		
			<div class="form-group">
				<label class="form-control-label">Date of Submission</label>
				<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
					<input type="text" class="form-control" name="submission_date" value="<?php echo set_value('submission_date', $y->submission_date); ?>" required readonly />
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
				</div>
			</div>

			<div class="form-group">       
				<button type="submit" class="btn btn-primary btn-lg m-t-5">Update</button>
			</div>

		</div>

	</div><!--/.row-->
					
<?php echo form_close(); ?>	
			