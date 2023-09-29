	
	<div class="modal fade" id="change_password" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-width">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Change Password</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					<?php 
					$form_attributes = array("id" => "change_password_form");
					echo form_open('staff/change_password_ajax', $form_attributes); ?>
						
						<div class="form-group">
							<label> New Password </label>
							<input type="password" class="form-control" name="password" />	
						</div>
						<div class="form group">
							<label> Confirm New Password </label>
							<input type="password" class="form-control" name="c_password" placeholder="Re-enter Password" value="" />
						</div>
						
						<div id="status_msg" class="m-t-10"></div>
						
						<div class="m-t-10">
							<button class="btn btn-primary"> <i class="fa fa-arrow-circle-up"></i> Update </button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>