	<div class="modal fade" id="clear_fees" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Clear Fees</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					Are you sure you want to clear all fee details for the current term?
				</div>
				<div class="modal-footer">
					<a class="btn btn-sm btn-danger" role="button" href="<?php echo base_url($this->c_controller.'/clear_fee_details'); ?>"> Yes, Clear All </a>
					<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
				</div>
			</div>
		</div>
	</div>