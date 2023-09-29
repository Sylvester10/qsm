	<div class="row">
		<div class="col-md-7">
	
			<div class="new-item">
				<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('admin_users'); ?>"><i class="fa fa-users"></i> All Admins</a>
			</div>
			
			<p class="text-bold">Quick Note</p>

			<p>Level 1 (Chief Admin): Can manage all modules in the application. Suitable for school directors/proprietors/proprietresses </p>

			<p>Level 2 (Staff/Surrogate Admin): Can manage all modules except Admins, Procurement Requisition, and Weekly Reports. Suitable for school administrative staff (Principals, Head Teachers) or staff delegated to deputize for the directors/proprietors/proprietresses.</p>

			<p>Newly added admins must set their password <a href="<?php echo base_url('admin_acc/set_password'); ?>" target="_blank" class="text-success text-bold">here</a> before they can access their admin account.</p>
				
			<?php 
			$form_attributes = array("id" => "new_admin_form");
			echo form_open('admin/add_new_admin_ajax', $form_attributes); ?>
			
				<div class="form-group">
					<label>Name</label>
					<input type="text" class="form-control" name="name" required />
				</div>

				<div class="form-group m-t-20">
					<label>Email Address</label>
					<input type="email" class="form-control" name="email" required />
				</div>

				<div class="form-group m-t-20">
					<label>Phone</label>
					<input type="text" class="form-control numbers-only" name="phone" maxlength="15" required />
				</div>

				<div class="form-group m-t-20">
					<label class="form-control-label">Designation</label>
					<select class="form-control" name="designation" required>
						<option value="">Select</option>
						
						<?php 
						$designations = admin_designations();
						foreach ($designations as $designation) { ?>
							<option value="<?php echo $designation; ?>" <?php echo set_select('designation', $designation); ?> ><?php echo $designation; ?></option>
						<?php } ?>

					</select>
					<div class="form-error"><?php echo form_error('designation'); ?></div>
				</div>
					
				<div class="form-group">
					<label>Section(s) Assigned</label>
					<select class="form-control selectpicker" name="section_assigned[]" multiple>
						<?php 
						foreach ($sections as $s) { ?>
							<option value="<?php echo $s->id; ?>"><?php echo $s->section; ?></option>
						<?php } ?>
					</select>
					<div class="form-error"><?php echo form_error('section_assigned'); ?></div>
				</div>

				<div class="form-group m-t-20">
					<label>Level</label>
					<select class="form-control" name="level" required />
						<option value="">Assign Level</option>
						<option value="1">1 (Chief Admin)</option>
						<option value="2">2 (Staff/Surrogate Admin)</option>
					</select>
				</div>
				
				<div id="status_msg"></div>
				
				<div class="m-t-20">
					<button class="btn btn-primary btn-lg">Submit</button>
				</div>

			<?php echo form_close(); ?>		

		</div>
	</div>
			
	