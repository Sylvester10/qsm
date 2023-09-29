
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<div class="new-item">

	<?php if ($term == current_term) { ?>

		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/collect_fees/'.$class_id); ?>"><i class="fa fa-money"></i> Collect Fees</a>

	<?php } ?>
	
	<?php $uri_segment = $session.'/'.$term.'/'.$class_id; ?>

	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/partial_payment/'.$uri_segment); ?>"><i class="fa fa-money"></i> All Partial Payment</a>
	
</div>


	<h3>Student Name: <?php echo $student_name; ?></h3>

	<div class="row m-t-10 m-b-10">
		<div class="col-md-5 p-b-15">
			<h4 class="text-bold">General Information</h4>
			Amount Payable: <?php echo $amount_payable; ?> <br />
			<?php 
			//show fees due date if selected term is current term
			if ($term == current_term) { ?>
				Fee Due Date: <?php echo $fees_due_date; ?> <br />
			<?php } ?>		
			Session: <?php echo $the_session; ?><br />
			Term: <?php echo $term; ?><br />
		</div>
		<div class="col-md-5 col-md-offset-2">
			<h4 class="text-bold">Payment Information</h4>
			Installments: <?php echo $f->last_installment; ?> <br />
			Last Amount Paid: <?php echo s_currency_symbol. number_format($f->last_amount_paid); ?> <br />
			Total Amount Paid: <?php echo s_currency_symbol. number_format($f->last_total_amount_paid); ?> <br />
			Last Date Paid: <?php echo x_date($f->date_paid); ?> <br />
		</div>
	</div>


	<div class="row m-t-30">

		<?php
		foreach ($single_installment_details as $d) {

			$installment = $d[0];
			$amount_paid = s_currency_symbol . number_format($d[1]);
			$transaction_id = $d[2];
			$date_paid = x_date($d[3]);
			if ($d[4] == 'true') {
				$validated = 	'<b class="text-success">Yes</b>';
				$validate_url = base_url($this->c_controller.'/invalidate_partial_payment/'.$payment_id.'/'.$installment);
				$validate_action = '<a class="btn btn-danger btn-sm" href="' . $validate_url . '" title="Invalidate this payment">Invalidate</a>';
			} else {
				$validated = 	'<b class="text-danger">No</b>';
				$validate_url = base_url($this->c_controller.'/validate_partial_payment/'.$payment_id.'/'.$installment);
				$validate_action = '<a class="btn btn-success btn-sm" href="' . $validate_url . '" title="Validate this payment">Validate</a>';
			} ?>
			
			<div class="col-md-3 p-b-15">
				<div class="x_panel">
					<div class="x_title">
						<h2 class="page_title"><?php echo get_ordinal_number($installment); ?> Installment <br />
						</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						Amount Paid: <?php echo $amount_paid; ?> <br />
						Transaction ID: <?php echo $transaction_id; ?> <br />
						Date Paid: <?php echo $date_paid; ?> <br />
						Validated: <?php echo $validated; ?> <br />
						
						<?php 
						//allow validation action if admin
						if ($this->c_user == 'admin') { ?> 
							<div class="m-t-10">
								<?php echo $validate_action; ?>
							</div>
						<?php } ?>
						
					</div>
				</div>
			</div>

		<?php } ?>

	</div>
