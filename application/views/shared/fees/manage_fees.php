
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<?php require "application/views/shared/fees/modals/import_fees.php";  ?>
	
	<?php 
	if (count($fee_details) > 0) { 
		require "application/views/shared/fees/modals/clear_fees.php";
	} ?>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/new_class_fees'); ?>"><i class="fa fa-plus"></i> New Class Fee</a>
		<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#import_fees" title="Import fees from a previous session and term to current session and term"><i class="fa fa-upload"></i> Import Fees</button>

		<?php if (count($fee_details) > 0) { ?>
			<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#clear_fees" title="Clear all fees for current session and term"><i class="fa fa-trash"></i> Clear Fees</button>
		<?php } ?>

	</div>

	<?php if (count($fee_details) == 0) { ?>

		<h3 class="text-danger">No fee data available for current term</h3>

	<?php } else { ?>

		<div class="row table-scroll">
			<div class="col-md-12">

				<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
					<thead>
						<tr>
							<th class="w-15-p"> S/N </th>
							<th class="min-w-100"> Class </th>
							<th class="min-w-150"> Class Population </th>
							<th class="min-w-150"> Students On Discount </th>
							<th class="min-w-150"> Total Fees </th>
							<th class="min-w-250"> Fee Breakdown </th>
							<th class="min-w-100"> Actions </th>	
						</tr>
					</thead>
					<tbody>

						<?php
						$count = 1;
						foreach ($fee_details as $y) { 

							$class_id = $y->class_id;
							$class = $this->common_model->get_class_details($class_id)->class;
							$total_students_on_discount = count($this->fees_model->get_students_on_fee_discount_in_class($class_id));
							$fee_breakdown = $this->fees_model->get_fee_breakdown($y->id);
							
							echo modal_delete_confirm($y->id, "Fee Details for {$class}", 'fee details', $this->c_controller.'/delete_fee_details');

							//require "application/views/shared/fees/modals/edit_fee.php"; ?>

							<tr>	
								<td><?php echo $count; ?></td>
								<td><?php echo $class; ?></td>
								<td><?php echo $this->common_model->get_class_population($y->class_id); ?></td>
								<td>
									<?php
									if ($total_students_on_discount > 0) { ?>
										<div class="pull-right">
	                                        <a href="<?php echo base_url($this->c_controller.'/fee_discount_categories'); ?>">View</a>
	                                    </div>
	                                <?php } ?>
									<?php echo $total_students_on_discount; ?>
								</td>
								<td><?php echo s_currency_symbol . number_format($y->total_fees_payable); ?></td>
								<td><?php echo $fee_breakdown; ?></td>
								<td class="w-15-p text-center">
									<a class="btn btn-primary btn-sm" href="<?php echo base_url($this->c_controller.'/edit_class_fees/'.$y->id); ?>" title="Edit fees"><i class="fa fa-pencil"></i></a>
									<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete<?php echo $y->id; ?>" title="Delete fees"><i class="fa fa-trash"></i></button>
								</td>
							</tr>

							<?php $count++;

						} ?>

					</tbody>
				</table>

	  		</div><!-- /.col-md-8 -->
		</div><!-- /.row -->

	<?php } ?>
