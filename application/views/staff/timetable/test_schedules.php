
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<div class="row">
	<div class="col-md-12">
		<div class="pull-right m-t-10 m-b-10">
			<h4 class="text-bold">Test Information</h4>
			<p>Start Date: <?php echo $start_date; ?></p>
			<p>End Date: <?php echo $end_date; ?></p>
		</div>
	</div><!-- /.col-md-12 -->
</div><!-- /.row -->

<h3>Note</h3>
<p>Dates with test schedules are in green background. Click on the cell to see schedule details.</p>

<div class="calendar_responsive">	
	<?php echo $test_schedules; ?>
</div>
