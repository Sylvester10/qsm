	
	<div class="modal fade" id="change_upgrade_plan" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-width">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Upgrade Plan</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
				
					<?php 
					echo form_open('upgrade/change_upgrade_plan_other'); ?>
					
						<div class="form-group">
							<label>Upgrade to:</label>
							<select class="form-control" name="plan_id" required>
								<option value="">Select Plan</option>
					
								<?php 
								foreach ($upgrade_plans as $p) {
									$plan_id = $p->id;
									$amount = $this->common_model->get_plan_price_digit_by_location_no_format($plan_id); 
									//get upgrade amount
									$additional_amount = $this->plan_model->get_additional_amount($plan_id);
									$upgrade_amount = number_format($additional_amount, 2);
									$upgrade_price = $currency_code . $upgrade_amount; ?>
									<option class="<?php echo ($plan_id == 1) ? 'hide' : NULL; ?>" value="<?php echo $p->id; ?>"><?php echo $p->plan; ?> (<?php echo $upgrade_price; ?>)</option>
								<?php } ?>
								
							</select>
						</div>
						
						<div class="m-t-20">
							<button class="btn btn-primary">Change</button>
						</div>
						
					<?php echo form_close(); ?>

				</div>
			</div>
		</div>
	</div>