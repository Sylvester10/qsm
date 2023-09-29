
<div class="row">
	<div class="col-md-6">

		<h3>Override Password</h3>
		<p>Note: With the Override Password, you can access any school account as admin, staff, student or parent.</p>

		<?php 
		$form_attributes = array("id" => "override_password_form");
		echo form_open('super_admin/update_override_password_ajax', $form_attributes); ?>

			<div class="form-group">
				<label>Your Super Admin Password</label>
				<input type="password" class="form-control" name="super_admin_password" required />
			</div>

			<div class="form-group">
				<label>Override Password</label>
				<input type="password" class="form-control" name="password" required />
			</div>

			<div class="form-group">
				<label>Confirm Override Password</label>
				<input type="password" class="form-control" name="c_password" required />
			</div>

			<div class="form-group">
				<label class="form-control-label">Allow Override Login <small>(whether to allow login into client account using override password or not)</small></label> <br />
				<label>
					<input type="radio" name="override_login" value="true" <?php echo set_radio( 'override_login', 'true', radio_value($y->override_login, 'true') ); ?> /> Enabled
				</label>
				<label class="m-l-10"><input type="radio" name="override_login" value="false" <?php echo set_radio( 'override_login', 'false', radio_value($y->override_login, 'false') ); ?> /> 
					Disabled
				</label>
			</div>

			<div id="status_msg"></div>

			<div class="m-t-20">
				<button class="btn btn-primary btn-lg">Update</button>
			</div>

		<?php echo form_close(); ?>

	</div>
</div>
