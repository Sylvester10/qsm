
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('school_staff/edit_staff/'.$y->id); ?>"><i class="fa fa-pencil"></i> Edit Staff</a>
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('school_staff/staff_role/'.$y->id); ?>"><i class="fa fa-tasks"></i> Manage Role</a>
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('school_staff/subject_teachers'); ?>"><i class="fa fa-users"></i> All Subject Teachers</a>
	</div>

	<div class="row">
	
		<div class="col-md-6">

			<h3><?php echo $y->title . ' ' . $y->name; ?></h3>

			<?php 
			$form_attributes = array("id" => "subject_teacher_assignment_form");
			echo form_open('school_staff/subject_teacher_assignment_ajax/'.$subject_teacher_id, $form_attributes); ?>

				<input type="hidden" id="subject_teacher_id" value="<?php echo $subject_teacher_id; ?>" />
					
				<div class="form-group">
					<label>Assigned Class(es): <?php echo $assigned_classes; ?></label>
					<select class="form-control selectpicker" name="classes_assigned[]" multiple required>
						<?php echo $classes_option; ?>
					</select>
					<div class="form-error"><?php echo form_error('classes_assigned'); ?></div>
				</div>

				<div class="form-group">
					<label>Assigned Subject(s): <?php echo $assigned_subjects; ?></label>
					<select class="form-control selectpicker" name="subjects_assigned[]" multiple required>
						<?php echo $subjects_option; ?>
					</select>
					<div class="form-error"><?php echo form_error('subjects_assigned'); ?></div>
				</div>

				<div id="status_msg"></div>
				
				<div class="m-t-20">
					<button class="btn btn-primary btn-lg">Update</button>
				</div>

			<?php echo form_close(); ?>
			
		</div><!--/.col-->
		
	</div><!--/.row-->
	