
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
					<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('publications_staff/calendar_dates'); ?>"><i class="fa fa-calendar"></i> Calendar Grid</a>
				</div>
			</div>
		</div>
	</div>
</div>


<?php
//select options bulk actions 
$options_array = array(
	//'value' => 'Caption'
	'delete' => 'Delete'
); 
echo modal_bulk_actions_alt('publications_staff/bulk_actions_calendar_dates', $options_array); ?>

	<?php
	if ($total_records > 0) { 

		foreach ($calendar_list as $y) { 

			require "application/views/staff/publications/modals/manage_calendar.php"; 
			//delete confirm modal
			echo modal_delete_confirm($y->id, $y->caption, 'event', 'publications_staff/delete_calendar_date'); ?>

				<div class="row calendar_list_item m-b-30">

					<div class="col-md-1">
						<?php echo checkbox_bulk_action($y->id); ?>
					</div>

					<div class="col-md-2">
						<div class="calendar_list_date">
							<h2><?php echo strtoupper(get_month_value_short($y->month)); ?> <?php echo $y->day; ?></h2>
							<p><?php echo $y->year; ?></p>
						</div>
					</div>

					<div class="col-md-8">
						<div class="calendar_list_content">
							<h3><?php echo $y->caption; ?></h3>
							<p><?php echo $y->description; ?></p>
						</div>
					</div>

					<div class="col-md-1">
						<div class="calendar_list_action">
							<button class="btn btn-primary btn-sm button-adjust" data-toggle="modal" data-target="#options<?php echo $y->id; ?>"><i class="fa fa-navicon"></i> </button>
						</div>
					</div>

				</div>
		<?php } 
		
	} else { ?>

		<h3 class="text-danger">No event to show.</h3>

	<?php } ?>
	
		
	<!--Pagination Links-->
	<ul class="pagination">
		<?php foreach ($links as $link) {
			echo '<li>' . $link . '</li>';
		} ?>
	</ul>


<?php echo form_close(); ?>