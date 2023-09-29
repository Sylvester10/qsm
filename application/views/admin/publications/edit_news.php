
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
		
			<div class="new-item">
				<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('publications_admin/single_news/'.$y->id.'/'.$y->slug); ?>"><i class="fa fa-eye"></i> View News</a>
				<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('publications_admin/create_news'); ?>"><i class="fa fa-plus"></i> Create News</a>
				<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('publications_admin/news'); ?>"><i class="fa fa-book"></i> News List</a>
			</div>
		
			<?php echo form_open_multipart('publications_admin/edit_news_action/' . $y->id); ?>
			
				<div class="row">
				
					<div class="col-md-7 col-sm-12 col-xs-12 p-b-20">
					
						<div class="form-group">
							<label class="form-control-label">Title</label>
							<input type="text" name="title" value="<?php echo set_value('title', $y->title); ?>" class="form-control" maxlength="120" required />
							<div class="form-error"><?php echo form_error('title'); ?></div>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Body</label>
							<textarea name="body" class="form-control t300 textarea"><?php echo set_value('body', $y->body); ?></textarea>
							<div class="form-error"><?php echo form_error('body'); ?></div>
						</div>
						
					</div><!--/.col-md-6-->
					
					<div class="col-md-4 col-md-offset-1 col-sm-12 col-xs-12">
						
						<div class="form-group">
							<label class="form-control-label">Featured Image </label><br />
							
							<div id="current_image_area" class="m-b-10">
								<img id="current_image" src="<?php echo base_url('assets/uploads/news/' .$y->featured_image); ?>" />
							</div>
							<label class="form-control-label" id="change_image_text">Change image?</label> <br />
							
							<div class="file_area">
								<small>Only JPG and PNG files allowed (max 2MB).</small>
								<input type="file" name="featured_image" id="the_image_on_update" class="form-control" accept=".jpeg,.jpg,.png" />
								<div class="form-error upload_error"><?php echo $upload_error['error']; ?></div>
							</div>
						</div>
						
						<!-- Image preview-->
						<?php echo generate_image_preview(); ?>
						
						<div class="form-group">       
							<button type="submit" class="btn btn-primary m-t-5">Update News</button>
						</div>
						
					</div><!--/.col-md-6-->
					
				</div><!--/.row-->
				
			<?php echo form_close(); ?>	
			
		</div>
	</div>