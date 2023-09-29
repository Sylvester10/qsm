
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="new-item">
		<h4 class="text-bold">Class/Attendance Information</h4>
		<p>Class Population: <?php echo $this->common_model->get_class_population($class_id); ?></p>
		<p>Class Level: <?php echo $level; ?></p>
		<p>Session: <?php echo $the_session; ?></p>
		<p>Term: <?php echo $term; ?></p>
		<p>Class Teacher: <?php echo $this->common_model->get_class_teacher_name($class_id); ?></p>
	</div>
			

	<?php echo form_open('class_teacher/mark_attendance'); ?>
		<div class="attendance_area">
			<div class="table-scroll">
				<table class="table table-bordered cell-text-middle" style="text-align: left">
					<thead>
						<tr>
							<th class="w-50-p">Action</th>
							<th>Student's Name</th>
							<th>Present</th>
							<th>Absent</th>
							<th>Total</th>
							<th class="min-w-100">Recent Attendance <small>(Last 3)</small></th>
							<th>
								<div class="btn-group">
			                        <button class="btn btn-success btn-sm" type="button" id="select_all_present">All Present</button>
			                        <button class="btn btn-danger btn-sm" type="button" id="select_all_absent">All Absent</button>
			                        <button class="btn btn-default btn-sm" type="button" id="deselect_all">Deselect All</button>
			                    </div>
							</th>
						</tr>
					</thead>
					<tbody>

						<?php
						$i = 0;
						foreach ($students as $s) {

							$student_id = $s->id;
							$att_present = $this->common_model->get_attendance_present($session, $term, $class_id, $s->id);
							$att_absent = $this->common_model->get_attendance_absent($session, $term, $class_id, $s->id);
							$att_total = $this->common_model->get_attendance_total($session, $term, $class_id, $s->id);
							?>

							<input type="hidden" name="student_id[]" value="<?php echo $student_id; ?>" />
						
							<tr>
								<td><a class="btn btn-primary" href="<?php echo base_url('class_teacher/attendance_details/'.$s->id); ?>" target="_blank" title="View details"><i class="fa fa-history"></i></td>
								<td><?php echo $this->common_model->get_student_fullname($s->id); ?>
									
									<?php
									//flag attendance if absent is >= 5
									if ($att_absent >= 5) { ?>
										<div class="pull-right badge" style="background: #a94442;" title="Attendance level critical"> 
											<i class="fa fa-warning"></i> 
										</div>
									<?php } ?>

								</td>
								<td class="text-bold"><?php echo $att_present; ?></td>
								<?php
								//flag attendance red if absent is >= 5
								if ($att_absent < 5) { ?>
									<td class="text-bold"><?php echo $att_absent; ?></td>
								<?php } else { ?>
									<td class="text-bold text-danger"><?php echo $att_absent; ?></td>
								<?php } ?>
								<td class="text-bold"><?php echo $att_total; ?></td>
								<td class="text-bold">

									<?php 
									$limit = 3;
									$recent_attendance = $this->class_teacher_model->get_recent_attendance($student_id, $limit);

									foreach ($recent_attendance as $r) { ?>
										
										<small>
											<?php echo x_date($r->date); 
											if ($r->status == 'Present') { ?> 
												<i class="fa fa-check text-success"></i>
											<?php } else { ?>
												<i class="fa fa-times text-danger"></i>
											<?php } ?>
											<br />
										</small>

									<?php } ?>

								</td>
								<td class="att_toggle">
									<div class="row">
										<div class="col-md-5">
		                        			<input class="bulk_select_present" type="radio" name="status[<?php echo $i; ?>]" value="Present" /> Present
		                        		</div>
	                        			<div class="col-md-5">
	                        				<input class="bulk_select_absent" type="radio" name="status[<?php echo $i; ?>]" value="Absent" /> Absent
	                        			</div>
	                        		</div>
								</td>
							</tr>

							<?php $i++; ?>

						<?php } ?>

					</tbody>
				</table>
			</div>


			<?php 
			//show attendance action buttons if at least 1 student exists in class
			if (count($students) > 0) { ?>

				<div class="row m-t-20">
					<div class="col-md-4 col-md-offset-8">
						<div class="pull-right attendance_buttons">
							With selected: <br />

							<div class="form-group">
								<label class="form-control-label">Select Date</label>
								<div class="input-group date date_datepicker_no_future" data-date-format="yyyy-mm-dd" style="width: 180px">
									<input type="text" class="form-control" name="date" value="<?php echo set_value('date', default_calendar_date()); ?>" readonly required />
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
								</div>
							</div>

							<button class="btn btn-success" type="submit">Mark Attendance</button>

						</div><!-- /.pull-right -->
					</div><!-- /.col-md-8 -->
				</div><!-- /.row -->

			<?php } ?>

		</div><!--/.attendance_area-->

	<?php echo form_close() //mark_attendance ?>
