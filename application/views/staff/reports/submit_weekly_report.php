	
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
		
			<?php echo form_open_multipart('staff/submit_weekly_report_action'); ?>
			
				<div class="row">
				
					<div class="col-md-6 col-sm-12 col-xs-12">
					
						<div class="form-group">
							<label class="form-control-label">Title</label>
							<input type="text" name="title" value="<?php echo set_value('title'); ?>" class="form-control" required />
							<div class="form-error"><?php echo form_error('title'); ?></div>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Week</label>
							<select class="form-control" name="week" required>
								<option value="">Select Week</option>	
								<?php 
								//assumes there are at most 18 weeks in a term
								for ($week = 1; $week < 18; $week++) { ?>
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
							<label class="form-control-label">Term</label>
							<select class="form-control" name="term" required>
								<option value="">Select Term</option>	
								<option value="1st Term" <?php echo set_select('term', '1st Term'); ?> >1st Term</option>
								<option value="2nd Term" <?php echo set_select('term', '2nd Term'); ?> >2nd Term</option>
								<option value="3rd Term" <?php echo set_select('term', '3rd Term'); ?> >3rd Term</option>
							</select>
							<div class="form-error"><?php echo form_error('term'); ?></div>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Session</label>
							<select class="form-control" name="session" required>
								<option value="">Select Session</option>	
								<?php 
								//starting from school inception, 1978
								$current_year = date('Y');
								for ($i = $current_year; $i >= 1978; $i--) { 
									$session = $i . '/' . ($i + 1); ?>
									<option value="<?php echo $session; ?>" <?php echo set_select('session', $session); ?> ><?php echo $session; ?></option>
								<?php } ?>
							</select>
							<div class="form-error"><?php echo form_error('session'); ?></div>
						</div>
							
						<div class="form-group">
							<label class="form-control-label">Upload report </label><br />
							<small>Only Word and PDF files allowed (max 5MB).</small>
							<input type="file" name="report" class="form-control" accept=".pdf,.doc,.docx" required />
							<div class="form-error"><?php echo $error; ?></div>
						</div>
						
						<div class="form-group">       
							<input type="submit" value="Submit Report" class="btn btn-primary m-t-5">
						</div>
						
					</div><!--/.col-md-6-->
				</div><!--/.row-->
				
			<?php echo form_close(); ?>	
			
		</div>
	</div>