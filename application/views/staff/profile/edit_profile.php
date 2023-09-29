
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

<?php require("application/views/staff/profile/modals/change_password.php");  ?>

<div class="new-item">
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('staff/profile'); ?>"><i class="fa fa-user"></i> View Profile</a>
	<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#change_password"><i class="fa fa-lock"></i> Change Password</button>
</div>

	<?php 
	echo form_open_multipart('staff/edit_profile_action'); ?>
	
		All fields marked * are required.
	 
		<div class="row">
		
			<div class="col-md-6 col-sm-12 col-xs-12">
		
				<div class="form-group">
					<label>Title</label>
					<select class="form-control" name="title">
						<option selected value="<?php echo $y->title; ?>"><?php echo $y->title; ?></option>
						<option value="Mr." <?php echo set_select('title', 'Mr.'); ?> >Mr.</option>
						<option value="Mrs." <?php echo set_select('title', 'Mrs.'); ?> >Mrs.</option>
						<option value="Miss" <?php echo set_select('title', 'Miss'); ?> >Miss</option>
						<option value="Ms." <?php echo set_select('title', 'Ms.'); ?> >Ms.</option>
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
					<input type="email" class="form-control" value="<?php echo set_value('email', $y->email); ?>" readonly />
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
					<div class="input-group date date_datepicker_no_future" data-date-format="yyyy-mm-dd">
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
				
				
				<div class="column_title_I">
					<h4><b>Staff Passport (Optional)</b></h4>
					<hr>
				</div>
				
				<div class="form-group">
				
					<?php 
					if ($y->passport_photo != NULL) { ?>

						<div id="current_image_area" class="m-b-10">
							<img id="current_image" src="<?php echo base_url('assets/uploads/photos/staff/' .$y->passport_photo); ?>" />
						</div>
						<label class="form-control-label" id="change_image_text">Change Photo?</label> <br />

						<div class="file_area">
							<small>Only JPG files allowed (max 64KB).</small>
							<input type="file" name="passport_photo" id="the_image_on_update" class="form-control" accept=".jpeg,.jpg" value="<?php echo set_value('passport_photo'); ?>" />
							<div class="form-error"><?php echo $upload_error['error']; ?></div>
						</div>	
						
					<?php } else { ?>
					
						<div id="current_image_area" class="m-b-10">
							<img id="current_image" src="<?php echo ($y->sex == 'Male') ? male_staff_avatar : female_staff_avatar; ?>" />
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
				
				<div class="m-t-20">
					<button class="btn btn-primary btn-lg">Update</button>
				</div>
			
			</div><!--/.col-->
			
		</div><!--/.row-->

	
	<?php echo form_close(); ?>