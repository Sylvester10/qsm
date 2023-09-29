	
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
		
			<?php echo form_open_multipart('staff/initiate_request_action'); ?>
			
				<div class="row">
				
					<div class="col-md-6 col-sm-12 col-xs-12">
						<h3>Request Details</h3>
						<div class="form-group">
							<label class="form-control-label">Purpose of request (e.g. Internet Subscription)</label>
							<input type="text" name="purpose" id="purpose" value="<?php echo set_value('purpose'); ?>" class="form-control" required />
							<div class="form-error"><?php echo form_error('purpose'); ?></div>
						</div>
						<div class="form-group">
							<label class="form-control-label">Item(s) being requested </label>
							<textarea type="text" name="items" id="items" class="form-control t100" required><?php echo set_value('items'); ?></textarea>
							<div class="form-error"><?php echo form_error('items'); ?></div>
						</div>
						<div class="form-group">
							<label class="form-control-label">Why is/are the item(s) needed? (be concise)</label>
							<textarea type="text" name="items_info" class="form-control t100" required><?php echo set_value('items_info'); ?></textarea>
							<div class="form-error"><?php echo form_error('items_info'); ?></div>
						</div>
						<div class="form-group">
							<label class="form-control-label">How urgently is/are the item(s) needed? (e.g. 2 weeks)</label>
							<input type="text" name="urgency" value="<?php echo set_value('urgency'); ?>" class="form-control" required />
							<div class="form-error"><?php echo form_error('urgency'); ?></div>
						</div>
						<div class="form-group">
							<label class="form-control-label">Amount (in digits)</label>
							<div class="input-group">
								<div class="input-group-addon"><span class="input-group-text"><?php echo s_currency_symbol; ?></span></div>
								<input type="text" name="amount_digits" value="<?php echo set_value('amount_digits'); ?>" class="form-control numbers-only" id="amount_digits" required maxlength="15"/>
								<div class="input-group-addon"><span class="input-group-text">.00</span></div>
							</div>
							<div class="form-error"><?php echo form_error('amount_digits'); ?></div>
						</div>
						<div class="form-group">
							<label class="form-control-label">Amount (in words)</label>
							<textarea type="text" name="amount_words" class="form-control t100" id="amount_words" readonly autocomplete="off"><?php echo set_value('amount_words'); ?></textarea>
							<div class="form-error"><?php echo form_error('amount_words'); ?></div>
						</div>
					</div>
				
					<div class="col-md-6 col-sm-12 col-xs-12">
						
						<h3>Additional Content (optional)</h3>
						<div class="form-group">
							<label class="form-control-label">Upload a file </label><br />
							<small>Only documents (PDF, Word, Excel, PowerPoint), image and zip files allowed (max 10MB).</small>
							<input type="file" name="content" class="form-control" />
							<div class="form-error"><?php echo $error; ?></div>
						</div>
						
						<h3 class="m-t-30">Account Details</h3>
						<div class="form-group">
							<label class="form-control-label">Account Name</label>
							<input type="text" name="acc_name" value="<?php echo set_value('acc_name', $staff_details->name); ?>" class="form-control" required />
							<div class="form-error"><?php echo form_error('acc_name'); ?></div>
						</div>
						<div class="form-group">
							<label class="form-control-label">Account Number
							<?php if ($staff_details->acc_number == NULL) { ?>
								(<a href="<?php echo base_url('staff/profile'); ?>">update in profile</a>)
							<?php } ?>
							</label>
							<input type="text" name="acc_number" value="<?php echo set_value('acc_number', $staff_details->acc_number); ?>" class="form-control numbers-only" required />
							<div class="form-error"><?php echo form_error('acc_number'); ?></div>
						</div>
						<div class="form-group">
							<label class="form-control-label">Bank Name
							<?php if ($staff_details->bank_name == NULL) { ?>
								(<a href="<?php echo base_url('staff/profile'); ?>">update in profile</a>)
							<?php } ?>
							</label>
							<select class="form-control" name="bank_name" required>
								<option selected value="<?php echo $staff_details->bank_name; ?>"><?php echo $staff_details->bank_name; ?></option>	
								<?php 
								$banks = nigerian_banks();
								sort($banks); //sort in ascending order
								foreach ($banks as $index => $bank_name) { ?>
									<option value="<?php echo $bank_name; ?>" <?php echo set_select('bank_name', $bank_name); ?> ><?php echo $bank_name; ?></option>
								<?php } ?>
							</select>
							<div class="form-error"><?php echo form_error('bank_name'); ?></div>
						</div>
						
						<div class="form-group">       
							<input type="submit" value="Submit Request" class="btn btn-lg btn-primary">
						</div>
					</div>
					
				</div>
				
			<?php echo form_close(); ?>				
		</div>
	</div>