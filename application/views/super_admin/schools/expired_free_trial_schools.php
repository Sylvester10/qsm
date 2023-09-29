	
	<?php echo flash_message_success('status_msg'); ?>
	<?php echo flash_message_danger('status_msg_error'); ?>


	<?php require "application/views/super_admin/schools/includes/school_stats.php"; ?>

	
	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption'
	); 
	echo modal_bulk_actions('school_account/bulk_actions_schools', $options_array); ?>
		
		<table id="expired_free_trial_schools_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			
			<thead>
				<tr>
					<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
					<th> Actions </th>
					<th class="min-w-150"> School Name </th>
					<th class="min-w-100"> School ID </th>
					<th class="min-w-100"> Plan </th>
					<th class="min-w-100"> Data Records</th>
					<th class="min-w-100"> School Users</th>
					<th class="min-w-250"> Chief Admin Info</th>
					<th class="min-w-150"> School Location</th>
					<th class="min-w-100"> Country</th>
					<th class="min-w-100"> School Email</th>
					<th class="min-w-100"> School Phone No</th>
					<th class="min-w-100"> School Website</th>
					<th class="min-w-150"> Referrer </th>
					<th class="min-w-150"> Confirmation Status</th>
					<th class="min-w-100"> Date Installed</th>
					<th class="min-w-150"> Date Expired</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		
	<?php echo form_close(); ?>
