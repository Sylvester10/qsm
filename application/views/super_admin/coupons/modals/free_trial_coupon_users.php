	<div class="modal fade" id="coupon_user<?php echo $coupon_id; ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Discount Voucher Users: <?php echo $y->name; ?> (<?php echo count($coupon_users); ?>)</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">

					<p>Here is a list of schools who have activated the <?php echo $y->name; ?> voucher. They will be charged the discounted price during payment.</p>

					<hr />
					
					<?php 
					foreach ($coupon_users as $u) { 

						$school_id = $u->school_id;
						$school_details = $this->common_model->get_school_info($school_id);
						$school_name = $school_details->school_name; ?> 
						
						<div class="row">
							<div class="col-md-8">
								<?php echo $school_name; ?>
							</div>
							<div class="col-md-2 col-md-offset-2">
								<a class="btn btn-danger btn-sm" href="<?php echo base_url('coupon/delete_school_free_trial_coupon/'.$school_id); ?>" title="Remove school from coupon list"><i class="fa fa-trash"></i></a>
							</div>
						</div> 

					<?php } ?>
					
				</div>
			</div>
		</div>
	</div>