<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
	
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
		
			<?php 
			$form_attributes = array("id" => "update_term_info_form");
			echo form_open('settings/update_term_info_ajax', $form_attributes); ?>
			
				<div class="row">
				
					<div class="col-md-6 col-sm-12 col-xs-12">
					
						<div class="form-group">
							<label class="form-control-label">Current Session</label>
							<select class="form-control" name="session" required>
								<option selected value="<?php echo $y->session; ?>"><?php echo $y->session; ?></option>
								<?php 
								//starting from 1978
								$current_year = date('Y');
								for ($i = $current_year; $i >= 1978; $i--) { 
									$session = $i . '/' . ($i + 1); ?>
									<option value="<?php echo $session; ?>" <?php echo set_select('session', $session); ?> ><?php echo $session; ?></option>
								<?php } ?>
							</select>
							<div class="form-error"><?php echo form_error('session'); ?></div>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Current Term</label>
							<select class="form-control" name="term" required>
								<option selected value="<?php echo $y->term; ?>"><?php echo $y->term; ?></option>
								<option value="1st" <?php echo set_select('term', '1st'); ?> >1st</option>
								<option value="2nd" <?php echo set_select('term', '2nd'); ?> >2nd</option>
								<option value="3rd" <?php echo set_select('term', '3rd'); ?> >3rd</option>
							</select>
							<div class="form-error"><?php echo form_error('term'); ?></div>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Current Term Start Date</label>
							<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
								<input type="text" class="form-control" name="term_start_date" value="<?php echo set_value('term_start_date', $y->term_start_date); ?>" readonly required />
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
							</div>
							<div class="form-error"><?php echo form_error('term_start_date'); ?></div>
						</div>

						<div class="form-group">
							<label class="form-control-label">Current Term Closing Date</label>
							<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
								<input type="text" class="form-control" name="term_closing_date" value="<?php echo set_value('term_closing_date', $y->term_closing_date); ?>" readonly required />
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
							</div>
							<div class="form-error"><?php echo form_error('term_closing_date'); ?></div>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Current Term Fees Due Date</label>
							<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
								<input type="text" class="form-control" name="current_term_fees_due_date" value="<?php echo set_value('next_term_start_date', $y->current_term_fees_due_date); ?>" readonly required />
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
							</div>
							<div class="form-error"><?php echo form_error('current_term_fees_due_date'); ?></div>
						</div>

					</div>
					
					<div class="col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<label class="form-control-label">Next Term</label>
							<select class="form-control" name="next_term" required>
								<option selected value="<?php echo $y->next_term; ?>"><?php echo $y->next_term; ?></option>
								<option value="1st" <?php echo set_select('next_term', '1st'); ?> >1st</option>
								<option value="2nd" <?php echo set_select('next_term', '2nd'); ?> >2nd</option>
								<option value="3rd" <?php echo set_select('next_term', '3rd'); ?> >3rd</option>
							</select>
							<div class="form-error"><?php echo form_error('next_term'); ?></div>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Next Term Start Date</label>
							<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
								<input type="text" class="form-control" name="next_term_start_date" value="<?php echo set_value('next_term_start_date', $y->next_term_start_date); ?>" readonly required />
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
							</div>
							<div class="form-error"><?php echo form_error('next_term_start_date'); ?></div>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Next Term Fees Due Date</label>
							<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
								<input type="text" class="form-control" name="next_term_fees_due_date" value="<?php echo set_value('next_term_start_date', $y->next_term_fees_due_date); ?>" readonly required />
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
							</div>
							<div class="form-error"><?php echo form_error('next_term_fees_due_date'); ?></div>
						</div>

						
						<div id="status_msg"></div>
						
						<div class="form-group">
							<button class="btn btn-primary btn-lg">Update</button>
						</div>
						
					</div>
					
				</div><!--/.row-->
				
			<?php echo form_close(); ?>

		</div>
	</div>
