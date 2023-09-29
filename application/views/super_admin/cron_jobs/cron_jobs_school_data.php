<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


<?php
if ($total_jobs > 0) {
	$custom_msg = 'Are you sure you want to clear all cron jobs for this daemon?'; 
	$url = 'cron_jobs/clear_cron_jobs_school_data/'.$cron_name;
	$title = 'Clear Cron Jobs: ' . $cron_title . ' (' . $total_jobs . ')';
	echo modal_confirm_action('clear_jobs_school_data', $title, $custom_msg, $url);
}

if ($this->total_cron_jobs_all > 0) {
	$custom_msg = 'Are you sure you want to clear all cron jobs across all daemons?'; 
	$url = 'cron_jobs/clear_all_cron_jobs';
	$title = 'Clear All Cron Jobs' . ' (' . $this->total_cron_jobs_all . ')';
	echo modal_confirm_action('clear_all_jobs', $title, $custom_msg, $url); 
} ?>

<div class="new-item">

	<?php if ($total_jobs > 0) { ?>
		<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#clear_jobs_school_data" title="Clear all jobs for this daemon"><i class="fa fa-trash"></i> Clear Jobs</button>
	<?php } ?>

	<?php if ($this->total_cron_jobs_all > 0) { ?>
		<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#clear_all_jobs" title="Clear all jobs"><i class="fa fa-trash-o"></i> Clear All Jobs</button>
	<?php } ?>

	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('cron_jobs'); ?>" ><i class="fa fa-tasks"></i> Daemons</a>
</div>


<?php 
//select options bulk actions 
$options_array = array(
	//'value' => 'Caption'
	'delete' => 'Delete'
); 
echo modal_bulk_actions('cron_jobs/bulk_actions_cron_jobs_school_data', $options_array); ?>

	<div class="table-scroll">
		<table id="cron_jobs_school_data_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			<input type="hidden" id="cron_name" value="<?php echo $cron_name; ?>" />
			
			<thead>
				<tr>
					<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
					<th> Actions </th>
					<th class="min-w-150"> Daemon Name </th>
					<th class="min-w-150"> School Name </th>
					<th class="min-w-150"> Recipient </th>
					<th> Status </th>
					<th> Time Run </th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>

<?php echo form_close(); ?>