
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('school_library_admin'); ?>"><i class="fa fa-book"></i> All Books</a>
	</div>

	<?php 
	$form_attributes = array("id" => "new_book_form");
	echo form_open('school_library_admin/add_new_book_ajax', $form_attributes); ?>
	
		<div class="row">
		
			<div class="col-md-6 col-sm-12 col-xs-12">

				<p>All fields marked * are required.</p>

				<div class="form-group">
					<label class="form-control-label">Book Title*</label>
					<br/>
					<input type="text" name="book_name" value="<?php echo set_value('book_name'); ?>" class="form-control" required />
					<div class="form-error"><?php echo form_error('book_name'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Author*</label>
					<br/>
					<input type="text" name="author" class="form-control" value="<?php echo set_value('author'); ?>" required />
					<div class="form-error"><?php echo form_error('author'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">ISBN Number</label>
					<br/>
					<input type="text" name="book_no" value="<?php echo set_value('book_no'); ?>" class="form-control" />
					<div class="form-error"><?php echo form_error('book_no'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Edition</label>
					<br/>
					<input type="text" name="edition" value="<?php echo set_value('edition'); ?>" class="form-control" />
					<div class="form-error"><?php echo form_error('edition'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Number of Copies*</label>
					<br/>
					<input type="text" name="total_copies" class="form-control numbers-only" value="<?php echo set_value('total_copies'); ?>" required />
					<div class="form-error"><?php echo form_error('total_copies'); ?></div>
				</div>
				
				<div id="status_msg"></div>
				
				<div class="m-t-10">
					<button class="btn btn-primary btn-lg">Submit</button>
				</div>
			
			</div><!--/.col-->
			
		</div><!--/.row-->

		
	<?php echo form_close(); ?>

		