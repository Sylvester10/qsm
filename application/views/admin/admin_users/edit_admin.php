	<div class="row">
		<div class="col-md-7">
	
			<div class="new-item">
				<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('admin_users'); ?>"><i class="fa fa-users"></i> All Admins</a>
			</div>
			
			<p class="text-bold">Quick Note</p>
			<p>Level 1 (Chief Admin): Can manage all modules in the application. Suitable for school directors/proprietors/proprietresses </p>
			<p>Level 2 (Staff/Surrogate Admin): Can manage all modules except Admins, Procurement Requisition, and Weekly Reports. Suitable for school administrative staff (Principals, Head Teachers) or staff delegated to deputize for the directors/proprietors/proprietresses.</p>
		
			<?php 
			$form_attributes = array("id" => "edit_admin_form");
			echo form_open('admin_users/edit_admin_ajax/'.$y->id, $form_attributes); ?>
			
				<input type="hidden" id="admin_id" value="<?php echo $y->id; ?>" />
				<input type="hidden" id="admin_name" value="<?php echo $y->name; ?>" />
			
				<div class="form-group">
					<label>Name</label>
					<input type="text" class="form-control" name="name" value="<?php echo $y->name; ?>" required />
				</div>

				<div class="form-group m-t-20">
					<label>Email Address</label>
					<input type="email" class="form-control" value="<?php echo $y->email; ?>" readonly />
				</div>

				<div class="form-group m-t-20">
					<label>Phone</label>
					<input type="text" class="form-control numbers-only" name="phone" value="<?php echo $y->phone; ?>" maxlength="15" required />
				</div>

				<div class="form-group">
					<label class="form-control-label">Designation</label>
					<select class="form-control" name="designation" required >
						
						<?php 
						$designations = admin_designations();
						foreach ($designations as $designation) { 
							$selected = ($designation == $y->designation) ? 'selected' : NULL; ?>
							<option <?php echo $selected; ?> value="<?php echo $designation; ?>" <?php echo set_select('designation', $designation); ?> ><?php echo $designation; ?></option>
						<?php } ?>

					</select>
					<div class="form-error"><?php echo form_error('designation'); ?></div>
				</div>

				<div class="form-group">
					<label>Section(s) Assigned: <?php echo $section_assigned; ?></label>
					<select class="form-control selectpicker" name="section_assigned[]" multiple>
						<?php 
						foreach ($sections as $s) { ?>
							<option value="<?php echo $s->id; ?>"><?php echo $s->section; ?></option>
						<?php } ?>
					</select>
					<div class="form-error"><?php echo form_error('section_assigned'); ?></div>
				</div>

				<?php 
				//check if this admin is the chief administrator for the school (i.e. admin who registered school account)
				//ensure their level remains 1
				if (chief_admin_id == $y->id) { ?> 

					<div class="form-group m-t-20">
						<label>Level</label>
						<select class="form-control" name="level" required />
							<option value="1">1 (Chief Admin)</option>
						</select>
					</div>

				<?php } else { ?>

					<div class="form-group m-t-20">
						<label>Level</label>
						<select class="form-control" name="level" required />
							<option selected value="<?php echo $y->level; ?>"><?php echo $admin_level; ?></option>
							<option value="1">1 (Chief Admin)</option>
							<option value="2">2 (Staff/Surrogate Admin)</option>
						</select>
					</div>

				<?php } ?>
				
				<div id="status_msg"></div>
				
				<div class="m-t-20">
					<button class="btn btn-primary btn-lg">Update</button>
				</div>

			<?php echo form_close(); ?>
			
		</div>
	</div>
			
	