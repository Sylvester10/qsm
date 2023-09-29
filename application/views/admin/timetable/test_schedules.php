
<?php require("application/views/admin/timetable/modals/test_schedules_actions.php");  ?>
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="row">
		<div class="col-md-3">
			<div class="new-item">
				<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#new_schedule"><i class="fa fa-plus"></i> New Schedule</button>

				<?php if ($total_schedules > 0) { ?>
					<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#clear"><i class="fa fa-trash"></i> Clear All</button>
				<?php } ?>

			</div>
		</div>
		<div class="col-md-6 col-md-offset-3">
			<div class="pull-right m-t-10 m-b-10">
				<h4 class="text-bold">Test Information</h4>
				<p>Start Date: <?php echo $start_date; ?></p>
				<p>End Date: <?php echo $end_date; ?></p>
			</div>
		</div>
	</div>

<h3>Note</h3>
<p>Dates with test schedules are in green background. Click on the cell to see schedule details.</p>

<div class="calendar_responsive">	
	<?php echo $test_schedules; ?>
</div>
