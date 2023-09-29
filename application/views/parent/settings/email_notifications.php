<div class="row">
				
	<div class="col-md-6">
		
		<?php 
		$form_attributes = array("id" => "email_notifications_form");
		echo form_open('school_parent/update_email_notifications_ajax', $form_attributes); ?>
	
			<h3>Configure Email Notifications</h3>

			<div class="form-group">
				<label class="form-control-label">Child's Absence <small>(whether to be notified of child's absence or not)</small></label> <br />
				<label>
					<input type="radio" name="child_absence" value="true" <?php echo set_radio( 'child_absence', 'true', radio_value($y->child_absence, 'true') ); ?> /> Allowed
				</label>
				<label class="m-l-10"><input type="radio" name="child_absence" value="false" <?php echo set_radio( 'child_absence', 'false', radio_value($y->child_absence, 'false') ); ?> /> 
					Not Allowed
				</label>
				<div class="form-error"><?php echo form_error('child_absence'); ?></div>
			</div>

			<div class="form-group">
				<label class="form-control-label">Newsletters <small>(whether to receive school's periodic newsletters or not)</small></label> <br />
				<label>
					<input type="radio" name="newsletters" value="true" <?php echo set_radio( 'newsletters', 'true', radio_value($y->newsletters, 'true') ); ?> /> Allowed
				</label>
				<label class="m-l-10"><input type="radio" name="newsletters" value="false" <?php echo set_radio( 'newsletters', 'false', radio_value($y->newsletters, 'false') ); ?> /> 
					Not Allowed
				</label>
				<div class="form-error"><?php echo form_error('newsletters'); ?></div>
			</div>
			
			<div id="status_msg"></div>
			
			<div class="form-group">
				<button class="btn btn-success btn-lg">Update</button>
			</div>
		
		<?php echo form_close(); ?>
		
	</div>
	
</div><!--/.row-->
