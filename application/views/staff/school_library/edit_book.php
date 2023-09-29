
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('school_library_staff'); ?>"><i class="fa fa-book"></i> All Books</a>
	</div>

	<?php 
	$form_attributes = array("id" => "edit_book_form");
	echo form_open('school_library_staff/edit_book_ajax/'.$y->id, $form_attributes); ?>
	
		<div class="row">
		
			<div class="col-md-6 col-sm-12 col-xs-12">

				<p>All fields marked * are required.</p>

				<input type="hidden" id="book_id" value="<?php echo $y->id; ?>" />
				
				<div class="form-group">
					<label class="form-control-label">Book Title*</label>
					<br/>
					<input type="text" name="book_name" value="<?php echo set_value('book_name', $y->book_name); ?>" class="form-control" required />
				</div>

				<div class="form-group">
					<label class="form-control-label">Author*</label>
					<br/>
					<input type="text" name="author" value="<?php echo set_value('author', $y->author); ?>" class="form-control" required />
					<div class="form-error"><?php echo form_error('author'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">ISBN Number</label>
					<br/>
					<input type="text" name="book_no" value="<?php echo set_value('book_no', $y->book_no); ?>" class="form-control" />
					<div class="form-error"><?php echo form_error('book_no'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Edition</label>
					<br/>
					<input type="text" name="edition" value="<?php echo set_value('edition', $y->edition); ?>" class="form-control" />
					<div class="form-error"><?php echo form_error('edition'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">
						<small>Total copies in stock: <?php echo $y->total_copies; ?></small> <br />
						Extra Copies (leave blank if no extra copies)
					</label>
					<br/>
					<input type="text" name="extra_copies" class="form-control numbers-only" value="<?php echo set_value('extra_copies'); ?>" />
					<div class="form-error"><?php echo form_error('extra_copies'); ?></div>
				</div>
				
				
				<div id="status_msg"></div>
				
				<div class="m-t-10">
					<button class="btn btn-primary btn-lg">Update</button>
				</div>
			
			</div><!--/.col-->
			
		</div><!--/.row-->

		
	<?php echo form_close(); ?>

		