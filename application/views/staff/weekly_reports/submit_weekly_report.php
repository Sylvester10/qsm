	
	<div class="row">
		<div class="col-md-10 col-sm-12 col-xs-12">
		
			<?php echo form_open_multipart('weekly_reports_staff/submit_weekly_report_action'); ?>
			
				<div class="row">
				
					<div class="col-md-6 col-sm-12 col-xs-12">
					
						<div class="form-group">
							<label class="form-control-label">Week</label>
							<select class="form-control" name="week" required>
								<option value="">Select Week</option>	
								<?php 
								//assumes there are at most 30 weeks in a term
								for ($week = 1; $week <= 30; $week++) { ?>
									<option value="<?php echo $week; ?>" <?php echo set_select('week', $week); ?> ><?php echo $week; ?></option>
								<?php } ?>
							</select>
							<div class="form-error"><?php echo form_error('week'); ?></div>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Starting Date</label>
							<div class="input-group date datepicker" data-date-format="dd/mm/yyyy">
								<input type="text" class="form-control" name="starting_date" value="<?php echo set_value('starting_date'); ?>" readonly required />
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
							</div>
							<div class="form-error"><?php echo form_error('starting_date'); ?></div>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Ending Date</label>
							<div class="input-group date datepicker" data-date-format="dd/mm/yyyy">
								<input type="text" class="form-control" name="ending_date" value="<?php echo set_value('ending_date'); ?>" readonly required />
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
							</div>
							<div class="form-error"><?php echo form_error('ending_date'); ?></div>
						</div>
					</div><!--/.col-md-6-->
					
					<div class="col-md-6 col-sm-12 col-xs-12">

						<div class="form-group">
							<label class="form-control-label">Report Type</label>
							<select class="form-control" name="report_type_id" required>
								<option value="">Select Type</option>	
								
								<?php 
								foreach ($weekly_report_types as $t) { ?>
									<option value="<?php echo $t->id; ?>" <?php echo set_select('report_type_id', $t->id); ?> ><?php echo $t->type; ?></option>
								<?php } ?>

							</select>
							<div class="form-error"><?php echo form_error('report_type_id'); ?></div>
						</div>
							
						<div class="form-group">
							<label class="form-control-label">Upload report </label><br />
							<small>Only Word, PDF and Excel files allowed (max 5MB).</small>
							<input type="file" name="report" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx" required />
							<div class="form-error"><?php echo $upload_error['error']; ?></div>
						</div>
						
						<div class="form-group">       
							<input type="submit" value="Submit Report" class="btn btn-primary m-t-5">
						</div>
						
					</div><!--/.col-md-6-->
				</div><!--/.row-->
				
			<?php echo form_close(); ?>	
			
		</div>
	</div>