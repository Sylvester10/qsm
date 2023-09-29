<!--Class Options-->
	<div class="modal fade" id="details<?php echo $y->id; ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-width">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Fee Details: <?php echo $y->class; ?></h4>
				</div><!--/.modal-header-->
				<div class="modal-body">

					<?php 
					if ($term == current_term) { ?>
				
						<p><a type="button" href="<?php echo base_url($this->c_controller.'/collect_fees/'.$class_id); ?>" class="btn btn-default btn-sm btn-block action-btn"> <i class="fa fa-money" style="color: green"></i> &nbsp; Collect Fees</a></p>

					<?php } ?>

					<?php $uri_segment = $session.'/'.$term.'/'.$class_id; ?>

					<p><a type="button" href="<?php echo base_url($this->c_controller.'/full_payment/'.$uri_segment); ?>" class="btn btn-default btn-sm btn-block action-btn"> <i class="fa fa-money text-success"></i> &nbsp; View Full Payment</a></p>

					<p><a type="button" href="<?php echo base_url($this->c_controller.'/partial_payment/'.$uri_segment); ?>" class="btn btn-default btn-sm btn-block action-btn"> <i class="fa fa-money text-primary"></i> &nbsp; View Partial Payment</a></p>

					<p><a type="button" href="<?php echo base_url($this->c_controller.'/no_payment/'.$uri_segment); ?>" class="btn btn-default btn-sm btn-block action-btn"> <i class="fa fa-money text-danger"></i> &nbsp; View No Payment</a></p>

				</div>
			</div>
		</div>
	</div>