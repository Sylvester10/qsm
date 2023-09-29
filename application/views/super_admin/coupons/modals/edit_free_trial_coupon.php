	<div class="modal fade" id="edit<?php echo $coupon_id; ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Update Discount Voucher</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">

					<?php echo form_open('coupon/edit_free_trial_coupon/'.$coupon_id); ?>
					
						<div class="form-group">
							<label class="form-control-label">Voucher Name</label>
							<input type="text" name="name" class="form-control" value="<?php echo $y->name; ?>" required />
						</div>

						<div class="form-group">
							<label class="form-control-label">Voucher Code</label>
							<input type="text" name="code" class="form-control" value="<?php echo $y->code; ?>" required readonly />
						</div>

						<div class="form-group">
							<label class="form-control-label">Discount</label>
							<select class="form-control" name="discount" required>
								
								<?php 
								//assumes there are at most 30 weeks in a term
								for ($percent = 1; $percent <= 100; $percent++) { 
									//add 'selected' attr to current discount value
									$selected = ($percent == $y->discount) ? 'selected' : NULL; ?>
									<option <?php echo $selected; ?> value="<?php echo $percent; ?>" <?php echo set_select('percent', $y->discount); ?> ><?php echo $percent; ?>%</option>
								<?php } ?>
								
							</select>
							<div class="form-error"><?php echo form_error('percent'); ?></div>
						</div>

						<div class="form-group">
							<label class="form-control-label">Valid Until</label>
							<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
								<input type="text" class="form-control" name="valid_until" value="<?php echo set_value('valid_until', $y->valid_until); ?>" required readonly />
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