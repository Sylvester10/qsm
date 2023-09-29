
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('prs_admin/initiate_request'); ?>"><i class="fa fa-plus"></i> New Request</a>
	</div>

	<?php require "application/views/admin/prs/includes/request_stats.php"; ?>

	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption' 
		'unapprove' => 'Unapprove',
		'delete' => 'Delete'
	); 
	echo modal_bulk_actions('prs_admin/bulk_actions_requests', $options_array); ?>

	
		<table id="approved_requests_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
			<input type="hidden" id="session" value="<?php echo $session; ?>" />
			<input type="hidden" id="term" value="<?php echo $term; ?>" />
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			
			<thead>
				<tr>
					<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
					<th> Actions </th>
					<th class="min-w-100"> Ref ID </th>
					<th class="min-w-300"> Request Information </th>
					<th class="min-w-100"> Amount </th>
					<th class="min-w-150"> Raised By </th>
					<th class="min-w-250"> Account Information </th>
					<th class="min-w-250"> Approval Information </th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	
	<?php echo form_close(); ?>