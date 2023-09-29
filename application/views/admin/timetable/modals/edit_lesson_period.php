
	<div class="modal fade" id="edit<?php echo $d->id; ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Edit Lesson Period: <?php echo $subject; ?> </h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					<?php 
					echo form_open('timetable_admin/edit_lesson_period/'.$d->id); 
						$the_class = $this->common_model->get_class_details($d->class_id)->class; ?>

						<div class="form-group">
							<label class="form-control-label">Class</label>
							<select name="class_id" class="form-control selectpicker" required readonly>
								<option selected value="<?php echo $d->class_id; ?>"><?php echo $the_class; ?></option>
							</select>
						</div>

						<div class="form-group">
							<label class="form-control-label">Subject</label>
							<select name="subject_id" class="form-control selectpicker" required>
								<option selected value="<?php echo $d->subject_id; ?>"><?php echo $subject; ?></option>
								<?php echo $subjects_option; ?>
							</select>
						</div>
					
						<div class="form-group">
							<label class="form-control-label">Day</label>
							<select name="day" class="form-control selectpicker" required>
								<option selected value="<?php echo $d->day; ?>"><?php echo $d->day; ?></option>
								<option value="Monday">Monday</option>
								<option value="Tuesday">Tuesday</option>
								<option value="Wednesday">Wednesday</option>
								<option value="Thursday">Thursday</option>
								<option value="Friday">Friday</option>	
							</select>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Starting Period</label>
							<input type="text" name="start_time" value="<?php echo $d->start_time; ?>" class="form-control" required readonly />
						</div>

						<div class="form-group">
							<label class="form-control-label">Ending Period</label>
							<input type="text" name="end_time" value="<?php echo $d->end_time; ?>" class="form-control" required readonly />
						</div>
						
						<div>
							<button class="btn btn-primary">Update</button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>