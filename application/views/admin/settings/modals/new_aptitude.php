	
	<div class="modal fade" id="new_aptitude" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">New Behavioural Aptitude</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
				
					<?php 
					$apt_form_attributes = array("id" => "new_aptitude_form");
					echo form_open('settings/add_new_aptitude_ajax', $apt_form_attributes); ?>
					
						<div class="form-group">
							<label>Aptitude</label>
							<input type="text" class="form-control" name="aptitude" required />
						</div>

						<div class="form-group">
							<label>Domain</label>
							<select class="form-control selectpicker" name="domain" required>
								<option value="">Select Domain</option>
								<option value="Affective">Affective</option>
								<option value="Psychomotor">Psychomotor</option>
							</select>
						</div>
						
						<div id="apt_status_msg"></div>
						
						<div class="m-t-20">
							<button class="btn btn-primary">Submit</button>
						</div>

					<?php echo form_close(); ?>

				</div>
			</div>
		</div>
	</div>