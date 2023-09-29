
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	
	<?php require "application/views/shared/fees/includes/fee_stats.php"; ?>


	<div class="row m-t-10 m-b-10">
		<div class="col-md-12">

			<?php if ($term == current_term) { ?>

				<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/collect_fees/'.$class_id); ?>"><i class="fa fa-money"></i> Collect Fees</a>

			<?php } ?>

			<?php $uri_segment = $session.'/'.$term.'/'.$class_id; ?>

			<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/full_payment/'.$uri_segment); ?>"><i class="fa fa-money"></i> Full Payment</a>
			
			<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/no_payment/'.$uri_segment); ?>"><i class="fa fa-money"></i> No Payment</a>
			
		</div>
	</div>
	

	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption' 
		'unapprove' => 'Unapprove Payment',
	); 
	echo modal_bulk_actions($this->c_controller.'/bulk_actions_fees/'.$session.'/'.$term.'/'.$class_id, $options_array); ?>

		<table id="partial_fees_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
			<input type="hidden" id="session" value="<?php echo $session; ?>" />
			<input type="hidden" id="term" value="<?php echo $term; ?>" />
			<input type="hidden" id="class_id" value="<?php echo $class_id; ?>" />
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			
			<thead>
				<tr>
					<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
					<th> Actions </th>
					<th class="min-w-150"> Admission ID </th>
					<th class="min-w-200"> Name </th>
					<th class="min-w-150"> Discount </th>
					<th class="min-w-150"> Amount Payable </th>
					<th class="min-w-150"> Installments Made </th>
					<th class="min-w-150"> Last Amount Paid </th>
					<th class="min-w-150"> Total Amount Paid </th>
					<th class="min-w-150"> Balance </th>
					<th class="min-w-300"> Validation Info </th>
					<th class="min-w-150"> Suspended </th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	
	<?php echo form_close(); ?>


	<?php //echo $this->fees_model->validate_partial_payment(14, 2); ?>