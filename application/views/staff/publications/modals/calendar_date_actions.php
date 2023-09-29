	
	<div class="modal fade" id="create_calendar_date" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Create New Event</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					<?php 
					$form_attributes = array("id" => "create_calendar_date_form");
					echo form_open('publications_staff/create_calendar_date_ajax', $form_attributes); ?>
					
						<div class="form-group">
							<label class="form-control-label">Date</label>
							<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
								<input type="text" class="form-control" name="calendar_date" value="<?php echo set_value('calendar_date', default_calendar_date()); ?>" required readonly />
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Event Caption</label>
							<input type="text" name="caption" class="form-control" value="<?php echo set_value('caption'); ?>" required />
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Event Description</label>
							<textarea name="description" class="form-control t200" required><?php echo set_value('description'); ?></textarea>
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
	
	
	<!--Term Date Clear Confirm-->
	<div class="modal fade" id="clear_calendar_dates" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Clear Calendar Dates</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					Are you sure you want to clear all calendar dates? This action will delete all the records. <br />
				</div>
				<div class="modal-footer">
					<a class="btn btn-sm btn-danger" role="button" href="<?php echo base_url('publications_staff/clear_calendar_dates'); ?>"> Yes, Clear All </a>
					<button data-dismiss="modal" class="btn btn-sm"> No, Cancel </button>
				</div>
			</div>
		</div>
	</div>