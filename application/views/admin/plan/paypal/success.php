
<div class="row">
	<div class="col-md-6 col-sm-12 col-xs-12">
		<div class="panel panel-success">
			<div class="panel-heading">
				<i class="fa fa-paypal"></i> Transaction Status
			</div>
			
			<div class="panel-body">
	
				<h4 class="success"> Thank you for your payment. Your transaction has been completed, and a receipt for your purchase has been emailed to you. A summary of your payment is given below: </h4>
				
				<p>Payment for: <span><?php echo $payment_for; ?></span></p>
				<p>Transaction ID: <span><?php echo $txn_id; ?></span></p>
				<p>Amount Paid: <span>$<?php echo $amount; ?></span></p>
				<p>Payment Status: <span><?php echo $status; ?></span></p>
				
				<h4 class="success"> You may log into your account at <a href="https://www.paypal.com" target="_blank">www.paypal.com</a> to view details of this transaction. </h4> 
				
				<p><a class="btn btn-primary" href="<?php echo base_url($return_url); ?>">OK</a></p>
				
			</div>
		</div>
	</div>
</div>