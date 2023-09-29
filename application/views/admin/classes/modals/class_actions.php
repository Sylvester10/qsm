	
	<!--Class Options-->
	<div class="modal fade" id="options<?php echo $y->id; ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-width">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Actions: <?php echo $y->class; ?></h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
				
					<p><a type="button" href="<?php echo base_url('students_admin/single_class/'.$y->slug); ?>" class="btn btn-default btn-sm btn-block action-btn"> <i class="fa fa-eye" style="color: green"></i> &nbsp; View Class </a></p>

					<p><a type="button" href="<?php echo base_url('students_admin/attendance/'.$y->slug); ?>" class="btn btn-default btn-sm btn-block action-btn"> <i class="fa fa-refresh" style="color: green"></i> &nbsp; View Attendance </a></p>

					<p><a type="button" href="<?php echo base_url('timetable/lesson_periods/'.$y->slug); ?>" class="btn btn-default btn-sm btn-block action-btn"> <i class="fa fa-clock-o" style="color: green"></i> &nbsp; View Lesson Periods </a></p>
					
					<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn" data-toggle="modal" data-target="#edit<?php echo $y->id; ?>"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Class </a></p>
					
					<?php 
					if ($y->class_teacher_id != NULL) { ?>

						<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn" data-toggle="modal" data-target="#manage_class_teacher<?php echo $y->id; ?>"> <i class="fa fa-user" style="color: green"></i> &nbsp; Change Class Teacher </a></p>
					
						<p><a type="button" href="<?php echo base_url('classes/unassign_class/'.$y->id); ?>" class="btn btn-default btn-sm btn-block action-btn"> <i class="fa fa-exclamation-triangle" style="color: red"></i> &nbsp; Unassign Class </a></p>
						
						<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn" data-toggle="modal" data-target="#message_teacher<?php echo $y->id; ?>"> <i class="fa fa-envelope" style="color: green"></i> &nbsp; Message Class Teacher </a></p>
						
					<?php } else { ?>

						<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn" data-toggle="modal" data-target="#manage_class_teacher<?php echo $y->id; ?>"> <i class="fa fa-user" style="color: green"></i> &nbsp; Assign Class Teacher </a></p>

					<?php } ?>
					
					<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn" data-toggle="modal" data-target="#delete<?php echo $y->id; ?>"> <i class="fa fa-trash" style="color: red"></i> &nbsp; Delete Class </a></p>
					
				</div>
			</div>
		</div>
	</div>
	

	<!--Message Class Teacher-->
	<div class="modal fade" id="message_teacher<?php echo $y->id; ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Message Teacher: <?php echo $this->common_model->get_class_teacher_name($y->id); ?></h4>
				</div><!--/.modal-header-->
				<div class="modal-body">

					<?php echo form_open('classes/message_class_teacher/'. $y->id); ?>
						
						<div class="form-group">
							<label class="form-control-label">Your Message</label>
							<textarea name="message" class="form-control t200" required><?php echo set_value('message'); ?></textarea>
						</div>
						
						<div>
							<button class="btn btn-primary">Send Message </button>
						</div>

					<?php echo form_close(); ?>

				</div>
			</div>
		</div>
	</div>
	
	
	<!--Edit Class-->
	<div class="modal fade" id="edit<?php echo $y->id; ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Edit Class: <?php echo $y->class; ?></h4>
				</div><!--/.modal-header-->
				<div class="modal-body">

					<?php 
					echo form_open('classes/edit_class/'.$y->id); ?>
					
						<div class="form-group">
							<label class="form-control-label">Class</label>
							<input type="text" name="class" value="<?php echo $y->class; ?>" class="form-control" required />
						</div>

						<div class="form-group">
							<label class="form-control-label">Level</label>
							<input type="text" name="level" value="<?php echo $y->level; ?>" class="form-control" required />
						</div>

						<div class="form-group">
							<label class="form-control-label">Order Level <small>(Required to help with ordering)</small></label>
							<select class="form-control selectpicker" name="order_level">
								<option selected value="<?php echo $y->order_level; ?>"><?php echo $y->order_level; ?></option>
								<?php 
								for ($i = 1; $i <= 99; $i++) { ?>
									<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>
						
						<div class="form-group">
							<label class="form-control-label">Section</label>
							<select class="form-control selectpicker" name="section_id" required>
								<?php 
								//check if any section has been created
								if ( count($sections) === 0 ) { ?>
								
									<option value="">No sections found!</option>
								
								<?php } else { 

									$section = $this->common_model->get_section_details($y->section_id)->section; ?>
								
									<option selected value="<?php echo $y->section_id; ?>"><?php echo $section; ?></option>
									<?php
									//list the sections
									foreach ($sections as $st) { ?>
										<option value="<?php echo $st->id; ?>"><?php echo $st->section; ?></option>
									<?php } //endforeach ?>
									
								<?php } //endif ?>
							</select>
						</div>
						
						<div>
							<button class="btn btn-primary">Update</button>
						</div>

					<?php echo form_close(); ?>

				</div>
			</div>
		</div>
	</div>



	<!--Edit Class-->
	<div class="modal fade" id="manage_class_teacher<?php echo $y->id; ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Manage Class Teacher: <?php echo $y->class; ?></h4>
				</div><!--/.modal-header-->
				<div class="modal-body">

					<p>Note: Only staff assigned the role of Class Teacher can be assigned a class.</p>
					
					<?php 
					echo form_open('classes/change_class_teacher/'.$y->id); ?>
						
						<div class="form-group">
							<label class="form-control-label">Class Teacher: <?php echo $this->common_model->get_class_teacher_name($y->id); ?></label>
							<select class="form-control selectpicker" name="class_teacher_id">
								<?php 
								//check if any section has been created
								if ( count($teachers) === 0 ) { ?>
								
									<option value="">No teachers found!</option>
								
								<?php } else { 

									if ($y->class_teacher_id == NULL) { ?>
										<option value="">Leave unassigned</option>
									<?php } else { ?>
										<option value="<?php echo $y->class_teacher_id; ?>"><?php echo $this->common_model->get_class_teacher_name($y->id); ?></option>
									<?php }
									
									//list the teachers
									foreach ($teachers as $class_teacher_id) { 
										$name = $this->common_model->get_staff_details_by_id($class_teacher_id)->name; ?>
										<option value="<?php echo $class_teacher_id; ?>"><?php echo $name; ?></option>
									<?php } //endforeach ?>
									
								<?php } //endif ?>
							</select>
						</div>
						
						<div>
							<button class="btn btn-primary">Update</button>
						</div>

					<?php echo form_close(); ?>

				</div>
			</div>
		</div>
	</div>
	
	