
<?php require("application/views/admin/profile/modals/edit_profile.php");  ?>
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


	<div class="row">
	
		<div class="col-md-7">
		
			<div class="new-item">
				<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#edit_profile"><i class="fa fa-pencil"></i> Edit Profile</button>
			</div>

			<p><b>Name:</b> <?php echo $y->name; ?></p>
			<p><b>Email:</b> <?php echo $y->email; ?></p>
			<p><b>Phone:</b> <?php echo $y->phone; ?></p>
			<p><b>Designation:</b> <?php echo $y->designation; ?></p>
			<p><b>Section(s) Assigned:</b> <?php echo $section_assigned; ?></p>
			<p><b>Account Number:</b> <?php echo $y->acc_number; ?></p>
			<p><b>Bank Name:</b> <?php echo $y->bank_name; ?></p>

		</div>
	
	
		<div class="col-md-5">
			<h3>Profile Photo</h3>
			<?php
			if ($y->photo == NULL) { ?>
				<p class="text-danger">The default profile photo is still in use. Change to your photo.</p>
			<?php } ?>
			
			<?php echo form_open_multipart('admin/update_profile_photo'); ?>
			
				<div class="form-group">
					<div id="current_image_area" class="m-b-10">
						<?php
						if ($y->photo != NULL) { ?>
							<img id="current_image" src="<?php echo base_url('assets/uploads/photos/admins/'.$y->photo_thumb); ?>" />
						<?php } else { ?>
							<img id="current_image" src="<?php echo user_avatar; ?>" />
						<?php } ?>
					</div>
					<div class="file_area">
						<small>Only JPG and PNG files allowed (max 1MB).</small>
						<input type="file" name="profile_photo" id="the_image_on_update" class="form-control" accept=".jpg,.png" required />
						<div class="form-error"><?php echo $upload_error['error']; ?></div>
					</div>
				</div>		
				<!-- Image preview-->
				<?php echo generate_image_preview(); ?>
				
				<div class="m-t-10">
					<button class="btn btn-primary">Update</button>
					<?php if ($y->photo != NULL) { ?>
						<a class="btn btn-danger" href="<?php echo base_url('admin/reset_profile_photo'); ?>">Remove Photo</a>
					<?php } ?>
				</div>
				
			<?php echo form_close(); ?>
			
		</div>
		
	</div>