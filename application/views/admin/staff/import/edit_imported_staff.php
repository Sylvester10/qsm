
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>



	<?php 
	echo form_open_multipart('staff_import/edit_staff_action/'.$y->id); ?>

		All fields marked * are required.
	
		<div class="row">
		
			<div class="col-md-6 col-sm-12 col-xs-12">
		
				<div class="form-group">
					<label>Title</label>
					<select class="form-control" name="title" >
						<option selected value="<?php echo $y->title; ?>"><?php echo $y->title; ?></option>
						<option value="Mr." <?php echo set_select('title', 'Mr.'); ?> >Mr.</option>
						<option value="Mrs." <?php echo set_select('title', 'Mrs.'); ?> >Mrs.</option>
						<option value="Miss" <?php echo set_select('title', 'Miss'); ?> >Miss</option>
						<option value="Dr." <?php echo set_select('title', 'Dr.'); ?> >Dr.</option>
					</select>
					<div class="form-error"><?php echo form_error('title'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Name* <small>(Surname first)</small></label>
					<br/>
					<input type="text" name="name" value="<?php echo set_value('name', $y->name); ?>" class="form-control" required />
					<div class="form-error"><?php echo form_error('name'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Email*</label>
					<br/>
					<input type="email" name="email" class="form-control" value="<?php echo set_value('email', $y->email); ?>" required />
				</div>

				<div class="form-group">
					<label class="form-control-label">Mobile No*</label>
					<br/>
					<input type="text" name="phone" value="<?php echo set_value('phone', $y->phone); ?>" class="form-control" required />
					<div class="form-error"><?php echo form_error('phone'); ?></div>
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
					<label class="form-control-label">Date of Birth*</label>
					<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control" name="date_of_birth" value="<?php echo set_value('date_of_birth', $y->date_of_birth); ?>" readonly required />
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<div class="form-error"><?php echo form_error('date_of_birth'); ?></div>
					</div>
				</div>
				
				<div class="form-group" >
					<label class="form-control-label m-r-20 ">Sex*</label>
					<label class="m-r-10"><input type="radio" name="sex" value="Male" <?php echo set_radio( 'sex', 'Male', radio_value($y->sex, 'Male') ); ?> > Male</label>
					<label><input type="radio" name="sex" value="Female" <?php echo set_radio( 'sex', 'Female', radio_value($y->sex, 'Female') ); ?> > Female</label>
					<div class="form-error"><?php echo form_error('sex'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Home Address</label>
					<textarea class="form-control" name="home_address"><?php echo set_value('home_address', strip_tags($y->home_address)); ?></textarea>
					<div class="form-error"><?php echo form_error('home_address'); ?></div>
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
			
			</div><!--/.col-->
			

			<div class="col-md-6 col-sm-12 col-xs-12">
			
				<div class="form-group">
					<label class="form-control-label">Designation*</label>
					<select class="form-control" name="designation" required >
						<option selected value="<?php echo $y->designation; ?>"><?php echo $y->designation; ?></option>
						<option value="Head of School" <?php echo set_select('designation', 'Head of School'); ?> >Head of School</option>
						<option value="School Manager" <?php echo set_select('designation', 'School Manager'); ?> >School Manager</option>
						<option value="Principal" <?php echo set_select('designation', 'Principal'); ?> >Principal</option>
						<option value="Acting Principal" <?php echo set_select('designation', 'Acting Principal'); ?> >Acting Principal</option>
						<option value="Vice Principal" <?php echo set_select('designation', 'Vice Principal'); ?> >Vice Principal</option>
						<option value="HR Personnel" <?php echo set_select('designation', 'HR Personnel'); ?> >HR Personnel</option>
						<option value="Admin Personnel" <?php echo set_select('designation', 'Admin Personnel'); ?> >Admin Personnel</option>
						<option value="Guidance Counselor" <?php echo set_select('designation', 'Guidance Counselor'); ?> >Guidance Counselor</option>
						<option value="SEN Coordinator" <?php echo set_select('designation', 'SEN Coordinator'); ?> >SEN Coordinator</option>
						<option value="Head Teacher" <?php echo set_select('designation', 'Head Teacher'); ?> >Head Teacher</option>
						<option value="Acting Head Teacher" <?php echo set_select('designation', 'Acting Head Teacher'); ?> >Acting Head Teacher</option>
						<option value="Assistant Head Teacher" <?php echo set_select('designation', 'Assistant Head Teacher'); ?> >Assistant Head Teacher</option>
						<option value="Accountant" <?php echo set_select('designation', 'Accountant'); ?> >Accountant</option>
						<option value="Bursar" <?php echo set_select('designation', 'Bursar'); ?> >Bursar</option>
						<option value="Class Teacher" <?php echo set_select('designation', 'Class Teacher'); ?> >Class Teacher</option>
						<option value="Class Assistant" <?php echo set_select('designation', 'Class Assistant'); ?> >Class Assistant</option>
						<option value="ICT Personnel" <?php echo set_select('designation', 'ICT Personnel'); ?> >ICT Personnel</option>
						<option value="Librarian" <?php echo set_select('designation', 'Librarian'); ?> >Librarian</option>
						<option value="Sports Coordinator" <?php echo set_select('designation', 'Sports Coordinator'); ?> >Sports Coordinator</option>
						<option value="Cashier" <?php echo set_select('designation', 'Cashier'); ?> >Cashier</option>
						<option value="Store Manager" <?php echo set_select('designation', 'Store Manager'); ?> >Store Manager</option>
						<option value="Janitor" <?php echo set_select('designation', 'Janitor'); ?> >Janitor</option>
						<option value="Cook" <?php echo set_select('designation', 'Cook'); ?> >Cook</option>
						<option value="Security Personnel" <?php echo set_select('designation', 'Security Personnel'); ?> >Security Personnel</option>
						<option value="Other" <?php echo set_select('designation', 'Other'); ?> >Other</option>
					</select>
					<div class="form-error"><?php echo form_error('designation'); ?></div>
				</div>
		
				<div class="form-group">
					<label class="form-control-label">Highest Educational Qualification</label>
					<select class="form-control" name="qualification">
						<option selected value="<?php echo $y->qualification; ?>"><?php echo $y->qualification; ?></option>
						<option value="FSLC" <?php echo set_select('qualification', 'FSLC'); ?> >FSLC</option>
						<option value="SSCE" <?php echo set_select('qualification', 'SSCE'); ?> >SSCE</option>
						<option value="NCE" <?php echo set_select('qualification', 'NCE'); ?> >NCE</option>
						<option value="OND" <?php echo set_select('qualification', 'OND'); ?> >OND</option>
						<option value="HND" <?php echo set_select('qualification', 'HND'); ?> >HND</option>
						<option value="Degree" <?php echo set_select('qualification', 'Degree'); ?> >Degree</option>
						<option value="Masters" <?php echo set_select('qualification', 'Masters'); ?> >Masters</option>
						<option value="PhD" <?php echo set_select('qualification', 'PhD'); ?> >PhD</option>
						<option value="None" <?php echo set_select('qualification', 'None'); ?> >None</option>
					</select>
					<div class="form-error"><?php echo form_error('qualification'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Date of Employment</label>
					<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control" name="employment_date" value="<?php echo set_value('employment_date', $y->employment_date); ?>" readonly />
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<div class="form-error"><?php echo form_error('employment_date'); ?></div>
					</div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Name of Next of Kin</label>
					<br/>
					<input type="text" name="name_of_kin" class="form-control" value="<?php echo set_value('name_of_kin', $y->name_of_kin); ?>" />
					<div class="form-error"><?php echo form_error('name_of_kin'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Email of Next of Kin </label>
					<br/>
					<input type="email" name="email_of_kin" class="form-control" value="<?php echo set_value('email_of_kin', $y->email_of_kin); ?>" />
					<div class="form-error"><?php echo form_error('email_of_kin'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Mobile of Next of Kin </label>
					<br/>
					<input type="text" name="mobile_of_kin" class="form-control numbers-only" value="<?php echo set_value('mobile_of_kin', $y->mobile_of_kin); ?>" />
					<div class="form-error"><?php echo form_error('mobile_of_kin'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Address of Next of Kin</label>
					<textarea class="form-control" name="address_of_kin"><?php echo set_value('address_of_kin', strip_tags($y->address_of_kin)); ?></textarea>
					<div class="form-error"><?php echo form_error('address_of_kin'); ?></div>
				</div>	
				
				<div class="form-group">
					<label class="form-control-label">Account Number</label>
					<br/>
					<input type="text" name="acc_number" value="<?php echo set_value('acc_number', $y->acc_number); ?>" class="form-control numbers-only" />
					<div class="form-error"><?php echo form_error('acc_number'); ?></div>
				</div>
				
				<?php 
				//if installed country is Nigeria, show list of Nigerian banks in dropdown, else show textbox
				if ( s_country == 'Nigeria') { ?>
				
					<div class="form-group">
						<label class="form-control-label">Bank Name</label>
						<select class="form-control" name="bank_name">
							<option selected value="<?php echo $y->bank_name; ?>"><?php echo $y->bank_name; ?></option>	
							<?php 
							$banks = nigerian_banks();
							sort($banks); //sort in ascending order
							foreach ($banks as $index => $bank_name) { ?>
								<option value="<?php echo $bank_name; ?>" <?php echo set_select('bank_name', $bank_name); ?> ><?php echo $bank_name; ?></option>
							<?php } ?>
						</select>
						<div class="form-error"><?php echo form_error('bank_name'); ?></div>
					</div>
					
				<?php } else { ?>
				
					<div class="form-group">
						<label class="form-control-label">Bank Name</label>
						<input type="text" name="bank_name" value="<?php echo set_value('bank_name', $y->bank_name); ?>" class="form-control" />
						<div class="form-error"><?php echo form_error('bank_name'); ?></div>
					</div>
								
				<?php } ?> 
				
				<div class="form-group">
					<label class="form-control-label">Additional Information</label>
					<textarea class="form-control t100" name="additional_info"><?php echo set_value('additional_info', strip_tags($y->additional_info)); ?></textarea>
					<div class="form-error"><?php echo form_error('additional_info'); ?></div>
				</div>

				
				<div class="m-t-20">
					<button class="btn btn-primary btn-lg">Update and Continue</button>
				</div>
			
			</div><!--/.col-->
			
		</div><!--/.row-->

	
	<?php echo form_close(); ?>