	
	<div class="modal fade" id="new_grade_key" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">New Grade Key</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
				
					<?php 
					$grk_form_attributes = array("id" => "new_grade_key_form");
					echo form_open('settings/add_new_grade_key_ajax', $grk_form_attributes); ?>
					
						<div class="form-group">
							<label>Grade Key</label>
							<input type="text" class="form-control" name="grade_key" required />
						</div>
						
						<div id="grk_status_msg"></div>
						
						<div class="m-t-20">
							<button class="btn btn-primary">Submit </button>
						</div>

					<?php echo form_close(); ?>
					
				</div>
			</div>
		</div>
	</div>