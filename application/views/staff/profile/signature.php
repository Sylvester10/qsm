
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


	<div class="row">
	
		<div class="col-md-7">
		
			Note: This signature will be appended on reports that you produce.
			<?php
			if ($y->signature == NULL) { ?>
				<p class="text-danger">The default signature is still in use. Change to your signature.</p>
			<?php } ?>
			
			<?php echo form_open_multipart('staff/update_signature'); ?>
			
				<div class="form-group">
					<div id="current_image_area" class="m-b-10">
						<?php
						if ($y->signature != NULL) { ?>
							<img id="current_image" src="<?php echo base_url('assets/uploads/signature/staff/'.$y->signature); ?>" />
						<?php } else { ?>
							<img id="current_image" src="<?php echo staff_signature; ?>" />
						<?php } ?>
					</div>
					<div class="file_area m-t-30">
						<small>Only JPG and PNG files allowed (max 64KB).</small>
						<input type="file" name="signature" id="the_image_on_update" class="form-control" accept=".jpg,.png" required />
						<div class="form-error"><?php echo $upload_error['error']; ?></div>
					</div>
				</div>		
				<!-- Image preview-->
				<?php echo generate_image_preview(); ?>

				<button class="btn btn-primary">Update</button>
				
			<?php echo form_close(); ?>

			
		</div>
		
	</div>