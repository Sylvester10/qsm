
<div class="modal fade" id="edit_report_type<?php echo $y->id; ?>" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content modal-form-sm">
			<div class="modal-header">
				<div class="pull-right">
					<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
				</div>
				<h4 class="modal-title">Edit: <?php echo $y->type; ?></h4>
			</div><!--/.modal-header-->
			<div class="modal-body">

				<?php echo form_open('weekly_reports_admin/edit_report_type/'.$y->id); ?>
				
					<div class="form-group">
						<label class="form-control-label">Report Type</label>
						<input type="text" name="type" value="<?php echo set_value('type', $y->type); ?>" class="form-control" required />
					</div>
					
					<div>
						<button class="btn btn-primary">Update </button>
					</div>

				<?php echo form_close(); ?>

			</div>
		</div>
	</div>
</div>