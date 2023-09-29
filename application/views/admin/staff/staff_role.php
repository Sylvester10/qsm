
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('school_staff/edit_staff/'.$y->id); ?>"><i class="fa fa-pencil"></i> Edit Staff</a>
	</div>

	<div class="row">
	
		<div class="col-md-8">

			<b>Staff Information</b>
			<div>Name: <?php echo $y->title . ' ' . $y->name; ?></div>
			<div>Designation: <?php echo $y->designation; ?></div>
			<div>Qualification: <?php echo $y->qualification; ?></div>
			<div>Date of Employment: <?php echo $y->employment_date; ?></div>	

			<div class="text-bold m-t-30">Quick Note About Staff Role</div>
			<div class="m-b-10">Staff role determines who gets access to which section of this application. Multiple roles are possible.</div>
			
			<p><span class="text-bold">School Manager:</span> Concerned with the day-to-day, non-academic activities of the school. Can make requests, submit weekly reports.</p>
			
			<p><span class="text-bold">Academic Administrator:</span> Concerned with the day-to-day, academic activities of the school. Can manage all students and staff, action student reports, make requests, submit weekly reports. Suitable for Principals and Head Teachers (as well as their assistants).</p>
			
			<p><span class="text-bold">Class Teacher:</span> Concerned with managing a single class. Can produce student reports, mark attendance, submit homework. Suitable for Class Teachers (as well as their assistants).</p>

			<p><span class="text-bold">Subject Teacher:</span> Concerned with managing a single subject or multiple subjects across different classes. Can produce student reports for subjects assigned to them, submit homework. Suitable for teachers in secondary schools who teach one or more subjects to different classes. Ensure to <a href="<?php echo base_url('school_staff/subject_teachers'); ?>" target="_blank" class="underline-link">assign class(es) and subject(s) to staff</a> after assigning the Subject Teacher role to them.</p>
			
			<p><span class="text-bold">Bursar:</span> Concerned with managing fees. Can action fees. Suitable for the school bursar or accountant.</p>
				
			<p><span class="text-bold">Publication Manager:</span> Concerned with managing publications such as Announcement, Newsletters, News, Term Dates, and School Calendar Events. Suitable for staff delegated to oversee this.</p>
		
			<p><span class="text-bold">Basic Staff:</span> Suitable for any other staff who may not have much interaction with the application.</p>


			<?php 
			$form_attributes = array("id" => "staff_role_form");
			echo form_open('school_staff/staff_role_ajax/'.$y->id, $form_attributes); ?>

				<input type="hidden" id="staff_id" value="<?php echo $y->id; ?>" />
					
				<div class="form-group">
					<label>Assigned Role(s): <?php echo $y->role; ?></label>
					<select class="form-control selectpicker" name="role[]" multiple required>

						<?php 
						$staff_roles = staff_roles();
						foreach ($staff_roles as $role) { ?>
							<option value="<?php echo $role; ?>"><?php echo $role; ?></option>
						<?php } ?>

					</select>
					<div class="form-error"><?php echo form_error('role'); ?></div>
				</div>

				<div id="status_msg"></div>
				
				<div class="m-t-20">
					<button class="btn btn-primary btn-lg">Update</button>
				</div>

			<?php echo form_close(); ?>
			
		</div><!--/.col-->
		
	</div><!--/.row-->
	