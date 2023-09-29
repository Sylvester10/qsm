
	<?php echo form_open('students_admin/bulk_actions_students'); ?>
	
		<div class="row bulk-section">
			<div class="col-md-5">
				With selected: <br/> 
				<select class="form-control bulk-element bulk_action_type" name="bulk_action_type" id="bulk_action_students">
					<option value="" class="no_item">Bulk Action</option>
					<option value="promote">Promote</option>
					<option value="graduate">Graduate</option>
					<option value="delete">Delete</option>
				</select>
				<input type="button" class="btn btn-primary btn-sm bulk-element bulk_action_btn" data-toggle="modal" data-target="#bulk_action_confirm" title="Options" value="Apply" disabled />
			</div>
		</div>
		
		
		<!--Show this modal if Promote is selected-->
		<div class="modal fade" id="classes_form" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-form-sm">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title"> <i class="fa fa-send"></i> Promote Students</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						<label class="form-control-label">New Class</label>
						<select class="form-control selectpicker" name="class_id">
							<option value="">Select Class</option>
							<?php echo $this->common_model->classes_option_by_section_group(school_id); ?>
						</select>
						<div class="form-group">
							<label class="form-control-label">Date</label>
							<div class="input-group date datepicker_regular">
								<input type="text" class="form-control" name="last_promoted" value="<?php echo set_value('last_promoted', date_today()); ?>" readonly required />
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
							</div>
							<div class="form-error"><?php echo form_error('last_promoted'); ?></div>
						</div>
					</div>
					<div class="modal-footer">
						<button data-dismiss="modal" class="btn btn-primary">OK</button>
					</div>
				</div>
			</div>
		</div>
		
		
		<div class="modal fade" id="bulk_action_confirm" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title"> <i class="fa fa-database"></i> Bulk Actions</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						Note that all selected records will be affected. Are you sure you want to continue? 
					</div>
					<div class="modal-footer">
						<input type="submit" class="btn btn-primary" href="<?php echo base_url('students_admin/bulk_actions_students'); ?>" value="Yes, Continue" />
						<button data-dismiss="modal" class="btn btn-default"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>
	