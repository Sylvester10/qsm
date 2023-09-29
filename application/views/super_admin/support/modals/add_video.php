<?php echo flash_message_danger('status_msg_error'); ?>

<div class="modal fade" id="new_video" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">New Video</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">

					<?php 
					$form_attributes = array("id" => "new_support_video_form");
					echo form_open('support/add_new_video_ajax', $form_attributes); ?>
					
						<div class="form-group">
							<label class="form-control-label">Title</label>
							<input type="text" name="title" class="form-control" required />
						</div>

						<div class="form-group">
							<label class="form-control-label">Description</label>
							<textarea name="description" class="form-control t100" maxlength="100" required></textarea>
						</div>

						<div class="form-group">
							<label class="form-control-label">Video URL</label>
							<input type="text" name="url" class="form-control" required />
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Video Category</label>
							<select class="form-control" name="category">
								<option value="">-Select-</option>
								<option value="Admin" <?php echo set_select('category', 'Admin'); ?> >Admin</option>
								<option value="Staff" <?php echo set_select('category', 'Staff'); ?> >Staff</option>
								<option value="Student" <?php echo set_select('category', 'Student'); ?> >Student</option>
								<option value="Parent" <?php echo set_select('category', 'Parent'); ?> >Parent</option>
							</select>
							<div class="form-error"><?php echo form_error('category'); ?></div>
						</div>

						<div id="status_msg"></div>
						
						<div>
							<button class="btn btn-primary">Submit </button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>