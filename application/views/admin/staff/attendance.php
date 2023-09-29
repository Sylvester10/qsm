
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


	<?php echo form_open('school_staff/mark_attendance'); ?>
		<div class="attendance_area">
			<table id="staff_attendance_table" class="table table-bordered cell-text-middle" style="text-align: left">
				<thead>
					<tr>
						<th class="w-50-p">Action</th>
						<th>Name</th>
						<th>Present</th>
						<th>Absent</th>
						<th>Total</th>
						<th>
							<div class="pull-right">
								<input type="checkbox" class="select_all" title="Select all" />
							</div>
							Select Staff
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($staff as $s) { 
						$att_present = $this->school_staff_model->get_staff_attendance_present($s->id);
						$att_absent = $this->school_staff_model->get_staff_attendance_absent($s->id);
						$att_total = $this->school_staff_model->get_staff_attendance_total($s->id);
						?>
					
						<tr>
							<td><a class="btn btn-primary" href="<?php echo base_url('school_staff/attendance_details/'.$s->id); ?>" target="_blank" title="View details"><i class="fa fa-history"></i></td>
							<td><?php echo $s->name; ?>
								
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
							<td>
								<div class="pull-right">
									<?php echo checkbox_bulk_action($s->id); ?>
								</div>
							</td>
						</tr>

					<?php } ?>
				</tbody>
			</table>

			<?php 
			//show attendance action buttons if at least 1 staff exists in class
			if (count($staff) > 0) { ?>

				<div class="row m-t-20">
					<div class="col-md-4 col-md-offset-8">
						<div class="pull-right attendance_buttons">
							With selected: <br />

							<div class="form-group">
								<label class="form-control-label">Select Date</label>
								<div class="input-group date date_datepicker_no_future" data-date-format="yyyy-mm-dd" style="width: 270px">
									<input type="text" class="form-control" name="date" value="<?php echo set_value('date', default_calendar_date()); ?>" readonly required />
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
								</div>
							</div>

							<button class="btn btn-success" type="submit" name="attendance_action" value="mark_present">Mark Present</button>
							<button class="btn btn-danger" type="submit" name="attendance_action" value="mark_absent">Mark Absent</button>
						</div><!-- /.pull-right -->
					</div><!-- /.col-md-8 -->
				</div><!-- /.row -->

			<?php } ?>

		</div><!--/.attendance_area-->

	<?php echo form_close() //mark_attendance ?>
