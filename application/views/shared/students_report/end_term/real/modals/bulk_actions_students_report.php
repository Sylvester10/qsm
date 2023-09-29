
	<?php echo form_open($this->c_controller.'/bulk_actions_reports'); ?>
	
		<div class="row bulk-section">
			<div class="col-md-5">
				With selected: <br/> 
				<select class="form-control bulk-element bulk_action_type" name="bulk_action_type" id="bulk_action_students">
					<option value="" class="no_item">Bulk Action</option>
					<option value="approve">Approve Result</option>
					<option value="reject">Reject Result</option>
					<option value="mark_pending">Mark Pending</option>
					<option value="promote">Resumption Class</option>
				</select>
				<input type="button" class="btn btn-primary btn-sm bulk-element bulk_action_btn" data-toggle="modal" data-target="#bulk_action_confirm" title="Options" value="Apply" disabled />
			</div>
		</div>
		
		
		<!--Show this modal if Resumption Class is selected-->
		<div class="modal fade" id="classes_form" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content modal-form-sm">
					<div class="modal-header">
						<div class="pull-right">
							<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
						</div>
						<h4 class="modal-title"> <i class="fa fa-line-chart"></i> Resumption Class</h4>
					</div><!--/.modal-header-->
					<div class="modal-body">
						<div>Select class in which the selected students will resume into next term. This helps to:
							<ul>
								<li>Indicate class to be promoted to on the report card.</li>
								<li>Retrieve fees payable for next term.</li>
							</ul>
							<p>Ignore if same class.</p>
							<p>Note: Specifiying a resumption class does not automatically promote a student to the specified resumption class. Promotion can only be done in the <a class="underline-link" href="<?php echo base_url('students_admin/single_class/'.$slug); ?>" target="_blank">class page</a>. All promotions should be carried out after report production is fully completed and issued out (ideally at the begining of a new term).</p>
						</div>
						<label>Resumption Class</label>
						<select class="form-control selectpicker" name="resumption_class_id">
							<option selected value="<?php echo $class_id; ?>"><?php echo $class; ?></option>
							<?php echo $this->common_model->classes_option_by_section_group(school_id); ?>
						</select>
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
						<input type="submit" class="btn btn-primary" href="<?php echo base_url($this->c_controller.'/bulk_actions_reports'); ?>" value="Yes, Continue" />
						<button data-dismiss="modal" class="btn btn-default"> No, Cancel </button>
					</div>
				</div>
			</div>
		</div>
	