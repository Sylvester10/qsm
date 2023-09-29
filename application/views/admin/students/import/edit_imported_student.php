
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<?php echo form_open_multipart('student_import/edit_student_action/'.$y->id); ?>
	
		All fields marked * are required.
	
		<div class="row">
		
			<div class="col-md-6 col-sm-12 col-xs-12">

				<div class="column_title">
					<h4><b>Student Details</b></h4>
					<hr>
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
					<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
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
							<label class="form-control-label">State of Origin</label>
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
							<label class="form-control-label">L.G.A</label>
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
					<input class="form-control" name="home_address" value="<?php echo set_value('home_address', $y->home_address); ?>" />
					<div class="form-error"><?php echo form_error('home_address'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Additional Information</label>
					<textarea class="form-control" name="additional_info"><?php echo set_value('additional_info', $y->additional_info); ?></textarea>
					<div class="form-error"><?php echo form_error('additional_info'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Religion</label>
					<select class="form-control" name="religion">
						
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
				
			</div><!--/.col-->

			<div class="col-md-6 col-sm-12 col-xs-12">

				<div class="column_title">
					<h4><b>First Parents/Guardian Details</b></h4>
					<hr>
				</div>

				<div class="form-group">
					<label class="form-control-label">Name of Parent/Guardian*</label>
					<br/>
					<input type="text" name="parent_name" class="form-control" value="<?php echo set_value('parent_name', $y->parent_name); ?>" required />
					<div class="form-error"><?php echo form_error('parent_name'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label m-r-20">Sex</label>
					<label><input type="radio" name="parent_sex" value="Male" <?php echo set_radio( 'parent_sex', 'Male', radio_value($y->parent_sex, 'Male') ); ?> > Male</label>
					<label class="m-l-10"><input type="radio" name="parent_sex" value="Female" <?php echo set_radio( 'parent_sex', 'Female', radio_value($y->parent_sex, 'Female') ); ?> > Female</label>
					<div class="form-error"><?php echo form_error('parent_sex'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Relationship to Child</label>
					<select class="form-control" name="parent_relationship" id="parent_relationship">
						<option selected value="<?php echo $y->parent_relationship; ?>"><?php echo $y->parent_relationship; ?></option>
						<option value="Father" <?php echo set_select('parent_relationship', 'Father'); ?> >Father</option>
						<option value="Mother" <?php echo set_select('parent_relationship', 'Mother'); ?> >Mother</option>
						<option value="Grand Parent" <?php echo set_select('parent_relationship', 'Grand Parent'); ?> >Grand Parent</option>
						<option value="Uncle" <?php echo set_select('parent_relationship', 'Uncle'); ?> >Uncle</option>
						<option value="Aunty" <?php echo set_select('parent_relationship', 'Aunty'); ?> >Aunty</option>
						<option value="Other" id="parent_relationship_other_option" <?php echo set_select('parent_relationship', 'Other'); ?> >Other</option>
					</select>
					<div class="form-error"><?php echo form_error('parent_relationship'); ?></div>
				</div>

				<div class="form-group" style="display: none;" id="parent_relationship_other_area">
					<label class="form-control-label">Specify relationship to child</label>
					<br/>
					<input type="text" name="parents_relationship_other" class="form-control" value="<?php echo set_value('parents_relationship_other'); ?>" />
					<div class="form-error"><?php echo form_error('parents_relationship_other'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Mobile</label>
					<br/>
					<input type="text" name="parent_phone" value="<?php echo set_value('parent_phone', $y->parent_phone); ?>" class="form-control numbers-only" />
					<div class="form-error"><?php echo form_error('parent_phone'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Email</label>
					<br/>
					<input type="email" name="parent_email" class="form-control" value="<?php echo set_value('parent_email', $y->parent_email); ?>" />
					<div class="form-error"><?php echo form_error('parent_email'); ?></div>
				</div>
				
				<div class="column_title_I">
					<h4><b>Second Parent/Guardian Details</b></h4>
					<hr>
				</div>

				<div class="form-group">
					<label class="form-control-label">Name of Parent/Guardian</label>
					<br/>
					<input type="text" name="sec_parent_name" class="form-control" value="<?php echo set_value('sec_parent_name', $y->sec_parent_name); ?>" />
					<div class="form-error"><?php echo form_error('sec_parent_name'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label m-r-20">Sex</label>
					<label><input type="radio" name="sec_parent_sex" value="Male" <?php echo set_radio( 'sec_parent_sex', 'Male', radio_value($y->sec_parent_sex, 'Male') ); ?> > Male</label>
					<label class="m-l-10"><input type="radio" name="sec_parent_sex" value="Female" <?php echo set_radio( 'sec_parent_sex', 'Female', radio_value($y->sec_parent_sex, 'Female') ); ?> > Female</label>
					<div class="form-error"><?php echo form_error('sec_parent_sex'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Relationship to Child</label>
					<select class="form-control" name="sec_parent_relationship" id="sec_parent_relationship">
						<option selected value="<?php echo $y->sec_parent_relationship; ?>"><?php echo $y->sec_parent_relationship; ?></option>
						<option value="Father" <?php echo set_select('sec_parent_relationship', 'Father'); ?> >Father</option>
						<option value="Mother" <?php echo set_select('sec_parent_relationship', 'Mother'); ?> >Mother</option>
						<option value="Grand Parent" <?php echo set_select('sec_parent_relationship', 'Grand Parent'); ?> >Grand Parent</option>
						<option value="Uncle" <?php echo set_select('sec_parent_relationship', 'Uncle'); ?> >Uncle</option>
						<option value="Aunty" <?php echo set_select('sec_parent_relationship', 'Aunty'); ?> >Aunty</option>
						<option value="Other" id="sec_parent_relationship_other_option" <?php echo set_select('sec_parent_relationship', 'Other'); ?> >Other</option>
					</select>
					<div class="form-error"><?php echo form_error('sec_parent_relationship'); ?></div>
				</div>

				<div class="form-group" style="display: none;" id="sec_parent_relationship_other_area">
					<label class="form-control-label">Specify relationship to child</label>
					<br/>
					<input type="text" name="sec_parents_relationship_other" class="form-control" value="<?php echo set_value('sec_parents_relationship_other'); ?>" />
					<div class="form-error"><?php echo form_error('sec_parents_relationship_other'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Mobile</label>
					<br/>
					<input type="text" name="sec_parent_phone" class="form-control numbers-only" value="<?php echo set_value('sec_parent_phone', $y->sec_parent_phone); ?>" />
					<div class="form-error"><?php echo form_error('sec_parent_phone'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Email</label>
					<br/>
					<input type="email" name="sec_parent_email" class="form-control" value="<?php echo set_value('sec_parent_email', $y->sec_parent_email); ?>" />
					<div class="form-error"><?php echo form_error('sec_parent_email'); ?></div>
				</div>
				
				<div class="m-t-10">
					<button class="btn btn-primary btn-lg">Update and Continue</button>
				</div>
			
			</div><!--/.col-->
			
		</div><!--/.row-->

		
	<?php echo form_close(); ?>

		