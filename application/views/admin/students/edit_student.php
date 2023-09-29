
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

<div class="new-item">
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('students_admin/student_profile/'.$y->id); ?>"><i class="fa fa-user"></i> View Profile</a>
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('students_admin/students'); ?>"><i class="fa fa-users"></i> All Students</a>
</div>

	<?php echo form_open_multipart('students_admin/edit_student_action/'.$y->id); ?>
	
		All fields marked * are required.
	
		<div class="row">
		
			<div class="col-md-6 col-sm-12 col-xs-12">

				<div class="column_title">
					<h4><b>Student Details</b></h4>
					<hr>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Registration ID (Will be used by student to log into student panel)</label>
					<br/>
					<input type="text" value="<?php echo $y->reg_id; ?>" class="form-control" readonly />
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Admission ID*</label>
					<br/>
					<input type="text" name="admission_id" value="<?php echo set_value('admission_id', $y->admission_id); ?>" class="form-control" required />
					<div class="form-error"><?php echo form_error('admission_id'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Last Name*</label>
					<br/>
					<input type="text" name="last_name" value="<?php echo set_value('last_name', $y->last_name); ?>" class="form-control" required />
					<div class="form-error"><?php echo form_error('last_name'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">First Name*</label>
					<br/>
					<input type="text" name="first_name" value="<?php echo set_value('first_name', $y->first_name); ?>" class="form-control" required />
					<div class="form-error"><?php echo form_error('first_name'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Other Names</label>
					<br/>
					<input type="text" name="other_name" value="<?php echo set_value('other_name', $y->other_name); ?>" class="form-control" />
					<div class="form-error"><?php echo form_error('other_name'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Current Class*</label>
					<select class="form-control selectpicker" name="class_id" required>

						<?php 
						if ($current_class == NULL) { //class ID does not exist as a valid class ?>
							<option selected value="">Select Class</option>
						<?php } else { ?>
							<option selected value="<?php echo $y->class_id; ?>"><?php echo $current_class; ?></option>
						<?php } ?>

						<?php echo $classes_option; ?>

					</select>
					<div class="form-error"><?php echo form_error('class_id'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Date of Birth*</label>
					<div class="input-group date date_datepicker_no_future" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control" name="date_of_birth" value="<?php echo set_value('date_of_birth', $y->date_of_birth); ?>" readonly required />
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
					</div>
					<div class="form-error"><?php echo form_error('date_of_birth'); ?></div>
				</div>
				
				<div class="form-group ">
					<label class="form-control-label m-r-20">Sex*</label>
					<label><input type="radio" name="sex" value="Male" <?php echo set_radio( 'sex', 'Male', radio_value($y->sex, 'Male') ); ?> > Male</label>
					<label class="m-l-10"><input type="radio" name="sex" value="Female" <?php echo set_radio( 'sex', 'Female', radio_value($y->sex, 'Female') ); ?>> Female</label>
					<div class="form-error"><?php echo form_error('sex'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Blood Group</label>
					<select class="form-control" name="blood_group">
						<option selected value="<?php echo $y->blood_group; ?>"><?php echo $y->blood_group; ?></option>
						<option value="A" <?php echo set_select('blood_group', 'A'); ?> >A</option>
						<option value="AB" <?php echo set_select('blood_group', 'AB'); ?> >AB</option>
						<option value="B" <?php echo set_select('blood_group', 'B'); ?> >B</option>
						<option value="O<sup>+</sup>" <?php echo set_select('blood_group', 'O<sup>+</sup>'); ?> >O<sup>+</sup></option>
						<option value="O<sup>-</sup>" <?php echo set_select('blood_group', 'O<sup>-</sup>'); ?> >O<sup>-</sup></option>
					</select>
					<div class="form-error"><?php echo form_error('blood_group'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Place of Birth</label>
					<br/>
					<input type="text" name="place_of_birth" value="<?php echo set_value('place_of_birth', $y->place_of_birth); ?>" class="form-control" />
					<div class="form-error"><?php echo form_error('place_of_birth'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Nationality*</label>
					<br/>
					<select class="form-control" name="nationality" id="nationality" required>
						<option value="<?php echo s_country; ?>" <?php echo set_select('nationality', $y->nationality); ?> ><?php echo $y->nationality; ?></option>

						<?php 
						$countries = countries();
						foreach ($countries as $country) { ?>
							<option value="<?php echo $country; ?>" <?php echo set_select('nationality', $country); ?> ><?php echo $country; ?></option>
						<?php } ?> 

					</select>
					<div class="form-error"><?php echo form_error('nationality'); ?></div>
				</div>
							
				<?php 
				//show State and LGA if school country location is Nigeria 
				if (s_country == 'Nigeria') { ?>
				
					<div id="">
						<div class="form-group">
							<label class="form-control-label">State of Origin*</label>
							<select class="form-control" name="state_of_origin" id="state">
								<option selected value="<?php echo $y->state_of_origin; ?>"><?php echo $y->state_of_origin; ?></option>
								<?php 
								$states = nigerian_states();
								foreach ($states as $state ) { ?>
									<option value="<?php echo $state; ?>"><?php echo $state; ?></option>
								<?php }
								 ?>
							</select>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">L.G.A*</label>
							<select class="form-control" name="local_gov" id="lga">
								<option selected value="<?php echo $y->local_gov; ?>"><?php echo $y->local_gov; ?></option>
								<!--Dependent based selection-->
							</select>
						</div>
					</div>
					
				<?php } ?>

				<div class="form-group">
					<label class="form-control-label">Home Town</label>
					<br/>
					<input type="text" name="home_town" class="form-control" value="<?php echo set_value('home_town', $y->home_town); ?>" />
					<div class="form-error"><?php echo form_error('home_town'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Home Address</label>
					<textarea class="form-control" name="home_address"><?php echo set_value('home_address', strip_tags($y->home_address)); ?></textarea>
					<div class="form-error"><?php echo form_error('home_address'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Additional Information</label>
					<textarea class="form-control" name="additional_info"><?php echo set_value('additional_info', strip_tags($y->additional_info)); ?></textarea>
					<div class="form-error"><?php echo form_error('additional_info'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Religion</label>
					<select class="form-control" name="religion">
						<option value="">Select</option>
						
						<?php 
						$religions = world_religions();
						foreach ($religions as $religion ) { 
							//add selected attribute to preset religion
							$selected = ($religion == $y->religion) ? 'selected' : NULL; ?>
							<option <?php echo $selected; ?> value="<?php echo $religion; ?>"><?php echo $religion; ?></option>
						<?php } ?>

					</select>
					<div class="form-error"><?php echo form_error('religion'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Former School (Leave blank if fresh pupil)</label>
					<br/>
					<input type="text" name="present_school" class="form-control" value="<?php echo set_value('present_school', $y->present_school); ?>" />
					<div class="form-error"><?php echo form_error('present_school'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Former School Address (Leave blank if fresh pupil)</label>
					<input class="form-control" name="present_school_address"  value="<?php echo set_value('present_school_address', $y->present_school_address); ?>" />
					<div class="form-error"><?php echo form_error('present_school_address'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Last Class in Former School (Leave blank if fresh pupil)</label>
					<br/>
					<input type="text" name="present_class" class="form-control" value="<?php echo set_value('present_class', $y->present_class); ?>" />
					<div class="form-error"><?php echo form_error('present_class'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Admission Date*</label>
					<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control" name="admission_date" value="<?php echo set_value('admission_date', $y->admission_date); ?>" readonly required />
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
					</div>
					<div class="form-error"><?php echo form_error('admission_date'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Password Reset Code (Will be used to reset student's password if they forget their password. Disclose to student or parent/guardian only)</label>
					<br/>
					<input type="text" value="<?php echo $y->pass_reset_code; ?>" class="form-control" readonly />
				</div>
				
			</div><!--/.col-->
			
			

			<div class="col-md-6 col-sm-12 col-xs-12">
				
				<div class="column_title_I">
					<h4><b>Student Passport (Optional)</b></h4>
					<hr>
				</div>
				
				<div class="form-group">
				
					<?php 
					if ($y->passport_photo != NULL) { ?>
					
						<div id="current_image_area" class="m-b-10">
							<img id="current_image" src="<?php echo base_url('assets/uploads/photos/students/' .$y->passport_photo); ?>" />
						</div>
						<label class="form-control-label" id="change_image_text">Change Photo?</label> <br />
						
						<div class="file_area">
							<small>Only JPG files allowed (max 64KB).</small>
							<input type="file" name="passport_photo" id="the_image_on_update" class="form-control" accept=".jpeg,.jpg" value="<?php echo set_value('passport_photo'); ?>" />
							<div class="form-error"><?php echo $upload_error['error']; ?></div>
						</div>
						
					<?php } else { ?>
						
						<div id="current_image_area" class="m-b-10">
							<img id="current_image" src="<?php echo ($y->sex == 'Male') ? male_student_avatar : female_student_avatar; ?>" />
						</div>
						<label class="form-control-label" id="change_image_text">Upload Passport Photo?</label> <br />
						
						<div class="file_area">
							<small>Only JPG files allowed (max 64KB).</small>
							<input type="file" name="passport_photo" id="the_image_on_update" class="form-control" accept=".jpeg,.jpg" value="<?php echo set_value('passport_photo'); ?>" />
							<div class="form-error"><?php echo $upload_error['error']; ?></div>
						</div>
						
					<?php } ?>
					
				</div>		
				<!-- Image preview-->
				<?php echo generate_image_preview(); ?>
				
				
				<div class="m-t-10">
					<button class="btn btn-primary btn-lg">Update</button>
				</div>
			
			</div><!--/.col-->
			
		</div><!--/.row-->

		
	<?php echo form_close(); ?>

		