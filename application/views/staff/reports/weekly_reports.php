
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('staff/submit_weekly_report'); ?>"><i class="fa fa-plus"></i> New Report</a>
	</div>

	<table id="weekly_reports_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
	
		<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />	
		<thead>
			<tr>
				<th> Actions </th>
				<th> Ref ID </th>
				<th> Reported By </th>
				<th class="min-w-200"> Title </th>
				<th> Week </th>
				<th class="min-w-200"> Date Range </th>
				<th> Term </th>
				<th> Session </th>
				<th> Date Submitted </th>
			</tr>
		</thead>
		<tbody>
		</tbody>
		
	</table>
	