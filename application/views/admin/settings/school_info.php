
<?php require("application/views/admin/settings/modals/edit_school_info.php");  ?>
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="row">
	
		<div class="col-md-7">
		
			<div class="new-item">
				<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#edit_school_info"><i class="fa fa-pencil"></i> Edit School Info</button>
			</div>

			<p><b>School name:</b> <?php echo $y->school_name; ?></p>
			<p><b>Location:</b> <?php echo $y->school_location; ?></p>
			<p><b>Country:</b> <?php echo $y->country; ?></p>
			<p><b>Official Email:</b> <?php echo $y->official_mail; ?></p>
			<p><b>Official Telephone Line:</b> <?php echo $y->telephone_line; ?></p>
			
			<?php if (school_website == software_website) { ?>

				<p><b>Website:</b> <?php echo $y->school_website; ?></p>

			<?php } else { ?>

				<p><b>Website:</b> <a href="<?php echo school_website; ?>" target="_blank" title="Visit school website"><?php echo $y->school_website; ?></a>

			<?php } ?>
			
			<p><b>School Motto:</b> <em><?php echo $y->school_motto; ?></em></p>
			<p><b>Account Plan:</b> <?php echo school_plan; ?></p>
			
		</div>
		
		<div class="col-md-5">
			<h3>School Logo</h3>
			<p>This logo will be used in creating email and report headers, as well as favicon for the application.</p>
			<?php
			if ($y->school_logo == NULL) { ?>
				<p class="text-danger">The default logo is still in use. Change to your school's logo.</p>
			<?php } ?>
			
			<?php echo form_open_multipart('settings/update_school_logo'); ?>
			
				<div class="form-group">
					<div id="current_image_area" class="m-b-10">
						<img id="current_image" src="<?php echo school_logo; ?>" />
					</div>
					<div class="file_area">
						<small>Only PNG files allowed (max 100KB, dimension should be between 150x150 and 250x250 pixels).</small>
						<input type="file" name="school_logo" id="the_image_on_update" class="form-control" accept=".png" required />
						<div class="form-error"><?php echo $upload_error['error']; ?></div>
					</div>
				</div>	
					
				<!-- Image preview-->
				<?php echo generate_image_preview(); ?>
				
				<div class="m-t-10">
					<button class="btn btn-primary">Update</button>
					<?php if ($y->school_logo != NULL) { ?>
						<a class="btn btn-danger" href="<?php echo base_url('settings/reset_school_logo'); ?>">Reset to Default</a>
					<?php } ?>
				</div>
				
			<?php echo form_close(); ?>
			
		</div>
		
	</div>
	