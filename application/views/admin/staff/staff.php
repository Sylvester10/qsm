
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('school_staff/new_staff'); ?>"><i class="fa fa-plus"></i> New Staff</a>
	</div>


	<p>Note: Newly added staff must set their password <a href="<?php echo base_url('staff_acc/set_password'); ?>" target="_blank" class="text-success text-bold">here</a> before they can access their staff account.</p>


	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption'
		'delete' => 'Delete'
	); 
	echo modal_bulk_actions('school_staff/delete_bulk_staff', $options_array); ?>
	
	<div class="table-scroll">
		<table id="staff_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			
			<thead>
				<tr>
					<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
					<th> Actions </th>
					<th class="min-w-100"> Passport </th>
					<th class="min-w-200"> Name </th>
					<th class="min-w-200"> Bio Information </th>
					<th class="min-w-200"> Place Information </th>
					<th class="min-w-250"> Employment Information </th>
					<th class="min-w-250"> Contact Information </th>
					<th class="min-w-250"> Next of Kin Information </th>
					<th class="min-w-250"> Account Information </th>	
					<th class="min-w-100"> Religion </th>
					<th class="min-w-250"> Additional Info </th>
					<th class="min-w-150"> Role(s) </th>
					<th class="min-w-150"> Date Added </th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	
<?php echo form_close(); ?>