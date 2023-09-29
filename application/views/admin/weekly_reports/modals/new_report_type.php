
<div class="modal fade" id="new_report_type" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content modal-form-sm">
			<div class="modal-header">
				<div class="pull-right">
					<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
				</div>
				<h4 class="modal-title">New Report Type</h4>
			</div><!--/.modal-header-->
			<div class="modal-body">

				<?php 
				$form_attributes = array("id" => "new_report_type_form");
				echo form_open('weekly_reports_admin/add_new_report_type_ajax', $form_attributes); ?>
				
					<div class="form-group">
						<label class="form-control-label">Report Type</label>
						<input type="text" name="type" value="<?php echo set_value('type'); ?>" class="form-control" required />
					</div>
					
					<div id="status_msg"></div>
					
					<div>
						<button class="btn btn-primary">Submit </button>
					</div>

				<?php echo form_close(); ?>
				
			</div>
		</div>
	</div>
</div>