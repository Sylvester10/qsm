
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="row">
		<div class="col-md-3">
			<div class="new-item">
				<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('admin/initiate_request'); ?>"><i class="fa fa-plus"></i> New Request</a>
			</div>
		</div>
		<div class="col-md-5 col-md-offset-4">
			<div class="pull-right m-t-10 m-b-10">
				<?php if ($total_pending_requests > 0) { ?>
					<p><i class="fa fa-spinner fa-spin text-primary"></i> Pending: <?php echo number_format($total_pending_requests); ?></p>
				<?php } else { ?>
					<p><i class="fa fa-spinner text-primary"></i> Pending: <?php echo number_format($total_pending_requests); ?></p>
				<?php } ?>
				<p><i class="fa fa-check-square-o text-success"></i> Approved: <?php echo number_format($total_approved_requests); ?></p>
				<p><i class="fa fa-ban text-danger"></i> Declined: <?php echo number_format($total_declined_requests); ?></p>
				<p><i class="fa fa-th-large"></i> All: <?php echo number_format($total_requests); ?></p>
			</div>
		</div>
	</div>

	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption' 
		'unapprove' => 'Unapprove',
		'delete' => 'Delete'
	); 
	echo modal_bulk_actions('admin/bulk_actions_requests', $options_array); ?>

	
		<table id="approved_requests_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			
			<thead>
				<tr>
					<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
					<th> Actions </th>
					<th> Ref ID </th>
					<th class="min-w-200"> Raised By </th>
					<th class="min-w-200"> Purpose </th>
					<th class="min-w-250"> Additional Info </th>
					<th> Urgency </th>
					<th> Amount </th>
					<th> Amount Approved </th>
					<th> Status </th>
					<th class="min-w-200"> Account Name </th>
					<th> Account Number </th>
					<th class="min-w-200"> Bank Name </th>
					<th> Date Raised </th>
					<th> Date Approved </th>
					<th class="min-w-200"> Approved By </th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	
<?php echo form_close(); ?>