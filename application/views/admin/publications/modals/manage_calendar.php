	
	<!--Calendar Date Options-->
	<div class="modal fade" id="options<?php echo $y->id; ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-width">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Actions: <?php echo $y->caption; ?></h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn" data-toggle="modal" data-target="#edit<?php echo $y->id; ?>"> <i class="fa fa-edit" style="color: green"></i> &nbsp; Edit Event </a></p>
					<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn" data-toggle="modal" data-target="#delete<?php echo $y->id; ?>"> <i class="fa fa-trash" style="color: red"></i> &nbsp; Delete Event </a></p>
				</div>
			</div>
		</div>
	</div>
	
	
	<!--Calendar Date Edit Form-->
	<div class="modal fade" id="edit<?php echo $y->id; ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Edit: <?php echo $y->caption; ?></h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					<?php echo form_open('publications_admin/edit_calendar_date/'. $y->id); 
						
						$date = $y->year .'/'. $y->month .'/'. $y->day; ?>
					
						<div class="form-group">
							<label class="form-control-label">Date</label>
							<div class="input-group date calendar_date_datepicker" data-date-format="yyyy-mm-dd">
								<input type="text" class="form-control" name="calendar_date" value="<?php echo set_value('calendar_date', $date); ?>" readonly required />
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Event Caption</label>
							<input type="text" name="caption" class="form-control" value="<?php echo set_value('caption', $y->caption); ?>" required />
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Event Description</label>
							<textarea name="description" class="form-control t200" required><?php echo set_value('description', strip_tags($y->description)); ?></textarea>
						</div>
						
						<div>
							<button class="btn btn-primary">Submit </button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>