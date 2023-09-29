
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
	<?php 
	echo form_open_multipart('school_staff/staff_registration_action'); ?>
	
	<div class="row">
		<div class="col-md-6 col-sm-12 col-xs-12">
			<p>Note: Newly added staff must set their password <a href="<?php echo base_url('staff_acc/set_password'); ?>" target="_blank" class="text-success text-bold">here</a> before they can access their staff account.</p>
		</div>
	</div>
	
		All fields marked * are required.
	
		<div class="row">
		
			<div class="col-md-6 col-sm-12 col-xs-12">
		
				<div class="form-group">
					<label>Title</label>
					<select class="form-control" name="title">
						<option value="">-Select-</option>

						<?php 
						$titles = staff_titles();
						foreach ($titles as $title) { ?>
							<option value="<?php echo $title; ?>" <?php echo set_select('title', $title); ?> ><?php echo $title; ?></option>
						<?php } ?>

					</select>
					<div class="form-error"><?php echo form_error('title'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Name* <small>(Surname first)</small></label>
					<br/>
					<input type="text" name="name" value="<?php echo set_value('name'); ?>" class="form-control" required />
					<div class="form-error"><?php echo form_error('name'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Email*</label>
					<br/>
					<input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" required />
					<div class="form-error"><?php echo form_error('email'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Mobile No*</label>
					<br/>
					<input type="text" name="phone" value="<?php echo set_value('phone'); ?>" class="form-control numbers-only" required />
					<div class="form-error"><?php echo form_error('phone'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Nationality*</label>
					<br/>
					<select class="form-control" name="nationality" id="nationality" required>
						<option value="<?php echo s_country; ?>" <?php echo set_select('nationality', s_country); ?> ><?php echo s_country; ?></option>

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
								<option value="">Select</option>

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
								<!--Dependent based selection-->
							</select>
						</div>
					</div>
					
				<?php } ?>
				
				<div class="form-group">
					<label class="form-control-label">Date of Birth*</label>
					<div class="input-group date date_datepicker_no_future" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control" name="date_of_birth" value="<?php echo set_value('date_of_birth'); ?>" readonly required />
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<div class="form-error"><?php echo form_error('date_of_birth'); ?></div>
					</div>
				</div>
				
				<div class="form-group" >
					<label class="form-control-label m-r-20 ">Sex*</label>
					<label class="m-r-10" ><input type="radio" name="sex" value="Male" <?php echo set_radio('sex', 'Male'); ?> > Male</label>
					<label><input type="radio" name="sex" value="Female" <?php echo set_radio('sex', 'Female'); ?> > Female</label>
					<div class="form-error"><?php echo form_error('sex'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Home Address</label>
					<textarea class="form-control t100" name="home_address"><?php echo set_value('home_address'); ?></textarea>
					<div class="form-error"><?php echo form_error('home_address'); ?></div>
				</div>	

				<div class="form-group">
					<label class="form-control-label">Religion</label>
					<select class="form-control" name="religion">
						<option value="">Select</option>
						
						<?php 
						$religions = world_religions();
						foreach ($religions as $religion) { ?>
							<option value="<?php echo $religion; ?>"><?php echo $religion; ?></option>
						<?php } ?>

					</select>
					<div class="form-error"><?php echo form_error('religion'); ?></div>
				</div>
			
			</div><!--/.col-->

			<div class="col-md-6 col-sm-12 col-xs-12">

				<div class="form-group">
					<label class="form-control-label">Designation*</label>
					<select class="form-control" name="designation" required>
						<option value="">Select</option>
						
						<?php 
						$designations = staff_designations();
						foreach ($designations as $designation) { ?>
							<option value="<?php echo $designation; ?>" <?php echo set_select('designation', $designation); ?> ><?php echo $designation; ?></option>
						<?php } ?>

					</select>
					<div class="form-error"><?php echo form_error('designation'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Highest Educational Qualification</label>
					<select class="form-control" name="qualification">
						<option value="">Select</option>
						
						<?php 
						$qualifications = staff_qualifications();
						foreach ($qualifications as $qualification) { ?>
							<option value="<?php echo $qualification; ?>" <?php echo set_select('qualification', $qualification); ?> ><?php echo $qualification; ?></option>
						<?php } ?>

					</select>
					<div class="form-error"><?php echo form_error('qualification'); ?></div>
				</div>
					
				<div class="form-group">
					<label class="form-control-label">Date of Employment</label>
					<div class="input-group date date_datepicker_no_future" data-date-format="yyyy-mm-dd">
						<input type="text" class="form-control" name="employment_date" value="<?php echo set_value('employment_date'); ?>" readonly />
						<div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						</div>
						<div class="form-error"><?php echo form_error('employment_date'); ?></div>
					</div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Name of Next of Kin</label>
					<br/>
					<input type="text" name="name_of_kin" class="form-control" value="<?php echo set_value('name_of_kin'); ?>" />
					<div class="form-error"><?php echo form_error('name_of_kin'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Email of Next of Kin </label>
					<br/>
					<input type="email" name="email_of_kin" class="form-control" value="<?php echo set_value('email_of_kin'); ?>" />
					<div class="form-error"><?php echo form_error('email_of_kin'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Mobile of Next of Kin </label>
					<br/>
					<input type="text" name="mobile_of_kin" class="form-control numbers-only" value="<?php echo set_value('mobile_of_kin'); ?>" />
					<div class="form-error"><?php echo form_error('mobile_of_kin'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Address of Next of Kin</label>
					<textarea class="form-control" name="address_of_kin"><?php echo set_value('address_of_kin'); ?></textarea>
					<div class="form-error"><?php echo form_error('address_of_kin'); ?></div>
				</div>	
				
				<div class="form-group">
					<label class="form-control-label">Account Number</label>
					<br/>
					<input type="text" name="acc_number" value="<?php echo set_value('acc_number'); ?>" class="form-control numbers-only" />
					<div class="form-error"><?php echo form_error('acc_number'); ?></div>
				</div>
				
				<?php 
					//if installed country is Nigeria, show list of Nigerian banks in dropdown, else show textbox
					if ( s_country == 'Nigeria') { ?>
					
						<div class="form-group">
							<label class="form-control-label">Bank Name</label>
							<select class="form-control" name="bank_name">
								<option value="">-Select Bank-</option>	
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
							<input type="text" name="bank_name" value="<?php echo set_value('bank_name'); ?>" class="form-control" />
							<div class="form-error"><?php echo form_error('bank_name'); ?></div>
						</div>
								
				<?php } ?> 
				
				<div class="form-group">
					<label class="form-control-label">Additional Information</label>
					<textarea class="form-control t100" name="additional_info"><?php echo set_value('additional_info'); ?></textarea>
					<div class="form-error"><?php echo form_error('additional_info'); ?></div>
				</div>

				
				<div class="column_title_I">
					<h4><b>Staff Passport (Optional)</b></h4>
					<hr>
				</div>

				<div class="form-group">
					<small>Only JPG files allowed (max 64KB).</small>
					<input type="file" name="passport_photo" id="the_image" class="form-control" accept=".jpeg,.jpg" />
					<div class="form-error"><?php echo $error; ?></div>
				</div>
							
				<!-- Image preview-->
				<?php echo generate_image_preview(); ?>

				<div class="m-t-20">
					<button class="btn btn-primary btn-lg">Submit</button>
				</div>
			
			</div><!--/.col-->
			
		</div><!--/.row-->

	
	<?php echo form_close(); ?>