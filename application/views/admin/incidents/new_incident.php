
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>


	<div class="new-item m-b-20">
		
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('incidents/student_incidents/'.$student_id); ?>"><i class="fa fa-user"></i> Student's Incidents</a>
		
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('incidents'); ?>"><i class="fa fa-history"></i> All Incidents</a>

	</div>



	<?php 
	$form_attributes = array("id" => "new_incident_form");
	echo form_open('incidents/new_incident_ajax/'.$student_id, $form_attributes); ?>

		<input type="hidden" id="student_id" value="<?php echo $student_id; ?>" />
	
		<div class="row">
		
			<div class="col-md-6 col-sm-12 col-xs-12">

				<div class="form-group">
					<label class="form-control-label">Student's Name</label>
					<br/>
					<input type="text" value="<?php echo $student_name; ?>" class="form-control" readonly />
				</div>

				<div class="form-group">
					<label class="form-control-label">Student's Admission ID</label>
					<br/>
					<input type="text" value="<?php echo $admission_id; ?>" class="form-control" readonly />
				</div>

				<div class="form-group">
					<label class="form-control-label">Student's Class</label>
					<br/>
					<input type="text" value="<?php echo $class; ?>" class="form-control" readonly />
				</div>

				<div class="form-group">
					<label class="form-control-label">Incident Caption/Title</label>
					<br/>
					<input type="text" name="caption" value="<?php echo set_value('caption'); ?>" class="form-control" required />
					<div class="form-error"><?php echo form_error('caption'); ?></div>
				</div>


				<div class="form-group">
					<label class="form-control-label">Incident Description</label>
					<br/>
					<textarea name="description" class="form-control t200" required><?php echo set_value('description'); ?></textarea>
					<div class="form-error"><?php echo form_error('description'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Date of Incident</label>
					<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control" name="incident_date" value="<?php echo set_value('incident_date', default_calendar_date()); ?>" readonly required />
						<div class="form-error"><?php echo form_error('incident_date'); ?></div>
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Actions Taken</label>
					<br/>
					<textarea name="actions_taken" class="form-control t200" required><?php echo set_value('actions_taken'); ?></textarea>
					<div class="form-error"><?php echo form_error('actions_taken'); ?></div>
				</div>


				<div id="status_msg"></div>

				<div class="form-group">
					<button class="btn btn-primary">Submit</button>
				</div>
			
			</div><!--/.col-->
		
		</div><!--/.row-->

		
	<?php echo form_close(); ?>

		