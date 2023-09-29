	
	<div class="modal fade" id="activate_coupon" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-width">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Activate Discount Voucher</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
				
					<?php 
					$activate_coupon_form_attributes = array("id" => "activate_coupon_form");
					echo form_open('activate/activate_coupon_ajax', $activate_coupon_form_attributes); ?>
					
						<div class="form-group">
							<label>Voucher Code</label>
							<input class="form-control" name="coupon_code" required placeholder="Enter the voucher code you received here" />
						</div>
						
						<div id="ac_status_msg"></div>
						
						<div class="m-t-20">
							<button class="btn btn-primary">Activate</button>
						</div>

					<?php echo form_close(); ?>

				</div>
			</div>
		</div>
	</div>