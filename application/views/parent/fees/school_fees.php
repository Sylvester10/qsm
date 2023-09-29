

<div class="row">

	<?php 
	foreach ($children as $c) { 

		$child_id = $c->id;
		$child_name = $this->common_model->get_student_fullname($child_id); 
		$total_fees = $this->fees_model_parent->get_total_fees_payable($child_id);
		$payment_status = $this->fees_model_parent->payment_status($child_id);
		$amount_paid = $this->fees_model_parent->fees_amount_paid($child_id);
		$balance = $this->fees_model_parent->fees_balance($child_id);
		$date_paid = $this->fees_model_parent->get_fees_date_paid($child_id);
		$transaction_id = $this->fees_model_parent->get_fee_transaction_id($child_id); 
		$last_installment = $this->fees_model_parent->get_fee_last_installment($child_id);
		$single_installment_details = $this->fees_model_parent->get_installment_details($child_id); ?>

		<div class="col-md-6 col-sm-12 col-xs-12">

			<div class="x_panel">
				<div class="x_title">
					<h4 class="page_title text-bold f-s-17"><?php echo $child_name; ?></h4>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">

					<div class="m-b-30">
						<h4 class="text-bold">Fee Details</h4>
						<p>Current Session: <?php echo current_session; ?></p>
						<p>Current Term: <?php echo current_term; ?></p>
						<p>Total Fees: <?php echo $total_fees; ?></p>
						<p>Due Date: <?php echo $current_term_fees_due_date; ?></p>

						<h4 class="m-t-30 text-bold">Payment Details</h4>
						<p>Payment Status: <?php echo $payment_status; ?></p>
						<p>Amount Paid: <?php echo $amount_paid; ?></p>
						<p>Balance: <?php echo $balance; ?></p>
						<p>Transaction Reference: <?php echo $transaction_id; ?></p>
						<p>Date Paid: <?php echo $date_paid; ?></p>
						
						
						<?php 
						//check if student paid in installment so we can display installment details
						if ($last_installment != NULL) {
						
							foreach ($single_installment_details as $d) {

								$installment = $d[0];
								$amount_paid = s_currency_symbol . number_format($d[1]);
								$transaction_id = $d[2];
								$date_paid = x_date($d[3]); ?>
								
								<div class="col-md-6 p-b-15">
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
										</div>
									</div>
								</div>

							<?php } 
							
						} ?>
						
					</div>

				</div>
			</div>

		</div>

	<?php } ?>

</div>