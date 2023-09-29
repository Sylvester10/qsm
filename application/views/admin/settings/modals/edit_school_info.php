	
	<div class="modal fade" id="edit_school_info" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Edit School Info</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					
					<?php 
					$form_attributes = array("id" => "edit_school_form");
					echo form_open('settings/edit_school_info_ajax', $form_attributes); ?>
					
						<div class="form-group">
							<label>School Name</label>
							<input type="text" class="form-control" name="school_name" value="<?php echo $y->school_name; ?>" required />
						</div>
						<div class="form-group m-t-20">
							<label>School Location</label>
							<input type="text" class="form-control" name="school_location" value="<?php echo $y->school_location; ?>" required />
						</div>
						<div class="form-group m-t-20">
							<label>Official Email Address (School's)</label>
							<input type="email" class="form-control" name="official_mail" value="<?php echo $y->official_mail; ?>" required />
						</div>
						<div class="form-group m-t-20">
							<label>Official Telephone Line (School's)</label>
							<input type="text" class="form-control phone-num" name="telephone_line" value="<?php echo $y->telephone_line; ?>" required />
						</div>
						<div class="form-group m-t-20">
							<label>School Website (if any)</label>
							<input type="text" class="form-control" name="school_website" value="<?php echo $y->school_website; ?>" placeholder="http://example.com" />
						</div>
						<div class="form-group m-t-20">
							<label>School Motto</label>
							<input type="text" class="form-control" name="school_motto" value="<?php echo $y->school_motto; ?>" required />
						</div>
						
						<div id="status_msg"></div>
						
						<div>
							<button class="btn btn-primary"> <i class="fa fa-arrow-circle-up"></i> Update </button>
						</div>

					<?php echo form_close(); ?>
					
				</div>
			</div>
		</div>
	</div>