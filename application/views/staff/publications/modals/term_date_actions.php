	
	<div class="modal fade" id="create_term_date" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Create Term Date</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					<?php 
					$form_attributes = array("id" => "create_term_date_form");
					echo form_open('publications_staff/create_term_date_ajax', $form_attributes); ?>
					
						<div class="form-group">
							<label class="form-control-label">Activity</label>
							<input type="text" name="activity" value="<?php echo set_value('activity'); ?>" class="form-control" required />
						</div>
					
						<div class="form-group">
							<label class="form-control-label">Date (Format: mm/dd/yyyy)</label>
							<input type="date" class="form-control" name="term_date" value="<?php echo set_value('term_date'); ?>" required />
						</div>
						
						<div class="form-group">
							<label class="form-control-label">End Date (Leave blank if not required. Format: mm/dd/yyyy)</label>
							<input type="date" class="form-control" name="end_date" value="<?php echo set_value('end_date'); ?>" />
						</div>
						
						<div id="status_msg"></div>
						
						<div>
							<button class="btn btn-primary"> <i class="fa fa-arrow-circle-up"></i> Submit </button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
	
	
	<!--Term Date Clear Confirm-->
	<div class="modal fade" id="clear_term_dates" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Clear Term Dates</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					<h4>Note: This action is meant for deleting old term dates in order to create term dates for a new term.</h4>
					Are you sure you want to clear all term dates? This will delete all the records. <br />
				</div>
				<div class="modal-footer">
					<a class="btn btn-sm btn-danger" role="button" href="<?php echo base_url('publications_staff/clear_term_dates'); ?>"> Yes, Clear All </a>
					<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
				</div>
			</div>
		</div>
	</div>