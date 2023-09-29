
<?php require("application/views/staff/publications/modals/calendar_date_actions.php");  ?>
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<div class="row">
		<div class="col-md-10">
			<div class="new-item">
				<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#create_calendar_date"><i class="fa fa-plus"></i> New Event</button>
				<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#clear_calendar_dates"><i class="fa fa-trash"></i> Clear All</button>
			</div>
		</div>
		<div class="col-md-2">
			<div class="pull-right">
				<div class="pull-right m-b-10">
					<div class="new-item">
						<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('publications_staff/calendar_dates_list'); ?>"><i class="fa fa-calendar"></i> Calendar List</a>
					</div>
				</div>
			</div>
		</div>
	</div>

<h3>Note</h3>
<p>Dates with events are highlighted with green. Click on the cell to see event details.</p>
<p>To manage events, view <a href="<?php echo base_url('publications_staff/calendar_dates_list'); ?>">Calendar List</a>.</p>

<div class="calendar_responsive">	
	<?php echo $calendar; ?>
</div>
