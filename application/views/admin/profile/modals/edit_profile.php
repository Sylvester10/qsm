	
	<div class="modal fade" id="edit_profile" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Edit Profile</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					<?php 
					$form_attributes = array("id" => "edit_profile_form");
					echo form_open('admin/edit_profile_ajax', $form_attributes); ?>
					
						<div class="form-group">
							<label>Name</label>
							<input type="text" class="form-control" name="name" value="<?php echo $y->name; ?>" required />
						</div>
						<div class="form-group">
							<label>Phone Number</label>
							<input type="text" class="form-control numbers-only" name="phone" value="<?php echo $y->phone; ?>" required />
						</div>
						<div class="form-group">
							<label>Account Number</label>
							<input type="text" class="form-control numbers-only" name="acc_number" value="<?php echo $y->acc_number; ?>" maxlength="10" />
						</div>
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
						
						<div class="form-group m-t-20">
							<input type="checkbox" name="change_password" id="change_password" /> Change password?
						</div>
						
						<div id="password_area" style="display: none" class="m-b-20">
							<div class="form-group">
								<label> New Password </label>
								<input type="password" class="form-control" name="password" id="password" value="" />	
							</div>
							<div class="form group">
								<label> Confirm New Password </label>
								<input type="password" class="form-control" name="c_password" id="c_password" placeholder="Re-enter Password" value="" />		
							</div>
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