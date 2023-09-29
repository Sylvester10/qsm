
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<div class="row">
	<div class="col-md-12">
		<h4 class="text-bold">Exam Information</h4>
		<p>Class: <?php echo $class; ?></p>
		<p>Session: <?php echo current_session; ?></p>
		<p>Term: <?php echo current_term; ?></p>
		<p>Start Date: <?php echo $start_date; ?></p>
		<p>End Date: <?php echo $end_date; ?></p>
	</div><!-- /.col-md-12 -->
</div><!-- /.row -->


<?php if ($total_schedules > 0) { ?>

	<div class="row m-t-30">
		<div class="col-md-10 table-scroll">
			<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
				
				<thead>
					<tr>
						<th class="w-5"> S/N </th>
						<th class="w-30"> Date </th>
						<th class="w-20"> Time </th>
						<th class="w-45"> Subject </th>
					</tr>
				</thead>
				<tbody>
					
					<?php
					$count = 1;
					foreach ($exam_schedules as $s) { 

						$subject = $this->common_model->get_subject_details($s->subject_id)->subject;
						$date = $s->year .'/'. $s->month .'/'. $s->day;
						$date = x_date_full($date);
						$class_idx = $s->class_id;
						//class IDs are saved as 1, 2, 3, ...
						//explode class IDs into array of individual IDs
						$class_idx = explode(", ", $class_idx);

						if (in_array($class_id, $class_idx)) { ?>

							<tr>
								<td><?php echo $count; ?></td>
								<td><?php echo $date; ?></td>
								<td><?php echo $s->time; ?></td>
								<td><?php echo $subject; ?></td>
							</tr>

							<?php $count++;

						} 

					} ?>

				</tbody>
			</table>
		</div>
	</div>

<?php } else { ?>

	<h3 class="text-danger">No exam schedules found for this class.</h3>

<?php } ?>