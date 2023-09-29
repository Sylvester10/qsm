
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	
<div class="table-scroll">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>S/N</th>
				<th class="min-w-100">Actions</th>
				<th class="min-w-150">Daemon Name</th>
				<th class="min-w-300">Description</th>
				<th class="min-w-200">Frequency</th>
				<th class="min-w-150">Total Emails</th>
				<th class="min-w-250">Last Run</th>
				<th class="min-w-200">Next Run</th>
				<th class="min-w-100">Job Count</th>
			</tr>
		</thead>
		<tbody>
			
			<?php
			$count = 1;
			foreach ($cron_daemons as $d) { 
				$last_run = x_date_time($d->last_run) . ' (' . time_ago($d->last_run) . ')';
				$next_run = x_date_time($d->next_run); 
				$jobs_url = ($d->category == 'database_data') ? 'cron_jobs_db' : 'cron_jobs_school_data';
				$total_jobs = $this->cron_jobs_model->count_all_cron_jobs_by_daemon($d->category, $d->name); ?>

				<tr>
					<td><?php echo $count; ?></td>
					<td class="w-15-p text-center">
						<a class="btn btn-primary btn-sm" href="<?php echo base_url('cron_jobs/test_run_daemon/'.$d->name); ?>" title="Test-run this daemon"><i class="fa fa-rocket"></i></a>
						<a class="btn btn-success btn-sm" href="<?php echo base_url('cron_jobs/'.$jobs_url.'/'.$d->name); ?>" title="View executed jobs"><i class="fa fa-clock-o"></i></a>
					</td>
					<td><?php echo $d->title; ?></td>
					<td><?php echo $d->description; ?></td>
					<td><?php echo $d->frequency; ?></td>
					<td><?php echo $d->total_mails; ?></td>
					<td><?php echo ($d->last_run != NULL) ? $last_run : NULL; ?></td>
					<td><?php echo ($d->next_run != NULL) ? $next_run : NULL; ?></td>
					<td><?php echo $total_jobs; ?></td>
				</tr>

				<?php $count++;
				
			} ?>

			<tr>
				<?php
				//empty data cells 
				for ($i = 0; $i < 8; $i++) {
					echo '<td></td>';
				} ?>
				<td><?php echo $this->total_cron_jobs_all; ?></td>
			</tr>

		</tbody>
	</table>
</div>

