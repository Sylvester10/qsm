
<div class="row">

	<div class="col-md-4 col-sm-6 col-xs-12">
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
		</div>
	</div>
	

	<div class="col-md-8 col-sm-12 col-xs-12">
		
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
	