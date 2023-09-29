
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


	<div class="row">
	
		<div class="col-md-7">
		
			Note: This stamp will be appended on reports that you approve.
			<?php
			if ($y->school_stamp == NULL) { ?>
				<p class="text-danger">The default stamp is still in use. Change to your official school stamp.</p>
			<?php } ?>
			
			<?php echo form_open_multipart('settings/update_school_stamp'); ?>
			
				<div class="form-group">
					<div id="current_image_area" class="m-b-10">
						<?php
						if ($y->school_stamp != NULL) { ?>
							<img id="current_image" src="<?php echo base_url('assets/uploads/stamp/'.$y->school_stamp); ?>" />
						<?php } else { ?>
							<img id="current_image" src="<?php echo school_stamp; ?>" />
						<?php } ?>
					</div>
					<div class="file_area m-t-30">
						<small>Only JPG and PNG files allowed (max 64KB).</small>
						<input type="file" name="stamp" id="the_image_on_update" class="form-control" accept=".jpg,.png" required />
						<div class="form-error"><?php echo $upload_error['error']; ?></div>
					</div>
				</div>		
				
				<!-- Image preview-->
				<?php echo generate_image_preview(); ?>

				<button class="btn btn-primary">Update</button>
				
			<?php echo form_close(); ?>

			
		</div>
		
	</div>