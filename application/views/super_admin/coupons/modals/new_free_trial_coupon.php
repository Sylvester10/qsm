	<div class="modal fade" id="new_coupon" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">New Discount Voucher</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">

					<?php 
					$form_attributes = array("id" => "new_free_trial_coupon_form");
					echo form_open('coupon/new_free_trial_coupon_ajax', $form_attributes); ?>
					
						<div class="form-group">
							<label class="form-control-label">Voucher Name</label>
							<input type="text" name="name" class="form-control" required />
						</div>

						<div class="form-group">
							<label class="form-control-label">Voucher Code</label>
							<input type="text" name="code" class="form-control" required />
						</div>

						<div class="form-group">
							<label class="form-control-label">Discount</label>
							<select class="form-control" name="discount" required>
								<option value="">Select Discount</option>	
								<?php 
								//assumes there are at most 30 weeks in a term
								for ($percent = 1; $percent <= 100; $percent++) { ?>
									<option value="<?php echo $percent; ?>" <?php echo set_select('percent', $percent); ?> ><?php echo $percent; ?>%</option>
								<?php } ?>
							</select>
							<div class="form-error"><?php echo form_error('percent'); ?></div>
						</div>

						<div class="form-group">
							<label class="form-control-label">Valid Until</label>
							<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
								<input type="text" class="form-control" name="valid_until" value="<?php echo set_value('valid_until', default_calendar_date()); ?>" required readonly />
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
							</div>
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